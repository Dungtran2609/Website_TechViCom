<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class NewsCategory extends Model
{
    use HasFactory;


    protected $table = 'news_categories';  // tên bảng


    protected $primaryKey = 'id'; // khoá chính


    public $incrementing = true;


    protected $keyType = 'int'; // kiểu khoá chính


    protected $fillable = ['name', 'slug'];


    /**
     * Một danh mục có nhiều bài viết (news).
     */
    public function news()
    {
    return $this->hasMany(News::class, 'category_id', 'id'); // Không cần sửa, đã đúng
    }
}
