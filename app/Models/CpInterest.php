<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpInterest extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'contact_person',
        'email',
        'mobile',
        'state',
        'city',
        'message',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
