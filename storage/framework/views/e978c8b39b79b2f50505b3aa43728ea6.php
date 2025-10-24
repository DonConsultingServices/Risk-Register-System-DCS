

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title">User Details</h1>
                    <p class="page-description">View user information and activity</p>
                </div>
                <div class="page-header-actions">
                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Users
                    </a>
                    <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit User
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- User Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="user-avatar-large mb-3">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <h4 class="mb-1"><?php echo e($user->name); ?></h4>
                            <p class="text-muted mb-2"><?php echo e($user->email); ?></p>
                            <span class="badge badge-<?php echo e($user->role === 'admin' ? 'danger' : ($user->role === 'manager' ? 'warning' : 'info')); ?> mb-3">
                                <?php echo e($user->role_display_name); ?>

                            </span>
                            <div class="mt-3">
                                <span class="badge badge-<?php echo e($user->is_active ? 'success' : 'secondary'); ?>">
                                    <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- User Stats -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">User Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="stat-item">
                                <div class="stat-label">Member Since</div>
                                <div class="stat-value"><?php echo e($user->created_at->format('M j, Y')); ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Last Login</div>
                                <div class="stat-value">
                                    <?php if($user->last_login_at): ?>
                                        <?php echo e($user->last_login_at->diffForHumans()); ?>

                                    <?php else: ?>
                                        Never
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Password Changed</div>
                                <div class="stat-value">
                                    <?php if($user->password_changed_at): ?>
                                        <?php echo e($user->password_changed_at->diffForHumans()); ?>

                                    <?php else: ?>
                                        Never
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Activity -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Recent Activity</h6>
                        </div>
                        <div class="card-body">
                            <?php if($recentActivities->count() > 0): ?>
                                <div class="activity-list">
                                    <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="activity-item">
                                            <div class="activity-icon">
                                                <i class="<?php echo e($activity->action_icon); ?> <?php echo e($activity->action_color); ?>"></i>
                                            </div>
                                            <div class="activity-content">
                                                <div class="activity-description"><?php echo e($activity->description); ?></div>
                                                <div class="activity-time"><?php echo e($activity->time_ago); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">No recent activity</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem 0;
}

.page-header-content h1 {
    margin: 0;
    color: var(--logo-dark-blue-primary);
    font-size: 2rem;
    font-weight: 600;
}

.page-header-content p {
    margin: 0.5rem 0 0 0;
    color: #64748b;
}

.user-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 2rem;
    margin: 0 auto;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
}

.badge-danger { background-color: #dc3545; color: white; }
.badge-warning { background-color: #ffc107; color: #000; }
.badge-info { background-color: #17a2b8; color: white; }
.badge-success { background-color: #28a745; color: white; }
.badge-secondary { background-color: #6c757d; color: white; }

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    font-weight: 500;
    color: #64748b;
}

.stat-value {
    font-weight: 600;
    color: var(--logo-dark-blue-primary);
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1rem;
}

.activity-content {
    flex: 1;
}

.activity-description {
    font-weight: 500;
    color: var(--logo-dark-blue-primary);
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.875rem;
    color: #64748b;
}
</style>
<?php $__env->stopSection(); ?>

<style>
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
    .page-description {
        font-size: 0.85rem;
    }
    .page-header-actions {
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
    }
    .card {
        margin-bottom: 1rem;
    }
    .card-header {
        padding: 0.75rem 1rem;
    }
    .card-title {
        font-size: 1.1rem;
    }
    .card-body {
        padding: 1rem;
    }
    .row {
        margin: 0;
    }
    .col-md-4, .col-md-8 {
        padding: 0.25rem;
        margin-bottom: 1rem;
    }
    .user-avatar-large {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
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
    .text-muted {
        font-size: 0.75rem;
    }
    .btn-group {
        flex-direction: column;
        gap: 0.25rem;
    }
    .btn-group .btn {
        width: 100%;
        margin-bottom: 0.25rem;
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
    .page-description {
        font-size: 0.8rem;
    }
    .card-header {
        padding: 0.5rem 0.75rem;
    }
    .card-title {
        font-size: 1rem;
    }
    .card-body {
        padding: 0.75rem;
    }
    .col-md-4, .col-md-8 {
        padding: 0.125rem;
        margin-bottom: 0.75rem;
    }
    .user-avatar-large {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
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
    .text-muted {
        font-size: 0.7rem;
    }
    .btn {
        padding: 0.625rem 1.25rem;
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .page-header {
        padding: 0.5rem;
    }
    .page-title {
        font-size: 1rem;
    }
    .page-description {
        font-size: 0.75rem;
    }
    .card-header {
        padding: 0.4rem 0.5rem;
    }
    .card-title {
        font-size: 0.9rem;
    }
    .card-body {
        padding: 0.5rem;
    }
    .user-avatar-large {
        width: 40px;
        height: 40px;
        font-size: 1rem;
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
    .text-muted {
        font-size: 0.65rem;
    }
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
}
</style>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\DCS-Best\resources\views/users/show.blade.php ENDPATH**/ ?>