<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // --- THÊM MỚI: Định nghĩa các hằng số để quản lý tập trung ---

    /**
     * Các trạng thái xử lý của đơn hàng.
     */
    public const ORDER_STATUSES = [
        'pending'    => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped'    => 'Đã giao hàng',
        'delivered'  => 'Đã nhận hàng',
        'cancelled'  => 'Đã hủy',
        'returned'   => 'Đã trả hàng',
    ];

    /**
     * Các phương thức thanh toán.
     */
    public const PAYMENT_METHODS = [
        'cod'           => 'Thanh toán khi nhận hàng (COD)',
        'bank_transfer' => 'Chuyển khoản ngân hàng (Thủ công)',
        'vietqr'        => 'Thanh toán VietQR Online', // Thêm phương thức mới
    ];

    /**
     * Các trạng thái thanh toán của đơn hàng.
     */
    public const PAYMENT_STATUSES = [
        'unpaid'   => 'Chưa thanh toán', // Trạng thái ban đầu
        'paid'     => 'Đã thanh toán',   // Webhook sẽ cập nhật trạng thái này
        'failed'   => 'Thanh toán thất bại',
        'refunded' => 'Đã hoàn tiền',
    ];

    /**
     * Các thuộc tính có thể gán hàng loạt (mass assignable).
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
        'payment_status', // Rất quan trọng cho thanh toán online
    ];

    /**
     * Các trường kiểu ngày tháng cần được cast sang đối tượng Carbon.
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'shipped_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }

    // =========================================================================
    // === ACCESSORS & MUTATORS ===
    // =========================================================================

    /**
     * SỬA ĐỔI: Accessor dịch phương thức thanh toán sang tiếng Việt, sử dụng hằng số.
     */
    public function getPaymentMethodVietnameseAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    /**
     * SỬA ĐỔI: Accessor dịch trạng thái đơn hàng sang tiếng Việt, sử dụng hằng số.
     */
    public function getStatusVietnameseAttribute(): string
    {
        return self::ORDER_STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * SỬA ĐỔI: Accessor dịch trạng thái thanh toán sang tiếng Việt, sử dụng hằng số.
     */
    public function getPaymentStatusVietnameseAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? ucfirst($this->payment_status);
    }
}
