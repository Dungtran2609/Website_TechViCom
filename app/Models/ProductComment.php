<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class ProductComment extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'order_id', 'content', 'rating', 'status', 'parent_id', 'is_hidden'
    ];


    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DELETED = 'deleted';


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function replies()
    {
        return $this->hasMany(ProductComment::class, 'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(ProductComment::class, 'parent_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Kiểm tra xem user có thể đánh giá sản phẩm này không
     */
    public static function canUserReview($userId, $productId, $orderId = null)
    {
        // Kiểm tra đã đánh giá sản phẩm này chưa (bất kỳ đơn hàng nào)
        $existingReview = self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->whereNull('parent_id')
            ->first();

        if ($existingReview) {
            return false; // Đã đánh giá rồi
        }

        // Nếu có order_id, kiểm tra đơn hàng cụ thể
        if ($orderId) {
            $order = Order::where('id', $orderId)
                ->where('user_id', $userId)
                ->where('status', 'received')
                ->first();

            if (!$order) {
                return false;
            }

            // Kiểm tra thời gian nhận hàng (15 ngày)
            if ($order->received_at && now()->diffInDays($order->received_at) > 15) {
                return false;
            }

            return true; // Có thể đánh giá
        }

        // Nếu không có order_id, kiểm tra có đơn hàng nào hợp lệ không
        $orderItems = \App\Models\OrderItem::whereHas('order', function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', 'received');
        })->where('product_id', $productId)->get();

        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;
            
            if (!$order->received_at) {
                continue;
            }

            $daysSinceReceived = now()->diffInDays($order->received_at);
            if ($daysSinceReceived < 0) {
                $daysSinceReceived = 0;
            }
            if ($daysSinceReceived <= 15) {
                return true; // Có đơn hàng hợp lệ
            }
        }

        return false;
    }
}
