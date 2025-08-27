<?php

namespace App\Http\Controllers\Client\Carts;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientCartController extends Controller
{

    public function index(Request $request)
    {
        // Chặn admin không cho mua hàng
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Admin không thể mua hàng. Vui lòng sử dụng tài khoản khách hàng.');
        }
        
        if (Auth::check()) {
            $cartItems = Cart::with(['product.productAllImages', 'productVariant.attributeValues.attribute'])
                ->where('user_id', Auth::id())
                ->get();
        } else {
            // Giỏ hàng session cho guest
            $sessionCart = session()->get('cart', []);
            $cartItems = [];

            foreach ($sessionCart as $key => $item) {
                $product = Product::with(['productAllImages', 'variants.attributeValues.attribute'])
                    ->find($item['product_id']);

                if ($product) {
                    $cartItem = (object) [
                        'id' => $key,
                        'product' => $product,
                        'product_id' => $item['product_id'],
                        'variant_id' => $item['variant_id'],
                        'quantity' => $item['quantity'],
                        'productVariant' => $item['variant_id'] ? ProductVariant::with('attributeValues.attribute')->find($item['variant_id']) : null
                    ];
                    $cartItems[] = $cartItem;
                }
            }
        }

        // If AJAX request, return JSON
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            $items = [];
            $total = 0;
            foreach ($cartItems as $key => $cartItem) {
                $product = is_object($cartItem) ? $cartItem->product : $cartItem['product'];
                $quantity = is_object($cartItem) ? $cartItem->quantity : $cartItem['quantity'];
                $price = 0;
                // Nếu có variant cụ thể
                if (is_object($cartItem) && isset($cartItem->productVariant) && $cartItem->productVariant) {
                    $price = $cartItem->productVariant->sale_price ?? $cartItem->productVariant->price ?? 0;
                }
                // Nếu là session cart và có variant_id
                elseif (!is_object($cartItem) && isset($cartItem['variant_id']) && $cartItem['variant_id']) {
                    $variant = \App\Models\ProductVariant::find($cartItem['variant_id']);
                    $price = $variant ? ($variant->sale_price ?? $variant->price) : 0;
                }
                // Nếu có variant đầu tiên của product
                elseif ($product->variants && $product->variants->count() > 0) {
                    $variant = $product->variants->first();
                    $price = $variant->sale_price ?? $variant->price ?? 0;
                }
                $total += $price * $quantity;
                // Lấy đúng trường ảnh - ưu tiên hình ảnh biến thể
                $image = asset('client_css/images/placeholder.svg');
                
                // 1. Ưu tiên hình ảnh của biến thể cụ thể
                if (is_object($cartItem) && isset($cartItem->productVariant) && $cartItem->productVariant && $cartItem->productVariant->image) {
                    $image = asset('storage/' . ltrim($cartItem->productVariant->image, '/'));
                }
                // 2. Nếu là session cart và có variant_id
                elseif (!is_object($cartItem) && isset($cartItem['variant_id']) && $cartItem['variant_id']) {
                    $variant = \App\Models\ProductVariant::find($cartItem['variant_id']);
                    if ($variant && $variant->image) {
                        $image = asset('storage/' . ltrim($variant->image, '/'));
                    }
                }
                // 3. Nếu sản phẩm có biến thể thì lấy ảnh biến thể đầu tiên
                elseif ($product->variants && $product->variants->count() > 0) {
                    $variant = $product->variants->first();
                    if ($variant && $variant->image) {
                        $image = asset('storage/' . ltrim($variant->image, '/'));
                    }
                }
                // 4. Fallback sang thumbnail của sản phẩm
                elseif ($product->thumbnail) {
                    $image = asset('storage/' . ltrim($product->thumbnail, '/'));
                }
                // 5. Fallback sang ảnh đầu tiên của sản phẩm
                elseif ($product->productAllImages && $product->productAllImages->count() > 0) {
                    $imgObj = $product->productAllImages->first();
                    $imgField = $imgObj->image_path ?? $imgObj->image_url ?? $imgObj->image ?? null;
                    if ($imgField) {
                        $image = asset('storage/' . ltrim($imgField, '/'));
                    }
                }
                $items[] = [
                    'id' => is_object($cartItem) ? $cartItem->id : $key, // Use key for session cart
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'image' => $image,
                    'type' => $product->type,
                    'thumbnail' => $product->type === 'simple' && $product->thumbnail ? ltrim($product->thumbnail, '/') : null,
                    'variant' => is_object($cartItem) ? $cartItem->productVariant : null,
                    'stock' => isset($cartItem->productVariant) && $cartItem->productVariant ? $cartItem->productVariant->stock : ($product->stock ?? 0),
                ];
            }
            return response()->json([
                'success' => true,
                'items' => $items,
                'total' => $total,
                'count' => count($items)
            ]);
        }

        $cartSubtotal = 0;
        foreach ($cartItems as $item) {
            $cartSubtotal += ($item->productVariant?->sale_price ?? $item->productVariant?->price ?? $item->price ?? 0) * $item->quantity;
        }
        $availableCoupons = \App\Models\Coupon::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();
        // Đánh dấu coupon áp dụng được
        foreach ($availableCoupons as $coupon) {
            $coupon->can_apply = true;
            if ($coupon->min_order_value && $cartSubtotal < $coupon->min_order_value) {
                $coupon->can_apply = false;
            }
            // Có thể bổ sung kiểm tra theo sản phẩm/danh mục nếu cần
        }
        return view('client.carts.index', compact('cartItems', 'availableCoupons'));
    }

    public function add(Request $request)
    {
        // Chặn admin và staff không được thêm vào giỏ hàng
        if (Auth::check()) {
            $user = Auth::user();
            $userRoles = $user->roles->pluck('name')->toArray();
            $blockedRoles = ['admin', 'staff', 'employee', 'manager'];
            
            if (array_intersect($userRoles, $blockedRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản Admin/Staff không được phép mua hàng!'
                ], 403);
            }
        }

        try {
            $data = $request->validate([
                'product_id' => ['required', 'integer', 'exists:products,id'],
                'quantity' => ['nullable', 'integer', 'min:1'],
                'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            ]);

            $productId = (int) $data['product_id'];
            $quantity = (int) ($data['quantity'] ?? 1);
            $variantId = $data['variant_id'] ?? null;

            $product = \App\Models\Product::with('variants:id,product_id,price,sale_price,stock')
                ->findOrFail($productId);

            // Nếu SP có biến thể mà chưa chọn -> chặn
            if ($product->variants->count() > 0 && !$variantId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn phân loại sản phẩm'
                ], 422);
            }

            // Tồn kho
            $stock = $variantId
                ? (int) \App\Models\ProductVariant::findOrFail($variantId)->stock
                : (int) ($product->stock ?? 0);

            // Tìm item hiện có (⚠️ dùng variant_id, không phải product_variant_id)
            if (Auth::check()) {
                $existing = \App\Models\Cart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->when(
                        $variantId,
                        fn($q) => $q->where('variant_id', $variantId),
                        fn($q) => $q->whereNull('variant_id')
                    )->first();

                $currentQty = $existing?->quantity ?? 0;

                if ($stock !== null && $currentQty + $quantity > $stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng vượt quá tồn kho! Chỉ còn ' . $stock . ' sản phẩm.',
                        'max_quantity' => $stock
                    ], 400);
                }

                if ($existing) {
                    $existing->quantity = $currentQty + $quantity;
                    $existing->save();
                } else {
                    \App\Models\Cart::create([
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                        'variant_id' => $variantId,   // ✅ tên cột mới
                        'quantity' => $quantity,
                    ]);
                }
            } else {
                // Guest – session cart
                $cart = session()->get('cart', []);
                $key = $productId . '_' . ($variantId ?? 'default');
                $currentQty = isset($cart[$key]) ? (int) $cart[$key]['quantity'] : 0;

                if ($stock !== null && $currentQty + $quantity > $stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng vượt quá tồn kho! Chỉ còn ' . $stock . ' sản phẩm.',
                        'max_quantity' => $stock
                    ], 400);
                }

                $cart[$key] = [
                    'product_id' => $productId,
                    'variant_id' => $variantId,     // ✅
                    'quantity' => $currentQty + $quantity,
                ];
                session()->put('cart', $cart);
                session()->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng'
            ]);

        } catch (\Throwable $e) {
            Log::error('cart.add failed', ['msg' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng'
            ], 500);
        }
    }

    public function setBuyNow(Request $request)
    {
        // Chặn admin và staff không được mua hàng
        if (Auth::check()) {
            $user = Auth::user();
            $userRoles = $user->roles->pluck('name')->toArray();
            $blockedRoles = ['admin', 'staff', 'employee', 'manager'];
            
            if (array_intersect($userRoles, $blockedRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản Admin/Staff không được phép mua hàng!'
                ], 403);
            }
        }
        
        try {
            $data = $request->validate([
                'product_id' => ['required', 'integer', 'exists:products,id'],
                'quantity' => ['required', 'integer', 'min:1'],
                'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            ]);

            // Lưu thông tin "buy now" vào session
            session()->put('buynow', [
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'variant_id' => $data['variant_id'] ?? null,
            ]);
            session()->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã set buy now thành công!',
            ]);
        } catch (\Throwable $e) {
            Log::error('setBuyNow failed', ['msg' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi set buy now',
            ], 500);
        }
    }







    public function update(Request $request, $id)
    {
        // Chặn admin và staff không được mua hàng
        if (Auth::check()) {
            $user = Auth::user();
            $userRoles = $user->roles->pluck('name')->toArray();
            $blockedRoles = ['admin', 'staff', 'employee', 'manager'];
            
            if (array_intersect($userRoles, $blockedRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản Admin/Staff không được phép mua hàng!'
                ], 403);
            }
        }
        
        error_log('Update cart called with id: ' . $id . ' and quantity: ' . $request->quantity);

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if (Auth::check()) {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            // Lấy tồn kho
            $stock = null;
            if ($cartItem->variant_id) {
                $variant = ProductVariant::find($cartItem->variant_id);
                $stock = $variant ? (int)$variant->stock : null;
            } else {
                $product = Product::find($cartItem->product_id);
                $stock = $product ? (int)$product->stock : null;
            }
            if ($stock !== null && $request->quantity > $stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá tồn kho! Chỉ còn ' . $stock . ' sản phẩm.',
                    'max_quantity' => $stock
                ], 400);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();
            error_log('Updated DB cart item: ' . $cartItem->id);
        } else {
            $cart = session()->get('cart', []);
            error_log('Current session cart before update: ' . json_encode($cart));
            error_log('Available keys: ' . json_encode(array_keys($cart)));

            if (isset($cart[$id])) {
                // Lấy tồn kho
                $stock = null;
                if (!empty($cart[$id]['variant_id'])) {
                    $variant = ProductVariant::find($cart[$id]['variant_id']);
                    $stock = $variant ? (int)$variant->stock : null;
                } else {
                    $product = Product::find($cart[$id]['product_id']);
                    $stock = $product ? (int)$product->stock : null;
                }
                if ($stock !== null && $request->quantity > $stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng vượt quá tồn kho! Chỉ còn ' . $stock . ' sản phẩm.',
                        'max_quantity' => $stock
                    ], 400);
                }
                $cart[$id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
                session()->save();
                error_log('Updated session cart item: ' . $id . ' to quantity: ' . $request->quantity);
                error_log('Session cart after update: ' . json_encode(session()->get('cart', [])));
            } else {
                error_log('Session cart item not found: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng',
                    'debug' => [
                        'requested_id' => $id,
                        'available_keys' => array_keys($cart),
                        'cart' => $cart
                    ]
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng'
        ]);
    }

    public function remove($id)
    {
        // Chặn admin và staff không được mua hàng
        if (Auth::check()) {
            $user = Auth::user();
            $userRoles = $user->roles->pluck('name')->toArray();
            $blockedRoles = ['admin', 'staff', 'employee', 'manager'];
            
            if (array_intersect($userRoles, $blockedRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản Admin/Staff không được phép mua hàng!'
                ], 403);
            }
        }
        
        error_log('Remove cart called with id: ' . $id);

        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->delete();
            error_log('Removed from DB cart: ' . $id);
        } else {
            $cart = session()->get('cart', []);
            error_log('Current session cart before remove: ' . json_encode($cart));
            error_log('Available keys: ' . json_encode(array_keys($cart)));

            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                session()->save();
                error_log('Removed from session cart: ' . $id);
                error_log('Session cart after remove: ' . json_encode(session()->get('cart', [])));
            } else {
                error_log('Session cart item not found for removal: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng',
                    'debug' => [
                        'requested_id' => $id,
                        'available_keys' => array_keys($cart),
                        'cart' => $cart
                    ]
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
        ]);
    }

    public function clear()
    {
        // Chặn admin và staff không được mua hàng
        if (Auth::check()) {
            $user = Auth::user();
            $userRoles = $user->roles->pluck('name')->toArray();
            $blockedRoles = ['admin', 'staff', 'employee', 'manager'];
            
            if (array_intersect($userRoles, $blockedRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản Admin/Staff không được phép mua hàng!'
                ], 403);
            }
        }
        
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng'
        ]);
    }

    public function count()
    {
        // Chặn admin và staff không được mua hàng
        if (Auth::check()) {
            $user = Auth::user();
            $userRoles = $user->roles->pluck('name')->toArray();
            $blockedRoles = ['admin', 'staff', 'employee', 'manager'];
            
            if (array_intersect($userRoles, $blockedRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản Admin/Staff không được phép mua hàng!'
                ], 403);
            }
        }
        
        if (Auth::check()) {
            $count = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            $count = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}