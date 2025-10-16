<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'page_url',
        'ip_address',
        'user_agent',
        'last_seen_at',
        'is_online'
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'is_online' => 'boolean',
    ];

    /**
     * Get the user that owns the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user is currently online (active within last 5 minutes)
     */
    public function isCurrentlyOnline(): bool
    {
        return $this->is_online && $this->last_seen_at->isAfter(now()->subMinutes(5));
    }

    /**
     * Get online status text
     */
    public function getOnlineStatusText(): string
    {
        if ($this->isCurrentlyOnline()) {
            return 'Online';
        } elseif ($this->last_seen_at->isAfter(now()->subMinutes(30))) {
            return 'Recently online';
        } elseif ($this->last_seen_at->isAfter(now()->subHour())) {
            return 'Away';
        } elseif ($this->last_seen_at->isAfter(now()->subDay())) {
            return 'Offline';
        } else {
            return 'Offline';
        }
    }

    /**
     * Get online status color
     */
    public function getOnlineStatusColor(): string
    {
        if ($this->isCurrentlyOnline()) {
            return '#28a745'; // Green
        } elseif ($this->last_seen_at->isAfter(now()->subMinutes(30))) {
            return '#ffc107'; // Yellow
        } else {
            return '#6c757d'; // Gray
        }
    }

    /**
     * Update user activity
     */
    public static function updateActivity($userId, $activityType = 'page_view', $pageUrl = null)
    {
        $activity = self::where('user_id', $userId)->first();
        
        if ($activity) {
            $activity->update([
                'activity_type' => $activityType,
                'page_url' => $pageUrl,
                'last_seen_at' => now(),
                'is_online' => true,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } else {
            self::create([
                'user_id' => $userId,
                'activity_type' => $activityType,
                'page_url' => $pageUrl,
                'last_seen_at' => now(),
                'is_online' => true,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    /**
     * Mark user as offline
     */
    public static function markOffline($userId)
    {
        self::where('user_id', $userId)->update([
            'is_online' => false,
            'last_seen_at' => now(),
        ]);
    }

    /**
     * Get online users
     */
    public static function getOnlineUsers()
    {
        return self::where('is_online', true)
            ->where('last_seen_at', '>', now()->subMinutes(5))
            ->with('user')
            ->get();
    }

    /**
     * Get user's last activity
     */
    public static function getUserLastActivity($userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('last_seen_at', 'desc')
            ->first();
    }
}
