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
use Illuminate\Support\Str;

class ClientCheckoutController extends Controller
{
    public function __construct()
    {
        file_put_contents(storage_path('logs/debug.txt'), "Controller constructor called at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    }

    public function index()
    {
        file_put_contents(storage_path('logs/debug.txt'), "Checkout method called at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents(storage_path('logs/debug.txt'), "User logged in: " . (Auth::check() ? 'Yes' : 'No') . "\n", FILE_APPEND);
        if (Auth::check()) {
            file_put_contents(storage_path('logs/debug.txt'), "User ID: " . Auth::id() . "\n", FILE_APPEND);
        }
        
        error_log('DEBUG: Checkout index method called');
        
        $cartItems = [];
        $subtotal = 0;

        if (Auth::check()) {
            $cartItems = Cart::with(['product.productAllImages', 'product.variants', 'productVariant.attributeValues.attribute'])
                            ->where('user_id', Auth::id())
                            ->get();
            
            file_put_contents(storage_path('logs/debug.txt'), "DB Cart Items Count: " . $cartItems->count() . "\n", FILE_APPEND);
            
            foreach ($cartItems as $item) {
                // Get price from variant or product's first variant
                $price = 0;
                if ($item->productVariant) {
                    $price = $item->productVariant->price ?? 0;
                } elseif ($item->product->variants && $item->product->variants->count() > 0) {
                    $price = $item->product->variants->first()->price ?? 0;
                }
                $subtotal += $price * $item->quantity;
            }
        } else {
            $cart = session()->get('cart', []);
            file_put_contents(storage_path('logs/debug.txt'), "Session Cart: " . print_r($cart, true) . "\n", FILE_APPEND);
            
            foreach ($cart as $item) {
                file_put_contents(storage_path('logs/debug.txt'), "Processing session item: " . print_r($item, true) . "\n", FILE_APPEND);
                
                $product = Product::with(['productAllImages', 'variants'])->find($item['product_id']);
                if ($product) {
                    // Get price from session item or variant
                    $price = 0;
                    if (isset($item['price']) && $item['price'] > 0) {
                        // Use price from session (already calculated)
                        $price = $item['price'];
                        file_put_contents(storage_path('logs/debug.txt'), "Using session price: " . $price . "\n", FILE_APPEND);
                    } elseif (isset($item['variant_id']) && $item['variant_id']) {
                        $variant = \App\Models\ProductVariant::find($item['variant_id']);
                        $price = $variant ? $variant->price : 0;
                        file_put_contents(storage_path('logs/debug.txt'), "Using variant price: " . $price . "\n", FILE_APPEND);
                    } elseif ($product->variants && $product->variants->count() > 0) {
                        $price = $product->variants->first()->price ?? 0;
                        file_put_contents(storage_path('logs/debug.txt'), "Using first variant price: " . $price . "\n", FILE_APPEND);
                    }
                    
                    file_put_contents(storage_path('logs/debug.txt'), "Final price for item: " . $price . ", quantity: " . $item['quantity'] . "\n", FILE_APPEND);
                    
                    $cartItems[] = (object)[
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'productVariant' => isset($item['variant_id']) ? \App\Models\ProductVariant::find($item['variant_id']) : null,
                        'price' => $price
                    ];
                    $subtotal += $price * $item['quantity'];
                    
                    file_put_contents(storage_path('logs/debug.txt'), "Subtotal now: " . $subtotal . "\n", FILE_APPEND);
                }
            }
        }

        if (empty($cartItems)) {
            file_put_contents(storage_path('logs/debug.txt'), "No cart items found, redirecting to cart\n", FILE_APPEND);
            return redirect()->route('carts.index')->with('error', 'Giỏ hàng trống');
        }

        // Debug output
        file_put_contents(storage_path('logs/debug.txt'), "Cart Items Count: " . count($cartItems) . "\n", FILE_APPEND);
        file_put_contents(storage_path('logs/debug.txt'), "Calculated Subtotal: " . $subtotal . "\n", FILE_APPEND);
        
        foreach ($cartItems as $index => $item) {
            file_put_contents(storage_path('logs/debug.txt'), "Item $index: " . print_r([
                'product_name' => $item->product->name ?? 'No product',
                'quantity' => $item->quantity ?? 'No quantity',
                'has_variant' => isset($item->productVariant) ? 'Yes' : 'No',
                'variant_price' => isset($item->productVariant) ? $item->productVariant->price : 'N/A',
                'product_variants_count' => $item->product->variants ? $item->product->variants->count() : 0,
                'images_count' => $item->product->productAllImages ? $item->product->productAllImages->count() : 0
            ], true) . "\n", FILE_APPEND);
        }

        // Lấy địa chỉ người dùng
        $addresses = [];
        if (Auth::check()) {
            $addresses = UserAddress::where('user_id', Auth::id())
                                   ->orderBy('is_default', 'desc')
                                   ->get();
        }

        // Lấy phương thức vận chuyển
        $shippingMethods = ShippingMethod::all();

        return view('client.checkouts.index', compact(
            'cartItems',
            'subtotal',
            'addresses',
            'shippingMethods'
        ));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
                       ->where('status', 1)
                       ->where('start_date', '<=', now())
                       ->where('end_date', '>=', now())
                       ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'
            ]);
        }

        // Kiểm tra điều kiện áp dụng
        $subtotal = $request->subtotal;

        if ($subtotal < $coupon->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đủ điều kiện áp dụng mã giảm giá'
            ]);
        }

        // Tính toán giảm giá
        $discountAmount = 0;
        if ($coupon->discount_type === 'percent') {
            $discountAmount = ($subtotal * $coupon->value) / 100;
            if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                $discountAmount = $coupon->max_discount_amount;
            }
        } else {
            $discountAmount = $coupon->value;
        }

        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
            'coupon' => $coupon
        ]);
    }

    public function process(Request $request)
    {
        // Clear any previous logs
        file_put_contents(storage_path('logs/checkout_debug.log'), "=== CHECKOUT PROCESS START ===\n", LOCK_EX);
        file_put_contents(storage_path('logs/checkout_debug.log'), "Timestamp: " . now() . "\n", FILE_APPEND | LOCK_EX);
        file_put_contents(storage_path('logs/checkout_debug.log'), "Request data: " . json_encode($request->all()) . "\n", FILE_APPEND | LOCK_EX);
        file_put_contents(storage_path('logs/checkout_debug.log'), "User authenticated: " . (Auth::check() ? 'Yes (ID: ' . Auth::id() . ')' : 'No') . "\n", FILE_APPEND | LOCK_EX);
        
        Log::info('=== CHECKOUT PROCESS START ===');
        Log::info('Request data: ', $request->all());
        Log::info('User logged in: ' . (Auth::check() ? 'Yes (ID: ' . Auth::id() . ')' : 'No'));
        
        // Debug validation
        try {
            file_put_contents(storage_path('logs/checkout_debug.log'), "Starting validation...\n", FILE_APPEND | LOCK_EX);
            
            $validationRules = [
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:20',
                'recipient_address' => 'required|string|max:500',
                'payment_method' => 'required|in:cod,bank_transfer',
                'shipping_method_id' => 'nullable|integer',
                'coupon_code' => 'nullable|string'
            ];
            
            // Only require guest_email if user is not logged in
            if (!Auth::check()) {
                $validationRules['guest_email'] = 'required|email';
            }
            
            file_put_contents(storage_path('logs/checkout_debug.log'), "Validation rules: " . json_encode($validationRules) . "\n", FILE_APPEND | LOCK_EX);
            
            $request->validate($validationRules);
            
            file_put_contents(storage_path('logs/checkout_debug.log'), "Validation passed successfully\n", FILE_APPEND | LOCK_EX);
            Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            file_put_contents(storage_path('logs/checkout_debug.log'), "Validation failed: " . json_encode($e->errors()) . "\n", FILE_APPEND | LOCK_EX);
            Log::error('Validation failed:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();
        try {
            // Lấy giỏ hàng
            $cartItems = [];
            $subtotal = 0;

            if (Auth::check()) {
                $cartItems = Cart::with(['product', 'product.variants', 'productVariant'])
                                ->where('user_id', Auth::id())
                                ->get();
                
                Log::info('Found ' . $cartItems->count() . ' items in DB cart');
                
                foreach ($cartItems as $item) {
                    // Get price from variant
                    $price = 0;
                    if ($item->productVariant) {
                        $price = $item->productVariant->price ?? 0;
                    } elseif ($item->product->variants && $item->product->variants->count() > 0) {
                        $price = $item->product->variants->first()->price ?? 0;
                    }
                    $subtotal += $price * $item->quantity;
                }
            } else {
                $cart = session()->get('cart', []);
                Log::info('Found ' . count($cart) . ' items in session cart');
                
                foreach ($cart as $item) {
                    $product = Product::with('variants')->find($item['product_id']);
                    if ($product) {
                        // Get price from variant
                        $price = 0;
                        if (isset($item['variant_id']) && $item['variant_id']) {
                            $variant = \App\Models\ProductVariant::find($item['variant_id']);
                            $price = $variant ? $variant->price : 0;
                        } elseif ($product->variants && $product->variants->count() > 0) {
                            $price = $product->variants->first()->price ?? 0;
                        }
                        
                        $cartItems[] = (object)[
                            'product' => $product,
                            'quantity' => $item['quantity'],
                            'productVariant' => isset($item['variant_id']) ? \App\Models\ProductVariant::find($item['variant_id']) : null,
                            'price' => $price
                        ];
                        $subtotal += $price * $item['quantity'];
                    }
                }
            }

            if (empty($cartItems)) {
                Log::error('Cart is empty, redirecting');
                return redirect()->route('carts.index')->with('error', 'Giỏ hàng trống');
            }

            Log::info('Cart processing complete - Items: ' . count($cartItems) . ', Subtotal: ' . $subtotal);

            // Xử lý coupon
            $discountAmount = 0;
            $coupon = null;
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)
                               ->where('status', 1)
                               ->where('start_date', '<=', now())
                               ->where('end_date', '>=', now())
                               ->first();

                if ($coupon && $subtotal >= $coupon->min_order_value) {
                    if ($coupon->discount_type === 'percent') {
                        $discountAmount = ($subtotal * $coupon->value) / 100;
                        if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                            $discountAmount = $coupon->max_discount_amount;
                        }
                    } else {
                        $discountAmount = $coupon->value;
                    }
                }
            }

            // Phí vận chuyển - logic đơn giản
            $shippingFee = 30000; // Phí ship mặc định 30k
            
            // Logic: ID = 1 là lấy tại cửa hàng (0đ), khác là giao hàng (30k)
            if ($request->shipping_method_id == 1) {
                $shippingFee = 0; // Lấy tại cửa hàng
            }
            
            // Đảm bảo luôn là số nguyên
            $shippingFee = (int) $shippingFee;
            
            file_put_contents(storage_path('logs/checkout_debug.log'), "Shipping fee calculated: " . $shippingFee . "\n", FILE_APPEND | LOCK_EX);

            $totalAmount = (float) $subtotal + (float) $shippingFee;
            $finalTotal = $totalAmount - (float) $discountAmount;

            // Ensure all monetary values are floats and not null
            $subtotal = (float) $subtotal;
            $shippingFee = (int) $shippingFee;
            $discountAmount = (int) $discountAmount;
            $totalAmount = (int) $totalAmount;
            $finalTotal = (int) $finalTotal;

            file_put_contents(storage_path('logs/checkout_debug.log'), "Final calculations - Subtotal: $subtotal, Shipping: $shippingFee, Discount: $discountAmount, Final: $finalTotal\n", FILE_APPEND | LOCK_EX);
            Log::info('Order calculations - Subtotal: ' . $subtotal . ', Shipping: ' . $shippingFee . ', Discount: ' . $discountAmount . ', Final: ' . $finalTotal);

            // Tạo đơn hàng
            $orderData = [
                'user_id' => Auth::check() ? Auth::id() : null,
                'address_id' => null, // Set to null since we're not using saved addresses
                'guest_name' => Auth::check() ? null : $request->recipient_name,
                'guest_email' => Auth::check() ? null : $request->guest_email,
                'guest_phone' => Auth::check() ? null : $request->recipient_phone,
                'payment_method' => $request->payment_method ?? 'cod',
                'coupon_id' => $coupon ? $coupon->id : null,
                'coupon_code' => $request->coupon_code,
                'discount_amount' => $discountAmount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'final_total' => $finalTotal,
                'status' => 'pending',
                'payment_status' => 'pending',
                'recipient_name' => $request->recipient_name ?? 'Unknown',
                'recipient_phone' => $request->recipient_phone ?? 'Unknown',
                'recipient_address' => $request->recipient_address ?? 'Unknown',
                'shipping_method_id' => $request->shipping_method_id ?? 1
            ];

            Log::info('Creating order with data: ', $orderData);
            $order = Order::create($orderData);
            Log::info('Order created successfully with ID: ' . $order->id);

            // Tạo order items
            Log::info('Creating order items for ' . count($cartItems) . ' products');
            foreach ($cartItems as $item) {
                // Get price from variant or product
                $price = 0;
                $variant = null;
                
                if (isset($item->productVariant) && $item->productVariant) {
                    $variant = $item->productVariant;
                    $price = $variant->price ?? 0;
                } elseif (isset($item->price)) {
                    $price = $item->price; // For session cart items
                } elseif ($item->product->variants && $item->product->variants->count() > 0) {
                    $variant = $item->product->variants->first();
                    $price = $variant->price ?? 0;
                }

                // Nếu không có variant, tạo variant mặc định hoặc skip
                if (!$variant && $item->product->variants && $item->product->variants->count() > 0) {
                    $variant = $item->product->variants->first();
                }

                // Bỏ qua nếu không có variant (vì database yêu cầu variant_id)
                if (!$variant) {
                    Log::warning('Skipping product ' . $item->product->id . ' - no variant found');
                    continue;
                }

                // Get product image
                $productImage = '';
                if ($item->product->productAllImages && $item->product->productAllImages->count() > 0) {
                    $productImage = $item->product->productAllImages->first()->image_path ?? '';
                }

                $orderItemData = [
                    'order_id' => $order->id,
                    'variant_id' => $variant->id, // Dùng variant_id theo migration
                    'product_id' => $item->product->id,
                    'name_product' => $item->product->name ?? 'Unknown Product',
                    'image_product' => $productImage,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total_price' => $price * $item->quantity // Dùng total_price theo migration
                ];

                file_put_contents(storage_path('logs/checkout_debug.log'), "Creating order item: " . json_encode($orderItemData) . "\n", FILE_APPEND | LOCK_EX);
                OrderItem::create($orderItemData);
                Log::info('Created order item for product ID: ' . $item->product->id);
            }

            // Xóa giỏ hàng
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
                Log::info('Cleared DB cart for user: ' . Auth::id());
            } else {
                session()->forget('cart');
                Log::info('Cleared session cart');
            }

            DB::commit();
            Log::info('=== CHECKOUT PROCESS SUCCESS - Redirecting to success page ===');

            return redirect()->route('checkout.success', $order->id)
                           ->with('success', 'Đặt hàng thành công');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('=== CHECKOUT PROCESS FAILED ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with(['orderItems.product'])
                     ->where('id', $orderId);

        if (Auth::check()) {
            $order->where('user_id', Auth::id());
        }

        $order = $order->firstOrFail();

        return view('client.checkouts.success', compact('order'));
    }
}
