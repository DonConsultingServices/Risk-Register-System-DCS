<?php $__env->startSection('title', 'User Management - DCS-Best'); ?>
<?php $__env->startSection('page-title', 'Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title" style="color: #ffffff !important;">User Management</h1>
                    <p class="page-description">Manage system users and their permissions</p>
                </div>
                <div class="page-header-actions">
                    <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add New User
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('users.index')); ?>" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo e($search); ?>" placeholder="Search by name or email...">
                        </div>
                        <div class="col-md-2">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="all" <?php echo e($role === 'all' ? 'selected' : ''); ?>>All Roles</option>
                                <option value="admin" <?php echo e($role === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                <option value="manager" <?php echo e($role === 'manager' ? 'selected' : ''); ?>>Manager</option>
                                <option value="staff" <?php echo e($role === 'staff' ? 'selected' : ''); ?>>Staff</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="all" <?php echo e($status === 'all' ? 'selected' : ''); ?>>All Status</option>
                                <option value="active" <?php echo e($status === 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="inactive" <?php echo e($status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="name" <?php echo e($sort === 'name' ? 'selected' : ''); ?>>Name</option>
                                <option value="email" <?php echo e($sort === 'email' ? 'selected' : ''); ?>>Email</option>
                                <option value="role" <?php echo e($sort === 'role' ? 'selected' : ''); ?>>Role</option>
                                <option value="created_at" <?php echo e($sort === 'created_at' ? 'selected' : ''); ?>>Created</option>
                                <option value="last_login_at" <?php echo e($sort === 'last_login_at' ? 'selected' : ''); ?>>Last Login</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="order" class="form-label">Order</label>
                            <select class="form-select" id="order" name="order">
                                <option value="asc" <?php echo e($order === 'asc' ? 'selected' : ''); ?>>Ascending</option>
                                <option value="desc" <?php echo e($order === 'desc' ? 'selected' : ''); ?>>Descending</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                                Apply Filters
                            </button>
                            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h3 class="stat-number"><?php echo e($counts['all']); ?></h3>
                            <p class="stat-label">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h3 class="stat-number text-success"><?php echo e($counts['active']); ?></h3>
                            <p class="stat-label">Active</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h3 class="stat-number text-danger"><?php echo e($counts['inactive']); ?></h3>
                            <p class="stat-label">Inactive</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h3 class="stat-number text-primary"><?php echo e($counts['admin']); ?></h3>
                            <p class="stat-label">Admins</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h3 class="stat-number text-warning"><?php echo e($counts['manager']); ?></h3>
                            <p class="stat-label">Managers</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h3 class="stat-number text-info"><?php echo e($counts['staff']); ?></h3>
                            <p class="stat-label">Staff</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Users (<?php echo e($users->total()); ?> total)</h5>
                </div>
                <div class="card-body p-0">
                    <?php if($users->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar me-3">
                                                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo e($user->name); ?></div>
                                                        <div class="text-muted small"><?php echo e($user->email); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo e($user->role === 'admin' ? 'danger' : ($user->role === 'manager' ? 'warning' : 'info')); ?>">
                                                    <?php echo e($user->role_display_name); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo e($user->is_active ? 'success' : 'secondary'); ?>">
                                                    <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if($user->last_login_at): ?>
                                                    <span class="text-muted"><?php echo e($user->last_login_at->diffForHumans()); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Never</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?php echo e($user->created_at->format('M j, Y')); ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('users.show', $user)); ?>" class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if($user->id !== auth()->id()): ?>
                                                        <button class="btn btn-sm btn-outline-<?php echo e($user->is_active ? 'warning' : 'success'); ?>" 
                                                                onclick="toggleUserStatus(<?php echo e($user->id); ?>)" 
                                                                title="<?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?>">
                                                            <i class="fas fa-<?php echo e($user->is_active ? 'user-times' : 'user-check'); ?>"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" 
                                                                onclick="deleteUser(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')" 
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No users found</h5>
                            <p class="text-muted">Try adjusting your search criteria or add a new user.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if($users->hasPages()): ?>
                    <div class="card-footer">
                        <?php echo e($users->appends(request()->query())->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete User</button>
                <!-- Fallback form for deletion -->
                <form id="deleteForm" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Responsive Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1rem 0;
        flex-wrap: wrap;
        gap: 1rem;
    }

.page-header-content h1 {
    margin: 0;
    color: var(--logo-dark-blue-primary);
    font-size: 1.5rem;
    font-weight: 600;
}

.page-header-content p {
    margin: 0.5rem 0 0 0;
    color: #64748b;
}

/* Enhanced Stat Cards */
.stat-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.stat-label {
    margin: 0.5rem 0 0 0;
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Enhanced User Avatar */
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    flex-shrink: 0;
}

/* Enhanced Badge Styles */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    white-space: nowrap;
}

.badge-danger { background-color: #dc3545; color: white; }
.badge-warning { background-color: #ffc107; color: #000; }
.badge-info { background-color: #17a2b8; color: white; }
.badge-success { background-color: #28a745; color: white; }
.badge-secondary { background-color: #6c757d; color: white; }

/* Enhanced Table Styles */
.table th {
    border-top: none;
    font-weight: 600;
    color: var(--logo-dark-blue-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Enhanced Button Group */
.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Mobile Responsive Enhancements */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        padding: 1rem 0;
        margin-bottom: 1.5rem;
    }
    
    .page-header-content h1 {
        font-size: 1.5rem;
    }
    
    .page-header-content p {
        font-size: 0.9rem;
    }
    
    .page-header-actions {
        width: 100%;
        margin-top: 1rem;
    }
    
    .page-header-actions .btn {
        width: 100%;
        text-align: center;
    }
    
    /* Mobile Stats Grid */
    .row .col-md-2 {
        margin-bottom: 1rem;
    }
    
    .stat-card {
        padding: 1rem;
        text-align: center;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .stat-label {
        font-size: 0.8rem;
    }
    
    /* Mobile Table Enhancements */
    .table th,
    .table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.75rem;
        max-width: 100px;
    }
    
    .table th {
        font-size: 0.7rem;
    }
    
    /* Mobile User Info */
    .user-avatar {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
    
    /* Mobile Badges */
    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Mobile Button Group */
    .btn-group {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 0.25rem;
        font-size: 0.7rem;
        padding: 0.375rem 0.5rem;
    }
}

@media (max-width: 576px) {
    .page-header-content h1 {
        font-size: 1.25rem;
    }
    
    .stat-number {
        font-size: 1.25rem;
    }
    
    .table th,
    .table td {
        padding: 0.375rem 0.125rem;
        font-size: 0.7rem;
        max-width: 80px;
    }
    
    .table th {
        font-size: 0.65rem;
    }
    
    .user-avatar {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
    }
    
    .btn-group .btn {
        font-size: 0.65rem;
        padding: 0.25rem 0.375rem;
    }
}

@media (max-width: 480px) {
    .page-header-content h1 {
        font-size: 1.1rem;
    }
    
    .stat-number {
        font-size: 1.1rem;
    }
    
    .table th,
    .table td {
        padding: 0.25rem 0.1rem;
        font-size: 0.65rem;
        max-width: 60px;
    }
    
    .table th {
        font-size: 0.6rem;
    }
    
    .user-avatar {
        width: 24px;
        height: 24px;
        font-size: 0.7rem;
    }
    
    .badge {
        font-size: 0.55rem;
        padding: 0.15rem 0.3rem;
    }
    
    .btn-group .btn {
        font-size: 0.6rem;
        padding: 0.2rem 0.3rem;
    }
}
</style>

<script>
function toggleUserStatus(userId) {
        fetch(`<?php echo e(url('/users')); ?>/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Error updating user status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating user status');
    });
}

function deleteUser(userId, userName) {
    // console.log('Delete user called:', userId, userName);
    document.getElementById('deleteUserName').textContent = userName;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
    
    // Clear any previous event listeners
    const confirmBtn = document.getElementById('confirmDelete');
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    
    newConfirmBtn.onclick = function() {
        // console.log('Confirm delete clicked for user:', userId);
        
        // Try AJAX first
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        // console.log('CSRF Token:', csrfToken);
        // console.log('Delete URL:', `<?php echo e(url('/users')); ?>/${userId}`);
        
            fetch(`<?php echo e(url('/users')); ?>/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        .then(response => {
            // console.log('Response status:', response.status);
            // console.log('Response ok:', response.ok);
            if (response.ok) {
                // console.log('User deleted successfully, reloading page');
                location.reload();
            } else {
                return response.text().then(text => {
                    console.error('AJAX Delete failed:', text);
                    // Fallback to form submission
                    // console.log('Falling back to form submission');
                    const form = document.getElementById('deleteForm');
                    form.action = `<?php echo e(url('/users')); ?>/${userId}`;
                    form.submit();
                });
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            // console.log('Falling back to form submission due to fetch error');
            // Fallback to form submission
            const form = document.getElementById('deleteForm');
            form.action = `<?php echo e(url('/users')); ?>/${userId}`;
            form.submit();
        });
    };
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\well-known\resources\views/users/index.blade.php ENDPATH**/ ?>