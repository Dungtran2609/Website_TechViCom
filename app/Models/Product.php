<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;


class Product extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'slug',
        'type',
        'short_description',
        'long_description',
        'description',
        'thumbnail',
        'status',
        'brand_id',
        'category_id',
        'is_featured',
        'view_count',
        'price',
        'compare_price',
        'sku',
    ];


    protected $casts = [
        'is_featured' => 'boolean',
    ];


    protected $appends = [
        'price_range',
        'total_stock',
    ];


    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }


    public function allImages()
    {
        return $this->hasMany(ProductAllImage::class)->orderBy('sort_order');
    }

    public function productAllImages()
    {
        return $this->hasMany(ProductAllImage::class)->orderBy('sort_order');
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productComments()
    {
        return $this->hasMany(ProductComment::class);
    }

    public function images()
    {
        return $this->hasMany(
            \App\Models\ProductAllImage::class,
            'product_id', // FK trên product_all_images
            'id'          // PK của product
        )
            ->orderBy('sort_order');
    }
    public function getPriceRangeAttribute(): string
    {
        if ($this->variants->isEmpty()) {
            return 'Chưa có giá';
        }


        if ($this->type === 'simple') {
            return Number::format($this->variants->first()->price) . ' đ';
        }


        $minPrice = $this->variants->min('price');
        $maxPrice = $this->variants->max('price');


        if ($minPrice === $maxPrice) {
            return Number::format($minPrice) . ' đ';
        }


        return Number::format($minPrice) . ' - ' . Number::format($maxPrice) . ' đ';
    }


    public function getTotalStockAttribute(): int
    {
        return $this->variants->sum('stock');
    }


 public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }

    public function getDisplayPriceAttribute()
    {
        if ($this->type === 'simple') {
            return $this->sale_price && $this->sale_price < $this->price
                ? $this->sale_price
                : $this->price;
        }
        if ($this->variants->count()) {
            $min = $this->variants->min('price');
            $max = $this->variants->max('price');
            return ($min && $max && $min != $max)
                ? ['min' => $min, 'max' => $max]
                : $min;
        }
        return null;
    }
    
}
