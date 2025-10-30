<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Risk;
use App\Models\User;
use App\Models\Client;
use App\Services\PerformanceOptimizer;
use App\Services\RiskClassificationService;
use App\Services\AdvancedCacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with optimized risk statistics
     */
    public function index()
    {
        try {
            // Use advanced caching service for better performance
            $dashboardData = AdvancedCacheService::getDashboardStats(auth()->id());
            
            // Get recent risks with optimized caching
            try {
                $recentRisks = AdvancedCacheService::getRecentRisks(5);
                $dashboardData['recentRisks'] = collect($recentRisks);
            } catch (\Exception $e) {
                Log::error('Error getting recent risks: ' . $e->getMessage());
                $dashboardData['recentRisks'] = collect();
            }
            
            return view('dashboard', $dashboardData);

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            Log::error('Dashboard error trace: ' . $e->getTraceAsString());
            
            // Return fallback data
            return view('dashboard', [
                'totalRisks' => 0,
                'activeClients' => 0,
                'highRiskClients' => 0,
                'overdueItems' => 0,
                'highRisks' => 0,
                'mediumRisks' => 0,
                'lowRisks' => 0,
                'recentRisks' => collect(),
                'openRisks' => 0,
                'closedRisks' => 0,
                'totalUsers' => 0,
                'activeUsers' => 0,
                'userRoles' => 0,
                'recentLogins' => 0,
                'totalClients' => 0,
                'riskMatrix' => [],
                'error' => 'Unable to load dashboard data. Please try again.'
            ]);
        }
    }

    /**
     * Get dashboard statistics for API
     */
    public function getStats()
    {
        try {
            $stats = [
                'totalRisks' => Risk::whereNull('deleted_at')->count(),
                'activeClients' => Client::where('assessment_status', 'approved')->count(),
                'highRiskClients' => Client::where(function($query) {
                    $query->where('overall_risk_rating', 'LIKE', '%High%')
                          ->orWhere('overall_risk_rating', 'Critical');
                })->whereNull('deleted_at')->count(),
                'overdueItems' => Risk::where('due_date', '<', now())->where('status', '!=', 'Closed')->whereNull('deleted_at')->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Stats API error: ' . $e->getMessage());
            return response()->json([
                'totalRisks' => 0,
                'activeClients' => 0,
                'highRiskClients' => 0,
                'overdueItems' => 0,
            ]);
        }
    }

    /**
     * Get recent risks for API
     */
    public function getRecentRisks()
    {
        try {
            $risks = Risk::with(['client:id,name', 'category:id,name'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($risk) {
                    return [
                        'id' => $risk->id,
                        'title' => $risk->title,
                        'status' => $risk->status,
                        'risk_level' => $risk->risk_rating,
                        'client_name' => $risk->client->name ?? 'Unknown',
                        'category_name' => $risk->category->name ?? 'Uncategorized',
                    ];
                });

            return response()->json($risks);
        } catch (\Exception $e) {
            Log::error('Recent risks API error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get recent activities for API
     */
    public function getRecentActivities()
    {
        try {
            // For now, return sample activities. In a real app, you'd have an activities table
            $activities = [
                [
                    'type' => 'risk_created',
                    'description' => 'New risk "Insufficient Capital" was created',
                    'created_at' => now()->subHours(2)->diffForHumans(),
                ],
                [
                    'type' => 'client_added',
                    'description' => 'New client "ABC Corporation" was added',
                    'created_at' => now()->subHours(4)->diffForHumans(),
                ],
                [
                    'type' => 'assessment_completed',
                    'description' => 'Risk assessment completed for "XYZ Ltd"',
                    'created_at' => now()->subHours(6)->diffForHumans(),
                ],
            ];

            return response()->json($activities);
        } catch (\Exception $e) {
            Log::error('Recent activities API error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get risk status distribution for API
     */
    public function getRiskStatusDistribution()
    {
        try {
            $distribution = [
                'labels' => ['Open', 'In Progress', 'Resolved', 'Closed'],
                'values' => [
                    Risk::where('status', 'Open')->whereNull('deleted_at')->count(),
                    Risk::where('status', 'In Progress')->whereNull('deleted_at')->count(),
                    Risk::where('status', 'Resolved')->whereNull('deleted_at')->count(),
                    Risk::where('status', 'Closed')->whereNull('deleted_at')->count(),
                ],
            ];

            return response()->json($distribution);
        } catch (\Exception $e) {
            Log::error('Risk status distribution API error: ' . $e->getMessage());
            return response()->json([
                'labels' => ['Open', 'In Progress', 'Resolved', 'Closed'],
                'values' => [0, 0, 0, 0],
            ]);
        }
    }

    /**
     * Get real-time dashboard updates via AJAX
     */
    public function getUpdates()
    {
        try {
            $updates = [
                'totalRisks' => Risk::whereNull('deleted_at')->count(),
                'highRisks' => Risk::where('risk_rating', 'High')->whereNull('deleted_at')->count(),
                'mediumRisks' => Risk::where('risk_rating', 'Medium')->whereNull('deleted_at')->count(),
                'lowRisks' => Risk::where('risk_rating', 'Low')->whereNull('deleted_at')->count(),
                'openRisks' => Risk::where('status', 'Open')->whereNull('deleted_at')->count(),
                'closedRisks' => Risk::where('status', 'Closed')->whereNull('deleted_at')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $updates,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard updates error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch updates'
            ], 500);
        }
    }

    /**
     * Clear dashboard cache
     */
    public function clearCache()
    {
        try {
            PerformanceOptimizer::clearCaches();
            
            return response()->json([
                'success' => true,
                'message' => 'Dashboard cache cleared successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Cache clear error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to clear cache'
            ], 500);
        }
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics()
    {
        try {
            $metrics = PerformanceOptimizer::getPerformanceMetrics();
            
            return response()->json([
                'success' => true,
                'data' => $metrics
            ]);

        } catch (\Exception $e) {
            Log::error('Performance metrics error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch performance metrics'
            ], 500);
        }
    }

    /**
     * Log performance metrics for monitoring
     */
    private function logPerformanceMetrics()
    {
        $memoryUsage = memory_get_usage(true);
        $peakMemory = memory_get_peak_usage(true);
        
        if ($memoryUsage > 50 * 1024 * 1024) { // 50MB threshold
            Log::warning("High memory usage detected: " . round($memoryUsage / 1024 / 1024, 2) . "MB");
        }
        
        // Cache performance tracking
        $cacheHits = Cache::get('cache_hits', 0);
        $cacheMisses = Cache::get('cache_misses', 0);
        
        Cache::put('cache_hits', $cacheHits + 1, 3600);
    }
}
