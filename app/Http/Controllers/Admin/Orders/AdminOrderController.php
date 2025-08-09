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
    public function index(Request $request)
    {
        $orders = Order::with([
            'user:id,name',
            'orderItems.productVariant.product.images'
        ])
            ->when(
                $request->search,
                fn($q, $s) =>
                $q->where('id', 'like', "%{$s}%")
                    ->orWhereHas(
                        'user',
                        fn($q2) =>
                        $q2->where('name', 'like', "%{$s}%")
                    )
            )
            ->latest()
            ->paginate(15);

        $orderData = $orders->map(fn($order) => [
            'id' => $order->id,
            'user_name' => $order->customer_name, // Sử dụng accessor mới

            // lấy orderItem đầu tiên
            'image' => optional($order->orderItems->first(), function ($item) {
                $variant = $item->productVariant;
                $prod = $variant->product;

                // 1. Ảnh lưu tạm trên order_item
                if (!empty($item->image_product)) {
                    $path = $item->image_product;
                }
                // 2. Ảnh đầu tiên của product
                elseif ($prod->images->isNotEmpty()) {
                    $path = $prod->images->first()->image_path;
                }
                // 3. Ảnh của variant
                elseif (!empty($variant->image)) {
                    $path = $variant->image;
                } else {
                    return null;
                }

                return asset('storage/' . ltrim($path, '/'));
            }),
        ]);

        return view('admin.orders.index', [
            'orders' => $orderData,
            'pagination' => $orders,
        ]);
    }

    /**
     * Cập nhật trạng thái đơn hàng (AJAX hoặc form riêng)
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'];
        $status = $request->input('status');
        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Trạng thái không hợp lệ.');
        }
        $order->status = $status;
        // Nếu chuyển sang shipped thì cập nhật shipped_at
        if ($status === 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        }
        $order->save();
        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Cập nhật trạng thái thành công!');
    }

    // show  
    public function show(int $id)
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
        ])->findOrFail($id);

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






    //Update 
// app/Http/Controllers/Admin/Orders/OrderController.php

    public function edit(int $id)
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
        ])->findOrFail($id);

        // Các data phụ trợ
        $shippingMethods = ShippingMethod::all();
        $coupons = Coupon::where('status', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        // Map trạng thái & PT thanh toán
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
            'user_name' => $order->user->name,
            'user_email' => $order->user->email,
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
    public function updateOrders(Request $request, int $id)
    {
        $order = Order::findOrFail($id);
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

        // Validate
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
            'order_items.*.quantity' => 'nullable|integer|min:1',
            'order_items.*.price' => 'nullable|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Xác định những trường được phép sửa
        $editable = [
            'recipient_name',
            'recipient_phone',
            'recipient_address',
            'status',
            'order_items',
            'coupon_id',
            'to_district_id',
            'to_ward_code',
            'payment_status',    // ← cho phép fill chung
        ];
        // if ($oldStatus === 'pending') {
            // $editable[] = 'payment_method';
            // $editable[] = 'shipping_method_id';
        // }
        // Chỉ cho đổi payment_method khi pending
        if ($oldStatus === 'pending') {
            $editable[] = 'payment_method';
            $editable[] = 'shipping_method_id';
        } else {
            // Nếu không phải pending, bỏ luôn payment_status và payment_method
            unset($data['payment_status'], $data['payment_method']);
        }
        // Tính tổng tiền sản phẩm (theo input mới nếu có)
        $totalAmount = collect($order->orderItems)->sum(function ($item) use ($data) {
            $itm = collect($data['order_items'] ?? [])->firstWhere('id', $item->id);
            $qty = $itm['quantity'] ?? $item->quantity;
            $price = $itm['price'] ?? ($item->productVariant->sale_price ?? $item->productVariant->price ?? 0);
            return $qty * $price;
        });

        // Tính phí ship: ID=1 là store_pickup miễn phí
        $methodId = $data['shipping_method_id'] ?? $order->shipping_method_id;
        $shippingFee = $methodId === 1
            ? 0
            : ($totalAmount >= 3000000 ? 0 : 60000);

        // Lưu coupon_id nhưng không lưu coupon_discount
        if (isset($data['coupon_id'])) {
            $order->coupon_id = $data['coupon_id'];
        }

        // Tính giảm giá động (khi show sẽ tính lại với helper)
        // Nhưng để lưu final_total chính xác, chúng ta cũng tính discount ở đây
        $discount = 0;
        if ($order->coupon_id) {
            $coupon = Coupon::find($order->coupon_id);
            $discount = $this->calculateCouponDiscount($coupon, $totalAmount + $shippingFee);
        }

        // Cập nhật đơn hàng
        $order->fill(array_intersect_key($data, array_flip($editable)));
        // $order->shipping_fee = $shippingFee;
        // $order->total_amount = $totalAmount;
        // $order->final_total = $totalAmount + $shippingFee - $discount;
        // if (!empty($data['shipped_at'])) {
        // $order->shipped_at = Carbon::parse($data['shipped_at']);
        // }
        // $order->save();
        $order->shipping_fee = $shippingFee;
        $order->total_amount = $totalAmount;
        $order->final_total = $totalAmount + $shippingFee - $discount;
        $order->save();
        // Cập nhật order items nếu có
        if (!empty($data['order_items'])) {
            foreach ($data['order_items'] as $itm) {
                $oi = $order->orderItems()->find($itm['id']);
                if ($oi) {
                    $oi->update([
                        'quantity' => $itm['quantity'] ?? $oi->quantity,
                        'price' => $itm['price'] ?? $oi->price,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Đơn hàng đã được cập nhật.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $allowed = ['cancelled', 'returned', 'delivered'];
        if (!in_array($order->status, $allowed)) {
            return back()->with('error', 'Chỉ có thể xóa khi trạng thái đã hủy, đã trả hoặc đã nhận.');
        }
        $order->delete(); // xóa mềm
        return redirect()->route('admin.orders.index')
            ->with('success', 'Đơn hàng đã được chuyển vào thùng rác.');
    }

    public function trashed()
    {
        $trashed = Order::onlyTrashed()
            ->with(['user', 'orderItems.product', 'orderItems.productVariant.images'])
            ->latest()
            ->get();

        $orderData = $trashed->map(fn($order) => [
            'id' => $order->id,
            'image' => optional(
                $order->orderItems
                    ->flatMap(fn($i) => $i->productVariant->images->where('is_primary', true))
                    ->first()
            )->img_url,
            'user_name' => $order->user->name ?? 'Khách vãng lai',
            'product_names' => $order->orderItems->pluck('product.name')->implode(', '),
            'total_quantity' => $order->orderItems->sum('quantity'),
            'subtotal' => $order->orderItems->sum(fn($i) => ($i->productVariant->sale_price ?? $i->productVariant->price) * $i->quantity),
            'shipping_fee' => $order->shipping_fee,
            'coupon_discount' => $order->coupon_discount,
            'final_total' => $order->final_total,
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
        ]);

        return view('admin.orders.trashed', ['orders' => $orderData]);
    }

    public function restore($id)
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('admin.order.trashed')
            ->with('success', 'Đơn hàng đã được phục hồi.');
    }

    public function forceDelete($id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::withTrashed()->findOrFail($id);
            $order->orderItems()->forceDelete();
            $order->forceDelete();
        });
        return redirect()->route('admin.orders.trashed')
            ->with('success', 'Đơn hàng đã bị xóa vĩnh viễn.');
    }

    public function returnsIndex(Request $request)
    {
        $returns = OrderReturn::with(['order.user', 'order.orderItems.product', 'order.orderItems.productVariant'])
            ->latest()
            ->paginate(15);

        $data = $returns->getCollection()->map(function ($ret) {
            $order = $ret->order;
            if (!$order) {
                return null;
            }
            $base = $order->orderItems->sum(fn($i) => ($i->productVariant->sale_price ?? $i->productVariant->price) * $i->quantity);
            $shippingFee = $order->shippingMethod->fee ?? 0;
            $couponDisc = $order->coupon ? $this->calculateCouponDiscount($order->coupon, $base + $shippingFee) : 0;

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
                'order_total' => $base + $shippingFee - $couponDisc,
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
        })
            ->filter()     // loại bỏ null
            ->values();    // reset index

        return view('admin.orders.returns', [
            'returns' => $data,
            'pagination' => $returns,
        ]);
    }

    public function processReturn(Request $request, $id)
    {
        $ret = OrderReturn::findOrFail($id);
        $action = $request->input('action'); // 'approve' || 'reject'
        $note = $request->input('admin_note');
        if (!in_array($action, ['approve', 'reject'])) {
            return back()->with('error', 'Hành động không hợp lệ.');
        }

        // Cập nhật trạng thái return
        $ret->status = $action === 'approve' ? 'approved' : 'rejected';
        $ret->processed_at = now();
        $ret->admin_note = $note;

        // Nếu phê duyệt thì đồng bộ order.status
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
        return redirect()->route('admin.order.returns')
            ->with('success', "Yêu cầu $msg");
    }

    // Tinhs
    private function calculateCouponDiscount($coupon, $orderTotal)
    {
        if (!$coupon || !$coupon->status || now()->lt($coupon->start_date) || now()->gt($coupon->end_date)) {
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
        if ($coupon->min_order_value && $orderTotal < $coupon->min_order_value) {
            return 0;
        }
        if ($coupon->max_order_value && $orderTotal > $coupon->max_order_value) {
            return 0;
        }
        return $discount;
    }
}

