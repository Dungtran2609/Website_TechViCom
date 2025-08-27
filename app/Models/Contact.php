<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

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
        'deleted_at' => 'datetime',
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

    /**
     * Scope để lấy các liên hệ đã bị xóa mềm
     */
    public function scopeOnlyTrashed($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope để lấy tất cả liên hệ (bao gồm cả đã xóa)
     */
    public function scopeWithTrashed($query)
    {
        return $query->withTrashed();
    }

    /**
     * Kiểm tra xem liên hệ có thể xóa được không
     */
    public function canBeDeleted()
    {
        // Kiểm tra trạng thái
        if (in_array($this->status, ['pending', 'in_progress'])) {
            return false;
        }
        
        // Kiểm tra thời gian tạo (ít nhất 24h)
        if ($this->created_at->diffInHours(now()) < 24) {
            return false;
        }
        
        // Kiểm tra đã đọc chưa
        if (!$this->is_read) {
            return false;
        }
        
        return true;
    }

    /**
     * Lấy lý do không thể xóa
     */
    public function getDeleteRestrictionReason()
    {
        if ($this->status === 'pending') {
            return 'Liên hệ đang ở trạng thái chờ xử lý';
        }
        
        if ($this->status === 'in_progress') {
            return 'Liên hệ đang được xử lý';
        }
        
        if (!$this->is_read) {
            return 'Liên hệ chưa được đọc';
        }
        
        if ($this->created_at->diffInHours(now()) < 24) {
            return 'Liên hệ được tạo chưa đủ 24 giờ';
        }
        
        return null;
    }
}
