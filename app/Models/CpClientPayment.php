<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpClientPayment extends Model
{
    protected $fillable = [
        'batch_id', 'cp_id', 'amount', 'payment_date', 'remarks', 'added_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function channelPartner()
    {
        return $this->belongsTo(ChannelPartner::class, 'cp_id');
    }

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
