<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpWalletTransaction extends Model
{
    protected $table = 'cp_wallet_transactions';

    protected $fillable = [
        'cp_id',
        'amount',
        'transaction_type',
        'opening_balance',
        'closing_balance',
        'txn_id',
        'source',
        'remarks',
        'status'
    ];

    public function wallet()
    {
        return $this->belongsTo(CpWallet::class, 'cp_id', 'cp_id');
    }

    public function channelPartner()
    {
        return $this->belongsTo(ChannelPartner::class, 'cp_id', 'id');
    }
}
