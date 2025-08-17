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
            $cartItems = Cart::with(['product.productAllImages', 'productVariant.attributeValues.attribute'])
                ->where('user_id', Auth::id())
                ->get();
        } else {
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

        // Trả về JSON cho AJAX
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            $items = [];
            $total = 0;
            foreach ($cartItems as $key => $cartItem) {
                $product = $cartItem->product;
                $quantity = $cartItem->quantity;
                $variant = $cartItem->productVariant;
                $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);
                $total += $price * $quantity;
                // Lấy đúng trường ảnh
                $image = asset('images/default-product.jpg');
                if ($product->productAllImages && $product->productAllImages->count() > 0) {
                    $imgObj = $product->productAllImages->first();
                    $imgField = $imgObj->image_path ?? $imgObj->image_url ?? $imgObj->image ?? null;
                    if ($imgField) {
                        $image = asset('storage/' . ltrim($imgField, '/'));
                    }
                }
                $attributes = $variant ? $variant->attributeValues->map(function ($v) {
                    return [
                        'name' => $v->attribute->name,
                        'value' => $v->value
                    ];
                }) : [];
                $items[] = [
                    'id' => $cartItem->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'image' => $image,
                    'attributes' => $attributes,
                    'variant_id' => $variant ? $variant->id : null
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
            // Validate
            if (!$request->product_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ], 400);
            }
            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;
            $variantId = $request->variant_id;

            $product = Product::with(['productAllImages', 'variants'])->find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Kiểm tra tồn kho
            $stock = null;
            $variant = null;
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Biến thể không tồn tại'
                    ], 404);
                }
                $stock = $variant->stock;
            } else {
                $stock = $product->stock ?? 0;
            }

            // Lấy số lượng hiện tại trong giỏ
            $currentQty = 0;
            if (Auth::check()) {
                $existingCart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->first();
                if ($existingCart) {
                    $currentQty = $existingCart->quantity;
                }
            } else {
                $cart = session()->get('cart', []);
                $key = $productId . '_' . ($variantId ?? 'default');
                if (isset($cart[$key])) {
                    $currentQty = $cart[$key]['quantity'];
                }
            }

            if ($stock !== null && ($currentQty + $quantity) > $stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá tồn kho!'
                ], 400);
            }

            // Thêm vào giỏ
            if (Auth::check()) {
                if (isset($existingCart)) {
                    $existingCart->quantity += $quantity;
                    $existingCart->save();
                } else {
                    $existingCart = Cart::create([
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                        'product_variant_id' => $variantId,
                        'quantity' => $quantity
                    ]);
                }
            } else {
                $cart = session()->get('cart', []);
                $key = $productId . '_' . ($variantId ?? 'default');
                if (isset($cart[$key])) {
                    $cart[$key]['quantity'] += $quantity;
                } else {
                    $cart[$key] = [
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'quantity' => $quantity,
                        'product' => $product->toArray()
                    ];
                }
                session()->put('cart', $cart);
                session()->save();
            }

            // Trả về thông tin sản phẩm vừa thêm
            $image = asset('images/default-product.jpg');
            if ($product->productAllImages && $product->productAllImages->count() > 0) {
                $imgObj = $product->productAllImages->first();
                $imgField = $imgObj->image_path ?? $imgObj->image_url ?? $imgObj->image ?? null;
                if ($imgField) {
                    $image = asset('storage/' . ltrim($imgField, '/'));
                }
            }
            $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);
            $attributes = $variant ? $variant->attributeValues->map(function ($v) {
                return [
                    'name' => $v->attribute->name,
                    'value' => $v->value
                ];
            }) : [];

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                'item' => [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'name' => $product->name,
                    'image' => $image,
                    'price' => $price,
                    'quantity' => $currentQty + $quantity,
                    'attributes' => $attributes
                ]
            ]);
        } catch (\Exception $e) {
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
            $cartItem = Cart::with(['product', 'productVariant'])
                ->where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            // Kiểm tra số lượng tồn kho
            $stock = null;
            if ($cartItem->productVariant) {
                $stock = $cartItem->productVariant->stock;
            } else {
                $stock = $cartItem->product->stock ?? 0;
            }

            // Kiểm tra nếu số lượng vượt quá tồn kho
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
                // Kiểm tra số lượng tồn kho cho session cart
                $productId = $cart[$id]['product_id'];
                $variantId = $cart[$id]['variant_id'] ?? null;
                
                $product = Product::find($productId);
                if (!$product) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại'
                    ], 404);
                }

                $stock = null;
                if ($variantId) {
                    $variant = ProductVariant::find($variantId);
                    if ($variant) {
                        $stock = $variant->stock;
                    }
                } else {
                    $stock = $product->stock ?? 0;
                }

                // Kiểm tra nếu số lượng vượt quá tồn kho
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

    public function setBuyNow(Request $request)
    {
        try {
            // Validate
            if (!$request->product_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ], 400);
            }

            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;
            $variantId = $request->variant_id ?? null;

            // Kiểm tra sản phẩm tồn tại
            $product = Product::find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại'
                ], 404);
            }

            // Kiểm tra variant nếu có
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if (!$variant || $variant->product_id != $productId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Biến thể sản phẩm không hợp lệ'
                    ], 400);
                }
            }

            // Set session buynow
            $buynowData = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'variant_id' => $variantId
            ];
            
            session(['buynow' => $buynowData]);
            
            Log::info('Buynow session set', $buynowData);

            return response()->json([
                'success' => true,
                'message' => 'Đã sẵn sàng mua ngay'
            ]);

        } catch (\Exception $e) {
            Log::error('setBuyNow error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra'
            ], 500);
        }
    }
}
