<?php

namespace App\Services;

use App\Models\SystemSetting;

class RiskClassificationService
{
    /**
     * Get individual risk rating based on points using configurable thresholds
     */
    public static function getIndividualRiskRating(int $points): array
    {
        $highThreshold = SystemSetting::get('risk_threshold_high', 15);
        $criticalThreshold = SystemSetting::get('risk_threshold_critical', 20);
        
        if ($points >= $criticalThreshold) {
            return [
                'level' => 'Critical',
                'rating' => 'Critical',
                'color' => 'danger',
                'description' => 'Critical Risk - Immediate action required'
            ];
        } elseif ($points >= $highThreshold) {
            return [
                'level' => 'High',
                'rating' => 'High',
                'color' => 'danger',
                'description' => 'High Risk - Priority attention needed'
            ];
        } elseif ($points >= ($highThreshold - 5)) {
            return [
                'level' => 'Medium',
                'rating' => 'Medium',
                'color' => 'warning',
                'description' => 'Medium Risk - Monitor closely'
            ];
        } else {
            return [
                'level' => 'Low',
                'rating' => 'Low',
                'color' => 'success',
                'description' => 'Low Risk - Standard monitoring'
            ];
        }
    }

    /**
     * Get overall risk assessment based on total points (as per your table)
     */
    public static function getOverallRiskAssessment(int $totalPoints): array
    {
        if ($totalPoints >= 20) {
            return [
                'rating' => 'Very High-risk',
                'client_acceptance' => 'Do not accept client',
                'monitoring' => 'N/A',
                'color' => 'danger',
                'description' => 'Very High Risk - Do not accept client'
            ];
        } elseif ($totalPoints >= 17) {
            return [
                'rating' => 'High-risk',
                'client_acceptance' => 'Accept client',
                'monitoring' => 'Quarterly review',
                'color' => 'danger',
                'description' => 'High Risk - Accept with enhanced due diligence'
            ];
        } elseif ($totalPoints >= 15) {
            return [
                'rating' => 'Medium-risk',
                'client_acceptance' => 'Accept client',
                'monitoring' => 'Bi-Annually',
                'color' => 'warning',
                'description' => 'Medium Risk - Accept with standard monitoring'
            ];
        } else {
            return [
                'rating' => 'Low-risk',
                'client_acceptance' => 'Accept client',
                'monitoring' => 'Annually',
                'color' => 'success',
                'description' => 'Low Risk - Accept with basic monitoring'
            ];
        }
    }

    /**
     * Get risk classification based on total points using settings thresholds
     */
    public static function classifyRisk(int $totalPoints): array
    {
        $highThreshold = SystemSetting::get('risk_threshold_high', 15);
        $criticalThreshold = SystemSetting::get('risk_threshold_critical', 20);
        
        if ($totalPoints >= $criticalThreshold) {
            return [
                'level' => 'Critical',
                'rating' => 'Critical',
                'color' => 'danger',
                'description' => 'Critical Risk - Immediate action required'
            ];
        } elseif ($totalPoints >= $highThreshold) {
            return [
                'level' => 'High',
                'rating' => 'High', 
                'color' => 'danger',
                'description' => 'High Risk - Priority attention needed'
            ];
        } elseif ($totalPoints >= ($highThreshold - 5)) {
            return [
                'level' => 'Medium',
                'rating' => 'Medium',
                'color' => 'warning',
                'description' => 'Medium Risk - Monitor closely'
            ];
        } else {
            return [
                'level' => 'Low',
                'rating' => 'Low',
                'color' => 'success',
                'description' => 'Low Risk - Standard monitoring'
            ];
        }
    }
    
    /**
     * Risk Assessment Matrix based on your system's matrix
     * This matches the matrix shown in your image
     */
    private const RISK_MATRIX = [
        'High' => [
            'High' => 5,    // High Impact + High Likelihood = 5 points
            'Medium' => 5,  // High Impact + Medium Likelihood = 5 points
            'Low' => 4      // High Impact + Low Likelihood = 4 points
        ],
        'Medium' => [
            'High' => 4,    // Medium Impact + High Likelihood = 4 points
            'Medium' => 3,  // Medium Impact + Medium Likelihood = 3 points
            'Low' => 2      // Medium Impact + Low Likelihood = 2 points
        ],
        'Low' => [
            'High' => 3,    // Low Impact + High Likelihood = 3 points
            'Medium' => 1,  // Low Impact + Medium Likelihood = 1 point
            'Low' => 1      // Low Impact + Low Likelihood = 1 point
        ]
    ];

    /**
     * Calculate risk points from impact and likelihood using the correct matrix
     */
    public static function calculateRiskPoints($impact, $likelihood)
    {
        $impact = ucfirst(strtolower($impact));
        $likelihood = ucfirst(strtolower($likelihood));
        
        return self::RISK_MATRIX[$impact][$likelihood] ?? 1;
    }
    
    /**
     * Get all risks classified by current settings
     */
    public static function getClassifiedRisks()
    {
        $highThreshold = SystemSetting::get('risk_threshold_high', 15);
        $criticalThreshold = SystemSetting::get('risk_threshold_critical', 20);
        
        // Only get active risks, exclude soft-deleted ones
        $risks = \App\Models\Risk::whereNull('deleted_at')->get();
        $classified = [
            'critical' => collect(),
            'high' => collect(),
            'medium' => collect(),
            'low' => collect(),
        ];
        
        foreach ($risks as $risk) {
            $points = self::calculateRiskPoints($risk->impact, $risk->likelihood);
            $classification = self::getIndividualRiskRating($points);
            $level = strtolower($classification['level']);
            
            $classified[$level]->push($risk);
        }
        
        return $classified;
    }
    
    /**
     * Get risk counts based on current settings
     */
    public static function getRiskCounts()
    {
        return \Illuminate\Support\Facades\Cache::remember('risk_counts', 300, function() {
            $highThreshold = SystemSetting::get('risk_threshold_high', 15);
            $criticalThreshold = SystemSetting::get('risk_threshold_critical', 20);
            
            // Use raw SQL for better performance
            $risks = \Illuminate\Support\Facades\DB::select("
                SELECT impact, likelihood, COUNT(*) as count
                FROM risks 
                WHERE deleted_at IS NULL 
                GROUP BY impact, likelihood
            ");
            
            $classified = [
                'critical' => 0,
                'high' => 0,
                'medium' => 0,
                'low' => 0,
                'total' => 0,
            ];
            
            foreach ($risks as $risk) {
                $points = self::calculateRiskPoints($risk->impact, $risk->likelihood);
                $classification = self::getIndividualRiskRating($points);
                $level = strtolower($classification['level']);
                
                $classified[$level] += $risk->count;
                $classified['total'] += $risk->count;
            }
            
            return $classified;
        });
    }
    
    /**
     * Update risk classification for all existing risks
     */
    public static function updateAllRiskClassifications()
    {
        // Only update active risks, exclude soft-deleted ones
        $risks = \App\Models\Risk::whereNull('deleted_at')->get();
        
        foreach ($risks as $risk) {
            $points = self::calculateRiskPoints($risk->impact, $risk->likelihood);
            $classification = self::getIndividualRiskRating($points);
            
            $risk->update([
                'risk_level' => $classification['level'],
                'risk_rating' => $classification['rating'],
            ]);
        }
        
        return $risks->count();
    }
    
    /**
     * Calculate overall risk assessment for a client based on all their risks
     */
    public static function calculateClientRiskAssessment($clientRisks)
    {
        $totalPoints = 0;
        $riskDetails = [];
        
        foreach ($clientRisks as $risk) {
            $points = self::calculateRiskPoints($risk->impact, $risk->likelihood);
            $totalPoints += $points;
            
            $individualRating = self::getIndividualRiskRating($points);
            
            $riskDetails[] = [
                'risk_id' => $risk->id,
                'title' => $risk->title,
                'impact' => $risk->impact,
                'likelihood' => $risk->likelihood,
                'points' => $points,
                'rating' => $individualRating['rating'],
                'level' => $individualRating['level']
            ];
        }
        
        $overallAssessment = self::getOverallRiskAssessment($totalPoints);
        
        return [
            'total_points' => $totalPoints,
            'risk_count' => count($clientRisks),
            'overall_rating' => $overallAssessment['rating'],
            'client_acceptance' => $overallAssessment['client_acceptance'],
            'monitoring_frequency' => $overallAssessment['monitoring'],
            'color' => $overallAssessment['color'],
            'description' => $overallAssessment['description'],
            'risk_details' => $riskDetails
        ];
    }
}
