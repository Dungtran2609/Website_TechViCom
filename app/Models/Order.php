<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Các thuộc tính có thể gán hàng loạt
     */
    protected $fillable = [
        'user_id',
        'address_id',
        'shipping_method_id',
        'payment_method',
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'shipping_fee',
        'total_amount',
        'final_total',
        'status',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'shipped_at',
    ];

    /**
     * Trường kiểu ngày cần được cast sang Carbon
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'shipped_at',
    ];

    /**
     * Mối quan hệ: đơn hàng thuộc về người dùng
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ: địa chỉ giao hàng
     */
    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    /**
     * Mối quan hệ: phương thức vận chuyển
     */
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    /**
     * Mối quan hệ: mã giảm giá áp dụng
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    /**
     * Mối quan hệ: các sản phẩm trong đơn hàng
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Mối quan hệ: giao dịch thanh toán (nếu có)
     */


    /**
     * Mối quan hệ: các lần trả hàng liên quan
     */
    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }

    /**
     * Accessor: dịch phương thức thanh toán sang tiếng Việt
     */
    public function getPaymentMethodVietnameseAttribute()
    {
        $methods = [
            'credit_card'   => 'Thẻ tín dụng/ghi nợ',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cod'           => 'Thanh toán khi nhận hàng',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Accessor: dịch trạng thái đơn hàng sang tiếng Việt
     */
    public function getStatusVietnameseAttribute()
    {
        $statuses = [
            'pending'    => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped'    => 'Đã giao',
            'delivered'  => 'Đã nhận',
            'cancelled'  => 'Đã hủy',
            'returned'   => 'Đã trả hàng',
        ];

        return $statuses[$this->status] ?? $this->status;
    }
    public const PAYMENT_STATUSES = [
        'pending' => 'Đang chờ xử lý',
        'paid' => 'Đã thanh toán',
        'failed' => 'Thất bại',
    ];
    
}
