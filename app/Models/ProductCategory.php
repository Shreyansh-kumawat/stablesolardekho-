<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['category_name', 'slug', 'category_description', 'image', 'active_status'];

    protected static function booted(): void
    {
        static::saving(function ($cat) {
            if (empty($cat->slug) || $cat->isDirty('category_name')) {
                $base = \Illuminate\Support\Str::slug($cat->category_name);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $cat->id ?? 0)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $cat->slug = $slug;
            }
        });
    }

    // Accessor so views can use ->name or ->category_name interchangeably
    public function getNameAttribute(): string
    {
        return $this->attributes['category_name'] ?? '';
    }

    public function getDescriptionAttribute(): string
    {
        return $this->attributes['category_description'] ?? '';
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function subCategories()
    {
        return $this->hasMany(ProductSubCategory::class, 'category_id');
    }
}
