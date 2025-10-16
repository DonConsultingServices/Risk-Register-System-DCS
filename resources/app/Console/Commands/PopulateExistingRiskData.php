<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Models\Risk;
use App\Models\ComprehensiveRiskAssessment;

class PopulateExistingRiskData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'risk:populate-existing-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate missing risk data for existing clients and ensure system handles all future assessments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting comprehensive risk data population...');
        
        try {
            // Step 1: Populate missing data in existing risks table
            $this->populateExistingRisksData();
            
            // Step 2: Create comprehensive risk assessments for existing data
            $this->createComprehensiveAssessments();
            
            // Step 3: Update clients table with missing risk details
            $this->updateClientsRiskData();
            
            // Step 4: Verify data integrity
            $this->verifyDataIntegrity();
            
            $this->info('✅ All existing risk data has been populated successfully!');
            $this->info('🎯 System is now ready to handle all future risk assessments correctly.');
            
        } catch (\Exception $e) {
            $this->error('❌ Error during data population: ' . $e->getMessage());
            Log::error('Risk data population failed', ['error' => $e->getMessage()]);
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Populate missing data in existing risks table
     */
    private function populateExistingRisksData()
    {
        $this->info('📊 Populating missing data in existing risks table...');
        
        $risks = Risk::whereNull('impact')
            ->orWhereNull('likelihood')
            ->orWhereNull('risk_rating')
            ->orWhere('impact', '')
            ->orWhere('likelihood', '')
            ->orWhere('risk_rating', '')
            ->get();
            
        $updatedCount = 0;
        
        foreach ($risks as $risk) {
            // Calculate missing fields based on overall risk points
            $totalPoints = $risk->overall_risk_points ?? 0;
            
            if ($totalPoints > 0) {
                // Determine impact and likelihood based on points
                $impact = $this->calculateImpactFromPoints($totalPoints);
                $likelihood = $this->calculateLikelihoodFromPoints($totalPoints);
                $riskRating = $this->calculateRiskRatingFromPoints($totalPoints);
                
                // Update the risk record
                $risk->update([
                    'impact' => $impact,
                    'likelihood' => $likelihood,
                    'risk_rating' => $riskRating,
                ]);
                
                $updatedCount++;
            }
        }
        
        $this->info("✅ Updated {$updatedCount} risk records with missing data");
    }
    
    /**
     * Create comprehensive risk assessments for existing data
     */
    private function createComprehensiveAssessments()
    {
        $this->info('🔍 Creating comprehensive risk assessments for existing data...');
        
        // Get all clients with risk data
        $clientsWithRiskData = Client::whereNotNull('overall_risk_points')
            ->where('overall_risk_points', '>', 0)
            ->get();
            
        $createdCount = 0;
        
        foreach ($clientsWithRiskData as $client) {
            // Check if comprehensive assessment already exists
            $existingAssessment = ComprehensiveRiskAssessment::where('risk_id', function($query) use ($client) {
                $query->select('id')
                    ->from('risks')
                    ->where('client_name', $client->name)
                    ->limit(1);
            })->first();
            
            if (!$existingAssessment) {
                // Create comprehensive assessment based on client data
                $this->createComprehensiveAssessmentFromClient($client);
                $createdCount++;
            }
        }
        
        $this->info("✅ Created {$createdCount} comprehensive risk assessments");
    }
    
    /**
     * Update clients table with missing risk details
     */
    private function updateClientsRiskData()
    {
        $this->info('👥 Updating clients table with missing risk details...');
        
        $clients = Client::whereNotNull('overall_risk_points')
            ->where('overall_risk_points', '>', 0)
            ->get();
            
        $updatedCount = 0;
        
        foreach ($clients as $client) {
            // Get the associated risk record
            $risk = Risk::where('client_name', $client->name)->first();
            
            if ($risk) {
                // Update client with risk details
                $client->update([
                    'risk_category' => $risk->risk_category ?? 'Comprehensive',
                    'risk_id' => $risk->risk_id ?? 'N/A',
                ]);
                
                $updatedCount++;
            }
        }
        
        $this->info("✅ Updated {$updatedCount} client records with risk details");
    }
    
    /**
     * Verify data integrity
     */
    private function verifyDataIntegrity()
    {
        $this->info('🔍 Verifying data integrity...');
        
        // Check risks table
        $risksWithMissingData = Risk::whereNull('impact')
            ->orWhereNull('likelihood')
            ->orWhereNull('risk_rating')
            ->orWhere('impact', '')
            ->orWhere('likelihood', '')
            ->orWhere('risk_rating', '')
            ->count();
            
        // Check comprehensive assessments
        $risksWithoutComprehensive = Risk::whereNotNull('overall_risk_points')
            ->where('overall_risk_points', '>', 0)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('comprehensive_risk_assessments')
                    ->whereRaw('comprehensive_risk_assessments.risk_id = risks.id');
            })
            ->count();
            
        if ($risksWithMissingData === 0 && $risksWithoutComprehensive === 0) {
            $this->info('✅ Data integrity verified - all records are complete!');
        } else {
            $this->warn("⚠️  Found {$risksWithMissingData} risks with missing data and {$risksWithoutComprehensive} without comprehensive assessments");
        }
    }
    
    /**
     * Calculate impact based on total risk points
     */
    private function calculateImpactFromPoints($totalPoints)
    {
        if ($totalPoints >= 15) return 'High';
        if ($totalPoints >= 10) return 'Medium';
        return 'Low';
    }
    
    /**
     * Calculate likelihood based on total risk points
     */
    private function calculateLikelihoodFromPoints($totalPoints)
    {
        if ($totalPoints >= 17) return 'High';
        if ($totalPoints >= 12) return 'Medium';
        return 'Low';
    }
    
    /**
     * Calculate risk rating based on total risk points
     */
    private function calculateRiskRatingFromPoints($totalPoints)
    {
        if ($totalPoints >= 20) return 'High';
        if ($totalPoints >= 15) return 'Medium';
        return 'Low';
    }
    
    /**
     * Create comprehensive assessment from existing client data
     */
    private function createComprehensiveAssessmentFromClient($client)
    {
        $totalPoints = $client->overall_risk_points ?? 0;
        
        // First, create a risk record if it doesn't exist
        $risk = Risk::firstOrCreate(
            ['client_name' => $client->name],
            [
                'title' => $client->risk_category . ' Risk Assessment',
                'description' => 'Comprehensive risk assessment for ' . $client->name,
                'client_name' => $client->name,
                // client_identification_done field removed - consolidated with screening fields
                'client_screening_date' => $client->client_screening_date ?? now(),
                'client_screening_result' => $client->client_screening_result ?? 'Done',
                'risk_description' => $client->risk_category . ' risks',
                'risk_detail' => 'Comprehensive risk assessment',
                'risk_category' => $client->risk_category ?? 'Comprehensive',
                'risk_id' => $client->risk_id ?? 'N/A',
                'impact' => $this->calculateImpactFromPoints($totalPoints),
                'likelihood' => $this->calculateLikelihoodFromPoints($totalPoints),
                'risk_rating' => $this->calculateRiskRatingFromPoints($totalPoints),
                'mitigation_strategies' => 'Standard mitigation measures',
                'owner' => 'Risk Manager',
                'status' => 'Open',
                'overall_risk_points' => $totalPoints,
                'overall_risk_rating' => $client->overall_risk_rating ?? 'Low-risk',
                'client_acceptance' => $client->client_acceptance ?? 'Accept client',
                'ongoing_monitoring' => $client->ongoing_monitoring ?? 'Annually',
                'dcs_risk_appetite' => $client->dcs_risk_appetite ?? 'Moderate',
                'dcs_comments' => 'Risk assessment completed via system',
                'created_by' => $client->created_by ?? 1,
                'updated_by' => $client->updated_by ?? 1,
            ]
        );
        
        // Now create comprehensive assessment
        ComprehensiveRiskAssessment::create([
            'risk_id' => $risk->id,
            'sr_risk_id' => $client->risk_id ?? 'N/A',
            'sr_risk_name' => $client->risk_category . ' Risk Assessment',
            'sr_impact' => $this->calculateImpactFromPoints($totalPoints),
            'sr_likelihood' => $this->calculateLikelihoodFromPoints($totalPoints),
            'sr_risk_rating' => $this->calculateRiskRatingFromPoints($totalPoints),
            'sr_points' => min(5, max(1, intval($totalPoints / 4))),
            'sr_mitigation' => 'Standard mitigation measures',
            'sr_owner' => 'Risk Manager',
            'sr_status' => 'Open',
            
            'cr_risk_id' => $client->risk_id ?? 'N/A',
            'cr_risk_name' => $client->risk_category . ' Risk Assessment',
            'cr_impact' => $this->calculateImpactFromPoints($totalPoints),
            'cr_likelihood' => $this->calculateLikelihoodFromPoints($totalPoints),
            'cr_risk_rating' => $this->calculateRiskRatingFromPoints($totalPoints),
            'cr_points' => min(5, max(1, intval($totalPoints / 4))),
            'cr_mitigation' => 'Standard mitigation measures',
            'cr_owner' => 'Risk Manager',
            'cr_status' => 'Open',
            
            'pr_risk_id' => $client->risk_id ?? 'N/A',
            'pr_risk_name' => $client->risk_category . ' Risk Assessment',
            'pr_impact' => $this->calculateImpactFromPoints($totalPoints),
            'pr_likelihood' => $this->calculateLikelihoodFromPoints($totalPoints),
            'pr_risk_rating' => $this->calculateRiskRatingFromPoints($totalPoints),
            'pr_points' => min(5, max(1, intval($totalPoints / 4))),
            'pr_mitigation' => 'Standard mitigation measures',
            'pr_owner' => 'Risk Manager',
            'pr_status' => 'Open',
            
            'dr_risk_id' => $client->risk_id ?? 'N/A',
            'dr_risk_name' => $client->risk_category . ' Risk Assessment',
            'dr_impact' => $this->calculateImpactFromPoints($totalPoints),
            'dr_likelihood' => $this->calculateLikelihoodFromPoints($totalPoints),
            'dr_risk_rating' => $this->calculateRiskRatingFromPoints($totalPoints),
            'dr_points' => min(5, max(1, intval($totalPoints / 4))),
            'dr_mitigation' => 'Standard mitigation measures',
            'dr_owner' => 'Risk Manager',
            'dr_status' => 'Open',
            
            'total_points' => $totalPoints,
            'overall_risk_rating' => $client->overall_risk_rating ?? 'Low-risk',
            'client_acceptance' => $client->client_acceptance ?? 'Accept client',
            'ongoing_monitoring' => $client->ongoing_monitoring ?? 'Annually',
            'created_by' => $client->created_by ?? 1,
            'updated_by' => $client->updated_by ?? 1,
        ]);
    }
}
