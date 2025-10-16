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
            
            // Use Eloquent models with relationships instead of raw SQL
            // Added distinct() to prevent duplicate records
            $risks = Risk::with(['client', 'creator', 'assignedUser', 'category'])
                ->where('approval_status', $status)
                ->distinct()
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Remove any duplicates based on ID (extra safety)
            $risks = $risks->unique('id');
            
            // Get counts for all statuses using Eloquent
            $stats = (object)[
                'pending' => Risk::where('approval_status', 'pending')->count(),
                'approved' => Risk::where('approval_status', 'approved')->count(),
                'rejected' => Risk::where('approval_status', 'rejected')->count()
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
    public function approve(Request $request, Risk $risk)
    {
        // Check if already approved
        if ($risk->approval_status === 'approved') {
            return redirect()->route('risks.approval.index', ['status' => 'approved'])
                ->with('info', 'This risk has already been approved.');
        }
        
        if (!$risk->canBeApproved()) {
            return redirect()->route('risks.approval.index')
                ->with('error', 'This risk cannot be approved.');
        }

        $request->validate([
            'approval_notes' => 'nullable|string|max:1000'
        ]);

        $risk->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes
        ]);

        Log::info('Risk approved', [
            'risk_id' => $risk->id,
            'approved_by' => auth()->id(),
            'approver_name' => auth()->user()->name
        ]);

        return redirect()->route('risks.approval.index', ['status' => 'approved'])
            ->with('success', 'Risk has been approved successfully.');
    }

    /**
     * Reject a risk
     */
    public function reject(Request $request, Risk $risk)
    {
        // Check if already rejected
        if ($risk->approval_status === 'rejected') {
            return redirect()->route('risks.approval.index', ['status' => 'rejected'])
                ->with('info', 'This risk has already been rejected.');
        }
        
        if (!$risk->canBeRejected()) {
            return redirect()->route('risks.approval.index')
                ->with('error', 'This risk cannot be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $risk->update([
            'approval_status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);

        Log::info('Risk rejected', [
            'risk_id' => $risk->id,
            'rejected_by' => auth()->id(),
            'rejector_name' => auth()->user()->name,
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('risks.approval.index', ['status' => 'rejected'])
            ->with('success', 'Risk has been rejected.');
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
            'pending' => Risk::where('approval_status', 'pending')->whereNull('deleted_at')->count(),
            'approved' => Risk::where('approval_status', 'approved')->whereNull('deleted_at')->count(),
            'rejected' => Risk::where('approval_status', 'rejected')->whereNull('deleted_at')->count(),
            'total' => Risk::whereNull('deleted_at')->count()
        ];

        return response()->json($stats);
    }
}