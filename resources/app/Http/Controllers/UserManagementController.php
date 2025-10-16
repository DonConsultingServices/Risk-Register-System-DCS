<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status', 'all');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'asc');

        $query = User::query();

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        // Apply status filter
        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'inactive') {
            $query->inactive();
        }

        // Apply sorting
        $query->orderBy($sort, $order);

        $users = $query->paginate(15);

        // Get counts for filters
        $counts = [
            'all' => User::count(),
            'active' => User::active()->count(),
            'inactive' => User::inactive()->count(),
            'admin' => User::where('role', 'admin')->count(),
            'manager' => User::where('role', 'manager')->count(),
            'staff' => User::where('role', 'staff')->count(),
        ];

        return view('users.index', compact('users', 'counts', 'search', 'role', 'status', 'sort', 'order'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,staff',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
            'password_changed_at' => now()
        ]);

        // Log activity
        ActivityLog::log(
            Auth::id(),
            'create_user',
            "Created new user: {$user->name}",
            ['user_id' => $user->id, 'role' => $user->role],
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('dashboard')
                ->with('error', 'User not found!');
        }
        
        // Staff can only view their own profile
        $currentUser = Auth::user();
        if ($currentUser->isStaff() && $currentUser->id != $user->id) {
            abort(403, 'Access denied. You can only view your own profile.');
        }
        
        $recentActivities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('users.show', compact('user', 'recentActivities'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('dashboard')
                ->with('error', 'User not found!');
        }
        
        // Staff can only edit their own profile
        $currentUser = Auth::user();
        if ($currentUser->isStaff() && $currentUser->id != $user->id) {
            abort(403, 'Access denied. You can only edit your own profile.');
        }
        
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('dashboard')
                ->with('error', 'User not found!');
        }
        
        // Staff can only update their own profile
        $currentUser = Auth::user();
        if ($currentUser->isStaff() && $currentUser->id != $user->id) {
            abort(403, 'Access denied. You can only edit your own profile.');
        }
        
        // Staff users cannot change their own role or active status
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ];
        
        // Only admins and managers can change roles and status
        if ($currentUser->isManagerOrAdmin()) {
            $validationRules['role'] = 'required|in:admin,manager,staff';
            $validationRules['is_active'] = 'boolean';
        }
        
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldData = $user->toArray();
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        // Only admins and managers can update role and status
        if ($currentUser->isManagerOrAdmin()) {
            $updateData['role'] = $request->role;
            $updateData['is_active'] = $request->boolean('is_active', true);
        }
        
        $user->update($updateData);

        // Log activity
        ActivityLog::log(
            Auth::id(),
            'update_user',
            "Updated user: {$user->name}",
            ['user_id' => $user->id, 'changes' => array_diff_assoc($user->toArray(), $oldData)],
            $request->ip(),
            $request->userAgent()
        );

        // Redirect based on user role
        $redirectRoute = $currentUser->isManagerOrAdmin() ? 'users.index' : 'dashboard';
        return redirect()->route($redirectRoute)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy($userId)
    {
        // Find the user manually to handle 404 properly
        $user = User::find($userId);
        
        if (!$user) {
            \Log::error('User not found for deletion', [
                'requested_id' => $userId,
                'auth_id' => Auth::id(),
                'request_method' => request()->method(),
                'request_url' => request()->url()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            return redirect()->route('users.index')
                ->with('error', 'User not found!');
        }
        
        \Log::info('User deletion attempt', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'auth_id' => Auth::id(),
            'request_method' => request()->method(),
            'request_url' => request()->url()
        ]);

        try {
            // Prevent deleting own account
            if ($user->id === Auth::id()) {
                \Log::warning('User tried to delete own account', ['user_id' => $user->id]);
                return redirect()->back()
                    ->with('error', 'You cannot delete your own account!');
            }

            $userName = $user->name;
            $userId = $user->id;
            
            \Log::info('Proceeding with user deletion', ['user_id' => $userId, 'user_name' => $userName]);
            
            // Log activity before deletion
            ActivityLog::log(
                Auth::id(),
                'delete_user',
                "Deleted user: {$userName}",
                ['deleted_user_id' => $userId],
                request()->ip(),
                request()->userAgent()
            );
            
            $user->delete();
            
            \Log::info('User deleted successfully', ['user_id' => $userId, 'user_name' => $userName]);

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('User deletion failed', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found!'], 404);
        }
        
        // Prevent deactivating own account
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'You cannot deactivate your own account!'], 403);
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        // Log activity
        ActivityLog::log(
            Auth::id(),
            'toggle_user_status',
            "{$status} user: {$user->name}",
            ['user_id' => $user->id, 'is_active' => $user->is_active],
            request()->ip(),
            request()->userAgent()
        );

        return response()->json([
            'success' => true,
            'message' => "User {$status} successfully!",
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Search users for message recipient selection
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        $excludeCurrentUser = $request->get('exclude_current', true);

        $query = User::active();

        if ($excludeCurrentUser) {
            $query->where('id', '!=', Auth::id());
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->select('id', 'name', 'email', 'role')
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'role_display' => $user->role_display_name,
                    'text' => "{$user->name} ({$user->email})"
                ];
            });

        return response()->json($users);
    }

    /**
     * Get user details for message recipient
     */
    public function getUserDetails($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found!'], 404);
        }
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'role_display' => $user->role_display_name,
            'is_active' => $user->is_active,
            'last_login' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never'
        ]);
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found!'], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now()
        ]);

        // Log activity
        ActivityLog::log(
            Auth::id(),
            'reset_user_password',
            "Reset password for user: {$user->name}",
            ['user_id' => $user->id],
            $request->ip(),
            $request->userAgent()
        );

        return response()->json(['success' => true, 'message' => 'Password reset successfully!']);
    }
}