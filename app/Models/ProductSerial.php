<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    protected $fillable = [
        'product_id',
        'serial_number',
        'status',
        'current_location',
        'issue_to',
        'warehouse_id',
        'customer_order_id',
        'batch_txn_id',
        'purchase_price',
        'invoice_number',
        'invoice_date',
        'supplier_name',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }
}
