<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomSpec extends Model
{
    protected $fillable = ['product_id', 'spec_name', 'spec_value', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
