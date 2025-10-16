@extends('layouts.sidebar')

@section('title', 'Client Assessment Details')

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
    
    .details-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    
    .details-card .card-header {
        background: var(--logo-dark-blue-primary);
        color: white;
        padding: 1rem 1.5rem;
        border: none;
    }
    
    .details-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        padding: 1.5rem;
    }
    
    .info-section h6 {
        color: var(--logo-dark-blue-primary);
        font-weight: 600;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 500;
        color: #475569;
    }
    
    .info-value {
        color: #1e293b;
        font-weight: 500;
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
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        padding: 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }
    
    .risks-table {
        margin-top: 1rem;
    }
    
    .risks-table .table th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 600;
        color: #475569;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
    }
    
    .risks-table .table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    
    /* Modal fixes to prevent blocking */
    .modal-backdrop {
        z-index: 1040 !important;
        pointer-events: none; /* Make it non-blocking */
    }
    .modal {
        z-index: 1050 !important;
        pointer-events: auto;
    }
    .modal-dialog {
        z-index: 1060 !important;
        pointer-events: auto;
    }
    .modal-content {
        position: relative;
        z-index: 1070 !important;
        pointer-events: auto !important;
    }
    .modal-body, .modal-footer, .modal-header {
        z-index: 1070 !important;
        pointer-events: auto !important;
    }
    .modal-body input, .modal-body textarea, .modal-body select, .modal-body button {
        pointer-events: auto !important;
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Client Assessment Details</h1>
                <p class="page-subtitle">{{ $client->name }} - Risk Assessment Review</p>
            </div>
            <div>
                <a href="{{ route('client-assessments.approval.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i>Back to Approvals
                </a>
            </div>
        </div>
    </div>

    <!-- Client Assessment Details -->
    <div class="details-card">
        <div class="card-header">
            <h5><i class="fas fa-user me-2"></i>Client Information</h5>
        </div>
        <div class="info-grid">
            <div class="info-section">
                <h6>Basic Information</h6>
                <div class="info-item">
                    <span class="info-label">Client Name:</span>
                    <span class="info-value">{{ $client->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $client->email ?? 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Industry:</span>
                    <span class="info-value">{{ $client->industry ?? 'Not specified' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="badge bg-{{ $client->assessment_status_color }}">{{ ucfirst($client->assessment_status) }}</span>
                    </span>
                </div>
            </div>
            
            <div class="info-section">
                <h6>KYC Details</h6>
                <div class="info-item">
                    <span class="info-label">Client Type:</span>
                    <span class="info-value">{{ $client->kyc->client_type ?? 'N/A' }}</span>
                </div>
                
                @if(($client->kyc->client_type ?? '') === 'Individual')
                    <!-- Individual-specific fields -->
                    <div class="info-item">
                        <span class="info-label">Gender:</span>
                        <span class="info-value">{{ $client->kyc->gender ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nationality:</span>
                        <span class="info-value">{{ $client->kyc->nationality ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Is Minor:</span>
                        <span class="info-value">{{ isset($client->kyc->is_minor) ? ($client->kyc->is_minor ? 'Yes' : 'No') : 'N/A' }}</span>
                    </div>
                    
                    @if(($client->kyc->nationality ?? '') === 'Namibian' && !($client->kyc->is_minor ?? false))
                        <div class="info-item">
                            <span class="info-label">ID Number:</span>
                            <span class="info-value">{{ $client->kyc->id_number ?? 'N/A' }}</span>
                        </div>
                    @endif
                    
                    @if(($client->kyc->nationality ?? '') === 'Foreign' && !($client->kyc->is_minor ?? false))
                        <div class="info-item">
                            <span class="info-label">Passport Number:</span>
                            <span class="info-value">{{ $client->kyc->passport_number ?? 'N/A' }}</span>
                        </div>
                    @endif
                    
                    <div class="info-item">
                        <span class="info-label">Source of Income:</span>
                        <span class="info-value">{{ $client->kyc->income_source ?? 'N/A' }}</span>
                    </div>
                @elseif(($client->kyc->client_type ?? '') === 'Juristic')
                    <!-- Juristic-specific fields -->
                    <div class="info-item">
                        <span class="info-label">Registration Number:</span>
                        <span class="info-value">{{ $client->kyc->registration_number ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Entity Type:</span>
                        <span class="info-value">{{ $client->kyc->entity_type ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Trading Address:</span>
                        <span class="info-value">{{ $client->kyc->trading_address ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Source of Income:</span>
                        <span class="info-value">{{ $client->kyc->income_source ?? 'N/A' }}</span>
                    </div>
                @endif
            </div>

            <div class="info-section">
                <h6>Supporting Documents</h6>
                @if(($client->kyc->client_type ?? '') === 'Individual')
                    <!-- Individual-specific documents -->
                    @if(($client->kyc->nationality ?? '') === 'Namibian' && !($client->kyc->is_minor ?? false))
                        <div class="info-item">
                            <span class="info-label">ID Document:</span>
                            <span class="info-value">
                                @php($idDoc = ($client->documents->firstWhere('document_type','id_document')->file_path ?? null) ?? ($client->kyc->id_document_path ?? null))
                                @if(!empty($idDoc))
                                    <a href="{{ Storage::disk('public')->url($idDoc) }}" target="_blank">View</a>
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    @endif
                    
                    @if(($client->kyc->is_minor ?? false))
                        <div class="info-item">
                            <span class="info-label">Birth Certificate:</span>
                            <span class="info-value">
                                @php($birthDoc = ($client->documents->firstWhere('document_type','birth_certificate')->file_path ?? null) ?? ($client->kyc->birth_certificate_path ?? null))
                                @if(!empty($birthDoc))
                                    <a href="{{ Storage::disk('public')->url($birthDoc) }}" target="_blank">View</a>
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    @endif
                    
                    @if(($client->kyc->nationality ?? '') === 'Foreign' && !($client->kyc->is_minor ?? false))
                        <div class="info-item">
                            <span class="info-label">Passport Document:</span>
                            <span class="info-value">
                                @php($passportDoc = ($client->documents->firstWhere('document_type','passport_document')->file_path ?? null) ?? ($client->kyc->passport_document_path ?? null))
                                @if(!empty($passportDoc))
                                    <a href="{{ Storage::disk('public')->url($passportDoc) }}" target="_blank">View</a>
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    @endif
                @endif
                <div class="info-item">
                    <span class="info-label">{{ $client->client_type === 'Juristic' ? 'Trading Address Residence:' : 'Proof of Residence:' }}</span>
                    <span class="info-value">
                        @php($porDoc = ($client->documents->firstWhere('document_type','proof_of_residence')->file_path ?? null) ?? ($client->kyc->proof_of_residence_path ?? null))
                        @if(!empty($porDoc))
                            <a href="{{ Storage::disk('public')->url($porDoc) }}" target="_blank">View</a>
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">KYC Form:</span>
                    <span class="info-value">
                        @php($kycForm = ($client->documents->firstWhere('document_type','kyc_form')->file_path ?? null) ?? ($client->kyc->kyc_form_path ?? null))
                        @if(!empty($kycForm))
                            <a href="{{ Storage::disk('public')->url($kycForm) }}" target="_blank">View</a>
                        @else
                            N/A
                        @endif
                    </span>
                </div>
            </div>
            <div class="info-section">
                <h6>Assessment Details</h6>
                <div class="info-item">
                    <span class="info-label">Assessment Date:</span>
                    <span class="info-value">{{ $client->client_screening_date ? $client->client_screening_date->format('M d, Y') : 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Screening Status:</span>
                    <span class="info-value">{{ $client->client_screening_result ?? 'Not completed' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Risk Score:</span>
                    <span class="info-value">
                        <span class="risk-score">{{ $client->overall_risk_points ?? 0 }} points</span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Risk Rating:</span>
                    <span class="info-value">{{ $client->overall_risk_rating ?? 'Not assessed' }}</span>
                </div>
            </div>
            
            <div class="info-section">
                <h6>Assessment Results</h6>
                <div class="info-item">
                    <span class="info-label">Client Acceptance:</span>
                    <span class="info-value">{{ $client->client_acceptance ?? 'Pending' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Monitoring Frequency:</span>
                    <span class="info-value">{{ $client->ongoing_monitoring ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">DCS Risk Appetite:</span>
                    <span class="info-value">{{ $client->dcs_risk_appetite ?? 'Not specified' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">DCS Comments:</span>
                    <span class="info-value">{{ $client->dcs_comments ?? 'No comments' }}</span>
                </div>
            </div>
            
            <div class="info-section">
                <h6>Audit Information</h6>
                <div class="info-item">
                    <span class="info-label">Created By:</span>
                    <span class="info-value">{{ $client->creator->name ?? 'Unknown' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Created At:</span>
                    <span class="info-value">{{ $client->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value">{{ $client->updated_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Assessment ID:</span>
                    <span class="info-value">#{{ $client->id }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Category Breakdown -->
    @if($client->comprehensiveRiskAssessment)
    <div class="details-card">
        <div class="card-header">
            <h5><i class="fas fa-list-check me-2"></i>Risk Category Breakdown - Staff Selections</h5>
        </div>
        <div class="alert alert-warning mx-3 mt-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Manager Review Required:</strong> Please verify that the selected risk categories (SR, CR, PR, DR) are appropriate and legitimate for this client. Check for any potential bias or malicious selections.
        </div>
        <div class="info-grid">
            @if($client->comprehensiveRiskAssessment->sr_risk_id)
            <div class="info-section">
                <h6><span class="badge bg-info me-2">SR</span>Service Risk</h6>
                <div class="info-item">
                    <span class="info-label">Risk ID:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->sr_risk_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Risk Name:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->sr_risk_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Points:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->sr_points ?? 0 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rating:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->sr_risk_rating ?? 'Not assessed' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Impact:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->sr_impact ?? 'Not specified' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Likelihood:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->sr_likelihood ?? 'Not specified' }}</span>
                </div>
            </div>
            @endif

            @if($client->comprehensiveRiskAssessment->cr_risk_id)
            <div class="info-section">
                <h6><span class="badge bg-warning me-2">CR</span>Client Risk</h6>
                <div class="info-item">
                    <span class="info-label">Risk ID:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->cr_risk_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Risk Name:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->cr_risk_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Points:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->cr_points ?? 0 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rating:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->cr_risk_rating ?? 'Not assessed' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Impact:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->cr_impact ?? 'Not specified' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Likelihood:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->cr_likelihood ?? 'Not specified' }}</span>
                </div>
            </div>
            @endif

            @if($client->comprehensiveRiskAssessment->pr_risk_id)
            <div class="info-section">
                <h6><span class="badge bg-danger me-2">PR</span>Payment Risk</h6>
                <div class="info-item">
                    <span class="info-label">Risk ID:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->pr_risk_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Risk Name:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->pr_risk_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Points:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->pr_points ?? 0 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rating:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->pr_risk_rating ?? 'Not assessed' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Impact:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->pr_impact ?? 'Not specified' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Likelihood:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->pr_likelihood ?? 'Not specified' }}</span>
                </div>
            </div>
            @endif

            @if($client->comprehensiveRiskAssessment->dr_risk_id)
            <div class="info-section">
                <h6><span class="badge bg-secondary me-2">DR</span>Delivery Risk</h6>
                <div class="info-item">
                    <span class="info-label">Risk ID:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->dr_risk_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Risk Name:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->dr_risk_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Points:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->dr_points ?? 0 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rating:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->dr_risk_rating ?? 'Not assessed' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Impact:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->dr_impact ?? 'Not specified' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Likelihood:</span>
                    <span class="info-value">{{ $client->comprehensiveRiskAssessment->dr_likelihood ?? 'Not specified' }}</span>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Points Summary -->
        <div class="mx-3 mb-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Points Breakdown</h6>
                            <div class="row text-center">
                                @if($client->comprehensiveRiskAssessment->sr_risk_id)
                                <div class="col-3">
                                    <div class="fw-bold text-info">{{ $client->comprehensiveRiskAssessment->sr_points ?? 0 }}</div>
                                    <small class="text-muted">SR</small>
                                </div>
                                @endif
                                @if($client->comprehensiveRiskAssessment->cr_risk_id)
                                <div class="col-3">
                                    <div class="fw-bold text-warning">{{ $client->comprehensiveRiskAssessment->cr_points ?? 0 }}</div>
                                    <small class="text-muted">CR</small>
                                </div>
                                @endif
                                @if($client->comprehensiveRiskAssessment->pr_risk_id)
                                <div class="col-3">
                                    <div class="fw-bold text-danger">{{ $client->comprehensiveRiskAssessment->pr_points ?? 0 }}</div>
                                    <small class="text-muted">PR</small>
                                </div>
                                @endif
                                @if($client->comprehensiveRiskAssessment->dr_risk_id)
                                <div class="col-3">
                                    <div class="fw-bold text-secondary">{{ $client->comprehensiveRiskAssessment->dr_points ?? 0 }}</div>
                                    <small class="text-muted">DR</small>
                                </div>
                                @endif
                            </div>
                            <hr>
                            <div class="text-center">
                                <strong>Total: {{ $client->comprehensiveRiskAssessment->total_points ?? 0 }} points</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Risk Assessment Summary</h6>
                            <p class="mb-1"><strong>Overall Rating:</strong> {{ $client->comprehensiveRiskAssessment->overall_risk_rating ?? 'Not assessed' }}</p>
                            <p class="mb-1"><strong>Client Acceptance:</strong> {{ $client->comprehensiveRiskAssessment->client_acceptance ?? 'Pending' }}</p>
                            <p class="mb-0"><strong>Monitoring:</strong> {{ $client->comprehensiveRiskAssessment->ongoing_monitoring ?? 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Associated Risks -->
    @if($client->risks && $client->risks->count() > 0)
    <div class="details-card">
        <div class="card-header">
            <h5><i class="fas fa-exclamation-triangle me-2"></i>Associated Risks ({{ $client->risks->count() }})</h5>
        </div>
        <div class="risks-table">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Risk Title</th>
                            <th>Category</th>
                            <th>Rating</th>
                            <th>Impact</th>
                            <th>Likelihood</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($client->risks as $risk)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $risk->title }}</div>
                                <small class="text-muted">{{ Str::limit($risk->description, 50) }}</small>
                            </td>
                            <td>{{ $risk->risk_category ?? 'Uncategorized' }}</td>
                            <td>
                                <span class="badge bg-{{ $risk->risk_rating_color }}">{{ $risk->risk_rating }}</span>
                            </td>
                            <td>{{ $risk->impact ?? 'Not specified' }}</td>
                            <td>{{ $risk->likelihood ?? 'Not specified' }}</td>
                            <td>
                                <span class="badge bg-{{ $risk->status_color }}">{{ $risk->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn btn-success btn-lg" onclick="approveAssessment({{ $client->id }})">
            <i class="fas fa-check me-2"></i>Approve Assessment
        </button>
        <button class="btn btn-danger btn-lg" onclick="rejectAssessment({{ $client->id }})">
            <i class="fas fa-times me-2"></i>Reject Assessment
        </button>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Client Assessment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="approval_notes" class="form-label">Approval Notes (Optional)</label>
                        <textarea class="form-control" id="approval_notes" name="approval_notes" rows="3" placeholder="Add any notes about this approval..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Assessment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Client Assessment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Assessment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveAssessment(clientId) {
    // console.log('Approve Assessment clicked');
    
    // Clean up any existing modals first
    cleanupModals();
    
    const modalElement = document.getElementById('approvalModal');
    document.getElementById('approvalForm').action = `/client-assessments/approval/${clientId}/approve`;
    
    // Dispose any existing instance
    const existingInstance = bootstrap.Modal.getInstance(modalElement);
    if (existingInstance) {
        existingInstance.dispose();
    }
    
    // Create new modal with no backdrop
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: false, // No backdrop to prevent blocking
        keyboard: true,
        focus: true
    });
    
    // Show the modal
    modal.show();
    
    // Manually add a non-blocking backdrop
    setTimeout(() => {
        let backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'custom-backdrop-approval';
        backdrop.style.zIndex = '1040';
        backdrop.style.pointerEvents = 'none'; // Make it non-blocking
        document.body.appendChild(backdrop);
        document.body.classList.add('modal-open');
        
        // Focus on textarea
        const textarea = document.getElementById('approval_notes');
        if (textarea) {
            textarea.focus();
        }
    }, 100);
    
    // Add cleanup event listener
    modalElement.addEventListener('hidden.bs.modal', function() {
        const customBackdrop = document.getElementById('custom-backdrop-approval');
        if (customBackdrop) {
            customBackdrop.remove();
        }
        cleanupModals();
    }, { once: true });
}

function rejectAssessment(clientId) {
    // console.log('Reject Assessment clicked');
    
    // Clean up any existing modals first
    cleanupModals();
    
    const modalElement = document.getElementById('rejectionModal');
    document.getElementById('rejectionForm').action = `/client-assessments/approval/${clientId}/reject`;
    
    // Dispose any existing instance
    const existingInstance = bootstrap.Modal.getInstance(modalElement);
    if (existingInstance) {
        existingInstance.dispose();
    }
    
    // Create new modal with no backdrop
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: false, // No backdrop to prevent blocking
        keyboard: true,
        focus: true
    });
    
    // Show the modal
    modal.show();
    
    // Manually add a non-blocking backdrop
    setTimeout(() => {
        let backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'custom-backdrop-rejection';
        backdrop.style.zIndex = '1040';
        backdrop.style.pointerEvents = 'none'; // Make it non-blocking
        document.body.appendChild(backdrop);
        document.body.classList.add('modal-open');
        
        // Focus on textarea
        const textarea = document.getElementById('rejection_reason');
        if (textarea) {
            textarea.focus();
        }
    }, 100);
    
    // Add cleanup event listener
    modalElement.addEventListener('hidden.bs.modal', function() {
        const customBackdrop = document.getElementById('custom-backdrop-rejection');
        if (customBackdrop) {
            customBackdrop.remove();
        }
        cleanupModals();
    }, { once: true });
}

function cleanupModals() {
    // console.log('Cleaning up modals...');
    const backdrops = document.querySelectorAll('.modal-backdrop, .modal-backdrop.show, #custom-backdrop-approval, #custom-backdrop-rejection');
    backdrops.forEach(backdrop => {
        // console.log('Removing backdrop:', backdrop);
        backdrop.remove();
    });
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.body.style.overflowY = '';
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            // console.log('Disposing modal instance:', modal.id);
            modalInstance.dispose();
        }
    });
    document.body.classList.remove('modal-open');
    // console.log('Modal cleanup completed');
}
</script>
@endsection
