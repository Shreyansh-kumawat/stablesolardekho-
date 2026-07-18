<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'mobile_number',
        'address',
        'state',
        'district',
        'city',
        'pincode',
        'cp_id',
        'is_active',
        'admin_permissions',
        'cp_permissions',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'admin_permissions' => 'array',
            'cp_permissions' => 'array',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function channelPartner()
    {
        return $this->belongsTo(ChannelPartner::class, 'cp_id');
    }

    public function customerOrders()
    {
        return $this->hasMany(\App\Models\CustomerOrder::class, 'user_id');
    }

    public function referralCode()
    {
        return $this->hasOne(ReferralCode::class);
    }

    public function referralLeads()
    {
        return $this->hasMany(ReferralLead::class, 'referrer_id');
    }

    public function cashbackTransactions()
    {
        return $this->hasMany(CashbackTransaction::class, 'referrer_id');
    }

    public function hasCpPermission(string $permission): bool
    {
        $perms = $this->cp_permissions ?? [];
        if (is_string($perms)) { $perms = json_decode($perms, true) ?? []; }
        return in_array($permission, $perms);
    }

    public function hasAdminPermission(string $permission): bool
    {
        if ($this->role_id === 1) return true;
        $perms = $this->admin_permissions ?? [];
        if (is_string($perms)) { $perms = json_decode($perms, true) ?? []; }
        if (in_array($permission, $perms)) return true;
        if (str_contains($permission, '.')) {
            $module = explode('.', $permission)[0];
            return in_array($module, $perms);
        }
        $subKeys = array_filter($perms, fn($p) => str_starts_with($p, $permission . '.'));
        return count($subKeys) > 0;
    }
}
