<?php

namespace App\Http\Controllers\Client\Orders;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientOrderController extends Controller
{
    /**
     * Xác nhận đã nhận hàng (khi đã giao hoặc đang giao)
     */
    public function confirmReceived($id)
    {
        $user  = Auth::user();
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        if (!in_array($order->status, ['delivered', 'shipped'])) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ xác nhận khi đơn hàng đã giao hoặc đang giao!'
            ], 400);
        }

        $order->status = 'received';
        $order->received_at = now(); // Lưu thời gian nhận hàng
        $order->save();

        return response()->json(['success' => true, 'message' => 'Đã xác nhận nhận hàng!']);
    }

    /**
     * Danh sách đơn hàng: tabs trạng thái + tìm kiếm + phân trang
     */
    public function index(Request $request)
    {
        $user   = Auth::user();
        $search = trim((string) $request->input('q', ''));
        $status = $request->input('status', 'all');

        // Debug: Log parameters
        Log::info('Order filter parameters', [
            'status' => $status,
            'search' => $search,
            'all_params' => $request->all()
        ]);

        // Thứ tự trạng thái để ORDER BY FIELD
        $statusOrder = ['pending', 'processing', 'shipped', 'delivered', 'received', 'cancelled', 'returned'];

        $query = Order::with(['orderItems.productVariant.product', 'returns'])
            ->where('user_id', $user->id);

        // Lọc theo trạng thái (DB-side)
        if ($status !== 'all' && in_array($status, $statusOrder, true)) {
            $query->where('status', $status);
            Log::info('Filtering by status', ['status' => $status]);
        } else {
            Log::info('No status filter applied', ['status' => $status]);
        }

        // Tìm kiếm: DH000123 / ID / tên sản phẩm
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                // "DH000123" -> lấy 123
                if (preg_match('/^DH0*([0-9]+)$/i', $search, $m)) {
                    $q->where('id', (int) $m[1]);
                    return;
                }

                // Toàn số: id chính xác hoặc prefix id
                if (ctype_digit($search)) {
                    $q->where('id', (int) $search)
                        ->orWhereRaw('CAST(id AS CHAR) LIKE ?', [$search . '%']);
                    return;
                }

                // Tên sản phẩm: tìm theo name_product trong order_items
                $q->orWhereHas('orderItems', function ($oi) use ($search) {
                    $oi->where('name_product', 'LIKE', '%' . $search . '%');
                });
            });
        }

        // Sắp xếp theo trạng thái rồi thời gian tạo (DB-side)
        $orders = $query
            ->orderByRaw("FIELD(status, '" . implode("','", $statusOrder) . "'), created_at DESC")
            ->paginate(10)
            ->withQueryString();

        // Debug: Log query and results
        Log::info('Order query results', [
            'total_orders' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'per_page' => $orders->perPage(),
            'status_counts' => $orders->getCollection()->groupBy('status')->map->count()
        ]);

        // (Tuỳ view cần) số lượng theo từng trạng thái cho badge/tabs
        $counts = Order::where('user_id', $user->id)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();
        $counts['all'] = Order::where('user_id', $user->id)->count();

        return view('client.accounts.orders', [
            'orders' => $orders,
            'status' => $status,
            'search' => $search,
            'counts' => $counts, // nếu muốn hiển thị badge số lượng ở tab
        ]);
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function show($id)
    {
        $user = Auth::user();
        $order = Order::with([
            'orderItems.productVariant.product.images',
            'shippingMethod',
            'coupon'
        ])->where('user_id', $user->id)->findOrFail($id);

        return view('client.orders.show', [
            'order'            => $order,
            'paymentStatusMap' => Order::PAYMENT_STATUSES,
            'shippingFee'      => $order->shipping_fee,
        ]);
    }

    /**
     * Hiển thị form đặt hàng
     */
    public function create()
    {
        $variants        = ProductVariant::with('product')->get();
        $shippingMethods = ShippingMethod::all();
        $coupons = Coupon::where('status', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('client.orders.create', compact('variants', 'shippingMethods', 'coupons'));
    }

    /**
     * Lưu đơn hàng mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_name'           => 'required|string|max:255',
            'recipient_phone'          => 'required|string|max:20',
            'recipient_email'          => 'required|email|max:255',
            'recipient_address'        => 'required|string|max:500',
            'shipping_method_id'       => 'required|exists:shipping_methods,id',
            'payment_method'           => 'required|in:credit_card,bank_transfer,cod',
            'coupon_id'                => 'nullable|exists:coupons,id',
            'order_items'              => 'required|array|min:1',
            'order_items.*.variant_id' => 'required|exists:product_variants,id',
            'order_items.*.quantity'   => 'required|integer|min:1',
            'order_items.*.price'      => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $subtotal = collect($request->order_items)->sum(function ($itm) {
                $variant = ProductVariant::findOrFail($itm['variant_id']);
                $price   = $itm['price'] ?? $variant->sale_price ?? $variant->price ?? 0;
                return $price * $itm['quantity'];
            });

            $shipId      = (int) $request->shipping_method_id;
            $shippingFee = $shipId == 1 ? 0 : (($subtotal >= 3000000) ? 0 : 60000);

            $couponDiscount = 0;
            if ($request->filled('coupon_id')) {
                $coupon = Coupon::findOrFail($request->coupon_id);
                $couponDiscount = $this->calculateCouponDiscount($coupon, $subtotal + $shippingFee);
            }

            $order = Order::create([
                'user_id'            => $user->id,
                'recipient_name'     => $request->recipient_name,
                'recipient_phone'    => $request->recipient_phone,
                'recipient_email'    => $request->recipient_email,
                'recipient_address'  => $request->recipient_address,
                'shipping_method_id' => $shipId,
                'payment_method'     => $request->payment_method,
                'payment_status'     => 'pending',
                'coupon_id'          => $request->coupon_id,
                'shipping_fee'       => $shippingFee,
                'total_amount'       => $subtotal,
                'coupon_discount'    => $couponDiscount,
                'final_total'        => $subtotal + $shippingFee - $couponDiscount,
                'status'             => 'pending',
                'created_at'         => Carbon::now(),
            ]);

            foreach ($request->order_items as $itm) {
                $variant = ProductVariant::findOrFail($itm['variant_id']);
                $price   = $itm['price'] ?? $variant->sale_price ?? $variant->price ?? 0;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'variant_id' => $itm['variant_id'],
                    'quantity'   => $itm['quantity'],
                    'price'      => $price,
                ]);

                $variant->decrement('stock', $itm['quantity']);
            }

            DB::commit();

            return redirect()->route('client.orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());

            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.')->withInput();
        }
    }

    /** Hủy đơn (chỉ khi pending) */
    public function cancel($id)
    {
        $user  = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        // Chặn hủy đơn hàng VNPay
        if ($order->payment_method === 'bank_transfer') {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng thanh toán VNPay không thể hủy. Vui lòng liên hệ bộ phận hỗ trợ để được hỗ trợ!'
            ], 403);
        }

        $request = request();
        $cancelReason = $request->input('cancel_reason', 'Khách hủy');
        $clientNote = $request->input('client_note', '');

        // Hủy đơn hàng ngay lập tức
        $order->status = 'cancelled';
        $order->save();

        // Tạo record lưu lý do hủy
        OrderReturn::create([
            'order_id'     => $order->id,
            'type'         => 'cancel',
            'reason'       => $cancelReason,
            'client_note'  => $clientNote,
            'status'       => 'approved', // Tự động approved
            'requested_at' => now(),
            'approved_at'  => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được hủy thành công!'
        ]);
    }

    /** Xác nhận thanh toán (chỉ khi pending) */
    public function confirmPayment($id)
    {
        $user  = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->findOrFail($id);

        $order->payment_status = 'paid';
        $order->save();

        return back()->with('success', 'Đã xác nhận thanh toán!');
    }

    /** Yêu cầu trả hàng (chỉ khi đã delivered) */
    public function requestReturn($id)
    {
        $user  = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->findOrFail($id);

        $request = request();
        $returnReason = $request->input('cancel_reason', 'Khách hàng yêu cầu trả');
        $clientNote = $request->input('client_note', '');
        $selectedProducts = $request->input('selected_products', []);

        // Đảm bảo selectedProducts là array
        if (!is_array($selectedProducts)) {
            $selectedProducts = [$selectedProducts];
        }

        // Kiểm tra chọn sản phẩm
        if (empty($selectedProducts)) {
            return response()->json([
                'success' => false, 
                'message' => 'Vui lòng chọn ít nhất một sản phẩm để đổi/trả!'
            ], 400);
        }

        // Kiểm tra upload video
        if (!$request->hasFile('return_video')) {
            return response()->json([
                'success' => false, 
                'message' => 'Vui lòng upload video chứng minh!'
            ], 400);
        }

        // Xử lý upload ảnh cho từng sản phẩm
        $productImages = [];
        foreach ($selectedProducts as $productId) {
            $productImages[$productId] = [];
            
            if ($request->hasFile("product_images.{$productId}")) {
                foreach ($request->file("product_images.{$productId}") as $image) {
                    if ($image->isValid()) {
                        $imageName = 'return_product_' . $productId . '_' . time() . '_' . $image->getClientOriginalName();
                        $imagePath = $image->storeAs('returns/images', $imageName, 'public');
                        $productImages[$productId][] = $imagePath;
                    }
                }
            }
            
            // Kiểm tra có ảnh cho sản phẩm này không
            if (empty($productImages[$productId])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vui lòng upload ảnh chứng minh cho tất cả sản phẩm được chọn!'
                ], 400);
            }
        }

        // Xử lý upload video
        $videoPath = null;
        if ($request->hasFile('return_video')) {
            $video = $request->file('return_video');
            if ($video->isValid()) {
                $videoName = 'return_' . time() . '_' . $video->getClientOriginalName();
                $videoPath = $video->storeAs('returns/videos', $videoName, 'public');
            }
        }

        // Tạo record trả hàng
        $orderReturn = OrderReturn::create([
            'order_id'     => $order->id,
            'type'         => 'return',
            'reason'       => $returnReason,
            'client_note'  => $clientNote,
            'status'       => 'pending',
            'requested_at' => now(),
            'images'       => $productImages,
            'video'        => $videoPath,
            'selected_products' => $selectedProducts,
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Yêu cầu đổi/trả hàng đã được gửi với đầy đủ chứng minh!'
        ]);
    }

    /** Xác nhận đã nhận hàng (khi đã giao hoặc đang giao) */
    public function confirmReceipt($id)
    {
        $user  = Auth::user();
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        if (!in_array($order->status, ['delivered', 'shipped'])) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ xác nhận khi đơn hàng đã giao hoặc đang giao!'
            ], 400);
        }

        $order->status = 'received';
        $order->received_at = now(); // Lưu thời gian nhận hàng
        $order->save();

        return response()->json(['success' => true, 'message' => 'Đã xác nhận nhận hàng!']);
    }

    /** Helper */
    private function calculateCouponDiscount($coupon, $orderTotal)
    {
        if (
            !$coupon || !$coupon->status ||
            now()->lt($coupon->start_date) ||
            now()->gt($coupon->end_date)
        ) {
            return 0;
        }

        $discount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discount = ($orderTotal * $coupon->value) / 100;
            if ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount) {
                $discount = $coupon->max_discount_amount;
            }
        } else {
            $discount = $coupon->value;
        }

        if (
            ($coupon->min_order_value && $orderTotal < $coupon->min_order_value) ||
            ($coupon->max_order_value && $orderTotal > $coupon->max_order_value)
        ) {
            return 0;
        }

        return $discount;
    }
}
