@extends('layouts.sidebar')

@section('title', 'Risk Approvals')

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
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        text-decoration: none;
        color: inherit;
    }
    
    .stat-card.active {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
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
    
    .risks-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .risks-table-card .card-header {
        background: var(--logo-dark-blue-primary);
        color: white;
        padding: 1rem 1.5rem;
        border: none;
    }
    
    .risks-table-card .card-header h5 {
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
    
    .risk-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--logo-dark-blue-primary);
        background: rgba(0, 7, 45, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
    }
    
    .risk-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    
    .risk-description {
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
    
    .client-link {
        color: var(--logo-dark-blue-primary);
        text-decoration: none;
        font-weight: 500;
    }
    
    .client-link:hover {
        color: var(--logo-dark-blue-hover);
        text-decoration: underline;
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
    
    .bulk-actions {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .bulk-actions.hidden {
        display: none;
    }
    
    /* Mobile-First Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem;
        }
        .page-header {
            margin: 0 -0.5rem 1.5rem -0.5rem;
            border-radius: 0;
            padding: 1rem;
        }
        .page-title {
            font-size: 1.25rem;
        }
        .page-subtitle {
            font-size: 0.85rem;
        }
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin: 0 -0.5rem 1.5rem -0.5rem;
        }
        .stat-card {
            padding: 1rem;
            margin: 0 0.5rem;
        }
        .stat-number {
            font-size: 1.5rem;
        }
        .stat-label {
            font-size: 0.8rem;
        }
        .approvals-table-container {
            margin: 0 -0.5rem;
            border-radius: 0;
        }
        .table-responsive {
            font-size: 0.75rem;
            border-radius: 0;
        }
        .table {
            min-width: 1000px;
        }
        .table th,
        .table td {
            padding: 0.375rem 0.25rem;
            font-size: 0.7rem;
            white-space: nowrap;
        }
        .table th {
            font-size: 0.65rem;
            padding: 0.25rem 0.125rem;
        }
        .badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
        .btn-group .btn {
            padding: 0.25rem 0.375rem;
            font-size: 0.65rem;
        }
        .table-scroll-indicator {
            font-size: 0.7rem;
            padding: 0.375rem;
        }
    }
    
    @media (max-width: 576px) {
        .container-fluid {
            padding: 0.25rem;
        }
        .page-header {
            margin: 0 -0.25rem 1rem -0.25rem;
            padding: 0.75rem;
        }
        .page-title {
            font-size: 1.1rem;
        }
        .page-subtitle {
            font-size: 0.8rem;
        }
        .stats-grid {
            margin: 0 -0.25rem 1rem -0.25rem;
        }
        .stat-card {
            padding: 0.75rem;
            margin: 0 0.25rem;
        }
        .stat-number {
            font-size: 1.25rem;
        }
        .stat-label {
            font-size: 0.75rem;
        }
        .approvals-table-container {
            margin: 0 -0.25rem;
        }
        .table {
            min-width: 900px;
        }
        .table th,
        .table td {
            padding: 0.25rem 0.125rem;
            font-size: 0.65rem;
        }
        .table th {
            font-size: 0.6rem;
            padding: 0.2rem 0.1rem;
        }
        .badge {
            font-size: 0.55rem;
            padding: 0.15rem 0.3rem;
        }
        .btn-group .btn {
            padding: 0.2rem 0.3rem;
            font-size: 0.6rem;
        }
    }
    
    @media (max-width: 480px) {
        .page-header {
            padding: 0.5rem;
        }
        .page-title {
            font-size: 1rem;
        }
        .page-subtitle {
            font-size: 0.75rem;
        }
        .stat-card {
            padding: 0.5rem;
        }
        .stat-number {
            font-size: 1.1rem;
        }
        .stat-label {
            font-size: 0.7rem;
        }
        .table {
            min-width: 800px;
        }
        .table th,
        .table td {
            padding: 0.2rem 0.1rem;
            font-size: 0.6rem;
        }
        .table th {
            font-size: 0.55rem;
            padding: 0.15rem 0.05rem;
        }
        .badge {
            font-size: 0.5rem;
            padding: 0.1rem 0.25rem;
        }
    .btn-group .btn {
        padding: 0.15rem 0.25rem;
        font-size: 0.55rem;
    }
}

/* Modal fixes - Enhanced z-index management */
.modal {
    z-index: 1070 !important;
    pointer-events: auto;
}

.modal-backdrop {
    z-index: 1055 !important;
    pointer-events: none; /* Allow clicks to pass through to modal content */
}

.modal-backdrop.show {
    pointer-events: none;
}

.modal-dialog {
    z-index: 1071 !important;
    pointer-events: auto;
    position: relative;
}

.modal-content {
    position: relative;
    z-index: 1072 !important;
    pointer-events: auto;
    background: white;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Ensure form elements are clickable and properly visible */
.modal-body {
    position: relative;
    z-index: 1073 !important;
    pointer-events: auto;
    background: white !important;
    opacity: 1 !important;
}

.modal-body input,
.modal-body textarea,
.modal-body select {
    position: relative;
    z-index: 1074 !important;
    pointer-events: auto;
    background: white !important;
    opacity: 1 !important;
    color: #212529 !important;
    border: 1px solid #ced4da !important;
}

.modal-body textarea {
    background: white !important;
    opacity: 1 !important;
    color: #212529 !important;
    border: 1px solid #ced4da !important;
    box-shadow: none !important;
}

.modal-footer {
    position: relative;
    z-index: 1073 !important;
    pointer-events: auto;
    background: white !important;
    opacity: 1 !important;
    border-top: 1px solid #dee2e6 !important;
}

.modal-footer button {
    position: relative;
    z-index: 1074 !important;
    pointer-events: auto;
    opacity: 1 !important;
}

.modal-header {
    position: relative;
    z-index: 1073 !important;
    pointer-events: auto;
    background: white !important;
    opacity: 1 !important;
    border-bottom: 1px solid #dee2e6 !important;
}

.modal-header .btn-close {
    position: relative;
    z-index: 1074 !important;
    pointer-events: auto;
    opacity: 1 !important;
}

.modal-header .modal-title {
    color: #212529 !important;
    opacity: 1 !important;
}

/* Ensure modal doesn't block interactions */
.modal.show {
    display: block !important;
    pointer-events: auto;
}

/* Fix for body scroll lock issues */
body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
}

/* Ensure all interactive elements work */
.modal * {
    pointer-events: auto;
}

/* Force modal content to be interactive and properly visible */
#approvalModal .modal-content,
#rejectionModal .modal-content {
    pointer-events: auto !important;
    position: relative !important;
    z-index: 1072 !important;
    background: white !important;
    opacity: 1 !important;
    color: #212529 !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Ensure modal content is completely isolated from backdrop effects */
#approvalModal .modal-content *,
#rejectionModal .modal-content * {
    background: inherit !important;
    opacity: 1 !important;
    color: inherit !important;
}

/* Specific textarea styling to ensure it's bright and visible */
#approvalModal textarea,
#rejectionModal textarea {
    background: white !important;
    opacity: 1 !important;
    color: #212529 !important;
    border: 1px solid #ced4da !important;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075) !important;
}

/* Ensure labels are visible */
#approvalModal .form-label,
#rejectionModal .form-label {
    color: #212529 !important;
    opacity: 1 !important;
    font-weight: 500 !important;
}
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Risk Approvals</h1>
                <p class="page-subtitle">Review and approve pending risk assessments</p>
            </div>
        </div>
    </div>

    @if(isset($error))
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Error!</h4>
            <p>{{ $error }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Error!</h4>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Approval Statistics -->
    <div class="stats-grid">
        <a href="{{ route('risks.approval.index', ['status' => 'pending']) }}" 
           class="stat-card pending {{ $status === 'pending' ? 'active' : '' }}">
            <div class="stat-number text-warning">{{ $stats->pending }}</div>
            <div class="stat-label">Pending Approval</div>
        </a>
        <a href="{{ route('risks.approval.index', ['status' => 'approved']) }}" 
           class="stat-card approved {{ $status === 'approved' ? 'active' : '' }}">
            <div class="stat-number text-success">{{ $stats->approved }}</div>
            <div class="stat-label">Approved</div>
        </a>
        <a href="{{ route('risks.approval.index', ['status' => 'rejected']) }}" 
           class="stat-card rejected {{ $status === 'rejected' ? 'active' : '' }}">
            <div class="stat-number text-danger">{{ $stats->rejected }}</div>
            <div class="stat-label">Rejected</div>
        </a>
    </div>

    <!-- Risks Table -->
    <div class="risks-table-card">
        <div class="card-header">
            <h5>
                <i class="fas fa-{{ $status === 'pending' ? 'clock' : ($status === 'approved' ? 'check-circle' : 'times-circle') }} me-2"></i>
                {{ ucfirst($status) }} Risk Approvals
            </h5>
        </div>
        <div class="card-body p-0">
            @if($risks->count() > 0)
                @if($status === 'pending')
                <!-- Bulk Actions - Only show for pending risks -->
                <div class="bulk-actions" id="bulk-actions">
                    <button class="btn btn-success btn-sm" onclick="bulkApprove()">
                        <i class="fas fa-check me-1"></i>Approve Selected
                    </button>
                    <span class="text-muted" id="selected-count">0 selected</span>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                @if($status === 'pending')
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" id="select-all">
                                </th>
                                @endif
                                <th>Risk ID</th>
                                <th>Risk Details</th>
                                <th>Client</th>
                                <th>Created By</th>
                                <th>Risk Level</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($risks as $risk)
                            <tr>
                                @if($status === 'pending')
                                <td>
                                    <input type="checkbox" class="form-check-input risk-checkbox" value="{{ $risk->id }}">
                                </td>
                                @endif
                                <td>
                                    <span class="risk-id">#{{ $risk->id }}</span>
                                </td>
                                <td>
                                    <div class="risk-title">{{ $risk->title }}</div>
                                    <div class="risk-description">{{ Str::limit($risk->description, 60) }}</div>
                                </td>
                                <td>
                                    @if($risk->client)
                                        <a href="{{ route('clients.show', $risk->client) }}" class="client-link">{{ $risk->client->name }}</a>
                                    @else
                                        <span class="text-muted">{{ $risk->client_name }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($risk->creator)
                                        <span class="fw-medium">{{ $risk->creator->name }}</span>
                                        <br><small class="text-muted">{{ $risk->creator->role_display_name }}</small>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $risk->risk_rating_color }}">{{ $risk->risk_rating }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $risk->created_at->format('M d, Y') }}</span>
                                    <br><small class="text-muted">{{ $risk->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('risks.approval.show', $risk) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($status === 'pending')
                                        <button class="btn btn-sm btn-success" onclick="approveRisk({{ $risk->id }})" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectRisk({{ $risk->id }})" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @elseif($status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Approved
                                        </span>
                                        @elseif($status === 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Rejected
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-{{ $status === 'pending' ? 'clock' : ($status === 'approved' ? 'check-circle' : 'times-circle') }}"></i>
                    <h5>No {{ ucfirst($status) }} Risks</h5>
                    <p>
                        @if($status === 'pending')
                            All risks have been reviewed and approved.
                        @elseif($status === 'approved')
                            No risks have been approved yet.
                        @else
                            No risks have been rejected yet.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="position: relative; z-index: 1060;">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalTitle">Approve Risk</h5>
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
                    <button type="submit" class="btn btn-success">Approve Risk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="position: relative; z-index: 1060;">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalTitle">Reject Risk</h5>
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
                    <button type="submit" class="btn btn-danger">Reject Risk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Select all functionality - only for pending risks
@if($status === 'pending')
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.risk-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

document.querySelectorAll('.risk-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const selected = document.querySelectorAll('.risk-checkbox:checked');
    const count = selected.length;
    document.getElementById('selected-count').textContent = `${count} selected`;
    
    // Show/hide bulk actions
    const bulkActions = document.getElementById('bulk-actions');
    if (count > 0) {
        bulkActions.classList.remove('hidden');
    } else {
        bulkActions.classList.add('hidden');
    }
}
@endif

// Global modal management
let currentModal = null;

function cleanupModal() {
    if (currentModal) {
        try {
            currentModal.hide();
            currentModal.dispose();
        } catch (e) {
            // console.log('Modal cleanup error:', e);
        }
        currentModal = null;
    }
    
    // Remove any lingering backdrops
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // Reset body classes
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

function approveRisk(riskId) {
    // Clean up any existing modal first
    cleanupModal();
    
    // Set the form action
    document.getElementById('approvalForm').action = `/risks/approval/${riskId}/approve`;
    
    // Clear any previous form data
    document.getElementById('approval_notes').value = '';
    
    // Show the modal
    const modalElement = document.getElementById('approvalModal');
    currentModal = new bootstrap.Modal(modalElement, {
        backdrop: 'static', // Prevent backdrop clicks from closing modal
        keyboard: false,    // Prevent ESC key from closing modal
        focus: true
    });
    
    // Add event listeners
    modalElement.addEventListener('shown.bs.modal', function() {
        setTimeout(() => {
            const textarea = document.getElementById('approval_notes');
            if (textarea) {
                textarea.focus();
            }
        }, 150);
        
        // Fix backdrop to not block modal interactions
        setTimeout(() => {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => {
                backdrop.style.pointerEvents = 'none';
                backdrop.style.zIndex = '1055';
            });
        }, 100);
        
        // Ensure buttons are clickable by removing any event blockers
        const submitButton = modalElement.querySelector('button[type="submit"]');
        const cancelButton = modalElement.querySelector('button[data-bs-dismiss="modal"]');
        
        if (submitButton) {
            submitButton.style.pointerEvents = 'auto';
            submitButton.style.zIndex = '1074';
            submitButton.style.position = 'relative';
            
            // Remove any existing event listeners that might interfere
            const newSubmitButton = submitButton.cloneNode(true);
            submitButton.parentNode.replaceChild(newSubmitButton, submitButton);
        }
        
        if (cancelButton) {
            cancelButton.style.pointerEvents = 'auto';
            cancelButton.style.zIndex = '1074';
            cancelButton.style.position = 'relative';
            
            // Remove any existing event listeners that might interfere
            const newCancelButton = cancelButton.cloneNode(true);
            cancelButton.parentNode.replaceChild(newCancelButton, cancelButton);
        }
        
        // Ensure textarea is clickable
        const textarea = modalElement.querySelector('textarea');
        if (textarea) {
            textarea.style.pointerEvents = 'auto';
            textarea.style.zIndex = '1074';
            textarea.style.position = 'relative';
        }
    });
    
    modalElement.addEventListener('hidden.bs.modal', function() {
        cleanupModal();
    });
    
    currentModal.show();
}

function rejectRisk(riskId) {
    // Clean up any existing modal first
    cleanupModal();
    
    // Set the form action
    document.getElementById('rejectionForm').action = `/risks/approval/${riskId}/reject`;
    
    // Clear any previous form data
    document.getElementById('rejection_reason').value = '';
    
    // Show the modal
    const modalElement = document.getElementById('rejectionModal');
    currentModal = new bootstrap.Modal(modalElement, {
        backdrop: 'static', // Prevent backdrop clicks from closing modal
        keyboard: false,    // Prevent ESC key from closing modal
        focus: true
    });
    
    // Add event listeners
    modalElement.addEventListener('shown.bs.modal', function() {
        setTimeout(() => {
            const textarea = document.getElementById('rejection_reason');
            if (textarea) {
                textarea.focus();
            }
        }, 150);
        
        // Fix backdrop to not block modal interactions
        setTimeout(() => {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => {
                backdrop.style.pointerEvents = 'none';
                backdrop.style.zIndex = '1055';
            });
        }, 100);
        
        // Ensure buttons are clickable by removing any event blockers
        const submitButton = modalElement.querySelector('button[type="submit"]');
        const cancelButton = modalElement.querySelector('button[data-bs-dismiss="modal"]');
        
        if (submitButton) {
            submitButton.style.pointerEvents = 'auto';
            submitButton.style.zIndex = '1074';
            submitButton.style.position = 'relative';
            
            // Remove any existing event listeners that might interfere
            const newSubmitButton = submitButton.cloneNode(true);
            submitButton.parentNode.replaceChild(newSubmitButton, submitButton);
        }
        
        if (cancelButton) {
            cancelButton.style.pointerEvents = 'auto';
            cancelButton.style.zIndex = '1074';
            cancelButton.style.position = 'relative';
            
            // Remove any existing event listeners that might interfere
            const newCancelButton = cancelButton.cloneNode(true);
            cancelButton.parentNode.replaceChild(newCancelButton, cancelButton);
        }
        
        // Ensure textarea is clickable
        const textarea = modalElement.querySelector('textarea');
        if (textarea) {
            textarea.style.pointerEvents = 'auto';
            textarea.style.zIndex = '1074';
            textarea.style.position = 'relative';
        }
    });
    
    modalElement.addEventListener('hidden.bs.modal', function() {
        cleanupModal();
    });
    
    currentModal.show();
}

function bulkApprove() {
    const selected = Array.from(document.querySelectorAll('.risk-checkbox:checked')).map(cb => cb.value);
    
    if (selected.length === 0) {
        alert('Please select at least one risk to approve.');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selected.length} risk(s)?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/risks/approval/bulk-approve';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        selected.forEach(riskId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'risk_ids[]';
            input.value = riskId;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Page-level cleanup and responsiveness fixes
document.addEventListener('DOMContentLoaded', function() {
    // Clean up any existing modals on page load
    cleanupModal();
    
    // Add global click handler to ensure responsiveness
    document.addEventListener('click', function(e) {
        // If clicking outside modal content but inside modal, close it
        if (e.target.classList.contains('modal') && !e.target.classList.contains('modal-content')) {
            cleanupModal();
        }
    });
    
    // Add escape key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && currentModal) {
            cleanupModal();
        }
    });
    
    // Ensure body is responsive after any modal interaction
    setInterval(function() {
        if (!currentModal && document.body.classList.contains('modal-open')) {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    }, 1000);
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    cleanupModal();
});
</script>
@endsection
