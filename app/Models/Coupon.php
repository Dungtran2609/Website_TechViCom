<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'discount_type', 'apply_type', 'value', 'max_discount_amount',
        'min_order_value', 'max_order_value', 'max_usage_per_user',
        'start_date', 'end_date', 'status', 'promotion_id'
    ];

    protected $casts = [
        'apply_type' => 'string',
    ];

    protected $dates = ['start_date', 'end_date', 'deleted_at'];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_category');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user');
    }
}
