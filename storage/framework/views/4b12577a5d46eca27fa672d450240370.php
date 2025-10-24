

<?php $__env->startSection('title', 'Review Risk Approval'); ?>

<?php $__env->startSection('content'); ?>
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
    
    .risk-details-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    
    .risk-details-card .card-header {
        background: var(--logo-dark-blue-primary);
        color: white;
        padding: 1rem 1.5rem;
        border: none;
    }
    
    .risk-details-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .detail-row {
        display: flex;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 600;
        color: #475569;
        width: 200px;
        flex-shrink: 0;
    }
    
    .detail-value {
        color: #1e293b;
        flex: 1;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
    }
    
    .action-buttons {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
    }
    
    .action-buttons .btn {
        margin: 0 0.5rem;
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
    
    .client-link {
        color: var(--logo-dark-blue-primary);
        text-decoration: none;
        font-weight: 500;
    }
    
    .client-link:hover {
        color: var(--logo-dark-blue-hover);
        text-decoration: underline;
    }
    
    .text-muted {
        color: #64748b !important;
    }
    
    /* Modal z-index fix to prevent backdrop from blocking interaction */
    .modal-backdrop {
        z-index: 1040 !important;
    }
    
    .modal {
        z-index: 1050 !important;
    }
    
    .modal-dialog {
        z-index: 1060 !important;
    }
    
    /* Ensure modal content is interactive */
    .modal-content {
        position: relative;
        z-index: 1070 !important;
        pointer-events: auto !important;
    }
    
    .modal-body,
    .modal-footer,
    .modal-header {
        z-index: 1070 !important;
        pointer-events: auto !important;
    }
    
    .modal-body input,
    .modal-body textarea,
    .modal-body select,
    .modal-body button {
        pointer-events: auto !important;
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
        .risk-details-card {
            margin: 0 -0.5rem 1.5rem -0.5rem;
            border-radius: 0;
        }
        .risk-details-card .card-header {
            padding: 0.75rem 1rem;
        }
        .risk-details-card .card-header h5 {
            font-size: 1rem;
        }
        .card-body {
            padding: 1rem;
        }
        .detail-row {
            flex-direction: column;
            gap: 0.5rem;
        }
        .detail-label {
            font-size: 0.8rem;
            font-weight: 600;
        }
        .detail-value {
            font-size: 0.85rem;
        }
        .approval-actions {
            margin: 0 -0.5rem;
            border-radius: 0;
            padding: 1rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }
        .btn-group {
            flex-direction: column;
            gap: 0.5rem;
        }
        .btn-group .btn {
            width: 100%;
        }
        .table {
            font-size: 0.8rem;
        }
        .table th, .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.75rem;
        }
        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
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
        .risk-details-card {
            margin: 0 -0.25rem 1rem -0.25rem;
        }
        .risk-details-card .card-header {
            padding: 0.5rem 0.75rem;
        }
        .risk-details-card .card-header h5 {
            font-size: 0.9rem;
        }
        .card-body {
            padding: 0.75rem;
        }
        .detail-label {
            font-size: 0.75rem;
        }
        .detail-value {
            font-size: 0.8rem;
        }
        .approval-actions {
            margin: 0 -0.25rem;
            padding: 0.75rem;
        }
        .btn {
            padding: 0.625rem 1.25rem;
            font-size: 0.85rem;
        }
        .table {
            font-size: 0.7rem;
        }
        .table th, .table td {
            padding: 0.375rem 0.125rem;
            font-size: 0.65rem;
        }
        .badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
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
        .risk-details-card .card-header {
            padding: 0.4rem 0.5rem;
        }
        .risk-details-card .card-header h5 {
            font-size: 0.85rem;
        }
        .card-body {
            padding: 0.5rem;
        }
        .detail-label {
            font-size: 0.7rem;
        }
        .detail-value {
            font-size: 0.75rem;
        }
        .approval-actions {
            padding: 0.5rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        .table {
            font-size: 0.65rem;
        }
        .table th, .table td {
            padding: 0.25rem 0.1rem;
            font-size: 0.6rem;
        }
        .badge {
            font-size: 0.6rem;
            padding: 0.15rem 0.3rem;
        }
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
                <h1 class="page-title">Review Risk Approval</h1>
                <p class="page-subtitle">Risk ID: <span class="risk-id">#<?php echo e($risk->id); ?></span></p>
            </div>
            <div>
                <a href="<?php echo e(route('risks.approval.index')); ?>" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to Approvals
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Risk Details -->
            <div class="risk-details-card">
                <div class="card-header">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Risk Assessment Details</h5>
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">Risk ID</div>
                        <div class="detail-value">
                            <span class="risk-id">#<?php echo e($risk->id); ?></span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Title</div>
                        <div class="detail-value"><?php echo e($risk->title); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Description</div>
                        <div class="detail-value"><?php echo e($risk->description); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Client</div>
                        <div class="detail-value">
                            <?php if($risk->client): ?>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <a href="<?php echo e(route('clients.show', $risk->client)); ?>" class="client-link"><?php echo e($risk->client->name); ?></a>
                                        <?php if($risk->client->email): ?>
                                            <br><small class="text-muted"><?php echo e($risk->client->email); ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ms-3">
                                        <button class="btn btn-sm btn-outline-info" onclick="viewClientHistory('<?php echo e($risk->client->name); ?>', <?php echo e($risk->client->id); ?>)" title="View Complete Assessment History">
                                            <i class="fas fa-history me-1"></i>View History
                                        </button>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-muted"><?php echo e($risk->client_name); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Risk Category</div>
                        <div class="detail-value">
                            <span class="badge bg-secondary"><?php echo e($risk->risk_category); ?></span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Risk Level</div>
                        <div class="detail-value">
                            <span class="badge bg-<?php echo e($risk->risk_rating_color); ?>"><?php echo e($risk->risk_rating); ?></span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Impact</div>
                        <div class="detail-value"><?php echo e($risk->impact); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Likelihood</div>
                        <div class="detail-value"><?php echo e($risk->likelihood); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="badge bg-<?php echo e($risk->status_color); ?>"><?php echo e($risk->status); ?></span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Mitigation Strategies</div>
                        <div class="detail-value"><?php echo e($risk->mitigation_strategies); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Owner</div>
                        <div class="detail-value"><?php echo e($risk->owner); ?></div>
                    </div>
                    
                    <?php if($risk->assignedUser): ?>
                    <div class="detail-row">
                        <div class="detail-label">Assigned To</div>
                        <div class="detail-value"><?php echo e($risk->assignedUser->name); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($risk->due_date): ?>
                    <div class="detail-row">
                        <div class="detail-label">Due Date</div>
                        <div class="detail-value">
                            <?php echo e($risk->due_date->format('M d, Y')); ?>

                            <?php if($risk->isOverdue()): ?>
                                <span class="badge bg-danger ms-2">Overdue</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-row">
                        <div class="detail-label">Overall Risk Points</div>
                        <div class="detail-value"><?php echo e($risk->overall_risk_points ?? 'N/A'); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Overall Risk Rating</div>
                        <div class="detail-value"><?php echo e($risk->overall_risk_rating ?? 'N/A'); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Client Acceptance</div>
                        <div class="detail-value"><?php echo e($risk->client_acceptance ?? 'N/A'); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Ongoing Monitoring</div>
                        <div class="detail-value"><?php echo e($risk->ongoing_monitoring ?? 'N/A'); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">DCS Risk Appetite</div>
                        <div class="detail-value"><?php echo e($risk->dcs_risk_appetite ?? 'N/A'); ?></div>
                    </div>
                    
                    <?php if($risk->dcs_comments): ?>
                    <div class="detail-row">
                        <div class="detail-label">DCS Comments</div>
                        <div class="detail-value"><?php echo e($risk->dcs_comments); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Approval Actions -->
            <div class="action-buttons">
                <h5 class="mb-3">Approval Actions</h5>
                <p class="text-muted mb-4">Review the risk assessment details and make your decision.</p>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-lg" onclick="approveRisk()">
                        <i class="fas fa-check me-2"></i>Approve Risk
                    </button>
                    <button class="btn btn-danger btn-lg" onclick="rejectRisk()">
                        <i class="fas fa-times me-2"></i>Reject Risk
                    </button>
                </div>
                
                <hr class="my-4">
                
                <div class="text-start">
                    <h6 class="text-muted">Risk Information</h6>
                    <p class="small text-muted mb-1">
                        <strong>Created by:</strong> <?php echo e($risk->creator->name ?? 'Unknown'); ?><br>
                        <strong>Created:</strong> <?php echo e($risk->created_at->format('M d, Y H:i')); ?><br>
                        <strong>Last updated:</strong> <?php echo e($risk->updated_at->format('M d, Y H:i')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel">Approve Risk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('risks.approval.approve', $risk)); ?>" method="POST">
                <?php echo csrf_field(); ?>
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
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalLabel">Reject Risk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('risks.approval.reject', $risk)); ?>" method="POST">
                <?php echo csrf_field(); ?>
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
// Enhanced modal cleanup function
function cleanupModals() {
    // Remove all modal backdrops including custom ones
    const backdrops = document.querySelectorAll('.modal-backdrop, .modal-backdrop.show, #custom-backdrop');
    backdrops.forEach(backdrop => {
        backdrop.remove();
    });
    
    // Reset body classes and styles
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.body.style.overflowY = '';
    
    // Dispose of all modal instances
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.dispose();
        }
    });
    
    // Force remove any remaining modal classes
    document.body.classList.remove('modal-open');
}

// Add event listeners for modal cleanup
document.addEventListener('DOMContentLoaded', function() {
    // Clean up any existing modals on page load
    cleanupModals();
    
    // Add event listener for when modals are hidden
    document.addEventListener('hidden.bs.modal', function(event) {
        cleanupModals();
    });
    
    // Add event listener for when modals are shown
    document.addEventListener('shown.bs.modal', function(event) {
        // Modal shown
    });
    
    // Force cleanup every 2 seconds as a safety net
    setInterval(function() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        if (backdrops.length > 0) {
            cleanupModals();
        }
    }, 2000);
});

function approveRisk() {
    // Clean up any existing modals first
    cleanupModals();
    
    const modalElement = document.getElementById('approvalModal');
    
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
        backdrop.id = 'custom-backdrop';
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
        const customBackdrop = document.getElementById('custom-backdrop');
        if (customBackdrop) {
            customBackdrop.remove();
        }
        cleanupModals();
    }, { once: true });
}

function rejectRisk() {
    // Clean up any existing modals first
    cleanupModals();
    
    const modalElement = document.getElementById('rejectionModal');
    
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
        backdrop.id = 'custom-backdrop';
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
        const customBackdrop = document.getElementById('custom-backdrop');
        if (customBackdrop) {
            customBackdrop.remove();
        }
        cleanupModals();
    }, { once: true });
}

// Emergency cleanup function - call this if modals get stuck
function emergencyCleanup() {
    // Remove all modal backdrops including custom ones
    const backdrops = document.querySelectorAll('.modal-backdrop, #custom-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // Reset body
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.body.style.overflowY = '';
    
    // Hide all modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.classList.remove('show');
        modal.style.display = 'none';
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.dispose();
        }
    });
}

// Make emergency cleanup available globally
window.emergencyCleanup = emergencyCleanup;

// View complete client history
function viewClientHistory(clientName, clientId) {
    // Create a modal to show complete client history
    const modalHtml = `
        <div class="modal fade" id="clientHistoryModal" tabindex="-1" aria-labelledby="clientHistoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clientHistoryModalLabel">
                            <i class="fas fa-history me-2"></i>
                            Complete Assessment History - ${clientName}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="clientHistoryContent">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading client history...</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="exportClientHistory('${clientName}')">
                            <i class="fas fa-download me-1"></i>Export History
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('clientHistoryModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('clientHistoryModal'));
    modal.show();
    
    // Load client history
    loadClientHistory(clientName, clientId);
}

function loadClientHistory(clientName, clientId) {
    fetch(`/api/clients/${clientId}/history`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            displayClientHistory(data, clientName);
        })
        .catch(error => {
            console.error('Error loading client history:', error);
            document.getElementById('clientHistoryContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error loading client history: ${error.message}. Please try again.
                </div>
            `;
        });
}

function displayClientHistory(data, clientName) {
    const content = document.getElementById('clientHistoryContent');
    
    let html = `
        <div class="alert alert-info mb-3">
            <h6><i class="fas fa-user me-2"></i>Client: ${clientName}</h6>
            <p class="mb-0">Complete assessment history with all risk IDs and progression tracking for AML compliance.</p>
        </div>
    `;

    if (data.assessments && data.assessments.length > 0) {
        html += '<div class="table-responsive">';
        html += '<table class="table table-striped table-hover">';
        html += '<thead class="table-dark">';
        html += '<tr>';
        html += '<th>Assessment Date</th>';
        html += '<th>Risk Score</th>';
        html += '<th>Risk Rating</th>';
        html += '<th>Status</th>';
        html += '<th>Client Risk ID</th>';
        html += '<th>Service Risk ID</th>';
        html += '<th>Payment Risk ID</th>';
        html += '<th>Delivery Risk ID</th>';
        html += '<th>Decision</th>';
        html += '<th>Monitoring</th>';
        html += '<th>Actions</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        
        data.assessments.forEach((assessment, index) => {
            const date = new Date(assessment.created_at).toLocaleDateString();
            const riskColor = getRiskRatingColor(assessment.overall_risk_rating);
            const statusColor = getStatusColor(assessment.assessment_status);
            const isLatest = index === 0;
            
            html += `
                <tr class="${isLatest ? 'table-success' : ''}">
                    <td>
                        <strong>${date}</strong>
                        ${isLatest ? '<br><small class="text-success"><i class="fas fa-star"></i> Latest</small>' : ''}
                    </td>
                    <td><strong>${assessment.overall_risk_points || 'N/A'}</strong></td>
                    <td><span class="badge bg-${riskColor}">${assessment.overall_risk_rating}</span></td>
                    <td><span class="badge bg-${statusColor}">${assessment.assessment_status}</span></td>
                    <td><span class="badge bg-primary">CR-${String(assessment.id).padStart(2, '0')}</span></td>
                    <td><span class="badge bg-info">SR-${String(assessment.id).padStart(2, '0')}</span></td>
                    <td><span class="badge bg-warning">PR-${String(assessment.id).padStart(2, '0')}</span></td>
                    <td><span class="badge bg-secondary">DR-${String(assessment.id).padStart(2, '0')}</span></td>
                    <td>${assessment.client_acceptance || 'N/A'}</td>
                    <td>${assessment.ongoing_monitoring || 'N/A'}</td>
                    <td>
                        <a href="/clients/${assessment.id}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        
        // Add risk progression summary
        if (data.assessments.length > 1) {
            html += `
                <div class="mt-4">
                    <h6><i class="fas fa-chart-line me-2"></i>Risk Progression Summary</h6>
                    <div class="alert alert-light">
                        <p class="mb-1"><strong>Total Assessments:</strong> ${data.assessments.length}</p>
                        <p class="mb-1"><strong>Risk Trend:</strong> ${getRiskTrend(data.assessments)}</p>
                        <p class="mb-0"><strong>Last Assessment:</strong> ${data.assessments[0].overall_risk_rating} (${data.assessments[0].overall_risk_points} points)</p>
                    </div>
                </div>
            `;
        }
    } else {
        html += '<div class="alert alert-warning">No previous assessments found for this client.</div>';
    }
    
    content.innerHTML = html;
}

function getRiskRatingColor(rating) {
    switch(rating.toLowerCase()) {
        case 'low': return 'success';
        case 'medium': return 'warning';
        case 'high': return 'danger';
        case 'critical': return 'dark';
        default: return 'secondary';
    }
}

function getStatusColor(status) {
    switch(status.toLowerCase()) {
        case 'approved': return 'success';
        case 'pending': return 'warning';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

function getRiskTrend(assessments) {
    if (assessments.length < 2) return 'Insufficient data';
    
    const latest = assessments[0].overall_risk_points || 0;
    const previous = assessments[1].overall_risk_points || 0;
    
    if (latest > previous) return 'Increasing Risk';
    if (latest < previous) return 'Decreasing Risk';
    return 'Stable Risk';
}

function exportClientHistory(clientName) {
    // Simple export functionality
    const table = document.querySelector('#clientHistoryModal table');
    if (table) {
        const csv = tableToCSV(table);
        downloadCSV(csv, `${clientName}_assessment_history.csv`);
    }
}

function tableToCSV(table) {
    const rows = Array.from(table.querySelectorAll('tr'));
    return rows.map(row => {
        const cells = Array.from(row.querySelectorAll('th, td'));
        return cells.map(cell => `"${cell.textContent.trim()}"`).join(',');
    }).join('\n');
}

function downloadCSV(csv, filename) {
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\DCS-Best\resources\views/risks/approval/show.blade.php ENDPATH**/ ?>