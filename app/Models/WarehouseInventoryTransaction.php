<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseInventoryTransaction extends Model
{
    protected $fillable = [
        'warehouse_id', 'product_id', 'serial_id', 'transaction_type',
        'quantity', 'transfer_type', 'transfer_to', 'unit_price',
        'invoice_number', 'invoice_date', 'performed_by', 'txn_id', 'remarks',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function serial()
    {
        return $this->belongsTo(ProductSerial::class, 'serial_id');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
