<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Risk;
use App\Models\Client;
use App\Models\RiskCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\ComprehensiveRiskAssessmentService;
use App\Services\RiskClassificationService;
use Carbon\Carbon;
use PDF;
use Excel;

class RiskController extends Controller
{
    protected $riskAssessmentService;

    public function __construct(ComprehensiveRiskAssessmentService $riskAssessmentService)
    {
        $this->riskAssessmentService = $riskAssessmentService;
    }

    /**
     * Display a listing of the resource - OPTIMIZED VERSION
     */
    public function index(Request $request)
    {
        // Use caching for risk index data (2 minutes cache - shorter to reflect deletions faster)
        $cacheKey = 'risks_index_' . auth()->id();
        
        // Clear cache if requested (useful after deletions)
        if ($request->has('refresh')) {
            Cache::forget($cacheKey);
        }
        
        $data = Cache::remember($cacheKey, 120, function() {
            // Single optimized query to get all statistics at once - BASED ON CLIENTS, NOT INDIVIDUAL RISKS
            $stats = DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM clients WHERE deleted_at IS NULL) as total_risks,
                    (SELECT COUNT(*) FROM clients WHERE status = 'Active' AND deleted_at IS NULL) as open_risks,
                    (SELECT COUNT(*) FROM clients WHERE overall_risk_rating LIKE '%High%' AND deleted_at IS NULL) as high_risks
            ");
            
            // Get recent clients with their risk assessments - BASED ON CLIENTS, NOT INDIVIDUAL RISKS
            $risks = DB::select("
                SELECT 
                    c.id, c.name as title, c.company as description, c.name as client_name, c.id as client_id,
                    'Comprehensive' as risk_category, c.overall_risk_rating as risk_rating, c.status, c.created_at,
                    c.name as client_name_from_relation, c.company,
                    'Comprehensive Risk Assessment' as category_name
                FROM clients c
                WHERE c.deleted_at IS NULL
                ORDER BY c.created_at DESC
                LIMIT 20
            ");
            
            return [
                'stats' => $stats[0],
                'risks' => $risks
            ];
        });
        
        // Convert stdClass objects to Eloquent models for proper route binding
        $riskModels = collect($data['risks'])->map(function($riskData) {
            // Create a new Risk model instance
            $risk = new \App\Models\Risk();
            
            // Fill the model with the raw data
            foreach ($riskData as $key => $value) {
                $risk->$key = $value;
            }
            
            // Set the key name for route model binding
            $risk->setKeyName('id');
            $risk->exists = true;
            
            // Create client object if client_id exists
            if ($risk->client_id) {
                $client = new \App\Models\Client();
                $client->id = $risk->client_id;
                $client->name = $risk->client_name_from_relation;
                $client->company = $risk->company;
                $client->setKeyName('id');
                $client->exists = true;
                $risk->client = $client;
            }
            
            return $risk;
        });
        
        return view('risks.index', [
            'risks' => $riskModels,
            'totalRisks' => $data['stats']->total_risks,
            'openRisks' => $data['stats']->open_risks,
            'highRisks' => $data['stats']->high_risks
        ]);
    }

    /**
     * Show the form for creating a new risk.
     */
    public function create()
    {
        // Clear reports page session flag when visiting create page
        session()->forget('from_reports_page');
        
        // Get all risk categories for the dropdowns
        $riskCategories = DB::table('risk_categories')
            ->orderBy('risk_category')
            ->orderBy('risk_id')
            ->get();

        // Get all users for the assigned user dropdown
        $users = \App\Models\User::orderBy('name')->get();

        return view('risks.create', compact('riskCategories', 'users'));
    }

    /**
     * Store a newly created risk in storage.
     */
    public function store(Request $request)
    {
        // Base validation
        $baseRules = [
            'client_name' => 'required|string|max:255',
            'assessment_date' => 'required|date|date_equals:' . date('Y-m-d'),
            'screening_status' => 'required|string',
            'sr_selection' => 'required|string',
            'cr_selection' => 'required|string',
            'pr_selection' => 'required|string',
            'dr_selection' => 'required|string',
            'risk_description' => 'required|string',
            'risk_category' => 'required|string',
            'impact' => 'required|string',
            'likelihood' => 'required|string',
            'status' => 'required|string',
            'mitigation_strategies' => 'required|string',
            'owner' => 'required|string',
            // client_identification_done field removed - consolidated with screening fields
            'client_screening_date' => 'nullable|date',
            'client_screening_result' => 'nullable|string',
            'dcs_risk_appetite' => 'required|string',
            'dcs_comments' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_user_id' => 'nullable|integer|exists:users,id',
            'risk_detail' => 'nullable|string',
            // KYC fields
            'client_type' => 'required|in:Individual,Juristic',
            'gender' => 'nullable|in:Male,Female',
            'nationality' => 'nullable|in:Namibian,Foreign',
            'is_minor' => 'nullable|boolean',
            'id_number' => 'nullable|string|max:100|prohibited_if:is_minor,1|prohibited_unless:nationality,Namibian',
            'passport_number' => 'nullable|string|max:100|prohibited_unless:nationality,Foreign',
            'registration_number' => 'nullable|string|max:150',
            'entity_type' => 'nullable|string|max:50',
            'trading_address' => 'nullable|string|max:255',
            'income_source' => 'nullable|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_industry' => 'required|string',
            // Prohibit opposite document based on minor selection
            'id_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120|prohibited_if:is_minor,1',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120|prohibited_if:is_minor,0|prohibited_unless:nationality,Namibian',
            'passport_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120|prohibited_unless:nationality,Foreign',
            'proof_of_residence' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kyc_form' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'source_of_earnings' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        $messages = [
            'assessment_date.date_equals' => 'Assessment date must be exactly today\'s date. Only current date assessments are allowed.',
        ];

        $validator = \Validator::make($request->all(), $baseRules, $messages);

        // Require individual selections when Individual
        $validator->sometimes('gender', 'required|in:Male,Female', function($input) {
            return $input->client_type === 'Individual';
        });
        $validator->sometimes('nationality', 'required|in:Namibian,Foreign', function($input) {
            return $input->client_type === 'Individual';
        });
        $validator->sometimes('is_minor', 'required|boolean', function($input) {
            return $input->client_type === 'Individual';
        });

        // Conditional requirements
        // Only one of ID or Birth Certificate depending on minor
        $validator->sometimes('id_document', 'required|prohibited_unless:is_minor,0', function($input) {
            return $input->client_type === 'Individual' && $input->nationality === 'Namibian' && (int)($input->is_minor ?? 0) === 0;
        });
        $validator->sometimes('id_number', 'required|string|max:100', function($input) {
            return $input->client_type === 'Individual' && $input->nationality === 'Namibian' && (int)($input->is_minor ?? 0) === 0;
        });
        $validator->sometimes('birth_certificate', 'required|prohibited_unless:is_minor,1', function($input) {
            return $input->client_type === 'Individual' && (int)($input->is_minor ?? 0) === 1;
        });
        $validator->sometimes('passport_document', 'required', function($input) {
            // Foreign adults require passport; minors use birth certificate
            return $input->client_type === 'Individual' && $input->nationality === 'Foreign' && (int)($input->is_minor ?? 0) === 0;
        });
        $validator->sometimes('passport_number', 'required|string|max:100', function($input) {
            return $input->client_type === 'Individual' && $input->nationality === 'Foreign' && (int)($input->is_minor ?? 0) === 0;
        });
        // Proof of residence required for Individuals (general) and Juristic (trading address residence)
        $validator->sometimes('proof_of_residence', 'required', function($input) {
            return $input->client_type === 'Individual' || $input->client_type === 'Juristic';
        });

        // Foreign Individuals must provide source of income
        $validator->sometimes('income_source', 'required|string|max:255', function($input) {
            return $input->client_type === 'Individual' && $input->nationality === 'Foreign';
        });
        $validator->sometimes('registration_number', 'required|string|max:150', function($input) {
            return $input->client_type === 'Juristic';
        });
        $validator->sometimes('entity_type', 'required|string|max:50', function($input) {
            return $input->client_type === 'Juristic';
        });
        $validator->sometimes('trading_address', 'required|string|max:255', function($input) {
            return $input->client_type === 'Juristic';
        });
        $validator->sometimes('income_source', 'required|string|max:255', function($input) {
            return $input->client_type === 'Juristic';
        });

        $validator->validate();

        // Get comprehensive risk data from form
        $srRiskId = $request->sr_risk_id;
        $crRiskId = $request->cr_risk_id;
        $prRiskId = $request->pr_risk_id;
        $drRiskId = $request->dr_risk_id;
        
        // Get individual risk details
        $srPoints = intval($request->sr_points) ?: 0;
        $crPoints = intval($request->cr_points) ?: 0;
        $prPoints = intval($request->pr_points) ?: 0;
        $drPoints = intval($request->dr_points) ?: 0;
        
        // Calculate total risk points from form data
        $totalPoints = $srPoints + $crPoints + $prPoints + $drPoints;
        
        // Get overall assessment from form
        $overallRiskRating = $request->overall_risk_rating ?: 'Low-risk';
        $clientAcceptance = $request->client_acceptance ?: 'Accept client';
        $ongoingMonitoring = $request->ongoing_monitoring ?: 'Annually';
        
        // Generate unique risk ID (use the first selection as primary)
        $primaryRiskId = $request->sr_selection ?: $request->cr_selection ?: $request->pr_selection ?: $request->dr_selection;
        $riskId = $primaryRiskId;

        // Determine approval status based on user role
        // Staff users can approve their own risk assessments
        // Only external/non-staff users need approval
        $approvalStatus = 'approved'; // Default for all authenticated users

        // First, create or update the client record
        $client = \App\Models\Client::updateOrCreate(
            ['name' => $request->client_name],
            [
                'name' => $request->client_name,
                'email' => $request->client_email ?: $request->client_name . '@example.com',
                'phone' => 'N/A',
                'address' => 'N/A',
                'status' => 'Active',
                'assessment_status' => $approvalStatus, // Set the correct assessment status
                'created_by' => auth()->id(), // Track who created the assessment
                // client_identification_done field removed - consolidated with screening fields
                'client_screening_date' => $request->client_screening_date ?? $request->assessment_date,
                'client_screening_result' => $request->client_screening_result ?? $request->screening_status,
                'risk_category' => $request->risk_category,
                'risk_id' => $riskId,
                'overall_risk_points' => $totalPoints,
                'overall_risk_rating' => $overallRiskRating,
                'client_acceptance' => $clientAcceptance,
                'ongoing_monitoring' => $ongoingMonitoring,
                'dcs_risk_appetite' => $request->dcs_risk_appetite ?? 'Moderate',
                'dcs_comments' => $request->dcs_comments ?? 'Risk assessment completed via system',
                'industry' => $request->client_industry ?: 'Not Specified',
                // Add KYC fields
                'client_type' => $request->client_type,
                'gender' => $request->client_type === 'Individual' ? $request->gender : null,
                'nationality' => $request->client_type === 'Individual' ? $request->nationality : null,
                'is_minor' => $request->client_type === 'Individual' ? $request->is_minor : null,
                'id_number' => $request->client_type === 'Individual' && $request->nationality === 'Namibian' && !$request->is_minor ? $request->id_number : null,
                'passport_number' => $request->client_type === 'Individual' && $request->nationality === 'Foreign' && !$request->is_minor ? $request->passport_number : null,
                'registration_number' => $request->client_type === 'Juristic' ? $request->registration_number : null,
                'entity_type' => $request->client_type === 'Juristic' ? $request->entity_type : null,
                'trading_address' => $request->client_type === 'Juristic' ? $request->trading_address : null,
                'income_source' => $request->income_source,
            ]
        );

        // Then create the risk record with comprehensive data
        // Handle document uploads
        $idPath = $request->file('id_document')?->store('kyc/id_documents', 'public');
        $birthPath = $request->file('birth_certificate')?->store('kyc/birth_certificates', 'public');
        $passportPath = $request->file('passport_document')?->store('kyc/passports', 'public');
        $porPath = $request->file('proof_of_residence')?->store('kyc/proof_of_residence', 'public');
        $kycFormPath = $request->file('kyc_form')?->store('kyc/forms', 'public');
        $soePath = $request->file('source_of_earnings')?->store('kyc/source_of_earnings', 'public');

        $risk = Risk::create([
            'title' => $request->risk_description, // Use risk_description as title
            'description' => $request->risk_description, // Use risk_description as description
            'client_name' => $request->client_name,
            // client_identification_done field removed - consolidated with screening fields
            'client_screening_date' => $request->client_screening_date ?? $request->assessment_date,
            'client_screening_result' => $request->client_screening_result ?? $request->screening_status,
            'risk_description' => $request->risk_description,
            'risk_detail' => $request->risk_detail ?? 'Multiple risk categories assessed',
            'risk_category' => $request->risk_category,
            'risk_id' => $riskId,
            'impact' => $request->impact,
            'likelihood' => $request->likelihood,
            'risk_rating' => 'High', // Default based on highest risk
            'mitigation_strategies' => $request->mitigation_strategies,
            'owner' => $request->owner,
            'status' => $request->status,
            'overall_risk_points' => $totalPoints,
            'overall_risk_rating' => $overallRiskRating,
            'client_acceptance' => $clientAcceptance,
            'ongoing_monitoring' => $ongoingMonitoring,
            'dcs_risk_appetite' => $request->dcs_risk_appetite ?? 'Moderate',
            'dcs_comments' => $request->dcs_comments ?? 'Risk assessment completed via system',
            'due_date' => $request->due_date ?? $this->calculateDueDate($overallRiskRating),
            'assigned_user_id' => $request->assigned_user_id,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'approval_status' => $approvalStatus,
            // KYC fields
            'client_type' => $request->client_type,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'is_minor' => $request->boolean('is_minor'),
            'id_number' => $request->id_number,
            'passport_number' => $request->passport_number,
            'registration_number' => $request->registration_number,
            'entity_type' => $request->entity_type,
            'trading_address' => $request->trading_address,
            'income_source' => $request->income_source,
            'id_document_path' => $idPath,
            'birth_certificate_path' => $birthPath,
            'passport_document_path' => $passportPath,
            'proof_of_residence_path' => $porPath,
            'kyc_form_path' => $kycFormPath,
        ]);
        
        // Store comprehensive risk details in the dedicated table
        // This ensures regulatory compliance and complete audit trail
        $comprehensiveAssessment = \App\Models\ComprehensiveRiskAssessment::create([
            'risk_id' => $risk->id,
            'sr_risk_id' => $srRiskId,
            'sr_risk_name' => $request->sr_risk_name,
            'sr_impact' => $request->sr_impact,
            'sr_likelihood' => $request->sr_likelihood,
            'sr_risk_rating' => $request->sr_risk_rating,
            'sr_points' => $srPoints,
            'sr_mitigation' => $request->sr_mitigation,
            'sr_owner' => $request->sr_owner,
            'sr_status' => $request->sr_status,
            
            'cr_risk_id' => $crRiskId,
            'cr_risk_name' => $request->cr_risk_name,
            'cr_impact' => $request->cr_impact,
            'cr_likelihood' => $request->cr_likelihood,
            'cr_risk_rating' => $request->cr_risk_rating,
            'cr_points' => $crPoints,
            'cr_mitigation' => $request->cr_mitigation,
            'cr_owner' => $request->cr_owner,
            'cr_status' => $request->cr_status,
            
            'pr_risk_id' => $prRiskId,
            'pr_risk_name' => $request->pr_risk_name,
            'pr_impact' => $request->pr_impact,
            'pr_likelihood' => $request->pr_likelihood,
            'pr_risk_rating' => $request->pr_risk_rating,
            'pr_points' => $prPoints,
            'pr_mitigation' => $request->pr_mitigation,
            'pr_owner' => $request->pr_owner,
            'pr_status' => $request->pr_status,
            
            'dr_risk_id' => $drRiskId,
            'dr_risk_name' => $request->dr_risk_name,
            'dr_impact' => $request->dr_impact,
            'dr_likelihood' => $request->dr_likelihood,
            'dr_risk_rating' => $request->dr_risk_rating,
            'dr_points' => $drPoints,
            'dr_mitigation' => $request->dr_mitigation,
            'dr_owner' => $request->dr_owner,
            'dr_status' => $request->dr_status,
            
            'total_points' => $totalPoints,
            'overall_risk_rating' => $overallRiskRating,
            'client_acceptance' => $clientAcceptance,
            'ongoing_monitoring' => $ongoingMonitoring,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        // Persist uploaded documents in client_documents for robust retrieval on approvals
        $documentsToSave = [
            ['type' => 'id_document', 'path' => $idPath],
            ['type' => 'birth_certificate', 'path' => $birthPath],
            ['type' => 'passport_document', 'path' => $passportPath],
            ['type' => 'proof_of_residence', 'path' => $porPath],
            ['type' => 'kyc_form', 'path' => $kycFormPath],
            ['type' => 'source_of_earnings', 'path' => $soePath],
        ];

        foreach ($documentsToSave as $doc) {
            if (!empty($doc['path'])) {
                \App\Models\ClientDocument::create([
                    'client_id' => $client->id,
                    'risk_id' => $risk->id,
                    'document_type' => $doc['type'],
                    'file_path' => $doc['path'],
                    'original_name' => null,
                    'mime_type' => null,
                    'file_size' => null,
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }
        
        // Log comprehensive risk data for regulatory compliance
        Log::info('Comprehensive Risk Assessment Completed', [
            'risk_id' => $risk->id,
            'comprehensive_assessment_id' => $comprehensiveAssessment->id,
            'client_name' => $request->client_name,
            'total_points' => $totalPoints,
            'overall_risk_rating' => $overallRiskRating,
            'client_acceptance' => $clientAcceptance,
            'ongoing_monitoring' => $ongoingMonitoring
        ]);

        // Prepare success message based on approval status
        $successMessage = 'Risk assessment completed! Client "' . $request->client_name . '" - Decision: ' . $clientAcceptance . ' (Total Points: ' . $totalPoints . ')';
        
        if ($approvalStatus === 'pending') {
            $successMessage .= ' - Awaiting manager approval.';
        }

        // Clear cache to update risk counts immediately
        Cache::flush();
        
        // Also clear specific dashboard and client stats caches
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            Cache::forget('risks_index_' . $user->id);
            Cache::forget('dashboard_stats_' . $user->id);
            Cache::forget('dashboard_stats_v2_' . $user->id);
            Cache::forget('client_stats_' . $user->id);
            Cache::forget('client_stats_v2_' . $user->id);
        }

        return redirect()->route('risks.index')
            ->with('success', $successMessage);
    }

    /**
     * Get the next sequence number for a risk category
     */
    private function getNextRiskSequence($category)
    {
        // This method is no longer needed since we're using the risk IDs directly from the form
        // The risk IDs are already unique (SR-01, CR-02, etc.)
        return 1;
    }

    /**
     * Calculate risk rating based on impact and likelihood
     */
    private function calculateRiskRating($impact, $likelihood)
    {
        if ($impact === 'High' && $likelihood === 'High') {
            return 'High';
        } elseif ($impact === 'High' || $likelihood === 'High') {
            return 'Medium';
        } elseif ($impact === 'Medium' && $likelihood === 'Medium') {
            return 'Medium';
        }
        return 'Low';
    }

    /**
     * Calculate risk points based on impact and likelihood
     */
    private function calculateRiskPoints($impact, $likelihood)
    {
        $impactScores = ['Low' => 1, 'Medium' => 2, 'High' => 3];
        $likelihoodScores = ['Low' => 1, 'Medium' => 2, 'High' => 3];
        
        $impactScore = $impactScores[$impact] ?? 1;
        $likelihoodScore = $likelihoodScores[$likelihood] ?? 1;
        
        return $impactScore * $likelihoodScore;
    }

    /**
     * Display the specified resource.
     */
    public function show(Risk $risk)
    {
        $risk->load(['client', 'assignedUser', 'creator', 'updater']);
        
        return view('risks.show', compact('risk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Risk $risk)
    {
        $categories = RiskCategory::all();
        $clients = \App\Models\Client::all();
        $users = \App\Models\User::all();

        return view('risks.edit', compact('risk', 'categories', 'clients', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Risk $risk)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'risk_category' => 'required|string',
            'risk_rating' => 'required|in:Low,Medium,High',
            'impact' => 'required|in:Low,Medium,High',
            'likelihood' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Open,In Progress,Closed,On Hold',
            'mitigation_measures' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        try {
            $risk->update($validated);
            
            // Clear cache to update risk counts immediately
            Cache::flush();
            
            Log::info('Risk updated', ['risk_id' => $risk->id, 'user_id' => auth()->id()]);
            
            return redirect()->route('risks.index')
                ->with('success', 'Risk updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update risk', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update risk. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Risk $risk)
    {
        try {
            $risk->delete();
            
            // Clear cache for all users to update counts immediately
            Cache::flush();
            
            // Also clear specific risk index caches
            $users = \App\Models\User::all();
            foreach ($users as $user) {
                Cache::forget('risks_index_' . $user->id);
                Cache::forget('dashboard_stats_' . $user->id);
                Cache::forget('dashboard_stats_v2_' . $user->id);
                Cache::forget('client_stats_' . $user->id);
                Cache::forget('client_stats_v2_' . $user->id);
            }
            
            Log::info('Risk deleted', ['risk_id' => $risk->id, 'user_id' => auth()->id()]);
            
            // Check if user came from reports page using multiple methods
            $referer = request()->header('referer');
            $fromReports = false;
            
            if ($referer) {
                $fromReports = str_contains($referer, '/reports') || 
                              str_contains($referer, 'reports') ||
                              str_contains($referer, 'risks.reports');
            }
            
            // Also check if there's a specific parameter or session flag
            if (request()->has('from_reports') || session('from_reports_page')) {
                $fromReports = true;
            }
            
            if ($fromReports) {
                return redirect()->route('risks.reports')
                    ->with('success', 'Risk assessment deleted successfully');
            }
            
            return redirect()->route('risks.index')
                ->with('success', 'Risk deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete risk', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete risk. Please try again.');
        }
    }



    /**
     * Show reports - OPTIMIZED VERSION
     */
    public function reports(Request $request)
    {
        // Set session flag to track that user is on reports page
        session(['from_reports_page' => true]);
        
        // Use caching for reports data (5 minutes cache)
        $cacheKey = 'reports_data_' . md5(serialize($request->all()));
        
        $data = Cache::remember($cacheKey, 300, function() use ($request) {
            // Compute stats from current assessments (clients) for consistency
            $stats = DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM clients WHERE deleted_at IS NULL) as total_risks,
                    (SELECT COUNT(*) FROM risks WHERE status = 'Open' AND deleted_at IS NULL) as open_risks,
                    (SELECT COUNT(*) FROM risks WHERE status = 'Closed' AND deleted_at IS NULL) as closed_risks,
                    (SELECT COUNT(*) FROM risks WHERE due_date < ? AND status != 'Closed' AND deleted_at IS NULL) as overdue_risks,
                    (SELECT COUNT(*) FROM clients WHERE assessment_status = 'approved' AND deleted_at IS NULL) as approved_clients,
                    (SELECT COUNT(*) FROM clients WHERE assessment_status = 'pending' AND deleted_at IS NULL) as pending_clients,
                    (SELECT COUNT(*) FROM clients WHERE assessment_status = 'rejected' AND deleted_at IS NULL) as rejected_clients,
                    (SELECT COUNT(*) FROM users) as total_users
            ", [now()]);
            
            $stat = $stats[0];
            
            // Risk level counts based on clients' current overall rating
            $riskCounts = [
                'high' => (int) DB::table('clients')->where('overall_risk_rating', 'LIKE', '%High%')->where('assessment_status', 'approved')->whereNull('deleted_at')->count(),
                'critical' => (int) DB::table('clients')->where('overall_risk_rating', 'Critical')->where('assessment_status', 'approved')->whereNull('deleted_at')->count(),
                'medium' => (int) DB::table('clients')->where('overall_risk_rating', 'LIKE', '%Medium%')->where('assessment_status', 'approved')->whereNull('deleted_at')->count(),
                'low' => (int) DB::table('clients')->where('overall_risk_rating', 'LIKE', '%Low%')->where('assessment_status', 'approved')->whereNull('deleted_at')->count(),
            ];
            
            // Risks by category can still read from risks for distribution visuals
            $risksByCategory = DB::select("
                SELECT risk_category, COUNT(*) as count 
                FROM risks 
                WHERE deleted_at IS NULL 
                GROUP BY risk_category
            ");
            
            return [
                'stats' => $stat,
                'riskCounts' => $riskCounts,
                'risksByCategory' => $risksByCategory
            ];
        });
        
        // Extract cached data
        $stats = $data['stats'];
        $riskCounts = $data['riskCounts'];
        $risksByCategory = collect($data['risksByCategory']);
        
        // Build optimized risks query
        $risksQuery = $this->buildOptimizedRisksQuery($request);
        $risks = $risksQuery->get();
        
        // Convert risks to have proper client objects for route generation
        $risks = $risks->map(function($risk) {
            if ($risk->client_id) {
                // Create a client object from the selected fields
                $client = new \App\Models\Client();
                $client->id = $risk->client_id;
                $client->name = $risk->client_name_from_relation;
                $client->company = $risk->company;
                $client->assessment_status = $risk->assessment_status;
                $client->rejection_reason = $risk->rejection_reason;
                $client->approved_at = $risk->approved_at;
                $client->approved_by = $risk->approved_by;
                $client->setKeyName('id');
                $client->exists = true;
                $risk->client = $client;
            }
            return $risk;
        });
        
        // Only load rejected clients data if needed (lazy loading)
        $rejectedClients = collect();
        $rejectionCategories = collect();
        
        if ($request->get('filter') === 'rejected' || $stats->rejected_clients > 0) {
            // Clear cache if refresh is requested
            if ($request->get('refresh_rejected') === 'true') {
                Cache::forget('rejected_clients_data');
            }
            $rejectedData = $this->getRejectedClientsData();
            $rejectedClients = $rejectedData['clients'];
            $rejectionCategories = $rejectedData['categories'];
        }
        
        return view('risks.reports', [
            'totalRisks' => $stats->total_risks,
            'highRisks' => $riskCounts['high'],
            'criticalRisks' => $riskCounts['critical'],
            'mediumRisks' => $riskCounts['medium'],
            'lowRisks' => $riskCounts['low'],
            'openRisks' => $stats->open_risks,
            'closedRisks' => $stats->closed_risks,
            'overdueRisks' => $stats->overdue_risks,
            'risks' => $risks,
            'risksByCategory' => $risksByCategory,
            'clientStats' => [
                'totalClients' => $stats->approved_clients,
                'pendingClients' => $stats->pending_clients,
                'rejectedClients' => $stats->rejected_clients,
            ],
            'rejectedClients' => $rejectedClients,
            'rejectionCategories' => $rejectionCategories
        ]);
    }
    
    /**
     * Build optimized risks query - now based on clients
     */
    private function buildOptimizedRisksQuery(Request $request)
    {
        $query = Client::select([
                'clients.id', 'clients.name as title', 'clients.company as description', 
                'clients.name as client_name', 'clients.id as client_id',
                'clients.overall_risk_rating as risk_rating', 'clients.status', 'clients.created_at',
                'clients.assessment_status', 'clients.rejection_reason',
                'clients.approved_at', 'clients.approved_by',
                'clients.name as client_name_from_relation', 'clients.company',
                'clients.risk_category', 'clients.overall_risk_points',
                'users.name as assigned_user_name',
                'approvers.name as approver_name',
                DB::raw('CASE 
                    WHEN clients.overall_risk_rating LIKE "%Very High%" OR clients.overall_risk_rating LIKE "%Critical%" THEN NULL
                    WHEN clients.overall_risk_rating LIKE "%High%" THEN DATE_ADD(clients.approved_at, INTERVAL 3 MONTH)
                    WHEN clients.overall_risk_rating LIKE "%Medium%" THEN DATE_ADD(clients.approved_at, INTERVAL 6 MONTH)
                    WHEN clients.overall_risk_rating LIKE "%Low%" THEN DATE_ADD(clients.approved_at, INTERVAL 12 MONTH)
                    ELSE NULL
                END as due_date')
            ])
            ->leftJoin('users', 'clients.created_by', '=', 'users.id')
            ->leftJoin('users as approvers', 'clients.approved_by', '=', 'approvers.id')
            ->whereNull('clients.deleted_at');
        
        // Apply filtering based on request
        $this->applyRisksFilters($query, $request);
        
        return $query->orderBy('clients.created_at', 'desc');
    }
    
    /**
     * Apply filters to risks query
     */
    private function applyRisksFilters($query, Request $request)
    {
        $filter = $request->get('filter');
        
        // Apply quick filters first
        switch ($filter) {
            case 'rejected':
                $query->where('clients.assessment_status', 'rejected');
                break;
            case 'approved':
                $query->where('clients.assessment_status', 'approved');
                break;
            case 'pending':
                $query->where('clients.assessment_status', 'pending');
                break;
            case 'high_risk':
                $query->where('clients.assessment_status', 'approved')
                      ->where(function($q) {
                          $q->where('clients.overall_risk_rating', 'High-risk')
                            ->orWhere('clients.overall_risk_rating', 'Very High-risk');
                      });
                break;
            case 'overdue':
                // For overdue, we need to check if client has overdue risks
                $query->whereHas('risks', function($riskQuery) {
                    $riskQuery->where('due_date', '<', now())
                              ->where('status', '!=', 'Closed');
                });
                break;
            case 'open':
                $query->where('clients.status', 'Active');
                break;
            case 'closed':
                $query->where('clients.status', 'Inactive');
                break;
            default:
                // Default: show approved clients only
                $query->where('clients.assessment_status', 'approved');
                break;
        }
        
        // Apply specific filters
        if ($request->has('risk_level')) {
            $query->where('clients.overall_risk_rating', $request->get('risk_level'));
        }
        
        if ($request->has('status')) {
            $query->where('clients.status', $request->get('status'));
        }
        
        if ($request->has('approval_status')) {
            $query->where('clients.assessment_status', $request->get('approval_status'));
        }
    }
    
    /**
     * Get rejected clients data (lazy loaded)
     */
    private function getRejectedClientsData()
    {
        return Cache::remember('rejected_clients_data', 300, function() {
            $rejectedClients = DB::select("
                SELECT 
                    c.id, c.name, c.email, c.company, c.rejection_reason, c.updated_at,
                    GROUP_CONCAT(r.id) as risk_ids,
                    GROUP_CONCAT(r.title) as risk_titles,
                    GROUP_CONCAT(r.risk_category) as risk_categories,
                    GROUP_CONCAT(r.impact) as risk_impacts,
                    GROUP_CONCAT(r.likelihood) as risk_likelihoods,
                    GROUP_CONCAT(r.risk_rating) as risk_ratings,
                    GROUP_CONCAT(r.overall_risk_points) as risk_scores,
                    COALESCE(SUM(r.overall_risk_points), 0) as total_risk_score,
                    COALESCE(COUNT(r.id), 0) as risk_count
                FROM clients c
                LEFT JOIN risks r ON c.id = r.client_id AND r.deleted_at IS NULL
                WHERE c.assessment_status = 'rejected' AND c.deleted_at IS NULL
                GROUP BY c.id, c.name, c.email, c.company, c.rejection_reason, c.updated_at
            ");

            $processedClients = collect($rejectedClients)->map(function($clientData) {
                $riskIds = $clientData->risk_ids ? explode(',', $clientData->risk_ids) : [];
                $riskTitles = $clientData->risk_titles ? explode(',', $clientData->risk_titles) : [];
                $riskCategories = $clientData->risk_categories ? explode(',', $clientData->risk_categories) : [];
                $riskImpacts = $clientData->risk_impacts ? explode(',', $clientData->risk_impacts) : [];
                $riskLikelihoods = $clientData->risk_likelihoods ? explode(',', $clientData->risk_likelihoods) : [];
                $riskRatings = $clientData->risk_ratings ? explode(',', $clientData->risk_ratings) : [];
                $riskScores = $clientData->risk_scores ? explode(',', $clientData->risk_scores) : [];

                $risks = [];
                $count = max(count($riskIds), count($riskTitles), count($riskCategories), count($riskImpacts), count($riskLikelihoods), count($riskRatings));
                for ($i = 0; $i < $count; $i++) {
                    if (!empty($riskIds[$i])) {
                        $risks[] = [
                            'id' => $riskIds[$i],
                            'title' => $riskTitles[$i] ?? 'N/A',
                            'risk_category' => $riskCategories[$i] ?? 'Uncategorized',
                            'impact' => $riskImpacts[$i] ?? 'N/A',
                            'likelihood' => $riskLikelihoods[$i] ?? 'N/A',
                            'risk_rating' => $riskRatings[$i] ?? 'N/A',
                            'overall_risk_points' => isset($riskScores[$i]) ? (int)$riskScores[$i] : 0,
                        ];
                    }
                }

                // Determine highest risk category from available risks
                $highestCategory = null;
                if (!empty($risks)) {
                    // Priority by score, then by rating severity
                    usort($risks, function($a, $b) {
                        $prio = ['Critical' => 4, 'High' => 3, 'Medium' => 2, 'Low' => 1];
                        $scoreCmp = ($b['overall_risk_points'] <=> $a['overall_risk_points']);
                        if ($scoreCmp !== 0) return $scoreCmp;
                        $ra = $prio[$a['risk_rating']] ?? 0;
                        $rb = $prio[$b['risk_rating']] ?? 0;
                        return $rb <=> $ra;
                    });
                    $highestCategory = $risks[0]['risk_category'] ?? null;
                }

                return (object) [
                    'id' => $clientData->id,
                    'name' => $clientData->name,
                    'email' => $clientData->email,
                    'company' => $clientData->company,
                    'rejection_reason' => $clientData->rejection_reason,
                    'updated_at' => Carbon::parse($clientData->updated_at),
                    'risks' => $risks,
                    'total_risk_score' => (int) $clientData->total_risk_score,
                    'risk_count' => (int) (count($risks)),
                    'highest_risk_category' => $highestCategory,
                ];
            });

            $categories = collect($processedClients)->flatMap(function($client) {
                return collect($client->risks)->pluck('risk_category');
            })->countBy();

            return [
                'clients' => $processedClients,
                'categories' => $categories,
            ];
        });
    }

    /**
     * Show settings
     */
    public function settings()
    {
        // Get current settings from database
        $settings = \App\Models\SystemSetting::getMultiple([
            'risk_assessment_frequency',
            'auto_risk_scoring',
            'risk_threshold_high',
            'risk_threshold_critical',
            'email_notifications',
            'high_risk_alerts',
            'overdue_notifications',
            'notification_frequency',
        ]);

        // Get risk threshold settings
        $riskThresholdSettings = [
            'auto_rejection_enabled' => \App\Models\RiskThresholdSetting::isAutoRejectionEnabled(),
            'auto_rejection_threshold' => \App\Models\RiskThresholdSetting::getAutoRejectionThreshold(),
        ];
        
        // Initialize default settings only if no settings exist
        if (empty($settings)) {
            \App\Models\SystemSetting::initializeDefaults();
            $settings = \App\Models\SystemSetting::getMultiple([
                'risk_assessment_frequency',
                'auto_risk_scoring',
                'risk_threshold_high',
                'risk_threshold_critical',
                'email_notifications',
                'high_risk_alerts',
                'overdue_notifications',
                'notification_frequency',
            ]);
        }
        
        
        // Get risk categories for management
        $categories = \App\Models\RiskCategory::withCount('risks')->get();
        
        // Get system information
        $systemInfo = [
            'version' => '1.0.0',
            'last_updated' => now()->format('M d, Y'),
            'total_risks' => \App\Models\Risk::whereNull('deleted_at')->count(),
            'total_clients' => \App\Models\Client::where('assessment_status', 'approved')->count(),
            'cache_enabled' => true,
        ];
        
        return view('risks.settings', compact('settings', 'riskThresholdSettings', 'categories', 'systemInfo'));
    }

    /**
     * Update general settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'risk_assessment_frequency' => 'required|in:weekly,monthly,quarterly,annually',
            'auto_risk_scoring' => 'boolean',
            'risk_threshold_high' => 'required|integer|min:1|max:25',
            'risk_threshold_critical' => 'required|integer|min:1|max:25',
            'auto_rejection_enabled' => 'boolean',
            'auto_rejection_threshold' => 'required|integer|min:1|max:30',
        ]);

        try {
            // Save settings to database
            \App\Models\SystemSetting::setMultiple([
                'risk_assessment_frequency' => [
                    'value' => $validated['risk_assessment_frequency'],
                    'type' => 'string',
                    'description' => 'How often risk assessments should be conducted'
                ],
                'auto_risk_scoring' => [
                    'value' => $validated['auto_risk_scoring'] ?? false,
                    'type' => 'boolean',
                    'description' => 'Enable automatic risk score calculation'
                ],
                'risk_threshold_high' => [
                    'value' => (int) $validated['risk_threshold_high'],
                    'type' => 'integer',
                    'description' => 'Risk score threshold for high risk classification'
                ],
                'risk_threshold_critical' => [
                    'value' => (int) $validated['risk_threshold_critical'],
                    'type' => 'integer',
                    'description' => 'Risk score threshold for critical risk classification'
                ],
            ]);

            // Update risk threshold settings
            \App\Models\RiskThresholdSetting::setAutoRejectionEnabled($validated['auto_rejection_enabled'] ?? false);
            \App\Models\RiskThresholdSetting::setAutoRejectionThreshold($validated['auto_rejection_threshold']);
            
            Log::info('Risk settings updated', ['user_id' => auth()->id(), 'settings' => $validated]);
            
            return redirect()->route('risks.settings')
                ->with('success', 'General settings updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update risk settings', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update settings. Please try again.');
        }
    }

    /**
     * Update notification settings
     */
    public function updateNotificationSettings(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'high_risk_alerts' => 'boolean',
            'overdue_notifications' => 'boolean',
            'notification_frequency' => 'required|in:immediate,daily,weekly',
        ]);

        try {
            // Save notification settings to database
            \App\Models\SystemSetting::setMultiple([
                'email_notifications' => [
                    'value' => $validated['email_notifications'] ?? false,
                    'type' => 'boolean',
                    'description' => 'Enable email notifications for risk updates'
                ],
                'high_risk_alerts' => [
                    'value' => $validated['high_risk_alerts'] ?? false,
                    'type' => 'boolean',
                    'description' => 'Send immediate alerts for high and critical risks'
                ],
                'overdue_notifications' => [
                    'value' => $validated['overdue_notifications'] ?? false,
                    'type' => 'boolean',
                    'description' => 'Notify when risks become overdue'
                ],
                'notification_frequency' => [
                    'value' => $validated['notification_frequency'],
                    'type' => 'string',
                    'description' => 'Frequency of notifications'
                ],
            ]);
            
            Log::info('Notification settings updated', ['user_id' => auth()->id(), 'settings' => $validated]);
            
            return redirect()->route('risks.settings')
                ->with('success', 'Notification settings updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update notification settings', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update notification settings. Please try again.');
        }
    }

    /**
     * Handle bulk actions for risks
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'selected_risks' => 'required|array|min:1',
            'selected_risks.*' => 'exists:risks,id',
            'action' => 'required|in:open,in_progress,closed,on_hold,delete,assign'
        ]);

        try {
            $risks = Risk::whereIn('id', $request->selected_risks);
            
            switch ($request->action) {
                case 'open':
                    $risks->update(['status' => 'Open']);
                    $message = 'Selected risks moved to Open status';
                    break;
                    
                case 'in_progress':
                    $risks->update(['status' => 'In Progress']);
                    $message = 'Selected risks moved to In Progress status';
                    break;
                    
                case 'closed':
                    $risks->update(['status' => 'Closed']);
                    $message = 'Selected risks moved to Closed status';
                    break;
                    
                case 'on_hold':
                    $risks->update(['status' => 'On Hold']);
                    $message = 'Selected risks moved to On Hold status';
                    break;
                    
                case 'assign':
                    if (!$request->filled('assigned_user_id')) {
                        return back()->with('error', 'Please select a user to assign risks to.');
                    }
                    $risks->update(['assigned_user_id' => $request->assigned_user_id]);
                    $message = 'Selected risks assigned successfully';
                    break;
                    
                case 'delete':
                    $risks->delete();
                    $message = 'Selected risks deleted successfully';
                    break;
            }
            
            Log::info('Bulk action performed on risks', [
                'action' => $request->action,
                'risks_count' => count($request->selected_risks),
                'user_id' => auth()->id()
            ]);
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Bulk action failed for risks', ['error' => $e->getMessage()]);
            return back()->with('error', 'Bulk action failed. Please try again.');
        }
    }

    /**
     * Export risk data
     */
    public function export(Risk $risk)
    {
        try {
            $data = [
                'risk' => $risk->toArray(),
                'client' => $risk->client ? $risk->client->toArray() : null,
                'assigned_user' => $risk->assignedUser ? $risk->assignedUser->toArray() : null,
                'category' => $risk->category ? $risk->category->toArray() : null,
                'exported_at' => now()->toISOString(),
                'exported_by' => auth()->user()->name,
            ];

            $filename = 'risk_' . $risk->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';
            
            return response()->json($data)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');
                
        } catch (\Exception $e) {
            Log::error('Failed to export risk', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to export risk data. Please try again.');
        }
    }

    /**
     * Bulk export selected risks
     */
    public function bulkExport(Request $request)
    {
        $request->validate([
            'risk_ids' => 'required|string',
        ]);

        try {
            $riskIds = explode(',', $request->risk_ids);
            $risks = Risk::with(['client', 'assignedUser', 'category'])
                ->whereIn('id', $riskIds)
                ->get();

            if ($risks->isEmpty()) {
                return back()->with('error', 'No risks found to export.');
            }

            $data = [
                'risks' => $risks->toArray(),
                'export_info' => [
                    'total_risks' => $risks->count(),
                    'exported_at' => now()->toISOString(),
                    'exported_by' => auth()->user()->name,
                ]
            ];

            $filename = 'risks_bulk_export_' . now()->format('Y-m-d_H-i-s') . '.json';
            
            return response()->json($data)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');
                
        } catch (\Exception $e) {
            Log::error('Bulk export failed for risks', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to export selected risks. Please try again.');
        }
    }

    /**
     * Export all risks as PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $query = Risk::with(['client', 'assignedUser', 'category']);
            
            // Apply filters if any
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('risk_rating')) {
                $query->where('risk_rating', $request->risk_rating);
            }
            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }
            
            $risks = $query->get();
            
            $data = [
                'risks' => $risks,
                'exported_at' => now()->format('Y-m-d H:i:s'),
                'exported_by' => auth()->user()->name,
                'total_risks' => $risks->count(),
                'high_risk_count' => $risks->where('risk_rating', 'High')->count(),
                'medium_risk_count' => $risks->where('risk_rating', 'Medium')->count(),
                'low_risk_count' => $risks->where('risk_rating', 'Low')->count(),
            ];
            
            $pdf = \PDF::loadView('risks.exports.pdf', $data);
            $pdf->setPaper('A4', 'landscape');
            
            $filename = 'risk_assessments_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('PDF export failed for risks', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to export risk assessments as PDF. Please try again.');
        }
    }

    /**
     * Export all risks as Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $query = Risk::with(['client', 'assignedUser', 'category']);
            
            // Apply filters if any
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('risk_rating')) {
                $query->where('risk_rating', $request->risk_rating);
            }
            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }
            
            $risks = $query->get();
            
            $filename = 'risk_assessments_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            
            return \Excel::download(new \App\Exports\RisksExport($risks), $filename);
            
        } catch (\Exception $e) {
            Log::error('Excel export failed for risks', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to export risk assessments as Excel. Please try again.');
        }
    }

    /**
     * Export all risks as CSV
     */
    public function exportCsv(Request $request)
    {
        try {
            $query = Risk::with(['client', 'assignedUser', 'category']);
            
            // Apply filters if any
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('risk_rating')) {
                $query->where('risk_rating', $request->risk_rating);
            }
            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }
            
            $risks = $query->get();
            
            $filename = 'client_risk_assessments_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($risks) {
                $file = fopen('php://output', 'w');
                
                // CSV headers - focused on compliance reporting
                fputcsv($file, [
                    'Client Name', 'Company', 'Risk Level', 'Assessment Status', 
                    'Approval Status', 'Assessment Date', 'Next Review Date', 
                    'Risk Category', 'Overall Risk Points', 'Client Acceptance', 
                    'Ongoing Monitoring', 'DCS Comments'
                ]);
                
                // CSV data
                foreach ($risks as $risk) {
                    fputcsv($file, [
                        $risk->client ? $risk->client->name : ($risk->title ?? 'Assessment #' . $risk->id),
                        $risk->client ? ($risk->client->company ?? 'Individual') : 'N/A',
                        $risk->risk_level ?? $risk->overall_risk_rating ?? 'Not Assessed',
                        $risk->status ?? 'In Progress',
                        $risk->approval_status ?? 'Pending',
                        $risk->created_at->format('Y-m-d'),
                        $risk->due_date ? $risk->due_date->format('Y-m-d') : 'N/A',
                        $risk->risk_category ?? 'N/A',
                        $risk->overall_risk_points ?? $risk->total_points ?? 'N/A',
                        $risk->client_acceptance ?? 'N/A',
                        $risk->ongoing_monitoring ?? 'N/A',
                        $risk->dcs_comments ?? 'N/A'
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('CSV export failed for risks', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to export client assessments as CSV. Please try again.');
        }
    }
    
    /**
     * Calculate due date based on risk rating according to FIC compliance matrix
     */
    private function calculateDueDate($riskRating)
    {
        $baseDate = now();
        
        switch ($riskRating) {
            case 'Very High-risk':
                return null; // N/A - Do not accept client
            case 'High-risk':
                return $baseDate->addMonths(3); // Quarterly review
            case 'Medium-risk':
                return $baseDate->addMonths(6); // Bi-Annually
            case 'Low-risk':
                return $baseDate->addYear(); // Annually
            default:
                return $baseDate->addYear(); // Default to annually
        }
    }
}
