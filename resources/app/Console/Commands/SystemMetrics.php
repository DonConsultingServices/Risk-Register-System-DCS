<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\PerformanceOptimizer;

class SystemMetrics extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'system:metrics {--detailed : Show detailed metrics} {--export : Export metrics to file}';

    /**
     * The console command description.
     */
    protected $description = 'Display system performance metrics and health status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📊 DCS-Best System Performance Metrics');
        $this->newLine();

        try {
            // System Health Check
            $this->displaySystemHealth();

            // Performance Metrics
            $this->displayPerformanceMetrics();

            // Database Metrics
            $this->displayDatabaseMetrics();

            // Cache Metrics
            $this->displayCacheMetrics();

            // Storage Metrics
            $this->displayStorageMetrics();

            // Export if requested
            if ($this->option('export')) {
                $this->exportMetrics();
            }

        } catch (\Exception $e) {
            $this->error('❌ Failed to retrieve metrics: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Display system health status
     */
    private function displaySystemHealth()
    {
        $this->info('🏥 System Health Status');
        $this->line('  ┌─────────────────────────────────────────────────────────┐');
        
        // Database connection
        try {
            DB::connection()->getPdo();
            $this->line('  │ ✅ Database Connection: Healthy                        │');
        } catch (\Exception $e) {
            $this->line('  │ ❌ Database Connection: Failed                        │');
        }

        // Cache connection
        try {
            Cache::store()->has('test');
            $this->line('  │ ✅ Cache System: Healthy                             │');
        } catch (\Exception $e) {
            $this->line('  │ ❌ Cache System: Failed                              │');
        }

        // Storage connection
        try {
            Storage::disk('local')->exists('test');
            $this->line('  │ ✅ Storage System: Healthy                           │');
        } catch (\Exception $e) {
            $this->line('  │ ❌ Storage System: Failed                            │');
        }

        $this->line('  └─────────────────────────────────────────────────────────┘');
        $this->newLine();
    }

    /**
     * Display performance metrics
     */
    private function displayPerformanceMetrics()
    {
        $this->info('⚡ Performance Metrics');
        
        $metrics = PerformanceOptimizer::getPerformanceMetrics();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Cache Efficiency', $metrics['cache_efficiency'] . '%'],
                ['Memory Usage', round($metrics['memory_usage'] / 1024 / 1024, 2) . ' MB'],
                ['Peak Memory', round($metrics['peak_memory'] / 1024 / 1024, 2) . ' MB'],
                ['Cache Hits', $metrics['cache_hits']],
                ['Cache Misses', $metrics['cache_misses']],
                ['Slow Queries', $metrics['slow_queries']],
            ]
        );

        // Additional performance data
        $avgExecutionTime = Cache::get('avg_execution_time', 0);
        $requestCount = Cache::get('request_count', 0);
        $highMemoryRequests = Cache::get('high_memory_requests', 0);

        $this->line('  📈 Average Execution Time: ' . round($avgExecutionTime * 1000, 2) . 'ms');
        $this->line('  📊 Total Requests: ' . number_format($requestCount));
        $this->line('  🚨 High Memory Requests: ' . number_format($highMemoryRequests));
        $this->newLine();
    }

    /**
     * Display database metrics
     */
    private function displayDatabaseMetrics()
    {
        $this->info('🗄️  Database Metrics');
        
        try {
            $tables = ['users', 'risks', 'clients'];
            $tableData = [];

            foreach ($tables as $table) {
                $count = DB::table($table)->count();
                $size = $this->getTableSize($table);
                $tableData[] = [$table, number_format($count), $size];
            }

            $this->table(
                ['Table', 'Records', 'Size'],
                $tableData
            );

            // Database performance
            $this->line('  🔍 Database Status: ' . DB::connection()->getDatabaseName());
            $this->line('  📊 Total Tables: ' . count($tables));
            
        } catch (\Exception $e) {
            $this->warn('  ⚠️  Unable to retrieve database metrics: ' . $e->getMessage());
        }

        $this->newLine();
    }

    /**
     * Display cache metrics
     */
    private function displayCacheMetrics()
    {
        $this->info('💾 Cache Metrics');
        
        $cacheStats = [
            ['Dashboard Stats', Cache::has('dashboard_stats') ? '✅ Cached' : '❌ Not Cached'],
            ['Risk Matrix', Cache::has('risk_matrix') ? '✅ Cached' : '❌ Not Cached'],
            ['Client Stats', Cache::has('client_stats') ? '✅ Cached' : '❌ Not Cached'],
            ['User Stats', Cache::has('user_stats') ? '✅ Cached' : '❌ Not Cached'],
        ];

        $this->table(
            ['Cache Key', 'Status'],
            $cacheStats
        );

        $this->newLine();
    }

    /**
     * Display storage metrics
     */
    private function displayStorageMetrics()
    {
        $this->info('💿 Storage Metrics');
        
        try {
            $disk = Storage::disk('local');
            $totalSpace = disk_total_space(storage_path());
            $freeSpace = disk_free_space(storage_path());
            $usedSpace = $totalSpace - $freeSpace;
            $usagePercentage = round(($usedSpace / $totalSpace) * 100, 2);

            $this->line('  📁 Storage Path: ' . storage_path());
            $this->line('  💾 Total Space: ' . $this->formatBytes($totalSpace));
            $this->line('  📊 Used Space: ' . $this->formatBytes($usedSpace) . ' (' . $usagePercentage . '%)');
            $this->line('  🆓 Free Space: ' . $this->formatBytes($freeSpace));

            // Storage health indicator
            if ($usagePercentage > 90) {
                $this->error('  🚨 Storage Usage: Critical (>90%)');
            } elseif ($usagePercentage > 80) {
                $this->warn('  ⚠️  Storage Usage: High (>80%)');
            } else {
                $this->info('  ✅ Storage Usage: Normal');
            }

        } catch (\Exception $e) {
            $this->warn('  ⚠️  Unable to retrieve storage metrics: ' . $e->getMessage());
        }

        $this->newLine();
    }

    /**
     * Export metrics to file
     */
    private function exportMetrics()
    {
        $this->info('📤 Exporting metrics...');
        
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "system_metrics_{$timestamp}.txt";
        $filepath = storage_path("logs/{$filename}");

        $content = $this->generateMetricsReport();
        
        if (file_put_contents($filepath, $content)) {
            $this->info("  ✅ Metrics exported to: {$filepath}");
        } else {
            $this->error("  ❌ Failed to export metrics");
        }
    }

    /**
     * Generate metrics report
     */
    private function generateMetricsReport()
    {
        $report = "DCS-Best System Performance Report\n";
        $report .= "Generated: " . now()->toDateTimeString() . "\n";
        $report .= str_repeat("=", 50) . "\n\n";

        // Add all metrics to report
        $metrics = PerformanceOptimizer::getPerformanceMetrics();
        $report .= "Performance Metrics:\n";
        $report .= "- Cache Efficiency: {$metrics['cache_efficiency']}%\n";
        $report .= "- Memory Usage: " . round($metrics['memory_usage'] / 1024 / 1024, 2) . " MB\n";
        $report .= "- Peak Memory: " . round($metrics['peak_memory'] / 1024 / 1024, 2) . " MB\n";

        return $report;
    }

    /**
     * Get table size in MB
     */
    private function getTableSize($table)
    {
        try {
            $result = DB::select("SELECT ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size' FROM information_schema.TABLES WHERE table_schema = DATABASE() AND table_name = ?", [$table]);
            return isset($result[0]->Size) ? $result[0]->Size . ' MB' : 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
