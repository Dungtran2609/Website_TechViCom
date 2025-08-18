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
        ];
        return $map[$code] ?? ($code ?? '');
    }

    private function wardNameByCode(?string $code): string
    {
        return $code ?? '';
    }

    public function index(Request $request)
    {
        file_put_contents(storage_path('logs/debug.txt'), "Checkout method called at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents(storage_path('logs/debug.txt'), "User logged in: " . (Auth::check() ? 'Yes' : 'No') . "\n", FILE_APPEND);
        if (Auth::check()) {
            file_put_contents(storage_path('logs/debug.txt'), "User ID: " . Auth::id() . "\n", FILE_APPEND);
        }

        if ($request->has('clear_restored_coupon')) {
            session()->forget('restored_coupon');
        }

        $buildKey = function ($productId, $variantId = null) {
            return sprintf('%s:%s', (int) $productId, $variantId ? (int) $variantId : 0);
        };

        $cartItems = [];
        $subtotal = 0;
        $selectedParam = $request->get('selected');

        // Ưu tiên lấy theo ?selected=...
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

                    if ($item->productVariant?->image) {
                        $image = 'storage/' . $item->productVariant->image;
                    } elseif ($item->product?->thumbnail) {
                        $image = 'storage/' . $item->product->thumbnail;
                    } elseif ($item->product?->productAllImages?->count() > 0) {
                        $image = 'storage/' . $item->product->productAllImages->first()->image_path;
                    } else {
                        $image = 'client_css/images/placeholder.svg';
                    }

                    $item->price = (float) $price;
                    $item->image = $image;
                    $item->cart_item_id = $buildKey($item->product?->id, $item->productVariant?->id);
                    $item->product_name = $item->product?->name ?? 'Unknown Product';

                    $subtotal += (float) $price * (int) $item->quantity;
                    $cartItems[] = $item;
                }
            } else {
                // guest + ?selected=...
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
                        if (isset($cart[$key]))
                            $filtered[$key] = $cart[$key];
                    }
                    $cart = $filtered;
                }

                foreach ($cart as $ci) {
                    $product = Product::with(['productAllImages', 'variants'])->find($ci['product_id']);
                    if (!$product)
                        continue;
                    $variant = !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null;
                    $price = $variant ? ($variant->sale_price ?? $variant->price)
                        : ($product->sale_price ?? $product->price);

                    if ($variant?->image) {
                        $image = 'storage/' . $variant->image;
                    } elseif ($product->thumbnail) {
                        $image = 'storage/' . $product->thumbnail;
                    } elseif ($product->productAllImages?->count() > 0) {
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
        } elseif (session('buynow')) {
            // giữ nguyên như hiện tại của bạn...
            $buynow = session('buynow');
            $product = Product::with(['productAllImages', 'variants'])->find($buynow['product_id']);
            $variant = !empty($buynow['variant_id']) ? \App\Models\ProductVariant::find($buynow['variant_id']) : null;
            if ($product) {
                $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);
                if ($variant?->image) {
                    $image = 'storage/' . $variant->image;
                } elseif ($product->thumbnail) {
                    $image = 'storage/' . $product->thumbnail;
                } elseif ($product->productAllImages?->count() > 0) {
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
        } else {
            // ====== BUG FIX: NHÁNH MẶC ĐỊNH ======
            if (Auth::check()) {
                // -> LẤY GIỎ TỪ DB CHO USER ĐÃ ĐĂNG NHẬP
                $dbCartItems = Cart::with([
                    'product.productAllImages',
                    'product.variants',
                    'productVariant'
                ])->where('user_id', Auth::id())->get();

                foreach ($dbCartItems as $ci) {
                    if ($ci->productVariant) {
                        $price = $ci->productVariant->sale_price ?? $ci->productVariant->price ?? 0;
                    } elseif ($ci->product && $ci->product->variants?->count() > 0) {
                        $pvar = $ci->product->variants->first();
                        $price = $pvar->sale_price ?? $pvar->price ?? 0;
                    } else {
                        $price = $ci->product->sale_price ?? $ci->product->price ?? 0;
                    }

                    if ($ci->productVariant?->image) {
                        $image = 'storage/' . $ci->productVariant->image;
                    } elseif ($ci->product?->thumbnail) {
                        $image = 'storage/' . $ci->product->thumbnail;
                    } elseif ($ci->product?->productAllImages?->count() > 0) {
                        $image = 'storage/' . $ci->product->productAllImages->first()->image_path;
                    } else {
                        $image = 'client_css/images/placeholder.svg';
                    }

                    $cartItems[] = (object) [
                        'cart_item_id' => $buildKey($ci->product?->id, $ci->productVariant?->id),
                        'product' => $ci->product,
                        'productVariant' => $ci->productVariant,
                        'quantity' => (int) $ci->quantity,
                        'price' => (float) $price,
                        'product_name' => $ci->product?->name ?? 'Unknown Product',
                        'image' => $image,
                    ];
                    $subtotal += (float) $price * (int) $ci->quantity;
                }
            } else {
                // -> GUEST: LẤY GIỎ TỪ SESSION
                $cart = session()->get('cart', []);
                foreach ($cart as $ci) {
                    $product = Product::with(['productAllImages', 'variants'])->find($ci['product_id']);
                    if (!$product)
                        continue;

                    $variant = !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null;
                    $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                    if ($variant?->image) {
                        $image = 'storage/' . $variant->image;
                    } elseif ($product->thumbnail) {
                        $image = 'storage/' . $product->thumbnail;
                    } elseif ($product->productAllImages?->count() > 0) {
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

        // Xử lý áp dụng mã giảm giá nếu có (không dùng API)
        $appliedCoupon = null;
        $discountAmount = 0;
        $couponMessage = null;
        if ($request->filled('coupon_code')) {
            $couponCode = $request->input('coupon_code');
            $coupon = Coupon::where('code', $couponCode)
                ->where('status', 1)
                ->where(function($q){
                    $q->whereNull('deleted_at');
                })
                ->where(function($q){
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function($q){
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->first();
            if ($coupon) {
                // Kiểm tra điều kiện đơn hàng
                if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                    $couponMessage = 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value) . '₫';
                } elseif ($coupon->max_order_value && $subtotal > $coupon->max_order_value) {
                    $couponMessage = 'Đơn hàng vượt quá giá trị tối đa ' . number_format($coupon->max_order_value) . '₫';
                } else {
                    // Tính số tiền giảm
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
        ));
    }


    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
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

        $subtotal = $request->subtotal;
        $discountAmount = $this->calculateCouponDiscount($coupon, $subtotal);

        if ($discountAmount <= 0) {
            // Lý do có thể: chưa đạt min, vượt max, hết hạn, v.v.
            $msg = 'Đơn hàng chưa đủ điều kiện áp dụng mã giảm giá';
            if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                $msg = 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value) . '₫';
            } elseif ($coupon->max_order_value && $subtotal > $coupon->max_order_value) {
                $msg = 'Đơn hàng vượt quá giá trị tối đa ' . number_format($coupon->max_order_value) . '₫';
            }
            return response()->json([
                'success' => false,
                'message' => $msg
            ]);
        }

        $discountAmount = 0.0;
        if ($coupon->discount_type === 'percent') {
            $discountAmount = ($subtotal * $coupon->value) / 100;
            if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                $discountAmount = (float) $coupon->max_discount_amount;
            }
        } else {
            $discountAmount = (float) $coupon->value;
        }

        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
            'coupon' => $coupon,
        ]);
    }

    public function process(Request $request)
    {
        try {
            Log::info('Checkout process started', [
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'user_id' => Auth::id(),
                'selected_address' => $request->selected_address ?? null
            ]);

            // ===== Địa chỉ nhận hàng =====
            $addressData = null;
            if ($request->has('selected_address') && $request->selected_address !== 'new') {
                $address = UserAddress::where('user_id', Auth::id())->where('id', $request->selected_address)->first();
                if (!$address) {
                    return redirect()->route('checkout.index')->with('error', 'Địa chỉ không hợp lệ');
                }
                $addressData = [
                    'recipient_name' => $address->recipient_name ?? (Auth::user()->name ?? ''),
                    'recipient_phone' => $address->phone ?? (Auth::user()->phone_number ?? ''),
                    'recipient_email' => Auth::user()->email ?? '',
                    'recipient_address' => $address->address_line . ', ' . $address->ward . ', ' . $address->district . ', ' . $address->city,
                    'province_code' => '01',
                    'district_code' => $address->district_code ?? '',
                    'ward_code' => $address->ward_code ?? '',
                ];
            }

            if ($addressData) {
                $request->validate([
                    'payment_method' => 'required|in:cod,bank_transfer',
                    'shipping_method_id' => 'required|exists:shipping_methods,id',
                    'order_notes' => 'nullable|string',
                ]);
            } else {
                $request->validate([
                    'recipient_name' => 'required|string|max:255',
                    'recipient_phone' => 'required|string|max:20',
                    'recipient_email' => 'required|email',
                    'recipient_address' => 'required|string|max:255',
                    'province_code' => 'required|in:01',
                    'district_code' => 'required|string',
                    'ward_code' => 'required|string',
                    'payment_method' => 'required|in:cod,bank_transfer',
                    'shipping_method_id' => 'required|exists:shipping_methods,id',
                    'order_notes' => 'nullable|string',
                ]);
                $addressData = [
                    'recipient_name' => $request->recipient_name,
                    'recipient_phone' => $request->recipient_phone,
                    'recipient_email' => $request->recipient_email,
                    'recipient_address' => $request->recipient_address,
                    'province_code' => $request->province_code,
                    'district_code' => $request->district_code,
                    'ward_code' => $request->ward_code,
                ];
            }

            // ===== BUYNOW → SESSION → DB =====
            $cartItems = collect();
            $source = null;
            $selectedIds = $request->input('selected');
            $selectedIdsArr = [];
            if ($selectedIds) {
                $selectedIdsArr = array_filter(explode(',', $selectedIds));
            }

            $buynow = session('buynow');
            if ($buynow) {
                $product = Product::with(['productAllImages', 'variants'])->find($buynow['product_id']);
                if (!$product)
                    return redirect()->route('checkout.index')->with('error', 'Sản phẩm không tồn tại');
                $variant = !empty($buynow['variant_id']) ? \App\Models\ProductVariant::find($buynow['variant_id']) : null;
                $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                $cartItems->push((object) [
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'product' => $product,
                    'productVariant' => $variant,
                    'quantity' => (int) ($buynow['quantity'] ?? 1),
                    'price' => (float) $price,
                    'image' => $variant?->image ? 'storage/' . $variant->image
                        : ($product->thumbnail ? 'storage/' . $product->thumbnail
                            : (($product->productAllImages?->count() > 0)
                                ? 'storage/' . $product->productAllImages->first()->image_path
                                : 'client_css/images/placeholder.svg')),
                ]);
                $source = 'buynow';
            } else {
                if (!Auth::check()) {
                    $sessionCart = session()->get('cart', []);
                    if (empty($sessionCart))
                        return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');

                    // Nếu có selected, chỉ lấy các key được chọn
                    if (!empty($selectedIdsArr)) {
                        $filtered = [];
                        foreach ($selectedIdsArr as $key) {
                            if (isset($sessionCart[$key])) {
                                $filtered[$key] = $sessionCart[$key];
                            }
                        }
                        $sessionCart = $filtered;
                    }

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
                            'image' => $variant?->image ? 'storage/' . $variant->image
                                : ($product->thumbnail ? 'storage/' . $product->thumbnail
                                    : (($product->productAllImages?->count() > 0)
                                        ? 'storage/' . $product->productAllImages->first()->image_path
                                        : 'client_css/images/placeholder.svg')),
                        ]);
                    }
                    if ($cartItems->isEmpty())
                        return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');
                    $source = 'session';
                } else {
                    // User đăng nhập
                    if (!empty($selectedIdsArr)) {
                        $dbCartItems = Cart::with(['product.productAllImages', 'product.variants', 'productVariant'])
                            ->where('user_id', Auth::id())
                            ->whereIn('id', $selectedIdsArr)
                            ->get();
                    } else {
                        $dbCartItems = Cart::with(['product.productAllImages', 'product.variants', 'productVariant'])
                            ->where('user_id', Auth::id())
                            ->get();
                    }
                    if ($dbCartItems->isEmpty())
                        return redirect()->route('checkout.index')->with('error', 'Giỏ hàng trống');

                    foreach ($dbCartItems as $ci) {
                        if ($ci->productVariant) {
                            $price = $ci->productVariant->sale_price ?? $ci->productVariant->price ?? 0;
                        } elseif ($ci->product && $ci->product->variants?->count() > 0) {
                            $pvar = $ci->product->variants->first();
                            $price = $pvar->sale_price ?? $pvar->price ?? 0;
                        } else {
                            $price = $ci->product->sale_price ?? $ci->product->price ?? 0;
                        }

                        if ($ci->productVariant?->image) {
                            $image = 'storage/' . $ci->productVariant->image;
                        } elseif ($ci->product?->thumbnail) {
                            $image = 'storage/' . $ci->product->thumbnail;
                        } elseif ($ci->product?->productAllImages?->count() > 0) {
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

            // ===== PHÍ SHIP =====
            $subtotal = $cartItems->sum(fn($i) => ((float) $i->price) * ((int) $i->quantity));
            $shippingMethodId = $request->shipping_method_id;
            $shippingFee = 0;
            if ($shippingMethodId == 1) { // home_delivery
                $shippingFee = ($subtotal >= 3000000) ? 0 : 50000;
            } else {
                $shippingFee = 0;
            }

            // ===== COUPON =====
            $discountAmount = 0;
            $couponCode = null;
            if (!empty($request->coupon_code)) {
                $couponCode = $request->coupon_code;
                $coupon = Coupon::where('code', $couponCode)->where('status', true)->whereNull('deleted_at')->first();
                if ($coupon && $coupon->max_usage_per_user > 0 && Auth::check()) {
                    $usedCount = \App\Models\Order::where('user_id', Auth::id())
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

            // Map CODE -> NAME (HN)
            $provinceCode = $addressData['province_code'] ?? '01';
            $districtCode = $addressData['district_code'] ?? '';
            $wardCode = $addressData['ward_code'] ?? '';
            $provinceName = $this->provinceNameByCode($provinceCode);
            $districtName = $this->districtNameByCode($districtCode);
            $wardName = $this->wardNameByCode($wardCode);

            // ===== Tạo đơn hàng =====
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . time() . '-' . (Auth::id() ?? 'guest'),

                'recipient_name' => $addressData['recipient_name'],
                'recipient_phone' => $addressData['recipient_phone'],
                'recipient_email' => $addressData['recipient_email'],
                'recipient_address' => $addressData['recipient_address'],

                'province_code' => $provinceCode,
                'district_code' => $districtCode,
                'ward_code' => $wardCode,
                'city' => $provinceName,
                'district' => $districtName,
                'ward' => $wardName,

                'payment_method' => $request->payment_method,
                'shipping_method_id' => $shippingMethodId,
                'order_notes' => $request->order_notes,
                'total_amount' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => $discountAmount,
                'coupon_code' => $couponCode,
                'final_total' => $finalTotal,
                'status' => 'pending',
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'processing',
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

            // Dọn dữ liệu giỏ
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
                        )
                        ->delete();
                }
            }

            // ===== Thanh toán =====
            if ($request->payment_method === 'cod') {
                session(['last_order_id' => $order->id]);
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ sớm nhất.');
            } else {
                // TxnRef + Amount (*100) — thêm 4 số ngẫu nhiên để không trùng
                $txnRef = sprintf('VNP-%s-%s-%04d', $order->id, now()->format('YmdHis'), random_int(0, 9999));
                $amountExpected = (int) round($order->final_total * 100);

                $order->update([
                    'payment_status' => 'processing',
                    'vnp_txn_ref' => $txnRef,
                    'vnp_amount_expected' => $amountExpected,
                ]);

                $vnpayService = new VNPayService();
                $paymentUrl = $vnpayService->createPaymentUrl($order, $request, [
                    'txn_ref' => $txnRef,
                    'amount' => $amountExpected,
                ]);

                $order->update(['vnpay_url' => $paymentUrl]);

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
                return redirect()->route('checkout.success', $order->id)->with('success', 'Đơn hàng đã được thanh toán');
            }

            // TxnRef mới mỗi lần bấm thanh toán
            $txnRef = sprintf('VNP-%s-%s-%04d', $order->id, now()->format('YmdHis'), random_int(0, 9999));
            $amountExpected = (int) ($order->vnp_amount_expected ?: round($order->final_total * 100));

            $order->forceFill([
                'payment_status' => 'processing',
                'vnp_txn_ref' => $txnRef,
                'vnp_amount_expected' => $amountExpected,
            ]);

            $vnpayService = new VNPayService();
            $paymentUrl = $vnpayService->createPaymentUrl($order, request(), [
                'txn_ref' => $txnRef,
                'amount' => $amountExpected,
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
    public function vnpay_return(Request $request)
    {
        try {
            $svc = new VNPayService();
            $vnp = $svc->processReturn($request); // các key ở cấp cao nhất

            // 1) Sai chữ ký => về checkout
            if (empty($vnp['is_valid'])) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Chữ ký không hợp lệ. Vui lòng chọn lại phương thức thanh toán.');
            }

            // 2) LẤY ĐÚNG KEY Ở CẤP CAO NHẤT
            $txnRef = $vnp['vnp_TxnRef'] ?? null;
            $respCode = $vnp['vnp_ResponseCode'] ?? null;
            $amountActual = (int) ($vnp['vnp_Amount'] ?? 0); // VNPay trả *100

            if (!$txnRef) {
                return redirect()->route('checkout.index')->with('error', 'Thiếu mã giao dịch.');
            }

            $order = Order::where('vnp_txn_ref', $txnRef)->first();
            if (!$order) {
                return redirect()->route('checkout.index')->with('error', 'Không tìm thấy đơn hàng.');
            }

            // 3) Người dùng HỦY (24) -> khôi phục giỏ + quay lại checkout
            if ($respCode === '24') {
                $this->restoreCartFromOrder($order);
                $order->forceFill([
                    'payment_status' => 'cancelled',
                    'status' => 'cancelled',
                ])->save();

                session(['payment_cancelled_message' => 'Bạn đã hủy thanh toán. Vui lòng chọn lại phương thức.']);
                return redirect()->route('checkout.index');
            }

            // 4) Đối chiếu số tiền
            if ((int) $order->vnp_amount_expected !== $amountActual) {
                $this->restoreCartFromOrder($order);
                session(['payment_cancelled_message' => 'Số tiền không khớp. Vui lòng chọn lại phương thức.']);
                return redirect()->route('checkout.index');
            }

            // 5) Tránh xử lý lặp
            if (!in_array($order->payment_status, ['pending', 'processing'])) {
                session(['last_order_id' => $order->id]);
                return redirect()->route('checkout.success', $order->id);
            }

            // 6) Cập nhật trạng thái theo kết quả trả về
            $result = $svc->updateOrderStatus($order, $vnp);

            if (!empty($result['success'])) {
                session(['last_order_id' => $order->id]); // cho guest xem trang success
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', $result['message'] ?? 'Thanh toán thành công');
            }

            // Thất bại khác -> khôi phục giỏ + về checkout
            $this->restoreCartFromOrder($order);
            $msg = $result['message'] ?? 'Thanh toán thất bại. Vui lòng chọn lại phương thức.';
            session(['payment_cancelled_message' => $msg]);
            return redirect()->route('checkout.index')->with('error', $msg);

        } catch (\Exception $e) {
            Log::error('VNPAY Return Error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng chọn lại phương thức.');
        }
    }


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
                'user_id' => Auth::id(),
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
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
                foreach ($order->orderItems as $item) {
                    Cart::create([
                        'user_id' => Auth::id(),
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
                $coupon = Coupon::where('code', $order->coupon_code)->where('status', true)->whereNull('deleted_at')->first();
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
                'user_id' => Auth::id(),
                'items_count' => $order->orderItems->count(),
                'has_coupon' => !empty($order->coupon_code)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to restore cart from order: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'user_id' => Auth::id()
            ]);
        }
    }

    protected function calculateCouponDiscount($coupon, $subtotal)
    {
        if (!$coupon || !$coupon->status) return 0;
        // Kiểm tra ngày hiệu lực
        $now = now();
        if ($coupon->start_date && $now->lt($coupon->start_date)) return 0;
        if ($coupon->end_date && $now->gt($coupon->end_date)) return 0;
        // Kiểm tra min/max đơn hàng
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
        // Không vượt quá tổng đơn hàng
        $discount = min($discount, $subtotal);
        return (int) $discount;
    }

    // public function applyCoupon(Request $request)
    // {
    //     try {
    //         $couponCode = $request->input('coupon_code');
    //         $subtotal = $request->input('subtotal', 0);

    //         $coupon = Coupon::where('code', $couponCode)->where('status', true)->whereNull('deleted_at')->first();
    //         if (!$coupon) {
    //             return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã bị vô hiệu hóa']);
    //         }

    //         $now = Carbon::now();
    //         if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
    //             return response()->json(['success' => false, 'message' => 'Mã giảm giá chưa có hiệu lực']);
    //         }
    //         if ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
    //             return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn']);
    //         }
    //         if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
    //             return response()->json(['success' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value) . '₫']);
    //         }
    //         if ($coupon->max_order_value && $subtotal > $coupon->max_order_value) {
    //             return response()->json(['success' => false, 'message' => 'Đơn hàng vượt quá giá trị tối đa ' . number_format($coupon->max_order_value) . '₫']);
    //         }

    //         $discountAmount = 0;
    //         if ($coupon->discount_type === 'percent') {
    //             $discountAmount = $subtotal * ($coupon->value / 100);
    //             if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
    //                 $discountAmount = $coupon->max_discount_amount;
    //             }
    //         } else {
    //             $discountAmount = $coupon->value;
    //         }

    //         $discountAmount = min($discountAmount, $subtotal);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Mã giảm giá hợp lệ',
    //             'discount_amount' => $discountAmount,
    //             'coupon' => [
    //                 'code' => $coupon->code,
    //                 'discount_type' => $coupon->discount_type,
    //                 'value' => $coupon->value,
    //                 'max_discount_amount' => $coupon->max_discount_amount,
    //                 'min_order_value' => $coupon->min_order_value,
    //                 'max_order_value' => $coupon->max_order_value
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xử lý mã giảm giá']);
    //     }
    // }
}
