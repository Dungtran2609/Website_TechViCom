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
        
        // Kiểm tra có đơn hàng nào đã nhận và chưa đánh giá không
        $orderItems = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'received'); // Chỉ cho phép khi đã nhận hàng
        })->where('product_id', $productId)->get();

        if ($orderItems->isEmpty()) {
            return false;
        }

        // Kiểm tra từng đơn hàng
        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;

            // Chỉ cho phép đánh giá nếu có received_at
            if (!$order->received_at) {
                continue; // Bỏ qua đơn hàng không có received_at
            }

            // Kiểm tra thời gian nhận hàng (15 ngày)
            $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
            $daysSinceReceived = now()->diffInDays($receivedAt);
            // Nếu received_at trong tương lai, coi như vừa nhận hàng
            if ($daysSinceReceived < 0) {
                $daysSinceReceived = 0;
            }
            if ($daysSinceReceived > 15) {
                continue; // Bỏ qua đơn hàng này, kiểm tra đơn hàng khác
            }

            // Kiểm tra đã comment cho đơn hàng này chưa
            $existingComment = ProductComment::where('user_id', $user->id)
                                           ->where('product_id', $productId)
                                           ->where('order_id', $order->id)
                                           ->whereNull('parent_id')
                                           ->first();

            if (!$existingComment) {
                return true; // Có thể đánh giá cho đơn hàng này
            }
        }

        return false; // Đã đánh giá hết tất cả đơn hàng
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
        
        // Kiểm tra có đơn hàng nào đã nhận không
        $orderItems = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'received');
        })->where('product_id', $productId)->get();

        if ($orderItems->isEmpty()) {
            return 'Bạn cần mua và nhận sản phẩm này trước khi đánh giá.';
        }

        $canReviewAny = false;
        $allReviewed = true;
        $timeExpired = true;

        // Kiểm tra từng đơn hàng
        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;

            // Chỉ cho phép đánh giá nếu có received_at
            if (!$order->received_at) {
                continue; // Bỏ qua đơn hàng không có received_at
            }

            // Kiểm tra thời gian nhận hàng (15 ngày)
            $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
            $daysSinceReceived = now()->diffInDays($receivedAt);
            // Nếu received_at trong tương lai, coi như vừa nhận hàng
            if ($daysSinceReceived < 0) {
                $daysSinceReceived = 0;
            }
            if ($daysSinceReceived <= 15) {
                $timeExpired = false; // Có ít nhất 1 đơn hàng còn thời gian
                
                // Kiểm tra đã comment cho đơn hàng này chưa
                $existingComment = ProductComment::where('user_id', $user->id)
                                               ->where('product_id', $productId)
                                               ->where('order_id', $order->id)
                                               ->whereNull('parent_id')
                                               ->first();

                if (!$existingComment) {
                    $canReviewAny = true; // Có thể đánh giá cho đơn hàng này
                    $allReviewed = false;
                    break;
                }
            }
        }

        if ($timeExpired) {
            return 'Tất cả đơn hàng đã hết thời gian đánh giá (15 ngày).';
        }

        if ($allReviewed) {
            return 'Bạn đã đánh giá tất cả đơn hàng của sản phẩm này.';
        }

        return null; // Có thể comment
    }

    /**
     * Lấy thời gian còn lại để đánh giá (tính bằng ngày)
     */
    public static function getRemainingDaysToReview($productId)
    {
        if (!Auth::check()) {
            return 0;
        }

        $user = Auth::user();
        
        // Lấy tất cả đơn hàng đã nhận và chưa đánh giá
        $orderItems = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'received');
        })->where('product_id', $productId)->get();

        if ($orderItems->isEmpty()) {
            return 0;
        }

        $maxRemainingDays = 0;

        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;

            if (!$order->received_at) {
                continue;
            }

            $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
            $daysSinceReceived = now()->diffInDays($receivedAt);
            // Nếu received_at trong tương lai, coi như vừa nhận hàng
            if ($daysSinceReceived < 0) {
                $daysSinceReceived = 0;
            }

            // Kiểm tra đã comment cho đơn hàng này chưa
            $existingComment = ProductComment::where('user_id', $user->id)
                                           ->where('product_id', $productId)
                                           ->where('order_id', $order->id)
                                           ->whereNull('parent_id')
                                           ->first();

            if (!$existingComment) {
                $remainingDays = 15 - $daysSinceReceived;
                $maxRemainingDays = max($maxRemainingDays, $remainingDays);
            }
        }

        return max(0, $maxRemainingDays);
    }

    /**
     * Lấy danh sách sản phẩm đã mua và chưa đánh giá
     */
    public static function getPurchasedItems($productId)
    {
        if (!Auth::check()) {
            return collect();
        }

        $user = Auth::user();
        
        // Lấy tất cả order items của sản phẩm này mà user đã mua và đã nhận hàng
        $orderItems = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'received');
        })->where('product_id', $productId)
          ->with(['productVariant.attributeValues.attribute', 'order'])
          ->get();

        if ($orderItems->isEmpty()) {
            return collect();
        }

        $purchasedItems = collect();

        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;

            // Chỉ cho phép đánh giá nếu có received_at
            if (!$order->received_at) {
                continue;
            }

            // Kiểm tra thời gian nhận hàng (15 ngày)
            $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
            $daysSinceReceived = now()->diffInDays($receivedAt);
            if ($daysSinceReceived < 0) {
                $daysSinceReceived = 0;
            }
            if ($daysSinceReceived > 15) {
                continue; // Bỏ qua đơn hàng hết thời gian
            }

            // Kiểm tra đã comment cho đơn hàng này chưa
            $existingComment = ProductComment::where('user_id', $user->id)
                                           ->where('product_id', $productId)
                                           ->where('order_id', $order->id)
                                           ->whereNull('parent_id')
                                           ->first();

            if (!$existingComment) {
                $purchasedItems->push($orderItem);
            }
        }

        return $purchasedItems;
    }

    /**
     * Kiểm tra xem có thể đánh giá không và trả về thông tin chi tiết
     */
    public static function getReviewStatus($productId)
    {
        if (!Auth::check()) {
            return [
                'can_review' => false,
                'message' => 'Bạn cần đăng nhập để đánh giá.',
                'remaining_days' => 0
            ];
        }

        $user = Auth::user();
        
        // Kiểm tra có đơn hàng nào đã nhận không
        $orderItems = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'received');
        })->where('product_id', $productId)->get();

        if ($orderItems->isEmpty()) {
            return [
                'can_review' => false,
                'message' => 'Bạn cần mua và nhận sản phẩm này trước khi đánh giá.',
                'remaining_days' => 0
            ];
        }

        $remainingDays = self::getRemainingDaysToReview($productId);
        $canReviewAny = false;
        $allReviewed = true;
        $timeExpired = true;

        // Kiểm tra từng đơn hàng
        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;

            // Chỉ cho phép đánh giá nếu có received_at
            if (!$order->received_at) {
                continue; // Bỏ qua đơn hàng không có received_at
            }

            // Kiểm tra thời gian nhận hàng (15 ngày)
            $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
            $daysSinceReceived = now()->diffInDays($receivedAt);
            // Nếu received_at trong tương lai, coi như vừa nhận hàng
            if ($daysSinceReceived < 0) {
                $daysSinceReceived = 0;
            }
            if ($daysSinceReceived <= 15) {
                $timeExpired = false; // Có ít nhất 1 đơn hàng còn thời gian
                
                // Kiểm tra đã comment cho đơn hàng này chưa
                $existingComment = ProductComment::where('user_id', $user->id)
                                               ->where('product_id', $productId)
                                               ->where('order_id', $order->id)
                                               ->whereNull('parent_id')
                                               ->first();

                if (!$existingComment) {
                    $canReviewAny = true; // Có thể đánh giá cho đơn hàng này
                    $allReviewed = false;
                    break;
                }
            }
        }

        if ($timeExpired) {
            return [
                'can_review' => false,
                'message' => 'Tất cả đơn hàng đã hết thời gian đánh giá (15 ngày).',
                'remaining_days' => 0
            ];
        }

        if ($allReviewed) {
            return [
                'can_review' => false,
                'message' => 'Bạn đã đánh giá tất cả đơn hàng của sản phẩm này.',
                'remaining_days' => 0
            ];
        }

        return [
            'can_review' => true,
            'message' => "Bạn còn {$remainingDays} ngày để đánh giá sản phẩm.",
            'remaining_days' => $remainingDays
        ];
    }
} 