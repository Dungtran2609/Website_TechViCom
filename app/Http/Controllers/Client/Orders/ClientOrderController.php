<?php

namespace App\Http\Controllers\Client;

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
use Illuminate\Support\Facades\Validator;

class ClientOrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng của user
     */
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with('orderItems.productVariant.product')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    /**
     * Xem chi tiết một đơn hàng
     */
    public function show($id)
    {
        $user = Auth::user();
        $order = Order::with([
            'orderItems.productVariant.product.images',
            'shippingMethod',
            'coupon'
        ])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('client.orders.show', [
            'order' => $order,
            'paymentStatusMap' => Order::PAYMENT_STATUSES,
        ]);
    }

    /**
     * Hiển thị form đặt hàng mới
     */
    public function create()
    {
        $variants = ProductVariant::with('product')->get();
        $shippingMethods = ShippingMethod::all();
        $coupons = Coupon::where('status', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('client.orders.create', compact(
            'variants',
            'shippingMethods',
            'coupons'
        ));
    }

    /**
     * Xử lý lưu đơn hàng mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'recipient_address' => 'required|string|max:500',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'payment_method' => 'required|in:credit_card,bank_transfer,cod',
            'coupon_id' => 'nullable|exists:coupons,id',
            'order_items' => 'required|array|min:1',
            'order_items.*.variant_id' => 'required|exists:product_variants,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'order_items.*.price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Tính subtotal
            $subtotal = collect($request->order_items)->sum(function ($itm) {
                $variant = ProductVariant::findOrFail($itm['variant_id']);
                $price = $itm['price']
                    ?? $variant->sale_price
                    ?? $variant->price
                    ?? 0;
                return $price * $itm['quantity'];
            });

            // Tính phí ship
            $shipId = $request->shipping_method_id;
            $shippingFee = $shipId == 1
                ? 0
                : (($subtotal >= 3000000) ? 0 : 60000);

            // Tính giảm giá coupon
            $couponDiscount = 0;
            if ($request->filled('coupon_id')) {
                $coupon = Coupon::findOrFail($request->coupon_id);
                $couponDiscount = $this->calculateCouponDiscount(
                    $coupon,
                    $subtotal + $shippingFee
                );
            }

            // Tạo order chính
            $order = Order::create([
                'user_id' => $user->id,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'recipient_address' => $request->recipient_address,
                'shipping_method_id' => $shipId,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'coupon_id' => $request->coupon_id,
                'shipping_fee' => $shippingFee,
                'total_amount' => $subtotal,
                'coupon_discount' => $couponDiscount,
                'final_total' => $subtotal + $shippingFee - $couponDiscount,
                'status' => 'pending',
                'created_at' => Carbon::now(),
            ]);

            // Tạo order items và trừ stock
            foreach ($request->order_items as $itm) {
                $variant = ProductVariant::findOrFail($itm['variant_id']);
                $price = $itm['price']
                    ?? $variant->sale_price
                    ?? $variant->price
                    ?? 0;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $itm['variant_id'],
                    'quantity' => $itm['quantity'],
                    'price' => $price,
                ]);

                $variant->decrement('stock', $itm['quantity']);
            }

            DB::commit();

            return redirect()
                ->route('client.orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());

            return back()
                ->with('error', 'Có lỗi xảy ra, vui lòng thử lại.')
                ->withInput();
        }
    }

    /**
     * Hủy đơn (chỉ khi đang pending)
     */
    public function cancel($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Đơn hàng đã được hủy.');
    }

    /**
     * Xác nhận thanh toán (chỉ khi pending)
     */
    public function confirmPayment($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->findOrFail($id);

        $order->payment_status = 'paid';
        $order->save();

        return back()->with('success', 'Đã xác nhận thanh toán!');
    }

    /**
     * Yêu cầu trả hàng (chỉ khi đã delivered)
     */
    public function requestReturn($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->findOrFail($id);

        OrderReturn::create([
            'order_id' => $order->id,
            'type' => 'return',
            'reason' => 'Khách hàng yêu cầu trả',
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return back()->with('success', 'Yêu cầu trả hàng đã được gửi.');
    }

    /**
     * Helper tính giảm giá coupon
     */
    private function calculateCouponDiscount($coupon, $orderTotal)
    {
        if (
            !$coupon
            || !$coupon->status
            || now()->lt($coupon->start_date)
            || now()->gt($coupon->end_date)
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
            ($coupon->min_order_value && $orderTotal < $coupon->min_order_value)
            || ($coupon->max_order_value && $orderTotal > $coupon->max_order_value)
        ) {
            return 0;
        }

        return $discount;
    }
}
