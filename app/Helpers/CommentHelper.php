<?php

namespace App\Helpers;

use App\Models\OrderItem;
use App\Models\ProductComment;
use Illuminate\Support\Facades\Auth;

class CommentHelper
{
    /**
     * Kiểm tra user có thể comment sản phẩm không
     */
    public static function canComment($productId)
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        // Kiểm tra đã mua sản phẩm chưa
        $hasPurchased = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('status', ['delivered', 'shipped']); // Đã nhận hàng hoặc đã giao hàng
        })->where('product_id', $productId)->exists();

        if (!$hasPurchased) {
            return false;
        }

        // Kiểm tra đã comment chưa
        $existingComment = ProductComment::where('user_id', $user->id)
                                       ->where('product_id', $productId)
                                       ->whereNull('parent_id')
                                       ->first();

        return !$existingComment;
    }

    /**
     * Kiểm tra user có thể reply comment không
     */
    public static function canReply($productId)
    {
        return self::canComment($productId);
    }

    /**
     * Lấy thông báo lý do không thể comment
     */
    public static function getCommentRestrictionMessage($productId)
    {
        if (!Auth::check()) {
            return 'Bạn cần đăng nhập để bình luận.';
        }

        $user = Auth::user();
        
        // Kiểm tra đã mua sản phẩm chưa
        $hasPurchased = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('status', ['delivered', 'shipped']);
        })->where('product_id', $productId)->exists();

        if (!$hasPurchased) {
            return 'Bạn cần mua sản phẩm này trước khi bình luận.';
        }

        // Kiểm tra đã comment chưa
        $existingComment = ProductComment::where('user_id', $user->id)
                                       ->where('product_id', $productId)
                                       ->whereNull('parent_id')
                                       ->first();

        if ($existingComment) {
            return 'Bạn đã bình luận sản phẩm này rồi.';
        }

        return null; // Có thể comment
    }
} 