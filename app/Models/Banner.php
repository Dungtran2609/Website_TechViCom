<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'stt', 'image', 'link', 'start_date', 'end_date'
    ];

    // Trạng thái hoạt động tính theo thời gian
    public function getStatusAttribute()
    {
        $now = now()->format('Y-m-d');
        $start = \Carbon\Carbon::parse($this->start_date)->format('Y-m-d');
        $end = \Carbon\Carbon::parse($this->end_date)->format('Y-m-d');

        if ($now < $start) {
            return 'Sắp diễn ra';
        } elseif ($now >= $start && $now <= $end) {
            return 'Hiện';
        } else {
            return 'Đã kết thúc';
        }
    }

    // Nếu cần, có thể thêm accessor cho start_date và end_date để chỉ lấy ngày
    public function getStartDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
    public function getEndDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
}