<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\FavoriteProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    /**
     * Thêm sản phẩm vào danh sách yêu thích
     */
    public function toggle(Request $request)
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để thêm vào yêu thích',
                    'redirect' => route('login')
                ], 401);
            } else {
                return redirect()->route('login')->with('message', 'Vui lòng đăng nhập để thêm vào yêu thích');
            }
        }

        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Kiểm tra xem sản phẩm đã được yêu thích chưa
        $favorite = FavoriteProduct::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            // Nếu đã yêu thích thì xóa
            $favorite->delete();
            $isFavorite = false;
            $message = 'Đã xóa khỏi danh sách yêu thích';
        } else {
            // Nếu chưa yêu thích thì thêm
            FavoriteProduct::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            $isFavorite = true;
            $message = 'Đã thêm vào danh sách yêu thích';
        }

        return response()->json([
            'success' => true,
            'is_favorite' => $isFavorite,
            'message' => $message
        ]);
    }

    /**
     * Lấy danh sách sản phẩm yêu thích
     */
    public function index()
    {
        // Redirect đến trang products.love thay vì hiển thị view riêng
        return redirect()->route('products.love');
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích
     */
    public function remove(Request $request)
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để xóa khỏi yêu thích',
                    'redirect' => route('login')
                ], 401);
            } else {
                return redirect()->route('login')->with('message', 'Vui lòng đăng nhập để xóa khỏi yêu thích');
            }
        }

        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        FavoriteProduct::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích'
        ]);
    }

    /**
     * Kiểm tra trạng thái yêu thích của sản phẩm
     */
    public function check(Request $request)
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để kiểm tra yêu thích',
                    'redirect' => route('login')
                ], 401);
            } else {
                return redirect()->route('login')->with('message', 'Vui lòng đăng nhập để kiểm tra yêu thích');
            }
        }

        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id'
        ]);

        $userId = Auth::id();
        $productIds = $request->product_ids;

        $favorites = FavoriteProduct::where('user_id', $userId)
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->toArray();

        return response()->json([
            'success' => true,
            'favorites' => $favorites
        ]);
    }
}
