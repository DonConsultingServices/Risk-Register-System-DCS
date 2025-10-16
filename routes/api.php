<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardUpdateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Dashboard API Routes (accessible without auth for dashboard display)
Route::prefix('dashboard')->group(function () {
    Route::get('/stats', [DashboardController::class, 'getStats']);
    Route::get('/recent-risks', [DashboardController::class, 'getRecentRisks']);
    Route::get('/recent-activities', [DashboardController::class, 'getRecentActivities']);
    Route::get('/risk-status-distribution', [DashboardController::class, 'getRiskStatusDistribution']);
    Route::post('/clear-cache', [DashboardController::class, 'clearCache']);
});

// Real-time dashboard updates
Route::prefix('dashboard-updates')->group(function () {
    Route::get('/stats', [DashboardUpdateController::class, 'getRealTimeStats']);
    Route::get('/live-metrics', [DashboardUpdateController::class, 'getLiveMetrics']);
    Route::post('/clear-all-caches', [DashboardUpdateController::class, 'clearAllCaches']);
});

// Client search and history API routes moved to web.php for proper authentication