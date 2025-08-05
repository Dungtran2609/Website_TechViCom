<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reason',
        'type',
        'status',
        'requested_at',
        'processed_at',
        'admin_note',
        'client_note', // 👈 Bổ sung do có trong migration
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Mối quan hệ: Đơn hàng gốc
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Accessor: dịch trạng thái sang tiếng Việt
     */
    public function getStatusVietnameseAttribute()
    {
        return match ($this->status) {
            'pending'  => 'Đang chờ duyệt',
            'approved' => 'Đã chấp thuận',
            'rejected' => 'Đã từ chối',
            default    => ucfirst($this->status),
        };
    }

    /**
     * Accessor: dịch loại yêu cầu
     */
    public function getTypeVietnameseAttribute()
    {
        return match ($this->type) {
            'cancel' => 'Hủy đơn hàng',
            'return' => 'Trả hàng',
            default  => ucfirst($this->type),
        };
    }

    /**
     * Scope lọc theo trạng thái
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}

