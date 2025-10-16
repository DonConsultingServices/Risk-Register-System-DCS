<?php

namespace App\Services;

use App\Models\PredefinedRisk;

class RiskCalculationService
{
    /**
     * Risk level thresholds
     */
    const RISK_THRESHOLDS = [
        'Very High' => 20,
        'High' => 17,
        'Medium' => 15,
        'Low' => 10,
        'Very Low' => 0,
    ];

    /**
     * Risk level points
     */
    const RISK_POINTS = [
        'High' => 5,
        'Medium' => 3,
        'Low' => 1,
    ];

    /**
     * Calculate total risk score from selected risks
     */
    public static function calculateTotalScore($selectedRisks)
    {
        $totalScore = 0;
        
        foreach ($selectedRisks as $risk) {
            $totalScore += self::RISK_POINTS[$risk->risk_level] ?? 0;
        }
        
        return $totalScore;
    }

    /**
     * Determine risk rating based on total score
     */
    public static function determineRiskRating($totalScore)
    {
        foreach (self::RISK_THRESHOLDS as $rating => $threshold) {
            if ($totalScore >= $threshold) {
                return $rating;
            }
        }
        
        return 'Low';
    }

    /**
     * Determine client decision based on risk rating
     */
    public static function determineClientDecision($riskRating)
    {
        switch ($riskRating) {
            case 'Very High':
                return 'Do not accept client';
            case 'High':
                return 'Accept client';
            case 'Medium':
                return 'Accept client';
            case 'Low':
                return 'Accept client';
            case 'Very Low':
                return 'Accept client';
            default:
                return 'Under Review';
        }
    }

    /**
     * Determine monitoring frequency based on risk rating according to FIC compliance matrix
     */
    public static function determineMonitoringFrequency($riskRating)
    {
        switch ($riskRating) {
            case 'Very High-risk':
                return 'N/A'; // Do not accept client
            case 'High-risk':
                return 'Quarterly review';
            case 'Medium-risk':
                return 'Bi-Annually';
            case 'Low-risk':
                return 'Annually';
            default:
                return 'Annually';
        }
    }

    /**
     * Calculate risk matrix position
     */
    public static function calculateRiskMatrixPosition($impact, $likelihood)
    {
        $impactScores = ['Low' => 1, 'Medium' => 2, 'High' => 3];
        $likelihoodScores = ['Low' => 1, 'Medium' => 2, 'High' => 3];
        
        $impactScore = $impactScores[$impact] ?? 1;
        $likelihoodScore = $likelihoodScores[$likelihood] ?? 1;
        
        $totalScore = $impactScore * $likelihoodScore;
        
        if ($totalScore >= 6) {
            return 'High';
        } elseif ($totalScore >= 4) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }

    /**
     * Get risk level color for UI
     */
    public static function getRiskLevelColor($riskLevel)
    {
        return [
            'Very High' => 'danger',
            'High' => 'danger',
            'Medium' => 'warning',
            'Low' => 'success',
            'Very Low' => 'success'
        ][$riskLevel] ?? 'secondary';
    }

    /**
     * Calculate weighted risk score
     */
    public static function calculateWeightedScore($risks, $weights = [])
    {
        $totalScore = 0;
        $totalWeight = 0;
        
        foreach ($risks as $risk) {
            $weight = $weights[$risk->id] ?? 1;
            $score = self::RISK_POINTS[$risk->risk_level] ?? 0;
            
            $totalScore += $score * $weight;
            $totalWeight += $weight;
        }
        
        if ($totalWeight === 0) {
            return 0;
        }
        
        return round($totalScore / $totalWeight, 2);
    }
}
