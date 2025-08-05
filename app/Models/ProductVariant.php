<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'stock',
        'low_stock_amount',
        'image',
        'weight',
        'length',
        'width',
        'height',
        'is_active',
    ];


    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values');
    }
}
