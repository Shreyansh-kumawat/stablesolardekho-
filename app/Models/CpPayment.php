<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpPayment extends Model
{
    protected $fillable = [
        'cp_id', 'cp_order_id', 'amount', 'payment_mode', 'reference_number',
        'screenshot', 'payment_date', 'status', 'verified_by', 'verified_at',
        'remarks', 'admin_notes', 'recorded_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function channelPartner()
    {
        return $this->belongsTo(ChannelPartner::class, 'cp_id');
    }

    public function cpOrder()
    {
        return $this->belongsTo(CpOrder::class, 'cp_order_id');
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function recordedByUser()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
