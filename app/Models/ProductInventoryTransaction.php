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

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function channelPartner()
    {
        return $this->belongsTo(ChannelPartner::class, 'txn_done_from');
    }
    public function serialNumbers()
    {
        return $this->belongsTo(ProductSerial::class, 'serial_id');
    }
}
