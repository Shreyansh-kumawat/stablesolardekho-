<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashbackTransaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function lead()
    {
        return $this->belongsTo(ReferralLead::class, 'referral_lead_id');
    }
}
