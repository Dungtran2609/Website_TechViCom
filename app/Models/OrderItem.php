<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'variant_id',
        'product_id',
        'name_product',
        'image_product',
        'quantity',
        'price',
        'total_price',
    ];

    /**
     * Mối quan hệ với đơn hàng
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mối quan hệ với biến thể sản phẩm
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Mối quan hệ với sản phẩm gốc
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mối quan hệ với hình ảnh sản phẩm qua biến thể
     */
    public function images()
    {
        return $this->hasManyThrough(
            ProductAllImage::class,
            ProductVariant::class,
            'id',            // localKey của ProductVariant
            'variant_id',    // foreignKey trong ProductAllImage
            'variant_id',    // foreignKey trong OrderItem
            'id'             // localKey của ProductVariant
        );
    }

    /**
     * Mối quan hệ với attribute values qua product variant
     */
    public function attributeValues()
    {
        return $this->hasManyThrough(
            AttributeValue::class,
            ProductVariant::class,
            'id',                    // localKey của ProductVariant
            'variant_id',            // foreignKey trong ProductVariantAttributeValue
            'variant_id',            // foreignKey trong OrderItem
            'id'                     // localKey của ProductVariant
        )->join('product_variant_attribute_values', 'attribute_values.id', '=', 'product_variant_attribute_values.attribute_value_id');
    }
}
