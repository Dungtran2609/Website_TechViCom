<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'image',
        'status',
    ];
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the category image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    /**
     * Get the category image with fallback
     */
    public function getDisplayImageAttribute()
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return $this->image_url;
        }
        return null;
    }

    /**
     * Check if category has image
     */
    public function hasImage()
    {
        return $this->image && file_exists(public_path('storage/' . $this->image));
    }
}
