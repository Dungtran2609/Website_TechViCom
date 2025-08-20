<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'slug', 'description', 'flash_type', 'start_date', 'end_date', 'status'
    ];

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'promotion_category');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product')->withPivot('sale_price');
    }
}
