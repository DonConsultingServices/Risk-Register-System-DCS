<?php

namespace App\Services;

use App\Models\Risk;
use App\Models\PredefinedRisk;
use App\Models\RiskCategory;

class RiskAssessmentService
{
    /**
     * Risk Assessment Matrix based on Impact × Likelihood
     * This matches your system's matrix shown in the image
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
     * Calculate risk points based on Impact and Likelihood
     */
    public function calculateRiskPoints(string $impact, string $likelihood): int
    {
        return self::RISK_MATRIX[$impact][$likelihood] ?? 0;
    }

    /**
     * Get risk rating based on points
     */
    public function getRiskRating(int $points): string
    {
        if ($points >= 4) return 'High';
        if ($points >= 2) return 'Medium';
        return 'Low';
    }

    /**
     * Get overall risk rating and actions based on total points
     */
    public function getOverallRiskRating(int $totalPoints): array
    {
        if ($totalPoints >= 20) {
            return [
                'rating' => 'Very High-risk',
                'client_acceptance' => 'Do not accept client',
                'monitoring' => 'N/A'
            ];
        } elseif ($totalPoints >= 17) {
            return [
                'rating' => 'High-risk',
                'client_acceptance' => 'Accept client',
                'monitoring' => 'Quarterly review'
            ];
        } elseif ($totalPoints >= 15) {
            return [
                'rating' => 'Medium-risk',
                'client_acceptance' => 'Accept client',
                'monitoring' => 'Bi-Annually'
            ];
        } else {
            return [
                'rating' => 'Low-risk',
                'client_acceptance' => 'Accept client',
                'monitoring' => 'Annually'
            ];
        }
    }

    /**
     * Calculate total risk points for a category
     */
    public function calculateCategoryRiskPoints(RiskCategory $category): array
    {
        $risks = $category->risks()->withTrashed()->get();
        $predefinedRisks = $category->predefinedRisks()->get();

        $totalPoints = 0;
        $riskCount = 0;
        $riskLevels = ['High' => 0, 'Medium' => 0, 'Low' => 0];
        $riskDetails = [];

        // Calculate points for actual risks
        foreach ($risks as $risk) {
            $points = $this->calculateRiskPoints($risk->impact, $risk->likelihood);
            $totalPoints += $points;
            $riskCount++;
            
            $riskLevel = $this->getRiskRating($points);
            $riskLevels[$riskLevel]++;
            
            $riskDetails[] = [
                'id' => $risk->id,
                'title' => $risk->title,
                'impact' => $risk->impact,
                'likelihood' => $risk->likelihood,
                'points' => $points,
                'risk_rating' => $riskLevel
            ];
        }

        // Calculate points for predefined risks
        foreach ($predefinedRisks as $predefinedRisk) {
            $points = $this->calculateRiskPoints($predefinedRisk->impact, $predefinedRisk->likelihood);
            $totalPoints += $points;
            
            $riskLevel = $this->getRiskRating($points);
            $riskLevels[$riskLevel]++;
            
            $riskDetails[] = [
                'id' => $predefinedRisk->id,
                'title' => $predefinedRisk->title,
                'impact' => $predefinedRisk->impact,
                'likelihood' => $predefinedRisk->likelihood,
                'points' => $points,
                'risk_rating' => $riskLevel,
                'is_predefined' => true
            ];
        }

        $overallRating = $this->getOverallRiskRating($totalPoints);

        return [
            'total_points' => $totalPoints,
            'risk_count' => $riskCount,
            'predefined_count' => $predefinedRisks->count(),
            'risk_levels' => $riskLevels,
            'overall_rating' => $overallRating,
            'risk_details' => $riskDetails
        ];
    }

    /**
     * Get risk statistics for all categories
     */
    public function getCategoriesRiskStatistics(): array
    {
        $categories = RiskCategory::with(['risks', 'predefinedRisks'])->get();
        $statistics = [];

        foreach ($categories as $category) {
            $statistics[$category->id] = $this->calculateCategoryRiskPoints($category);
        }

        return $statistics;
    }

    /**
     * Get risk matrix for display
     */
    public function getRiskMatrix(): array
    {
        return self::RISK_MATRIX;
    }
}
