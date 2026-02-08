<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function Category()
    {
    return $this->belongsTo(ProductCategory::class, 'category_id');
    }

     public function subCategory()
    {
    return $this->belongsTo(ProductSubCategory::class, 'sub_category_id');
    }
}
