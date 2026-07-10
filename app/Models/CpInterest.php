<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpInterest extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'mobile',
        'state',
        'city',
        'message',
        'status',
    ];
}
