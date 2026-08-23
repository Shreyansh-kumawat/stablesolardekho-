<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseInventory extends Model
{
    protected $fillable = [
        'warehouse_id', 'product_id', 'available_qty',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
