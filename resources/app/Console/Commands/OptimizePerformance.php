<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class OptimizePerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:performance {--clear-cache : Clear all caches}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize system performance by clearing caches and running optimizations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting performance optimization...');

        if ($this->option('clear-cache')) {
            $this->clearAllCaches();
        }

        $this->runOptimizations();
        $this->showPerformanceTips();

        $this->info('Performance optimization completed!');
    }

    /**
     * Clear all caches
     */
    private function clearAllCaches()
    {
        $this->info('Clearing caches...');
        
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        // Clear custom caches
        Cache::forget('dashboard_stats_' . auth()->id());
        Cache::forget('client_stats_' . auth()->id());
        Cache::forget('recent_risks');
        
        $this->info('All caches cleared successfully.');
    }

    /**
     * Run performance optimizations
     */
    private function runOptimizations()
    {
        $this->info('Running optimizations...');
        
        // Run database migrations for indexes
        $this->info('Adding database indexes...');
        Artisan::call('migrate', ['--force' => true]);
        
        // Optimize autoloader
        $this->info('Optimizing autoloader...');
        Artisan::call('optimize');
        
        $this->info('Optimizations completed.');
    }

    /**
     * Show performance tips
     */
    private function showPerformanceTips()
    {
        $this->info('');
        $this->info('Performance Tips:');
        $this->line('1. Database indexes have been added for faster queries');
        $this->line('2. Caching is enabled for dashboard statistics (5 minutes)');
        $this->line('3. Client statistics are cached per user (5 minutes)');
        $this->line('4. Recent risks are cached (1 minute)');
        $this->line('5. N+1 query problems have been fixed');
        $this->line('6. Excessive debug logging has been removed');
        $this->line('');
        $this->line('To clear caches manually, run: php artisan optimize:performance --clear-cache');
        $this->line('To monitor performance, check the logs for slow queries');
    }
}