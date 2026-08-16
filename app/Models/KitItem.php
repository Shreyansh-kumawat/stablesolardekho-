<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    protected $fillable = ['product_id', 'category_id', 'component_product_id', 'category_label', 'item_name', 'quantity_label', 'quantity', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function componentProduct()
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
