

<?php $__env->startSection('title', 'Client Assessment Approvals'); ?>

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
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Client Assessment Approvals</h1>
                <p class="page-subtitle">Review and approve pending client risk assessments</p>
            </div>
        </div>
    </div>

    <!-- Approval Statistics -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-number text-warning"><?php echo e($pendingAssessments->count()); ?></div>
            <div class="stat-label">Pending Assessment</div>
        </div>
        <div class="stat-card approved">
            <div class="stat-number text-success"><?php echo e(\App\Models\Client::where('assessment_status', 'approved')->count()); ?></div>
            <div class="stat-label">Approved</div>
        </div>
        <div class="stat-card rejected">
            <div class="stat-number text-danger"><?php echo e(\App\Models\Client::where('assessment_status', 'rejected')->count()); ?></div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    <!-- Pending Assessments Table -->
    <div class="assessments-table-card">
        <div class="card-header">
            <h5><i class="fas fa-clock me-2"></i>Pending Client Assessments</h5>
        </div>
        <div class="card-body p-0">
            <?php if($pendingAssessments->count() > 0): ?>
                <!-- Bulk Actions -->
                <div class="bulk-actions" id="bulk-actions">
                    <button class="btn btn-success btn-sm" onclick="bulkApprove()">
                        <i class="fas fa-check me-1"></i>Approve Selected
                    </button>
                    <span class="text-muted" id="selected-count">0 selected</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" id="select-all">
                                </th>
                                <th>Client Details</th>
                                <th>Risk Assessment</th>
                                <th>Created By</th>
                                <th>Assessment Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pendingAssessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input client-checkbox" value="<?php echo e($client->id); ?>">
                                </td>
                                <td>
                                    <div class="client-name"><?php echo e($client->name); ?></div>
                                    <div class="client-details">
                                        <?php if($client->email): ?>
                                            <i class="fas fa-envelope me-1"></i><?php echo e($client->email); ?>

                                        <?php endif; ?>
                                        <?php if($client->industry): ?>
                                            <br><i class="fas fa-building me-1"></i><?php echo e($client->industry); ?>

                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="risk-score"><?php echo e($client->overall_risk_points ?? 0); ?> pts</div>
                                    <div class="client-details"><?php echo e($client->overall_risk_rating ?? 'Not assessed'); ?></div>
                                    <div class="client-details"><?php echo e($client->client_acceptance ?? 'Pending'); ?></div>
                                    <?php if($client->comprehensiveRiskAssessment): ?>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Categories:</strong>
                                                <?php if($client->comprehensiveRiskAssessment->sr_risk_id): ?>
                                                    <span class="badge bg-info me-1">SR</span>
                                                <?php endif; ?>
                                                <?php if($client->comprehensiveRiskAssessment->cr_risk_id): ?>
                                                    <span class="badge bg-warning me-1">CR</span>
                                                <?php endif; ?>
                                                <?php if($client->comprehensiveRiskAssessment->pr_risk_id): ?>
                                                    <span class="badge bg-danger me-1">PR</span>
                                                <?php endif; ?>
                                                <?php if($client->comprehensiveRiskAssessment->dr_risk_id): ?>
                                                    <span class="badge bg-secondary me-1">DR</span>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($client->creator): ?>
                                        <span class="fw-medium"><?php echo e($client->creator->name); ?></span>
                                        <br><small class="text-muted"><?php echo e($client->creator->role_display_name); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted"><?php echo e($client->created_at->format('M d, Y')); ?></span>
                                    <br><small class="text-muted"><?php echo e($client->created_at->diffForHumans()); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($client->assessment_status_color); ?>"><?php echo e(ucfirst($client->assessment_status)); ?></span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo e(route('client-assessments.approval.show', $client)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success" onclick="approveAssessment(<?php echo e($client->id); ?>)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectAssessment(<?php echo e($client->id); ?>)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <h5>No Pending Assessments</h5>
                    <p>All client assessments have been reviewed and approved.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalTitle">Approve Client Assessment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm" method="POST">
                <?php echo csrf_field(); ?>
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
<div class="modal fade" id="rejectionModal" tabindex="-1" data-bs-backdrop="false" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="z-index: 1060;">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalTitle">Reject Client Assessment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="closeRejectionModal()"></button>
            </div>
            <form id="rejectionForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Please provide a reason for rejection..." required style="resize: vertical; min-height: 80px;" autocomplete="off" spellcheck="true"></textarea>
                        <div class="form-text">Type your rejection reason above. If you cannot type, please check the browser console for errors.</div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="testTextarea()">Test Textarea</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeRejectionModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Assessment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Fix modal backdrop and interaction issues */
#rejectionModal {
    z-index: 1055 !important;
}

#rejectionModal .modal-dialog {
    z-index: 1060 !important;
    pointer-events: auto !important;
}

#rejectionModal .modal-content {
    z-index: 1060 !important;
    position: relative !important;
    pointer-events: auto !important;
}

#rejectionModal .modal-body {
    z-index: 1061 !important;
    position: relative !important;
    pointer-events: auto !important;
}

#rejectionModal .modal-body textarea {
    pointer-events: auto !important;
    opacity: 1 !important;
    z-index: 1062 !important;
    position: relative !important;
    background-color: #fff !important;
    border: 1px solid #ced4da !important;
    cursor: text !important;
}

#rejectionModal .modal-body textarea:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    outline: none !important;
}

#rejectionModal .modal-body textarea:disabled {
    pointer-events: auto !important;
    opacity: 1 !important;
    background-color: #fff !important;
}

/* Remove backdrop completely */
.modal-backdrop {
    display: none !important;
}

/* Ensure no backdrop is created */
#rejectionModal + .modal-backdrop {
    display: none !important;
}

/* Fix any potential overlay issues */
#rejectionModal .modal-body * {
    pointer-events: auto !important;
}

/* Force modal to be interactive */
#rejectionModal .modal-dialog,
#rejectionModal .modal-content,
#rejectionModal .modal-body,
#rejectionModal .modal-body textarea {
    user-select: text !important;
    -webkit-user-select: text !important;
    -moz-user-select: text !important;
    -ms-user-select: text !important;
}

/* Ensure page remains interactive when modal is open */
body.modal-open {
    overflow: auto !important;
}

/* Hide any backdrop that might be created */
.modal-backdrop.show {
    display: none !important;
}

/* Ensure modal doesn't block page interaction */
#rejectionModal {
    pointer-events: auto !important;
}

#rejectionModal .modal-dialog {
    pointer-events: auto !important;
}

#rejectionModal .modal-content {
    pointer-events: auto !important;
}
</style>

<script>
// Prevent backdrop creation globally
document.addEventListener('DOMContentLoaded', function() {
    // Remove any existing backdrops
    const removeBackdrops = () => {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '';
    };
    
    // Run immediately and on interval
    removeBackdrops();
    setInterval(removeBackdrops, 100);
});

// Select all functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.client-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

document.querySelectorAll('.client-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const selected = document.querySelectorAll('.client-checkbox:checked');
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

function approveAssessment(clientId) {
    document.getElementById('approvalForm').action = `/client-assessments/approval/${clientId}/approve`;
    new bootstrap.Modal(document.getElementById('approvalModal')).show();
}

function rejectAssessment(clientId) {
    // console.log('Rejecting assessment for client:', clientId);
    
    // Set the form action
    document.getElementById('rejectionForm').action = `/client-assessments/approval/${clientId}/reject`;
    
    // Clear any previous rejection reason
    const textarea = document.getElementById('rejection_reason');
    textarea.value = '';
    
    // Ensure textarea is properly enabled
    textarea.disabled = false;
    textarea.readOnly = false;
    textarea.removeAttribute('disabled');
    textarea.removeAttribute('readonly');
    
    // Get modal element
    const modalElement = document.getElementById('rejectionModal');
    
    // Configure modal options to prevent backdrop issues
    const modalOptions = {
        backdrop: false,
        keyboard: true,
        focus: true
    };
    
    // Create and show modal
    const modal = new bootstrap.Modal(modalElement, modalOptions);
    modal.show();
    
    // Remove any backdrop that might be created
    setTimeout(() => {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            backdrop.remove();
        });
        
        // Ensure body doesn't have modal-open class that blocks interaction
        document.body.classList.remove('modal-open');
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '';
    }, 100);
    
    // Focus on textarea after modal is shown
    setTimeout(() => {
        // console.log('Focusing textarea after modal show');
        textarea.focus();
        textarea.select();
    }, 200);
}

function closeRejectionModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('rejectionModal'));
    if (modal) {
        modal.hide();
    }
}

function testTextarea() {
    const textarea = document.getElementById('rejection_reason');
    // console.log('Testing textarea...');
    // console.log('Textarea element:', textarea);
    // console.log('Textarea disabled:', textarea.disabled);
    // console.log('Textarea readOnly:', textarea.readOnly);
    // console.log('Textarea style:', textarea.style);
    
    // Try to focus and add text
    textarea.focus();
    textarea.value = 'Test text - if you can see this, the textarea is working!';
    textarea.select();
    
    alert('Test completed. Check if text appeared in the textarea and check browser console for details.');
}

function bulkApprove() {
    const selected = Array.from(document.querySelectorAll('.client-checkbox:checked')).map(cb => cb.value);
    
    if (selected.length === 0) {
        alert('Please select at least one assessment to approve.');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selected.length} assessment(s)?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/client-assessments/approval/bulk-approve';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        selected.forEach(clientId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'client_ids[]';
            input.value = clientId;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\DCS-Best\resources\views/client-assessments/approval/index.blade.php ENDPATH**/ ?>