@extends('layouts.sidebar')

@section('title', 'Rejected Client Assessments')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 7, 45, 0.2);
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        color: white;
    }
    
    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        margin: 0.25rem 0 0 0;
        font-size: 0.9rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 4px solid;
        text-align: center;
    }
    
    .stat-card.pending { border-left-color: var(--logo-warning); }
    .stat-card.approved { border-left-color: var(--logo-success); }
    .stat-card.rejected { border-left-color: var(--logo-danger); }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    
    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0.5rem 0 0 0;
        opacity: 0.8;
    }
    
    .assessments-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .assessments-table-card .card-header {
        background: var(--logo-dark-blue-primary);
        color: white;
        padding: 1rem 1.5rem;
        border: none;
    }
    
    .assessments-table-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .table {
        margin: 0;
    }
    
    .table th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 600;
        color: #475569;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.5rem;
    }
    
    .table td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .table tbody tr:hover {
        background: #f8fafc;
    }
    
    .client-name {
        font-weight: 600;
        color: var(--logo-dark-blue-primary);
        margin-bottom: 0.25rem;
    }
    
    .client-details {
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1.4;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
    }
    
    .risk-score {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--logo-dark-blue-primary);
        background: rgba(0, 7, 45, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
    }
    
    .rejection-reason {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #64748b;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    .empty-state h5 {
        color: #475569;
        margin-bottom: 0.5rem;
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Rejected Client Assessments</h1>
                <p class="page-subtitle">Review previously rejected client risk assessments</p>
            </div>
            <div>
                <a href="{{ route('client-assessments.approval.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i>Back to Pending
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-number text-warning">{{ \App\Models\Client::where('assessment_status', 'pending')->count() }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card approved">
            <div class="stat-number text-success">{{ \App\Models\Client::where('assessment_status', 'approved')->count() }}</div>
            <div class="stat-label">Approved</div>
        </div>
        <div class="stat-card rejected">
            <div class="stat-number text-danger">{{ $rejectedAssessments->count() }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    <!-- Rejected Assessments Table -->
    <div class="assessments-table-card">
        <div class="card-header">
            <h5><i class="fas fa-times-circle me-2"></i>Rejected Client Assessments</h5>
        </div>
        <div class="card-body p-0">
            @if($rejectedAssessments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Client Details</th>
                                <th>Risk Assessment</th>
                                <th>Created By</th>
                                <th>Rejected By</th>
                                <th>Rejection Reason</th>
                                <th>Rejected At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectedAssessments as $client)
                            <tr>
                                <td>
                                    <div class="client-name">{{ $client->name }}</div>
                                    <div class="client-details">
                                        @if($client->email)
                                            <i class="fas fa-envelope me-1"></i>{{ $client->email }}
                                        @endif
                                        @if($client->industry)
                                            <br><i class="fas fa-building me-1"></i>{{ $client->industry }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="risk-score">{{ $client->overall_risk_points ?? 0 }} pts</div>
                                    <div class="client-details">{{ $client->overall_risk_rating ?? 'Not assessed' }}</div>
                                    <div class="client-details">{{ $client->client_acceptance ?? 'Pending' }}</div>
                                    @if($client->comprehensiveRiskAssessment)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Categories:</strong>
                                                @if($client->comprehensiveRiskAssessment->sr_risk_id)
                                                    <span class="badge bg-info me-1">SR</span>
                                                @endif
                                                @if($client->comprehensiveRiskAssessment->cr_risk_id)
                                                    <span class="badge bg-warning me-1">CR</span>
                                                @endif
                                                @if($client->comprehensiveRiskAssessment->pr_risk_id)
                                                    <span class="badge bg-danger me-1">PR</span>
                                                @endif
                                                @if($client->comprehensiveRiskAssessment->dr_risk_id)
                                                    <span class="badge bg-secondary me-1">DR</span>
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($client->creator)
                                        <span class="fw-medium">{{ $client->creator->name }}</span>
                                        <br><small class="text-muted">{{ $client->creator->role_display_name }}</small>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    @if($client->approver)
                                        <span class="fw-medium">{{ $client->approver->name }}</span>
                                        <br><small class="text-muted">{{ $client->approver->role_display_name }}</small>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="rejection-reason" title="{{ $client->rejection_reason }}">
                                        {{ Str::limit($client->rejection_reason, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $client->approved_at ? $client->approved_at->format('M d, Y') : 'Unknown' }}</span>
                                    <br><small class="text-muted">{{ $client->approved_at ? $client->approved_at->diffForHumans() : '' }}</small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('client-assessments.approval.show', $client) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <h5>No Rejected Assessments</h5>
                    <p>No client assessments have been rejected yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
