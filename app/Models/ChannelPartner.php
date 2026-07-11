<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelPartner extends Model
{
    protected $fillable = [
        'cp_name', 'contact_person', 'email', 'phone_number',
        'full_address', 'city', 'state', 'zip_code', 'cp_role', 'is_active',
    ];

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
