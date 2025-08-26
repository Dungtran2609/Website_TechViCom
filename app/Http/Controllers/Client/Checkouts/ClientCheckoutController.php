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
use App\Http\Requests\Client\CheckoutRequest;
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

    /* ============================== Guest VNPay cancel counter ============================== */
    /** Lấy số lần hủy VNPay cho khách vãng lai từ session */
    private function getGuestCancelCount(): int
    {
        $cancelData = session('guest_vnpay_cancel_count', []);
        $totalCount = 0;
        $currentTime = time();
        
        // Chỉ tính những lần hủy trong vòng 24 giờ qua
        foreach ($cancelData as $timestamp => $count) {
            if ($currentTime - $timestamp < 86400) { // 24 giờ = 86400 giây
                $totalCount += $count;
            }
        }
        
        return $totalCount;
    }

    /** Tăng số lần hủy VNPay cho khách vãng lai */
    private function incrementGuestCancelCount(): int
    {
        $cancelData = session('guest_vnpay_cancel_count', []);
        $currentTime = time();
        
        // Thêm lần hủy mới
        if (!isset($cancelData[$currentTime])) {
            $cancelData[$currentTime] = 0;
        }
        $cancelData[$currentTime]++;
        
        // Dọn dẹp dữ liệu cũ (hơn 24 giờ)
        $cleanedData = [];
        foreach ($cancelData as $timestamp => $count) {
            if ($currentTime - $timestamp < 86400) {
                $cleanedData[$timestamp] = $count;
            }
        }
        
        session(['guest_vnpay_cancel_count' => $cleanedData]);
        
        $totalCount = $this->getGuestCancelCount();
        
        Log::info('Guest VNPay cancel count incremented', [
            'new_count' => $totalCount,
            'session_id' => session()->getId()
        ]);
        
        return $totalCount;
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

        $buildKey = fn($productId, $variantId = null) => sprintf('%s:%s', (int)$productId, $variantId ? (int)$variantId : 0);

        $cartItems = [];
        $subtotal  = 0;
        $selectedParam = $request->get('selected');

        // Xử lý thanh toán lại từ đơn hàng
        $orderId = $request->get('order_id') ?: session('repayment_order_id');
        $existingOrder = null;
        
        // Nếu có selectedParam (mua sản phẩm mới) thì xóa session repayment
        if (!empty($selectedParam)) {
            session()->forget('repayment_order_id');
            session()->forget('show_repayment_message');
            session()->forget('applied_coupon'); // Xóa mã giảm giá đã áp dụng
            $orderId = null; // Reset orderId để không lấy đơn hàng cũ
        }
        
        // Nếu không có order_id trong request, xóa session repayment
        if (!$request->get('order_id')) {
            session()->forget('repayment_order_id');
            session()->forget('show_repayment_message');
            session()->forget('applied_coupon');
        }
        
        if ($orderId && Auth::check()) {
            $existingOrder = Order::with(['orderItems.productVariant.product.productAllImages', 'orderItems.product.productAllImages'])
                ->where('user_id', Auth::id())
                ->where('id', $orderId)
                ->where('status', 'pending')
                ->whereIn('payment_status', ['pending', 'processing', 'failed'])
                ->where('payment_method', '!=', 'cod')
                ->first();

            if ($existingOrder) {
                // Lấy sản phẩm từ đơn hàng cũ
                foreach ($existingOrder->orderItems as $item) {
                    $price = $item->price ?? 0;
                    $image = null;

                    if ($item->productVariant && $item->productVariant->image) {
                        $image = 'storage/' . ltrim($item->productVariant->image, '/');
                    } elseif ($item->product && $item->product->thumbnail) {
                        $image = 'storage/' . ltrim($item->product->thumbnail, '/');
                    } elseif ($item->product && $item->product->productAllImages && $item->product->productAllImages->count() > 0) {
                        $imgObj = $item->product->productAllImages->first();
                        $imgField = $imgObj->image_path ?? $imgObj->image_url ?? $imgObj->image ?? null;
                        if ($imgField) $image = 'uploads/products/' . ltrim($imgField, '/');
                    } else {
                        $image = 'client_css/images/placeholder.svg';
                    }

                    $cartItems[] = (object)[
                        'cart_item_id'   => $buildKey($item->product_id, $item->product_variant_id),
                        'product'        => $item->product,
                        'productVariant' => $item->productVariant,
                        'quantity'       => (int)$item->quantity,
                        'price'          => (float)$price,
                        'product_name'   => $item->name_product,
                        'image'          => $image,
                        'from_existing_order' => true,
                        'order_item_id'  => $item->id,
                        'product_id'     => $item->product_id,
                        'variant_id'     => $item->product_variant_id
                    ];
                    $subtotal += (float)$price * (int)$item->quantity;
                }

                // Lưu thông tin đơn hàng cũ để xử lý sau
                session(['repayment_order_id' => $orderId]);

                // Debug log để kiểm tra giá trị từ đơn hàng cũ
                Log::info('Checkout with existing order', [
                    'order_id' => $orderId,
                    'subtotal_from_order_items' => $subtotal,
                    'order_items_count' => count($cartItems),
                    'existing_order_found' => true,
                    'order_items' => array_map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'name' => $item->product_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'total' => $item->price * $item->quantity
                        ];
                    }, $cartItems)
                ]);
            } else {
                // Debug log khi không tìm thấy đơn hàng
                Log::warning('Repayment order not found', [
                    'order_id' => $orderId,
                    'user_id' => Auth::id()
                ]);

                // Xóa session nếu không tìm thấy đơn hàng
                session()->forget('repayment_order_id');
            }
        }

        /* ---------- ƯU TIÊN ?selected= ---------- */
        // Xử lý selectedParam (mua sản phẩm mới) - ưu tiên cao nhất
        if (empty($cartItems) && !empty($selectedParam)) {
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
                    $product = Product::with(['productAllImages', 'variants'])->find($ci['product_id']);
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
        // Chỉ xử lý buynow nếu không đang thanh toán lại
        elseif (empty($cartItems) && session('buynow') && !$existingOrder) {
            $buynow = session('buynow');
            $product = Product::with(['productAllImages', 'variants'])->find($buynow['product_id']);
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
        // Chỉ xử lý mặc định nếu không đang thanh toán lại
        elseif (empty($cartItems) && !$existingOrder) {
            if (Auth::check()) {
                $dbCartItems = Cart::with([
                    'product.productAllImages',
                    'product.variants',
                    'productVariant'
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
                    $product = Product::with(['productAllImages', 'variants'])->find($ci['product_id']);
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

        // Nếu không có cartItems và không có existingOrder, xóa session repayment và redirect
        if (empty($cartItems) && !$existingOrder) {
            session()->forget('repayment_order_id');
            session()->forget('show_repayment_message');
            return redirect()->route('carts.index')->with('error', 'Giỏ hàng trống');
        }
        
        // Nếu có cartItems từ giỏ hàng (không phải thanh toán lại), xóa session repayment
        if (!empty($cartItems) && !$existingOrder && !$request->get('order_id')) {
            session()->forget('repayment_order_id');
            session()->forget('show_repayment_message');
            session()->forget('applied_coupon'); // Xóa mã giảm giá đã áp dụng
        }

        // Nếu đang thanh toán lại nhưng không có cartItems, redirect về cart với thông báo lỗi
        if ($existingOrder && empty($cartItems)) {
            Log::error('Repayment order found but no cart items loaded', [
                'order_id' => $existingOrder->id,
                'user_id' => Auth::id(),
                'order_items_count' => $existingOrder->orderItems->count()
            ]);

            session()->forget('repayment_order_id');
            return redirect()->route('carts.index')->with('error', 'Không thể tải thông tin đơn hàng cần thanh toán lại');
        }

        // Debug log cho thanh toán lại
        if ($existingOrder) {
            Log::info('Repayment order processed successfully', [
                'order_id' => $existingOrder->id,
                'cart_items_count' => count($cartItems),
                'subtotal' => $subtotal
            ]);
        }

        $this->forgetCartEmptyErrorIfAny();

        // Địa chỉ
        $addresses = [];
        $currentUser = null;
        $defaultAddress = null;
        if (Auth::check()) {
            $currentUser = Auth::user();
            $addresses = UserAddress::where('user_id', Auth::id())->orderBy('is_default', 'desc')->get();
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
                Log::info('VNPay locked due to total spam for logged user', [
                    'user_id' => Auth::id(),
                    'total_cancel_count' => $totalCancelCount
                ]);
            }
        } else {
            // Kiểm tra spam chặn cho khách vãng lai
            $guestCancelCount = $this->getGuestCancelCount();
            
            if ($guestCancelCount >= 3) {
                $vnpayLocked = true;
                $orderVnpayCancelCount = $guestCancelCount;
                Log::info('VNPay locked due to total spam for guest', [
                    'session_id' => session()->getId(),
                    'guest_cancel_count' => $guestCancelCount
                ]);
            }
        }

        // Preview coupon (tùy chọn – giữ nguyên code cũ)
        $appliedCoupon = null;
        $discountAmount = 0;
        $couponMessage = null;
        
        // Tự động áp dụng mã giảm giá từ đơn hàng cũ khi thanh toán lại
        // Chỉ áp dụng khi thực sự đang thanh toán lại (có order_id trong request)
        $isRepayment = $request->get('order_id') && !empty($request->get('order_id'));
        
        // Đối với đơn hàng mới, không áp dụng mã giảm giá tự động
        if (!$isRepayment) {
            $appliedCoupon = null;
            $discountAmount = 0;
            $couponMessage = null;
        }
        
        if ($isRepayment && $existingOrder && $existingOrder->coupon_code) {
            $couponCode = $existingOrder->coupon_code;
            $coupon = Coupon::where('code', $couponCode)
                ->where('status', true)
                ->whereNull('deleted_at')
                ->first();

            if ($coupon) {
                // Kiểm tra thời gian hiệu lực
                $now = Carbon::now();
                if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
                    $couponMessage = 'Mã giảm giá chưa có hiệu lực';
                } elseif ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
                    $couponMessage = 'Mã giảm giá đã hết hạn';
                } elseif ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
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
                    $couponMessage = 'Áp dụng mã thành công! (Tự động áp dụng lại từ đơn hàng cũ)';
                }
            } else {
                $couponMessage = 'Mã giảm giá không tồn tại hoặc đã bị vô hiệu hóa';
            }
        }
        
        // Nếu có mã giảm giá mới được nhập
        if ($request->filled('coupon_code')) {
            $couponCode = $request->input('coupon_code');
            $coupon = Coupon::where('code', $couponCode)
                ->where('status', true)
                ->whereNull('deleted_at')
                ->first();

            if ($coupon) {
                // Kiểm tra thời gian hiệu lực
                $now = Carbon::now();
                if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
                    $couponMessage = 'Mã giảm giá chưa có hiệu lực';
                } elseif ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
                    $couponMessage = 'Mã giảm giá đã hết hạn';
                } elseif ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
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
                $couponMessage = 'Mã giảm giá không tồn tại hoặc đã bị vô hiệu hóa';
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
            'vnpayLocked',
            'existingOrder'
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
            ->where('status', true)
            ->whereNull('deleted_at')
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã bị vô hiệu hóa',
            ]);
        }

        // Kiểm tra thời gian hiệu lực
        $now = Carbon::now();
        if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá chưa có hiệu lực',
            ]);
        }
        
        if ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã hết hạn',
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
    public function process(CheckoutRequest $request)
    {
        try {
            Log::info('Checkout process started', [
                'payment_method'   => $request->payment_method,
                'shipping_method'  => $request->shipping_method,
                'user_id'          => Auth::id(),
                'selected_address' => $request->selected_address ?? null,
                'is_guest'         => !Auth::check(),
                'request_data'     => $request->all()
            ]);

            /* Chặn VNPay nếu user đã hủy >=3 lần */
            if (($request->payment_method ?? '') === 'bank_transfer') {
                if (Auth::check()) {
                    // Kiểm tra tổng số lần hủy VNPay của user đăng nhập
                    $userOrders = Order::where('user_id', Auth::id())
                        ->where('payment_method', 'bank_transfer')
                        ->get();

                    $totalCancelCount = 0;
                    foreach ($userOrders as $userOrder) {
                        $this->resetCancelCountIfNeeded($userOrder);
                        $cancelCount = $this->getCancelCount($userOrder);
                        $totalCancelCount += $cancelCount;
                    }

                    Log::info('Checking VNPay spam protection for logged user in process', [
                        'user_id' => Auth::id(),
                        'total_cancel_count' => $totalCancelCount,
                        'orders_count' => $userOrders->count()
                    ]);

                    if ($totalCancelCount >= 3) {
                        Log::info('VNPay blocked due to total spam for logged user in process', [
                            'user_id' => Auth::id(),
                            'total_cancel_count' => $totalCancelCount
                        ]);
                        return redirect()->route('checkout.fail')
                            ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ.');
                    }
                } else {
                    // Kiểm tra tổng số lần hủy VNPay của khách vãng lai
                    $guestCancelCount = $this->getGuestCancelCount();
                    
                    Log::info('Checking VNPay spam protection for guest in process', [
                        'session_id' => session()->getId(),
                        'guest_cancel_count' => $guestCancelCount
                    ]);

                    if ($guestCancelCount >= 3) {
                        Log::info('VNPay blocked due to total spam for guest in process', [
                            'session_id' => session()->getId(),
                            'guest_cancel_count' => $guestCancelCount
                        ]);
                        return redirect()->route('checkout.fail')
                            ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ.');
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
                    'recipient_address' => $address->address_line . ', ' . $address->ward . ', ' . $address->district . ', ' . $address->city,
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
                    'recipient_address' => $request->recipient_address,
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

            // Kiểm tra xem có phải thanh toán lại sau khi hủy không
            $isRetryAfterCancel = false;
            if (Auth::check() && !empty($selectedIdsArr)) {
                $existingCartItems = Cart::where('user_id', Auth::id())->get();
                $foundSelectedItems = Cart::where('user_id', Auth::id())->whereIn('id', $selectedIdsArr)->get();

                // Nếu có cart items nhưng không tìm thấy selected items, có thể là retry sau khi hủy
                if ($existingCartItems->count() > 0 && $foundSelectedItems->count() === 0) {
                    $isRetryAfterCancel = true;
                    Log::info('Detected retry after cancel - using all cart items', [
                        'user_id' => Auth::id(),
                        'selected_ids' => $selectedIdsArr,
                        'existing_cart_count' => $existingCartItems->count(),
                        'found_selected_count' => $foundSelectedItems->count()
                    ]);
                }
            }

            // Kiểm tra xem có phải thanh toán lại không
            $repaymentOrderId = session('repayment_order_id');
            $isRepayment = $repaymentOrderId && Auth::check();

            // Debug log cho thanh toán lại
            Log::info('Checkout process - repayment check', [
                'repayment_order_id' => $repaymentOrderId,
                'is_repayment' => $isRepayment,
                'user_id' => Auth::id(),
                'selected_ids' => $selectedIds,
                'selected_ids_array' => $selectedIdsArr
            ]);

            if ($isRepayment) {
                // Nếu đang thanh toán lại, lấy sản phẩm từ đơn hàng cũ
                $existingOrder = Order::with(['orderItems.productVariant.product.images'])
                    ->where('user_id', Auth::id())
                    ->where('id', $repaymentOrderId)
                    ->where('status', 'pending')
                    ->whereIn('payment_status', ['pending', 'processing', 'failed'])
                    ->where('payment_method', '!=', 'cod')
                    ->first();

                if ($existingOrder) {
                    Log::info('Repayment order found in process', [
                        'order_id' => $existingOrder->id,
                        'order_items_count' => $existingOrder->orderItems->count()
                    ]);

                    foreach ($existingOrder->orderItems as $item) {
                        $price = $item->price ?? 0;
                        $image = null;

                        if ($item->productVariant && $item->productVariant->image) {
                            $image = 'storage/' . ltrim($item->productVariant->image, '/');
                        } elseif ($item->product && $item->product->thumbnail) {
                            $image = 'storage/' . ltrim($item->product->thumbnail, '/');
                        } elseif ($item->product && $item->product->productAllImages && $item->product->productAllImages->count() > 0) {
                            $imgObj = $item->product->productAllImages->first();
                            $imgField = $imgObj->image_path ?? $imgObj->image_url ?? $imgObj->image ?? null;
                            if ($imgField) $image = 'uploads/products/' . ltrim($imgField, '/');
                        } else {
                            $image = 'client_css/images/placeholder.svg';
                        }

                        $cartItems->push((object)[
                            'product_id'    => $item->product_id,
                            'variant_id'    => $item->product_variant_id,
                            'product'       => $item->product,
                            'productVariant' => $item->productVariant,
                            'quantity'      => (int)$item->quantity,
                            'price'         => (float)$price,
                            'image'         => $image,
                            'from_existing_order' => true,
                            'order_item_id' => $item->id,
                        ]);
                    }
                    $source = 'repayment';

                    Log::info('Repayment cart items created', [
                        'cart_items_count' => $cartItems->count(),
                        'source' => $source
                    ]);
                } else {
                    Log::warning('Repayment order not found in process', [
                        'repayment_order_id' => $repaymentOrderId,
                        'user_id' => Auth::id()
                    ]);
                }
            }

            $buynow = session('buynow');
            if ($buynow && !$isRepayment) {
                $product = Product::with(['productAllImages', 'variants'])->find($buynow['product_id']);
                if (!$product) return redirect()->route('checkout.index')->with('error', 'Sản phẩm không tồn tại');
                $variant = !empty($buynow['variant_id']) ? \App\Models\ProductVariant::find($buynow['variant_id']) : null;
                $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                $cartItems->push((object)[
                    'product_id'    => $product->id,
                    'variant_id'    => $variant?->id,
                    'product'       => $product,
                    'productVariant' => $variant,
                    'quantity'      => (int)($buynow['quantity'] ?? 1),
                    'price'         => (float)$price,
                    'image'         => $variant?->image ? 'storage/' . $variant->image
                        : ($product->thumbnail ? 'storage/' . $product->thumbnail
                            : (($product->productAllImages?->count() > 0)
                                ? 'storage/' . $product->productAllImages->first()->image_path
                                : 'client_css/images/placeholder.svg')),
                ]);
                $source = 'buynow';
            } elseif (!$isRepayment) {
                if (!Auth::check()) {
                    $sessionCart = session()->get('cart', []);
                    Log::info('Guest checkout - session cart processing', [
                        'session_cart_count' => count($sessionCart),
                        'session_cart_keys' => array_keys($sessionCart),
                        'selected_ids' => $selectedIds,
                        'selected_ids_array' => $selectedIdsArr
                    ]);
                    
                    if (empty($sessionCart)) {
                        Log::warning('Guest checkout - empty session cart');
                        return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');
                    }

                    // Debug log để kiểm tra selectedIds
                    Log::info('Checkout session cart processing', [
                        'selected_ids' => $selectedIds,
                        'selected_ids_array' => $selectedIdsArr,
                        'session_cart_keys' => array_keys($sessionCart),
                        'session_cart_count' => count($sessionCart)
                    ]);

                    if (!empty($selectedIdsArr)) {
                        $filtered = [];
                        foreach ($selectedIdsArr as $selectedKey) {
                            // Thử xử lý format "product_id:variant_id" trước
                            $parts = explode(':', $selectedKey);
                            if (count($parts) === 2) {
                                $productId = $parts[0];
                                $variantId = $parts[1] === '0' ? 'default' : $parts[1];
                                $sessionKey = $productId . '_' . $variantId;

                                if (isset($sessionCart[$sessionKey])) {
                                    $filtered[$sessionKey] = $sessionCart[$sessionKey];
                                    Log::info('Added to filtered cart (product:variant format)', [
                                        'selected_key' => $selectedKey,
                                        'session_key' => $sessionKey,
                                        'item' => $sessionCart[$sessionKey]
                                    ]);
                                    continue;
                                }
                            }

                            // Nếu không tìm thấy, thử tìm theo product_id và variant_id trong session cart
                            $found = false;
                            foreach ($sessionCart as $sessionKey => $cartItem) {
                                if ($cartItem['product_id'] == $selectedKey) {
                                    $filtered[$sessionKey] = $cartItem;
                                    Log::info('Added to filtered cart (by product_id)', [
                                        'selected_key' => $selectedKey,
                                        'session_key' => $sessionKey,
                                        'item' => $cartItem
                                    ]);
                                    $found = true;
                                    break;
                                }
                            }

                            if (!$found) {
                                Log::warning('Selected key not found in session cart', [
                                    'selected_key' => $selectedKey,
                                    'available_keys' => array_keys($sessionCart)
                                ]);
                            }
                        }
                        if (!empty($filtered)) {
                            $sessionCart = $filtered;
                            Log::info('Filtered session cart', [
                                'filtered_keys' => array_keys($filtered),
                                'filtered_count' => count($filtered)
                            ]);
                        } else {
                            Log::warning('No items found in filtered cart, returning error');

                            // Thử sử dụng tất cả session cart items nếu không tìm thấy selected items
                            if (count($sessionCart) > 0) {
                                Log::info('Using all session cart items instead of selected items', [
                                    'session_cart_count' => count($sessionCart)
                                ]);
                            } else {
                                return redirect()->route('checkout.index')->with('error', 'Không tìm thấy sản phẩm đã chọn');
                            }
                        }
                    }

                    foreach ($sessionCart as $ci) {
                        $product = Product::with(['productAllImages', 'variants'])->find($ci['product_id']);
                        if (!$product) continue;
                        $variant = !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null;
                        $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                        $cartItems->push((object)[
                            'product_id'    => $product->id,
                            'variant_id'    => $variant?->id,
                            'product'       => $product,
                            'productVariant' => $variant,
                            'quantity'      => (int)($ci['quantity'] ?? 1),
                            'price'         => (float)$price,
                            'image'         => $variant?->image ? 'storage/' . $variant->image
                                : ($product->thumbnail ? 'storage/' . $product->thumbnail
                                    : (($product->productAllImages?->count() > 0)
                                        ? 'storage/' . $product->productAllImages->first()->image_path
                                        : 'client_css/images/placeholder.svg')),
                        ]);
                    }
                    if ($cartItems->isEmpty()) {
                        Log::warning('Guest checkout - cart items is empty after processing', [
                            'session_cart_count' => count($sessionCart),
                            'cart_items_count' => $cartItems->count()
                        ]);
                        return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');
                    }
                    Log::info('Guest checkout - cart items processed successfully', [
                        'cart_items_count' => $cartItems->count(),
                        'source' => $source
                    ]);
                    $source = 'session';
                } else {
                    if (!empty($selectedIdsArr) && !$isRetryAfterCancel) {
                        $dbCartItems = Cart::with(['product.productAllImages', 'product.variants', 'productVariant'])
                            ->where('user_id', Auth::id())->whereIn('id', $selectedIdsArr)->get();
                        if ($dbCartItems->isEmpty()) {
                            Log::warning('Selected cart items not found for user', [
                                'user_id' => Auth::id(),
                                'selected_ids' => $selectedIdsArr
                            ]);

                            // Thử lấy tất cả cart items nếu không tìm thấy selected items
                            $allCartItems = Cart::with(['product.productAllImages', 'product.variants', 'productVariant'])
                                ->where('user_id', Auth::id())->get();

                            if ($allCartItems->isEmpty()) {
                                return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');
                            } else {
                                // Sử dụng tất cả cart items nếu không tìm thấy selected items
                                $dbCartItems = $allCartItems;
                                Log::info('Using all cart items instead of selected items', [
                                    'user_id' => Auth::id(),
                                    'all_cart_items_count' => $allCartItems->count()
                                ]);
                            }
                        }
                    } else {
                        // Nếu là retry sau khi hủy hoặc không có selected IDs, lấy tất cả cart items
                        $dbCartItems = Cart::with(['product.productAllImages', 'product.variants', 'productVariant'])
                            ->where('user_id', Auth::id())->get();

                        if ($isRetryAfterCancel) {
                            Log::info('Using all cart items for retry after cancel', [
                                'user_id' => Auth::id(),
                                'cart_items_count' => $dbCartItems->count()
                            ]);
                        }
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
                            'productVariant' => $ci->productVariant,
                            'quantity'      => (int)$ci->quantity,
                            'price'         => (float)$price,
                            'image'         => $image,
                        ]);
                    }
                    $source = 'db';
                }
            }

            // ==== phí & coupon ====
            // Đảm bảo $subtotal luôn được định nghĩa
            if ($isRepayment) {
                // Nếu đang thanh toán lại, tính từ cartItems đã được tạo từ orderItems cũ
                $subtotal = $cartItems->sum(fn($i) => ((float)$i->price) * ((int)$i->quantity));
            } else {
                // Nếu không phải thanh toán lại, tính từ giỏ hàng hiện tại
                $subtotal = $cartItems->sum(fn($i) => ((float)$i->price) * ((int)$i->quantity));
            }

            // Đảm bảo $subtotal không bao giờ null hoặc undefined
            if (!isset($subtotal) || $subtotal === null) {
                $subtotal = 0;
            }

            // Kiểm tra giỏ hàng trống (chỉ khi không phải thanh toán lại)
            if (!$isRepayment && $cartItems->isEmpty()) {
                Log::warning('Cart is empty for non-repayment checkout', [
                    'is_repayment' => $isRepayment,
                    'cart_items_count' => $cartItems->count()
                ]);

                // Nếu có selected IDs nhưng không có cart items, có thể là do cart đã bị xóa
                if (!empty($selectedIdsArr)) {
                    Log::info('Redirecting to cart due to missing cart items', [
                        'selected_ids' => $selectedIdsArr,
                        'user_id' => Auth::id()
                    ]);
                    return redirect()->route('client.carts.index')->with('error', 'Sản phẩm đã chọn không còn trong giỏ hàng. Vui lòng kiểm tra lại.');
                }

                return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');
            }

            // Debug log cho cart items
            Log::info('Cart items before processing', [
                'is_repayment' => $isRepayment,
                'cart_items_count' => $cartItems->count(),
                'source' => $source
            ]);

            // Nếu đang thanh toán lại nhưng không có đơn hàng cũ, redirect về cart
            if ($isRepayment) {
                $checkExistingOrder = Order::where('user_id', Auth::id())
                    ->where('id', $repaymentOrderId)
                    ->where('status', 'pending')
                    ->whereIn('payment_status', ['pending', 'processing', 'failed'])
                    ->where('payment_method', '!=', 'cod')
                    ->first();

                if (!$checkExistingOrder) {
                    Log::warning('Repayment order not found in validation', [
                        'repayment_order_id' => $repaymentOrderId,
                        'user_id' => Auth::id()
                    ]);
                    session()->forget('repayment_order_id');
                    return redirect()->route('carts.index')->with('error', 'Không tìm thấy đơn hàng cần thanh toán lại');
                } else {
                    Log::info('Repayment order validated successfully', [
                        'order_id' => $checkExistingOrder->id,
                        'payment_status' => $checkExistingOrder->payment_status
                    ]);
                }
            }
            $shippingMethodId = $request->shipping_method_id;

            // Nếu thanh toán lại, sử dụng giá từ đơn hàng cũ
            if ($isRepayment) {
                // Tìm đơn hàng cũ để lấy giá
                $existingOrderForPricing = Order::where('user_id', Auth::id())
                    ->where('id', $repaymentOrderId)
                    ->where('status', 'pending')
                    ->whereIn('payment_status', ['pending', 'processing', 'failed'])
                    ->where('payment_method', '!=', 'cod')
                    ->first();

                if ($existingOrderForPricing) {
                    $shippingFee = $existingOrderForPricing->shipping_fee ?? 0;
                    $discountAmount = $existingOrderForPricing->discount_amount ?? 0;
                    $couponCode = $existingOrderForPricing->coupon_code;
                } else {
                    // Fallback nếu không tìm thấy đơn hàng cũ
                    $shippingFee = ($shippingMethodId == 1) ? (($subtotal >= 3000000) ? 0 : 50000) : 0;
                    $discountAmount = 0;
                    $couponCode = null;
                }
            } else {
                // Nếu thanh toán mới, tính toán bình thường
                $shippingFee = ($shippingMethodId == 1) ? (($subtotal >= 3000000) ? 0 : 50000) : 0;
                $discountAmount = 0;
                $couponCode = null;
            }
            // Xử lý coupon cho thanh toán mới hoặc thanh toán lại
            if (!empty($request->coupon_code)) {
                // Khách vãng lai không thể áp dụng coupon
                if (!Auth::check()) {
                    return redirect()->route('checkout.index')->with('error', 'Vui lòng đăng nhập để nhận khuyến mãi');
                }
                
                $couponCode = $request->coupon_code;
                $coupon = Coupon::where('code', $couponCode)->where('status', true)->whereNull('deleted_at')->first();
                
                // Chỉ kiểm tra số lần sử dụng coupon nếu không phải thanh toán lại
                if (!$isRepayment && $coupon && $coupon->max_usage_per_user > 0) {
                    $usedCount = 0;
                    
                    if (Auth::check()) {
                        // User đã đăng nhập - kiểm tra theo user_id
                        $usedCount = Order::where('user_id', Auth::id())
                            ->where('coupon_code', $coupon->code)
                            ->whereNull('deleted_at')
                            ->count();
                    } else {
                        // Khách vãng lai - kiểm tra theo email hoặc phone từ request
                        $guestEmail = $request->input('guest_email');
                        $guestPhone = $request->input('guest_phone');
                        
                        if ($guestEmail) {
                            $usedCount = Order::where('guest_email', $guestEmail)
                                ->where('coupon_code', $coupon->code)
                                ->whereNull('deleted_at')
                                ->count();
                        } elseif ($guestPhone) {
                            $usedCount = Order::where('guest_phone', $guestPhone)
                                ->where('coupon_code', $coupon->code)
                                ->whereNull('deleted_at')
                                ->count();
                        }
                    }
                    
                    if ($usedCount >= $coupon->max_usage_per_user) {
                        return redirect()->route('checkout.index')->with('error', 'Bạn đã sử dụng hết số lần cho phép cho mã giảm giá này.');
                    }
                }
                
                // Debug log cho thanh toán lại
                if ($isRepayment) {
                    Log::info('Repayment coupon processing - skipping usage limit check', [
                        'order_id' => $repaymentOrderId,
                        'coupon_code' => $couponCode,
                        'reason' => 'Coupon already used in original order'
                    ]);
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

            // Tính final_total dựa trên loại thanh toán
            if ($isRepayment) {
                // Khi thanh toán lại, sử dụng giá từ đơn hàng cũ
                $finalTotal = $subtotal + $shippingFee - $discountAmount;
            } else {
                // Khi thanh toán mới, tính toán bình thường
                $finalTotal = $subtotal + $shippingFee - $discountAmount;
            }

            // Debug log để kiểm tra giá trị cuối cùng
            Log::info('Checkout final calculation', [
                'is_repayment' => $isRepayment,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => $discountAmount,
                'final_total' => $finalTotal,
                'cart_items_count' => count($cartItems),
                'repayment_order_id' => $repaymentOrderId
            ]);

            // Map CODE -> NAME
            $provinceCode = $addressData['province_code'] ?? '01';
            $districtCode = $addressData['district_code'] ?? '';
            $wardCode     = $addressData['ward_code'] ?? '';
            $provinceName = $this->provinceNameByCode($provinceCode);
            $districtName = $this->districtNameByCode($districtCode);
            $wardName     = $this->wardNameByCode($wardCode);

            // ==== Tạo order + TRỪ KHO (transaction) ====
            DB::beginTransaction();

            // Kiểm tra xem có phải thanh toán lại không
            $existingOrder = null;

            if ($isRepayment) {
                $existingOrder = Order::where('user_id', Auth::id())
                    ->where('id', $repaymentOrderId)
                    ->where('status', 'pending')
                    ->whereIn('payment_status', ['pending', 'processing', 'failed'])
                    ->where('payment_method', '!=', 'cod')
                    ->first();
            }

            if ($existingOrder) {
                // Cập nhật đơn hàng cũ - GIỮ NGUYÊN GIÁ TỪ ORDER CŨ
                $order = $existingOrder;

                Log::info('Updating existing order for repayment', [
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_method,
                    'cart_items_count' => $cartItems->count()
                ]);

                // Tính lại tổng tiền từ orderItems cũ để đảm bảo giá không thay đổi
                $originalSubtotal = $order->orderItems->sum(function ($item) {
                    return $item->price * $item->quantity;
                });

                // Sử dụng giá cũ cho shipping fee và discount
                $originalShippingFee = $order->shipping_fee ?? 0;
                $originalDiscountAmount = $order->discount_amount ?? 0;

                // Tính lại final_total dựa trên giá cũ hoàn toàn
                $originalFinalTotal = $originalSubtotal + $originalShippingFee - $originalDiscountAmount;

                $order->update([
                    // Cập nhật thông tin người nhận mới
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
                    'total_amount'       => $originalSubtotal, // Sử dụng giá cũ
                    'shipping_fee'       => $originalShippingFee, // Sử dụng giá cũ
                    'discount_amount'    => $originalDiscountAmount, // Sử dụng giá cũ
                    'coupon_code'        => $order->coupon_code, // Giữ nguyên coupon cũ
                    'final_total'        => $originalFinalTotal, // Sử dụng giá cũ
                    'order_notes'        => $request->order_notes, // Cập nhật ghi chú mới
                    'payment_status'     => $request->payment_method === 'cod' ? 'pending' : 'processing',
                    'updated_at'         => now(),
                    // Reset các trường VNPay khi thanh toán lại
                    'vnpay_url'          => null,
                    'vnpay_transaction_id' => null,
                    'vnpay_bank_code'    => null,
                    'vnpay_card_type'    => null,
                    'vnp_txn_ref'        => null,
                    'vnp_amount_expected' => null,
                    'vnpay_discount'     => 0, // Reset giảm giá VNPay khi thanh toán lại
                ]);

                // Cập nhật lại thông tin sản phẩm trong orderItems để đảm bảo hiển thị đúng
                foreach ($order->orderItems as $orderItem) {
                    $product = Product::find($orderItem->product_id);
                    if ($product) {
                        $orderItem->update([
                            'name_product' => $product->name,
                            'price' => $orderItem->price // Giữ nguyên giá đã đặt
                        ]);
                    }
                }

                // Debug log để kiểm tra giá trị khi thanh toán lại
                Log::info('Repayment order update', [
                    'order_id' => $order->id,
                    'original_subtotal' => $originalSubtotal,
                    'original_final_total' => $originalFinalTotal,
                    'original_shipping_fee' => $originalShippingFee,
                    'original_discount_amount' => $originalDiscountAmount,
                    'vnpay_discount_before_reset' => $order->vnpay_discount,
                    'new_subtotal_from_cart' => $subtotal,
                    'new_final_total_from_cart' => $finalTotal,
                    'new_shipping_fee' => $shippingFee,
                    'new_discount_amount' => $discountAmount,
                    'order_items' => $order->orderItems->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'name' => $item->name_product,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'total' => $item->price * $item->quantity
                        ];
                    })->toArray()
                ]);

                // Xóa session
                session()->forget('repayment_order_id');

                Log::info('Existing order updated successfully', [
                    'order_id' => $order->id,
                    'payment_status' => $order->payment_status
                ]);
            } else {
                // Tạo đơn hàng mới
                Log::info('Creating new order', [
                    'is_repayment' => $isRepayment,
                    'cart_items_count' => $cartItems->count(),
                    'source' => $source
                ]);

                $orderData = [
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
                    'order_notes'        => $request->order_notes,
                    'status'             => 'pending',
                    'payment_status'     => $request->payment_method === 'cod' ? 'pending' : 'processing',
                ];

                // Nếu là khách vãng lai, lưu thông tin guest
                if (!Auth::check()) {
                    $orderData['guest_name'] = $addressData['recipient_name'];
                    $orderData['guest_email'] = $addressData['recipient_email'];
                    $orderData['guest_phone'] = $addressData['recipient_phone'];
                    $orderData['user_id'] = null; // Đảm bảo user_id là null cho khách vãng lai
                }

                $order = Order::create($orderData);

                // Chỉ tạo orderItems mới nếu không phải thanh toán lại
                if (!$isRepayment) {
                    foreach ($cartItems as $item) {
                        $price     = (float)$item->price;
                        $variantId = $item->variant_id ?? ($item->productVariant->id ?? null);
                        $imageProd = $item->image ?? null;
                        $totalPrice = $price * (int)$item->quantity;

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
                }
            }

            // Trừ kho (chỉ khi tạo đơn hàng mới)
            if (!$existingOrder) {
                $this->reserveStock($order);
            }

            DB::commit();

            // Dọn giỏ (chỉ khi tạo đơn hàng mới)
            if (!$existingOrder) {
                session()->forget('buynow');
                if ($source === 'session') {
                    $cart = session()->get('cart', []);
                    $removedCount = 0;
                    foreach ($cartItems as $item) {
                        // Tạo key giống như khi thêm vào cart (format: product_id_variant_id)
                        $variantId = $item->variant_id ?? 'default';
                        $key = $item->product_id . '_' . $variantId;

                        if (isset($cart[$key])) {
                            unset($cart[$key]);
                            $removedCount++;
                            Log::info('Removed from session cart', [
                                'key' => $key,
                                'product_id' => $item->product_id,
                                'variant_id' => $item->variant_id,
                                'quantity' => $item->quantity
                            ]);
                        } else {
                            // Fallback: tìm kiếm theo product_id và variant_id
                            foreach ($cart as $cartKey => $cartItem) {
                                if (
                                    $cartItem['product_id'] == $item->product_id &&
                                    ($cartItem['variant_id'] ?? null) == ($item->variant_id ?? null)
                                ) {
                                    unset($cart[$cartKey]);
                                    $removedCount++;
                                    Log::info('Removed from session cart with fallback search', [
                                        'expected_key' => $key,
                                        'found_key' => $cartKey,
                                        'product_id' => $item->product_id,
                                        'variant_id' => $item->variant_id
                                    ]);
                                    break;
                                }
                            }
                        }
                    }
                    session(['cart' => $cart]);
                    Log::info('Session cart cleanup completed', [
                        'items_processed' => count($cartItems),
                        'items_removed' => $removedCount,
                        'remaining_items' => count($cart)
                    ]);
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
            }

            // Thanh toán
            if ($request->payment_method === 'cod') {
                // Nếu đang có session force_cod_for_order_id, COD thành công thì bỏ ép
                if (session()->has('force_cod_for_order_id')) {
                    session()->forget('force_cod_for_order_id');
                    session()->forget('payment_cancelled_message');
                }

                // Dọn dẹp session thanh toán lại
                session()->forget('repayment_order_id');

                // Chỉ tạo session last_order_id khi tạo đơn hàng mới
                if (!$existingOrder) {
                    session(['last_order_id' => $order->id]);
                }

                $successMessage = $existingOrder ? 'Cập nhật phương thức thanh toán thành công!' : 'Đặt hàng thành công! Chúng tôi sẽ liên hệ sớm nhất.';
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', $successMessage);
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
                } else {
                    // Kiểm tra tổng số lần hủy VNPay của khách vãng lai
                    $guestCancelCount = $this->getGuestCancelCount();
                    
                    if ($guestCancelCount >= 3) {
                        Log::info('VNPay blocked due to total spam for guest in final check', [
                            'session_id' => session()->getId(),
                            'guest_cancel_count' => $guestCancelCount
                        ]);
                        return redirect()->route('checkout.fail')
                            ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ.');
                    }
                }



                $txnRef = sprintf('VNP-%s-%s-%04d', $order->id, now()->format('YmdHis'), random_int(0, 9999));
                $amountExpected = (int) round($order->final_total * 100);

                // Debug log để kiểm tra giá trị VNPay
                Log::info('VNPay payment amount', [
                    'order_id' => $order->id,
                    'final_total' => $order->final_total,
                    'amount_expected' => $amountExpected,
                    'is_repayment' => $isRepayment,
                    'original_subtotal' => $existingOrder ? $existingOrder->orderItems->sum(function ($item) {
                        return $item->price * $item->quantity;
                    }) : null
                ]);

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

                // Thông báo khi thanh toán lại
                if ($existingOrder) {
                    session()->flash('repayment_message', 'Đang chuyển đến trang thanh toán VNPay...');
                }

                // Giữ session repayment_order_id khi chuyển đến VNPay
                if ($isRepayment) {
                    session(['repayment_order_id' => $order->id]);
                }

                return redirect($paymentUrl);
            }
        } catch (\RuntimeException $ex) {
            DB::rollBack();
            Log::warning('Out of stock at checkout', ['msg' => $ex->getMessage()]);
            // Dọn dẹp session khi có lỗi
            session()->forget('repayment_order_id');
            return redirect()->route('checkout.index')->with('error', $ex->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            // Dọn dẹp session khi có lỗi
            session()->forget('repayment_order_id');
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
                session()->forget('repayment_order_id');
                return redirect()->route('checkout.index')->with('error', 'Bạn không có quyền truy cập đơn hàng này');
            }

            if ($order->payment_status === 'paid') {
                session()->forget('repayment_order_id');
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

                Log::info('Checking VNPay spam protection in vnpay_payment for logged user', [
                    'user_id' => Auth::id(),
                    'total_cancel_count' => $totalCancelCount,
                    'orders_count' => $userOrders->count()
                ]);

                if ($totalCancelCount >= 3) {
                    Log::info('VNPay blocked due to total spam in vnpay_payment for logged user', [
                        'user_id' => Auth::id(),
                        'total_cancel_count' => $totalCancelCount
                    ]);
                    session()->forget('repayment_order_id');
                    return redirect()->route('checkout.fail')
                        ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ.');
                }
            } else {
                // Kiểm tra spam chặn cho khách vãng lai
                $guestCancelCount = $this->getGuestCancelCount();
                
                Log::info('Checking VNPay spam protection in vnpay_payment for guest', [
                    'session_id' => session()->getId(),
                    'guest_cancel_count' => $guestCancelCount
                ]);

                if ($guestCancelCount >= 3) {
                    Log::info('VNPay blocked due to total spam in vnpay_payment for guest', [
                        'session_id' => session()->getId(),
                        'guest_cancel_count' => $guestCancelCount
                    ]);
                    session()->forget('repayment_order_id');
                    return redirect()->route('checkout.fail')
                        ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ.');
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

            // Giữ session repayment_order_id khi khởi tạo lại VNPay
            // Không thay đổi giá trị session nếu đang thanh toán lại
            if (!session('repayment_order_id')) {
                session(['repayment_order_id' => $order->id]);
            }

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error('VNPAY Payment Error: ' . $e->getMessage(), [
                'order_id' => $order_id,
                'trace' => $e->getTraceAsString()
            ]);

            // Dọn dẹp session khi có lỗi
            session()->forget('repayment_order_id');

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

                    Log::info('Total VNPay cancel count for logged user', [
                        'user_id' => Auth::id(),
                        'total_cancel_count' => $totalCancelCount,
                        'current_order_count' => $count
                    ]);

                    // Thông báo sau lần 2
                    if ($totalCancelCount == 2) {
                        session(['repayment_order_id' => $order->id]);
                        return redirect()->route('checkout.index')
                            ->with('error', 'Bạn đã hủy VNPay 2 lần. Vui lòng đổi phương thức khác để hoàn thành, không đơn hàng sẽ bị hủy.');
                    }

                    // Chặn hoàn toàn nếu >=3
                    if ($totalCancelCount >= 3) {
                        session()->forget('repayment_order_id');
                        return redirect()->route('checkout.fail')
                            ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ.');
                    }
                } else {
                    // Xử lý cho khách vãng lai
                    $guestCancelCount = $this->incrementGuestCancelCount();
                    
                    Log::info('Total VNPay cancel count for guest', [
                        'session_id' => session()->getId(),
                        'guest_cancel_count' => $guestCancelCount,
                        'current_order_count' => $count
                    ]);

                    // Thông báo sau lần 2
                    if ($guestCancelCount == 2) {
                        session(['repayment_order_id' => $order->id]);
                        return redirect()->route('checkout.index')
                            ->with('error', 'Bạn đã hủy VNPay 2 lần. Vui lòng đổi phương thức khác để hoàn thành, không đơn hàng sẽ bị hủy.');
                    }

                    // Chặn hoàn toàn nếu >=3
                    if ($guestCancelCount >= 3) {
                        session()->forget('repayment_order_id');
                        return redirect()->route('checkout.fail')
                            ->with('error', 'Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ.');
                    }
                }

                session(['payment_cancelled_message' => 'Bạn đã hủy thanh toán. Vui lòng chọn lại phương thức.']);
                session(['repayment_order_id' => $order->id]);
                return redirect()->route('checkout.index');
            }

            // Debug log để kiểm tra số tiền
            Log::info('VNPay Return Debug', [
                'order_id' => $order->id,
                'expected_amount' => $order->vnp_amount_expected,
                'actual_amount' => $amountActual,
                'difference' => (int)$order->vnp_amount_expected - $amountActual,
                'order_final_total_before' => $order->final_total,
                'order_vnpay_discount_before' => $order->vnpay_discount ?? 0,
                'vnpay_promotion_code' => $vnp['vnp_PromotionCode'] ?? null,
                'vnpay_promotion_amount' => $vnp['vnp_PromotionAmount'] ?? null
            ]);

            // Đối chiếu số tiền (cho phép voucher VNPay làm giảm số tiền)
            if ($amountActual > (int)$order->vnp_amount_expected) {
                Log::warning('VNPay amount mismatch - actual > expected', [
                    'order_id' => $order->id,
                    'expected' => $order->vnp_amount_expected,
                    'actual' => $amountActual
                ]);
                $this->releaseStock($order);
                $this->restoreCartFromOrder($order);
                session(['payment_cancelled_message' => 'Số tiền không khớp. Vui lòng chọn lại phương thức.']);
                session(['repayment_order_id' => $order->id]);
                return redirect()->route('checkout.index');
            }

            // Xử lý voucher VNPay
            $discountAmount = 0;
            $oldFinalTotal = $order->final_total;

            // Kiểm tra nếu có voucher từ VNPay
            if (($vnp['vnp_PromotionAmount'] ?? 0) > 0) {
                $discountAmount = $vnp['vnp_PromotionAmount'];
                Log::info('VNPay promotion detected', [
                    'order_id' => $order->id,
                    'promotion_code' => $vnp['vnp_PromotionCode'] ?? 'N/A',
                    'promotion_amount' => $discountAmount
                ]);
            }
            // Hoặc nếu số tiền thực tế ít hơn (do voucher VNPay)
            elseif ($amountActual < (int)$order->vnp_amount_expected) {
                $discountAmount = (int)$order->vnp_amount_expected - $amountActual;
                Log::info('VNPay amount difference detected', [
                    'order_id' => $order->id,
                    'expected_amount' => $order->vnp_amount_expected,
                    'actual_amount' => $amountActual,
                    'calculated_discount' => $discountAmount
                ]);
            }
            // Nếu VNPay trả về số tiền dự kiến nhưng có voucher (cần kiểm tra thêm)
            elseif ($amountActual == (int)$order->vnp_amount_expected) {
                // Kiểm tra xem có thông tin voucher trong raw data không
                $rawData = $vnp['raw'] ?? [];
                $promotionAmount = 0;

                // Tìm kiếm các key có thể chứa thông tin voucher
                foreach ($rawData as $key => $value) {
                    if (
                        strpos(strtolower($key), 'promotion') !== false ||
                        strpos(strtolower($key), 'discount') !== false ||
                        strpos(strtolower($key), 'voucher') !== false
                    ) {
                        $promotionAmount = (int)$value;
                        break;
                    }
                }

                if ($promotionAmount > 0) {
                    $discountAmount = $promotionAmount;
                    Log::info('VNPay promotion found in raw data', [
                        'order_id' => $order->id,
                        'raw_data' => $rawData,
                        'promotion_amount' => $discountAmount
                    ]);
                }

                // Nếu không tìm thấy trong raw data, có thể VNPay đã áp dụng voucher nhưng không trả về thông tin
                // Trong trường hợp này, chúng ta có thể cần một cách khác để phát hiện voucher
                if ($promotionAmount == 0) {
                    Log::info('VNPay amount matches expected but no promotion data found', [
                        'order_id' => $order->id,
                        'raw_data' => $rawData,
                        'note' => 'VNPay may have applied voucher but not returned promotion info'
                    ]);

                    // Thử kiểm tra xem có thể có voucher được áp dụng không
                    // Dựa trên thông tin từ VNPay, có thể có voucher 100,000 VND
                    // Đây là một fallback mechanism
                    $possibleVoucherAmount = 10000000; // 100,000 VND (x100)
                    $expectedWithVoucher = $order->vnp_amount_expected - $possibleVoucherAmount;

                    if ($amountActual == $expectedWithVoucher) {
                        $discountAmount = $possibleVoucherAmount;
                        Log::info('VNPay voucher detected via fallback mechanism', [
                            'order_id' => $order->id,
                            'expected_with_voucher' => $expectedWithVoucher,
                            'actual_amount' => $amountActual,
                            'detected_voucher' => $discountAmount
                        ]);
                    }
                }
            }
            // Nếu VNPay trả về số tiền dự kiến (có thể đã áp dụng voucher nhưng không trả về thông tin)
            elseif ($amountActual == (int)$order->vnp_amount_expected) {
                Log::info('VNPay amount matches expected - checking for hidden voucher', [
                    'order_id' => $order->id,
                    'expected_amount' => $order->vnp_amount_expected,
                    'actual_amount' => $amountActual,
                    'note' => 'VNPay may have applied voucher but not returned the actual amount'
                ]);

                // Kiểm tra xem có thể có voucher được áp dụng không
                // Dựa trên thông tin từ VNPay, có thể có nhiều loại voucher
                $possibleVoucherAmounts = [
                    10000000,  // 100,000 VND (x100)
                    5000000,   // 50,000 VND (x100)
                    20000000,  // 200,000 VND (x100)
                    30000000,  // 300,000 VND (x100)
                    50000000,  // 500,000 VND (x100)
                    100000000, // 1,000,000 VND (x100)
                ];

                foreach ($possibleVoucherAmounts as $voucherAmount) {
                    $expectedWithVoucher = $order->vnp_amount_expected - $voucherAmount;

                    // Nếu số tiền thực tế khớp với số tiền sau khi áp dụng voucher
                    if ($amountActual == $expectedWithVoucher) {
                        $discountAmount = $voucherAmount;
                        Log::info('VNPay hidden voucher detected', [
                            'order_id' => $order->id,
                            'expected_with_voucher' => $expectedWithVoucher,
                            'actual_amount' => $amountActual,
                            'detected_voucher' => $discountAmount,
                            'voucher_amount_vnd' => $voucherAmount / 100
                        ]);
                        break;
                    }
                }
            }

            // Chỉ áp dụng voucher khi thực sự có thông tin voucher từ VNPay
            // Không tự động áp dụng voucher 100K nếu không có thông tin từ VNPay
            if ($discountAmount == 0 && $amountActual == (int)$order->vnp_amount_expected) {
                Log::info('VNPay amount matches expected - no voucher applied', [
                    'order_id' => $order->id,
                    'returned_amount' => $amountActual,
                    'expected_amount' => $order->vnp_amount_expected,
                    'note' => 'No voucher applied - amount matches expected'
                ]);
            }

            // Cập nhật đơn hàng nếu có voucher
            if ($discountAmount > 0) {
                // Tính toán final_total mới sau khi trừ voucher
                $newFinalTotal = ($amountActual - $discountAmount) / 100;

                // Sử dụng DB::update để đảm bảo cập nhật trực tiếp
                $saved = DB::table('orders')
                    ->where('id', $order->id)
                    ->update([
                        'vnpay_discount' => $discountAmount,
                        'final_total' => $newFinalTotal,
                        'updated_at' => now()
                    ]);

                // Refresh model để đảm bảo dữ liệu được cập nhật
                $order->refresh();

                Log::info('VNPay voucher applied', [
                    'order_id' => $order->id,
                    'expected_amount' => $order->vnp_amount_expected,
                    'actual_amount' => $amountActual,
                    'discount_amount' => $discountAmount,
                    'old_final_total' => $oldFinalTotal,
                    'new_final_total' => $order->final_total,
                    'save_success' => $saved,
                    'vnpay_discount_saved' => $order->vnpay_discount,
                    'vnpay_discount_after_refresh' => $order->vnpay_discount
                ]);
            } else {
                // Đảm bảo vnpay_discount = 0 khi không có voucher
                if (($order->vnpay_discount ?? 0) > 0) {
                    DB::table('orders')
                        ->where('id', $order->id)
                        ->update([
                            'vnpay_discount' => 0,
                            'updated_at' => now()
                        ]);
                    $order->refresh();

                    Log::info('VNPay discount reset to 0 - no voucher applied', [
                        'order_id' => $order->id,
                        'previous_vnpay_discount' => $order->vnpay_discount,
                        'note' => 'Reset vnpay_discount to 0 because no voucher was applied'
                    ]);
                }

                Log::info('VNPay amount matches expected - no voucher', [
                    'order_id' => $order->id,
                    'expected' => $order->vnp_amount_expected,
                    'actual' => $amountActual,
                    'promotion_amount' => $vnp['vnp_PromotionAmount'] ?? 0
                ]);

                // Thêm logic để kiểm tra xem có thể có voucher được áp dụng không
                // Dựa trên thông tin từ VNPay, có thể có voucher được áp dụng nhưng không trả về thông tin
                $rawData = $vnp['raw'] ?? [];
                $hasVoucherInfo = false;

                // Kiểm tra xem có thông tin voucher trong raw data không
                foreach ($rawData as $key => $value) {
                    if (
                        strpos(strtolower($key), 'promotion') !== false ||
                        strpos(strtolower($key), 'discount') !== false ||
                        strpos(strtolower($key), 'voucher') !== false ||
                        strpos(strtolower($key), 'coupon') !== false
                    ) {
                        $hasVoucherInfo = true;
                        Log::info('VNPay voucher info found in raw data', [
                            'order_id' => $order->id,
                            'key' => $key,
                            'value' => $value
                        ]);
                        break;
                    }
                }

                if (!$hasVoucherInfo) {
                    Log::info('VNPay no voucher info found in raw data', [
                        'order_id' => $order->id,
                        'raw_data_keys' => array_keys($rawData)
                    ]);
                }
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
                // Dọn dẹp session thanh toán lại
                session()->forget('repayment_order_id');
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
                    session(['repayment_order_id' => $order->id]);
                    return redirect()->route('checkout.index')
                        ->with('error', 'Bạn đã thất bại VNPay 2 lần. Vui lòng đổi phương thức khác để hoàn thành, không đơn hàng sẽ bị hủy.');
                }

                // Chặn hoàn toàn nếu >=3
                if ($totalCancelCount >= 3) {
                    session()->forget('repayment_order_id');
                    return redirect()->route('checkout.fail')
                        ->with('error', 'Bạn đã thất bại VNPay quá 3 lần. Vui lòng thử lại sau 2 phút.');
                }
            }

            session(['repayment_order_id' => $order->id]);
            return redirect()->route('checkout.index')->with('error', $msg);
        } catch (\Exception $e) {
            Log::error('VNPAY Return Error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // Dọn dẹp session khi có lỗi
            session()->forget('repayment_order_id');
            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng chọn lại phương thức.');
        }
    }

    /* ============================== Success page ============================== */
    public function success($orderId)
    {
        try {
            $order = Order::with([
                'orderItems.product.productAllImages',
                'orderItems.productVariant.attributeValues.attribute',
                'orderItems.product.variants'
            ])->where('id', $orderId)->first();

            if (!$order) {
                session()->forget('repayment_order_id');
                return redirect()->route('checkout.index')->with('error', 'Không tìm thấy đơn hàng');
            }
            if (Auth::check() && $order->user_id !== Auth::id()) {
                session()->forget('repayment_order_id');
                return redirect()->route('checkout.index')->with('error', 'Bạn không có quyền xem đơn hàng này');
            }
            if (!Auth::check() && session('last_order_id') != $orderId) {
                session()->forget('repayment_order_id');
                return redirect()->route('checkout.index')->with('error', 'Vui lòng đặt hàng để xem đơn này');
            }

            // Dọn dẹp session thanh toán lại khi hiển thị trang success
            session()->forget('repayment_order_id');

            // Debug log để kiểm tra dữ liệu order trong success page
            Log::info('Success page order data', [
                'order_id' => $order->id,
                'final_total' => $order->final_total,
                'vnpay_discount' => $order->vnpay_discount ?? 0,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'order_items_count' => $order->orderItems->count(),
                'order_items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'name_product' => $item->name_product,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'has_product' => $item->product ? 'yes' : 'no',
                        'has_variant' => $item->productVariant ? 'yes' : 'no'
                    ];
                })->toArray()
            ]);

            return view('client.checkouts.success', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error in checkout success: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'user_id'  => Auth::id(),
                'trace'    => $e->getTraceAsString()
            ]);

            // Dọn dẹp session khi có lỗi
            session()->forget('repayment_order_id');
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
                            'details' => [
                                'discount_type'      => $coupon->discount_type,
                                'value'              => $coupon->value,
                                'max_discount_amount' => $coupon->max_discount_amount,
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
                'items_count' => $order->orderItems->count(),
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
                // Kiểm tra tồn kho trước khi trừ
                $variant = DB::table('product_variants')->where('id', $item->variant_id)->first();
                if (!$variant || (int)$variant->stock < $qty) {
                    throw new \RuntimeException("Biến thể sản phẩm không đủ tồn kho. Hiện có: " . ($variant ? (int)$variant->stock : 0) . ", cần: {$qty}");
                }
                
                $affected = DB::table('product_variants')
                    ->where('id', $item->variant_id)
                    ->whereRaw('CAST(stock AS SIGNED) >= ?', [$qty])
                    ->decrement('stock', $qty);
                if (!$affected) throw new \RuntimeException('Biến thể sản phẩm không đủ tồn kho.');
            } else {
                // Kiểm tra tồn kho trước khi trừ
                $product = DB::table('products')->where('id', $item->product_id)->first();
                if (!$product || (int)$product->stock < $qty) {
                    throw new \RuntimeException("Sản phẩm không đủ tồn kho. Hiện có: " . ($product ? (int)$product->stock : 0) . ", cần: {$qty}");
                }
                
                $affected = DB::table('products')
                    ->where('id', $item->product_id)
                    ->whereRaw('CAST(stock AS SIGNED) >= ?', [$qty])
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