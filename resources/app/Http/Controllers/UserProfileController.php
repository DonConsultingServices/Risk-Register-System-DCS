<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserPreference;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();
        $preferences = UserPreference::getForUser($user->id);
        $recentActivities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'user' => $user,
            'preferences' => $preferences,
            'recent_activities' => $recentActivities
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Log activity
        ActivityLog::log(
            $user->id,
            'update_profile',
            'Updated profile information',
            ['name' => $request->name, 'email' => $request->email],
            $request->ip(),
            $request->userAgent()
        );

        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['errors' => ['current_password' => ['Current password is incorrect']]], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Log activity
        ActivityLog::log(
            $user->id,
            'change_password',
            'Changed password',
            null,
            $request->ip(),
            $request->userAgent()
        );

        return response()->json(['success' => true, 'message' => 'Password changed successfully']);
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'theme' => 'in:light,dark,auto',
            'language' => 'string|max:10',
            'timezone' => 'string|max:50',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'two_factor_enabled' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $preferences = UserPreference::getForUser($user->id);
        $preferences->updatePreferences($request->all());

        // Log activity
        ActivityLog::log(
            $user->id,
            'update_preferences',
            'Updated user preferences',
            $request->all(),
            $request->ip(),
            $request->userAgent()
        );

        return response()->json(['success' => true, 'message' => 'Preferences updated successfully']);
    }

    /**
     * Get user activity log
     */
    public function getActivityLog(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 20);
        
        $activities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($activities);
    }

    /**
     * Toggle two-factor authentication
     */
    public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();
        $preferences = UserPreference::getForUser($user->id);
        
        $preferences->update([
            'two_factor_enabled' => !$preferences->two_factor_enabled
        ]);

        $status = $preferences->two_factor_enabled ? 'enabled' : 'disabled';

        // Log activity
        ActivityLog::log(
            $user->id,
            'toggle_2fa',
            "Two-factor authentication {$status}",
            ['enabled' => $preferences->two_factor_enabled],
            $request->ip(),
            $request->userAgent()
        );

        return response()->json([
            'success' => true, 
            'message' => "Two-factor authentication {$status}",
            'enabled' => $preferences->two_factor_enabled
        ]);
    }

    /**
     * Get system information
     */
    public function getSystemInfo()
    {
        return response()->json([
            'app_name' => config('app.name'),
            'app_version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_time' => now()->toISOString(),
            'timezone' => config('app.timezone'),
        ]);
    }

    /**
     * Get help and support information
     */
    public function getHelpInfo()
    {
        return response()->json([
            'support_email' => 'support@dcs-best.com',
            'documentation_url' => '/docs',
            'video_tutorials' => '/tutorials',
            'faq_url' => '/faq',
            'contact_phone' => '+1-800-DCS-BEST',
            'business_hours' => 'Monday - Friday, 9:00 AM - 6:00 PM EST'
        ]);
    }

    /**
     * Update user activity
     */
    public function updateActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_type' => 'required|string|max:255',
            'page_url' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        UserActivity::updateActivity(
            Auth::id(),
            $request->activity_type,
            $request->page_url
        );

        return response()->json(['success' => true]);
    }

    /**
     * Mark user as offline
     */
    public function markOffline()
    {
        UserActivity::markOffline(Auth::id());
        return response()->json(['success' => true]);
    }

    /**
     * Get user online status
     */
    public function getUserStatus(User $user)
    {
        $activity = UserActivity::getUserLastActivity($user->id);
        
        if (!$activity) {
            return response()->json([
                'is_online' => false,
                'status_text' => 'Offline',
                'color' => '#6c757d',
                'last_seen' => null
            ]);
        }

        return response()->json([
            'is_online' => $activity->isCurrentlyOnline(),
            'status_text' => $activity->getOnlineStatusText(),
            'color' => $activity->getOnlineStatusColor(),
            'last_seen' => $activity->last_seen_at
        ]);
    }

    /**
     * Get all online users
     */
    public function getOnlineUsers()
    {
        $onlineUsers = UserActivity::getOnlineUsers();
        
        $users = $onlineUsers->map(function ($activity) {
            return [
                'id' => $activity->user->id,
                'name' => $activity->user->name,
                'email' => $activity->user->email,
                'is_online' => $activity->isCurrentlyOnline(),
                'status_text' => $activity->getOnlineStatusText(),
                'status_color' => $activity->getOnlineStatusColor(),
                'last_seen' => $activity->last_seen_at
            ];
        });

        return response()->json(['users' => $users]);
    }
}