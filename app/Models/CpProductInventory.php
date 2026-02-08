<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpProductInventory extends Model
{
     protected $fillable = [
        'cp_id',
        'product_id',
        'available_qty',
    ];
}
