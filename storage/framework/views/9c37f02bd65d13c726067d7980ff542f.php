

<?php $__env->startSection('title', 'Risk Management Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('risks.index')); ?>">Risks</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
                <h4 class="page-title">Risk Management Settings</h4>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- General Settings -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">General Settings</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('risks.settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-3">
                            <label for="risk_assessment_frequency" class="form-label">Risk Assessment Frequency</label>
                            <select class="form-select" id="risk_assessment_frequency" name="risk_assessment_frequency">
                                <?php $frequency = old('risk_assessment_frequency', $settings['risk_assessment_frequency'] ?? 'monthly'); ?>
                                <option value="weekly" <?php echo e($frequency == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                                <option value="monthly" <?php echo e($frequency == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                <option value="quarterly" <?php echo e($frequency == 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                                <option value="annually" <?php echo e($frequency == 'annually' ? 'selected' : ''); ?>>Annually</option>
                            </select>
                            <small class="form-text text-muted">How often should risk assessments be conducted?</small>
                        </div>

                        <div class="mb-3">
                            <label for="auto_risk_scoring" class="form-label">Automatic Risk Scoring</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_risk_scoring" name="auto_risk_scoring" value="1" 
                                       <?php echo e(old('auto_risk_scoring', $settings['auto_risk_scoring'] ?? false) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="auto_risk_scoring">
                                    Enable automatic risk score calculation
                                </label>
                            </div>
                            <small class="form-text text-muted">Automatically calculate risk scores based on impact and likelihood</small>
                        </div>

                        <div class="mb-3">
                            <label for="risk_threshold_high" class="form-label">High Risk Threshold</label>
                            <input type="number" class="form-control" id="risk_threshold_high" name="risk_threshold_high" 
                                   value="<?php echo e(old('risk_threshold_high', $settings['risk_threshold_high'] ?? 15)); ?>" min="1" max="25">
                            <small class="form-text text-muted">Risk score above which a risk is considered high (1-25)</small>
                        </div>

                        <div class="mb-3">
                            <label for="risk_threshold_critical" class="form-label">Critical Risk Threshold</label>
                            <input type="number" class="form-control" id="risk_threshold_critical" name="risk_threshold_critical" 
                                   value="<?php echo e(old('risk_threshold_critical', $settings['risk_threshold_critical'] ?? 20)); ?>" min="1" max="25">
                            <small class="form-text text-muted">Risk score above which a risk is considered critical (1-25)</small>
                        </div>

                        <hr class="my-4">
                        <h5 class="text-primary mb-3">Automatic Client Rejection Settings</h5>

                        <div class="mb-3">
                            <label for="auto_rejection_enabled" class="form-label">Automatic Client Rejection</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_rejection_enabled" name="auto_rejection_enabled" value="1" 
                                       <?php echo e(old('auto_rejection_enabled', $riskThresholdSettings['auto_rejection_enabled'] ?? true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="auto_rejection_enabled">
                                    Enable automatic client rejection based on risk threshold
                                </label>
                            </div>
                            <small class="form-text text-muted">Automatically reject clients when their risk score exceeds the threshold</small>
                        </div>

                        <div class="mb-3">
                            <label for="auto_rejection_threshold" class="form-label">Auto-Rejection Threshold</label>
                            <input type="number" class="form-control" id="auto_rejection_threshold" name="auto_rejection_threshold" 
                                   value="<?php echo e(old('auto_rejection_threshold', $riskThresholdSettings['auto_rejection_threshold'] ?? 20)); ?>" min="1" max="30">
                            <small class="form-text text-muted">Risk score above which clients are automatically rejected (1-30). Default: 20 (Very High risk)</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Save General Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Notification Settings</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('risks.settings.notifications')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-3">
                            <label for="email_notifications" class="form-label">Email Notifications</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" 
                                       <?php echo e(old('email_notifications', $settings['email_notifications'] ?? true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="email_notifications">
                                    Enable email notifications for risk updates
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="high_risk_alerts" class="form-label">High Risk Alerts</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="high_risk_alerts" name="high_risk_alerts" value="1" 
                                       <?php echo e(old('high_risk_alerts', $settings['high_risk_alerts'] ?? true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="high_risk_alerts">
                                    Send immediate alerts for high and critical risks
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="overdue_notifications" class="form-label">Overdue Risk Notifications</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="overdue_notifications" name="overdue_notifications" value="1" 
                                       <?php echo e(old('overdue_notifications', $settings['overdue_notifications'] ?? true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="overdue_notifications">
                                    Notify when risks become overdue
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notification_frequency" class="form-label">Notification Frequency</label>
                            <select class="form-select" id="notification_frequency" name="notification_frequency">
                                <?php $frequency = old('notification_frequency', $settings['notification_frequency'] ?? 'immediate'); ?>
                                <option value="immediate" <?php echo e($frequency == 'immediate' ? 'selected' : ''); ?>>Immediate</option>
                                <option value="daily" <?php echo e($frequency == 'daily' ? 'selected' : ''); ?>>Daily Digest</option>
                                <option value="weekly" <?php echo e($frequency == 'weekly' ? 'selected' : ''); ?>>Weekly Summary</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Notification Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if(auth()->user()->canManageRiskCategories()): ?>
    <div class="row mt-4">
        <!-- Risk Categories Management -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-link p-0 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#riskCategoriesCollapse" aria-expanded="true" aria-controls="riskCategoriesCollapse">
                                <i class="fas fa-chevron-down collapse-icon" id="riskCategoriesIcon"></i>
                            </button>
                            <h4 class="header-title mb-0">Risk Categories</h4>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fas fa-plus me-1"></i>Add Category
                        </button>
                    </div>
                </div>
                <div class="collapse show" id="riskCategoriesCollapse">
                    <div class="card-body">
                        <?php if(isset($categories) && $categories->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Color</th>
                                            <th>Risks Count</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($category->name); ?></td>
                                            <td><?php echo e(Str::limit($category->description ?? 'No description', 50)); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-preview me-2" 
                                                         style="width: 20px; height: 20px; background-color: <?php echo e($category->getFormattedColor()); ?>; border-radius: 4px;"></div>
                                                    <span><?php echo e($category->getFormattedColor()); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo e($category->risks_count ?? 0); ?></td>
                                            <td>
                                                <?php if($category->is_active): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="editCategory(<?php echo e($category->id); ?>, '<?php echo e(addslashes($category->name)); ?>', '<?php echo e(addslashes($category->description ?? '')); ?>', '<?php echo e($category->getFormattedColor()); ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteCategory(<?php echo e($category->id); ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">No Categories Found</h5>
                                <p class="text-muted">Create your first risk category to get started.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

        <!-- System Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-link p-0 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#systemInfoCollapse" aria-expanded="true" aria-controls="systemInfoCollapse">
                            <i class="fas fa-chevron-down collapse-icon" id="systemInfoIcon"></i>
                        </button>
                        <h4 class="header-title mb-0">System Information</h4>
                    </div>
                </div>
                <div class="collapse show" id="systemInfoCollapse">
                    <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">System Version</label>
                        <p class="mb-0"><?php echo e($systemInfo['version'] ?? '1.0.0'); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Last Updated</label>
                        <p class="mb-0"><?php echo e($systemInfo['last_updated'] ?? 'Never'); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Database Records</label>
                        <p class="mb-0"><?php echo e($systemInfo['total_risks'] ?? 0); ?> risks, <?php echo e($systemInfo['total_clients'] ?? 0); ?> clients</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cache Status</label>
                        <p class="mb-0">
                            <?php if($systemInfo['cache_enabled'] ?? false): ?>
                                <span class="badge bg-success">Enabled</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Disabled</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <hr>
                    
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearCache()">
                                <i class="fas fa-refresh me-1"></i>Clear Cache
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="exportSettings()">
                                <i class="fas fa-download me-1"></i>Export Settings
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="resetToDefaults()">
                                <i class="fas fa-undo me-1"></i>Reset to Defaults
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(auth()->user()->canManageRiskCategories()): ?>
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Risk Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('risk-categories.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Description</label>
                        <textarea class="form-control" id="category_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_color" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color" id="category_color" name="color" value="#007bff">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Risk Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_category_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_category_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_category_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_category_color" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color" id="edit_category_color" name="color">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
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
    .col-md-6, .col-md-4 {
        padding: 0.25rem;
        margin-bottom: 0.75rem;
    }
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.375rem;
    }
    .form-control, .form-select {
        padding: 0.75rem;
        font-size: 16px; /* Prevents zoom on iOS */
        border-radius: 8px;
    }
    .form-text {
        font-size: 0.8rem;
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
    .text-end {
        text-align: center !important;
        margin-top: 1rem;
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
    .alert {
        font-size: 0.85rem;
        padding: 0.75rem 1rem;
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
    .col-md-6, .col-md-4 {
        padding: 0.125rem;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        padding: 0.625rem;
        font-size: 16px;
    }
    .form-text {
        font-size: 0.75rem;
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
    .alert {
        font-size: 0.8rem;
        padding: 0.625rem 0.75rem;
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
    .form-control, .form-select {
        padding: 0.5rem;
        font-size: 16px;
    }
    .form-text {
        font-size: 0.7rem;
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
    .alert {
        font-size: 0.75rem;
        padding: 0.5rem 0.625rem;
    }
}
</style>

<?php $__env->startPush('styles'); ?>
<style>
.color-preview {
    border: 1px solid #dee2e6;
}

.form-control-color {
    width: 100%;
    height: 38px;
}

.collapse-icon {
    transition: transform 0.3s ease;
    font-size: 1.1rem;
    color: #6c757d;
}

.collapse-icon.rotated {
    transform: rotate(-90deg);
}

.btn-link {
    text-decoration: none;
    border: none;
    background: none;
}

.btn-link:hover {
    text-decoration: none;
    color: #007bff;
}

.btn-link:focus {
    box-shadow: none;
    outline: none;
}

.collapse {
    transition: height 0.3s ease;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Handle collapse icon rotation
document.addEventListener('DOMContentLoaded', function() {
    // Risk Categories collapse
    const riskCategoriesCollapse = document.getElementById('riskCategoriesCollapse');
    const riskCategoriesIcon = document.getElementById('riskCategoriesIcon');
    
    if (riskCategoriesCollapse && riskCategoriesIcon) {
        riskCategoriesCollapse.addEventListener('show.bs.collapse', function() {
            riskCategoriesIcon.classList.remove('rotated');
        });
        
        riskCategoriesCollapse.addEventListener('hide.bs.collapse', function() {
            riskCategoriesIcon.classList.add('rotated');
        });
        
        // Set initial state
        if (!riskCategoriesCollapse.classList.contains('show')) {
            riskCategoriesIcon.classList.add('rotated');
        }
    }
    
    // System Information collapse
    const systemInfoCollapse = document.getElementById('systemInfoCollapse');
    const systemInfoIcon = document.getElementById('systemInfoIcon');
    
    if (systemInfoCollapse && systemInfoIcon) {
        systemInfoCollapse.addEventListener('show.bs.collapse', function() {
            systemInfoIcon.classList.remove('rotated');
        });
        
        systemInfoCollapse.addEventListener('hide.bs.collapse', function() {
            systemInfoIcon.classList.add('rotated');
        });
        
        // Set initial state
        if (!systemInfoCollapse.classList.contains('show')) {
            systemInfoIcon.classList.add('rotated');
        }
    }
});

function editCategory(id, name, description, color) {
    document.getElementById('edit_category_name').value = name;
    document.getElementById('edit_category_description').value = description;
    document.getElementById('edit_category_color').value = color;
    
    const form = document.getElementById('editCategoryForm');
    form.action = `/risk-categories/${id}`;
    
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category? This will also affect all associated risks.')) {
        fetch(`/risk-categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

function clearCache() {
    if (confirm('Are you sure you want to clear all caches? This may temporarily slow down the system.')) {
        fetch('/dashboard/clear-all-caches', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Cache cleared successfully', 'success');
            } else {
                showNotification('Failed to clear cache', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to clear cache', 'error');
        });
    }
}

function exportSettings() {
    // Create a settings object with current form values
    const settings = {
        general: {
            risk_assessment_frequency: document.getElementById('risk_assessment_frequency').value,
            auto_risk_scoring: document.getElementById('auto_risk_scoring').checked,
            risk_threshold_high: document.getElementById('risk_threshold_high').value,
            risk_threshold_critical: document.getElementById('risk_threshold_critical').value
        },
        notifications: {
            email_notifications: document.getElementById('email_notifications').checked,
            high_risk_alerts: document.getElementById('high_risk_alerts').checked,
            overdue_notifications: document.getElementById('overdue_notifications').checked,
            notification_frequency: document.getElementById('notification_frequency').value
        },
        exported_at: new Date().toISOString(),
        exported_by: '<?php echo e(auth()->user()->name); ?>'
    };
    
    // Create and download JSON file
    const dataStr = JSON.stringify(settings, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'risk_management_settings_' + new Date().toISOString().split('T')[0] + '.json';
    link.click();
    URL.revokeObjectURL(url);
    
    showNotification('Settings exported successfully', 'success');
}

function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
        // Reset form values to defaults
        document.getElementById('risk_assessment_frequency').value = 'monthly';
        document.getElementById('auto_risk_scoring').checked = true;
        document.getElementById('risk_threshold_high').value = 15;
        document.getElementById('risk_threshold_critical').value = 20;
        document.getElementById('email_notifications').checked = true;
        document.getElementById('high_risk_alerts').checked = true;
        document.getElementById('overdue_notifications').checked = true;
        document.getElementById('notification_frequency').value = 'immediate';
        
        showNotification('Settings reset to defaults (not saved yet)', 'info');
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\DCS-Best\resources\views/risks/settings.blade.php ENDPATH**/ ?>