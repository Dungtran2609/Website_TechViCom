<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'discount_type', 'value', 'max_discount_amount',
        'min_order_value', 'max_order_value', 'max_usage_per_user',
        'start_date', 'end_date', 'status',
    ];

    protected $dates = ['start_date', 'end_date', 'deleted_at'];

}
