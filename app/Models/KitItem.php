<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    protected $fillable = ['product_id', 'category_label', 'item_name', 'quantity_label', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
