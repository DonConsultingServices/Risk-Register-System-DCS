<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardUpdateController;
use App\Http\Controllers\RiskCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RiskApprovalController;
use App\Http\Controllers\ClientRiskAssessmentController;
use App\Http\Controllers\ClientAssessmentApprovalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// CSRF token refresh route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->name('csrf.token');

// Password reset routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Dashboard Performance Routes
    Route::get('/dashboard/updates', [DashboardController::class, 'getUpdates'])->name('dashboard.updates');
    Route::post('/dashboard/clear-cache', [DashboardController::class, 'clearCache'])->name('dashboard.clear-cache');
    Route::get('/dashboard/performance-metrics', [DashboardController::class, 'getPerformanceMetrics'])->name('dashboard.performance-metrics');
    
    // Real-time Dashboard Updates
    Route::get('/dashboard/real-time-stats', [DashboardUpdateController::class, 'getRealTimeStats'])->name('dashboard.real-time-stats');
    Route::get('/dashboard/live-metrics', [DashboardUpdateController::class, 'getLiveMetrics'])->name('dashboard.live-metrics');
    Route::post('/dashboard/clear-all-caches', [DashboardUpdateController::class, 'clearAllCaches'])->name('dashboard.clear-all-caches');
    
    // Client Management - View only, creation redirects to risk assessment
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    
    // Bulk operations must come before parameterized routes
    Route::post('/clients/bulk-delete', [ClientController::class, 'bulkDelete'])->name('clients.bulk-delete');
    Route::post('/clients/bulk-export', [ClientController::class, 'bulkExport'])->name('clients.bulk-export');
    
    // Parameterized routes
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/modal', [ClientController::class, 'modalDetails'])->name('clients.modal');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/clients/{client}/export', [ClientController::class, 'export'])->name('clients.export');
    Route::get('/clients/{client}/risk-analysis', [ClientController::class, 'riskAnalysis'])->name('clients.risk-analysis');
    Route::post('/clients/update-risk-names', [ClientController::class, 'updateRiskNames'])->name('clients.update-risk-names');
    
    // Risk Approval System - MUST come before resource routes to avoid conflicts
    Route::prefix('risks')->name('risks.')->group(function () {
        Route::get('/approval', [RiskApprovalController::class, 'index'])->name('approval.index');
        Route::get('/approval/{risk}', [RiskApprovalController::class, 'show'])->name('approval.show');
        Route::post('/approval/{risk}/approve', [RiskApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('/approval/{risk}/reject', [RiskApprovalController::class, 'reject'])->name('approval.reject');
        Route::post('/approval/bulk-approve', [RiskApprovalController::class, 'bulkApprove'])->name('approval.bulk-approve');
        Route::get('/approval/stats', [RiskApprovalController::class, 'stats'])->name('approval.stats');
    });
    
    // Risk Management
    Route::resource('risks', RiskController::class);
    Route::post('/risks/bulk-action', [RiskController::class, 'bulkAction'])->name('risks.bulk-action');
    Route::get('/risks/{risk}/export', [RiskController::class, 'export'])->name('risks.export');
    Route::post('/risks/bulk-export', [RiskController::class, 'bulkExport'])->name('risks.bulk-export');
    Route::get('/risks-export/csv', [RiskController::class, 'exportCsv'])->name('risks.export.csv');
    
    
    // Risk Categories (Manager/Admin only)
    Route::middleware('role:manager,admin')->group(function () {
        Route::resource('risk-categories', RiskCategoryController::class);
        Route::post('/risk-categories/bulk-action', [RiskCategoryController::class, 'bulkAction'])->name('risk-categories.bulk-action');
        Route::get('/risk-categories/{riskCategory}/export', [RiskCategoryController::class, 'export'])->name('risk-categories.export');
        Route::get('/risk-categories-export/csv', [RiskCategoryController::class, 'exportCsv'])->name('risk-categories.export.csv');
    });
    // Redirect old categories route to proper risk categories
    Route::redirect('/categories', '/risk-categories');
    
    // Risk Assessment - Redirected to integrated Risk Register system
    Route::redirect('/assessment', '/risks/create');
    Route::redirect('/client-risk-assessment', '/risks/create');
    
    // Client Assessment Approval System
    Route::prefix('client-assessments')->name('client-assessments.')->group(function () {
        Route::get('/approval', [ClientAssessmentApprovalController::class, 'index'])->name('approval.index');
        Route::get('/approval/{client}', [ClientAssessmentApprovalController::class, 'show'])->name('approval.show');
        Route::post('/approval/{client}/approve', [ClientAssessmentApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('/approval/{client}/reject', [ClientAssessmentApprovalController::class, 'reject'])->name('approval.reject');
        Route::post('/approval/bulk-approve', [ClientAssessmentApprovalController::class, 'bulkApprove'])->name('approval.bulk-approve');
        Route::get('/approval/stats', [ClientAssessmentApprovalController::class, 'stats'])->name('approval.stats');
        Route::get('/rejected', [ClientAssessmentApprovalController::class, 'rejected'])->name('rejected');
    });

    // Client search and history API routes (moved from api.php for proper web middleware)
    Route::get('/api/clients/search', [ClientController::class, 'searchClients']);
    Route::get('/api/clients/{client}/history', [ClientController::class, 'getClientHistory']);
    
    // Reports
    Route::get('/reports', [RiskController::class, 'reports'])->name('risks.reports');
    
    // Settings - Restricted to Admin and Manager only
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/settings', [RiskController::class, 'settings'])->name('risks.settings');
        Route::put('/settings', [RiskController::class, 'updateSettings'])->name('risks.settings.update');
        Route::put('/settings/notifications', [RiskController::class, 'updateNotificationSettings'])->name('risks.settings.notifications');
    });
    
    // Messages - Specific routes first to avoid conflicts
    Route::get('/messages/unread-count', [MessageController::class, 'getUnreadCount'])->name('messages.unread-count');
    Route::get('/messages/recent', [MessageController::class, 'getRecentMessages'])->name('messages.recent');
    Route::resource('messages', MessageController::class);
    Route::post('/messages/{message}/mark-read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
    Route::post('/messages/{message}/mark-unread', [MessageController::class, 'markAsUnread'])->name('messages.mark-unread');
    Route::post('/messages/{message}/archive', [MessageController::class, 'archive'])->name('messages.archive');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/{notification}/mark-unread', [NotificationController::class, 'markAsUnread'])->name('notifications.mark-unread');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    
    // User Profile
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [UserProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/change-password', [UserProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/preferences', [UserProfileController::class, 'updatePreferences'])->name('profile.preferences');
    Route::get('/profile/activity', [UserProfileController::class, 'getActivityLog'])->name('profile.activity');
    Route::post('/profile/toggle-2fa', [UserProfileController::class, 'toggleTwoFactor'])->name('profile.toggle-2fa');
    
    // User Activity & Online Status
    Route::post('/user-activity/update', [UserProfileController::class, 'updateActivity'])->name('user-activity.update');
    Route::post('/user-activity/offline', [UserProfileController::class, 'markOffline'])->name('user-activity.offline');
    Route::get('/user-activity/status/{user}', [UserProfileController::class, 'getUserStatus'])->name('user-activity.status');
    Route::get('/user-activity/online-users', [UserProfileController::class, 'getOnlineUsers'])->name('user-activity.online-users');
    Route::get('/profile/system-info', [UserProfileController::class, 'getSystemInfo'])->name('profile.system-info');
    Route::get('/profile/help-info', [UserProfileController::class, 'getHelpInfo'])->name('profile.help-info');
    
    // User Management - Restricted to Admin and Manager only
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
        Route::get('/users/search', [UserManagementController::class, 'search'])->name('users.search');
        Route::get('/users/{user}/details', [UserManagementController::class, 'getUserDetails'])->name('users.details');
    });
    
    // User Profile - Allow users to view and edit their own profile
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    
});

// Public routes
Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'message' => 'Client Acceptance & Retention Risk Register System is running']);
});
