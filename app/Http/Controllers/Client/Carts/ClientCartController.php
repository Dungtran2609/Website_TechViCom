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
        if (Auth::check()) {
            $cartItems = Cart::with(['product.productAllImages', 'product.variants', 'productVariant.attributeValues.attribute'])
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
            foreach ($cartItems as $cartItem) {
                $product = is_object($cartItem) ? $cartItem->product : $cartItem['product'];
                $quantity = is_object($cartItem) ? $cartItem->quantity : $cartItem['quantity'];
                $variant = is_object($cartItem) ? $cartItem->productVariant : (isset($cartItem['productVariant']) ? $cartItem['productVariant'] : null);
                // Lấy giá cho sản phẩm đơn hoặc biến thể
                $price = 0;
                if ($variant) {
                    $price = $variant->price ?? 0;
                } else {
                    // Nếu là sản phẩm đơn, lấy giá sale hoặc regular
                    if (isset($product->sale_price) && $product->sale_price > 0) {
                        $price = $product->sale_price;
                    } elseif (isset($product->regular_price) && $product->regular_price > 0) {
                        $price = $product->regular_price;
                    } elseif ($product->variants && $product->variants->count() > 0) {
                        $price = $product->variants->first()->price ?? 0;
                    }
                }
                $total += $price * $quantity;
                // Lấy ảnh đúng cho cả sản phẩm đơn và biến thể
                $image = null;
                if ($variant && isset($variant->image) && $variant->image) {
                    $image = asset('uploads/products/' . $variant->image);
                } elseif ($product->productAllImages && $product->productAllImages->first()) {
                    $image = asset('uploads/products/' . $product->productAllImages->first()->image);
                } else {
                    $image = asset('images/default-product.jpg');
                }
                $items[] = [
                    'id' => is_object($cartItem) ? $cartItem->id : (isset($cartItem['id']) ? $cartItem['id'] : null),
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'image' => $image,
                    'variant' => $variant ? [
                        'id' => $variant->id,
                        'attributes' => method_exists($variant, 'attributeValues') ? $variant->attributeValues->map(function($attr) {
                            return [
                                'name' => $attr->attribute->name,
                                'value' => $attr->value
                            ];
                        }) : [],
                    ] : null
                ];
            }
            return response()->json([
                'success' => true,
                'items' => $items,
                'total' => $total,
                'count' => count($items)
            ]);
        }

        return view('client.carts.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        try {
            error_log('Cart add function called with: ' . json_encode($request->all()));
            
            // Simple validation
            if (!$request->product_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ], 400);
            }
            
            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;
            $variantId = $request->variant_id;
            
            error_log("Adding product: $productId, quantity: $quantity, variant: $variantId");
            
            // Find product (optional validation)
            $product = Product::find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            if (Auth::check()) {
                // User đã đăng nhập
                $existingCart = Cart::where('user_id', Auth::id())
                                   ->where('product_id', $productId)
                                   ->where('product_variant_id', $variantId)
                                   ->first();

                if ($existingCart) {
                    $existingCart->quantity += $quantity;
                    $existingCart->save();
                } else {
                    Cart::create([
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                        'product_variant_id' => $variantId,
                        'quantity' => $quantity
                    ]);
                }
                error_log('Added to database cart for user: ' . Auth::id());
            } else {
                // Guest user - sử dụng session
                $cart = session()->get('cart', []);
                $key = $productId . '_' . ($variantId ?? 'default');

                error_log('Current cart session: ' . json_encode($cart));
                error_log('Adding item with key: ' . $key);

                if (isset($cart[$key])) {
                    $cart[$key]['quantity'] += $quantity;
                    error_log('Updated existing item quantity');
                } else {
                    $cart[$key] = [
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'quantity' => $quantity,
                        'product' => $product->toArray()
                    ];
                    error_log('Added new item to cart');
                }

                session()->put('cart', $cart);
                session()->save();
                error_log('Session cart after update: ' . json_encode(session()->get('cart', [])));
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng'
            ]);
            
        } catch (\Exception $e) {
            error_log('Add to cart error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        error_log('Update cart called with id: ' . $id . ' and quantity: ' . $request->quantity);
        
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if (Auth::check()) {
            $cartItem = Cart::where('user_id', Auth::id())
                           ->where('id', $id)
                           ->firstOrFail();
            
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
            error_log('Updated DB cart item: ' . $cartItem->id);
        } else {
            $cart = session()->get('cart', []);
            error_log('Current session cart before update: ' . json_encode($cart));
            error_log('Available keys: ' . json_encode(array_keys($cart)));
            
            if (isset($cart[$id])) {
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
