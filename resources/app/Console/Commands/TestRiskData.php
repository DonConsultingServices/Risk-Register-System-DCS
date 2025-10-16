<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ComprehensiveRiskAssessment;
use App\Models\Client;
use App\Models\Risk;

class TestRiskData extends Command
{
    protected $signature = 'risk:test-data';
    protected $description = 'Test comprehensive risk assessment data';

    public function handle()
    {
        $this->info('🧪 Testing comprehensive risk assessment data...');
        
        // Test comprehensive assessments
        $assessmentCount = ComprehensiveRiskAssessment::count();
        $this->info("📊 Total comprehensive assessments: {$assessmentCount}");
        
        if ($assessmentCount > 0) {
            $assessment = ComprehensiveRiskAssessment::first();
            $this->info("✅ Sample assessment found:");
            $this->info("   - Total Points: {$assessment->total_points}");
            $this->info("   - Overall Rating: {$assessment->overall_risk_rating}");
            $this->info("   - Client Acceptance: {$assessment->client_acceptance}");
            $this->info("   - Monitoring: {$assessment->ongoing_monitoring}");
        }
        
        // Test clients with comprehensive data
        $clientsWithData = Client::whereHas('comprehensiveRiskAssessment')->count();
        $this->info("👥 Clients with comprehensive data: {$clientsWithData}");
        
        // Test dashboard data
        $clients = Client::with(['risks', 'comprehensiveRiskAssessment'])->take(5)->get();
        $this->info("🔍 Testing dashboard data for all clients:");
        
        foreach ($clients as $client) {
            $this->info("   Client: {$client->name}");
            $this->info("     - Overall Risk Points: " . ($client->overall_risk_points ?? 'N/A'));
            $this->info("     - Risk ID: " . ($client->risk_id ?? 'N/A'));
            $this->info("     - Risks count: " . $client->risks->count());
            $this->info("     - Has comprehensive data: " . ($client->comprehensiveRiskAssessment ? 'Yes' : 'No'));
            
            if ($client->comprehensiveRiskAssessment) {
                $this->info("     - Total Points: {$client->comprehensiveRiskAssessment->total_points}");
                $this->info("     - Overall Rating: {$client->comprehensiveRiskAssessment->overall_risk_rating}");
            } else {
                // Check if there's a risk record for this client
                $risk = Risk::where('client_name', $client->name)->first();
                if ($risk) {
                    $this->info("     - Found risk record: ID {$risk->id}, Points: {$risk->overall_risk_points}");
                } else {
                    $this->info("     - No risk record found");
                }
            }
            $this->info("     ---");
        }
        
        // Check risks table
        $this->info("🔍 Checking risks table:");
        $risks = Risk::all();
        foreach ($risks as $risk) {
            $this->info("   Risk ID: {$risk->id}, Client: {$risk->client_name}, Points: {$risk->overall_risk_points}");
        }
        
        $this->info('✅ Test completed successfully!');
    }
}
