<?php

namespace App\Services;

use App\Models\Risk;
use App\Models\ComprehensiveRiskAssessment;
use Illuminate\Support\Facades\Log;

class ComprehensiveRiskAssessmentService
{
    /**
     * Official Risk Assessment Matrix for Namibia FIC compliance
     */
    private const RISK_MATRIX = [
        'High' => [
            'High' => ['rating' => 'High', 'points' => 5],
            'Medium' => ['rating' => 'High', 'points' => 5],
            'Low' => ['rating' => 'High', 'points' => 4]
        ],
        'Medium' => [
            'High' => ['rating' => 'Medium', 'points' => 4],
            'Medium' => ['rating' => 'Medium', 'points' => 3],
            'Low' => ['rating' => 'Medium', 'points' => 2]
        ],
        'Low' => [
            'High' => ['rating' => 'Low', 'points' => 3],
            'Medium' => ['rating' => 'Low', 'points' => 1],
            'Low' => ['rating' => 'Low', 'points' => 1]
        ]
    ];

    /**
     * Overall Risk Rating Thresholds
     */
    private const RISK_THRESHOLDS = [
        20 => ['rating' => 'Very High-risk', 'acceptance' => 'Do not accept client', 'monitoring' => 'N/A'],
        17 => ['rating' => 'High-risk', 'acceptance' => 'Accept client', 'monitoring' => 'Quarterly review'],
        15 => ['rating' => 'Medium-risk', 'acceptance' => 'Accept client', 'monitoring' => 'Bi-Annually'],
        10 => ['rating' => 'Low-risk', 'acceptance' => 'Accept client', 'monitoring' => 'Annually'],
        0 => ['rating' => 'Low-risk', 'acceptance' => 'Accept client', 'monitoring' => 'Annually']
    ];

    /**
     * Calculate individual risk rating and points based on impact and likelihood
     */
    public function calculateIndividualRisk(string $impact, string $likelihood): array
    {
        $impact = ucfirst(strtolower($impact));
        $likelihood = ucfirst(strtolower($likelihood));
        
        if (!isset(self::RISK_MATRIX[$impact][$likelihood])) {
            Log::warning('Invalid impact/likelihood combination', ['impact' => $impact, 'likelihood' => $likelihood]);
            return ['rating' => 'Low', 'points' => 1];
        }
        
        return self::RISK_MATRIX[$impact][$likelihood];
    }

    /**
     * Calculate overall risk assessment based on total points
     */
    public function calculateOverallRisk(int $totalPoints): array
    {
        foreach (self::RISK_THRESHOLDS as $threshold => $assessment) {
            if ($totalPoints >= $threshold) {
                return $assessment;
            }
        }
        
        return self::RISK_THRESHOLDS[0];
    }

    /**
     * Create comprehensive risk assessment record
     */
    public function createComprehensiveAssessment(array $data): ComprehensiveRiskAssessment
    {
        try {
            $assessment = ComprehensiveRiskAssessment::create([
                'risk_id' => $data['risk_id'],
                'sr_risk_id' => $data['sr_risk_id'] ?? null,
                'sr_risk_name' => $data['sr_risk_name'] ?? null,
                'sr_impact' => $data['sr_impact'] ?? null,
                'sr_likelihood' => $data['sr_likelihood'] ?? null,
                'sr_risk_rating' => $data['sr_risk_rating'] ?? null,
                'sr_points' => $data['sr_points'] ?? 0,
                'sr_mitigation' => $data['sr_mitigation'] ?? null,
                'sr_owner' => $data['sr_owner'] ?? null,
                'sr_status' => $data['sr_status'] ?? 'Open',
                
                'cr_risk_id' => $data['cr_risk_id'] ?? null,
                'cr_risk_name' => $data['cr_risk_name'] ?? null,
                'cr_impact' => $data['cr_impact'] ?? null,
                'cr_likelihood' => $data['cr_likelihood'] ?? null,
                'cr_risk_rating' => $data['cr_risk_rating'] ?? null,
                'cr_points' => $data['cr_points'] ?? 0,
                'cr_mitigation' => $data['cr_mitigation'] ?? null,
                'cr_owner' => $data['cr_owner'] ?? null,
                'cr_status' => $data['cr_status'] ?? 'Open',
                
                'pr_risk_id' => $data['pr_risk_id'] ?? null,
                'pr_risk_name' => $data['pr_risk_name'] ?? null,
                'pr_impact' => $data['pr_impact'] ?? null,
                'pr_likelihood' => $data['pr_likelihood'] ?? null,
                'pr_risk_rating' => $data['pr_risk_rating'] ?? null,
                'pr_points' => $data['pr_points'] ?? 0,
                'pr_mitigation' => $data['pr_mitigation'] ?? null,
                'pr_owner' => $data['pr_owner'] ?? null,
                'pr_status' => $data['pr_status'] ?? 'Open',
                
                'dr_risk_id' => $data['dr_risk_id'] ?? null,
                'dr_risk_name' => $data['dr_risk_name'] ?? null,
                'dr_impact' => $data['dr_impact'] ?? null,
                'dr_likelihood' => $data['dr_likelihood'] ?? null,
                'dr_risk_rating' => $data['dr_risk_rating'] ?? null,
                'dr_points' => $data['dr_points'] ?? 0,
                'dr_mitigation' => $data['dr_mitigation'] ?? null,
                'dr_owner' => $data['dr_owner'] ?? null,
                'dr_status' => $data['dr_status'] ?? 'Open',
                
                'total_points' => $data['total_points'],
                'overall_risk_rating' => $data['overall_risk_rating'],
                'client_acceptance' => $data['client_acceptance'],
                'ongoing_monitoring' => $data['ongoing_monitoring'],
                'created_by' => $data['created_by'],
                'updated_by' => $data['updated_by'],
            ]);
            
            Log::info('Comprehensive risk assessment created', [
                'assessment_id' => $assessment->id,
                'risk_id' => $data['risk_id'],
                'total_points' => $data['total_points']
            ]);
            
            return $assessment;
            
        } catch (\Exception $e) {
            Log::error('Failed to create comprehensive risk assessment', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Validate risk assessment data
     */
    public function validateRiskData(array $data): array
    {
        $errors = [];
        
        // Validate required fields
        $requiredFields = ['risk_id', 'total_points', 'overall_risk_rating', 'client_acceptance', 'ongoing_monitoring'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[] = "Missing required field: {$field}";
            }
        }
        
        // Validate risk points
        if (isset($data['total_points']) && ($data['total_points'] < 0 || $data['total_points'] > 25)) {
            $errors[] = 'Total risk points must be between 0 and 25';
        }
        
        // Validate enum values
        $validRatings = ['Low', 'Medium', 'High'];
        $validImpacts = ['Low', 'Medium', 'High'];
        $validLikelihoods = ['Low', 'Medium', 'High'];
        
        foreach (['sr', 'cr', 'pr', 'dr'] as $category) {
            if (isset($data["{$category}_impact"]) && !in_array($data["{$category}_impact"], $validImpacts)) {
                $errors[] = "Invalid {$category} impact value";
            }
            if (isset($data["{$category}_likelihood"]) && !in_array($data["{$category}_likelihood"], $validLikelihoods)) {
                $errors[] = "Invalid {$category} likelihood value";
            }
            if (isset($data["{$category}_risk_rating"]) && !in_array($data["{$category}_risk_rating"], $validRatings)) {
                $errors[] = "Invalid {$category} risk rating value";
            }
        }
        
        return $errors;
    }

    /**
     * Get risk assessment summary for reporting
     */
    public function getAssessmentSummary(int $riskId): array
    {
        $assessment = ComprehensiveRiskAssessment::where('risk_id', $riskId)->first();
        
        if (!$assessment) {
            return ['error' => 'Assessment not found'];
        }
        
        return [
            'total_points' => $assessment->total_points,
            'overall_rating' => $assessment->overall_risk_rating,
            'client_acceptance' => $assessment->client_acceptance,
            'monitoring_frequency' => $assessment->ongoing_monitoring,
            'risk_breakdown' => $assessment->risk_breakdown,
            'highest_risk_category' => $assessment->highest_risk_category,
            'created_at' => $assessment->created_at,
            'updated_at' => $assessment->updated_at
        ];
    }

    /**
     * Get regulatory compliance report
     */
    public function getRegulatoryReport(): array
    {
        $assessments = ComprehensiveRiskAssessment::with('risk')->get();
        
        $report = [
            'total_assessments' => $assessments->count(),
            'risk_distribution' => [
                'Very High-risk' => $assessments->where('overall_risk_rating', 'Very High-risk')->count(),
                'High-risk' => $assessments->where('overall_risk_rating', 'High-risk')->count(),
                'Medium-risk' => $assessments->where('overall_risk_rating', 'Medium-risk')->count(),
                'Low-risk' => $assessments->where('overall_risk_rating', 'Low-risk')->count(),
            ],
            'acceptance_distribution' => [
                'Accepted' => $assessments->where('client_acceptance', 'like', '%Accept%')->count(),
                'Rejected' => $assessments->where('client_acceptance', 'like', '%Do not accept%')->count(),
            ],
            'monitoring_distribution' => [
                'Quarterly' => $assessments->where('ongoing_monitoring', 'like', '%Quarterly%')->count(),
                'Bi-Annually' => $assessments->where('ongoing_monitoring', 'like', '%Bi-Annually%')->count(),
                'Annually' => $assessments->where('ongoing_monitoring', 'like', '%Annually%')->count(),
                'N/A' => $assessments->where('ongoing_monitoring', 'N/A')->count(),
            ]
        ];
        
        return $report;
    }
}
