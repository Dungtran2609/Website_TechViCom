<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\ShippingMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdminOrderController extends Controller
{
    /**
     * Hiển thị danh sách các đơn hàng.
     */
    public function index(Request $request)
    {
        $orders = Order::with(['user:id,name', 'orderItems.productVariant.product'])
            ->when($request->search, function ($query, $search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        // Payment method mapping
        $paymentMap = [
            'credit_card' => 'Thẻ tín dụng',
            'bank_transfer' => 'Chuyển khoản',
            'cod' => 'COD (Thanh toán khi nhận hàng)',
            'vietqr' => 'VietQR'
        ];

        $orderData = $orders->map(function($order) use ($paymentMap) {
            // Lấy ảnh đầu tiên
            $image = null;
            if ($order->orderItems->isNotEmpty()) {
                $firstItem = $order->orderItems->first();
                if (!empty($firstItem->image_product)) {
                    $image = $firstItem->image_product;
                } elseif ($firstItem->productVariant && $firstItem->productVariant->product) {
                    $product = $firstItem->productVariant->product;
                    if ($product->images->isNotEmpty()) {
                        $image = $product->images->first()->image_path;
                    }
                }
            }

            // Lấy tên tất cả sản phẩm
            $productNames = $order->orderItems->map(function($item) {
                return $item->productVariant->product->name ?? 'N/A';
            })->implode(', ');

            // Tính tổng số lượng
            $totalQuantity = $order->orderItems->sum('quantity');

            return [
                'id' => $order->id,
                'user_name' => $order->customer_name,
                'image' => $image ? asset('storage/' . ltrim($image, '/')) : null,
                'product_names' => $productNames,
                'total_quantity' => $totalQuantity,
                'final_total' => $order->final_total,
                'status' => $order->status,
                'payment_method' => $order->payment_method,
                'payment_method_vietnamese' => $paymentMap[$order->payment_method] ?? $order->payment_method,
                'created_at' => $order->created_at->format('d/m/Y H:i'),
            ];
        });

        return view('admin.orders.index', [
            'orders' => $orderData,
            'pagination' => $orders,
        ]);
    }

    /**
     * Hiển thị chi tiết một đơn hàng.
     */
    public function show(Order $order) // Sử dụng Route Model Binding
    {
        // Eager-load quan hệ cần thiết
        $order = Order::with([
            'user:id,name,email,phone_number',
            'user.addresses',
            'address',
            'orderItems.productVariant.product.images',
            'orderItems.productVariant.attributeValues.attribute',
            'shippingMethod:id,name',
            'coupon'
        ])->findOrFail($order->id);

        $shippingMethods = ShippingMethod::all();

        // Lấy địa chỉ giao hàng theo thứ tự ưu tiên:
        // 1. recipient_address trong order (địa chỉ thực tế giao hàng)
        // 2. address relationship (UserAddress) - chỉ cho user đăng nhập
        // 3. địa chỉ mặc định của user - chỉ cho user đăng nhập
        
        if (!empty($order->recipient_address)) {
            // Nếu có địa chỉ text trực tiếp trong order (ưu tiên cao nhất)
            $address = $order->recipient_address;
            $city = ''; // Không phân tích chi tiết từ text
            $district = '';
            $ward = '';
        } elseif ($order->address) {
            // Địa chỉ từ UserAddress relationship (chỉ có khi user đăng nhập)
            $city = $order->address->city ?? '';
            $district = $order->address->district ?? '';
            $ward = $order->address->ward ?? '';
            $address = $order->address->address_line ?? '';
        } elseif (!$order->isGuestOrder() && $order->user && $order->user->addresses) {
            // Fallback: lấy địa chỉ mặc định của user (chỉ khi có user)
            $defaultAddress = $order->user->addresses
                ->firstWhere('is_default', true)
                ?? $order->user->addresses->first();
            
            $city = $defaultAddress->city ?? '';
            $district = $defaultAddress->district ?? '';
            $ward = $defaultAddress->ward ?? '';
            $address = $defaultAddress->address_line ?? '';
        } else {
            // Khách vãng lai hoặc không có địa chỉ
            $city = '';
            $district = '';
            $ward = '';
            $address = '';
        }

        // Tính subtotal
        $subtotal = $order->orderItems->sum(function ($item) {
            $variant = $item->productVariant;
            $price = $variant->sale_price ?? $variant->price ?? 0;
            return ($item->quantity ?? 0) * $price;
        });

        // Tính phí vận chuyển
        if ($order->shipping_method_id === 1) {
            $shippingFee = 0; // Lấy tại cửa hàng
        } else {
            // Kiểm tra điều kiện miễn phí ship: Hà Nội + >= 3tr
            $isHanoi = (stripos($city, 'hà nội') !== false) || (stripos($city, 'hanoi') !== false);
            $shippingFee = ($isHanoi && $subtotal >= 3000000) ? 0 : 60000;
        }

        // Tính giảm giá
        $couponDiscount = $this->calculateCouponDiscount(
            $order->coupon,
            $subtotal + $shippingFee
        );

        // Tổng cuối
        $finalTotal = $subtotal + $shippingFee - $couponDiscount;

        // Ánh xạ trạng thái & phương thức thanh toán
        $statusMap = [
            'pending' => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã giao',
            'delivered' => 'Đã nhận',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã trả hàng',
        ];
        $paymentMap = [
            'credit_card' => 'Thẻ tín dụng/ghi nợ',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cod' => 'Thanh toán khi nhận hàng',
        ];

        // Chuẩn bị data cho view
        $orderData = [
            'id' => $order->id,
            'user_name' => $order->customer_name,
            'user_email' => $order->customer_email,
            'user_phone' => $order->customer_phone,
            'is_guest' => $order->isGuestOrder(),
            'recipient_name' => $order->recipient_name,
            'recipient_phone' => $order->recipient_phone,
            'address' => $address,
            'ward' => $ward,
            'district' => $district,
            'city' => $city,
            'shipping_method_id' => $order->shipping_method_id,
            'shipping_method_name' => $order->shippingMethod->name ?? 'Chưa chọn',
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'coupon_code' => $order->coupon->code ?? null,
            'coupon_discount' => $couponDiscount,
            'final_total' => $finalTotal,
            'status' => $order->status,
            'shipped_at' => $order->shipped_at,
            'status_vietnamese' => $statusMap[$order->status] ?? $order->status,
            'payment_method' => $order->payment_method,
            'payment_method_vietnamese' => $paymentMap[$order->payment_method] ?? $order->payment_method,
            'payment_status' => $order->payment_status,
            'payment_status_vietnamese' => Order::PAYMENT_STATUSES[$order->payment_status]
                ?? $order->payment_status,
            'payment_statuses' => Order::PAYMENT_STATUSES,  // ← đây
            'created_at' => Carbon::parse($order->created_at)->format('d/m/Y H:i'),
            'order_items' => $order->orderItems->map(function ($item) {
                $variant = $item->productVariant;
                $prod = $variant->product;
                $price = $variant->sale_price ?? $variant->price;
                if (!empty($item->image_product)) {
                    $path = $item->image_product;
                } elseif (!empty($variant->image)) {
                    $path = $variant->image;
                } else {
                    $path = null;
                }
                $imageUrl = $path
                    ? asset('storage/' . ltrim($path, '/'))
                    : null;



                return [
                    'image_product_url' => $imageUrl,
                    'brand_name' => $prod->brand?->name ?? '',
                    'category_name' => $prod->category?->name ?? '',
                    'stock' => $variant->stock ?? 0,
                    'weight' => $variant->weight ?? 0,
                    'dimensions' => $variant->dimensions ?? '',
                    'attributes' => $variant->attributeValues->map(fn($a) => [
                        'name' => $a->attribute->name,
                        'value' => $a->value,
                    ])->toArray(),
                    'name_product' => $prod->name,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total' => ($item->quantity ?? 0) * $price,
                ];
            })->toArray(),
        ];

        return view('admin.orders.show', compact('orderData'));
    }

    /**
     * Hiển thị form để chỉnh sửa đơn hàng.
     */
    public function edit(Order $order) // Sử dụng Route Model Binding
    {
        // Eager-load
        $order = Order::with([
            'user',
            'user.addresses',
            'address',
            'orderItems.productVariant.product.images',
            'orderItems.productVariant.attributeValues.attribute',
            'shippingMethod',
            'coupon',
        ])->findOrFail($order->id);

        // Lấy dữ liệu phụ trợ cho form
        $shippingMethods = ShippingMethod::all();
        $coupons = Coupon::where('status', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        // Lấy danh sách trạng thái từ hằng số trong Model để truyền sang view
        $orderStatuses = Order::ORDER_STATUSES;
        $paymentStatuses = Order::PAYMENT_STATUSES;
        // Tính subtotal
        $subtotal = $order->orderItems->sum(
            fn($item) =>
            ($item->quantity ?? 0)
            * ($item->productVariant->sale_price ?? $item->productVariant->price ?? 0)
        );

        // Tính phí ship (Hà Nội >=3tr miễn, else 60k)
        // Giả sử phương thức lấy tại cửa hàng có ID = 1
        $isHanoi = false;
        if (!$order->isGuestOrder() && $order->user && $order->user->addresses) {
            $defaultUserAddress = $order->user->addresses->firstWhere('is_default');
            $isHanoi = $defaultUserAddress && (stripos($defaultUserAddress->city, 'hà nội') !== false);
        } elseif ($order->address) {
            $isHanoi = stripos($order->address->city, 'hà nội') !== false;
        }
        
        $shippingFee = ($order->shipping_method_id === 1)
            ? 0
            : (($subtotal >= 3000000 && $isHanoi) ? 0 : 60000);

        // Tính giảm giá coupon
        $couponDiscount = $order->coupon_discount
            ?? $this->calculateCouponDiscount($order->coupon, $subtotal + $shippingFee);

        // Tổng cuối
        $finalTotal = $subtotal + $shippingFee - $couponDiscount;

        // Chuẩn bị dữ liệu cho view
        $orderData = [
            'id' => $order->id,
            'user_name' => $order->user->name ?? $order->customer_name ?? 'Guest',
            'user_email' => $order->user->email ?? $order->customer_email ?? 'N/A',
            'status' => $order->status,
            'status_vietnamese' => $statusMap[$order->status] ?? $order->status,
            'created_at' => Carbon::parse($order->created_at)->format('d/m/Y H:i'),
            'payment_method' => $order->payment_method,
            'payment_method_vietnamese' => $paymentMap[$order->payment_method] ?? $order->payment_method,
            'payment_status' => $order->payment_status,
            'payment_statuses' => Order::PAYMENT_STATUSES,
            'recipient_name' => $order->recipient_name,
            'recipient_phone' => $order->recipient_phone,
            'recipient_address' => $order->recipient_address,
            'shipping_method_id' => $order->shipping_method_id,
            'shipping_fee' => $shippingFee,
            'subtotal' => $subtotal,
            'coupon_id' => $order->coupon_id,
            'coupon_discount' => $couponDiscount,
            'final_total' => $finalTotal,
            'shipped_at' => $order->shipped_at,
            'order_items' => $order->orderItems->map(fn($item) => [
                'id' => $item->id,
                'name_product' => $item->productVariant->product->name,
                'quantity' => $item->quantity,
                'price' => $item->productVariant->sale_price ?? $item->productVariant->price,
                'total' => $item->quantity * ($item->productVariant->sale_price ?? $item->productVariant->price),
                'stock' => $item->productVariant->stock,
                'attributes' => $item->productVariant->attributeValues->map(fn($a) => [
                    'name' => $a->attribute->name,
                    'value' => $a->value,
                ])->toArray(),
                'image' => optional($item->productVariant->images->first())->image_path,
            ])->toArray(),
            'shipping_methods' => $shippingMethods,
            'coupons' => $coupons,
        ];

        return view('admin.orders.update', compact('orderData'));
    }

    /**
     * Cập nhật thông tin đơn hàng từ form của admin.
     * Đổi tên từ updateOrders -> update cho chuẩn RESTful.
     */
    public function update(Request $request, Order $order) // Sử dụng Route Model Binding
    {
        $order = Order::findOrFail($order->id);
        $oldStatus = $order->status;

        // Các giá trị hợp lệ
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'];
        $validPayments = ['credit_card', 'bank_transfer', 'cod'];
        $validPayments = ['credit_card', 'bank_transfer', 'cod'];
        $validPaymentStatus = array_keys(Order::PAYMENT_STATUSES);
        // Lấy dữ liệu cần thiết
        $data = $request->only([
            'status',
            'payment_status',
            'recipient_name',
            'recipient_phone',
            'recipient_address',
            'payment_method',
            'shipped_at',
            'order_items',
            'shipping_method_id',
            'coupon_id',
            'to_district_id',
            'to_ward_code',
            'guest_name',
            'guest_email', 
            'guest_phone',
        ]);

        // Validation
        $validator = Validator::make($data, [
            'status' => 'required|in:' . implode(',', $validStatuses),
            'payment_status' => 'nullable|in:' . implode(',', $validPaymentStatus),
            'recipient_name' => 'nullable|string|max:255',
            'recipient_phone' => 'nullable|string|max:20',
            'recipient_address' => 'nullable|string|max:500',
            'payment_method' => 'nullable|in:' . implode(',', $validPayments),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        // --- LOGIC NGHIỆP VỤ QUAN TRỌNG ---

        // 1. Ngăn chặn admin thay đổi trạng thái thanh toán của đơn hàng online (vietqr)
        if ($order->payment_method === 'vietqr') {
            unset($validatedData['payment_status']); // Xóa trường này khỏi dữ liệu cập nhật
        }

        // 2. Ngăn chặn admin xử lý đơn hàng online khi chưa được xác nhận thanh toán
        if ($order->payment_method === 'vietqr' && $order->payment_status !== 'paid') {
            if (in_array($validatedData['status'], ['processing', 'shipped'])) {
                return back()->with('error', 'Không thể xử lý đơn hàng thanh toán online khi chưa được xác nhận thanh toán.');
            }
        }

        // --- CẬP NHẬT DỮ LIỆU ---

        // Cập nhật trạng thái xử lý đơn hàng
        $order->status = $validatedData['status'];

        // Tự động cập nhật ngày giao hàng nếu trạng thái là "shipped" và chưa có ngày
        if ($validatedData['status'] === 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        }

        // Chỉ cho phép cập nhật trạng thái thanh toán nếu là đơn COD
        if (isset($validatedData['payment_status']) && $order->payment_method === 'cod') {
            $order->payment_status = $validatedData['payment_status'];

            // Tự động chuyển trạng thái đơn hàng thành 'delivered' khi admin xác nhận đã thu tiền COD
            if($validatedData['payment_status'] === 'paid' && $order->status !== 'delivered'){
                $order->status = 'delivered';
            }
        }

        $order->save();

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    /**
     * Xóa mềm một đơn hàng.
     */
    public function destroy(Order $order) // Sử dụng Route Model Binding
    {
        $allowedStatusesToDelete = ['cancelled', 'returned', 'delivered'];

        if (!in_array($order->status, $allowedStatusesToDelete)) {
            return back()->with('error', 'Chỉ có thể xóa đơn hàng đã hoàn tất (Đã nhận, Đã hủy, Đã trả hàng).');
        }

        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được chuyển vào thùng rác.');
    }

    /**
     * Hiển thị danh sách các đơn hàng đã bị xóa mềm.
     */
    public function trashed()
    {
        $trashedOrders = Order::onlyTrashed()
            ->with(['user:id,name,email', 'orderItems.productVariant.product.images'])
            ->latest()
            ->paginate(15);

        // Status mapping
        $statusMap = [
            'pending' => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý', 
            'shipped' => 'Đã giao',
            'delivered' => 'Đã nhận',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã trả hàng'
        ];

        // Format data như method index
        $orders = $trashedOrders->map(function($order) use ($statusMap) {
            // Lấy ảnh đầu tiên của sản phẩm đầu tiên
            $firstImage = null;
            if ($order->orderItems->isNotEmpty()) {
                $firstProduct = $order->orderItems->first()->productVariant->product ?? null;
                if ($firstProduct && $firstProduct->images->isNotEmpty()) {
                    $firstImage = $firstProduct->images->first()->image_path;
                }
            }

            // Lấy tên tất cả sản phẩm
            $productNames = $order->orderItems->map(function($item) {
                return $item->productVariant->product->name ?? 'N/A';
            })->implode(', ');

            // Tính tổng số lượng
            $totalQuantity = $order->orderItems->sum('quantity');

            return [
                'id' => $order->id,
                'user_name' => $order->user->name ?? 'Guest',
                'user_email' => $order->user->email ?? 'N/A',
                'image' => $firstImage,
                'product_names' => $productNames,
                'total_quantity' => $totalQuantity,
                'final_total' => $order->final_total,
                'status' => $order->status,
                'status_vietnamese' => $statusMap[$order->status] ?? $order->status,
                'created_at' => $order->created_at->format('d/m/Y H:i'),
                'deleted_at' => $order->deleted_at->format('d/m/Y H:i'),
            ];
        });

        return view('admin.orders.trashed', [
            'orders' => $orders,
            'pagination' => $trashedOrders
        ]);
    }

    /**
     * Khôi phục một đơn hàng đã bị xóa mềm.
     */
    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('admin.orders.trashed')->with('success', 'Đơn hàng đã được khôi phục thành công.');
    }

    /**
     * Xóa vĩnh viễn một đơn hàng.
     */
    public function forceDelete($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($order) {
            $order->orderItems()->delete();
            $order->forceDelete();
        });

        return redirect()->route('admin.orders.trashed')->with('success', 'Đơn hàng đã bị xóa vĩnh viễn.');
    }

    /**
     * Hiển thị danh sách yêu cầu trả hàng/hủy đơn.
     */
    public function returnsIndex(Request $request)
    {
        $returns = OrderReturn::with(['order.user'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.returns.index', compact('returns'));
    }

    /**
     * Xử lý yêu cầu trả hàng/hủy đơn (Phê duyệt hoặc Từ chối).
     */
    public function processReturn(Request $request, OrderReturn $return) // Sử dụng Route Model Binding
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Cập nhật trạng thái yêu cầu trả hàng
        $return->status = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        $return->processed_at = now();
        $return->admin_note = $validated['admin_note'];

        // Nếu phê duyệt, đồng bộ trạng thái của đơn hàng gốc
        if ($validated['action'] === 'approve') {
            $order = $return->order;

            // Xác định trạng thái mới cho đơn hàng
            $newOrderStatus = null;
            if ($return->type === 'cancel' && $order->status === 'pending') {
                $newOrderStatus = 'cancelled';
            } elseif ($return->type === 'return' && $order->status === 'delivered') {
                $newOrderStatus = 'returned';
            }

            if ($newOrderStatus) {
                $order->status = $newOrderStatus;
                $order->save();
            } else {
                return back()->with('error', 'Không thể xử lý yêu cầu dựa trên trạng thái hiện tại của đơn hàng.');
            }
        }

        $return->save();
        $message = $validated['action'] === 'approve' ? 'đã được phê duyệt.' : 'đã bị từ chối.';
        return redirect()->route('admin.orders.returns.index')->with('success', "Yêu cầu #{$return->id} $message");
    }

    /**
     * Helper function để tính toán giảm giá từ coupon.
     */
    private function calculateCouponDiscount($coupon, $orderTotal)
    {
        if (!$coupon || !$coupon->status || now()->isBefore($coupon->start_date) || now()->isAfter($coupon->end_date)) {
            return 0;
        }

        if ($coupon->min_order_value && $orderTotal < $coupon->min_order_value) {
            return 0;
        }

        if ($coupon->max_order_value && $orderTotal > $coupon->max_order_value) {
            return 0;
        }

        if ($coupon->discount_type === 'percentage') {
            $discount = ($orderTotal * $coupon->value) / 100;
            return ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount)
                ? $coupon->max_discount_amount
                : $discount;
        }

        return $coupon->value;
    }
}
