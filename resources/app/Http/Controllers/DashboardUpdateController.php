<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Risk;
use App\Models\Client;
use App\Models\User;
use App\Services\PerformanceOptimizer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardUpdateController extends Controller
{
    /**
     * Get real-time dashboard statistics
     */
    public function getRealTimeStats()
    {
        try {
        $approved = Client::where('assessment_status', 'approved')->count();
        $rejected = Client::where('assessment_status', 'rejected')->count();
        $pending = Client::where('assessment_status', 'pending')->count();

        // Count high risk clients properly - including High, High-risk, Very High-risk, and Critical
        $highRiskClients = Client::where(function($query) {
            $query->where('overall_risk_rating', 'LIKE', '%High%')
                  ->orWhere('overall_risk_rating', 'Critical');
        })->whereNull('deleted_at')->count();
        
        $data = [
            // Total risks should match the number of current client assessments (unique clients)
            'totalRisks' => $approved + $rejected + $pending,
            'highRisks' => $highRiskClients,
            'mediumRisks' => Client::where('overall_risk_rating', 'LIKE', '%Medium%')->whereNull('deleted_at')->count(),
            'lowRisks' => Client::where('overall_risk_rating', 'LIKE', '%Low%')->whereNull('deleted_at')->count(),
            'openRisks' => Risk::where('status', 'Open')->whereNull('deleted_at')->count(),
            'closedRisks' => Risk::where('status', 'Closed')->whereNull('deleted_at')->count(),
            'totalUsers' => User::count(),
            'activeUsers' => User::count(),
            'totalClients' => $approved, // Approved clients on card
            'activeClients' => $approved, // Fixed: Use approved clients, not Active status
            'rejectedClients' => $rejected,
            'highRiskClients' => $highRiskClients,
            'overdueItems' => Risk::where('status', 'Open')->where('created_at', '<', now()->subDays(7))->whereNull('deleted_at')->count(),
            'recentActivities' => $this->getRecentActivities(),
        ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Real-time stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch real-time statistics'
            ], 500);
        }
    }

    /**
     * Get live performance metrics
     */
    public function getLiveMetrics()
    {
        try {
            $metrics = [
                'memory_usage' => memory_get_usage(true),
                'memory_usage_percent' => $this->calculateMemoryUsagePercent(),
                'peak_memory' => memory_get_peak_usage(true),
                'cache_efficiency' => $this->calculateCacheEfficiency(),
                'active_connections' => $this->getActiveConnections(),
                'system_load' => $this->getSystemLoad(),
            ];

            return response()->json([
                'success' => true,
                'data' => $metrics
            ]);

        } catch (\Exception $e) {
            Log::error('Live metrics error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch live metrics'
            ], 500);
        }
    }

    /**
     * Clear all caches
     */
    public function clearAllCaches()
    {
        try {
            PerformanceOptimizer::clearCaches();
            Cache::flush();
            
            Log::info('All caches cleared by user', ['user_id' => auth()->id()]);
            
            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Cache clear error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to clear caches'
            ], 500);
        }
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Recent risks
        $recentRisks = Risk::latest()->take(5)->get();
        foreach ($recentRisks as $risk) {
            $activities->push([
                'type' => 'risk',
                'title' => $risk->title,
                'description' => 'Risk updated: ' . $risk->status,
                'timestamp' => $risk->updated_at->diffForHumans(),
            ]);
        }

        // Recent clients
        $recentClients = Client::latest()->take(3)->get();
        foreach ($recentClients as $client) {
            $activities->push([
                'type' => 'client',
                'title' => $client->name,
                'description' => 'Client ' . $client->status,
                'timestamp' => $client->created_at->diffForHumans(),
            ]);
        }

        return $activities->sortByDesc('timestamp')->take(8)->values();
    }

    /**
     * Calculate memory usage percentage
     */
    private function calculateMemoryUsagePercent()
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        
        if ($memoryLimit === '-1') {
            return 0; // No limit
        }
        
        $memoryLimitBytes = $this->convertToBytes($memoryLimit);
        
        if ($memoryLimitBytes === 0) {
            return 0;
        }
        
        return round(($memoryUsage / $memoryLimitBytes) * 100, 2);
    }

    /**
     * Convert memory limit to bytes
     */
    private function convertToBytes($memoryLimit)
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);
        
        switch ($unit) {
            case 'k': return $value * 1024;
            case 'm': return $value * 1024 * 1024;
            case 'g': return $value * 1024 * 1024 * 1024;
            default: return $value;
        }
    }

    /**
     * Calculate cache efficiency
     */
    private function calculateCacheEfficiency()
    {
        $hits = Cache::get('cache_hits', 0);
        $misses = Cache::get('cache_misses', 0);
        $total = $hits + $misses;
        
        if ($total === 0) {
            return 100;
        }
        
        return round(($hits / $total) * 100, 2);
    }

    /**
     * Get active connections (placeholder)
     */
    private function getActiveConnections()
    {
        // This would typically query your database connection pool
        // For now, return a placeholder
        return rand(5, 25);
    }

    /**
     * Get system load (placeholder)
     */
    private function getSystemLoad()
    {
        // This would typically get system load average
        // For now, return a placeholder
        return rand(10, 80) / 100;
    }
}
