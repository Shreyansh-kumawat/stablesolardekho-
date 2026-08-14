<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'item_name', 'item_code', 'slug', 'description', 'image',
        'current_sale_price', 'quantity', 'uom', 'category_id', 'sub_category_id',
        'is_featured', 'is_active', 'is_kit',
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

    public function scopeInStock($query)
    {
        return $query->whereHas('inventory', fn($q) => $q->where('available_qty', '>', 0));
    }

    public function inventory()
    {
        return $this->hasOne(ProductInventory::class);
    }

    public function scopeActive($query)
    {
        $query = $query->where('is_active', true)->inStock();
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_kit')) {
            $query->where('is_kit', false);
        }
        return $query;
    }

    public function scopeFeatured($query)
    {
        $query = $query->where('is_featured', true)->where('is_active', true)->inStock();
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_kit')) {
            $query->where('is_kit', false);
        }
        return $query;
    }

    public function customSpecs()
    {
        return $this->hasMany(ProductCustomSpec::class)->orderBy('sort_order');
    }

    public function kitItems()
    {
        return $this->hasMany(KitItem::class)->orderBy('sort_order');
    }

    public function kitSlabPrices()
    {
        return $this->hasMany(KitSlabPrice::class)->orderBy('min_qty');
    }
}
