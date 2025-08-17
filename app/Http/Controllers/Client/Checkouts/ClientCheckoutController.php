<?php

namespace App\Http\Controllers\Client\Checkouts;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\Coupon;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function index()
    {
        file_put_contents(storage_path('logs/debug.txt'), "Checkout method called at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents(storage_path('logs/debug.txt'), "User logged in: " . (Auth::check() ? 'Yes' : 'No') . "\n", FILE_APPEND);
        if (Auth::check()) {
            file_put_contents(storage_path('logs/debug.txt'), "User ID: " . Auth::id() . "\n", FILE_APPEND);
        }

        // Helper build key ổn định
        $buildKey = function ($productId, $variantId = null) {
            return sprintf('%s:%s', (int) $productId, $variantId ? (int) $variantId : 0);
        };

        $cartItems = [];
        $subtotal = 0;

        // 1) Ưu tiên 'buynow' - chỉ hiển thị sản phẩm buynow, không hiển thị giỏ hàng
        $buynow = session('buynow');
        Log::info('Checkout index - buynow session', ['buynow' => $buynow]);
        
        if ($buynow) {
            $product = Product::with(['productAllImages', 'variants'])->find($buynow['product_id']);
            $variant = !empty($buynow['variant_id']) ? \App\Models\ProductVariant::find($buynow['variant_id']) : null;

            if ($product) {
                $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);
                $image = '';
                if ($product->productAllImages && $product->productAllImages->count() > 0) {
                    $image = 'uploads/products/' . $product->productAllImages->first()->image_path;
                }

                $cartItems[] = (object) [
                    'cart_item_id' => $buildKey($product->id, $variant?->id),
                    'product' => $product,
                    'productVariant' => $variant,
                    'quantity' => (int) $buynow['quantity'],
                    'price' => (float) $price,
                    // cho Blade
                    'product_name' => $product->name,
                    'image' => $image,
                ];
                $subtotal += $price * (int) $buynow['quantity'];
            }
            session()->forget('buynow');
        }
        // 2) User đăng nhập (bảng carts) - chỉ khi không có buynow
        elseif (Auth::check()) {
            $cartQuery = Cart::with([
                'product.productAllImages', 
                'product.variants', 
                'productVariant.attributeValues.attribute',
                'productVariant' // Thêm eager loading cho productVariant
            ])
            ->where('user_id', Auth::id());

            // Kiểm tra xem có selected items không
            $selectedParam = request()->get('selected');
            $isSelectedCheckout = !empty($selectedParam);
            
            if ($isSelectedCheckout) {
                // Kiểm tra xem có phải là buynow format (product_id:variant_id) không
                $isBuyNowFormat = strpos($selectedParam, ':') !== false;
                
                if ($isBuyNowFormat) {
                    // Đây là buynow format, chuyển thành buynow session
                    $parts = explode(':', $selectedParam);
                    $productId = $parts[0];
                    $variantId = $parts[1] != '0' ? $parts[1] : null;
                    
                    session(['buynow' => [
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'quantity' => 1 // Mặc định quantity = 1 cho buynow
                    ]]);
                    
                    // Redirect để reload page với buynow session
                    return redirect()->route('checkout.index');
                } else {
                    // Đây có thể là cart item IDs format hoặc product IDs format
                    $selectedIds = explode(',', $selectedParam);
                    
                    // Kiểm tra xem có phải là product IDs hay cart item IDs
                    // Thử tìm cart items theo ID trước (cart item IDs)
                    $cartItemsById = Cart::where('user_id', Auth::id())
                        ->whereIn('id', $selectedIds)
                        ->count();
                    
                    if ($cartItemsById > 0) {
                        // Nếu tìm thấy cart items theo ID, sử dụng cart item IDs
                        $cartQuery->whereIn('id', $selectedIds);
                        Log::info('Using cart item IDs for filtering', ['cart_item_ids' => $selectedIds]);
                    } else {
                        // Nếu không tìm thấy, thử tìm theo product_id
                        $cartQuery->whereIn('product_id', $selectedIds);
                        Log::info('Using product IDs for filtering', ['product_ids' => $selectedIds]);
                    }
                }
            }

            $cartItems = $cartQuery->get();

            foreach ($cartItems as $item) {
                $price = 0;
                if ($item->productVariant) {
                    $price = $item->productVariant->sale_price ?? $item->productVariant->price ?? 0;
                } elseif ($item->product && $item->product->variants && $item->product->variants->count() > 0) {
                    $pvar = $item->product->variants->first();
                    $price = $pvar->sale_price ?? $pvar->price ?? 0;
                } else {
                    $price = $item->product->sale_price ?? $item->product->price ?? 0;
                }

                // set các field dùng cho view
                $item->price = (float) $price;
                $item->cart_item_id = $buildKey($item->product?->id, $item->productVariant?->id);
                
                // Thêm các field cần thiết cho view
                $item->product_name = $item->product ? $item->product->name : 'Unknown Product';
                
                // Set image
                $image = '';
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
                // Kiểm tra xem có selected items không
                $selectedParam = request()->get('selected');
                $isSelectedCheckout = !empty($selectedParam);
                
                if ($isSelectedCheckout) {
                    // Kiểm tra xem có phải là buynow format (product_id:variant_id) không
                    $isBuyNowFormat = strpos($selectedParam, ':') !== false;
                    
                    if ($isBuyNowFormat) {
                        // Đây là buynow format, chuyển thành buynow session
                        $parts = explode(':', $selectedParam);
                        $productId = $parts[0];
                        $variantId = $parts[1] != '0' ? $parts[1] : null;
                        
                        session(['buynow' => [
                            'product_id' => $productId,
                            'variant_id' => $variantId,
                            'quantity' => 1 // Mặc định quantity = 1 cho buynow
                        ]]);
                        
                        // Redirect để reload page với buynow session
                        return redirect()->route('checkout.index');
                    } else {
                        // Đây là cart keys format (cho session cart)
                        $selectedKeys = explode(',', $selectedParam);
                        $filteredCart = [];
                        foreach ($selectedKeys as $key) {
                            if (isset($cart[$key])) {
                                $filteredCart[$key] = $cart[$key];
                            }
                        }
                        $cart = $filteredCart;
                    }
                }
                
                foreach ($cart as $ci) {
                    $product = Product::with(['productAllImages', 'variants'])->find($ci['product_id']);
                    if (!$product)
                        continue;

                    $variant = !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null;
                    $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);

                    $image = '';
                    if ($variant && $variant->image) {
                        $image = 'storage/' . $variant->image; // Thêm storage/ vào đường dẫn
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
                        // cho Blade
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

        // thông tin user/địa chỉ
        $addresses = [];
        $currentUser = null;
        $defaultAddress = null;

        if (Auth::check()) {
            $currentUser = Auth::user();
            $addresses = UserAddress::where('user_id', Auth::id())->orderBy('is_default', 'desc')->get();
            $defaultAddress = $addresses->first();
        }

        $shippingMethods = ShippingMethod::all();

        return view('client.checkouts.index', compact(
            'cartItems',
            'subtotal',
            'addresses',
            'shippingMethods',
            'currentUser',
            'defaultAddress'
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
        // Logs
        file_put_contents(storage_path('logs/checkout_debug.log'), "=== CHECKOUT PROCESS START ===\n", LOCK_EX);
        file_put_contents(storage_path('logs/checkout_debug.log'), "Timestamp: " . now() . "\n", FILE_APPEND | LOCK_EX);
        file_put_contents(storage_path('logs/checkout_debug.log'), "Request data: " . json_encode($request->all()) . "\n", FILE_APPEND | LOCK_EX);
        file_put_contents(storage_path('logs/checkout_debug.log'), "User authenticated: " . (Auth::check() ? 'Yes (ID: ' . Auth::id() . ')' : 'No') . "\n", FILE_APPEND | LOCK_EX);

        Log::info('=== CHECKOUT PROCESS START ===', ['user' => Auth::id()]);

        // Validate
        try {
            $rules = [
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:20|regex:/^0[3-9][0-9]{8}$/',
                'recipient_email' => 'required|email|max:255',
                'recipient_address' => 'required|string|max:500',
                'payment_method' => 'required|in:cod,bank_transfer',
                'shipping_method_id' => 'nullable|integer',
                'coupon_code' => 'nullable|string',
                'use_default_address' => 'sometimes|boolean',
            ];
            
            $messages = [
                'recipient_phone.regex' => 'Số điện thoại không đúng định dạng. Vui lòng nhập số điện thoại Việt Nam hợp lệ (VD: 0362729054)',
                'recipient_email.email' => 'Email không đúng định dạng. Vui lòng nhập email hợp lệ (VD: example@gmail.com)',
            ];
            
            $request->validate($rules, $messages);

            // Nếu đã đăng nhập và user tick "dùng địa chỉ mặc định" thì mới ghi đè địa chỉ
            if (Auth::check() && $request->boolean('use_default_address')) {
                $user = Auth::user();
                $defaultAddress = UserAddress::where('user_id', $user->id)->orderBy('is_default', 'desc')->first();
                if ($defaultAddress) {
                    $fullAddress = trim(collect([
                        $defaultAddress->address_line,
                        $defaultAddress->ward,
                        $defaultAddress->district,
                        $defaultAddress->city,
                    ])->filter()->implode(', '));
                    if ($fullAddress !== '') {
                        $request->merge(['recipient_address' => $fullAddress]);
                    }
                }
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            file_put_contents(storage_path('logs/checkout_debug.log'), "Validation failed: " . json_encode($e->errors()) . "\n", FILE_APPEND | LOCK_EX);
            Log::error('Validation failed', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();
        try {
            // Lấy cart & tính subtotal
            $cartItems = [];
            $subtotal = 0.0;

            // Kiểm tra xem có phải là checkout từ selected items không
            $isSelectedCheckout = $request->has('selected') && $request->get('selected');
            $selectedParam = $request->get('selected');
            
            // Kiểm tra xem có phải là buynow format (product_id:variant_id) không
            $isBuyNowFormat = $isSelectedCheckout && strpos($selectedParam, ':') !== false;
            
            if ($isBuyNowFormat) {
                // Đây là buynow format, chuyển thành buynow session
                $parts = explode(':', $selectedParam);
                $productId = $parts[0];
                $variantId = $parts[1] != '0' ? $parts[1] : null;
                
                session(['buynow' => [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => 1 // Mặc định quantity = 1 cho buynow
                ]]);
                
                Log::info('Converted selected to buynow session', [
                    'selected' => $selectedParam,
                    'product_id' => $productId,
                    'variant_id' => $variantId
                ]);
            }
            
            $selectedIds = ($isSelectedCheckout && !$isBuyNowFormat) ? explode(',', $selectedParam) : [];

            // Kiểm tra buynow session trước
            $buynow = session('buynow');
            Log::info('Buynow session check', ['buynow' => $buynow]);
            
            if ($buynow) {
                $product = Product::with(['productAllImages', 'variants'])->find($buynow['product_id']);
                $variant = !empty($buynow['variant_id']) ? \App\Models\ProductVariant::find($buynow['variant_id']) : null;

                Log::info('Buynow product found', [
                    'product_id' => $buynow['product_id'],
                    'product' => $product ? $product->name : 'null',
                    'variant_id' => $buynow['variant_id'] ?? 'null',
                    'variant' => $variant ? $variant->id : 'null'
                ]);

                if ($product) {
                    $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);
                    
                    $cartItems[] = (object) [
                        'product' => $product,
                        'productVariant' => $variant,
                        'quantity' => (int) $buynow['quantity'],
                        'price' => (float) $price,
                    ];
                    $subtotal += (float) $price * (int) $buynow['quantity'];
                    
                    Log::info('Buynow item added', [
                        'product_name' => $product->name,
                        'quantity' => $buynow['quantity'],
                        'price' => $price,
                        'subtotal' => $subtotal
                    ]);
                }
                session()->forget('buynow');
            } elseif (Auth::check()) {
                $cartQuery = Cart::with(['product', 'product.variants', 'productVariant'])
                    ->where('user_id', Auth::id());
                
                // Nếu có selected items, chỉ lấy những items được chọn
                if ($isSelectedCheckout && !empty($selectedIds)) {
                    $cartQuery->whereIn('id', $selectedIds);
                    Log::info('Selected checkout with IDs', ['selected_ids' => $selectedIds]);
                }
                
                $cartItems = $cartQuery->get();
                Log::info('Cart items found', ['count' => $cartItems->count(), 'items' => $cartItems->pluck('id')->toArray()]);

                foreach ($cartItems as $item) {
                    $price = 0.0;
                    if ($item->productVariant) {
                        $price = $item->productVariant->sale_price ?? $item->productVariant->price ?? 0;
                    } elseif ($item->product && $item->product->variants && $item->product->variants->count() > 0) {
                        $pv = $item->product->variants->first();
                        $price = $pv->sale_price ?? $pv->price ?? 0;
                    } else {
                        $price = $item->product ? ($item->product->sale_price ?? $item->product->price ?? 0) : 0;
                    }
                    
                    Log::info('Cart item processed', [
                        'cart_item_id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product ? $item->product->name : 'null',
                        'quantity' => $item->quantity,
                        'price' => $price,
                        'total' => $price * $item->quantity
                    ]);
                    
                    $subtotal += (float) $price * (int) $item->quantity;
                }
            } else {
                $cart = session()->get('cart', []);
                
                // Nếu có selected items, chỉ lấy những items được chọn
                if ($isSelectedCheckout && !empty($selectedIds)) {
                    foreach ($selectedIds as $selectedKey) {
                        if (isset($cart[$selectedKey])) {
                            $ci = $cart[$selectedKey];
                            $product = Product::with('variants')->find($ci['product_id']);
                            if (!$product) continue;

                            $price = 0.0;
                            if (!empty($ci['variant_id'])) {
                                $variant = \App\Models\ProductVariant::find($ci['variant_id']);
                                $price = $variant ? ($variant->sale_price ?? $variant->price) : 0;
                            } elseif ($product->variants && $product->variants->count() > 0) {
                                $pv = $product->variants->first();
                                $price = $pv->sale_price ?? $pv->price ?? 0;
                            }

                            $cartItems[] = (object) [
                                'product' => $product,
                                'quantity' => (int) $ci['quantity'],
                                'productVariant' => !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null,
                                'price' => (float) $price,
                            ];

                            $subtotal += (float) $price * (int) $ci['quantity'];
                        }
                    }
                } else {
                    // Lấy tất cả items trong session cart
                    foreach ($cart as $ci) {
                        $product = Product::with('variants')->find($ci['product_id']);
                        if (!$product) continue;

                        $price = 0.0;
                        if (!empty($ci['variant_id'])) {
                            $variant = \App\Models\ProductVariant::find($ci['variant_id']);
                            $price = $variant ? ($variant->sale_price ?? $variant->price) : 0;
                        } elseif ($product->variants && $product->variants->count() > 0) {
                            $pv = $product->variants->first();
                            $price = $pv->sale_price ?? $pv->price ?? 0;
                        }

                        $cartItems[] = (object) [
                            'product' => $product,
                            'quantity' => (int) $ci['quantity'],
                            'productVariant' => !empty($ci['variant_id']) ? \App\Models\ProductVariant::find($ci['variant_id']) : null,
                            'price' => (float) $price,
                        ];

                        $subtotal += (float) $price * (int) $ci['quantity'];
                    }
                }
            }

            if (empty($cartItems)) {
                Log::warning('Cart is empty - no items found for checkout');
                file_put_contents(storage_path('logs/checkout_debug.log'), "Cart is empty - no items found for checkout\n", FILE_APPEND | LOCK_EX);
                return redirect()->route('carts.index')->with('error', 'Không tìm thấy sản phẩm để thanh toán. Vui lòng kiểm tra lại giỏ hàng.');
            }

            Log::info('Cart items prepared for order', [
                'count' => count($cartItems),
                'subtotal' => $subtotal
            ]);
            file_put_contents(storage_path('logs/checkout_debug.log'), "Cart items prepared: " . count($cartItems) . " items, subtotal: $subtotal\n", FILE_APPEND | LOCK_EX);

            // Coupon
            $discountAmount = 0.0;
            $coupon = null;

            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('status', 1)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();

                if ($coupon && (!$coupon->min_order_value || $subtotal >= $coupon->min_order_value)) {
                    if ($coupon->discount_type === 'percent') {
                        $discountAmount = ($subtotal * $coupon->value) / 100;
                        if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                            $discountAmount = (float) $coupon->max_discount_amount;
                        }
                    } else {
                        $discountAmount = (float) $coupon->value;
                    }
                }
            }

            // ✅ Tính phí ship đúng theo UI:
            // 1 = Giao hàng tận nơi → miễn phí nếu subtotal >= 3,000,000, ngược lại 50,000
            // 2 = Nhận tại cửa hàng → 0
            $shippingMethodId = (int) ($request->shipping_method_id ?? 1);
            if ($shippingMethodId === 1) {
                $shippingFee = ($subtotal >= 3000000) ? 0.0 : 50000.0;
            } else {
                $shippingFee = 0.0;
            }

            // Tổng tiền
            $totalAmount = $subtotal + $shippingFee;           // trước giảm giá
            $finalTotal = $totalAmount - $discountAmount;     // sau giảm giá

            file_put_contents(
                storage_path('logs/checkout_debug.log'),
                "Final calculations - Subtotal: $subtotal, Shipping: $shippingFee, Discount: $discountAmount, Final: $finalTotal\n",
                FILE_APPEND | LOCK_EX
            );

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'address_id' => null,

                // Lưu cho khách vãng lai (giữ tương thích), đồng thời luôn có recipient_* cho mọi trường hợp
                'guest_name' => Auth::check() ? null : $request->recipient_name,
                'guest_email' => Auth::check() ? null : $request->recipient_email,
                'guest_phone' => Auth::check() ? null : $request->recipient_phone,

                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'recipient_email' => $request->recipient_email, // <-- LƯU EMAIL NGƯỜI NHẬN
                'recipient_address' => $request->recipient_address,

                'payment_method' => $request->payment_method ?? 'cod',
                'payment_status' => 'pending',

                'coupon_id' => $coupon ? $coupon->id : null,
                'coupon_code' => $request->coupon_code,
                'discount_amount' => (float) $discountAmount,

                'shipping_method_id' => $shippingMethodId,
                'shipping_fee' => (float) $shippingFee,   // ✅ lưu vào DB

                'total_amount' => (float) $totalAmount,
                'final_total' => (float) $finalTotal,

                'status' => 'pending',
            ]);

            Log::info('Order created', ['order_id' => $order->id]);
            Log::info('Cart items count: ' . count($cartItems));

            // Tạo order items
            foreach ($cartItems as $item) {
                Log::info('Processing item', [
                    'product_id' => $item->product->id ?? 'null',
                    'product_name' => $item->product->name ?? 'null',
                    'quantity' => $item->quantity ?? 0,
                    'price' => $item->price ?? 0
                ]);
                $price = 0.0;
                $variant = null;

                if (!empty($item->productVariant)) {
                    $variant = $item->productVariant;
                    $price = $variant->sale_price ?? $variant->price ?? 0;
                } elseif (isset($item->price)) {
                    $price = (float) $item->price;
                } elseif ($item->product->variants && $item->product->variants->count() > 0) {
                    $variant = $item->product->variants->first();
                    $price = $variant->sale_price ?? $variant->price ?? 0;
                }

                // Nếu không có variant, tạo một variant mặc định hoặc sử dụng null
                if (!$variant && $item->product->variants && $item->product->variants->count() > 0) {
                    $variant = $item->product->variants->first();
                }
                
                // Nếu vẫn không có variant, có thể sản phẩm không có variant (buynow case)
                // Trong trường hợp này, chúng ta vẫn tạo order item với variant_id = null

                $productImage = '';
                if ($variant && $variant->image) {
                    $productImage = $variant->image; // Lưu đường dẫn đầy đủ từ database
                } elseif ($item->product->thumbnail) {
                    $productImage = $item->product->thumbnail;
                } elseif ($item->product->productAllImages && $item->product->productAllImages->count() > 0) {
                    $productImage = $item->product->productAllImages->first()->image_path;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $variant ? $variant->id : null, // Có thể null nếu sản phẩm không có variant
                    'product_id' => $item->product->id,
                    'name_product' => $item->product->name ?? 'Unknown Product',
                    'image_product' => $productImage,
                    'quantity' => (int) $item->quantity,
                    'price' => (float) $price,
                    'total_price' => (float) $price * (int) $item->quantity,
                ]);
            }

            // Xoá giỏ hàng (chỉ xóa những sản phẩm đã được checkout)
            if (Auth::check()) {
                // Nếu có parameter selected, chỉ xóa những sản phẩm được chọn
                if ($request->has('selected') && $request->get('selected')) {
                    $selectedIds = explode(',', $request->get('selected'));
                    // Lọc ra những ID hợp lệ (chỉ số nguyên)
                    $validIds = array_filter($selectedIds, function($id) {
                        return is_numeric($id) && $id > 0;
                    });
                    
                    if (!empty($validIds)) {
                        Cart::where('user_id', Auth::id())
                            ->whereIn('id', $validIds)
                            ->delete();
                    }
                } else {
                    // Xóa toàn bộ giỏ hàng
                    Cart::where('user_id', Auth::id())->delete();
                }
            } else {
                // Xử lý session cart cho khách vãng lai
                if ($request->has('selected') && $request->get('selected')) {
                    // Nếu có selected items, chỉ xóa những items được chọn
                    $selectedKeys = explode(',', $request->get('selected'));
                    $cart = session()->get('cart', []);
                    foreach ($selectedKeys as $key) {
                        unset($cart[$key]);
                    }
                    session(['cart' => $cart]);
                } else {
                    // Xóa toàn bộ session cart
                    session()->forget('cart');
                }
            }

            DB::commit();
            Log::info('Checkout success', ['order_id' => $order->id]);

            return redirect()
                ->route('checkout.success', $order->id)
                ->with('success', 'Đặt hàng thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CHECKOUT FAILED: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $orderQ = Order::with(['orderItems.product'])
            ->where('id', $orderId);

        if (Auth::check()) {
            $orderQ->where('user_id', Auth::id());
        }

        $order = $orderQ->firstOrFail();

        return view('client.checkouts.success', compact('order'));
    }

    /**
     * Tính toán số tiền giảm giá từ coupon cho một đơn hàng
     * @param \App\Models\Coupon $coupon
     * @param float|int $subtotal
     * @return int
     */
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
}