<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpProductInventoryTransaction extends Model
{
      protected $fillable = [
        'cp_id',
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
