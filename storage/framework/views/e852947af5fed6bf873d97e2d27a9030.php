<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title">Add New User</h1>
                    <p class="page-description">Create a new user account</p>
                </div>
                <div class="page-header-actions">
                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Users
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">User Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('users.store')); ?>">
                                <?php echo csrf_field(); ?>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="password" name="password" required minlength="8">
                                            <div class="form-text">Minimum 8 characters</div>
                                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" 
                                                   id="password_confirmation" name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="role" name="role" required>
                                                <option value="">Select Role</option>
                                                <option value="admin" <?php echo e(old('role') === 'admin' ? 'selected' : ''); ?>>Administrator</option>
                                                <option value="manager" <?php echo e(old('role') === 'manager' ? 'selected' : ''); ?>>Manager</option>
                                                <option value="staff" <?php echo e(old('role') === 'staff' ? 'selected' : ''); ?>>Staff</option>
                                            </select>
                                            <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                       value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="is_active">
                                                    Active User
                                                </label>
                                            </div>
                                            <div class="form-text">Inactive users cannot log in</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6>Role Permissions</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card role-card" data-role="admin">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-crown fa-2x text-danger mb-2"></i>
                                                    <h6>Administrator</h6>
                                                    <small class="text-muted">Full system access</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card role-card" data-role="manager">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-user-tie fa-2x text-warning mb-2"></i>
                                                    <h6>Manager</h6>
                                                    <small class="text-muted">Manage risks and users</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card role-card" data-role="staff">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-user fa-2x text-info mb-2"></i>
                                                    <h6>Staff</h6>
                                                    <small class="text-muted">Basic system access</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        Create User
                                    </button>
                                </div>
                            </form>
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

.role-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.role-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.role-card.selected {
    border-color: var(--logo-dark-blue-primary);
    background-color: rgba(0, 7, 45, 0.05);
}

.form-label {
    font-weight: 600;
    color: var(--logo-dark-blue-primary);
}

.text-danger {
    color: #dc3545 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const roleCards = document.querySelectorAll('.role-card');
    
    // Update role cards when select changes
    roleSelect.addEventListener('change', function() {
        updateRoleCards();
    });
    
    // Update select when role card is clicked
    roleCards.forEach(card => {
        card.addEventListener('click', function() {
            const role = this.dataset.role;
            roleSelect.value = role;
            updateRoleCards();
        });
    });
    
    function updateRoleCards() {
        const selectedRole = roleSelect.value;
        roleCards.forEach(card => {
            card.classList.remove('selected');
            if (card.dataset.role === selectedRole) {
                card.classList.add('selected');
            }
        });
    }
    
    // Initialize on page load
    updateRoleCards();
});
</script>
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
        margin-top: 1rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
    }
    .card {
        margin: 0 -0.5rem;
        border-radius: 0;
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
    .card {
        margin: 0 -0.25rem;
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
}
</style>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\well-known\resources\views/users/create.blade.php ENDPATH**/ ?>