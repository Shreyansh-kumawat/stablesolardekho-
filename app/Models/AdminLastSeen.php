<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLastSeen extends Model
{
    public $timestamps = false;
    protected $table = 'admin_last_seen';
    protected $fillable = ['user_id', 'section', 'seen_at'];
    protected $casts = ['seen_at' => 'datetime'];

    public static function markSeen(int $userId, string $section): void
    {
        static::updateOrCreate(
            ['user_id' => $userId, 'section' => $section],
            ['seen_at' => now()]
        );
    }

    public static function getSeenAt(int $userId, string $section): ?string
    {
        $record = static::where('user_id', $userId)->where('section', $section)->first();
        return $record?->seen_at;
    }
}
