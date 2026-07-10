<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpOrder extends Model
{
    protected $fillable = [
        'cp_id',
        'order_id',
        'products',
        'pricing_data',
        'order_notes',
        'status',
        'order_date',
        'subtotal',
        'gst_percentage',
        'gst_amount',
        'igst_amount',
        'total_tax',
        'grand_total',
        'quote_amount',
        'quote_date',
        'quote_validity_date',
        'quote_generated_by',
        'inQuoteSent',
    ];

    protected $casts = [
        'products' => 'json',
        'pricing_data' => 'json',
        'order_date' => 'date',
        'quote_date' => 'date',
        'quote_validity_date' => 'date',
    ];

    public function channelPartner()
    {
        return $this->belongsTo(ChannelPartner::class, 'cp_id');
    }

    public function quoteGeneratedBy()
    {
        return $this->belongsTo(User::class, 'quote_generated_by');
    }
}
