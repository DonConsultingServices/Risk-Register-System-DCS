<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get all notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');
        $limit = $request->get('limit', 20);

        $query = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Apply filters
        switch ($filter) {
            case 'unread':
                $query->unread();
                break;
            case 'read':
                $query->read();
                break;
            case 'urgent':
                $query->byPriority('urgent');
                break;
            case 'high':
                $query->byPriority('high');
                break;
        }

        $notifications = $query->limit($limit)->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::where('user_id', $user->id)->unread()->count()
        ]);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to notification.');
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to notification.');
        }

        $notification->markAsUnread();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to notification.');
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Clear all notifications
     */
    public function clearAll()
    {
        Notification::where('user_id', Auth::id())->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Get recent notifications for dropdown
     */
    public function getRecent()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json(['notifications' => $notifications]);
    }
}