<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class NewsComment extends Model
{
    use HasFactory;


    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'news_id',
        'content',
        'is_hidden',
        'parent_id',
        'like_count', // Thêm vào đây
    ];



    /**
     * Giá trị mặc định cho các trường
     *
     * @var array
     */
    protected $attributes = [
        'is_hidden' => false,
    ];


    /**
     * Mối quan hệ với bảng User (Người dùng đã viết bình luận)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Mối quan hệ với bảng News (Bài viết mà bình luận này thuộc về)
     */
    public function news()
    {
        return $this->belongsTo(\App\Models\News::class, 'news_id', 'id');
    }


    /**
     * Lấy bình luận cha (nếu có).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(NewsComment::class, 'parent_id');
    }


    /**
     * Lấy các bình luận con (trả lời).
     */
    public function children(): HasMany
    {
        return $this->hasMany(NewsComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(NewsComment::class, 'parent_id')->where('is_hidden', 1)->latest();
    }

    public function visibleChildren(): HasMany
    {
        return $this->hasMany(NewsComment::class, 'parent_id')->where('is_hidden', 0);
    }

}
