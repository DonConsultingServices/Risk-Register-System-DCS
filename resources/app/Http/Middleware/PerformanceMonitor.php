<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        // Process the request
        $response = $next($request);

        // Calculate performance metrics
        $executionTime = microtime(true) - $startTime;
        $memoryUsage = memory_get_usage(true) - $startMemory;
        $peakMemory = memory_get_peak_usage(true);

        // Log slow requests
        $slowQueryThreshold = config('performance.database.slow_query_threshold', 0.5);
        if ($executionTime > $slowQueryThreshold) {
            Log::warning("Slow request detected", [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => round($executionTime, 3) . 's',
                'memory_usage' => round($memoryUsage / 1024 / 1024, 2) . 'MB',
                'peak_memory' => round($peakMemory / 1024 / 1024, 2) . 'MB',
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);
        }

        // Track performance metrics
        $this->trackPerformanceMetrics($executionTime, $memoryUsage, $peakMemory);

        // Add performance headers to response
        $response->headers->set('X-Execution-Time', round($executionTime * 1000, 2) . 'ms');
        $response->headers->set('X-Memory-Usage', round($memoryUsage / 1024 / 1024, 2) . 'MB');

        return $response;
    }

    /**
     * Track performance metrics for analytics
     */
    private function trackPerformanceMetrics(float $executionTime, int $memoryUsage, int $peakMemory): void
    {
        try {
            // Track average execution time
            $avgExecutionTime = Cache::get('avg_execution_time', 0);
            $requestCount = Cache::get('request_count', 0);
            
            $newAvg = (($avgExecutionTime * $requestCount) + $executionTime) / ($requestCount + 1);
            
            Cache::put('avg_execution_time', $newAvg, 3600); // 1 hour
            Cache::put('request_count', $requestCount + 1, 3600);

            // Track memory usage patterns
            $memoryThreshold = config('performance.monitoring.memory_usage_threshold', 50) * 1024 * 1024; // Convert MB to bytes
            
            if ($peakMemory > $memoryThreshold) {
                $highMemoryCount = Cache::get('high_memory_requests', 0);
                Cache::put('high_memory_requests', $highMemoryCount + 1, 3600);
            }

            // Track slow query count
            $slowQueryThreshold = config('performance.database.slow_query_threshold', 0.5);
            if ($executionTime > $slowQueryThreshold) {
                $slowQueryCount = Cache::get('slow_query_count', 0);
                Cache::put('slow_query_count', $slowQueryCount + 1, 3600);
            }

        } catch (\Exception $e) {
            Log::error('Failed to track performance metrics: ' . $e->getMessage());
        }
    }
}
