<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'body',
        'sender_id',
        'recipient_id',
        'priority',
        'status',
        'is_important',
        'is_broadcast',
        'read_at'
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'is_broadcast' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the sender of the message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient of the message
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Check if message is unread
     */
    public function isUnread(): bool
    {
        return $this->status === 'unread';
    }

    /**
     * Check if message is read
     */
    public function isRead(): bool
    {
        return $this->status === 'read';
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    /**
     * Mark message as unread
     */
    public function markAsUnread(): void
    {
        $this->update([
            'status' => 'unread',
            'read_at' => null
        ]);
    }

    /**
     * Archive message
     */
    public function archive(): void
    {
        $this->update(['status' => 'archived']);
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
     * Get formatted time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    /**
     * Scope for read messages
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    /**
     * Scope for important messages
     */
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    /**
     * Scope for messages by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for broadcast messages
     */
    public function scopeBroadcast($query)
    {
        return $query->where('is_broadcast', true);
    }
}
