<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'theme',
        'language',
        'timezone',
        'email_notifications',
        'push_notifications',
        'two_factor_enabled',
        'notification_preferences',
        'ui_preferences'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'notification_preferences' => 'array',
        'ui_preferences' => 'array'
    ];

    /**
     * Get the user that owns the preferences
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create preferences for a user
     */
    public static function getForUser($userId)
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            [
                'theme' => 'light',
                'language' => 'en',
                'timezone' => 'UTC',
                'email_notifications' => true,
                'push_notifications' => true,
                'two_factor_enabled' => false
            ]
        );
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(array $preferences)
    {
        $allowedFields = [
            'theme', 'language', 'timezone', 'email_notifications',
            'push_notifications', 'two_factor_enabled', 'notification_preferences', 'ui_preferences'
        ];

        $updateData = array_intersect_key($preferences, array_flip($allowedFields));
        $this->update($updateData);
    }
}
