<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Risk;
use App\Models\Client;
use App\Models\User;

class PerformanceOptimizer
{
    /**
     * Get cached dashboard statistics
     */
    public static function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', 300, function () {
            return [
                'totalRisks' => Risk::whereNull('deleted_at')->count(),
                'highRisks' => Risk::where('risk_rating', 'High')->whereNull('deleted_at')->count(),
                'mediumRisks' => Risk::where('risk_rating', 'Medium')->whereNull('deleted_at')->count(),
                'lowRisks' => Risk::where('risk_rating', 'Low')->whereNull('deleted_at')->count(),
                'totalUsers' => User::count(),
                'activeUsers' => User::where('status', 'active')->count(),
                'totalClients' => Client::where('assessment_status', 'approved')->count(),
                'activeClients' => Client::where('status', 'Active')->count(),
                'highRiskClients' => Client::where('risk_level', 'High')->count(),
            ];
        });
    }

    /**
     * Get performance metrics
     */
    public static function getPerformanceMetrics()
    {
        return [
            'cache_efficiency' => self::calculateCacheEfficiency(),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
            'cache_hits' => Cache::get('cache_hits', 0),
            'cache_misses' => Cache::get('cache_misses', 0),
            'slow_queries' => 0, // Would be implemented with query logging
        ];
    }

    /**
     * Calculate cache efficiency
     */
    private static function calculateCacheEfficiency()
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
     * Clear all caches
     */
    public static function clearCaches()
    {
        Cache::forget('dashboard_stats');
        Cache::forget('cache_hits');
        Cache::forget('cache_misses');
    }
}
