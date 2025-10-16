<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\ComprehensiveRiskAssessment;

class FixApprovedClientAssessments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:fix-approved-assessments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create comprehensive risk assessments for approved clients that are missing them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix approved client assessments...');

        // Get approved clients that don't have comprehensive risk assessments
        $clients = Client::where('assessment_status', 'approved')
            ->whereDoesntHave('comprehensiveRiskAssessment')
            ->with('risks')
            ->get();

        if ($clients->isEmpty()) {
            $this->info('No approved clients found without comprehensive risk assessments.');
            return;
        }

        $this->info("Found {$clients->count()} approved clients without comprehensive risk assessments.");

        $fixed = 0;
        foreach ($clients as $client) {
            try {
                $this->createComprehensiveRiskAssessment($client);
                $fixed++;
                $this->line("✓ Fixed client: {$client->name}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to fix client {$client->name}: {$e->getMessage()}");
            }
        }

        $this->info("Successfully fixed {$fixed} client assessments.");
    }

    /**
     * Create comprehensive risk assessment record for a client
     */
    private function createComprehensiveRiskAssessment(Client $client)
    {
        // Check if comprehensive assessment already exists
        if ($client->comprehensiveRiskAssessment) {
            return $client->comprehensiveRiskAssessment;
        }

        // Get the client's risks
        $risks = $client->risks;
        
        // Initialize comprehensive assessment data
        $comprehensiveData = [
            'risk_id' => null, // Will be set if risks exist
            'total_points' => $client->overall_risk_points ?? 0,
            'overall_risk_rating' => $client->overall_risk_rating ?? 'Not Assessed',
            'client_acceptance' => $client->client_acceptance ?? 'Not Determined',
            'ongoing_monitoring' => $client->ongoing_monitoring ?? 'Not Determined',
            'created_by' => 1, // System user
        ];

        if ($risks->isNotEmpty()) {
            // Check if this is a dummy risk record (created for comprehensive assessment)
            $primaryRisk = $risks->first();
            $isDummyRisk = str_contains($primaryRisk->title, 'Comprehensive Risk Assessment');
            
            if ($isDummyRisk) {
                // This is a dummy risk - use default comprehensive assessment approach
                $comprehensiveData['risk_id'] = $primaryRisk->id;
                
                // Create default comprehensive assessment based on overall risk data
                $riskPoints = $client->overall_risk_points ?? 0;
                $riskRating = $client->overall_risk_rating ?? 'Not Assessed';
                
                // Distribute points across categories (simple distribution)
                $pointsPerCategory = max(1, floor($riskPoints / 4)); // Distribute across 4 categories
                
                // Determine impact and likelihood based on risk rating
                $impact = $this->getImpactFromRating($riskRating);
                $likelihood = $this->getLikelihoodFromRating($riskRating);
                
                // Add all four risk categories with default data
                $comprehensiveData['cr_risk_id'] = 'CR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
                $comprehensiveData['cr_risk_name'] = 'Client Risk Assessment';
                $comprehensiveData['cr_impact'] = $impact;
                $comprehensiveData['cr_likelihood'] = $likelihood;
                $comprehensiveData['cr_risk_rating'] = $this->normalizeRiskRating($riskRating);
                $comprehensiveData['cr_points'] = $pointsPerCategory;
                $comprehensiveData['cr_mitigation'] = 'Standard client risk mitigation measures';
                $comprehensiveData['cr_owner'] = 'System';
                $comprehensiveData['cr_status'] = 'Open';
                
                $comprehensiveData['sr_risk_id'] = 'SR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
                $comprehensiveData['sr_risk_name'] = 'Service Risk Assessment';
                $comprehensiveData['sr_impact'] = $impact;
                $comprehensiveData['sr_likelihood'] = $likelihood;
                $comprehensiveData['sr_risk_rating'] = $this->normalizeRiskRating($riskRating);
                $comprehensiveData['sr_points'] = $pointsPerCategory;
                $comprehensiveData['sr_mitigation'] = 'Standard service risk mitigation measures';
                $comprehensiveData['sr_owner'] = 'System';
                $comprehensiveData['sr_status'] = 'Open';
                
                $comprehensiveData['pr_risk_id'] = 'PR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
                $comprehensiveData['pr_risk_name'] = 'Payment Risk Assessment';
                $comprehensiveData['pr_impact'] = $impact;
                $comprehensiveData['pr_likelihood'] = $likelihood;
                $comprehensiveData['pr_risk_rating'] = $this->normalizeRiskRating($riskRating);
                $comprehensiveData['pr_points'] = $pointsPerCategory;
                $comprehensiveData['pr_mitigation'] = 'Standard payment risk mitigation measures';
                $comprehensiveData['pr_owner'] = 'System';
                $comprehensiveData['pr_status'] = 'Open';
                
                $comprehensiveData['dr_risk_id'] = 'DR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
                $comprehensiveData['dr_risk_name'] = 'Delivery Risk Assessment';
                $comprehensiveData['dr_impact'] = $impact;
                $comprehensiveData['dr_likelihood'] = $likelihood;
                $comprehensiveData['dr_risk_rating'] = $this->normalizeRiskRating($riskRating);
                $comprehensiveData['dr_points'] = $pointsPerCategory;
                $comprehensiveData['dr_mitigation'] = 'Standard delivery risk mitigation measures';
                $comprehensiveData['dr_owner'] = 'System';
                $comprehensiveData['dr_status'] = 'Open';
            } else {
                // Client has real individual risk records - use them
                $comprehensiveData['risk_id'] = $primaryRisk->id;

                // Categorize risks by their categories
                $categorizedRisks = $risks->groupBy('risk_category');

            // Process each risk category
            foreach ($categorizedRisks as $category => $categoryRisks) {
                $risk = $categoryRisks->first(); // Take the first risk from each category
                
                // Map category to comprehensive assessment fields
                switch (strtoupper($category)) {
                    case 'CLIENT RISK':
                    case 'CLIENT':
                        $comprehensiveData = array_merge($comprehensiveData, [
                            'cr_risk_id' => 'CR-' . str_pad($risk->id, 2, '0', STR_PAD_LEFT),
                            'cr_risk_name' => $risk->title,
                            'cr_impact' => $risk->impact ?? 'Not Assessed',
                            'cr_likelihood' => $risk->likelihood ?? 'Not Assessed',
                            'cr_risk_rating' => $risk->risk_rating ?? 'Not Assessed',
                            'cr_points' => $this->calculateRiskPoints($risk),
                            'cr_mitigation' => $risk->mitigation_measures ?? 'Not Specified',
                            'cr_owner' => 'System',
                            'cr_status' => 'Open',
                        ]);
                        break;
                        
                    case 'SERVICE RISK':
                    case 'SERVICE':
                        $comprehensiveData = array_merge($comprehensiveData, [
                            'sr_risk_id' => 'SR-' . str_pad($risk->id, 2, '0', STR_PAD_LEFT),
                            'sr_risk_name' => $risk->title,
                            'sr_impact' => $risk->impact ?? 'Not Assessed',
                            'sr_likelihood' => $risk->likelihood ?? 'Not Assessed',
                            'sr_risk_rating' => $risk->risk_rating ?? 'Not Assessed',
                            'sr_points' => $this->calculateRiskPoints($risk),
                            'sr_mitigation' => $risk->mitigation_measures ?? 'Not Specified',
                            'sr_owner' => 'System',
                            'sr_status' => 'Open',
                        ]);
                        break;
                        
                    case 'PAYMENT RISK':
                    case 'PAYMENT':
                        $comprehensiveData = array_merge($comprehensiveData, [
                            'pr_risk_id' => 'PR-' . str_pad($risk->id, 2, '0', STR_PAD_LEFT),
                            'pr_risk_name' => $risk->title,
                            'pr_impact' => $risk->impact ?? 'Not Assessed',
                            'pr_likelihood' => $risk->likelihood ?? 'Not Assessed',
                            'pr_risk_rating' => $risk->risk_rating ?? 'Not Assessed',
                            'pr_points' => $this->calculateRiskPoints($risk),
                            'pr_mitigation' => $risk->mitigation_measures ?? 'Not Specified',
                            'pr_owner' => 'System',
                            'pr_status' => 'Open',
                        ]);
                        break;
                        
                    case 'DELIVERY RISK':
                    case 'DELIVERY':
                        $comprehensiveData = array_merge($comprehensiveData, [
                            'dr_risk_id' => 'DR-' . str_pad($risk->id, 2, '0', STR_PAD_LEFT),
                            'dr_risk_name' => $risk->title,
                            'dr_impact' => $risk->impact ?? 'Not Assessed',
                            'dr_likelihood' => $risk->likelihood ?? 'Not Assessed',
                            'dr_risk_rating' => $risk->risk_rating ?? 'Not Assessed',
                            'dr_points' => $this->calculateRiskPoints($risk),
                            'dr_mitigation' => $risk->mitigation_measures ?? 'Not Specified',
                            'dr_owner' => 'System',
                            'dr_status' => 'Open',
                        ]);
                        break;
                }
            }
            }
        } else {
            // Client has no individual risk records - create a dummy risk record first
            $dummyRisk = \App\Models\Risk::create([
                'title' => 'Comprehensive Risk Assessment - ' . $client->name,
                'description' => 'Comprehensive risk assessment created for client approval',
                'risk_category' => 'Client Risk',
                'risk_rating' => $this->normalizeRiskRating($client->overall_risk_rating ?? 'Low'),
                'impact' => $this->getImpactFromRating($client->overall_risk_rating ?? 'Low'),
                'likelihood' => $this->getLikelihoodFromRating($client->overall_risk_rating ?? 'Low'),
                'status' => 'Open',
                'client_id' => $client->id,
                'mitigation_measures' => 'Standard comprehensive risk mitigation measures',
                'created_by' => 1, // System user
            ]);
            
            $comprehensiveData['risk_id'] = $dummyRisk->id;
            
            // Create default comprehensive assessment based on overall risk data
            $riskPoints = $client->overall_risk_points ?? 0;
            $riskRating = $client->overall_risk_rating ?? 'Not Assessed';
            
            // Distribute points across categories (simple distribution)
            $pointsPerCategory = max(1, floor($riskPoints / 4)); // Distribute across 4 categories
            
            // Determine impact and likelihood based on risk rating
            $impact = $this->getImpactFromRating($riskRating);
            $likelihood = $this->getLikelihoodFromRating($riskRating);
            
            // Add all four risk categories with default data
            $comprehensiveData['cr_risk_id'] = 'CR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
            $comprehensiveData['cr_risk_name'] = 'Client Risk Assessment';
            $comprehensiveData['cr_impact'] = $impact;
            $comprehensiveData['cr_likelihood'] = $likelihood;
            $comprehensiveData['cr_risk_rating'] = $this->normalizeRiskRating($riskRating);
            $comprehensiveData['cr_points'] = $pointsPerCategory;
            $comprehensiveData['cr_mitigation'] = 'Standard client risk mitigation measures';
            $comprehensiveData['cr_owner'] = 'System';
            $comprehensiveData['cr_status'] = 'Open';
            
            $comprehensiveData['sr_risk_id'] = 'SR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
            $comprehensiveData['sr_risk_name'] = 'Service Risk Assessment';
            $comprehensiveData['sr_impact'] = $impact;
            $comprehensiveData['sr_likelihood'] = $likelihood;
            $comprehensiveData['sr_risk_rating'] = $this->normalizeRiskRating($riskRating);
            $comprehensiveData['sr_points'] = $pointsPerCategory;
            $comprehensiveData['sr_mitigation'] = 'Standard service risk mitigation measures';
            $comprehensiveData['sr_owner'] = 'System';
            $comprehensiveData['sr_status'] = 'Open';
            
            $comprehensiveData['pr_risk_id'] = 'PR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
            $comprehensiveData['pr_risk_name'] = 'Payment Risk Assessment';
            $comprehensiveData['pr_impact'] = $impact;
            $comprehensiveData['pr_likelihood'] = $likelihood;
            $comprehensiveData['pr_risk_rating'] = $this->normalizeRiskRating($riskRating);
            $comprehensiveData['pr_points'] = $pointsPerCategory;
            $comprehensiveData['pr_mitigation'] = 'Standard payment risk mitigation measures';
            $comprehensiveData['pr_owner'] = 'System';
            $comprehensiveData['pr_status'] = 'Open';
            
            $comprehensiveData['dr_risk_id'] = 'DR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
            $comprehensiveData['dr_risk_name'] = 'Delivery Risk Assessment';
            $comprehensiveData['dr_impact'] = $impact;
            $comprehensiveData['dr_likelihood'] = $likelihood;
            $comprehensiveData['dr_risk_rating'] = $this->normalizeRiskRating($riskRating);
            $comprehensiveData['dr_points'] = $pointsPerCategory;
            $comprehensiveData['dr_mitigation'] = 'Standard delivery risk mitigation measures';
            $comprehensiveData['dr_owner'] = 'System';
            $comprehensiveData['dr_status'] = 'Open';
        }

        // Create the comprehensive risk assessment
        return ComprehensiveRiskAssessment::create($comprehensiveData);
    }

    /**
     * Calculate risk points based on impact and likelihood
     */
    private function calculateRiskPoints($risk)
    {
        $impactPoints = [
            'High' => 3,
            'Medium' => 2,
            'Low' => 1,
        ];

        $likelihoodPoints = [
            'High' => 3,
            'Medium' => 2,
            'Low' => 1,
        ];

        $impact = $impactPoints[$risk->impact] ?? 0;
        $likelihood = $likelihoodPoints[$risk->likelihood] ?? 0;

        return $impact * $likelihood;
    }

    /**
     * Get impact level from risk rating
     */
    private function getImpactFromRating($riskRating)
    {
        $rating = strtolower($riskRating);
        
        if (str_contains($rating, 'high') || str_contains($rating, 'critical')) {
            return 'High';
        } elseif (str_contains($rating, 'medium')) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }

    /**
     * Get likelihood level from risk rating
     */
    private function getLikelihoodFromRating($riskRating)
    {
        $rating = strtolower($riskRating);
        
        if (str_contains($rating, 'high') || str_contains($rating, 'critical')) {
            return 'High';
        } elseif (str_contains($rating, 'medium')) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }

    /**
     * Normalize risk rating to valid enum values
     */
    private function normalizeRiskRating($riskRating)
    {
        $rating = strtolower($riskRating);
        
        if (str_contains($rating, 'high') || str_contains($rating, 'critical')) {
            return 'High';
        } elseif (str_contains($rating, 'medium')) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }
}