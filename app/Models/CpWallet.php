<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpWallet extends Model
{
    protected $table = 'cp_wallets';

    protected $fillable = [
        'cp_id',
        'balance',
    ];

    public function transactions()
    {
        return $this->hasMany(CpWalletTransaction::class, 'cp_id', 'cp_id');
    }
}
