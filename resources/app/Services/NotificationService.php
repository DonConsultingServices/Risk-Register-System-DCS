<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification
     */
    public static function createNotification($userId, $type, $title, $message, $priority = 'normal', $actionUrl = null, $data = null)
    {
        try {
            $notification = Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'priority' => $priority,
                'action_url' => $actionUrl,
                'data' => $data,
                'read' => false,
            ]);

            // Trigger real-time notification
            self::triggerRealtimeNotification($notification);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create notification', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Create notification for multiple users
     */
    public static function createBulkNotification($userIds, $type, $title, $message, $priority = 'normal', $actionUrl = null, $data = null)
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $notification = self::createNotification($userId, $type, $title, $message, $priority, $actionUrl, $data);
            if ($notification) {
                $notifications[] = $notification;
            }
        }

        return $notifications;
    }

    /**
     * Create message notification
     */
    public static function createMessageNotification($recipientId, $senderName, $subject, $messageId)
    {
        return self::createNotification(
            $recipientId,
            'message',
            'New Message from ' . $senderName,
            'You have received a new message: ' . $subject,
            'normal',
            route('messages.show', $messageId),
            [
                'sender_name' => $senderName,
                'message_id' => $messageId,
                'subject' => $subject
            ]
        );
    }

    /**
     * Create broadcast message notification
     */
    public static function createBroadcastNotification($recipientId, $senderName, $subject, $messageId)
    {
        return self::createNotification(
            $recipientId,
            'broadcast',
            'Broadcast Message from ' . $senderName,
            'You have received a broadcast message: ' . $subject,
            'high',
            route('messages.show', $messageId),
            [
                'sender_name' => $senderName,
                'message_id' => $messageId,
                'subject' => $subject,
                'is_broadcast' => true
            ]
        );
    }

    /**
     * Create risk notification
     */
    public static function createRiskNotification($userId, $type, $title, $message, $riskId = null)
    {
        $actionUrl = $riskId ? route('risks.show', $riskId) : route('risks.index');
        
        return self::createNotification(
            $userId,
            'risk',
            $title,
            $message,
            'high',
            $actionUrl,
            [
                'risk_id' => $riskId,
                'type' => $type
            ]
        );
    }

    /**
     * Create system notification
     */
    public static function createSystemNotification($userId, $title, $message, $priority = 'normal')
    {
        return self::createNotification(
            $userId,
            'system',
            $title,
            $message,
            $priority,
            null,
            [
                'system_generated' => true
            ]
        );
    }

    /**
     * Create approval notification
     */
    public static function createApprovalNotification($userId, $type, $title, $message, $itemId = null)
    {
        $actionUrl = null;
        if ($itemId) {
            $actionUrl = $type === 'risk' ? route('risks.show', $itemId) : route('clients.show', $itemId);
        }

        return self::createNotification(
            $userId,
            'approval',
            $title,
            $message,
            'urgent',
            $actionUrl,
            [
                'item_id' => $itemId,
                'type' => $type
            ]
        );
    }

    /**
     * Trigger real-time notification
     */
    private static function triggerRealtimeNotification($notification)
    {
        // This will be handled by JavaScript for real-time updates
        // The notification is already created in the database
        // JavaScript will poll for updates or use WebSockets in the future
    }

    /**
     * Get notification sound based on priority
     */
    public static function getNotificationSound($priority)
    {
        return match($priority) {
            'urgent' => 'urgent-notification.mp3',
            'high' => 'high-notification.mp3',
            'normal' => 'normal-notification.mp3',
            'low' => 'low-notification.mp3',
            default => 'normal-notification.mp3'
        };
    }

    /**
     * Get notification icon based on type
     */
    public static function getNotificationIcon($type)
    {
        return match($type) {
            'message' => 'fas fa-envelope',
            'broadcast' => 'fas fa-bullhorn',
            'risk' => 'fas fa-exclamation-triangle',
            'approval' => 'fas fa-check-circle',
            'system' => 'fas fa-cog',
            'client' => 'fas fa-user',
            default => 'fas fa-bell'
        };
    }

    /**
     * Get notification color based on priority
     */
    public static function getNotificationColor($priority)
    {
        return match($priority) {
            'urgent' => '#dc3545',
            'high' => '#fd7e14',
            'normal' => '#0d6efd',
            'low' => '#6c757d',
            default => '#0d6efd'
        };
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead($notificationId, $userId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for user
     */
    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->update([
                'read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * Get unread count for user
     */
    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->count();
    }

    /**
     * Get recent notifications for user
     */
    public static function getRecentNotifications($userId, $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Clean up old notifications (older than 30 days)
     */
    public static function cleanupOldNotifications()
    {
        $deleted = Notification::where('created_at', '<', now()->subDays(30))->delete();
        Log::info("Cleaned up {$deleted} old notifications");
        return $deleted;
    }
}
