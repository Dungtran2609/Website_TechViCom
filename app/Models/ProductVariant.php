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

   
   
   
   

    public function attributes()
    {
        return $this->hasMany(ProductVariantAttributeValue::class);
    }

    public function attributesValue()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variants_attributes',
            'product_variant_id',
            'attribute_value_id'
        )->withTimestamps()->with('attribute'); // thêm để lấy tên thuộc tính gốc (Color, Size)
    }
    public function images()
    {
        // FK trên product_all_images là product_id, local key bên variant cũng là product_id
        return $this->hasMany(
            \App\Models\ProductAllImage::class,
            'product_id',   // foreign key trên product_all_images
            'product_id'    // local key trên product_variants
        )
            ->orderBy('sort_order')  // ưu tiên ảnh có sort_order nhỏ
            ->limit(1);              // chỉ load 1 ảnh “chính”
    }
}
