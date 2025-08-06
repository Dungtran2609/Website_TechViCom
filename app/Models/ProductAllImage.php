<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class ProductAllImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'sort_order',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }
    /**
     * Mối quan hệ với bảng ProductVariant (Biến thể sản phẩm mà hình ảnh này thuộc về)
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
