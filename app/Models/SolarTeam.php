<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolarTeam extends Model
{
    protected $fillable = [
        'name', 'position', 'mobile_number', 'address',
        'district', 'state', 'profile_photo',
        'status', 'sort_order', 'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];
}
