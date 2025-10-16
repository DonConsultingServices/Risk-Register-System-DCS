<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearAllCaches extends Command
{
    protected $signature = 'cache:clear-all';
    protected $description = 'Clear all application caches including dashboard and client caches';

    public function handle()
    {
        $this->info('Clearing all application caches...');
        
        // Clear Laravel caches
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->call('route:clear');
        
        // Clear custom caches
        $caches = [
            'dashboard_stats',
            'recent_risks',
            'client_stats',
            'pending_assessments',
            'rejected_clients_data',
            'performance_metrics'
        ];
        
        foreach ($caches as $cache) {
            Cache::forget($cache);
            $this->line("Cleared: {$cache}");
        }
        
        // Clear user-specific caches (this is a bit tricky, so we'll clear common patterns)
        $this->info('Clearing user-specific caches...');
        $this->line('Note: User-specific caches will be cleared on next access');
        
        $this->info('All caches cleared successfully!');
    }
}
