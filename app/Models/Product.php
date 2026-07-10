<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'item_name', 'item_code', 'slug', 'description', 'image',
        'current_sale_price', 'quantity', 'uom', 'category_id', 'sub_category_id',
        'is_featured', 'is_active',
        'type', 'brand', 'model', 'operating_voltage', 'solar_panel_type',
        'mnre_approved', 'certifications', 'manufacturer_warranty',
        'number_of_cells', 'encapsulate', 'country_of_origin',
        'input_voltage', 'max_supported_panel_power',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                do {
                    $slug = strtoupper(\Illuminate\Support\Str::random(3)) . rand(100, 999);
                } while (static::where('slug', $slug)->exists());
                $product->slug = $slug;
            }
        });
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(ProductSubCategory::class, 'sub_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }
}
