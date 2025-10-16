<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class ClientAssessmentApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:manager,admin');
    }

    /**
     * Display pending client assessments for approval - OPTIMIZED VERSION
     */
    public function index()
    {
        try {
            // Use caching for pending assessments (2 minutes cache - shorter for approval data)
            $cacheKey = 'pending_assessments_' . auth()->id();
            
            $pendingAssessments = \Illuminate\Support\Facades\Cache::remember($cacheKey, 120, function() {
                // Get pending clients with relationships
                return Client::with(['creator', 'comprehensiveRiskAssessment'])
                    ->where('assessment_status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
            });

            return view('client-assessments.approval.index', [
                'pendingAssessments' => $pendingAssessments
            ]);
        } catch (Exception $e) {
            Log::error('Client assessment approval index error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('dashboard')
                ->with('error', 'Unable to load client assessments. Please try again.');
        }
    }

    /**
     * Show a specific client assessment for approval - OPTIMIZED VERSION
     */
    public function show(Client $client)
    {
        if (!$client->isPendingAssessment()) {
            return redirect()->route('client-assessments.approval.index')
                ->with('error', 'This client assessment is not pending approval.');
        }

        // Use caching for client assessment details (2 minutes cache)
        $cacheKey = 'client_assessment_' . $client->id . '_' . auth()->id();
        
        $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 120, function() use ($client) {
            // Get client with creator info
            $clientData = \DB::select("
                SELECT 
                    c.*, u.name as creator_name, u.email as creator_email
                FROM clients c
                LEFT JOIN users u ON c.created_by = u.id
                WHERE c.id = ? AND c.deleted_at IS NULL
            ", [$client->id])[0];

            // Get risks for this client
            $risks = \DB::select("
                SELECT 
                    r.id, r.title, r.description, r.risk_category, r.risk_rating,
                    r.impact, r.likelihood, r.status, r.created_at
                FROM risks r
                WHERE r.client_id = ? AND r.deleted_at IS NULL
                ORDER BY r.created_at DESC
            ", [$client->id]);

            // Latest KYC details from risks
            $kyc = \DB::select("
                SELECT 
                    client_type, gender, nationality, is_minor,
                    id_number, passport_number, registration_number, entity_type,
                    trading_address, income_source,
                    id_document_path, birth_certificate_path, passport_document_path,
                    proof_of_residence_path, kyc_form_path
                FROM risks
                WHERE client_id = ? AND deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT 1
            ", [$client->id]);
            // Legacy fallback: some historical records might not have client_id populated
            if (empty($kyc)) {
                $kyc = \DB::select("
                    SELECT 
                        client_type, gender, nationality, is_minor,
                        id_number, passport_number, registration_number, entity_type,
                        trading_address, income_source,
                        id_document_path, birth_certificate_path, passport_document_path,
                        proof_of_residence_path, kyc_form_path
                    FROM risks
                    WHERE client_name = ? AND deleted_at IS NULL
                    ORDER BY created_at DESC
                    LIMIT 1
                ", [$client->name]);
            }

            // Get comprehensive risk assessment
            $comprehensiveAssessment = \DB::select("
                SELECT 
                    cra.*, r.title as risk_title
                FROM comprehensive_risk_assessments cra
                INNER JOIN risks r ON cra.risk_id = r.id
                WHERE r.client_id = ?
                ORDER BY cra.created_at DESC
                LIMIT 1
            ", [$client->id]);
            // Legacy fallback: join via client_name if client_id wasn't stored on risk
            if (empty($comprehensiveAssessment)) {
                $comprehensiveAssessment = \DB::select("
                    SELECT 
                        cra.*, r.title as risk_title
                    FROM comprehensive_risk_assessments cra
                    INNER JOIN risks r ON cra.risk_id = r.id
                    WHERE r.client_name = ?
                    ORDER BY cra.created_at DESC
                    LIMIT 1
                ", [$client->name]);
            }

            // Load documents from client_documents with join back to this client's risks
            $documents = \DB::select("\n                SELECT d.*\n                FROM client_documents d\n                WHERE d.client_id = ? AND d.deleted_at IS NULL\n                ORDER BY d.created_at DESC\n            ", [$client->id]);

            return [
                'client' => $clientData,
                'kyc' => $kyc[0] ?? null,
                'risks' => $risks,
                'documents' => $documents,
                'comprehensiveAssessment' => $comprehensiveAssessment[0] ?? null
            ];
        });

        // Add the data to the client object for the view
        $client->creator_name = $data['client']->creator_name;
        $client->creator_email = $data['client']->creator_email;
        $client->risks = collect($data['risks']);
        $client->kyc = $data['kyc'];

        // Map the comprehensive assessment to the relation name used by the view
        if (!empty($data['comprehensiveAssessment'])) {
            $assessmentModel = new \App\Models\ComprehensiveRiskAssessment();
            $assessmentModel->forceFill((array) $data['comprehensiveAssessment']);
            $client->setRelation('comprehensiveRiskAssessment', $assessmentModel);
        } else {
            $client->setRelation('comprehensiveRiskAssessment', null);
        }

        // Attach documents collection for the view
        $client->setRelation('documents', collect($data['documents'] ?? []));

        // Ensure the creator relation is available for the view
        if (empty($client->relationLoaded('creator'))) {
            $creator = new \App\Models\User();
            $creator->forceFill([
                'name' => $data['client']->creator_name ?? 'Unknown',
                'email' => $data['client']->creator_email ?? null,
            ]);
            $client->setRelation('creator', $creator);
        }

        return view('client-assessments.approval.show', compact('client'));
    }

    /**
     * Approve a client assessment
     */
    public function approve(Request $request, Client $client)
    {
        if (!$client->canBeApproved()) {
            return redirect()->route('client-assessments.approval.index')
                ->with('error', 'This client assessment cannot be approved.');
        }

        $request->validate([
            'approval_notes' => 'nullable|string|max:1000'
        ]);

        try {
            \DB::beginTransaction();

            // Update client status
            $client->update([
                'assessment_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'approval_notes' => $request->approval_notes,
                'status' => 'Active', // Activate the client
                'dcs_comments' => 'Risk assessment completed via system',
                'dcs_risk_appetite' => $this->determineDCSRiskAppetite($client->overall_risk_rating ?? 'Low')
            ]);

            // Update due_date for all risks associated with this client
            $dueDate = $this->calculateDueDate($client->overall_risk_rating ?? 'Low');
            \App\Models\Risk::where('client_id', $client->id)->update([
                'due_date' => $dueDate,
                'updated_by' => auth()->id(),
                'updated_at' => now()
            ]);

            // Create comprehensive risk assessment record if it doesn't exist
            $this->createComprehensiveRiskAssessment($client);

            \DB::commit();

            // Clear all relevant caches to update counts immediately
            \Cache::flush(); // Clear all caches
            
            // Also clear specific caches for all users
            $users = \App\Models\User::all();
            foreach ($users as $user) {
                \Cache::forget('dashboard_stats_' . $user->id);
                \Cache::forget('dashboard_stats_v2_' . $user->id);
                \Cache::forget('client_stats_' . $user->id);
                \Cache::forget('client_stats_v2_' . $user->id);
                \Cache::forget('pending_assessments_' . $user->id);
            }
            \Cache::forget('recent_risks');
            \Cache::forget('rejected_clients_data');

            Log::info('Client assessment approved', [
                'client_id' => $client->id,
                'client_name' => $client->name,
                'approved_by' => auth()->id(),
                'approver_name' => auth()->user()->name
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Client assessment has been approved successfully. Dashboard updated.')
                ->with('dashboard_refresh', true);
        } catch (\Exception $e) {
            \DB::rollback();
            Log::error('Error approving client assessment', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed to approve client assessment: ' . $e->getMessage());
        }
    }

    /**
     * Reject a client assessment
     */
    public function reject(Request $request, Client $client)
    {
        if (!$client->canBeRejected()) {
            return redirect()->route('client-assessments.approval.index')
                ->with('error', 'This client assessment cannot be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $client->update([
            'assessment_status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'status' => 'Inactive' // Deactivate the client
        ]);

        // Clear dashboard cache to update counts immediately
        \Cache::flush(); // Clear all caches
        
        // Also clear specific caches for all users
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            \Cache::forget('dashboard_stats_' . $user->id);
            \Cache::forget('dashboard_stats_v2_' . $user->id);
            \Cache::forget('client_stats_' . $user->id);
            \Cache::forget('client_stats_v2_' . $user->id);
        }
        \Cache::forget('recent_risks');

        Log::info('Client assessment rejected', [
            'client_id' => $client->id,
            'client_name' => $client->name,
            'rejected_by' => auth()->id(),
            'rejector_name' => auth()->user()->name,
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Client assessment has been rejected. Dashboard updated.')
            ->with('dashboard_refresh', true);
    }

    /**
     * Bulk approve client assessments
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
            'approval_notes' => 'nullable|string|max:1000'
        ]);

        try {
            \DB::beginTransaction();

            $clients = Client::whereIn('id', $request->client_ids)
                ->where('assessment_status', 'pending')
                ->get();

            $approvedCount = 0;
            foreach ($clients as $client) {
                $client->update([
                    'assessment_status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'approval_notes' => $request->approval_notes,
                    'status' => 'Active',
                    'dcs_comments' => 'Risk assessment completed via system',
                    'dcs_risk_appetite' => $this->determineDCSRiskAppetite($client->overall_risk_rating ?? 'Low')
                ]);

                // Update due_date for all risks associated with this client
                $dueDate = $this->calculateDueDate($client->overall_risk_rating ?? 'Low');
                \App\Models\Risk::where('client_id', $client->id)->update([
                    'due_date' => $dueDate,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);

                // Create comprehensive risk assessment record
                $this->createComprehensiveRiskAssessment($client);
                $approvedCount++;
            }

            \DB::commit();
            
            // Clear dashboard cache to update counts immediately
            \Cache::flush(); // Clear all caches
            
            // Also clear specific caches for all users
            $users = \App\Models\User::all();
            foreach ($users as $user) {
                \Cache::forget('dashboard_stats_' . $user->id);
                \Cache::forget('dashboard_stats_v2_' . $user->id);
                \Cache::forget('client_stats_' . $user->id);
                \Cache::forget('client_stats_v2_' . $user->id);
            }
            \Cache::forget('recent_risks');
        } catch (\Exception $e) {
            \DB::rollback();
            Log::error('Error in bulk approval', [
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed to approve client assessments: ' . $e->getMessage());
        }

        Log::info('Bulk client assessment approval', [
            'approved_count' => $approvedCount,
            'approved_by' => auth()->id(),
            'approver_name' => auth()->user()->name
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Successfully approved {$approvedCount} client assessments. Dashboard updated.")
            ->with('dashboard_refresh', true);
    }

    /**
     * Get approval statistics
     */
    public function stats()
    {
        $stats = [
            'pending' => Client::where('assessment_status', 'pending')->count(),
            'approved' => Client::where('assessment_status', 'approved')->count(),
            'rejected' => Client::where('assessment_status', 'rejected')->count(),
            'total' => Client::count()
        ];

        return response()->json($stats);
    }

    /**
     * Show rejected assessments
     */
    public function rejected()
    {
        try {
            $rejectedAssessments = Client::with(['creator', 'risks'])
                ->where('assessment_status', 'rejected')
                ->orderBy('approved_at', 'desc')
                ->get();

            return view('client-assessments.approval.rejected', compact('rejectedAssessments'));
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Create comprehensive risk assessment record for a client
     * This method now creates a NEW assessment each time, allowing multiple assessments per client
     */
    private function createComprehensiveRiskAssessment(Client $client)
    {
        // Always create a new comprehensive risk assessment
        // This allows clients to have multiple assessments over time with different risk categories

        // Get the client's risks
        $risks = $client->risks;
        
        // Initialize comprehensive assessment data
        $comprehensiveData = [
            'risk_id' => null, // Will be set if risks exist
            'total_points' => $client->overall_risk_points ?? 0,
            'overall_risk_rating' => $client->overall_risk_rating ?? 'Not Assessed',
            'client_acceptance' => $client->client_acceptance ?? 'Not Determined',
            'ongoing_monitoring' => $client->ongoing_monitoring ?? 'Not Determined',
            'created_by' => auth()->id(),
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
                'created_by' => auth()->id() ?? 1,
                'due_date' => $this->calculateDueDate($client->overall_risk_rating ?? 'Low'),
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
        return \App\Models\ComprehensiveRiskAssessment::create($comprehensiveData);
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
     * Calculate due date based on risk rating according to FIC compliance matrix
     */
    private function calculateDueDate($riskRating)
    {
        $baseDate = now();
        $rating = strtolower($riskRating);
        
        if (str_contains($rating, 'very high') || str_contains($rating, 'critical')) {
            return null; // N/A - Do not accept client
        } elseif (str_contains($rating, 'high')) {
            return $baseDate->copy()->addMonths(3); // Quarterly review
        } elseif (str_contains($rating, 'medium')) {
            return $baseDate->copy()->addMonths(6); // Bi-Annually
        } else {
            return $baseDate->copy()->addYear(); // Annually (Low risk)
        }
    }
}