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
                  ->whereIn('status', ['delivered', 'received']); // Cho phép khi đã giao hoặc đã nhận hàng
        })->where('product_id', $productId)->get();

        if ($orderItems->isEmpty()) {
            return false;
        }

        $hasValidOrder = false;

        // Kiểm tra từng đơn hàng
        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;

            // Nếu trạng thái là 'delivered', kiểm tra thời gian giao hàng (15 ngày)
            if ($order->status === 'delivered') {
                // Sử dụng shipped_at hoặc updated_at khi chuyển sang delivered để tính thời gian
                $deliveredAt = $order->shipped_at ?: $order->updated_at;
                $daysSinceDelivered = now()->diffInDays($deliveredAt);
                if ($daysSinceDelivered < 0) {
                    $daysSinceDelivered = 0;
                }
                if ($daysSinceDelivered > 15) {
                    continue; // Bỏ qua đơn hàng này, kiểm tra đơn hàng khác
                }

                $hasValidOrder = true; // Có ít nhất 1 đơn hàng hợp lệ về thời gian

                // Kiểm tra đã comment cho sản phẩm này trong đơn hàng này chưa
                $existingComment = ProductComment::where('user_id', $user->id)
                                               ->where('product_id', $productId)
                                               ->where('order_id', $order->id)
                                               ->whereNull('parent_id')
                                               ->first();

                if (!$existingComment) {
                    return true; // Có thể đánh giá cho sản phẩm này trong đơn hàng này
                }
                continue; // Đã đánh giá rồi, kiểm tra đơn hàng khác
            }

            // Nếu trạng thái là 'received', kiểm tra thời gian nhận hàng (15 ngày)
            if ($order->status === 'received' && $order->received_at) {
                $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
                $daysSinceReceived = now()->diffInDays($receivedAt);
                // Nếu received_at trong tương lai, coi như vừa nhận hàng
                if ($daysSinceReceived < 0) {
                    $daysSinceReceived = 0;
                }
                if ($daysSinceReceived > 15) {
                    continue; // Bỏ qua đơn hàng này, kiểm tra đơn hàng khác
                }

                $hasValidOrder = true; // Có ít nhất 1 đơn hàng hợp lệ về thời gian

                // Kiểm tra đã comment cho sản phẩm này trong đơn hàng này chưa
                $existingComment = ProductComment::where('user_id', $user->id)
                                               ->where('product_id', $productId)
                                               ->where('order_id', $order->id)
                                               ->whereNull('parent_id')
                                               ->first();

                if (!$existingComment) {
                    return true; // Có thể đánh giá cho sản phẩm này trong đơn hàng này
                }
            }
        }

        // Nếu không có đơn hàng hợp lệ về thời gian hoặc đã đánh giá hết
        return false;
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
                  ->whereIn('status', ['delivered', 'received']);
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

            // Nếu trạng thái là 'delivered', kiểm tra thời gian giao hàng (15 ngày)
            if ($order->status === 'delivered') {
                // Sử dụng shipped_at hoặc updated_at khi chuyển sang delivered để tính thời gian
                $deliveredAt = $order->shipped_at ?: $order->updated_at;
                $daysSinceDelivered = now()->diffInDays($deliveredAt);
                if ($daysSinceDelivered < 0) {
                    $daysSinceDelivered = 0;
                }
                if ($daysSinceDelivered <= 15) {
                    $timeExpired = false; // Có ít nhất 1 đơn hàng còn thời gian
                    
                    // Kiểm tra đã comment cho sản phẩm này chưa (bất kỳ đơn hàng nào)
                    $existingComment = ProductComment::where('user_id', $user->id)
                                                   ->where('product_id', $productId)
                                                   ->whereNull('parent_id')
                                                   ->first();

                    if (!$existingComment) {
                        $canReviewAny = true; // Có thể đánh giá cho sản phẩm này
                        $allReviewed = false;
                        break;
                    }
                }
                continue; // Đã đánh giá rồi, kiểm tra đơn hàng khác
            }

            // Nếu trạng thái là 'received', kiểm tra thời gian nhận hàng (15 ngày)
            if ($order->status === 'received' && $order->received_at) {
                $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
                $daysSinceReceived = now()->diffInDays($receivedAt);
                // Nếu received_at trong tương lai, coi như vừa nhận hàng
                if ($daysSinceReceived < 0) {
                    $daysSinceReceived = 0;
                }
                if ($daysSinceReceived <= 15) {
                    $timeExpired = false; // Có ít nhất 1 đơn hàng còn thời gian
                    
                    // Kiểm tra đã comment cho sản phẩm này chưa (bất kỳ đơn hàng nào)
                    $existingComment = ProductComment::where('user_id', $user->id)
                                                   ->where('product_id', $productId)
                                                   ->whereNull('parent_id')
                                                   ->first();

                    if (!$existingComment) {
                        $canReviewAny = true; // Có thể đánh giá cho sản phẩm này
                        $allReviewed = false;
                        break;
                    }
                }
            }
        }

        if ($timeExpired) {
            return 'Tất cả đơn hàng đã hết thời gian đánh giá (15 ngày).';
        }

        if ($allReviewed) {
            return 'Bạn đã đánh giá sản phẩm này cho tất cả đơn hàng rồi.';
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
                  ->whereIn('status', ['delivered', 'received']);
        })->where('product_id', $productId)->get();

        if ($orderItems->isEmpty()) {
            return 0;
        }

        $maxRemainingDays = 0;

        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;

            // Nếu trạng thái là 'delivered', kiểm tra thời gian giao hàng (15 ngày)
            if ($order->status === 'delivered') {
                // Sử dụng shipped_at hoặc updated_at khi chuyển sang delivered để tính thời gian
                $deliveredAt = $order->shipped_at ?: $order->updated_at;
                $daysSinceDelivered = now()->diffInDays($deliveredAt);
                if ($daysSinceDelivered < 0) {
                    $daysSinceDelivered = 0;
                }
                if ($daysSinceDelivered <= 15) {
                    // Kiểm tra đã comment cho sản phẩm này trong đơn hàng này chưa
                    $existingComment = ProductComment::where('user_id', $user->id)
                                                   ->where('product_id', $productId)
                                                   ->where('order_id', $order->id)
                                                   ->whereNull('parent_id')
                                                   ->first();

                    if (!$existingComment) {
                        $remainingDays = 15 - $daysSinceDelivered;
                        $maxRemainingDays = max($maxRemainingDays, $remainingDays);
                    }
                }
                continue;
            }

            // Nếu trạng thái là 'received', kiểm tra thời gian nhận hàng (15 ngày)
            if ($order->status === 'received' && $order->received_at) {
                $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
                $daysSinceReceived = now()->diffInDays($receivedAt);
                // Nếu received_at trong tương lai, coi như vừa nhận hàng
                if ($daysSinceReceived < 0) {
                    $daysSinceReceived = 0;
                }

                // Kiểm tra đã comment cho sản phẩm này trong đơn hàng này chưa
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
                  ->whereIn('status', ['delivered', 'received']);
        })->where('product_id', $productId)
          ->with(['productVariant.attributeValues.attribute', 'order'])
          ->get();

        if ($orderItems->isEmpty()) {
            return collect();
        }

        $purchasedItems = collect();

        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;

            // Nếu trạng thái là 'delivered', kiểm tra thời gian giao hàng (15 ngày)
            if ($order->status === 'delivered') {
                // Sử dụng shipped_at hoặc updated_at khi chuyển sang delivered để tính thời gian
                $deliveredAt = $order->shipped_at ?: $order->updated_at;
                $daysSinceDelivered = now()->diffInDays($deliveredAt);
                if ($daysSinceDelivered < 0) {
                    $daysSinceDelivered = 0;
                }
                if ($daysSinceDelivered <= 15) {
                    // Kiểm tra đã comment cho sản phẩm này trong đơn hàng này chưa
                    $existingComment = ProductComment::where('user_id', $user->id)
                                                   ->where('product_id', $productId)
                                                   ->where('order_id', $order->id)
                                                   ->whereNull('parent_id')
                                                   ->first();

                    if (!$existingComment) {
                        $purchasedItems->push($orderItem);
                    }
                }
                continue;
            }

            // Nếu trạng thái là 'received', kiểm tra thời gian nhận hàng (15 ngày)
            if ($order->status === 'received' && $order->received_at) {
                $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
                $daysSinceReceived = now()->diffInDays($receivedAt);
                if ($daysSinceReceived < 0) {
                    $daysSinceReceived = 0;
                }
                if ($daysSinceReceived > 15) {
                    continue; // Bỏ qua đơn hàng hết thời gian
                }

                // Kiểm tra đã comment cho sản phẩm này trong đơn hàng này chưa
                $existingComment = ProductComment::where('user_id', $user->id)
                                               ->where('product_id', $productId)
                                               ->where('order_id', $order->id)
                                               ->whereNull('parent_id')
                                               ->first();

                if (!$existingComment) {
                    $purchasedItems->push($orderItem);
                }
            }
        }

        return $purchasedItems;
    }

    /**
     * Lấy danh sách sản phẩm đã mua và chưa đánh giá (gom nhóm theo đơn hàng)
     */
    public static function getPurchasedItemsGrouped($productId)
    {
        if (!Auth::check()) {
            return collect();
        }

        $user = Auth::user();
        
        // Lấy tất cả order items của sản phẩm này mà user đã mua và đã nhận hàng
        $orderItems = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('status', ['delivered', 'received']);
        })->where('product_id', $productId)
          ->with(['productVariant.attributeValues.attribute', 'order'])
          ->get();

        if ($orderItems->isEmpty()) {
            return collect();
        }

        // Gom nhóm theo order_id
        $groupedItems = $orderItems->groupBy('order_id');
        $purchasedItems = collect();

        foreach ($groupedItems as $orderId => $items) {
            $order = $items->first()->order;
            $canReview = false;
            $remainingDays = 0;

            // Kiểm tra trạng thái đơn hàng và thời gian
            if ($order->status === 'delivered') {
                $deliveredAt = $order->shipped_at ?: $order->updated_at;
                $daysSinceDelivered = now()->diffInDays($deliveredAt);
                if ($daysSinceDelivered < 0) {
                    $daysSinceDelivered = 0;
                }
                if ($daysSinceDelivered <= 15) {
                    // Kiểm tra đã comment cho sản phẩm này trong đơn hàng này chưa
                    $existingComment = ProductComment::where('user_id', $user->id)
                                                   ->where('product_id', $productId)
                                                   ->where('order_id', $order->id)
                                                   ->whereNull('parent_id')
                                                   ->first();

                    if (!$existingComment) {
                        $canReview = true;
                        $remainingDays = 15 - $daysSinceDelivered;
                    }
                }
            } elseif ($order->status === 'received' && $order->received_at) {
                $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
                $daysSinceReceived = now()->diffInDays($receivedAt);
                if ($daysSinceReceived < 0) {
                    $daysSinceReceived = 0;
                }
                if ($daysSinceReceived <= 15) {
                    // Kiểm tra đã comment cho sản phẩm này trong đơn hàng này chưa
                    $existingComment = ProductComment::where('user_id', $user->id)
                                                   ->where('product_id', $productId)
                                                   ->where('order_id', $order->id)
                                                   ->whereNull('parent_id')
                                                   ->first();

                    if (!$existingComment) {
                        $canReview = true;
                        $remainingDays = 15 - $daysSinceReceived;
                    }
                }
            }

            if ($canReview) {
                // Tạo object gom nhóm
                $groupedItem = (object) [
                    'order' => $order,
                    'items' => $items,
                    'can_review' => $canReview,
                    'remaining_days' => $remainingDays,
                    'total_quantity' => $items->sum('quantity'),
                    'total_price' => $items->sum(function($item) {
                        return $item->price * $item->quantity;
                    })
                ];
                
                $purchasedItems->push($groupedItem);
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
                  ->whereIn('status', ['delivered', 'received']);
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

            // Nếu trạng thái là 'delivered', kiểm tra thời gian giao hàng (15 ngày)
            if ($order->status === 'delivered') {
                // Sử dụng shipped_at hoặc updated_at khi chuyển sang delivered để tính thời gian
                $deliveredAt = $order->shipped_at ?: $order->updated_at;
                $daysSinceDelivered = now()->diffInDays($deliveredAt);
                if ($daysSinceDelivered < 0) {
                    $daysSinceDelivered = 0;
                }
                if ($daysSinceDelivered <= 15) {
                    $timeExpired = false; // Có ít nhất 1 đơn hàng còn thời gian
                    
                    // Kiểm tra đã comment cho sản phẩm này chưa
                    $existingComment = ProductComment::where('user_id', $user->id)
                                                   ->where('product_id', $productId)
                                                   ->where('order_id', $order->id)
                                                   ->whereNull('parent_id')
                                                   ->first();

                    if (!$existingComment) {
                        $canReviewAny = true; // Có thể đánh giá cho sản phẩm này trong đơn hàng này
                        $allReviewed = false;
                        break;
                    }
                }
                continue; // Đã đánh giá rồi, kiểm tra đơn hàng khác
            }

            // Nếu trạng thái là 'received', kiểm tra thời gian nhận hàng (15 ngày)
            if ($order->status === 'received' && $order->received_at) {
                $receivedAt = is_string($order->received_at) ? \Carbon\Carbon::parse($order->received_at) : $order->received_at;
                $daysSinceReceived = now()->diffInDays($receivedAt);
                // Nếu received_at trong tương lai, coi như vừa nhận hàng
                if ($daysSinceReceived < 0) {
                    $daysSinceReceived = 0;
                }
                if ($daysSinceReceived <= 15) {
                    $timeExpired = false; // Có ít nhất 1 đơn hàng còn thời gian
                    
                    // Kiểm tra đã comment cho sản phẩm này chưa
                    $existingComment = ProductComment::where('user_id', $user->id)
                                                   ->where('product_id', $productId)
                                                   ->where('order_id', $order->id)
                                                   ->whereNull('parent_id')
                                                   ->first();

                    if (!$existingComment) {
                        $canReviewAny = true; // Có thể đánh giá cho sản phẩm này trong đơn hàng này
                        $allReviewed = false;
                        break;
                    }
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
                'message' => 'Bạn đã đánh giá sản phẩm này cho tất cả đơn hàng rồi.',
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