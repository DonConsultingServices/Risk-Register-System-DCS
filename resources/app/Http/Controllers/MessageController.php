<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');

        $query = Message::where('recipient_id', $user->id)
            ->with(['sender'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        switch ($filter) {
            case 'unread':
                $query->unread();
                break;
            case 'read':
                $query->read();
                break;
            case 'important':
                $query->important();
                break;
            case 'urgent':
                $query->byPriority('urgent');
                break;
        }

        // Apply search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhereHas('sender', function ($senderQuery) use ($search) {
                      $senderQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $messages = $query->paginate(15);

        // Get counts for filter tabs
        $counts = [
            'all' => Message::where('recipient_id', $user->id)->count(),
            'unread' => Message::where('recipient_id', $user->id)->unread()->count(),
            'read' => Message::where('recipient_id', $user->id)->read()->count(),
            'important' => Message::where('recipient_id', $user->id)->important()->count(),
            'urgent' => Message::where('recipient_id', $user->id)->byPriority('urgent')->count(),
        ];

        return view('messages.index', compact('messages', 'counts', 'filter', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('messages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $isBroadcast = $request->boolean('is_broadcast');
        
        $validator = Validator::make($request->all(), [
            'recipient_id' => $isBroadcast ? 'nullable' : 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_important' => 'boolean',
            'is_broadcast' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($isBroadcast) {
            // Handle broadcast message
            $recipients = User::where('is_active', true)
                ->where('id', '!=', Auth::id())
                ->get();

            $messages = [];
            $notifications = [];

            foreach ($recipients as $recipient) {
                // Create individual message for each recipient
                $message = Message::create([
                    'sender_id' => Auth::id(),
                    'recipient_id' => $recipient->id,
                    'subject' => '[BROADCAST] ' . $request->subject,
                    'body' => $request->body,
                    'priority' => $request->priority,
                    'is_important' => $request->boolean('is_important'),
                    'is_broadcast' => true,
                ]);

                $messages[] = $message;

                // Create notification for each recipient using NotificationService
                $priority = $request->priority === 'urgent' ? 'urgent' : ($request->boolean('is_important') ? 'high' : 'normal');
                NotificationService::createBroadcastNotification(
                    $recipient->id,
                    Auth::user()->name,
                    $request->subject,
                    $message->id
                );
            }


            return redirect()->route('messages.index')
                ->with('success', "Broadcast message sent successfully to {$recipients->count()} users!");
        } else {
            // Handle single recipient message
            $message = Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $request->recipient_id,
                'subject' => $request->subject,
                'body' => $request->body,
                'priority' => $request->priority,
                'is_important' => $request->boolean('is_important'),
                'is_broadcast' => false,
            ]);

                // Create notification for recipient using NotificationService
                $priority = $request->priority === 'urgent' ? 'urgent' : ($request->boolean('is_important') ? 'high' : 'normal');
                NotificationService::createMessageNotification(
                    $request->recipient_id,
                    Auth::user()->name,
                    $request->subject,
                    $message->id
                );

            return redirect()->route('messages.show', $message)
                ->with('success', 'Message sent successfully!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        // Ensure user can only view their own messages
        if ($message->recipient_id !== Auth::id() && $message->sender_id !== Auth::id()) {
            abort(403, 'Unauthorized access to message.');
        }

        // Mark as read if user is the recipient and message is unread
        if ($message->recipient_id === Auth::id() && $message->isUnread()) {
            $message->markAsRead();
            
            // Log the read action for debugging
            \Log::info('Message marked as read', [
                'message_id' => $message->id,
                'user_id' => Auth::id(),
                'timestamp' => now()
            ]);
        }

        $message->load(['sender', 'recipient']);

        return view('messages.show', compact('message'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        // Only allow editing if user is the sender and message is recent
        if ($message->sender_id !== Auth::id()) {
            abort(403, 'You can only edit your own messages.');
        }

        if ($message->created_at->diffInHours(now()) > 24) {
            return redirect()->route('messages.show', $message)
                ->with('error', 'Messages can only be edited within 24 hours of sending.');
        }

        $users = User::where('id', '!=', Auth::id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('messages.edit', compact('message', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        // Only allow updating if user is the sender
        if ($message->sender_id !== Auth::id()) {
            abort(403, 'You can only edit your own messages.');
        }

        if ($message->created_at->diffInHours(now()) > 24) {
            return redirect()->route('messages.show', $message)
                ->with('error', 'Messages can only be edited within 24 hours of sending.');
        }

        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_important' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $message->update([
            'recipient_id' => $request->recipient_id,
            'subject' => $request->subject,
            'body' => $request->body,
            'priority' => $request->priority,
            'is_important' => $request->boolean('is_important'),
        ]);

        return redirect()->route('messages.show', $message)
            ->with('success', 'Message updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        // Only allow deletion if user is sender or recipient
        if ($message->sender_id !== Auth::id() && $message->recipient_id !== Auth::id()) {
            abort(403, 'Unauthorized access to message.');
        }

        $message->delete();

        return redirect()->route('messages.index')
            ->with('success', 'Message deleted successfully!');
    }



    /**
     * Archive message
     */
    public function archive(Message $message)
    {
        if ($message->recipient_id !== Auth::id()) {
            abort(403, 'Unauthorized access to message.');
        }

        $message->archive();

        return response()->json(['success' => true]);
    }

    /**
     * Get unread message count for AJAX
     */
    public function getUnreadCount()
    {
        try {
            $count = Message::where('recipient_id', Auth::id())
                ->unread()
                ->count();

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            \Log::error('Error getting unread message count', ['error' => $e->getMessage()]);
            return response()->json(['count' => 0, 'error' => 'Failed to load message count']);
        }
    }

    /**
     * Mark message as read via AJAX
     */
    public function markAsRead(Request $request, Message $message)
    {
        try {
            // Ensure user can only mark their own messages as read
            if ($message->recipient_id !== Auth::id()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
            }

            if ($message->isUnread()) {
                $message->markAsRead();
                
                // Get updated unread count
                $unreadCount = Message::where('recipient_id', Auth::id())
                    ->unread()
                    ->count();
                
                return response()->json([
                    'success' => true, 
                    'unread_count' => $unreadCount,
                    'message' => 'Message marked as read'
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Message already read']);
        } catch (\Exception $e) {
            \Log::error('Error marking message as read', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'Failed to mark message as read']);
        }
    }

    /**
     * Mark message as unread via AJAX
     */
    public function markAsUnread(Request $request, Message $message)
    {
        try {
            // Ensure user can only mark their own messages as unread
            if ($message->recipient_id !== Auth::id()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
            }

            if ($message->isRead()) {
                $message->markAsUnread();
                
                // Get updated unread count
                $unreadCount = Message::where('recipient_id', Auth::id())
                    ->unread()
                    ->count();
                
                return response()->json([
                    'success' => true, 
                    'unread_count' => $unreadCount,
                    'message' => 'Message marked as unread'
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Message already unread']);
        } catch (\Exception $e) {
            \Log::error('Error marking message as unread', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'Failed to mark message as unread']);
        }
    }

    /**
     * Get recent messages for AJAX
     */
    public function getRecentMessages()
    {
        $messages = Message::where('recipient_id', Auth::id())
            ->with(['sender'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json(['messages' => $messages]);
    }
}
