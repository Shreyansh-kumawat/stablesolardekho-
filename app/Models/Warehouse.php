<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name', 'address', 'city', 'state', 'pin_code', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function managers()
    {
        return $this->belongsToMany(User::class, 'warehouse_managers')
                    ->withTimestamps();
    }

    public function inventories()
    {
        return $this->hasMany(WarehouseInventory::class);
    }

    public function transactions()
    {
        return $this->hasMany(WarehouseInventoryTransaction::class);
    }
}
