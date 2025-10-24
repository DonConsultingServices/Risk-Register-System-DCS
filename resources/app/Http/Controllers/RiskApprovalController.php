<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Risk;
use Illuminate\Support\Facades\Log;
use Exception;

class RiskApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:manager,admin');
    }

    /**
     * Display risks for approval with optional status filtering - OPTIMIZED VERSION
     */
    public function index(Request $request)
    {
        try {
            $status = $request->get('status', 'pending');
            
            // Validate status parameter
            if (!in_array($status, ['pending', 'approved', 'rejected'])) {
                $status = 'pending';
            }
            
            // Get clients based on assessment status instead of individual risks
            $clients = \App\Models\Client::with(['creator', 'risks'])
                ->where('assessment_status', $status)
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // For backward compatibility, use clients as risks
            $risks = $clients;
            
            // Get counts based on client assessment status (not risk approval status)
            $stats = (object)[
                'pending' => \App\Models\Client::where('assessment_status', 'pending')->whereNull('deleted_at')->count(),
                'approved' => \App\Models\Client::where('assessment_status', 'approved')->whereNull('deleted_at')->count(),
                'rejected' => \App\Models\Client::where('assessment_status', 'rejected')->whereNull('deleted_at')->count()
            ];

            // For backward compatibility, keep pendingRisks variable
            $pendingRisks = $risks;

            return view('risks.approval.index', compact('pendingRisks', 'risks', 'status', 'stats'));
        } catch (Exception $e) {
            Log::error('Risk approval index error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'status' => $status ?? 'unknown'
            ]);
            
            // Return a simple error view instead of redirecting
            return view('risks.approval.index', [
                'pendingRisks' => collect([]),
                'risks' => collect([]),
                'status' => 'pending',
                'stats' => (object)['pending' => 0, 'approved' => 0, 'rejected' => 0],
                'error' => 'Unable to load risk approval data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show a specific risk for approval
     */
    public function show(Risk $risk)
    {
        if (!$risk->isPending()) {
            return redirect()->route('risks.approval.index')
                ->with('error', 'This risk is not pending approval.');
        }

        $risk->load(['client', 'creator', 'assignedUser', 'category']);

        return view('risks.approval.show', compact('risk'));
    }

    /**
     * Approve a risk
     */
    public function approve(Request $request, $clientId)
    {
        $client = \App\Models\Client::findOrFail($clientId);
        
        // Check if already approved
        if ($client->assessment_status === 'approved') {
            return redirect()->route('risks.approval.index', ['status' => 'approved'])
                ->with('info', 'This client has already been approved.');
        }

        $request->validate([
            'approval_notes' => 'nullable|string|max:1000'
        ]);

        $client->update([
            'assessment_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
            'status' => 'Active'
        ]);

        Log::info('Client assessment approved', [
            'client_id' => $client->id,
            'client_name' => $client->name,
            'approved_by' => auth()->id(),
            'approver_name' => auth()->user()->name
        ]);

        return redirect()->route('risks.approval.index', ['status' => 'approved'])
            ->with('success', 'Client assessment has been approved successfully.');
    }

    /**
     * Reject a risk
     */
    public function reject(Request $request, $clientId)
    {
        $client = \App\Models\Client::findOrFail($clientId);
        
        // Check if already rejected
        if ($client->assessment_status === 'rejected') {
            return redirect()->route('risks.approval.index', ['status' => 'rejected'])
                ->with('info', 'This client has already been rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $client->update([
            'assessment_status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'status' => 'Inactive'
        ]);

        Log::info('Client assessment rejected', [
            'client_id' => $client->id,
            'client_name' => $client->name,
            'rejected_by' => auth()->id(),
            'rejector_name' => auth()->user()->name,
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('risks.approval.index', ['status' => 'rejected'])
            ->with('success', 'Client assessment has been rejected.');
    }

    /**
     * Bulk approve risks
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'risk_ids' => 'required|array',
            'risk_ids.*' => 'exists:risks,id',
            'approval_notes' => 'nullable|string|max:1000'
        ]);

        $risks = Risk::whereIn('id', $request->risk_ids)
            ->where('approval_status', 'pending')
            ->get();

        $approvedCount = 0;
        foreach ($risks as $risk) {
            $risk->update([
                'approval_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'approval_notes' => $request->approval_notes
            ]);
            $approvedCount++;
        }

        Log::info('Bulk risk approval', [
            'approved_count' => $approvedCount,
            'approved_by' => auth()->id(),
            'approver_name' => auth()->user()->name
        ]);

        return redirect()->route('risks.approval.index')
            ->with('success', "Successfully approved {$approvedCount} risks.");
    }

    /**
     * Get approval statistics
     */
    public function stats()
    {
        $stats = [
            'pending' => \App\Models\Client::where('assessment_status', 'pending')->whereNull('deleted_at')->count(),
            'approved' => \App\Models\Client::where('assessment_status', 'approved')->whereNull('deleted_at')->count(),
            'rejected' => \App\Models\Client::where('assessment_status', 'rejected')->whereNull('deleted_at')->count(),
            'total' => \App\Models\Client::whereNull('deleted_at')->count()
        ];

        return response()->json($stats);
    }
}