<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    /**
     * Get the user that performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a user activity
     */
    public static function log($userId, $action, $description, $metadata = null, $ipAddress = null, $userAgent = null)
    {
        return static::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }

    /**
     * Get formatted time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get action icon
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'create_risk' => 'fas fa-exclamation-triangle',
            'update_risk' => 'fas fa-edit',
            'send_message' => 'fas fa-envelope',
            'create_client' => 'fas fa-user-plus',
            'update_client' => 'fas fa-user-edit',
            'create_notification' => 'fas fa-bell',
            'view_report' => 'fas fa-chart-bar',
            default => 'fas fa-circle'
        };
    }

    /**
     * Get action color
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'login' => 'text-success',
            'logout' => 'text-muted',
            'create_risk' => 'text-warning',
            'update_risk' => 'text-info',
            'send_message' => 'text-primary',
            'create_client' => 'text-success',
            'update_client' => 'text-info',
            'create_notification' => 'text-warning',
            'view_report' => 'text-primary',
            default => 'text-muted'
        };
    }
}
