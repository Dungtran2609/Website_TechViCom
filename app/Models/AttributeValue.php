<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AttributeValue extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'attribute_id',
        'value',
        'color_code',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }


    public function productVariants()
    {
    return $this->belongsToMany(ProductVariant::class, 'product_variant_attribute_values');
    }
}


