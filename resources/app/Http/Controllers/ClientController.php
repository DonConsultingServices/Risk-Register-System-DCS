<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Risk;
use App\Models\ComprehensiveRiskAssessment;
use App\Services\RiskCalculationService;
use App\Services\ClientHistoryService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     * Only show approved clients in the client management list.
     * Group clients by name to avoid duplication and show assessment history.
     */
    public function index()
    {
        // Use caching for statistics to improve performance
        $stats = Cache::remember('client_stats_' . auth()->id(), 300, function () {
            // Get statistics with optimized queries
            $approvedClients = Client::where('assessment_status', 'approved')->count();
            $rejectedClients = Client::where('assessment_status', 'rejected')->count();
            $pendingClients = Client::where('assessment_status', 'pending')->count();
            
            // Get risk rating counts for approved clients only
            $riskCounts = Client::where('assessment_status', 'approved')
                ->selectRaw('overall_risk_rating, COUNT(*) as count')
                ->groupBy('overall_risk_rating')
                ->pluck('count', 'overall_risk_rating')
                ->toArray();

            // Normalize inconsistent rating labels to canonical keys
            $normalizedCounts = [
                'Low' => 0,
                'Medium' => 0,
                'High' => 0,
                'Critical' => 0,
            ];
            foreach ($riskCounts as $label => $count) {
                $l = strtolower((string)$label);
                if (str_contains($l, 'critical')) {
                    $normalizedCounts['Critical'] += $count;
                } elseif (str_contains($l, 'high')) {
                    $normalizedCounts['High'] += $count;
                } elseif (str_contains($l, 'medium')) {
                    $normalizedCounts['Medium'] += $count;
                } elseif (str_contains($l, 'low')) {
                    $normalizedCounts['Low'] += $count;
                }
            }
            
            return [
                'totalClients' => $approvedClients,
                'lowRiskClients' => $normalizedCounts['Low'] ?? 0,
                'mediumRiskClients' => $normalizedCounts['Medium'] ?? 0,
                'highRiskClients' => $normalizedCounts['High'] ?? 0,
                'criticalRiskClients' => $normalizedCounts['Critical'] ?? 0,
                'rejectedClients' => $rejectedClients,
                'pendingClients' => $pendingClients,
                'approvedClients' => $approvedClients,
            ];
        });

        // Get clients with optimized pagination query
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        
        // Use raw SQL for better performance with pagination - SHOW ALL ASSESSMENTS WITH HISTORY
        $clients = DB::select("
            SELECT 
                c.id, c.name, c.overall_risk_rating, c.overall_risk_points, 
                c.created_at, c.assessment_status, c.client_screening_date, 
                c.client_screening_result, c.dcs_risk_appetite, c.dcs_comments, 
                c.client_acceptance, c.ongoing_monitoring,
                cra.overall_risk_rating as comprehensive_rating,
                cra.client_acceptance as comprehensive_acceptance,
                cra.ongoing_monitoring as comprehensive_monitoring,
                cra.cr_risk_id, cra.cr_risk_name, cra.cr_impact, cra.cr_likelihood, cra.cr_risk_rating,
                cra.sr_risk_id, cra.sr_risk_name, cra.sr_impact, cra.sr_likelihood, cra.sr_risk_rating,
                cra.pr_risk_id, cra.pr_risk_name, cra.pr_impact, cra.pr_likelihood, cra.pr_risk_rating,
                cra.dr_risk_id, cra.dr_risk_name, cra.dr_impact, cra.dr_likelihood, cra.dr_risk_rating,
                cra.total_points, cra.overall_risk_rating as cra_overall_risk_rating
            FROM clients c
            LEFT JOIN risks r ON r.client_id = c.id 
                AND r.deleted_at IS NULL
                AND r.id = (
                    SELECT r2.id 
                    FROM risks r2 
                    WHERE r2.client_id = c.id AND r2.deleted_at IS NULL 
                    ORDER BY r2.created_at DESC 
                    LIMIT 1
                )
            LEFT JOIN comprehensive_risk_assessments cra ON cra.risk_id = r.id
            WHERE c.assessment_status = 'approved' 
            AND c.deleted_at IS NULL
            ORDER BY c.name, c.created_at DESC
            LIMIT ? OFFSET ?
        ", [$perPage, ($currentPage - 1) * $perPage]);
        
        // Get total count for pagination - COUNT ALL ASSESSMENTS
        $totalClients = DB::select("
            SELECT COUNT(*) as total
            FROM clients c
            WHERE c.assessment_status = 'approved' 
            AND c.deleted_at IS NULL
        ")[0]->total;
        
        // Convert stdClass objects to Eloquent models for proper route binding
        $clientModels = collect($clients)->map(function($clientData) {
            // Create a new Client model instance
            $client = new \App\Models\Client();
            
            // Fill the model with the raw data
            foreach ($clientData as $key => $value) {
                $client->$key = $value;
            }
            
            // Set the key name for route model binding
            $client->setKeyName('id');
            $client->exists = true;
            
            // Ensure data integrity for each client
            $this->ensureClientDataIntegrity($client);
            
            return $client;
        });
        
        // Create pagination object manually
        $clients = new \Illuminate\Pagination\LengthAwarePaginator(
            $clientModels,
            $totalClients,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('clients.index', array_merge($stats, compact('clients')));
    }

    /**
     * Show the form for creating a new resource.
     * Clients can only be added through the risk assessment process.
     */
    public function create()
    {
        return redirect()->route('client-risk-assessment.index')
            ->with('info', 'Clients can only be added through the risk assessment process. Please use the Client Risk Assessment form.');
    }

    /**
     * Store a newly created resource in storage.
     * Clients can only be added through the risk assessment process.
     */
    public function store(Request $request)
    {
        return redirect()->route('client-risk-assessment.index')
            ->with('info', 'Clients can only be added through the risk assessment process. Please use the Client Risk Assessment form.');
    }

    /**
     * Display the specified resource - OPTIMIZED VERSION
     * Only allow viewing approved clients.
     */
    public function show(Client $client)
    {
        // Only allow viewing approved clients
        if ($client->assessment_status !== 'approved') {
            return redirect()->route('clients.index')
                ->with('error', 'This client is not approved and cannot be viewed.');
        }

        // Ensure related documents are available for the view
        $client->loadMissing(['documents', 'approver']);

        // Use caching for client show data (5 minutes cache)
        $cacheKey = 'client_show_' . $client->id . '_' . auth()->id();
        
        $data = Cache::remember($cacheKey, 300, function() use ($client) {
            // Auto-heal on view
            $this->ensureClientDataIntegrity($client);

            // Get client with assessment history
            $clientWithHistory = ClientHistoryService::getClientWithHistory($client->id);
            
            // Get all assessments for this client with optimized query (legacy support)
            $allClientAssessments = DB::select("
                SELECT 
                    c.id, c.name, c.email, c.company, c.overall_risk_rating, 
                    c.overall_risk_points, c.created_at, c.updated_at,
                    u.name as creator_name
                FROM clients c
                LEFT JOIN users u ON c.created_by = u.id
                WHERE c.name = ? AND c.assessment_status = 'approved' AND c.deleted_at IS NULL
                ORDER BY c.created_at DESC
            ", [$client->name]);

            // Get recent risks for this client
            $recentRisks = DB::select("
                SELECT 
                    r.id, r.title, r.description, r.risk_category, r.risk_rating,
                    r.impact, r.likelihood, r.status, r.created_at
                FROM risks r
                WHERE r.client_id = ? AND r.deleted_at IS NULL
                ORDER BY r.created_at DESC
                LIMIT 10
            ", [$client->id]);

            // Get comprehensive risk assessment data from clients table
            $comprehensiveAssessment = DB::select("
                SELECT 
                    c.id, c.overall_risk_rating, c.client_acceptance,
                    c.ongoing_monitoring, c.overall_risk_points as total_points, 
                    c.dcs_risk_appetite, c.dcs_comments, c.created_at, c.updated_at
                FROM clients c
                WHERE c.id = ? AND c.deleted_at IS NULL
                LIMIT 1
            ", [$client->id]);

            return [
                'allClientAssessments' => $allClientAssessments,
                'recentRisks' => $recentRisks,
                'comprehensiveAssessment' => $comprehensiveAssessment[0] ?? null
            ];
        });

        // Group assessments - current and history
        $allClientAssessments = collect($data['allClientAssessments']);
        $currentAssessment = $allClientAssessments->first();
        $assessmentHistory = $allClientAssessments->skip(1)->values();

        // Add the data to the client object for the view
        $client->recent_risks = collect($data['recentRisks']);
        $client->comprehensive_assessment = $data['comprehensiveAssessment'];
        
        // Get comprehensive risk assessment to show accurate risk distribution
        $craData = DB::select("
            SELECT 
                cra.cr_risk_rating, cra.sr_risk_rating, cra.pr_risk_rating, cra.dr_risk_rating
            FROM comprehensive_risk_assessments cra
            INNER JOIN risks r ON r.id = cra.risk_id
            WHERE r.client_id = ? AND r.deleted_at IS NULL
            ORDER BY r.created_at DESC
            LIMIT 1
        ", [$client->id]);
        
        // Calculate risk distribution based on comprehensive assessment (CR, SR, PR, DR)
        if (!empty($craData)) {
            $cra = $craData[0];
            $ratings = [
                $cra->cr_risk_rating,
                $cra->sr_risk_rating,
                $cra->pr_risk_rating,
                $cra->dr_risk_rating
            ];
            
            $client->total_risks = 4; // Always 4 categories (CR, SR, PR, DR)
            $client->high_risks = collect($ratings)->filter(function($rating) {
                return str_contains(strtolower($rating ?? ''), 'high');
            })->count();
            $client->medium_risks = collect($ratings)->filter(function($rating) {
                return str_contains(strtolower($rating ?? ''), 'medium');
            })->count();
            $client->low_risks = collect($ratings)->filter(function($rating) {
                return str_contains(strtolower($rating ?? ''), 'low');
            })->count();
            $client->open_risks = 4; // All 4 categories are assessed
        } else {
            // Fallback to old method if no comprehensive assessment exists
            $recentRisks = collect($data['recentRisks']);
            $client->total_risks = $recentRisks->count();
            $client->high_risks = $recentRisks->filter(function($risk) {
                return str_contains(strtolower($risk->risk_rating ?? ''), 'high');
            })->count();
            $client->medium_risks = $recentRisks->filter(function($risk) {
                return str_contains(strtolower($risk->risk_rating ?? ''), 'medium');
            })->count();
            $client->low_risks = $recentRisks->filter(function($risk) {
                return str_contains(strtolower($risk->risk_rating ?? ''), 'low');
            })->count();
            $client->open_risks = $recentRisks->where('status', 'Open')->count();
        }
        
        // Get the new assessment history from the service
        $clientWithHistory = ClientHistoryService::getClientWithHistory($client->id);
        $newAssessmentHistory = $clientWithHistory->assessmentHistory ?? collect();
        
        return view('clients.show', compact('client', 'assessmentHistory', 'newAssessmentHistory'));
    }

    /**
     * Show client details in modal format (no sidebar layout)
     */
    public function modalDetails(Client $client)
    {
        // Only allow viewing approved clients
        if ($client->assessment_status !== 'approved') {
            return response()->json(['error' => 'This client is not approved and cannot be viewed.'], 403);
        }

        // Ensure related documents are available for the view
        $client->loadMissing(['documents', 'approver']);

        // Auto-heal on view
        $this->ensureClientDataIntegrity($client);

        // Load KYC data and document paths from risks table (where they actually exist)
        $riskData = DB::select("
            SELECT client_type, gender, nationality, is_minor, id_number, passport_number, 
                   registration_number, entity_type, trading_address, income_source, company_nationality,
                   id_document_path, birth_certificate_path, passport_document_path, 
                   proof_of_residence_path, kyc_form_path,
                   registration_document_path, foreign_registration_path, tax_certificate_path
            FROM risks 
            WHERE client_id = ? AND deleted_at IS NULL 
            ORDER BY created_at DESC 
            LIMIT 1
        ", [$client->id]);

        // Merge KYC data and document paths into client object
        if (!empty($riskData)) {
            $risk = $riskData[0];
            // KYC data
            $client->client_type = $risk->client_type;
            $client->gender = $risk->gender;
            $client->nationality = $risk->nationality;
            $client->is_minor = $risk->is_minor;
            $client->id_number = $risk->id_number;
            $client->passport_number = $risk->passport_number;
            $client->registration_number = $risk->registration_number;
            $client->entity_type = $risk->entity_type;
            $client->trading_address = $risk->trading_address;
            $client->income_source = $risk->income_source;
            $client->company_nationality = $risk->company_nationality;
            // Document paths
            $client->id_document_path = $risk->id_document_path;
            $client->birth_certificate_path = $risk->birth_certificate_path;
            $client->passport_document_path = $risk->passport_document_path;
            $client->proof_of_residence_path = $risk->proof_of_residence_path;
            $client->kyc_form_path = $risk->kyc_form_path;
            // Juristic-specific documents
            $client->registration_document_path = $risk->registration_document_path;
            $client->foreign_registration_path = $risk->foreign_registration_path;
            $client->tax_certificate_path = $risk->tax_certificate_path;
        }

        return view('clients.modal-details', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     * Only allow editing approved clients.
     */
    public function edit(Client $client)
    {
        // Only allow editing approved clients
        if ($client->assessment_status !== 'approved') {
            return redirect()->route('clients.index')
                ->with('error', 'This client is not approved and cannot be edited.');
        }

        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive,Pending,Suspended',
            'notes' => 'nullable|string',
        ]);

        try {
            $client->update($validated);
            
            Log::info('Client updated', ['client_id' => $client->id, 'user_id' => auth()->id()]);
            
            return redirect()->route('clients.index')
                ->with('success', 'Client updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update client', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update client. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            
            // Debug logging
            Log::info('Attempting to delete client', [
                'client_id' => $client->id,
                'client_name' => $client->name,
                'user_id' => auth()->id()
            ]);
            
            // Use database transaction for atomicity
            DB::beginTransaction();
            
            // Load relationships to ensure they're available
            $client->load(['risks']);
            
            // Delete related risks first to avoid foreign key constraints
            foreach ($client->risks as $risk) {
                // Load risk relationships
                $risk->load(['comprehensiveRiskAssessment']);
                
                // Delete comprehensive risk assessment first
                if ($risk->comprehensiveRiskAssessment) {
                    $risk->comprehensiveRiskAssessment->delete();
                    Log::info('Comprehensive risk assessment deleted', [
                        'cra_id' => $risk->comprehensiveRiskAssessment->id,
                        'risk_id' => $risk->id
                    ]);
                }
                
                // Then delete the risk
                $risk->delete();
                Log::info('Related risk deleted', [
                    'risk_id' => $risk->id,
                    'client_id' => $client->id
                ]);
            }
            
            // Delete the client
            $client->delete();
            
            // Commit the transaction
            DB::commit();
            
            Log::info('Client deleted successfully', ['client_id' => $client->id, 'user_id' => auth()->id()]);
            
            return redirect()->route('clients.index')
                ->with('success', 'Client deleted successfully');
        } catch (\Exception $e) {
            // Rollback transaction on any failure
            DB::rollBack();
            
            Log::error('Failed to delete client', [
                'error' => $e->getMessage(),
                'client_id' => $id ?? 'unknown',
                'user_id' => auth()->id()
            ]);
            return back()->with('error', 'Failed to delete client: ' . $e->getMessage());
        }
    }

    /**
     * Export client data
     */
    public function export(Client $client)
    {
        try {
            $data = [
                'client' => $client->toArray(),
                'risks' => $client->risks->toArray(),
                'exported_at' => now()->toISOString(),
                'exported_by' => auth()->user()->name,
            ];
            
            $filename = 'client_' . $client->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';
            
            return response()->json($data)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            Log::error('Failed to export client', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to export client data. Please try again.');
        }
    }


    /**
     * Bulk delete selected clients
     * Now handles grouped clients - deletes all assessments for selected client names
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'client_ids' => 'required|string'
        ]);

        $clientIds = explode(',', $request->client_ids);
        $deletedCount = 0;
        $deletedClientNames = [];

        try {
            Log::info('Starting bulk delete operation', [
                'client_ids' => $request->client_ids,
                'user_id' => auth()->id()
            ]);
            
            // Debug: Log the raw client IDs array
            Log::info('Raw client IDs array', [
                'raw_client_ids' => $clientIds,
                'count' => count($clientIds),
                'user_id' => auth()->id()
            ]);
            
            // Debug: Check what clients actually exist in the database
            $existingClientIds = Client::whereIn('id', $clientIds)->pluck('id')->toArray();
            Log::info('Existing client IDs in database', [
                'requested_ids' => $clientIds,
                'existing_ids' => $existingClientIds,
                'missing_ids' => array_diff($clientIds, $existingClientIds),
                'user_id' => auth()->id()
            ]);
            
            // Debug: Check if any clients exist at all
            $allClientIds = Client::pluck('id')->toArray();
            Log::info('All client IDs in database', [
                'all_client_ids' => $allClientIds,
                'user_id' => auth()->id()
            ]);
            
            // Use database transaction for atomicity
            DB::beginTransaction();
            
            foreach ($clientIds as $clientId) {
                // Clean the client ID (remove any whitespace)
                $clientId = trim($clientId);
                
                Log::info('Processing client ID in bulk delete loop', [
                    'raw_client_id' => $clientId,
                    'type' => gettype($clientId),
                    'user_id' => auth()->id()
                ]);
                
                // Skip empty client IDs
                if (empty($clientId)) {
                    Log::warning('Empty client ID found in bulk delete request', [
                        'client_ids' => $request->client_ids,
                        'user_id' => auth()->id()
                    ]);
                    continue;
                }
                
                // Try to find the client with different approaches
                $client = Client::find($clientId);
                if (!$client) {
                    // Try as integer
                    $client = Client::find((int)$clientId);
                    Log::info('Tried integer conversion', [
                        'client_id' => $clientId,
                        'converted_id' => (int)$clientId,
                        'found' => $client ? 'yes' : 'no',
                        'user_id' => auth()->id()
                    ]);
                }
                
                if (!$client) {
                    Log::warning('Client not found for bulk delete', [
                        'client_id' => $clientId,
                        'user_id' => auth()->id()
                    ]);
                    continue; // Skip this client and continue with others
                }
                
                $clientName = $client->name;
                
                Log::info('Processing client for bulk delete', [
                    'client_id' => $client->id,
                    'client_name' => $clientName,
                    'user_id' => auth()->id()
                ]);
                
                // Delete ALL assessments for this client name
                $allClientAssessments = Client::where('name', $clientName)
                    ->where('assessment_status', 'approved')
                    ->get();
                
                Log::info('Found assessments to delete', [
                    'client_name' => $clientName,
                    'assessment_count' => $allClientAssessments->count(),
                    'user_id' => auth()->id()
                ]);
                
                foreach ($allClientAssessments as $assessment) {
                    try {
                        // Load all relationships
                        $assessment->load(['risks']);
                        
                        // Delete related risks first to avoid foreign key constraints
                        foreach ($assessment->risks as $risk) {
                            // Load risk relationships
                            $risk->load(['comprehensiveRiskAssessment']);
                            
                            // Delete comprehensive risk assessment first
                            if ($risk->comprehensiveRiskAssessment) {
                                $risk->comprehensiveRiskAssessment->delete();
                                Log::info('Comprehensive risk assessment deleted', [
                                    'cra_id' => $risk->comprehensiveRiskAssessment->id,
                                    'risk_id' => $risk->id
                                ]);
                            }
                            
                            // Then delete the risk
                            $risk->delete();
                            Log::info('Related risk deleted', [
                                'risk_id' => $risk->id,
                                'client_id' => $assessment->id
                            ]);
                        }
                        
                        // Finally delete the client assessment
                        $assessment->delete();
                        $deletedCount++;
                        
                        Log::info('Client assessment deleted via bulk operation', [
                            'client_id' => $assessment->id,
                            'client_name' => $clientName,
                            'user_id' => auth()->id()
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to delete individual assessment in bulk operation', [
                            'client_id' => $assessment->id,
                            'client_name' => $clientName,
                            'error' => $e->getMessage(),
                            'user_id' => auth()->id()
                        ]);
                        // Rollback transaction on any failure
                        DB::rollBack();
                        throw $e;
                    }
                }
                
                if (!in_array($clientName, $deletedClientNames)) {
                    $deletedClientNames[] = $clientName;
                }
            }

            // Commit the transaction
            DB::commit();

            // Check if any clients were actually processed
            if (count($deletedClientNames) === 0) {
                Log::warning('No valid clients found for bulk delete', [
                    'client_ids' => $request->client_ids,
                    'user_id' => auth()->id()
                ]);
                return back()->with('error', 'No valid clients found to delete. Please check your selection and try again.');
            }

            $message = count($deletedClientNames) === 1 
                ? "Client '{$deletedClientNames[0]}' and all their assessments ({$deletedCount} total) deleted successfully" 
                : count($deletedClientNames) . " clients and all their assessments ({$deletedCount} total) deleted successfully";

            return redirect()->route('clients.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            // Rollback transaction on any failure
            DB::rollBack();
            
            Log::error('Bulk delete failed', [
                'error' => $e->getMessage(),
                'client_ids' => $request->client_ids,
                'user_id' => auth()->id()
            ]);
            return back()->with('error', 'Failed to delete selected clients: ' . $e->getMessage());
        }
    }

    /**
     * Bulk export selected clients
     * Now handles grouped clients - exports all assessments for selected client names
     */
    public function bulkExport(Request $request)
    {
        $request->validate([
            'client_ids' => 'required|string'
        ]);

        $clientIds = explode(',', $request->client_ids);
        $exportedClientNames = [];
        $allAssessments = collect();

        try {
            foreach ($clientIds as $clientId) {
                $client = Client::find($clientId);
                if ($client) {
                    $clientName = $client->name;
                    
                    // Get ALL assessments for this client name
                    $clientAssessments = Client::with(['risks', 'latestComprehensiveRiskAssessment'])
                        ->where('name', $clientName)
                        ->where('assessment_status', 'approved')
                        ->get();
                    
                    $allAssessments = $allAssessments->merge($clientAssessments);
                    
                    if (!in_array($clientName, $exportedClientNames)) {
                        $exportedClientNames[] = $clientName;
                    }
                }
            }

            $data = [
                'clients' => $allAssessments->map(function($client) {
                    return [
                        'id' => $client->id,
                        'name' => $client->name,
                        'email' => $client->email,
                        'company' => $client->company,
                        'industry' => $client->industry,
                        'status' => $client->status,
                        'client_screening_date' => $client->client_screening_date,
                        'client_screening_result' => $client->client_screening_result,
                        'risk_category' => $client->risk_category,
                        'risk_id' => $client->risk_id,
                        'overall_risk_points' => $client->overall_risk_points,
                        'overall_risk_rating' => $client->overall_risk_rating,
                        'client_acceptance' => $client->client_acceptance,
                        'ongoing_monitoring' => $client->ongoing_monitoring,
                        'dcs_risk_appetite' => $client->dcs_risk_appetite,
                        'dcs_comments' => $client->dcs_comments,
                        'comprehensive_assessment' => $client->comprehensiveRiskAssessment ? [
                            'total_points' => $client->comprehensiveRiskAssessment->total_points,
                            'overall_risk_rating' => $client->comprehensiveRiskAssessment->overall_risk_rating,
                            'client_acceptance' => $client->comprehensiveRiskAssessment->client_acceptance,
                            'ongoing_monitoring' => $client->comprehensiveRiskAssessment->ongoing_monitoring,
                        ] : null,
                        'risks_count' => $client->risks->count(),
                        'created_at' => $client->created_at,
                        'updated_at' => $client->updated_at,
                    ];
                })->toArray(),
                'exported_at' => now()->toISOString(),
                'exported_by' => auth()->user()->name,
                'total_clients' => count($exportedClientNames),
                'total_assessments' => $allAssessments->count(),
                'client_names' => $exportedClientNames,
            ];
            
            $filename = 'clients_bulk_export_' . now()->format('Y-m-d_H-i-s') . '.json';
            
            return response()->json($data)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            Log::error('Bulk export failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to export selected clients. Please try again.');
        }
    }

    /**
     * Display risk analysis for a specific client
     * Only allow viewing approved clients.
     */
    public function riskAnalysis(Client $client)
    {
        // Only allow viewing approved clients
        if ($client->assessment_status !== 'approved') {
            return redirect()->route('clients.index')
                ->with('error', 'This client is not approved and cannot be viewed.');
        }

        // Ensure data integrity before analysis
        $this->ensureClientDataIntegrity($client);

        $client->load(['risks' => function($query) {
            $query->latest();
        }]);

        // Calculate risk statistics
        $totalRisks = $client->risks->count();
        $highRisks = $client->risks->where('overall_risk_rating', 'High')->count();
        $openRisks = $client->risks->where('status', 'Open')->count();
        $inProgressRisks = $client->risks->where('status', 'In Progress')->count();
        $closedRisks = $client->risks->where('status', 'Closed')->count();
        $onHoldRisks = $client->risks->where('status', 'On Hold')->count();
        $criticalRisks = $client->risks->where('overall_risk_rating', 'Critical')->count();
        $lowRisks = $client->risks->where('overall_risk_rating', 'Low')->count();
        $mediumRisks = $client->risks->where('overall_risk_rating', 'Medium')->count();
        
        // Calculate overdue risks (risks past their due date)
        $overdueRisks = $client->risks->filter(function($risk) {
            return $risk->due_date && $risk->due_date->isPast() && $risk->status !== 'Closed';
        })->count();

        // Calculate risk matrix data
        $matrixData = $this->calculateRiskMatrixData($client->risks);

        // Calculate trend data for charts
        $trendDataResult = $this->calculateTrendData($client);
        $trendData = $trendDataResult['data'];
        $trendLabels = $trendDataResult['labels'];

        // Add calculated properties to client model
        $client->total_risks = $totalRisks;
        $client->high_risks = $highRisks;
        $client->open_risks = $openRisks;
        $client->low_risks = $lowRisks;
        $client->medium_risks = $mediumRisks;

        return view('clients.risk-analysis', compact(
            'client', 
            'overdueRisks', 
            'criticalRisks', 
            'openRisks', 
            'inProgressRisks', 
            'closedRisks', 
            'onHoldRisks',
            'matrixData',
            'trendData',
            'trendLabels'
        ));
    }

    // --- Internal helpers ---

    /**
     * Calculate risk matrix data for visualization
     */
    private function calculateRiskMatrixData($risks)
    {
        $matrixData = [
            'Very High' => ['Low' => 0, 'Medium' => 0, 'High' => 0, 'Critical' => 0],
            'High' => ['Low' => 0, 'Medium' => 0, 'High' => 0, 'Critical' => 0],
            'Medium' => ['Low' => 0, 'Medium' => 0, 'High' => 0, 'Critical' => 0],
            'Low' => ['Low' => 0, 'Medium' => 0, 'High' => 0, 'Critical' => 0],
        ];

        foreach ($risks as $risk) {
            $likelihood = $risk->likelihood ?? 'Low';
            $impact = $risk->impact ?? 'Low';
            
            // Map likelihood to matrix structure (database has Low/Medium/High, matrix expects Very High/High/Medium/Low)
            $likelihoodMap = [
                'Low' => 'Low',
                'Medium' => 'Medium', 
                'High' => 'High'
            ];
            
            // Map impact levels to matrix structure (database has Low/Medium/High, matrix expects Low/Medium/High/Critical)
            $impactMap = [
                'Low' => 'Low',
                'Medium' => 'Medium',
                'High' => 'High'
            ];
            
            $mappedLikelihood = $likelihoodMap[$likelihood] ?? 'Low';
            $mappedImpact = $impactMap[$impact] ?? 'Low';
            
            if (isset($matrixData[$mappedLikelihood][$mappedImpact])) {
                $matrixData[$mappedLikelihood][$mappedImpact]++;
            }
        }

        return $matrixData;
    }

    /**
     * Calculate trend data for charts
     */
    private function calculateTrendData($client)
    {
        // Get all risks for this client (not just last 6 months for better data)
        $allRisks = $client->risks;
        
        // Group by month
        $trendData = [];
        $trendLabels = [];
        
        // Get the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthRisks = $allRisks->filter(function($risk) use ($month) {
                return $risk->created_at->format('Y-m') === $month->format('Y-m');
            });
            
            $trendData[] = $monthRisks->count();
            $trendLabels[] = $month->format('M Y');
        }
        
        // If no data in last 6 months, show current month with all risks
        if (array_sum($trendData) === 0 && $allRisks->count() > 0) {
            $trendData = [0, 0, 0, 0, 0, $allRisks->count()];
            $trendLabels = [
                now()->subMonths(5)->format('M Y'),
                now()->subMonths(4)->format('M Y'),
                now()->subMonths(3)->format('M Y'),
                now()->subMonths(2)->format('M Y'),
                now()->subMonths(1)->format('M Y'),
                now()->format('M Y')
            ];
        }
        
        return [
            'data' => $trendData,
            'labels' => $trendLabels
        ];
    }

    private function ensureClientDataIntegrity(Client $client): void
    {
        $dirty = false;

        // 1) Ensure overall risk fields - use defaults if no risks exist
        if (empty($client->overall_risk_points) || empty($client->overall_risk_rating)) {
            $selectedRisks = $client->risks;
            if ($selectedRisks && $selectedRisks->count() > 0) {
                $score = RiskCalculationService::calculateTotalScore($selectedRisks);
                $rating = RiskCalculationService::determineRiskRating($score);
                $client->overall_risk_points = $score;
                $client->overall_risk_rating = $rating;
            } else {
                // Use defaults if no risks exist
                $client->overall_risk_points = 10;
                $client->overall_risk_rating = 'Low';
            }
            
            // Always set decision and monitoring
            $client->client_acceptance = RiskCalculationService::determineClientDecision($client->overall_risk_rating);
            $client->ongoing_monitoring = RiskCalculationService::determineMonitoringFrequency($client->overall_risk_rating);
            $dirty = true;
        }

        // 2) Ensure DCS fields
        if (empty($client->dcs_comments)) {
            $client->dcs_comments = 'Risk assessment completed via system';
            $dirty = true;
        }
        if (empty($client->dcs_risk_appetite)) {
            $client->dcs_risk_appetite = $this->determineDCSRiskAppetite($client->overall_risk_rating ?? 'Low');
            $dirty = true;
        }

        if ($dirty) {
            // Persist updates safely
            try {
                $client->save();
                Log::info('Auto-healed client data', [
                    'client_id' => $client->id,
                    'client_name' => $client->name,
                    'risk_points' => $client->overall_risk_points,
                    'risk_rating' => $client->overall_risk_rating,
                ]);
            } catch (\Exception $e) {
                Log::error('Auto-heal client update failed', [
                    'client_id' => $client->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // 3) Ensure a comprehensive risk assessment exists
        if (!$client->latestComprehensiveRiskAssessment) {
            $this->createComprehensiveRiskAssessmentIfMissing($client);
        }
    }

    private function createComprehensiveRiskAssessmentIfMissing(Client $client): void
    {
        try {
            // Link to an existing risk or create a minimal one
            $risk = $client->risks()->latest()->first();
            if (!$risk) {
                $risk = Risk::create([
                    'title' => 'Comprehensive Risk Assessment - ' . $client->name,
                    'description' => 'Comprehensive risk assessment created automatically',
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

            $rating = $client->overall_risk_rating ?? 'Low';
            $impact = $this->getImpactFromRating($rating);
            $likelihood = $this->getLikelihoodFromRating($rating);

            // Get specific risk names based on risk IDs
            $crRiskId = 'CR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
            $srRiskId = 'SR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
            $prRiskId = 'PR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
            $drRiskId = 'DR-' . str_pad($client->id, 2, '0', STR_PAD_LEFT);
            
            // Map risk IDs to specific risk names
            $riskNameMap = [
                'CR-01' => 'PIP / PEP client',
                'CR-02' => 'Corporate client',
                'CR-03' => 'Individual client',
                'SR-01' => 'High-risk services',
                'SR-02' => 'Complex services',
                'SR-03' => 'Standard services',
                'PR-01' => 'Unrecorded face-to-face transactions',
                'PR-02' => 'Cash Payments',
                'PR-03' => 'EFTS/SWIFT',
                'PR-04' => 'POS Payments',
                'DR-01' => 'Remote service risks',
                'DR-02' => 'Face-to-face service risks',
            ];
            
            ComprehensiveRiskAssessment::create([
                'risk_id' => $risk->id,
                // CR
                'cr_risk_id' => $crRiskId,
                'cr_risk_name' => $riskNameMap[$crRiskId] ?? 'Client Risk Assessment',
                'cr_impact' => $impact,
                'cr_likelihood' => $likelihood,
                'cr_risk_rating' => $this->normalizeRiskRating($rating),
                'cr_status' => 'Open',
                // SR
                'sr_risk_id' => $srRiskId,
                'sr_risk_name' => $riskNameMap[$srRiskId] ?? 'Service Risk Assessment',
                'sr_impact' => $impact,
                'sr_likelihood' => $likelihood,
                'sr_risk_rating' => $this->normalizeRiskRating($rating),
                'sr_status' => 'Open',
                // PR
                'pr_risk_id' => $prRiskId,
                'pr_risk_name' => $riskNameMap[$prRiskId] ?? 'Process Risk Assessment',
                'pr_impact' => $impact,
                'pr_likelihood' => $likelihood,
                'pr_risk_rating' => $this->normalizeRiskRating($rating),
                'pr_status' => 'Open',
                // DR
                'dr_risk_id' => $drRiskId,
                'dr_risk_name' => $riskNameMap[$drRiskId] ?? 'Delivery/Data Risk Assessment',
                'dr_impact' => $impact,
                'dr_likelihood' => $likelihood,
                'dr_risk_rating' => $this->normalizeRiskRating($rating),
                'dr_status' => 'Open',
            ]);

            // Refresh relation cache on the model instance
            $client->unsetRelation('latestComprehensiveRiskAssessment');
            $client->load('latestComprehensiveRiskAssessment');
        } catch (\Exception $e) {
            Log::error('Auto-heal CRA creation failed', [
                'client_id' => $client->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function determineDCSRiskAppetite($riskRating): string
    {
        $rating = strtolower($riskRating);
        if (str_contains($rating, 'high') || str_contains($rating, 'critical')) {
            return 'Conservative';
        } elseif (str_contains($rating, 'medium')) {
            return 'Moderate';
        }
        return 'Aggressive';
    }

    private function getImpactFromRating($riskRating): string
    {
        $rating = strtolower($riskRating);
        if (str_contains($rating, 'high') || str_contains($rating, 'critical')) {
            return 'High';
        } elseif (str_contains($rating, 'medium')) {
            return 'Medium';
        }
        return 'Low';
    }

    private function getLikelihoodFromRating($riskRating): string
    {
        $rating = strtolower($riskRating);
        if (str_contains($rating, 'high') || str_contains($rating, 'critical')) {
            return 'High';
        } elseif (str_contains($rating, 'medium')) {
            return 'Medium';
        }
        return 'Low';
    }

    private function normalizeRiskRating($riskRating): string
    {
        $rating = strtolower($riskRating);
        if (str_contains($rating, 'high')) {
            return 'High';
        } elseif (str_contains($rating, 'medium')) {
            return 'Medium';
        }
        return 'Low';
    }

    /**
     * Update existing comprehensive risk assessments with specific risk names
     */
    public function updateRiskNames()
    {
        $riskNameMap = [
            'CR-01' => 'PIP / PEP client',
            'CR-02' => 'Corporate client',
            'CR-03' => 'Individual client',
            'SR-01' => 'High-risk services',
            'SR-02' => 'Complex services',
            'SR-03' => 'Standard services',
            'PR-01' => 'Unrecorded face-to-face transactions',
            'PR-02' => 'Cash Payments',
            'PR-03' => 'EFTS/SWIFT',
            'PR-04' => 'POS Payments',
            'DR-01' => 'Remote service risks',
            'DR-02' => 'Face-to-face service risks',
        ];

        $assessments = \App\Models\ComprehensiveRiskAssessment::all();
        
        foreach ($assessments as $assessment) {
            $updated = false;
            
            if (isset($riskNameMap[$assessment->cr_risk_id])) {
                $assessment->cr_risk_name = $riskNameMap[$assessment->cr_risk_id];
                $updated = true;
            }
            
            if (isset($riskNameMap[$assessment->sr_risk_id])) {
                $assessment->sr_risk_name = $riskNameMap[$assessment->sr_risk_id];
                $updated = true;
            }
            
            if (isset($riskNameMap[$assessment->pr_risk_id])) {
                $assessment->pr_risk_name = $riskNameMap[$assessment->pr_risk_id];
                $updated = true;
            }
            
            if (isset($riskNameMap[$assessment->dr_risk_id])) {
                $assessment->dr_risk_name = $riskNameMap[$assessment->dr_risk_id];
                $updated = true;
            }
            
            if ($updated) {
                $assessment->save();
            }
        }
        
        return response()->json(['message' => 'Risk names updated successfully']);
    }

    /**
     * Search for clients by name - API endpoint for client lookup
     */
    public function searchClients(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json(['clients' => []]);
        }

        // Search for clients by name with assessment count and last assessment info
        $clients = DB::select("
            SELECT 
                c.id, c.name, c.email, c.company,
                COUNT(c2.id) as assessment_count,
                (
                    SELECT JSON_OBJECT(
                        'id', c3.id,
                        'overall_risk_rating', c3.overall_risk_rating,
                        'overall_risk_points', c3.overall_risk_points,
                        'assessment_status', c3.assessment_status,
                        'client_acceptance', c3.client_acceptance,
                        'ongoing_monitoring', c3.ongoing_monitoring,
                        'created_at', c3.created_at
                    )
                    FROM clients c3 
                    WHERE c3.name = c.name 
                    AND c3.assessment_status = 'approved' 
                    AND c3.deleted_at IS NULL
                    ORDER BY c3.created_at DESC 
                    LIMIT 1
                ) as last_assessment
            FROM clients c
            LEFT JOIN clients c2 ON c2.name = c.name 
                AND c2.assessment_status = 'approved' 
                AND c2.deleted_at IS NULL
            WHERE c.name LIKE ? 
            AND c.assessment_status = 'approved' 
            AND c.deleted_at IS NULL
            GROUP BY c.id, c.name, c.email, c.company
            ORDER BY c.name
            LIMIT 10
        ", ["%{$query}%"]);

        // Convert stdClass objects to arrays and parse JSON
        $clients = collect($clients)->map(function($client) {
            $clientArray = (array) $client;
            if ($clientArray['last_assessment']) {
                $clientArray['last_assessment'] = json_decode($clientArray['last_assessment'], true);
            }
            return $clientArray;
        });

        return response()->json(['clients' => $clients]);
    }

    /**
     * Get client assessment history - API endpoint
     * Returns complete assessment history including comprehensive risk details
     */
    public function getClientHistory(Client $client)
    {
        // Get all assessments for this client name with comprehensive risk assessment data
        $assessments = DB::select("
            SELECT 
                c.id, c.name, c.overall_risk_rating, c.overall_risk_points,
                c.assessment_status, c.client_acceptance, c.ongoing_monitoring,
                c.dcs_risk_appetite, c.dcs_comments, c.created_at, c.updated_at,
                cra.cr_risk_id, cra.cr_risk_name, cra.cr_impact, cra.cr_likelihood, cra.cr_risk_rating, cra.cr_points,
                cra.sr_risk_id, cra.sr_risk_name, cra.sr_impact, cra.sr_likelihood, cra.sr_risk_rating, cra.sr_points,
                cra.pr_risk_id, cra.pr_risk_name, cra.pr_impact, cra.pr_likelihood, cra.pr_risk_rating, cra.pr_points,
                cra.dr_risk_id, cra.dr_risk_name, cra.dr_impact, cra.dr_likelihood, cra.dr_risk_rating, cra.dr_points,
                u.name as approved_by_name
            FROM clients c
            LEFT JOIN risks r ON r.client_id = c.id AND r.deleted_at IS NULL
            LEFT JOIN comprehensive_risk_assessments cra ON cra.risk_id = r.id
            LEFT JOIN users u ON c.approved_by = u.id
            WHERE c.name = ? 
            AND c.assessment_status = 'approved' 
            AND c.deleted_at IS NULL
            ORDER BY c.created_at DESC
        ", [$client->name]);

        return response()->json([
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'company' => $client->company
            ],
            'assessments' => $assessments
        ]);
    }
}
