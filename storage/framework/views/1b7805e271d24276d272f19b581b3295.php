<?php $__env->startSection('title', 'Risk Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('risks.index')); ?>">Risks</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Risk Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title"><?php echo e($risk->title); ?></h4>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo e(route('risks.edit', $risk)); ?>" 
                                       onclick="return confirmEdit('risk')">
                                    <i class="mdi mdi-pencil me-2"></i>Edit
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo e(route('risks.destroy', $risk)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="dropdown-item text-danger" 
                                                onclick="return confirmDelete('risk')">
                                            <i class="mdi mdi-delete me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3">Risk Information</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <p class="mb-0"><?php echo e($risk->description); ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <span class="badge bg-<?php echo e($risk->status_color); ?>"><?php echo e($risk->status); ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Risk Level</label>
                                <span class="badge bg-<?php echo e($risk->risk_rating_color); ?>"><?php echo e($risk->risk_level); ?></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3">Assessment</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Impact</label>
                                <span class="badge bg-<?php echo e($risk->impact == 'Critical' ? 'danger' : ($risk->impact == 'High' ? 'warning' : 'info')); ?>">
                                    <?php echo e($risk->impact); ?>

                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Likelihood</label>
                                <span class="badge bg-<?php echo e($risk->likelihood == 'Very High' ? 'danger' : ($risk->likelihood == 'High' ? 'warning' : 'info')); ?>">
                                    <?php echo e($risk->likelihood); ?>

                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Risk Score</label>
                                <div class="d-flex align-items-center">
                                    <span class="h5 mb-0 me-2"><?php echo e($risk->risk_score); ?></span>
                                    <small class="text-muted">/ 25</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($risk->mitigation): ?>
                    <div class="mt-4">
                        <h6 class="text-muted text-uppercase mb-3">Mitigation Strategy</h6>
                        <p class="mb-0"><?php echo e($risk->mitigation); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Additional Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Client</label>
                        <p class="mb-0">
                            <?php if($risk->client): ?>
                                <a href="<?php echo e(route('clients.show', $risk->client)); ?>" class="text-decoration-none">
                                    <?php echo e($risk->client->name); ?>

                                </a>
                                <br>
                                <small class="text-muted"><?php echo e($risk->client->company ?? 'Individual'); ?></small>
                            <?php else: ?>
                                <span class="text-muted">No client linked to this risk assessment</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <?php if($risk->assignedUser): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Assigned To</label>
                        <p class="mb-0"><?php echo e($risk->assignedUser->name); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($risk->due_date): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Due Date</label>
                        <p class="mb-0">
                            <?php echo e($risk->due_date->format('M d, Y')); ?>

                            <?php if($risk->isOverdue()): ?>
                                <span class="badge bg-danger ms-2">Overdue</span>
                            <?php elseif($risk->getDaysUntilDue() <= 7): ?>
                                <span class="badge bg-warning ms-2">Due Soon</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Created</label>
                        <p class="mb-0"><?php echo e($risk->created_at->format('M d, Y H:i')); ?></p>
                    </div>
                    
                    <?php if($risk->updated_at != $risk->created_at): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Last Updated</label>
                        <p class="mb-0"><?php echo e($risk->updated_at->format('M d, Y H:i')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if($risk->isHighPriority()): ?>
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="mdi mdi-alert-circle text-warning" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">High Priority Risk</h6>
                    <p class="text-muted mb-0">This risk requires immediate attention</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Risk Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Risk Created</h6>
                                <p class="timeline-text"><?php echo e($risk->created_at->format('M d, Y H:i')); ?></p>
                                <small class="text-muted">by <?php echo e($risk->creator->name ?? 'System'); ?></small>
                            </div>
                        </div>
                        
                        <?php if($risk->updated_at != $risk->created_at): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Risk Updated</h6>
                                <p class="timeline-text"><?php echo e($risk->updated_at->format('M d, Y H:i')); ?></p>
                                <small class="text-muted">by <?php echo e($risk->updater->name ?? 'System'); ?></small>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($risk->status == 'Closed'): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Risk Closed</h6>
                                <p class="timeline-text">Risk has been resolved and closed</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Confirmation functions for edit and delete actions
function confirmEdit(type) {
    const messages = {
        'client': 'Are you sure you want to edit this client? Any changes will be saved immediately.',
        'risk': 'Are you sure you want to edit this risk assessment? Any changes will be saved immediately.',
        'category': 'Are you sure you want to edit this risk category? Any changes will be saved immediately.'
    };
    return confirm(messages[type] || 'Are you sure you want to proceed with editing?');
}

function confirmDelete(type, count = 1) {
    const messages = {
        'client': `Are you sure you want to delete ${count > 1 ? 'these clients' : 'this client'}? This will also delete all associated risks and cannot be undone.`,
        'risk': `Are you sure you want to delete ${count > 1 ? 'these risks' : 'this risk'}? This action cannot be undone.`,
        'category': `Are you sure you want to delete ${count > 1 ? 'these categories' : 'this category'}? This will also affect all associated risks and cannot be undone.`
    };
    return confirm(messages[type] || `Are you sure you want to delete ${count > 1 ? 'these items' : 'this item'}?`);
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<style>
/* Mobile-First Responsive Design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0.5rem;
    }
    .page-title-box {
        padding: 1rem 0;
    }
    .page-title {
        font-size: 1.25rem;
    }
    .breadcrumb {
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }
    .card {
        margin-bottom: 1rem;
    }
    .card-header {
        padding: 0.75rem 1rem;
    }
    .card-body {
        padding: 1rem;
    }
    .header-title {
        font-size: 1.1rem;
    }
    .row {
        margin: 0;
    }
    .col-lg-8, .col-lg-4 {
        padding: 0.25rem;
        margin-bottom: 1rem;
    }
    .table {
        font-size: 0.8rem;
    }
    .table th, .table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.75rem;
    }
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
    .btn-group {
        flex-direction: column;
        gap: 0.25rem;
    }
    .btn-group .btn {
        width: 100%;
        margin-bottom: 0.25rem;
    }
    .dropdown-menu {
        font-size: 0.8rem;
    }
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    .text-muted {
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0.25rem;
    }
    .page-title {
        font-size: 1.1rem;
    }
    .breadcrumb {
        font-size: 0.75rem;
    }
    .card-header {
        padding: 0.5rem 0.75rem;
    }
    .card-body {
        padding: 0.75rem;
    }
    .header-title {
        font-size: 1rem;
    }
    .col-lg-8, .col-lg-4 {
        padding: 0.125rem;
        margin-bottom: 0.75rem;
    }
    .table {
        font-size: 0.7rem;
    }
    .table th, .table td {
        padding: 0.375rem 0.125rem;
        font-size: 0.65rem;
    }
    .btn {
        padding: 0.4rem 0.75rem;
        font-size: 0.75rem;
    }
    .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    .text-muted {
        font-size: 0.7rem;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1rem;
    }
    .breadcrumb {
        font-size: 0.7rem;
    }
    .card-header {
        padding: 0.4rem 0.5rem;
    }
    .card-body {
        padding: 0.5rem;
    }
    .header-title {
        font-size: 0.9rem;
    }
    .table {
        font-size: 0.65rem;
    }
    .table th, .table td {
        padding: 0.25rem 0.1rem;
        font-size: 0.6rem;
    }
    .btn {
        padding: 0.3rem 0.5rem;
        font-size: 0.7rem;
    }
    .badge {
        font-size: 0.6rem;
        padding: 0.15rem 0.3rem;
    }
    .text-muted {
        font-size: 0.65rem;
    }
}
</style>

<?php $__env->startPush('styles'); ?>
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -45px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline-content {
    padding-left: 20px;
}

.timeline-title {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-text {
    margin-bottom: 5px;
    color: #6c757d;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\well-known\resources\views/risks/show.blade.php ENDPATH**/ ?>