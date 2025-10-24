<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\PredefinedRisk;
use App\Models\Risk;
use App\Models\RiskThresholdSetting;
use App\Services\RiskCalculationService;
use App\Services\NotificationService;
use App\Services\ClientHistoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ClientRiskAssessmentController extends Controller
{
    /**
     * Display the risk assessment interface - OPTIMIZED VERSION
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->processAssessment($request);
        }

        try {
            // Use caching for predefined risks (10 minutes cache - rarely changes)
            $predefinedRisks = \Illuminate\Support\Facades\Cache::remember('predefined_risks', 600, function() {
                $risks = PredefinedRisk::with('category')->get();
                if ($risks->isEmpty()) {
                    return collect();
                }
                return $risks->groupBy('category.name');
            });
            
            // Use caching for recent assessments (2 minutes cache)
            $recentAssessments = \Illuminate\Support\Facades\Cache::remember('recent_assessments_' . auth()->id(), 120, function() {
                return $this->getRecentAssessments();
            });

            return view('client-risk-assessment.index', compact('predefinedRisks', 'recentAssessments'));
        } catch (\Exception $e) {
            Log::error('Client risk assessment index error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('dashboard')
                ->with('error', 'Unable to load risk assessment page. Please try again.');
        }
    }

    /**
     * Process the risk assessment
     */
    private function processAssessment(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'identification_status' => 'required|in:Yes,No',
            'screening_date' => 'required|date|date_equals:' . date('Y-m-d'),
            'screening_result' => 'nullable|string|max:255',
            'selected_risks' => 'required|array|min:1',
            'selected_risks.*' => 'exists:predefined_risks,id',
        ], [
            'screening_date.date_equals' => 'Assessment date must be exactly today\'s date. Only current date assessments are allowed.',
        ]);

        try {
            DB::beginTransaction();

            // Calculate risk score first to determine automatic rejection
            $selectedRisks = PredefinedRisk::whereIn('id', $validated['selected_risks'])->get();
            $riskScore = RiskCalculationService::calculateTotalScore($selectedRisks);
            $riskRating = RiskCalculationService::determineRiskRating($riskScore);
            $clientDecision = RiskCalculationService::determineClientDecision($riskRating);

            // Check if automatic rejection is enabled and if client meets rejection criteria
            $autoRejectionEnabled = RiskThresholdSetting::isAutoRejectionEnabled();
            $autoRejectionThreshold = RiskThresholdSetting::getAutoRejectionThreshold();
            $shouldAutoReject = $autoRejectionEnabled && 
                               ($riskRating === 'Very High' || 
                                $clientDecision === 'Do not accept client' || 
                                $riskScore >= $autoRejectionThreshold);

            // Determine assessment status based on risk level and user role
            if ($shouldAutoReject) {
                // Automatically reject very high risk clients
                $assessmentStatus = 'rejected';
                $clientStatus = 'Rejected';
                $rejectionReason = 'Automatically rejected due to high risk assessment (Score: ' . $riskScore . ', Rating: ' . $riskRating . ', Threshold: ' . $autoRejectionThreshold . ')';
            } elseif (auth()->user()->isAdmin()) {
                // Admins can approve clients directly (except very high risk)
                $assessmentStatus = 'approved';
                $clientStatus = 'Active';
            } else {
                // Other users need approval
                $assessmentStatus = 'pending';
                $clientStatus = 'Pending';
            }

            // Prepare client data - ALWAYS populate all fields with defaults
            $clientData = [
                'name' => $validated['client_name'],
                'email' => $validated['client_email'] ?? $validated['client_name'] . '@example.com',
                'phone' => $validated['client_phone'] ?? 'Not Provided',
                'company' => $validated['company_name'] ?? 'Not Specified',
                'industry' => $validated['client_industry'] ?? 'Not Specified',
                'client_screening_date' => $validated['screening_date'],
                'client_screening_result' => $validated['screening_result'],
                'status' => $clientStatus,
                'assessment_status' => $assessmentStatus,
                'created_by' => auth()->id(),
                // Add KYC fields with defaults
                'client_type' => $validated['client_type'] ?? 'Individual',
                'nationality' => $validated['nationality'] ?? 'Namibian',
                'is_minor' => $validated['is_minor'] ?? false,
                'income_source' => $validated['income_source'] ?? 'Not Specified',
                'gender' => $validated['gender'] ?? 'Not Specified',
            ];

            // Add rejection reason if client is automatically rejected
            if ($assessmentStatus === 'rejected') {
                $clientData['rejection_reason'] = $rejectionReason;
            }

            // Calculate monitoring frequency
            $monitoringFrequency = RiskCalculationService::determineMonitoringFrequency($riskRating);

            // Prepare risk assessment data
            $riskData = [
                'overall_risk_points' => $riskScore,
                'overall_risk_rating' => $riskRating,
                'client_acceptance' => $clientDecision,
                'ongoing_monitoring' => $monitoringFrequency
            ];

            // Add DCS fields and approval info based on status
            $assessmentData = [];
            if ($assessmentStatus === 'approved') {
                $assessmentData['dcs_comments'] = 'Risk assessment completed via system';
                $assessmentData['dcs_risk_appetite'] = $this->determineDCSRiskAppetite($riskRating);
                $assessmentData['approved_by'] = auth()->id();
                $assessmentData['approved_at'] = now();
            } elseif ($assessmentStatus === 'rejected') {
                $assessmentData['dcs_comments'] = 'Client automatically rejected due to high risk';
                $assessmentData['dcs_risk_appetite'] = 'Not applicable - Client rejected';
            }

            // Use ClientHistoryService to handle duplicates and history
            $client = ClientHistoryService::createOrUpdateClientWithHistory(
                $clientData, 
                $riskData, 
                $assessmentData
            );

            // Create notification for automatic rejection
            if ($assessmentStatus === 'rejected') {
                NotificationService::createSystemNotification(
                    auth()->id(),
                    'Client Automatically Rejected',
                    "Client '{$validated['client_name']}' was automatically rejected due to very high risk assessment (Score: {$riskScore}, Rating: {$riskRating}).",
                    'high'
                );
                
                // Clear dashboard cache to update counts immediately
                \Cache::forget('dashboard_stats_' . auth()->id());
                \Cache::forget('recent_risks');
                \Cache::forget('client_stats_' . auth()->id());
            }

            // Create risk records
            foreach ($selectedRisks as $predefinedRisk) {
                Risk::create([
                    'title' => $predefinedRisk->title,
                    'description' => $predefinedRisk->description,
                    'risk_category' => $predefinedRisk->category->name,
                    'risk_rating' => $predefinedRisk->risk_level,
                    'impact' => $predefinedRisk->impact,
                    'likelihood' => $predefinedRisk->likelihood,
                    'status' => 'Open',
                    'client_id' => $client->id,
                    'mitigation_measures' => $predefinedRisk->mitigation_measures,
                    'created_by' => auth()->id(),
                ]);
            }

            // Create comprehensive risk assessment for ALL users (not just admins)
            $this->createComprehensiveRiskAssessment($client);

            DB::commit();

            Log::info('Risk assessment completed', [
                'client_id' => $client->id,
                'risk_score' => $riskScore,
                'risk_rating' => $riskRating,
                'user_id' => auth()->id()
            ]);

            return view('client-risk-assessment.results', compact(
                'client',
                'riskScore',
                'riskRating',
                'clientDecision',
                'monitoringFrequency',
                'selectedRisks'
            ));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Risk assessment failed', ['error' => $e->getMessage()]);
            
            return back()->with('error', 'Risk assessment failed. Please try again.');
        }
    }

    /**
     * Get recent assessments - OPTIMIZED VERSION
     */
    private function getRecentAssessments()
    {
        // Use raw SQL for better performance
        $assessments = DB::select("
            SELECT 
                c.id, c.name, c.client_screening_date as screening_date, c.overall_risk_rating, 
                c.overall_risk_points, c.assessment_status, c.created_at,
                SUM(CASE 
                    WHEN r.risk_rating = 'Low' THEN 1
                    WHEN r.risk_rating = 'Medium' THEN 3
                    WHEN r.risk_rating = 'High' THEN 5
                    ELSE 0 
                END) as calculated_risk_score
            FROM clients c
            LEFT JOIN risks r ON c.id = r.client_id AND r.deleted_at IS NULL
            WHERE c.client_screening_date IS NOT NULL 
            AND c.deleted_at IS NULL
            GROUP BY c.id, c.name, c.client_screening_date, c.overall_risk_rating, 
                     c.overall_risk_points, c.assessment_status, c.created_at
            ORDER BY c.client_screening_date DESC
            LIMIT 10
        ");
        
        return collect($assessments)->map(function ($client) {
            $client->risk_score = $client->calculated_risk_score ?? 0;
            $client->risk_rating = $this->determineRiskRating($client->risk_score);
            
            // Convert string dates to Carbon instances
            if ($client->screening_date) {
                $client->screening_date = Carbon::parse($client->screening_date);
            }
            if ($client->created_at) {
                $client->created_at = Carbon::parse($client->created_at);
            }
            
            return $client;
        });
    }

    /**
     * Determine risk rating based on score
     */
    private function determineRiskRating($score)
    {
        if ($score >= 20) return 'Very High';
        if ($score >= 17) return 'High';
        if ($score >= 15) return 'Medium';
        if ($score >= 10) return 'Low';
        return 'Very Low';
    }

    /**
     * Determine DCS risk appetite based on risk rating
     */
    private function determineDCSRiskAppetite($riskRating)
    {
        $rating = strtolower($riskRating);
        if (str_contains($rating, 'high') || str_contains($rating, 'critical')) {
            return 'Conservative';
        } elseif (str_contains($rating, 'medium')) {
            return 'Moderate';
        } else {
            return 'Aggressive';
        }
    }

    /**
     * Create comprehensive risk assessment for approved clients
     */
    private function createComprehensiveRiskAssessment(Client $client)
    {
        try {
            // Get the first risk record for this client
            $firstRisk = $client->risks()->first();
            
            if (!$firstRisk) {
                // Create a dummy risk record if none exists
                $firstRisk = Risk::create([
                    'title' => 'Comprehensive Risk Assessment - ' . $client->name,
                    'description' => 'Comprehensive risk assessment created for client approval',
                    'risk_category' => 'Client Risk',
                    'risk_rating' => $this->normalizeRiskRating($client->overall_risk_rating ?? 'Low'),
                    'impact' => $this->getImpactFromRating($client->overall_risk_rating ?? 'Low'),
                    'likelihood' => $this->getLikelihoodFromRating($client->overall_risk_rating ?? 'Low'),
                    'status' => 'Open',
                    'client_id' => $client->id,
                    'mitigation_measures' => 'Standard comprehensive risk mitigation measures',
                    'created_by' => auth()->id() ?? 1,
                ]);
            }

            // Calculate points for each category based on OFFICIAL RISK ASSESSMENT TABLE
            $totalPoints = $client->overall_risk_points ?? 0;
            
            // Use the SAME calculation logic as RiskController and JavaScript
            // This ensures consistency across all routes
            $crPoints = min(5, max(1, intval($totalPoints / 4))); // 25% of total
            $srPoints = min(5, max(1, intval($totalPoints / 4))); // 25% of total  
            $prPoints = min(5, max(1, intval($totalPoints / 4))); // 25% of total
            $drPoints = $totalPoints - ($crPoints + $srPoints + $prPoints); // Remaining points
            
            // Ensure DR points is not negative
            if ($drPoints < 1) $drPoints = 1;
            
            // Prepare comprehensive risk assessment data with calculated points
            $comprehensiveData = [
                'risk_id' => $firstRisk->id,
                'cr_risk_id' => 'CR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT),
                'cr_risk_name' => 'Client Risk Assessment',
                'cr_impact' => $this->getImpactFromPoints($crPoints),
                'cr_likelihood' => $this->getLikelihoodFromPoints($crPoints),
                'cr_risk_rating' => $this->getRiskRatingFromPoints($crPoints),
                'cr_points' => $crPoints,
                'cr_mitigation' => 'Standard client risk mitigation measures',
                'cr_owner' => 'Risk Manager',
                'cr_status' => 'Open',
                
                'sr_risk_id' => 'SR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT),
                'sr_risk_name' => 'Service Risk Assessment',
                'sr_impact' => $this->getImpactFromPoints($srPoints),
                'sr_likelihood' => $this->getLikelihoodFromPoints($srPoints),
                'sr_risk_rating' => $this->getRiskRatingFromPoints($srPoints),
                'sr_points' => $srPoints,
                'sr_mitigation' => 'Standard service risk mitigation measures',
                'sr_owner' => 'Risk Manager',
                'sr_status' => 'Open',
                
                'pr_risk_id' => 'PR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT),
                'pr_risk_name' => 'Payment Risk Assessment',
                'pr_impact' => $this->getImpactFromPoints($prPoints),
                'pr_likelihood' => $this->getLikelihoodFromPoints($prPoints),
                'pr_risk_rating' => $this->getRiskRatingFromPoints($prPoints),
                'pr_points' => $prPoints,
                'pr_mitigation' => 'Standard payment risk mitigation measures',
                'pr_owner' => 'Risk Manager',
                'pr_status' => 'Open',
                
                'dr_risk_id' => 'DR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT),
                'dr_risk_name' => 'Delivery Risk Assessment',
                'dr_impact' => $this->getImpactFromPoints($drPoints),
                'dr_likelihood' => $this->getLikelihoodFromPoints($drPoints),
                'dr_risk_rating' => $this->getRiskRatingFromPoints($drPoints),
                'dr_points' => $drPoints,
                'dr_mitigation' => 'Standard delivery risk mitigation measures',
                'dr_owner' => 'Risk Manager',
                'dr_status' => 'Open',
                
                'total_points' => $totalPoints,
                'overall_risk_rating' => $this->calculateOverallRiskRating($totalPoints),
                'client_acceptance' => $this->calculateClientAcceptance($totalPoints),
                'ongoing_monitoring' => $this->calculateMonitoringFrequency($totalPoints),
                'created_by' => auth()->id() ?? 1,
                'updated_by' => auth()->id() ?? 1,
            ];

            // Create the comprehensive risk assessment
            \App\Models\ComprehensiveRiskAssessment::create($comprehensiveData);

        } catch (\Exception $e) {
            Log::error('Failed to create comprehensive risk assessment', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);
        }
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
        if (str_contains($rating, 'high')) {
            return 'High';
        } elseif (str_contains($rating, 'medium')) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }

    /**
     * Get impact level from points based on OFFICIAL TABLE
     */
    private function getImpactFromPoints($points)
    {
        if ($points >= 5) return 'High';
        if ($points >= 3) return 'Medium';
        return 'Low';
    }

    /**
     * Get likelihood level from points based on OFFICIAL TABLE
     */
    private function getLikelihoodFromPoints($points)
    {
        if ($points >= 5) return 'High';
        if ($points >= 3) return 'Medium';
        return 'Low';
    }

    /**
     * Get risk rating from points based on OFFICIAL TABLE
     */
    private function getRiskRatingFromPoints($points)
    {
        if ($points >= 5) return 'High';
        if ($points >= 3) return 'Medium';
        return 'Low';
    }

    /**
     * Calculate overall risk rating based on OFFICIAL TABLE
     */
    private function calculateOverallRiskRating($totalPoints)
    {
        if ($totalPoints >= 20) return 'Very High-risk';
        if ($totalPoints >= 17) return 'High-risk';
        if ($totalPoints >= 15) return 'Medium-risk';
        if ($totalPoints >= 10) return 'Low-risk';
        return 'Low-risk';
    }

    /**
     * Calculate client acceptance based on OFFICIAL TABLE
     */
    private function calculateClientAcceptance($totalPoints)
    {
        if ($totalPoints >= 20) return 'Do not accept client';
        return 'Accept client';
    }

    /**
     * Calculate monitoring frequency based on OFFICIAL TABLE
     */
    private function calculateMonitoringFrequency($totalPoints)
    {
        if ($totalPoints >= 20) return 'N/A';
        if ($totalPoints >= 17) return 'Quarterly review';
        if ($totalPoints >= 15) return 'Bi-Annually';
        if ($totalPoints >= 10) return 'Annually';
        return 'Annually';
    }
}
