<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\PerformanceOptimizer;

class OptimizeSystem extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'system:optimize {--cache : Clear application cache} {--config : Clear config cache} {--route : Clear route cache} {--view : Clear view cache} {--all : Clear all caches}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize the DCS-Best Risk Register System for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 DCS-Best System Optimization Started');
        $this->newLine();

        try {
            // Clear specified caches
            if ($this->option('all') || $this->option('cache')) {
                $this->clearApplicationCache();
            }

            if ($this->option('all') || $this->option('config')) {
                $this->clearConfigCache();
            }

            if ($this->option('all') || $this->option('route')) {
                $this->clearRouteCache();
            }

            if ($this->option('all') || $this->option('view')) {
                $this->clearViewCache();
            }

            // Optimize database
            $this->optimizeDatabase();

            // Clear performance caches
            $this->clearPerformanceCaches();

            // Generate application key if missing
            $this->ensureApplicationKey();

            // Optimize autoloader
            $this->optimizeAutoloader();

            $this->newLine();
            $this->info('✅ System optimization completed successfully!');
            $this->newLine();

            // Show performance tips
            $this->showPerformanceTips();

        } catch (\Exception $e) {
            $this->error('❌ System optimization failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Clear application cache
     */
    private function clearApplicationCache()
    {
        $this->info('🗑️  Clearing application cache...');
        Cache::flush();
        $this->info('   ✅ Application cache cleared');
    }

    /**
     * Clear config cache
     */
    private function clearConfigCache()
    {
        $this->info('⚙️  Clearing config cache...');
        Artisan::call('config:clear');
        $this->info('   ✅ Config cache cleared');
    }

    /**
     * Clear route cache
     */
    private function clearRouteCache()
    {
        $this->info('🛣️  Clearing route cache...');
        Artisan::call('route:clear');
        $this->info('   ✅ Route cache cleared');
    }

    /**
     * Clear view cache
     */
    private function clearViewCache()
    {
        $this->info('👁️  Clearing view cache...');
        Artisan::call('view:clear');
        $this->info('   ✅ View cache cleared');
    }

    /**
     * Optimize database
     */
    private function optimizeDatabase()
    {
        $this->info('🗄️  Optimizing database...');
        
        try {
            // Analyze tables for better performance
            $tables = ['users', 'risks', 'clients'];
            
            foreach ($tables as $table) {
                DB::statement("ANALYZE TABLE {$table}");
                $this->info("   ✅ Analyzed table: {$table}");
            }

            // Optimize tables
            foreach ($tables as $table) {
                DB::statement("OPTIMIZE TABLE {$table}");
                $this->info("   ✅ Optimized table: {$table}");
            }

        } catch (\Exception $e) {
            $this->warn("   ⚠️  Database optimization warning: " . $e->getMessage());
        }
    }

    /**
     * Clear performance caches
     */
    private function clearPerformanceCaches()
    {
        $this->info('📊 Clearing performance caches...');
        PerformanceOptimizer::clearCaches();
        $this->info('   ✅ Performance caches cleared');
    }

    /**
     * Ensure application key exists
     */
    private function ensureApplicationKey()
    {
        $this->info('🔑 Checking application key...');
        
        if (empty(config('app.key'))) {
            $this->warn('   ⚠️  Application key is missing, generating...');
            Artisan::call('key:generate');
            $this->info('   ✅ Application key generated');
        } else {
            $this->info('   ✅ Application key exists');
        }
    }

    /**
     * Optimize autoloader
     */
    private function optimizeAutoloader()
    {
        $this->info('📚 Optimizing autoloader...');
        
        if (file_exists(base_path('composer.json'))) {
            $this->info('   📦 Running composer optimize...');
            exec('composer optimize --no-dev', $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->info('   ✅ Composer optimization completed');
            } else {
                $this->warn('   ⚠️  Composer optimization failed');
            }
        }
    }

    /**
     * Show performance tips
     */
    private function showPerformanceTips()
    {
        $this->info('💡 Performance Optimization Tips:');
        $this->newLine();
        
        $tips = [
            '• Enable Redis caching for better performance',
            '• Use database connection pooling in production',
            '• Implement lazy loading for large datasets',
            '• Monitor slow queries and optimize them',
            '• Use CDN for static assets in production',
            '• Enable HTTP/2 for better network performance',
            '• Implement rate limiting for API endpoints',
            '• Use queue workers for background jobs',
        ];

        foreach ($tips as $tip) {
            $this->line("  {$tip}");
        }

        $this->newLine();
        $this->info('📈 Monitor system performance with: php artisan system:metrics');
    }
}
