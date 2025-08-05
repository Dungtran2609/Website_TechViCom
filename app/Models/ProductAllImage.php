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
}
