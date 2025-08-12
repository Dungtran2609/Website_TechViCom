<?php
namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\ShippingMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminOrderController extends Controller
{
    /** Quy ước ID phương thức giao hàng (khớp Client) */
    private const DELIVERY_ID = 1; // Giao hàng tận nơi
    private const PICKUP_ID = 2; // Nhận tại cửa hàng

    /** Ngưỡng freeship & phí ship (khớp Client) */
    private const FREESHIP_THRESHOLD = 3000000;
    private const SHIP_FEE = 50000;

    /* ========================= INDEX ========================= */
    public function index(Request $request)
    {
        $orders = Order::with([
            'user:id,name',
            'orderItems.productVariant.product.images'
        ])
            ->when($request->search, function ($q, $s) {
                $q->where('id', 'like', "%{$s}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$s}%"));
            })
            ->latest()
            ->paginate(15);

        $orderData = $orders->map(function ($order) {
            $firstItem = $order->orderItems->first();
            $imgPath = $firstItem?->image_product
                ?: $firstItem?->productVariant?->product?->images->first()?->image_path
                ?: $firstItem?->productVariant?->image
                ?: null;

            return [
                'id' => $order->id,
                'user_name' => $order->customer_name, // accessor trong model
                'image' => $imgPath ? asset('storage/' . ltrim($imgPath, '/')) : null,
            ];
        });

        return view('admin.orders.index', [
            'orders' => $orderData,
            'pagination' => $orders,
        ]);
    }

    /* ========================= SHOW ========================= */
    public function show(int $id)
    {
        $order = Order::with([
            'user:id,name,email,phone_number',
            'user.addresses',
            'address',
            'orderItems.productVariant.product.images',
            'orderItems.productVariant.attributeValues.attribute',
            'shippingMethod:id,name',
            'coupon'
        ])->findOrFail($id);

        // ĐỊA CHỈ (ưu tiên text đã lưu trong order)
        if (!empty($order->recipient_address)) {
            $address = $order->recipient_address;
            $city = $district = $ward = '';
        } elseif ($order->address) {
            $city = $order->address->city ?? '';
            $district = $order->address->district ?? '';
            $ward = $order->address->ward ?? '';
            $address = $order->address->address_line ?? '';
        } else {
            $city = $district = $ward = $address = '';
        }

        // SUBTOTAL: ưu tiên total_price đã lưu
        $subtotal = $order->orderItems->sum(function ($item) {
            if (!is_null($item->total_price)) {
                return (float) $item->total_price;
            }
            $price = $item->price
                ?? ($item->productVariant->sale_price ?? $item->productVariant->price ?? 0);
            return (float) $price * (int) $item->quantity;
        });

        // ĐỌC GIÁ TRỊ ĐÃ LƯU (không tự tính lại để tránh lệch Client)
        $shippingFee = (int) ($order->shipping_fee ?? 0);
        $discountAmount = (int) ($order->discount_amount ?? $order->coupon_discount ?? 0);
        $totalAmount = (int) ($order->total_amount ?? ($subtotal + $shippingFee));
        $finalTotal = (int) ($order->final_total ?? ($subtotal + $shippingFee - $discountAmount));
        $couponCode = $order->coupon_code ?? ($order->coupon->code ?? null);

        // Map trạng thái
        $statusMap = [
            'pending' => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao hàng',
            'delivered' => 'Đã giao',
            'received' => 'Đã nhận hàng',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã trả hàng',
        ];
        $paymentMap = [
            'credit_card' => 'Thẻ tín dụng/ghi nợ',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cod' => 'Thanh toán khi nhận hàng',
        ];

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
            'coupon_code' => $couponCode,
            'coupon_discount' => $discountAmount,
            'final_total' => $finalTotal,
            'status' => $order->status,
            'status_vietnamese' => $statusMap[$order->status] ?? $order->status,
            'payment_method' => $order->payment_method,
            'payment_method_vietnamese' => $paymentMap[$order->payment_method] ?? $order->payment_method,
            'payment_status' => $order->payment_status,
            'payment_status_vietnamese' => Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status,
            'shipped_at' => $order->shipped_at ? Carbon::parse($order->shipped_at)->format('d/m/Y H:i') : '',
            'created_at' => Carbon::parse($order->created_at)->format('d/m/Y H:i'),
            'order_items' => $order->orderItems->map(function ($item) {
                $variant = $item->productVariant;
                $prod = $variant->product;
                $price = $item->price ?? ($variant->sale_price ?? $variant->price ?? 0);
                $imgPath = $item->image_product
                    ?: $variant->image
                    ?: null;

                return [
                    'image_product_url' => $imgPath ? asset('storage/' . ltrim($imgPath, '/')) : null,
                    'brand_name' => $prod->brand?->name ?? '',
                    'category_name' => $prod->category?->name ?? '',
                    'stock' => $variant->stock ?? 0,
                    'attributes' => $variant->attributeValues->map(fn($a) => [
                        'name' => $a->attribute->name,
                        'value' => $a->value,
                    ])->toArray(),
                    'name_product' => $prod->name,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total' => $item->total_price ?? ($price * $item->quantity),
                ];
            })->toArray(),
        ];

        return view('admin.orders.show', compact('orderData'));
    }

    /* ========================= EDIT ========================= */
    public function edit(int $id)
    {
        $order = Order::with([
            'user',
            'user.addresses',
            'address',
            'orderItems.productVariant.product.images',
            'orderItems.productVariant.attributeValues.attribute',
            'shippingMethod',
            'coupon',
        ])->findOrFail($id);

        $shippingMethods = ShippingMethod::all();
        $coupons = Coupon::where('status', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        $statusMap = [
            'pending' => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao',
            'delivered' => 'Đã giao',
            'received' => 'Đã nhận hàng',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã trả hàng',
        ];
        $paymentMap = [
            'credit_card' => 'Thẻ tín dụng/ghi nợ',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cod' => 'Thanh toán khi nhận hàng',
        ];

        // subtotal theo dữ liệu hiện tại
        $subtotal = $order->orderItems->sum(function ($item) {
            return (float) ($item->total_price ?? (($item->price ?? ($item->productVariant->sale_price ?? $item->productVariant->price ?? 0)) * $item->quantity));
        });

        // Đọc từ DB (không tự tính lại khi edit form load)
        $shippingFee = (int) ($order->shipping_fee ?? 0);
        $discountAmount = (int) ($order->discount_amount ?? $order->coupon_discount ?? 0);
        $finalTotal = (int) ($order->final_total ?? ($subtotal + $shippingFee - $discountAmount));

        $orderData = [
            'id' => $order->id,
            'user_name' => $order->user->name ?? 'Khách vãng lai',
            'user_email' => $order->user->email ?? '',
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
            'coupon_code' => $order->coupon_code,
            'coupon_discount' => $discountAmount,
            'final_total' => $finalTotal,
            'shipped_at' => $order->shipped_at,
            'order_items' => $order->orderItems->map(fn($item) => [
                'id' => $item->id,
                'name_product' => $item->productVariant->product->name,
                'quantity' => $item->quantity,
                'price' => $item->price ?? ($item->productVariant->sale_price ?? $item->productVariant->price),
                'total' => $item->total_price ?? ($item->quantity * ($item->price ?? ($item->productVariant->sale_price ?? $item->productVariant->price))),
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

    /* ========================= UPDATE ========================= */
    public function updateOrders(Request $request, int $id)
    {
        $order = Order::with('orderItems.productVariant')->findOrFail($id);
        $oldStatus = $order->status;

        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned', 'received'];
        $validPayments = ['credit_card', 'bank_transfer', 'cod'];
        $validPaymentStatus = array_keys(Order::PAYMENT_STATUSES);

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
            'coupon_code', // cho phép set code tay
        ]);

        $validator = Validator::make($data, [
            'status' => 'nullable|in:' . implode(',', $validStatuses),
            'payment_status' => 'nullable|in:' . implode(',', $validPaymentStatus),
            'payment_method' => 'nullable|in:' . implode(',', $validPayments),
            'shipped_at' => 'nullable|date',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_phone' => 'nullable|string|max:20',
            'recipient_address' => 'nullable|string|max:500',
            'shipping_method_id' => 'nullable|exists:shipping_methods,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'order_items' => 'nullable|array',
            'order_items.*.id' => 'required|integer|exists:order_items,id',
            'order_items.*.quantity' => 'nullable|integer|min:1',
            'order_items.*.price' => 'nullable|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cập nhật từng item nếu có
        if (!empty($data['order_items'])) {
            foreach ($data['order_items'] as $itm) {
                /** @var OrderItem|null $oi */
                $oi = $order->orderItems()->find($itm['id']);
                if ($oi) {
                    $newQty = $itm['quantity'] ?? $oi->quantity;
                    $newPrice = $itm['price'] ?? ($oi->price ?? ($oi->productVariant->sale_price ?? $oi->productVariant->price ?? 0));
                    $oi->quantity = (int) $newQty;
                    $oi->price = (float) $newPrice;
                    $oi->total_price = (float) $newPrice * (int) $newQty;
                    $oi->save();
                }
            }
        }

        // TÍNH LẠI THEO ĐƠN HIỆN TẠI (sau khi item được cập nhật)
        $subtotal = $order->orderItems()->sum(DB::raw('COALESCE(total_price, price*quantity)'));

        $methodId = $data['shipping_method_id'] ?? $order->shipping_method_id;
        $order->shipping_method_id = $methodId;

        // Coupon: ưu tiên id, sau đó code
        if (isset($data['coupon_id'])) {
            $order->coupon_id = $data['coupon_id'];
            $order->coupon_code = Coupon::find($data['coupon_id'])?->code;
        } elseif (!empty($data['coupon_code'])) {
            $order->coupon_code = $data['coupon_code'];
            $order->coupon_id = Coupon::where('code', $data['coupon_code'])->value('id');
        }

        // TÍNH DISCOUNT GIỐNG CLIENT
        $discountAmount = 0;
        $coupon = null;
        if ($order->coupon_id) {
            $coupon = Coupon::find($order->coupon_id);
        } elseif (!empty($order->coupon_code)) {
            $coupon = Coupon::where('code', $order->coupon_code)->first();
        }

        if ($coupon && $coupon->status && now()->between($coupon->start_date, $coupon->end_date)) {
            if ($subtotal >= (int) ($coupon->min_order_value ?? 0)) {
                if (in_array($coupon->discount_type, ['percent', 'percentage'])) {
                    $discountAmount = ($subtotal * $coupon->value) / 100;
                    if (!empty($coupon->max_discount_amount) && $discountAmount > (int) $coupon->max_discount_amount) {
                        $discountAmount = (int) $coupon->max_discount_amount;
                    }
                } else {
                    $discountAmount = (int) $coupon->value;
                }
            }
        }

        // PHÍ SHIP GIỐNG CLIENT (Delivery mới tính, Pickup = 0)
        $afterDiscount = max(0, $subtotal - $discountAmount);
        if ((int) $methodId === self::DELIVERY_ID) {
            $shippingFee = ($afterDiscount >= self::FREESHIP_THRESHOLD) ? 0 : self::SHIP_FEE;
        } else {
            $shippingFee = 0;
        }

        // GHI LÊN ORDER
        $order->recipient_name = $data['recipient_name'] ?? $order->recipient_name;
        $order->recipient_phone = $data['recipient_phone'] ?? $order->recipient_phone;
        $order->recipient_address = $data['recipient_address'] ?? $order->recipient_address;

        if ($oldStatus === 'pending' && !empty($data['payment_method'])) {
            $order->payment_method = $data['payment_method'];
        }
        if (!empty($data['payment_status'])) {
            $order->payment_status = $data['payment_status'];
        }

        // Quy trình đặc biệt: received
        if (($data['status'] ?? '') === 'received') {
            $order->status = 'received';
            $order->shipped_at = now();
        } elseif (!empty($data['status'])) {
            $order->status = $data['status'];
            if (!empty($data['shipped_at'])) {
                $order->shipped_at = $data['shipped_at'];
            }
        }

        // TỔNG TIỀN
        $order->shipping_fee = (int) $shippingFee;
        $order->discount_amount = (int) $discountAmount;
        $order->total_amount = (int) $subtotal + (int) $shippingFee;
        $order->final_total = (int) $subtotal + (int) $shippingFee - (int) $discountAmount;

        $order->save();

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Đơn hàng đã được cập nhật.');
    }

    /* ========================= DESTROY / TRASHED / RESTORE / FORCE DELETE ========================= */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $allowed = ['cancelled', 'returned', 'received'];
        if (!in_array($order->status, $allowed)) {
            return back()->with('error', 'Chỉ có thể xóa khi trạng thái đã hủy, đã trả hoặc đã nhận.');
        }
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được chuyển vào thùng rác.');
    }

    public function trashed()
    {
        $trashed = Order::onlyTrashed()
            ->with(['user', 'orderItems.product', 'orderItems.productVariant.images'])
            ->latest()
            ->get();

        $orderData = $trashed->map(function ($order) {
            $subtotal = $order->orderItems->sum(fn($i) => (float) ($i->total_price ?? (($i->productVariant->sale_price ?? $i->productVariant->price ?? 0) * $i->quantity)));
            return [
                'id' => $order->id,
                'image' => optional(
                    $order->orderItems
                        ->flatMap(fn($i) => $i->productVariant->images->where('is_primary', true))
                        ->first()
                )->img_url,
                'user_name' => $order->user->name ?? 'Khách vãng lai',
                'product_names' => $order->orderItems->pluck('product.name')->implode(', '),
                'total_quantity' => $order->orderItems->sum('quantity'),
                'subtotal' => $subtotal,
                'shipping_fee' => (int) ($order->shipping_fee ?? 0),
                'coupon_discount' => (int) ($order->discount_amount ?? $order->coupon_discount ?? 0),
                'final_total' => (int) ($order->final_total ?? ($subtotal + (int) ($order->shipping_fee ?? 0) - (int) ($order->discount_amount ?? 0))),
                'status' => $order->status,
                'status_vietnamese' => [
                    'pending' => 'Đang chờ xử lý',
                    'processing' => 'Đang xử lý',
                    'shipped' => 'Đã giao',
                    'delivered' => 'Đã nhận',
                    'cancelled' => 'Đã hủy',
                    'returned' => 'Đã trả hàng'
                ][$order->status] ?? $order->status,
                'created_at' => Carbon::parse($order->created_at)->format('d/m/Y H:i'),
                'payment_method' => $order->payment_method,
                'payment_method_vietnamese' => [
                    'credit_card' => 'Thẻ tín dụng',
                    'bank_transfer' => 'Chuyển khoản',
                    'cod' => 'COD'
                ][$order->payment_method] ?? $order->payment_method,
                'recipient_name' => $order->recipient_name,
                'recipient_phone' => $order->recipient_phone,
                'recipient_address' => $order->recipient_address,
                'shipped_at' => optional($order->shipped_at)->format('d/m/Y H:i'),
                'deleted_at' => optional($order->deleted_at)->format('d/m/Y H:i'),
            ];
        });

        return view('admin.orders.trashed', ['orders' => $orderData]);
    }

    public function restore($id)
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('admin.orders.trashed')->with('success', 'Đơn hàng đã được phục hồi.');
    }

    public function forceDelete($id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::withTrashed()->findOrFail($id);
            $order->orderItems()->forceDelete();
            $order->forceDelete();
        });
        return redirect()->route('admin.orders.trashed')->with('success', 'Đơn hàng đã bị xóa vĩnh viễn.');
    }

    /* ========================= RETURNS ========================= */
    public function returnsIndex(Request $request)
    {
        $returns = OrderReturn::with(['order.user', 'order.orderItems.product', 'order.orderItems.productVariant'])
            ->latest()
            ->paginate(15);

        $data = $returns->getCollection()->map(function ($ret) {
            $order = $ret->order;
            if (!$order)
                return null;

            $subtotal = $order->orderItems->sum(fn($i) => (float) ($i->total_price ?? (($i->productVariant->sale_price ?? $i->productVariant->price ?? 0) * $i->quantity)));
            $shipping = (int) ($order->shipping_fee ?? 0);
            $discount = (int) ($order->discount_amount ?? $order->coupon_discount ?? 0);

            return [
                'id' => $ret->id,
                'order_id' => $order->id,
                'user_name' => $order->user->name ?? 'Khách vãng lai',
                'reason' => $ret->reason ?: ($ret->type === 'cancel' ? 'Khách hủy' : 'Khách trả/đổi'),
                'type' => $ret->type,
                'status' => $ret->status,
                'status_vietnamese' => [
                    'pending' => 'Đang chờ',
                    'approved' => 'Đã phê duyệt',
                    'rejected' => 'Đã từ chối',
                    'processing' => 'Đang xử lý',
                    'shipped' => 'Đã giao',
                    'delivered' => 'Đã nhận',
                    'cancelled' => 'Đã hủy',
                    'returned' => 'Đã trả hàng'
                ][$ret->status] ?? $ret->status,
                'requested_at' => Carbon::parse($ret->requested_at)->format('d/m/Y H:i'),
                'processed_at' => optional($ret->processed_at)->format('d/m/Y H:i'),
                'admin_note' => $ret->admin_note,
                'order_total' => $subtotal + $shipping - $discount,
                'order_status_vietnamese' => [
                    'pending' => 'Đang chờ xử lý',
                    'processing' => 'Đang xử lý',
                    'shipped' => 'Đã giao',
                    'delivered' => 'Đã nhận',
                    'cancelled' => 'Đã hủy',
                    'returned' => 'Đã trả hàng'
                ][$order->status] ?? $order->status,
                'payment_method_vietnamese' => [
                    'credit_card' => 'Thẻ tín dụng',
                    'bank_transfer' => 'Chuyển khoản',
                    'cod' => 'COD'
                ][$order->payment_method] ?? $order->payment_method,
            ];
        })->filter()->values();

        return view('admin.orders.returns', [
            'returns' => $data,
            'pagination' => $returns,
        ]);
    }

    public function processReturn(Request $request, $id)
    {
        $ret = OrderReturn::findOrFail($id);
        $action = $request->input('action'); // approve|reject
        $note = $request->input('admin_note');

        if (!in_array($action, ['approve', 'reject'])) {
            return back()->with('error', 'Hành động không hợp lệ.');
        }

        $ret->status = $action === 'approve' ? 'approved' : 'rejected';
        $ret->processed_at = now();
        $ret->admin_note = $note;

        if ($action === 'approve') {
            $ord = $ret->order;
            if ($ret->type === 'cancel' && $ord->status === 'pending') {
                $ord->status = 'cancelled';
            } elseif ($ret->type === 'return' && $ord->status === 'delivered') {
                $ord->status = 'returned';
            } else {
                return back()->with('error', 'Không thể xử lý yêu cầu dựa trên trạng thái hiện tại.');
            }
            $ord->save();
        }

        $ret->save();
        $msg = $action === 'approve' ? 'đã được phê duyệt.' : 'đã bị từ chối.';
        return redirect()->route('admin.orders.returns')->with('success', "Yêu cầu $msg");
    }
}

