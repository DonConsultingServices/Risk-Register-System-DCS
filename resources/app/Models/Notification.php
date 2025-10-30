<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read',
        'read_at',
        'priority',
        'action_url'
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(): void
    {
        $this->update([
            'read' => false,
            'read_at' => null
        ]);
    }

    /**
     * Check if notification is unread
     */
    public function isUnread(): bool
    {
        return !$this->read;
    }

    /**
     * Get priority color class
     */
    public function getPriorityColorClass(): string
    {
        return match($this->priority) {
            'urgent' => 'text-danger',
            'high' => 'text-warning',
            'normal' => 'text-primary',
            'low' => 'text-muted',
            default => 'text-primary'
        };
    }

    /**
     * Get priority badge class
     */
    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            'urgent' => 'badge-danger',
            'high' => 'badge-warning',
            'normal' => 'badge-primary',
            'low' => 'badge-secondary',
            default => 'badge-primary'
        };
    }

    /**
     * Get icon for notification type
     */
    public function getIconClass(): string
    {
        return match($this->type) {
            'message' => 'fas fa-envelope',
            'risk' => 'fas fa-exclamation-triangle',
            'client' => 'fas fa-user',
            'system' => 'fas fa-cog',
            'approval' => 'fas fa-clipboard-check',
            'report' => 'fas fa-chart-bar',
            default => 'fas fa-bell'
        };
    }

    /**
     * Get formatted time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('read', true);
    }

    /**
     * Scope for notifications by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for notifications by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Create a new notification
     */
    public static function createNotification($userId, $type, $title, $message, $priority = 'normal', $actionUrl = null, $data = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'priority' => $priority,
            'action_url' => $actionUrl,
            'data' => $data
        ]);
    }
}
