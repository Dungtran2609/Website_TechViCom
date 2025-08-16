<?php

namespace App\Http\Controllers\Client\Checkouts;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\ShippingMethod;
use App\Models\Coupon;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClientCheckoutController extends Controller
{
    /** Map tên tỉnh cho code (Hà Nội). */
    private function provinceNameByCode(?string $code): string
    {
        return $code === '01' ? 'Thành phố Hà Nội' : ($code ?? '');
    }

    /** Map tên quận/huyện phổ biến của Hà Nội (có thể mở rộng). */
    private function districtNameByCode(?string $code): string
    {
        $map = [
            '001' => 'Quận Ba Đình',
            '002' => 'Quận Hoàn Kiếm',
            '003' => 'Quận Tây Hồ',
            '004' => 'Quận Long Biên',
            '005' => 'Quận Cầu Giấy',
            '006' => 'Quận Đống Đa',
            '007' => 'Quận Hai Bà Trưng',
            '008' => 'Quận Hoàng Mai',
            '009' => 'Quận Thanh Xuân',
            // ... có thể bổ sung thêm
        ];
        return $map[$code] ?? ($code ?? '');
    }

    private function wardNameByCode(?string $code): string
    {
        return $code ?? '';
    }

    public function index()
    {
        if (session('payment_cancelled_message') && request()->has('action')) {
            session()->forget('payment_cancelled_message');
        }

        // Xóa session restored_coupon nếu được yêu cầu
        if (request()->has('clear_restored_coupon')) {
            session()->forget('restored_coupon');
        }

        file_put_contents(storage_path('logs/debug.txt'), "Checkout method called at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents(storage_path('logs/debug.txt'), "User logged in: " . (Auth::check() ? 'Yes' : 'No') . "\n", FILE_APPEND);
        if (Auth::check()) {
            file_put_contents(storage_path('logs/debug.txt'), "User ID: " . Auth::id() . "\n", FILE_APPEND);
        }

        $buildKey = function ($productId, $variantId = null) {
            return sprintf('%s:%s', (int) $productId, $variantId ? (int) $variantId : 0);
        };

        $cartItems = [];
        $subtotal = 0;

        // 1) Ưu tiên 'buynow'
        $buynow = session('buynow');
        Log::info('Checkout index - buynow session', ['buynow' => $buynow]);

        if ($buynow) {
            $product = Product::with(['productAllImages', 'variants'])->find($buynow['product_id']);
            $variant = !empty($buynow['variant_id']) ? \App\Models\ProductVariant::find($buynow['variant_id']) : null;

            if ($product) {
                $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);
                if ($variant && $variant->image) {
                    $image = 'storage/' . $variant->image;
                } elseif ($product->thumbnail) {
                    $image = 'storage/' . $product->thumbnail;
                } elseif ($product->productAllImages && $product->productAllImages->count() > 0) {
                    $image = 'storage/' . $product->productAllImages->first()->image_path;
                } else {
                    $image = 'client_css/images/placeholder.svg';
                }

                $cartItems[] = (object) [
                    'cart_item_id' => $buildKey($product->id, $variant?->id),
                    'product' => $product,
                    'productVariant' => $variant,
                    'quantity' => (int) ($buynow['quantity'] ?? 1),
                    'price' => (float) $price,
                    'product_name' => $product->name,
                    'image' => $image,
                ];
                $subtotal += $price * (int) ($buynow['quantity'] ?? 1);
            }
        }
        // 2) User đăng nhập (khi không có buynow)
        elseif (Auth::check()) {
            $cartQuery = Cart::with([
                'product.productAllImages',
                'product.variants',
                'productVariant.attributeValues.attribute',
                'productVariant'
            ])->where('user_id', Auth::id());

            $selectedParam = request()->get('selected');
            if (!empty($selectedParam)) {
                $isBuyNowFormat = strpos($selectedParam, ':') !== false;
                if ($isBuyNowFormat) {
                    [$productId, $variantId] = explode(':', $selectedParam);
                    session([
                        'buynow' => [
                            'product_id' => $productId,
                            'variant_id' => $variantId != '0' ? $variantId : null,
                            'quantity' => 1
                        ]
                    ]);
                    return redirect()->route('checkout.index');
                } else {
                    $selectedIds = explode(',', $selectedParam);
                    $itemsById = Cart::where('user_id', Auth::id())->whereIn('id', $selectedIds)->count();
                    if ($itemsById > 0)
                        $cartQuery->whereIn('id', $selectedIds);
                    else
                        $cartQuery->whereIn('product_id', $selectedIds);
                }
            }

            $cartItems = $cartQuery->get();

            foreach ($cartItems as $item) {
                if ($item->productVariant) {
                    $price = $item->productVariant->sale_price ?? $item->productVariant->price ?? 0;
                } elseif ($item->product && $item->product->variants && $item->product->variants->count() > 0) {
                    $pvar = $item->product->variants->first();
                    $price = $pvar->sale_price ?? $pvar->price ?? 0;
                } else {
                    $price = $item->product->sale_price ?? $item->product->price ?? 0;
                }

                $item->price = (float) $price;
                $item->cart_item_id = $buildKey($item->product?->id, $item->productVariant?->id);
                $item->product_name = $item->product ? $item->product->name : 'Unknown Product';

                if ($item->productVariant && $item->productVariant->image) {
                    $image = 'storage/' . $item->productVariant->image;
                } elseif ($item->product && $item->product->thumbnail) {
                    $image = 'storage/' . $item->product->thumbnail;
                } elseif ($item->product && $item->product->productAllImages && $item->product->productAllImages->count() > 0) {
                    $image = 'storage/' . $item->product->productAllImages->first()->image_path;
                } else {
                    $image = 'client_css/images/placeholder.svg';
                }
                $item->image = $image;

                $subtotal += (float) $price * (int) $item->quantity;
            }
        }
        // 3) Khách vãng lai (session cart)
        else {
            $cart = session()->get('cart', []);
            if (is_array($cart) && count($cart) > 0) {
                $selectedParam = request()->get('selected');
                if (!empty($selectedParam)) {
                    $isBuyNowFormat = strpos($selectedParam, ':') !== false;
                    if ($isBuyNowFormat) {
                        [$productId, $variantId] = explode(':', $selectedParam);
                        session([
                            'buynow' => [
                                'product_id' => $productId,
                                'variant_id' => $variantId != '0' ? $variantId : null,
                                'quantity' => 1
                            ]
                        ]);
                        return redirect()->route('checkout.index');
                    } else {
                        $selectedKeys = explode(',', $selectedParam);
                        $filtered = [];
                        foreach ($selectedKeys as $key) {
                            if (isset($cart[$key]))
                                $filtered[$key] = $cart[$key];
                        }
                        $cart = $filtered;
                    }
                }

                foreach ($cart as $ci) {
                    $product = Product::with(['productAllImages', 'variants'])->find($ci['product_id']);
                    if (!$product)
                        continue;

                    $variant = !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null;
                    $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                    if ($variant && $variant->image) {
                        $image = 'storage/' . $variant->image;
                    } elseif ($product->thumbnail) {
                        $image = 'storage/' . $product->thumbnail;
                    } elseif ($product->productAllImages && $product->productAllImages->count() > 0) {
                        $image = 'storage/' . $product->productAllImages->first()->image_path;
                    } else {
                        $image = 'client_css/images/placeholder.svg';
                    }

                    $cartItems[] = (object) [
                        'cart_item_id' => $buildKey($product->id, $variant?->id),
                        'product' => $product,
                        'productVariant' => $variant,
                        'quantity' => (int) ($ci['quantity'] ?? 1),
                        'price' => (float) $price,
                        'product_name' => $product->name,
                        'image' => $image,
                    ];
                    $subtotal += (float) $price * (int) ($ci['quantity'] ?? 1);
                }
            }
        }

        if (empty($cartItems)) {
            file_put_contents(storage_path('logs/debug.txt'), "No cart items found, redirecting to cart\n", FILE_APPEND);
            return redirect()->route('carts.index')->with('error', 'Giỏ hàng trống');
        }

        $addresses = [];
        $currentUser = null;
        $defaultAddress = null;

        if (Auth::check()) {
            $currentUser = Auth::user();
            $addresses = UserAddress::where('user_id', Auth::id())->orderBy('is_default', 'desc')->get();
            $defaultAddress = $addresses->first();
        }

        $shippingMethods = ShippingMethod::all();

        // Lấy thông tin coupon đã được khôi phục từ session
        $restoredCoupon = session('restored_coupon');

        return view('client.checkouts.index', compact(
            'cartItems',
            'subtotal',
            'addresses',
            'shippingMethods',
            'currentUser',
            'defaultAddress',
            'restoredCoupon'
        ));
    }

    public function process(Request $request)
    {
        try {
            Log::info('Checkout process started', [
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'user_id' => auth()->id()
            ]);

            // Chỉ cho phép Hà Nội
            $request->validate([
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:20',
                'recipient_email' => 'required|email',
                'recipient_address' => 'required|string|max:255',

                'province_code' => 'required|in:01', // chỉ Hà Nội
                'district_code' => 'required|string',
                'ward_code' => 'required|string',

                'payment_method' => 'required|in:cod,bank_transfer',
                'shipping_method' => 'required|in:home_delivery,store_pickup',
                'order_notes' => 'nullable|string',
            ]);

            // ===== Lấy dữ liệu giỏ: BUYNOW → SESSION → DB =====
            $cartItems = collect();
            $source = null; // 'buynow'|'session'|'db'

            $buynow = session('buynow');
            if ($buynow) {
                $product = Product::with(['productAllImages', 'variants'])->find($buynow['product_id']);
                if (!$product) {
                    return redirect()->route('checkout.index')->with('error', 'Sản phẩm không tồn tại');
                }
                $variant = !empty($buynow['variant_id']) ? \App\Models\ProductVariant::find($buynow['variant_id']) : null;
                $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                $cartItems->push((object) [
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'product' => $product,
                    'productVariant' => $variant,
                    'quantity' => (int) ($buynow['quantity'] ?? 1),
                    'price' => (float) $price,
                    'image' => $variant && $variant->image
                        ? 'storage/' . $variant->image
                        : ($product->thumbnail
                            ? 'storage/' . $product->thumbnail
                            : (($product->productAllImages && $product->productAllImages->count() > 0)
                                ? 'storage/' . $product->productAllImages->first()->image_path
                                : 'client_css/images/placeholder.svg')),
                ]);
                $source = 'buynow';
            } else {
                if (!auth()->check()) {
                    $sessionCart = session()->get('cart', []);
                    if (empty($sessionCart))
                        return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');

                    foreach ($sessionCart as $ci) {
                        $product = Product::with(['productAllImages', 'variants'])->find($ci['product_id']);
                        if (!$product)
                            continue;
                        $variant = !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null;
                        $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                        $cartItems->push((object) [
                            'product_id' => $product->id,
                            'variant_id' => $variant?->id,
                            'product' => $product,
                            'productVariant' => $variant,
                            'quantity' => (int) ($ci['quantity'] ?? 1),
                            'price' => (float) $price,
                            'image' => $variant && $variant->image
                                ? 'storage/' . $variant->image
                                : ($product->thumbnail
                                    ? 'storage/' . $product->thumbnail
                                    : (($product->productAllImages && $product->productAllImages->count() > 0)
                                        ? 'storage/' . $product->productAllImages->first()->image_path
                                        : 'client_css/images/placeholder.svg')),
                        ]);
                    }
                    if ($cartItems->isEmpty())
                        return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');
                    $source = 'session';
                } else {
                    $dbCartItems = Cart::with([
                        'product.productAllImages',
                        'product.variants',
                        'productVariant'
                    ])->where('user_id', auth()->id())->get();

                    if ($dbCartItems->isEmpty())
                        return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');

                    foreach ($dbCartItems as $ci) {
                        if ($ci->productVariant) {
                            $price = $ci->productVariant->sale_price ?? $ci->productVariant->price ?? 0;
                        } elseif ($ci->product && $ci->product->variants && $ci->product->variants->count() > 0) {
                            $pvar = $ci->product->variants->first();
                            $price = $pvar->sale_price ?? $pvar->price ?? 0;
                        } else {
                            $price = $ci->product->sale_price ?? $ci->product->price ?? 0;
                        }

                        if ($ci->productVariant && $ci->productVariant->image) {
                            $image = 'storage/' . $ci->productVariant->image;
                        } elseif ($ci->product && $ci->product->thumbnail) {
                            $image = 'storage/' . $ci->product->thumbnail;
                        } elseif ($ci->product && $ci->product->productAllImages && $ci->product->productAllImages->count() > 0) {
                            $image = 'storage/' . $ci->product->productAllImages->first()->image_path;
                        } else {
                            $image = 'client_css/images/placeholder.svg';
                        }

                        $cartItems->push((object) [
                            'product_id' => $ci->product_id,
                            'variant_id' => $ci->variant_id,
                            'product' => $ci->product,
                            'productVariant' => $ci->productVariant,
                            'quantity' => (int) $ci->quantity,
                            'price' => (float) $price,
                            'image' => $image,
                        ]);
                    }
                    $source = 'db';
                }
            }

            if ($cartItems->isEmpty())
                return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');

            // ===== PHÍ SHIP: < 3tr -> 50k, >= 3tr -> 0 (only for home_delivery) =====
            $subtotal = $cartItems->sum(fn($i) => ((float) $i->price) * ((int) $i->quantity));
            $shippingFee = 0;
            if ($request->shipping_method === 'home_delivery') {
                $shippingFee = ($subtotal >= 3000000) ? 0 : 50000;
            }
            
            // Xử lý discount nếu có
            $discountAmount = 0;
            $couponCode = null;
            if ($request->has('coupon_code') && !empty($request->coupon_code)) {
                $couponCode = $request->coupon_code;
                // Tìm coupon trong database
                $coupon = Coupon::where('code', $couponCode)
                    ->where('status', true)
                    ->whereNull('deleted_at')
                    ->first();
                
                if ($coupon) {
                    // Kiểm tra thời gian hiệu lực
                    $now = Carbon::now();
                    if ((!$coupon->start_date || $now->gte(Carbon::parse($coupon->start_date))) &&
                        (!$coupon->end_date || $now->lte(Carbon::parse($coupon->end_date)))) {
                        
                        // Kiểm tra điều kiện giá trị đơn hàng
                        $orderTotal = $subtotal + $shippingFee;
                        if ((!$coupon->min_order_value || $orderTotal >= $coupon->min_order_value) &&
                            (!$coupon->max_order_value || $orderTotal <= $coupon->max_order_value)) {
                            
                            // Tính discount
                            if ($coupon->discount_type === 'percent') {
                                $discountAmount = $orderTotal * ($coupon->value / 100);
                                if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                                    $discountAmount = $coupon->max_discount_amount;
                                }
                            } else {
                                $discountAmount = $coupon->value;
                            }
                            
                            // Đảm bảo discount không vượt quá tổng tiền
                            $discountAmount = min($discountAmount, $orderTotal);
                        }
                    }
                }
            }
            
            $finalTotal = $subtotal + $shippingFee - $discountAmount;

            // Map CODE -> NAME (HN)
            $provinceCode = $request->input('province_code', '01');
            $districtCode = $request->input('district_code');
            $wardCode = $request->input('ward_code');

            $provinceName = $this->provinceNameByCode($provinceCode);
            $districtName = $this->districtNameByCode($districtCode);
            $wardName = $this->wardNameByCode($wardCode);

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . time() . '-' . (auth()->id() ?? 'guest'),

                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'recipient_email' => $request->recipient_email,
                'recipient_address' => $request->recipient_address,

                'province_code' => $provinceCode,
                'district_code' => $districtCode,
                'ward_code' => $wardCode,
                'city' => $provinceName,
                'district' => $districtName,
                'ward' => $wardName,

                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'order_notes' => $request->order_notes,
                'total_amount' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => $discountAmount,
                'coupon_code' => $couponCode,
                'final_total' => $finalTotal,
                'status' => 'pending',
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'processing',
            ]);

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'total_amount' => $order->total_amount
            ]);

            foreach ($cartItems as $item) {
                $price = (float) $item->price;
                $variantId = $item->variant_id ?? ($item->productVariant->id ?? null);
                $imageProd = $item->image ?? null;
                $totalPrice = $price * (int) $item->quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $variantId,
                    'quantity' => (int) $item->quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'name_product' => $item->product->name ?? 'Unknown Product',
                    'image_product' => $imageProd,
                ]);
            }

            // Dọn dữ liệu
            session()->forget('buynow');

            if ($source === 'session') {
                $cart = session()->get('cart', []);
                foreach ($cartItems as $item) {
                    $key = $item->product_id . ':' . ($item->variant_id ?? 0);
                    unset($cart[$key]);
                }
                session(['cart' => $cart]);
            } elseif ($source === 'db') {
                foreach ($cartItems as $item) {
                    Cart::where('user_id', auth()->id())
                        ->where('product_id', $item->product_id)
                        ->when($item->variant_id, function ($q) use ($item) {
                            $q->where('variant_id', $item->variant_id);
                        }, function ($q) {
                            $q->whereNull('variant_id');
                        })->delete();
                }
            }

            // Thanh toán
            if ($request->payment_method === 'cod') {
                Log::info('COD payment successful, redirecting to success page', [
                    'order_id' => $order->id,
                    'payment_method' => 'cod'
                ]);
                session(['last_order_id' => $order->id]);

                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ sớm nhất.');
            } else {
                Log::info('VNPAY payment, redirecting to VNPAY', [
                    'order_id' => $order->id,
                    'payment_method' => 'bank_transfer'
                ]);

                $vnpayService = new VNPayService();
                $paymentUrl = $vnpayService->createPaymentUrl($order, $request);

                $order->update([
                    'payment_status' => 'processing',
                    'vnpay_url' => $paymentUrl
                ]);

                return redirect($paymentUrl);
            }

        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    public function vnpay_payment($order_id)
    {
        try {
            $order = Order::with(['orderItems.product'])->findOrFail($order_id);

            if (Auth::check() && $order->user_id !== Auth::id()) {
                return redirect()->route('checkout.index')->with('error', 'Bạn không có quyền truy cập đơn hàng này');
            }

            if ($order->payment_status === 'paid') {
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Đơn hàng đã được thanh toán');
            }

            $vnpayService = new VNPayService();
            $paymentUrl = $vnpayService->createPaymentUrl($order, request());

            $order->update([
                'payment_status' => 'processing',
                'vnpay_url' => $paymentUrl
            ]);

            Log::info('VNPAY Payment URL created', [
                'order_id' => $order->id,
                'payment_url' => $paymentUrl,
                'return_url' => route('vnpay.return')
            ]);

            return redirect($paymentUrl);

        } catch (\Exception $e) {
            Log::error('VNPAY Payment Error: ' . $e->getMessage(), [
                'order_id' => $order_id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi tạo URL thanh toán: ' . $e->getMessage());
        }
    }

    public function vnpay_return(Request $request)
    {
        try {
            Log::info('VNPAY Return called', ['request_data' => $request->all()]);

            $vnpayService = new VNPayService();
            $vnpayData = $vnpayService->processReturn($request);

            Log::info('VNPAY Return processed', ['vnpay_data' => $vnpayData]);

            if (!$vnpayData['is_valid']) {
                Log::warning('VNPAY Invalid signature', ['vnpay_data' => $vnpayData]);
                return redirect()->route('checkout.index')
                    ->with('error', 'Chữ ký không hợp lệ');
            }

            $order = Order::find($vnpayData['order_id']);
            if (!$order) {
                Log::error('VNPAY Order not found', ['order_id' => $vnpayData['order_id']]);
                return redirect()->route('checkout.index')
                    ->with('error', 'Không tìm thấy đơn hàng');
            }

            $result = $vnpayService->updateOrderStatus($order, $vnpayData);

            Log::info('VNPAY Order status updated', ['result' => $result]);

            if ($result['success']) {
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', $result['message']);
            } else {
                $this->restoreCartFromOrder($order);
                session(['payment_cancelled_message' => $result['message']]);
                return redirect()->route('checkout.index');
            }

        } catch (\Exception $e) {
            Log::error('VNPAY Return Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        try {
            Log::info('Checkout success called', [
                'order_id' => $orderId,
                'user_id' => auth()->id(),
                'user_authenticated' => auth()->check()
            ]);

            $order = Order::with(['orderItems.product'])
                ->where('id', $orderId)
                ->first();

            if (!$order) {
                Log::warning('Order not found', ['order_id' => $orderId]);
                return redirect()->route('checkout.index')
                    ->with('error', 'Không tìm thấy đơn hàng');
            }

            Log::info('Order found', [
                'order_id' => $order->id,
                'order_user_id' => $order->user_id,
                'current_user_id' => auth()->id()
            ]);

            if (auth()->check() && $order->user_id !== auth()->id()) {
                Log::warning('User not authorized to view order', [
                    'order_user_id' => $order->user_id,
                    'current_user_id' => auth()->id()
                ]);
                return redirect()->route('checkout.index')
                    ->with('error', 'Bạn không có quyền xem đơn hàng này');
            }

            if (!auth()->check()) {
                if (session('last_order_id') != $orderId) {
                    Log::warning('Guest user tried to access order not just created', [
                        'order_id' => $orderId,
                        'session_last_order_id' => session('last_order_id')
                    ]);
                    return redirect()->route('checkout.index')
                        ->with('error', 'Vui lòng đặt hàng để xem đơn này');
                }
            }

            Log::info('User authorized, showing success page');
            return view('client.checkouts.success', compact('order'));

        } catch (\Exception $e) {
            Log::error('Error in checkout success: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi hiển thị đơn hàng');
        }
    }

    /** Khôi phục giỏ hàng từ order khi thanh toán thất bại/hủy */
    private function restoreCartFromOrder(Order $order)
    {
        try {
            if (auth()->check()) {
                Cart::where('user_id', auth()->id())->delete();
                foreach ($order->orderItems as $item) {
                    Cart::create([
                        'user_id' => auth()->id(),
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id ?? null,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ]);
                }
            } else {
                $cart = [];
                foreach ($order->orderItems as $item) {
                    $key = $item->product_id . ':' . ($item->variant_id ?? 0);
                    $cart[$key] = [
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ];
                }
                session(['cart' => $cart]);
            }

            // Khôi phục thông tin coupon nếu có
            if ($order->coupon_code) {
                $coupon = Coupon::where('code', $order->coupon_code)
                    ->where('status', true)
                    ->whereNull('deleted_at')
                    ->first();
                
                if ($coupon) {
                    $couponData = [
                        'code' => $coupon->code,
                        'amount' => $order->discount_amount,
                        'details' => [
                            'discount_type' => $coupon->discount_type,
                            'value' => $coupon->value,
                            'max_discount_amount' => $coupon->max_discount_amount,
                            'min_order_value' => $coupon->min_order_value,
                            'max_order_value' => $coupon->max_order_value
                        ]
                    ];
                    session(['restored_coupon' => $couponData]);
                }
            }

            Log::info('Cart restored from order', [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'items_count' => $order->orderItems->count(),
                'has_coupon' => !empty($order->coupon_code)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to restore cart from order: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'user_id' => auth()->id()
            ]);
        }
    }

    public function applyCoupon(Request $request)
    {
        try {
            $couponCode = $request->input('coupon_code');
            $subtotal = $request->input('subtotal', 0);
            
            // Find coupon in database
            $coupon = Coupon::where('code', $couponCode)
                            ->where('status', true)
                            ->whereNull('deleted_at')
                            ->first();
            
            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không tồn tại hoặc đã bị vô hiệu hóa'
                ]);
            }
            
            // Check if coupon is within valid date range
            $now = Carbon::now();
            if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá chưa có hiệu lực'
                ]);
            }
            
            if ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá đã hết hạn'
                ]);
            }
            
            // Check minimum order value
            if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value) . '₫'
                ]);
            }
            
            // Check maximum order value
            if ($coupon->max_order_value && $subtotal > $coupon->max_order_value) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng vượt quá giá trị tối đa ' . number_format($coupon->max_order_value) . '₫'
                ]);
            }
            
            // Calculate discount amount
            $discountAmount = 0;
            if ($coupon->discount_type === 'percent') {
                $discountAmount = $subtotal * ($coupon->value / 100);
                
                // Apply max discount limit for percentage type
                if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                    $discountAmount = $coupon->max_discount_amount;
                }
            } else {
                // Fixed amount discount
                $discountAmount = $coupon->value;
            }
            
            // Make sure discount doesn't exceed subtotal
            $discountAmount = min($discountAmount, $subtotal);
            
            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá hợp lệ',
                'discount_amount' => $discountAmount,
                'coupon' => [
                    'code' => $coupon->code,
                    'discount_type' => $coupon->discount_type,
                    'value' => $coupon->value,
                    'max_discount_amount' => $coupon->max_discount_amount,
                    'min_order_value' => $coupon->min_order_value,
                    'max_order_value' => $coupon->max_order_value
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý mã giảm giá'
            ]);
        }
    }
}
