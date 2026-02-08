<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventoryTransaction extends Model
{
    protected $fillable = [
        'product_id',
        'serial_id',
        'transaction_type',
        'quantity',
        'txn_done_from',
        'unit_price',
        'invoice_number',
        'invoice_date',
        'performed_by',
        'txn_id',
        'remarks',
    ];
}
