<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AdvancedCacheService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PerformanceMonitor extends Command
{
    protected $signature = 'performance:monitor';
    protected $description = 'Monitor application performance and provide optimization recommendations';

    public function handle()
    {
        $this->info('=== DCS Performance Monitor ===');
        
        // Check database performance
        $this->checkDatabasePerformance();
        
        // Check cache performance
        $this->checkCachePerformance();
        
        // Check asset optimization
        $this->checkAssetOptimization();
        
        // Provide recommendations
        $this->provideRecommendations();
        
        $this->info('Performance monitoring completed!');
    }
    
    private function checkDatabasePerformance()
    {
        $this->info("\n--- Database Performance ---");
        
        // Check query execution times
        $start = microtime(true);
        $clients = DB::select("SELECT COUNT(*) as count FROM clients WHERE deleted_at IS NULL");
        $clientsTime = (microtime(true) - $start) * 1000;
        
        $start = microtime(true);
        $risks = DB::select("SELECT COUNT(*) as count FROM risks WHERE deleted_at IS NULL");
        $risksTime = (microtime(true) - $start) * 1000;
        
        $this->line("✓ Clients query: {$clientsTime}ms");
        $this->line("✓ Risks query: {$risksTime}ms");
        
        // Check for slow queries
        if ($clientsTime > 100 || $risksTime > 100) {
            $this->warn("⚠ Slow queries detected (>100ms)");
        } else {
            $this->info("✓ Database queries are performing well");
        }
        
        // Check index usage
        $this->checkIndexUsage();
    }
    
    private function checkIndexUsage()
    {
        $tables = ['clients', 'risks', 'users'];
        
        foreach ($tables as $table) {
            $indexes = DB::select("SHOW INDEX FROM {$table}");
            $indexCount = count($indexes);
            
            if ($indexCount < 3) {
                $this->warn("⚠ Table '{$table}' has only {$indexCount} indexes - consider adding more");
            } else {
                $this->line("✓ Table '{$table}' has {$indexCount} indexes");
            }
        }
    }
    
    private function checkCachePerformance()
    {
        $this->info("\n--- Cache Performance ---");
        
        $cacheStats = AdvancedCacheService::getCacheStats();
        
        $this->line("✓ Cache driver: {$cacheStats['cache_driver']}");
        $this->line("✓ Memory usage: " . $this->formatBytes($cacheStats['memory_usage']));
        $this->line("✓ Peak memory: " . $this->formatBytes($cacheStats['peak_memory']));
        
        // Test cache performance
        $start = microtime(true);
        Cache::put('performance_test', 'test_value', 60);
        $putTime = (microtime(true) - $start) * 1000;
        
        $start = microtime(true);
        Cache::get('performance_test');
        $getTime = (microtime(true) - $start) * 1000;
        
        $this->line("✓ Cache put: {$putTime}ms");
        $this->line("✓ Cache get: {$getTime}ms");
        
        if ($putTime > 10 || $getTime > 5) {
            $this->warn("⚠ Cache operations are slow - consider optimizing cache driver");
        } else {
            $this->info("✓ Cache performance is good");
        }
        
        // Clean up test
        Cache::forget('performance_test');
    }
    
    private function checkAssetOptimization()
    {
        $this->info("\n--- Asset Optimization ---");
        
        $optimizedDir = public_path('assets/optimized');
        
        if (!is_dir($optimizedDir)) {
            $this->warn("⚠ Optimized assets directory not found - run 'php artisan assets:optimize'");
            return;
        }
        
        $cssFile = $optimizedDir . '/app.min.css';
        $jsFile = $optimizedDir . '/app.min.js';
        $criticalFile = $optimizedDir . '/critical.css';
        
        if (file_exists($cssFile)) {
            $cssSize = filesize($cssFile);
            $this->line("✓ Combined CSS: " . $this->formatBytes($cssSize));
            
            if ($cssSize > 50000) { // 50KB
                $this->warn("⚠ CSS file is large ({$this->formatBytes($cssSize)}) - consider further optimization");
            }
        }
        
        if (file_exists($jsFile)) {
            $jsSize = filesize($jsFile);
            $this->line("✓ Combined JS: " . $this->formatBytes($jsSize));
            
            if ($jsSize > 100000) { // 100KB
                $this->warn("⚠ JS file is large ({$this->formatBytes($jsSize)}) - consider further optimization");
            }
        }
        
        if (file_exists($criticalFile)) {
            $criticalSize = filesize($criticalFile);
            $this->line("✓ Critical CSS: " . $this->formatBytes($criticalSize));
            
            if ($criticalSize > 10000) { // 10KB
                $this->warn("⚠ Critical CSS is large ({$this->formatBytes($criticalSize)}) - consider reducing");
            }
        }
    }
    
    private function provideRecommendations()
    {
        $this->info("\n--- Performance Recommendations ---");
        
        $recommendations = [];
        
        // Check if optimized layout is being used
        if (!file_exists(resource_path('views/layouts/optimized.blade.php'))) {
            $recommendations[] = "Consider using the optimized layout for better performance";
        }
        
        // Check if assets are optimized
        if (!file_exists(public_path('assets/optimized/app.min.css'))) {
            $recommendations[] = "Run 'php artisan assets:optimize' to optimize CSS and JS files";
        }
        
        // Check cache configuration
        if (config('cache.default') === 'file') {
            $recommendations[] = "Consider using Redis or Memcached for better cache performance";
        }
        
        // Check if database indexes are optimal
        $recommendations[] = "Monitor slow query log for additional optimization opportunities";
        $recommendations[] = "Consider implementing database query result caching for heavy queries";
        $recommendations[] = "Use lazy loading for images and non-critical content";
        
        if (empty($recommendations)) {
            $this->info("✓ No immediate optimizations needed - system is well optimized!");
        } else {
            foreach ($recommendations as $i => $rec) {
                $this->line(($i + 1) . ". {$rec}");
            }
        }
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
