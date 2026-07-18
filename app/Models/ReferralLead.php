<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralLead extends Model
{
    protected $guarded = [];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function cashback()
    {
        return $this->hasOne(CashbackTransaction::class);
    }
}
