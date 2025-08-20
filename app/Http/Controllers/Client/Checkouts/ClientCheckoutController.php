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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ClientCheckoutController extends Controller
{
    public function __construct()
    {
        file_put_contents(
            storage_path('logs/debug.txt'),
            "Controller constructor called at " . date('Y-m-d H:i:s') . "\n",
            FILE_APPEND
        );
    }

    /* ============================== Name mappers ============================== */
    private function provinceNameByCode(?string $code): string
    {
        return $code === '01' ? 'Thành phố Hà Nội' : ($code ?? '');
    }

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
        ];
        return $map[$code] ?? ($code ?? '');
    }

    private function wardNameByCode(?string $code): string
    {
        return $code ?? '';
    }

    /* ============================== Stock flags ============================== */
    private function flagDir(): string
    {
        $dir = storage_path('app/stock_flags');
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        return $dir;
    }
    private function flagPath(Order $order, string $flag): string
    {
        return $this->flagDir() . DIRECTORY_SEPARATOR . "{$order->id}_" . strtoupper($flag) . ".flag";
    }
    private function setFlag(Order $order, string $flag): void
    {
        @file_put_contents($this->flagPath($order, $flag), '1', LOCK_EX);
    }
    private function hasFlag(Order $order, string $flag): bool
    {
        return file_exists($this->flagPath($order, $flag));
    }

    /* ============================== VNPay cancel counter ============================== */
    private function cancelCounterDir(): string
    {
        $dir = storage_path('app/vnp_cancel');
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        return $dir;
    }
    private function cancelCounterPath(Order $order): string
    {
        return $this->cancelCounterDir() . DIRECTORY_SEPARATOR . "{$order->id}.count";
    }

    /** Lấy số lần hủy VNPay cho đơn */
    private function getCancelCount(Order $order): int
    {
        if (Schema::hasColumn('orders', 'vnpay_cancel_count')) {
            return (int)($order->vnpay_cancel_count ?? 0);
        }
        $p = $this->cancelCounterPath($order);
        return file_exists($p) ? (int)file_get_contents($p) : 0;
    }

    /** Tăng số lần hủy VNPay, trả về giá trị mới */
    private function incrementCancelCount(Order $order): int
    {
        if (Schema::hasColumn('orders', 'vnpay_cancel_count')) {
            $order->increment('vnpay_cancel_count');
            $order->refresh();
            $count = (int)$order->vnpay_cancel_count;
        } else {
            $p = $this->cancelCounterPath($order);
            $n = $this->getCancelCount($order) + 1;
            @file_put_contents($p, (string)$n, LOCK_EX);
            $count = $n;
        }
        
        // Debug log
        Log::info('VNPay cancel count incremented', [
            'order_id' => $order->id,
            'new_count' => $count,
            'user_id' => Auth::id()
        ]);
        
        return $count;
    }

    /** Kiểm tra xem có thể reset counter không (sau 2 phút) */
    private function canResetCancelCount(Order $order): bool
    {
        // Reset sau 2 phút kể từ lần hủy cuối (để test)
        $lastCancelTime = $order->updated_at;
        $resetTime = $lastCancelTime->addMinutes(2);
        
        $canReset = now()->isAfter($resetTime);
        
        // Debug log
        Log::info('Checking if can reset cancel count', [
            'order_id' => $order->id,
            'last_cancel_time' => $lastCancelTime,
            'reset_time' => $resetTime,
            'now' => now(),
            'can_reset' => $canReset
        ]);
        
        return $canReset;
    }

    /** Reset counter nếu đã đủ thời gian */
    private function resetCancelCountIfNeeded(Order $order): void
    {
        $currentCount = $this->getCancelCount($order);
        
        // Reset tất cả đơn hàng sau 2 phút (để test)
        if ($this->canResetCancelCount($order)) {
            if (Schema::hasColumn('orders', 'vnpay_cancel_count')) {
                $order->update(['vnpay_cancel_count' => 0]);
            } else {
                $p = $this->cancelCounterPath($order);
                if (file_exists($p)) {
                    @unlink($p);
                }
            }
            
            Log::info('Reset VNPay cancel count', [
                'order_id' => $order->id,
                'old_count' => $currentCount,
                'new_count' => 0
            ]);
        }
    }

    /** Đánh dấu ép COD cho đơn */
    private function forceCODForOrder(Order $order, string $message = null): void
    {
        session([
            'force_cod_for_order_id' => $order->id,
            'payment_cancelled_message' => $message ?: 'Bạn đã hủy VNPay 2 lần. Vui lòng đổi phương thức khác để hoàn thành, không đơn hàng sẽ bị hủy.'
        ]);
    }

    /** Nếu đã có item trong cart, xoá flash error "Giỏ hàng trống" cũ */
    private function forgetCartEmptyErrorIfAny(): void
    {
        if (session()->has('error')) {
            $err = session('error');
            $txt = is_string($err) ? $err : (is_array($err) ? implode(' ', $err) : (string)$err);
            if (stripos($txt, 'giỏ hàng trống') !== false) session()->forget('error');
        }
    }

    /* ============================== PAGE: index ============================== */
    public function index(Request $request)
    {
        file_put_contents(storage_path('logs/debug.txt'), "Checkout method called at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents(storage_path('logs/debug.txt'), "User logged in: " . (Auth::check() ? 'Yes' : 'No') . "\n", FILE_APPEND);
        if (Auth::check()) file_put_contents(storage_path('logs/debug.txt'), "User ID: " . Auth::id() . "\n", FILE_APPEND);

        if ($request->has('clear_restored_coupon')) {
            session()->forget('restored_coupon');
        }

        $buildKey = fn ($productId, $variantId = null) => sprintf('%s:%s', (int)$productId, $variantId ? (int)$variantId : 0);

        $cartItems = [];
        $subtotal  = 0;
        $selectedParam = $request->get('selected');

        /* ---------- ƯU TIÊN ?selected= ---------- */
        if (!empty($selectedParam)) {
            session()->forget('buynow');

            if (Auth::check()) {
                $cartQuery = Cart::with([
                    'product.productAllImages',
                    'product.variants',
                    'productVariant.attributeValues.attribute',
                    'productVariant'
                ])->where('user_id', Auth::id());

                $isBuyNowFormat = strpos($selectedParam, ':') !== false;
                if ($isBuyNowFormat) {
                    [$productId, $variantId] = explode(':', $selectedParam);
                    $cartQuery->where('product_id', $productId);
                    $variantId != '0'
                        ? $cartQuery->where('variant_id', $variantId)
                        : $cartQuery->whereNull('variant_id');
                } else {
                    $selectedIds = explode(',', $selectedParam);
                    $itemsById = Cart::where('user_id', Auth::id())->whereIn('id', $selectedIds)->count();
                    $itemsById > 0
                        ? $cartQuery->whereIn('id', $selectedIds)
                        : $cartQuery->whereIn('product_id', $selectedIds);
                }

                $rows = $cartQuery->get();
                foreach ($rows as $item) {
                    if ($item->productVariant) {
                        $price = $item->productVariant->sale_price ?? $item->productVariant->price ?? 0;
                    } elseif ($item->product && $item->product->variants?->count() > 0) {
                        $pvar = $item->product->variants->first();
                        $price = $pvar->sale_price ?? $pvar->price ?? 0;
                    } else {
                        $price = $item->product->sale_price ?? $item->product->price ?? 0;
                    }

                    if ($item->productVariant?->image) $image = 'storage/' . $item->productVariant->image;
                    elseif ($item->product?->thumbnail) $image = 'storage/' . $item->product->thumbnail;
                    elseif ($item->product?->productAllImages?->count() > 0) $image = 'storage/' . $item->product->productAllImages->first()->image_path;
                    else $image = 'client_css/images/placeholder.svg';

                    $item->price = (float)$price;
                    $item->image = $image;
                    $item->cart_item_id = $buildKey($item->product?->id, $item->productVariant?->id);
                    $item->product_name = $item->product?->name ?? 'Unknown Product';

                    $subtotal += (float)$price * (int)$item->quantity;
                    $cartItems[] = $item;
                }
            } else {
                $cart = session()->get('cart', []);
                $isBuyNowFormat = strpos($selectedParam, ':') !== false;

                if ($isBuyNowFormat) {
                    [$productId, $variantId] = explode(':', $selectedParam);
                    $filtered = [];
                    foreach ($cart as $key => $ci) {
                        if (
                            $ci['product_id'] == $productId &&
                            ($ci['variant_id'] == $variantId || (!$ci['variant_id'] && $variantId == '0'))
                        ) {
                            $filtered[$key] = $ci;
                        }
                    }
                    $cart = $filtered;
                } else {
                    $selectedKeys = explode(',', $selectedParam);
                    $filtered = [];
                    foreach ($selectedKeys as $key) {
                        if (isset($cart[$key])) $filtered[$key] = $cart[$key];
                    }
                    $cart = $filtered;
                }

                foreach ($cart as $ci) {
                    $product = Product::with(['productAllImages','variants'])->find($ci['product_id']);
                    if (!$product) continue;
                    $variant = !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null;
                    $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                    if ($variant?->image) $image = 'storage/' . $variant->image;
                    elseif ($product->thumbnail) $image = 'storage/' . $product->thumbnail;
                    elseif ($product->productAllImages?->count() > 0) $image = 'storage/' . $product->productAllImages->first()->image_path;
                    else $image = 'client_css/images/placeholder.svg';

                    $cartItems[] = (object)[
                        'cart_item_id'   => $buildKey($product->id, $variant?->id),
                        'product'        => $product,
                        'productVariant' => $variant,
                        'quantity'       => (int)($ci['quantity'] ?? 1),
                        'price'          => (float)$price,
                        'product_name'   => $product->name,
                        'image'          => $image,
                    ];
                    $subtotal += (float)$price * (int)($ci['quantity'] ?? 1);
                }
            }
        }
        /* ---------- Buynow ---------- */
        elseif (session('buynow')) {
            $buynow = session('buynow');
            $product = Product::with(['productAllImages','variants'])->find($buynow['product_id']);
            $variant = !empty($buynow['variant_id']) ? \App\Models\ProductVariant::find($buynow['variant_id']) : null;
            if ($product) {
                $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);
                if ($variant?->image) $image = 'storage/' . $variant->image;
                elseif ($product->thumbnail) $image = 'storage/' . $product->thumbnail;
                elseif ($product->productAllImages?->count() > 0) $image = 'storage/' . $product->productAllImages->first()->image_path;
                else $image = 'client_css/images/placeholder.svg';

                $cartItems[] = (object)[
                    'cart_item_id'   => $buildKey($product->id, $variant?->id),
                    'product'        => $product,
                    'productVariant' => $variant,
                    'quantity'       => (int)($buynow['quantity'] ?? 1),
                    'price'          => (float)$price,
                    'product_name'   => $product->name,
                    'image'          => $image,
                ];
                $subtotal += $price * (int)($buynow['quantity'] ?? 1);
            }
        }
        /* ---------- Mặc định ---------- */
        else {
            if (Auth::check()) {
                $dbCartItems = Cart::with([
                    'product.productAllImages', 'product.variants', 'productVariant'
                ])->where('user_id', Auth::id())->get();

                foreach ($dbCartItems as $ci) {
                    if ($ci->productVariant) $price = $ci->productVariant->sale_price ?? $ci->productVariant->price ?? 0;
                    elseif ($ci->product && $ci->product->variants?->count() > 0) {
                        $pvar = $ci->product->variants->first();
                        $price = $pvar->sale_price ?? $pvar->price ?? 0;
                    } else $price = $ci->product->sale_price ?? $ci->product->price ?? 0;

                    if ($ci->productVariant?->image) $image = 'storage/' . $ci->productVariant->image;
                    elseif ($ci->product?->thumbnail) $image = 'storage/' . $ci->product->thumbnail;
                    elseif ($ci->product?->productAllImages?->count() > 0) $image = 'storage/' . $ci->product->productAllImages->first()->image_path;
                    else $image = 'client_css/images/placeholder.svg';

                    $cartItems[] = (object)[
                        'cart_item_id'   => $buildKey($ci->product?->id, $ci->productVariant?->id),
                        'product'        => $ci->product,
                        'productVariant' => $ci->productVariant,
                        'quantity'       => (int)$ci->quantity,
                        'price'          => (float)$price,
                        'product_name'   => $ci->product?->name ?? 'Unknown Product',
                        'image'          => $image,
                    ];
                    $subtotal += (float)$price * (int)$ci->quantity;
                }
            } else {
                $cart = session()->get('cart', []);
                foreach ($cart as $ci) {
                    $product = Product::with(['productAllImages','variants'])->find($ci['product_id']);
                    if (!$product) continue;

                    $variant = !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null;
                    $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                    if ($variant?->image) $image = 'storage/' . $variant->image;
                    elseif ($product->thumbnail) $image = 'storage/' . $product->thumbnail;
                    elseif ($product->productAllImages?->count() > 0) $image = 'storage/' . $product->productAllImages->first()->image_path;
                    else $image = 'client_css/images/placeholder.svg';

                    $cartItems[] = (object)[
                        'cart_item_id'   => $buildKey($product->id, $variant?->id),
                        'product'        => $product,
                        'productVariant' => $variant,
                        'quantity'       => (int)($ci['quantity'] ?? 1),
                        'price'          => (float)$price,
                        'product_name'   => $product->name,
                        'image'          => $image,
                    ];
                    $subtotal += (float)$price * (int)($ci['quantity'] ?? 1);
                }
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('carts.index')->with('error', 'Giỏ hàng trống');
        }

        $this->forgetCartEmptyErrorIfAny();

        // Địa chỉ
        $addresses = [];
        $currentUser = null;
        $defaultAddress = null;
        if (Auth::check()) {
            $currentUser = Auth::user();
            $addresses = UserAddress::where('user_id', Auth::id())->orderBy('is_default','desc')->get();
            $defaultAddress = $addresses->first();
        }

        $shippingMethods = ShippingMethod::all();

        // Thông tin ép COD (để view disable VNPay nếu cần)
        $orderVnpayCancelCount = 0;
        $vnpayLocked = false;
        $forcedId = session('force_cod_for_order_id');
        
        // Kiểm tra đơn hàng cụ thể nếu có
        if ($forcedId) {
            if ($o = Order::find($forcedId)) {
                $this->resetCancelCountIfNeeded($o);
                $orderVnpayCancelCount = $this->getCancelCount($o);
                // Chặn VNPay nếu >=3 lần hủy
                if ($orderVnpayCancelCount >= 3) {
                    $vnpayLocked = true;
                }
            }
        }
        
        // Kiểm tra tất cả đơn hàng VNPay của user để chặn VNPay
        if (Auth::check()) {
            $userOrders = Order::where('user_id', Auth::id())
                ->where('payment_method', 'bank_transfer')
                ->get();
            
            $totalCancelCount = 0;
            foreach ($userOrders as $userOrder) {
                $this->resetCancelCountIfNeeded($userOrder);
                $cancelCount = $this->getCancelCount($userOrder);
                $totalCancelCount += $cancelCount;
                
                Log::info('Checking order for VNPay lock', [
                    'order_id' => $userOrder->id,
                    'cancel_count' => $cancelCount,
                    'total_cancel_count' => $totalCancelCount
                ]);
            }
            
            // Chặn VNPay nếu tổng số lần hủy >=3
            if ($totalCancelCount >= 3) {
                $vnpayLocked = true;
                $orderVnpayCancelCount = $totalCancelCount;
                Log::info('VNPay locked due to total spam', [
                    'user_id' => Auth::id(),
                    'total_cancel_count' => $totalCancelCount
                ]);
            }
        }

        // Preview coupon (tùy chọn – giữ nguyên code cũ)
        $appliedCoupon = null;
        $discountAmount = 0;
        $couponMessage = null;
        if ($request->filled('coupon_code')) {
            $couponCode = $request->input('coupon_code');
            $coupon = Coupon::where('code', $couponCode)
                ->where('status', 1)
                ->where(function ($q) { $q->whereNull('deleted_at'); })
                ->where(function ($q) { $q->whereNull('start_date')->orWhere('start_date','<=',now()); })
                ->where(function ($q) { $q->whereNull('end_date')->orWhere('end_date','>=',now()); })
                ->first();

            if ($coupon) {
                if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                    $couponMessage = 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value) . '₫';
                } elseif ($coupon->max_order_value && $subtotal > $coupon->max_order_value) {
                    $couponMessage = 'Đơn hàng vượt quá giá trị tối đa ' . number_format($coupon->max_order_value) . '₫';
                } else {
                    if ($coupon->discount_type === 'percent') {
                        $discountAmount = $subtotal * ($coupon->value / 100);
                        if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                            $discountAmount = $coupon->max_discount_amount;
                        }
                    } else {
                        $discountAmount = $coupon->value;
                    }
                    $discountAmount = min($discountAmount, $subtotal);
                    $appliedCoupon = $coupon;
                    $couponMessage = 'Áp dụng mã thành công!';
                }
            } else {
                $couponMessage = 'Mã giảm giá không hợp lệ hoặc đã hết hạn';
            }
        }

        return view('client.checkouts.index', compact(
            'cartItems',
            'subtotal',
            'addresses',
            'shippingMethods',
            'currentUser',
            'defaultAddress',
            'appliedCoupon',
            'discountAmount',
            'couponMessage',
            'orderVnpayCancelCount',
            'vnpayLocked'
        ));
    }

    /* ============================== Coupon AJAX ============================== */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'subtotal'    => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn',
            ]);
        }

        $subtotal = (float)$request->subtotal;
        $discountAmount = $this->calculateCouponDiscount($coupon, $subtotal);

        if ($discountAmount <= 0) {
            $msg = 'Đơn hàng chưa đủ điều kiện áp dụng mã giảm giá';
            if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                $msg = 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value) . '₫';
            } elseif ($coupon->max_order_value && $subtotal > $coupon->max_order_value) {
                $msg = 'Đơn hàng vượt quá giá trị tối đa ' . number_format($coupon->max_order_value) . '₫';
            }
            return response()->json(['success' => false, 'message' => $msg]);
        }

        if ($coupon->discount_type === 'percent') {
            $discountAmount = ($subtotal * $coupon->value) / 100;
            if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                $discountAmount = (float)$coupon->max_discount_amount;
            }
        } else {
            $discountAmount = (float)$coupon->value;
        }

        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
            'coupon' => $coupon,
        ]);
    }

    /* ============================== PROCESS CHECKOUT ============================== */
    public function process(Request $request)
    {
        try {
            Log::info('Checkout process started', [
                'payment_method'   => $request->payment_method,
                'shipping_method'  => $request->shipping_method,
                'user_id'          => Auth::id(),
                'selected_address' => $request->selected_address ?? null
            ]);

            /* Chặn VNPay nếu user đã hủy >=3 lần */
            if (($request->payment_method ?? '') === 'bank_transfer') {
                if (Auth::check()) {
                    // Kiểm tra tổng số lần hủy VNPay của user
                    $userOrders = Order::where('user_id', Auth::id())
                        ->where('payment_method', 'bank_transfer')
                        ->get();
                    
                    $totalCancelCount = 0;
                    foreach ($userOrders as $userOrder) {
                        $this->resetCancelCountIfNeeded($userOrder);
                        $cancelCount = $this->getCancelCount($userOrder);
                        $totalCancelCount += $cancelCount;
                    }
                    
                    Log::info('Checking VNPay spam protection in process', [
                        'user_id' => Auth::id(),
                        'total_cancel_count' => $totalCancelCount,
                        'orders_count' => $userOrders->count()
                    ]);
                    
                    if ($totalCancelCount >= 3) {
                        Log::info('VNPay blocked due to total spam in process', [
                            'user_id' => Auth::id(),
                            'total_cancel_count' => $totalCancelCount
                        ]);
                        return redirect()->route('checkout.fail')
                            ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 2 phút.');
                    }
                }
            }

            // ==== Địa chỉ ====
            $addressData = null;
            if ($request->has('selected_address') && $request->selected_address !== 'new') {
                $address = UserAddress::where('user_id', Auth::id())->where('id', $request->selected_address)->first();
                if (!$address) return redirect()->route('checkout.index')->with('error', 'Địa chỉ không hợp lệ');

                $addressData = [
                    'recipient_name'   => $address->recipient_name ?? (Auth::user()->name ?? ''),
                    'recipient_phone'  => $address->phone ?? (Auth::user()->phone_number ?? ''),
                    'recipient_email'  => Auth::user()->email ?? '',
                    'recipient_address'=> $address->address_line . ', ' . $address->ward . ', ' . $address->district . ', ' . $address->city,
                    'province_code'    => $address->province_code ?? '01',
                    'district_code'    => $address->district_code ?? '',
                    'ward_code'        => $address->ward_code ?? '',
                ];
            } else {
                $request->validate([
                    'recipient_name'     => 'required|string|max:255',
                    'recipient_phone'    => 'required|string|max:20',
                    'recipient_email'    => 'required|email',
                    'recipient_address'  => 'required|string|max:255',
                    'province_code'      => 'required|in:01',
                    'district_code'      => 'required|string',
                    'ward_code'          => 'required|string',
                    'payment_method'     => 'required|in:cod,bank_transfer',
                    'shipping_method_id' => 'required|exists:shipping_methods,id',
                ]);
                $addressData = [
                    'recipient_name'   => $request->recipient_name,
                    'recipient_phone'  => $request->recipient_phone,
                    'recipient_email'  => $request->recipient_email,
                    'recipient_address'=> $request->recipient_address,
                    'province_code'    => $request->province_code,
                    'district_code'    => $request->district_code,
                    'ward_code'        => $request->ward_code,
                ];
            }

            // ==== Lấy cart ====
            $cartItems = collect();
            $source = null;
            $selectedIds = $request->input('selected');
            $selectedIdsArr = $selectedIds ? array_filter(explode(',', $selectedIds)) : [];

            $buynow = session('buynow');
            if ($buynow) {
                $product = Product::with(['productAllImages','variants'])->find($buynow['product_id']);
                if (!$product) return redirect()->route('checkout.index')->with('error', 'Sản phẩm không tồn tại');
                $variant = !empty($buynow['variant_id']) ? \App\Models\ProductVariant::find($buynow['variant_id']) : null;
                $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                $cartItems->push((object)[
                    'product_id'    => $product->id,
                    'variant_id'    => $variant?->id,
                    'product'       => $product,
                    'productVariant'=> $variant,
                    'quantity'      => (int)($buynow['quantity'] ?? 1),
                    'price'         => (float)$price,
                    'image'         => $variant?->image ? 'storage/' . $variant->image
                                        : ($product->thumbnail ? 'storage/' . $product->thumbnail
                                            : (($product->productAllImages?->count() > 0)
                                                ? 'storage/' . $product->productAllImages->first()->image_path
                                                : 'client_css/images/placeholder.svg')),
                ]);
                $source = 'buynow';
            } else {
                if (!Auth::check()) {
                    $sessionCart = session()->get('cart', []);
                    if (empty($sessionCart)) return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');

                    if (!empty($selectedIdsArr)) {
                        $filtered = [];
                        foreach ($selectedIdsArr as $key) if (isset($sessionCart[$key])) $filtered[$key] = $sessionCart[$key];
                        if (!empty($filtered)) $sessionCart = $filtered;
                    }

                    foreach ($sessionCart as $ci) {
                        $product = Product::with(['productAllImages','variants'])->find($ci['product_id']);
                        if (!$product) continue;
                        $variant = !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null;
                        $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                        $cartItems->push((object)[
                            'product_id'    => $product->id,
                            'variant_id'    => $variant?->id,
                            'product'       => $product,
                            'productVariant'=> $variant,
                            'quantity'      => (int)($ci['quantity'] ?? 1),
                            'price'         => (float)$price,
                            'image'         => $variant?->image ? 'storage/' . $variant->image
                                                : ($product->thumbnail ? 'storage/' . $product->thumbnail
                                                    : (($product->productAllImages?->count() > 0)
                                                        ? 'storage/' . $product->productAllImages->first()->image_path
                                                        : 'client_css/images/placeholder.svg')),
                        ]);
                    }
                    if ($cartItems->isEmpty()) return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');
                    $source = 'session';
                } else {
                    if (!empty($selectedIdsArr)) {
                        $dbCartItems = Cart::with(['product.productAllImages','product.variants','productVariant'])
                            ->where('user_id', Auth::id())->whereIn('id', $selectedIdsArr)->get();
                        if ($dbCartItems->isEmpty()) { // id cart có thể đổi sau khi restore
                            $dbCartItems = Cart::with(['product.productAllImages','product.variants','productVariant'])
                                ->where('user_id', Auth::id())->get();
                        }
                    } else {
                        $dbCartItems = Cart::with(['product.productAllImages','product.variants','productVariant'])
                            ->where('user_id', Auth::id())->get();
                    }
                    if ($dbCartItems->isEmpty()) return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');

                    foreach ($dbCartItems as $ci) {
                        if ($ci->productVariant) $price = $ci->productVariant->sale_price ?? $ci->productVariant->price ?? 0;
                        elseif ($ci->product && $ci->product->variants?->count() > 0) {
                            $pvar = $ci->product->variants->first();
                            $price = $pvar->sale_price ?? $pvar->price ?? 0;
                        } else $price = $ci->product->sale_price ?? $ci->product->price ?? 0;

                        if ($ci->productVariant?->image) $image = 'storage/' . $ci->productVariant->image;
                        elseif ($ci->product?->thumbnail) $image = 'storage/' . $ci->product->thumbnail;
                        elseif ($ci->product?->productAllImages?->count() > 0) $image = 'storage/' . $ci->product->productAllImages->first()->image_path;
                        else $image = 'client_css/images/placeholder.svg';

                        $cartItems->push((object)[
                            'product_id'    => $ci->product_id,
                            'variant_id'    => $ci->variant_id,
                            'product'       => $ci->product,
                            'productVariant'=> $ci->productVariant,
                            'quantity'      => (int)$ci->quantity,
                            'price'         => (float)$price,
                            'image'         => $image,
                        ]);
                    }
                    $source = 'db';
                }
            }

            if ($cartItems->isEmpty()) return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');

            // ==== phí & coupon ====
            $subtotal = $cartItems->sum(fn($i) => ((float)$i->price) * ((int)$i->quantity));
            $shippingMethodId = $request->shipping_method_id;
            $shippingFee = ($shippingMethodId == 1) ? (($subtotal >= 3000000) ? 0 : 50000) : 0;

            $discountAmount = 0;
            $couponCode = null;
            if (!empty($request->coupon_code)) {
                $couponCode = $request->coupon_code;
                $coupon = Coupon::where('code', $couponCode)->where('status', true)->whereNull('deleted_at')->first();
                if ($coupon && $coupon->max_usage_per_user > 0 && Auth::check()) {
                    $usedCount = Order::where('user_id', Auth::id())
                        ->where('coupon_code', $coupon->code)
                        ->whereNull('deleted_at')
                        ->count();
                    if ($usedCount >= $coupon->max_usage_per_user) {
                        return redirect()->route('checkout.index')->with('error', 'Bạn đã sử dụng hết số lần cho phép cho mã giảm giá này.');
                    }
                }
                if ($coupon) {
                    $now = Carbon::now();
                    if (
                        (!$coupon->start_date || $now->gte(Carbon::parse($coupon->start_date))) &&
                        (!$coupon->end_date || $now->lte(Carbon::parse($coupon->end_date)))
                    ) {
                        $orderTotal = $subtotal + $shippingFee;
                        if (
                            (!$coupon->min_order_value || $orderTotal >= $coupon->min_order_value) &&
                            (!$coupon->max_order_value || $orderTotal <= $coupon->max_order_value)
                        ) {
                            if ($coupon->discount_type === 'percent') {
                                $discountAmount = $orderTotal * ($coupon->value / 100);
                                if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                                    $discountAmount = $coupon->max_discount_amount;
                                }
                            } else {
                                $discountAmount = $coupon->value;
                            }
                            $discountAmount = min($discountAmount, $orderTotal);
                        }
                    }
                }
            }

            $finalTotal = $subtotal + $shippingFee - $discountAmount;

            // Map CODE -> NAME
            $provinceCode = $addressData['province_code'] ?? '01';
            $districtCode = $addressData['district_code'] ?? '';
            $wardCode     = $addressData['ward_code'] ?? '';
            $provinceName = $this->provinceNameByCode($provinceCode);
            $districtName = $this->districtNameByCode($districtCode);
            $wardName     = $this->wardNameByCode($wardCode);

            // ==== Tạo order + TRỪ KHO (transaction) ====
            DB::beginTransaction();

            $order = Order::create([
                'user_id'            => Auth::id(),
                'order_number'       => 'ORD-' . time() . '-' . (Auth::id() ?? 'guest'),

                'recipient_name'     => $addressData['recipient_name'],
                'recipient_phone'    => $addressData['recipient_phone'],
                'recipient_email'    => $addressData['recipient_email'],
                'recipient_address'  => $addressData['recipient_address'],

                'province_code'      => $provinceCode,
                'district_code'      => $districtCode,
                'ward_code'          => $wardCode,
                'city'               => $provinceName,
                'district'           => $districtName,
                'ward'               => $wardName,

                'payment_method'     => $request->payment_method,
                'shipping_method_id' => $shippingMethodId,
                'total_amount'       => $subtotal,
                'shipping_fee'       => $shippingFee,
                'discount_amount'    => $discountAmount,
                'coupon_code'        => $couponCode,
                'final_total'        => $finalTotal,
                'status'             => 'pending',
                'payment_status'     => $request->payment_method === 'cod' ? 'pending' : 'processing',
            ]);

            foreach ($cartItems as $item) {
                $price     = (float)$item->price;
                $variantId = $item->variant_id ?? ($item->productVariant->id ?? null);
                $imageProd = $item->image ?? null;
                $totalPrice= $price * (int)$item->quantity;

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'variant_id'    => $variantId,
                    'quantity'      => (int)$item->quantity,
                    'price'         => $price,
                    'total_price'   => $totalPrice,
                    'name_product'  => $item->product->name ?? 'Unknown Product',
                    'image_product' => $imageProd,
                ]);
            }

            // Trừ kho
            $this->reserveStock($order);

            DB::commit();

            // Dọn giỏ
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
                    Cart::where('user_id', Auth::id())
                        ->where('product_id', $item->product_id)
                        ->when(
                            $item->variant_id,
                            fn($q) => $q->where('variant_id', $item->variant_id),
                            fn($q) => $q->whereNull('variant_id')
                        )->delete();
                }
            }

            // Thanh toán
            if ($request->payment_method === 'cod') {
                            // Nếu đang có session force_cod_for_order_id, COD thành công thì bỏ ép
            if (session()->has('force_cod_for_order_id')) {
                session()->forget('force_cod_for_order_id');
                session()->forget('payment_cancelled_message');
            }
                session(['last_order_id' => $order->id]);
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ sớm nhất.');
            } else {
                // Nếu user đã hủy >=3 lần tổng cộng thì chặn VNPay ngay tại đây
                if (Auth::check()) {
                    $userOrders = Order::where('user_id', Auth::id())
                        ->where('payment_method', 'bank_transfer')
                        ->get();
                    
                    $totalCancelCount = 0;
                    foreach ($userOrders as $userOrder) {
                        $this->resetCancelCountIfNeeded($userOrder);
                        $cancelCount = $this->getCancelCount($userOrder);
                        $totalCancelCount += $cancelCount;
                    }
                    
                    if ($totalCancelCount >= 3) {
                        return redirect()->route('checkout.fail')
                            ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ.');
                    }
                }
                


                $txnRef = sprintf('VNP-%s-%s-%04d', $order->id, now()->format('YmdHis'), random_int(0, 9999));
                $amountExpected = (int) round($order->final_total * 100);

                $order->update([
                    'payment_status'      => 'processing',
                    'vnp_txn_ref'         => $txnRef,
                    'vnp_amount_expected' => $amountExpected,
                ]);

                $vnpayService = new VNPayService();
                $paymentUrl = $vnpayService->createPaymentUrl($order, $request, [
                    'txn_ref' => $txnRef,
                    'amount'  => $amountExpected,
                ]);

                $order->update(['vnpay_url' => $paymentUrl]);
                return redirect($paymentUrl);
            }
        } catch (\RuntimeException $ex) {
            DB::rollBack();
            Log::warning('Out of stock at checkout', ['msg' => $ex->getMessage()]);
            return redirect()->route('checkout.index')->with('error', $ex->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    /* ============================== Re-init VNPay for an order ============================== */
    public function vnpay_payment($order_id)
    {
        try {
            $order = Order::with(['orderItems.product'])->findOrFail($order_id);

            if (Auth::check() && $order->user_id !== Auth::id()) {
                return redirect()->route('checkout.index')->with('error', 'Bạn không có quyền truy cập đơn hàng này');
            }

            if ($order->payment_status === 'paid') {
                return redirect()->route('checkout.success', $order->id)->with('success', 'Đơn hàng đã được thanh toán');
            }

            // CHẶN nếu user đã hủy >= 3 lần tổng cộng
            if (Auth::check()) {
                $userOrders = Order::where('user_id', Auth::id())
                    ->where('payment_method', 'bank_transfer')
                    ->get();
                
                $totalCancelCount = 0;
                foreach ($userOrders as $userOrder) {
                    $this->resetCancelCountIfNeeded($userOrder);
                    $cancelCount = $this->getCancelCount($userOrder);
                    $totalCancelCount += $cancelCount;
                }
                
                if ($totalCancelCount >= 3) {
                    return redirect()->route('checkout.fail')
                        ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 2 phút.');
                }
            }
            
            // CHẶN nếu user đã hủy >= 3 lần
            if (Auth::check()) {
                $userOrders = Order::where('user_id', Auth::id())
                    ->where('payment_method', 'bank_transfer')
                    ->get();
                
                Log::info('Checking VNPay spam protection in vnpay_payment', [
                    'user_id' => Auth::id(),
                    'orders_count' => $userOrders->count()
                ]);
                
                foreach ($userOrders as $userOrder) {
                    $this->resetCancelCountIfNeeded($userOrder);
                    $cancelCount = $this->getCancelCount($userOrder);
                    
                    Log::info('Order cancel count check in vnpay_payment', [
                        'order_id' => $userOrder->id,
                        'cancel_count' => $cancelCount
                    ]);
                    
                    if ($cancelCount >= 3) {
                        Log::info('VNPay blocked due to spam in vnpay_payment', [
                            'order_id' => $userOrder->id,
                            'cancel_count' => $cancelCount,
                            'user_id' => Auth::id()
                        ]);
                        return redirect()->route('checkout.fail')
                            ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ.');
                    }
                }
            }

            $txnRef = sprintf('VNP-%s-%s-%04d', $order->id, now()->format('YmdHis'), random_int(0, 9999));
            $amountExpected = (int) ($order->vnp_amount_expected ?: round($order->final_total * 100));

            $order->forceFill([
                'payment_status'      => 'processing',
                'vnp_txn_ref'         => $txnRef,
                'vnp_amount_expected' => $amountExpected,
            ])->save();

            $vnpayService = new VNPayService();
            $paymentUrl = $vnpayService->createPaymentUrl($order, request(), [
                'txn_ref' => $txnRef,
                'amount'  => $amountExpected,
            ]);

            $order->update(['vnpay_url' => $paymentUrl]);
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

    /* ============================== VNPay return ============================== */
    public function vnpay_return(Request $request)
    {
        try {
            $svc = new VNPayService();
            $vnp = $svc->processReturn($request);

            if (empty($vnp['is_valid'])) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Chữ ký không hợp lệ. Vui lòng chọn lại phương thức thanh toán.');
            }

            $txnRef       = $vnp['vnp_TxnRef'] ?? null;
            $respCode     = $vnp['vnp_ResponseCode'] ?? null;
            $amountActual = (int) ($vnp['vnp_Amount'] ?? 0);

            if (!$txnRef) {
                return redirect()->route('checkout.index')->with('error', 'Thiếu mã giao dịch.');
            }

            $order = Order::with('orderItems')->where('vnp_txn_ref', $txnRef)->first();
            if (!$order) {
                return redirect()->route('checkout.index')->with('error', 'Không tìm thấy đơn hàng.');
            }

            // Người dùng HỦY (24)
            if ($respCode === '24') {
                $count = $this->incrementCancelCount($order);
                $this->releaseStock($order);
                $this->restoreCartFromOrder($order);
                $order->forceFill([
                    'payment_status' => 'cancelled',
                    'status'         => 'cancelled',
                ])->save();

                // Debug log
                Log::info('VNPay cancelled by user', [
                    'order_id' => $order->id,
                    'cancel_count' => $count,
                    'user_id' => Auth::id()
                ]);

                // Tính tổng số lần hủy của user
                if (Auth::check()) {
                    $userOrders = Order::where('user_id', Auth::id())
                        ->where('payment_method', 'bank_transfer')
                        ->get();
                    
                    $totalCancelCount = 0;
                    foreach ($userOrders as $userOrder) {
                        $this->resetCancelCountIfNeeded($userOrder);
                        $cancelCount = $this->getCancelCount($userOrder);
                        $totalCancelCount += $cancelCount;
                    }
                    
                    Log::info('Total VNPay cancel count for user', [
                        'user_id' => Auth::id(),
                        'total_cancel_count' => $totalCancelCount,
                        'current_order_count' => $count
                    ]);
                    
                    // Thông báo sau lần 2
                    if ($totalCancelCount == 2) {
                        return redirect()->route('checkout.index', ['order_id' => $order->id])
                            ->with('error', 'Bạn đã hủy VNPay 2 lần. Vui lòng đổi phương thức khác để hoàn thành, không đơn hàng sẽ bị hủy.');
                    }
                    
                    // Chặn hoàn toàn nếu >=3
                    if ($totalCancelCount >= 3) {
                        return redirect()->route('checkout.fail')
                            ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 2 phút.');
                    }
                }

                session(['payment_cancelled_message' => 'Bạn đã hủy thanh toán. Vui lòng chọn lại phương thức.']);
                return redirect()->route('checkout.index', ['order_id' => $order->id]);
            }

            // Đối chiếu số tiền
            if ((int)$order->vnp_amount_expected !== $amountActual) {
                $this->releaseStock($order);
                $this->restoreCartFromOrder($order);
                session(['payment_cancelled_message' => 'Số tiền không khớp. Vui lòng chọn lại phương thức.']);
                return redirect()->route('checkout.index', ['order_id' => $order->id]);
            }

            // Tránh xử lý lặp
            if (!in_array($order->payment_status, ['pending', 'processing'])) {
                session(['last_order_id' => $order->id]);
                return redirect()->route('checkout.success', $order->id);
            }

            // Cập nhật theo kết quả
            $result = $svc->updateOrderStatus($order, $vnp);

            if (!empty($result['success'])) {
                // Thanh toán thành công -> bỏ session nếu đang có
                if (session()->get('force_cod_for_order_id') == $order->id) {
                    session()->forget('force_cod_for_order_id');
                    session()->forget('payment_cancelled_message');
                }
                session(['last_order_id' => $order->id]);
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', $result['message'] ?? 'Thanh toán thành công');
            }

            // Thất bại khác -> tăng counter và khôi phục giỏ + về checkout
            $count = $this->incrementCancelCount($order);
            $this->releaseStock($order);
            $this->restoreCartFromOrder($order);
            $msg = $result['message'] ?? 'Thanh toán thất bại. Vui lòng chọn lại phương thức.';
            session(['payment_cancelled_message' => $msg]);
            
            // Tính tổng số lần thất bại của user
            if (Auth::check()) {
                $userOrders = Order::where('user_id', Auth::id())
                    ->where('payment_method', 'bank_transfer')
                    ->get();
                
                $totalCancelCount = 0;
                foreach ($userOrders as $userOrder) {
                    $this->resetCancelCountIfNeeded($userOrder);
                    $cancelCount = $this->getCancelCount($userOrder);
                    $totalCancelCount += $cancelCount;
                }
                
                Log::info('Total VNPay failure count for user', [
                    'user_id' => Auth::id(),
                    'total_cancel_count' => $totalCancelCount,
                    'current_order_count' => $count
                ]);
                
                // Thông báo sau lần 2
                if ($totalCancelCount == 2) {
                    return redirect()->route('checkout.index', ['order_id' => $order->id])
                        ->with('error', 'Bạn đã thất bại VNPay 2 lần. Vui lòng đổi phương thức khác để hoàn thành, không đơn hàng sẽ bị hủy.');
                }
                
                // Chặn hoàn toàn nếu >=3
                if ($totalCancelCount >= 3) {
                    return redirect()->route('checkout.fail')
                        ->with('error', 'Bạn đã thất bại VNPay quá 3 lần. Vui lòng thử lại sau 2 phút.');
                }
            }
            
            return redirect()->route('checkout.index', ['order_id' => $order->id])->with('error', $msg);
        } catch (\Exception $e) {
            Log::error('VNPAY Return Error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng chọn lại phương thức.');
        }
    }

    /* ============================== Success page ============================== */
    public function success($orderId)
    {
        try {
            $order = Order::with(['orderItems.product'])->where('id', $orderId)->first();

            if (!$order) {
                return redirect()->route('checkout.index')->with('error', 'Không tìm thấy đơn hàng');
            }
            if (Auth::check() && $order->user_id !== Auth::id()) {
                return redirect()->route('checkout.index')->with('error', 'Bạn không có quyền xem đơn hàng này');
            }
            if (!Auth::check() && session('last_order_id') != $orderId) {
                return redirect()->route('checkout.index')->with('error', 'Vui lòng đặt hàng để xem đơn này');
            }

            return view('client.checkouts.success', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error in checkout success: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'user_id'  => Auth::id(),
                'trace'    => $e->getTraceAsString()
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi hiển thị đơn hàng');
        }
    }

    /* ============================== Fail page ============================== */
    public function fail()
    {
        return view('client.checkouts.fail');
    }

    /** Khôi phục giỏ từ order */
    private function restoreCartFromOrder(Order $order)
    {
        try {
            $order->loadMissing('orderItems');

            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
                foreach ($order->orderItems as $item) {
                    Cart::create([
                        'user_id'    => Auth::id(),
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id ?? null,
                        'quantity'   => $item->quantity,
                        'price'      => $item->price,
                    ]);
                }
            } else {
                $cart = [];
                foreach ($order->orderItems as $item) {
                    $key = $item->product_id . ':' . ($item->variant_id ?? 0);
                    $cart[$key] = [
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'quantity'   => $item->quantity,
                        'price'      => $item->price,
                    ];
                }
                session(['cart' => $cart]);
            }

            if ($order->coupon_code) {
                $coupon = Coupon::where('code', $order->coupon_code)
                    ->where('status', true)->whereNull('deleted_at')->first();
                if ($coupon) {
                    session([
                        'restored_coupon' => [
                            'code'   => $coupon->code,
                            'amount' => $order->discount_amount,
                            'details'=> [
                                'discount_type'      => $coupon->discount_type,
                                'value'              => $coupon->value,
                                'max_discount_amount'=> $coupon->max_discount_amount,
                                'min_order_value'    => $coupon->min_order_value,
                                'max_order_value'    => $coupon->max_order_value
                            ]
                        ]
                    ]);
                }
            }

            Log::info('Cart restored from order', [
                'order_id'   => $order->id,
                'user_id'    => Auth::id(),
                'items_count'=> $order->orderItems->count(),
                'has_coupon' => !empty($order->coupon_code)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to restore cart from order: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'user_id'  => Auth::id()
            ]);
        }
    }

    /* ================== Quản lý tồn kho ================== */
    private function reserveStock(Order $order): void
    {
        $order->loadMissing('orderItems');
        $o = Order::lockForUpdate()->find($order->id);

        foreach ($o->orderItems as $item) {
            $qty = (int)$item->quantity;
            if ($qty <= 0) continue;

            if ($item->variant_id) {
                $affected = DB::table('product_variants')
                    ->where('id', $item->variant_id)
                    ->where('stock', '>=', $qty)
                    ->decrement('stock', $qty);
                if (!$affected) throw new \RuntimeException('Biến thể sản phẩm không đủ tồn kho.');
            } else {
                $affected = DB::table('products')
                    ->where('id', $item->product_id)
                    ->where('stock', '>=', $qty)
                    ->decrement('stock', $qty);
                if (!$affected) throw new \RuntimeException('Sản phẩm không đủ tồn kho.');
            }
        }

        $this->setFlag($o, 'reserved');
        $released = $this->flagPath($o, 'released');
        if (file_exists($released)) @unlink($released);
    }

    private function releaseStock(Order $order): void
    {
        $order->loadMissing('orderItems');

        if (!$this->hasFlag($order, 'reserved') || $this->hasFlag($order, 'released')) {
            Log::info('Skip stock release (no reserved or already released)', ['order_id' => $order->id]);
            return;
        }

        DB::transaction(function () use ($order) {
            $o = Order::lockForUpdate()->find($order->id);

            foreach ($o->orderItems as $item) {
                $qty = (int)$item->quantity;
                if ($qty <= 0) continue;

                if ($item->variant_id) {
                    DB::table('product_variants')->where('id', $item->variant_id)->increment('stock', $qty);
                } else {
                    DB::table('products')->where('id', $item->product_id)->increment('stock', $qty);
                }
            }

            $this->setFlag($o, 'released');
        }, 3);

        Log::info('Stock released', ['order_id' => $order->id]);
    }

    public static function releaseStockStatic($order)
    {
        $instance = new self();
        $instance->releaseStock($order);
    }

    /* ================== Coupon helper ================== */
    protected function calculateCouponDiscount($coupon, $subtotal)
    {
        if (!$coupon || !$coupon->status) return 0;
        $now = now();
        if ($coupon->start_date && $now->lt($coupon->start_date)) return 0;
        if ($coupon->end_date && $now->gt($coupon->end_date)) return 0;
        if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) return 0;
        if ($coupon->max_order_value && $subtotal > $coupon->max_order_value) return 0;

        $discount = 0;
        if ($coupon->discount_type === 'percent') {
            $discount = ($subtotal * $coupon->value) / 100;
            if ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount) {
                $discount = $coupon->max_discount_amount;
            }
        } else {
            $discount = $coupon->value;
        }
        return (int)min($discount, $subtotal);
    }
}


