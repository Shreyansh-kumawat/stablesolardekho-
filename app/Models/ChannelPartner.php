<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelPartner extends Model
{
    public function role()
    {
        return $this->belongsTo(ChannelPartnerRole::class, 'cp_role');
    }

    public function associateUsers()
    {
        return $this->hasMany(User::class, 'cp_id');
    }

    public function wallet()
    {
        return $this->hasOne(CpWallet::class, 'cp_id');
    }
}
