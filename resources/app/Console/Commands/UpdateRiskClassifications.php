<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RiskClassificationService;

class UpdateRiskClassifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'risks:update-classifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all risk classifications based on current settings thresholds';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating risk classifications based on current settings...');
        
        $updatedCount = RiskClassificationService::updateAllRiskClassifications();
        
        $this->info("Updated {$updatedCount} risk classifications.");
        
        // Show current counts
        $counts = RiskClassificationService::getRiskCounts();
        $this->table(
            ['Risk Level', 'Count'],
            [
                ['Critical', $counts['critical']],
                ['High', $counts['high']],
                ['Medium', $counts['medium']],
                ['Low', $counts['low']],
                ['Total', $counts['total']],
            ]
        );
        
        return Command::SUCCESS;
    }
}