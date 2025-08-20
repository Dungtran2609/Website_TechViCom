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
        // Nếu có order_id, kiểm tra đơn hàng
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

            // Kiểm tra đã đánh giá chưa
            $existingReview = self::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('order_id', $orderId)
                ->whereNull('parent_id')
                ->first();

            return !$existingReview;
        }

        return false;
    }
}
