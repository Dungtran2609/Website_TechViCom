<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    // Bảng tương ứng
    protected $table = 'user_addresses';

    // Các cột có thể điền vào
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'address',
        'address_line',
        'ward',
        'district',
        'city',
        'is_default',
    ];

    /**
     * Quan hệ ngược về bảng users.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
