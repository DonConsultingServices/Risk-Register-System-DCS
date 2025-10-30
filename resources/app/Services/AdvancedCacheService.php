<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdvancedCacheService
{
    /**
     * Cache TTL constants (in seconds)
     */
    const TTL_SHORT = 60;      // 1 minute
    const TTL_MEDIUM = 300;    // 5 minutes
    const TTL_LONG = 1800;     // 30 minutes
    const TTL_VERY_LONG = 3600; // 1 hour

    /**
     * Get dashboard statistics with intelligent caching
     */
    public static function getDashboardStats($userId)
    {
        $cacheKey = "dashboard_stats_v4_{$userId}";
        
        return Cache::remember($cacheKey, self::TTL_SHORT, function() {
        // Use Eloquent for more reliable queries
        $totalClients = \App\Models\Client::whereNull('deleted_at')->count();
        $approvedClients = \App\Models\Client::where('assessment_status', 'approved')->whereNull('deleted_at')->count();
        $pendingClients = \App\Models\Client::where('assessment_status', 'pending')->whereNull('deleted_at')->count();
        $rejectedClients = \App\Models\Client::where('assessment_status', 'rejected')->whereNull('deleted_at')->count();
        
        // Count high risk clients - including High, High-risk, Very High-risk, and Critical
        $highRiskClients = \App\Models\Client::where(function($query) {
            $query->where('overall_risk_rating', 'LIKE', '%High%')
                  ->orWhere('overall_risk_rating', 'Critical');
        })->whereNull('deleted_at')->count();
        
        $mediumRiskClients = \App\Models\Client::where('overall_risk_rating', 'LIKE', '%Medium%')
            ->whereNull('deleted_at')->count();
        $lowRiskClients = \App\Models\Client::where('overall_risk_rating', 'LIKE', '%Low%')
            ->whereNull('deleted_at')->count();
        
        $openRisks = \App\Models\Risk::where('status', 'Open')->whereNull('deleted_at')->count();
        $closedRisks = \App\Models\Risk::where('status', 'Closed')->whereNull('deleted_at')->count();
        $overdueRisks = \App\Models\Risk::where('due_date', '<', now())
            ->where('status', '!=', 'Closed')
            ->whereNull('deleted_at')->count();
        
        $activeUsers = \App\Models\User::where('is_active', 1)->count();

        return [
            'totalRisks' => $totalClients,
            'activeClients' => $approvedClients,
            'totalClients' => $totalClients,
            'openRisks' => $openRisks,
            'closedRisks' => $closedRisks,
            'highRisks' => $highRiskClients,
            'criticalRisks' => 0, // Included in highRiskClients
            'mediumRisks' => $mediumRiskClients,
            'lowRisks' => $lowRiskClients,
            'highRiskClients' => $highRiskClients,
            'overdueItems' => $overdueRisks,
            'pendingClientAssessments' => $pendingClients,
            'rejectedClients' => $rejectedClients,
            'totalUsers' => $activeUsers,
            'activeUsers' => $activeUsers,
        ];
        });
    }

    /**
     * Get client statistics with caching
     */
    public static function getClientStats($userId)
    {
        $cacheKey = "client_stats_v2_{$userId}";
        
        return Cache::remember($cacheKey, self::TTL_MEDIUM, function() {
            // Get client statistics
            $stats = DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM clients WHERE assessment_status = 'approved' AND deleted_at IS NULL) as approved_clients,
                    (SELECT COUNT(*) FROM clients WHERE assessment_status = 'rejected' AND deleted_at IS NULL) as rejected_clients,
                    (SELECT COUNT(*) FROM clients WHERE assessment_status = 'pending' AND deleted_at IS NULL) as pending_clients
            ")[0];

            // Get risk distribution for approved clients
            $riskCounts = DB::select("
                SELECT overall_risk_rating, COUNT(*) as count
                FROM clients 
                WHERE assessment_status = 'approved' AND deleted_at IS NULL
                GROUP BY overall_risk_rating
            ");

            $riskDistribution = [];
            foreach ($riskCounts as $risk) {
                $riskDistribution[$risk->overall_risk_rating] = $risk->count;
            }

            return [
                'totalClients' => $stats->approved_clients,
                'approvedClients' => $stats->approved_clients,
                'rejectedClients' => $stats->rejected_clients,
                'pendingClients' => $stats->pending_clients,
                'lowRiskClients' => $riskDistribution['Low'] ?? 0,
                'mediumRiskClients' => $riskDistribution['Medium'] ?? 0,
                'highRiskClients' => $riskDistribution['High'] ?? 0,
                'criticalRiskClients' => $riskDistribution['Critical'] ?? 0,
            ];
        });
    }

    /**
     * Get recent risks with caching
     */
    public static function getRecentRisks($limit = 10)
    {
        $cacheKey = "recent_risks_v2_{$limit}";
        
        return Cache::remember($cacheKey, self::TTL_SHORT, function() use ($limit) {
            return DB::select("
                SELECT 
                    r.id, r.title, r.status, r.risk_rating, r.created_at,
                    c.name as client_name
                FROM risks r
                LEFT JOIN clients c ON r.client_id = c.id AND c.deleted_at IS NULL
                WHERE r.deleted_at IS NULL
                ORDER BY r.created_at DESC
                LIMIT ?
            ", [$limit]);
        });
    }

    /**
     * Get pending assessments with caching
     */
    public static function getPendingAssessments($userId)
    {
        $cacheKey = "pending_assessments_v2_{$userId}";
        
        return Cache::remember($cacheKey, self::TTL_SHORT, function() {
            return DB::select("
                SELECT 
                    c.id, c.name, c.email, c.company, c.industry, 
                    c.overall_risk_rating, c.overall_risk_points, c.client_acceptance, 
                    c.assessment_status, c.created_at, c.updated_at,
                    u.name as creator_name, u.role as creator_role,
                    COUNT(r.id) as risk_count
                FROM clients c
                LEFT JOIN users u ON c.created_by = u.id
                LEFT JOIN risks r ON c.id = r.client_id AND r.deleted_at IS NULL
                WHERE c.assessment_status = 'pending' AND c.deleted_at IS NULL
                GROUP BY c.id, c.name, c.email, c.company, c.industry, 
                         c.overall_risk_rating, c.overall_risk_points, c.client_acceptance, 
                         c.assessment_status, c.created_at, c.updated_at, u.name, u.role
                ORDER BY c.created_at DESC
            ");
        });
    }

    /**
     * Clear all caches related to a specific user
     */
    public static function clearUserCaches($userId)
    {
        $cacheKeys = [
            "dashboard_stats_v2_{$userId}",
            "client_stats_v2_{$userId}",
            "pending_assessments_v2_{$userId}",
            "recent_risks_v2_10",
            "recent_risks_v2_5",
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Also clear legacy cache keys
        Cache::forget("dashboard_stats_{$userId}");
        Cache::forget("client_stats_{$userId}");
        Cache::forget("pending_assessments_{$userId}");
        Cache::forget("recent_risks");
    }

    /**
     * Clear all application caches
     */
    public static function clearAllCaches()
    {
        // Clear all cache patterns
        $patterns = [
            'dashboard_stats_v2_*',
            'client_stats_v2_*',
            'pending_assessments_v2_*',
            'recent_risks_v2_*',
            'dashboard_stats_*',
            'client_stats_*',
            'pending_assessments_*',
            'recent_risks',
            'rejected_clients_data',
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($pattern, '*')) {
                // For patterns with wildcards, we'd need Redis or similar
                // For now, clear common user IDs
                for ($i = 1; $i <= 100; $i++) {
                    Cache::forget(str_replace('*', $i, $pattern));
                }
            } else {
                Cache::forget($pattern);
            }
        }

        Log::info('All application caches cleared');
    }

    /**
     * Warm up critical caches
     */
    public static function warmUpCaches()
    {
        Log::info('Warming up caches...');
        
        // Warm up for common user IDs
        for ($i = 1; $i <= 10; $i++) {
            self::getDashboardStats($i);
            self::getClientStats($i);
            self::getPendingAssessments($i);
            self::getRecentRisks(5);
            self::getRecentRisks(10);
        }

        Log::info('Caches warmed.');
    }

    /**
     * Get cache statistics
     */
    public static function getCacheStats()
    {
        return [
            'cache_driver' => config('cache.default'),
            'cache_prefix' => config('cache.prefix'),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
            'cache_hits' => Cache::get('cache_hits', 0),
            'cache_misses' => Cache::get('cache_misses', 0),
        ];
    }
}
