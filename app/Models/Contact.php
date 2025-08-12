<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'user_id',
        'handled_by',
        'status',
        'response',
        'responded_at',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'responded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function handledByUser()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Kiểm tra xem user đã đạt giới hạn liên hệ trong ngày chưa
     * 
     * @param int|null $userId
     * @param string|null $email
     * @return bool
     */
    public static function hasReachedDailyLimit($userId = null, $email = null)
    {
        $query = self::whereDate('created_at', today());
        
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($email) {
            $query->where('email', $email);
        } else {
            return false;
        }
        
        return $query->count() >= 5;
    }

    /**
     * Đếm số lần liên hệ trong ngày của user
     * 
     * @param int|null $userId
     * @param string|null $email
     * @return int
     */
    public static function getTodayContactCount($userId = null, $email = null)
    {
        $query = self::whereDate('created_at', today());
        
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($email) {
            $query->where('email', $email);
        } else {
            return 0;
        }
        
        return $query->count();
    }
}
