@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>User Management
                </h5>
                <div class="btn-group">
                    <a href="{{ route('users.export') }}" class="btn btn-outline-primary">
                        <i class="fas fa-download me-1"></i>Export Users
                    </a>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Add User
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search users...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="roleFilter">
                            <option value="">All Roles</option>
                            <option value="admin">Administrator</option>
                            <option value="manager">Manager</option>
                            <option value="analyst">Risk Analyst</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Clear
                        </button>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-success" onclick="bulkAction('activate')">
                                <i class="fas fa-check me-1"></i>Activate Selected
                            </button>
                            <button type="button" class="btn btn-outline-warning" onclick="bulkAction('deactivate')">
                                <i class="fas fa-pause me-1"></i>Deactivate Selected
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="bulkAction('delete')">
                                <i class="fas fa-trash me-1"></i>Delete Selected
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="usersTable">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr data-role="{{ $user->role }}" data-status="{{ $user->is_active ? '1' : '0' }}">
                                <td>
                                    <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" 
                                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->id === auth()->id())
                                                <span class="badge bg-info ms-1">You</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'manager' ? 'warning' : ($user->role === 'analyst' ? 'info' : 'secondary')) }}">
                                        {{ $user->role_display_name }}
                                    </span>
                                </td>
                                <td>{{ $user->department ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $user->status_badge_class }}">
                                        {{ $user->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $user->formatted_last_login }}</small>
                                </td>
                                <td>
                                    <small>{{ $user->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('users.show', $user) }}" 
                                           class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" 
                                           class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button type="button" class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" 
                                                    onclick="toggleUserStatus({{ $user->id }})" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="deleteUser({{ $user->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No users found</h5>
                                    <p class="text-muted">Start by adding your first user.</p>
                                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-1"></i>Add User
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
                <p class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteUserForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Form -->
<form id="bulkActionForm" method="POST" action="{{ route('users.bulk-action') }}" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkActionType">
    <input type="hidden" name="user_ids" id="bulkUserIds">
</form>

<script>
// Search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value;
        const statusValue = statusFilter.value;

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let showRow = true;

            // Search filter
            if (searchTerm) {
                let rowText = '';
                for (let j = 1; j < cells.length - 1; j++) {
                    rowText += cells[j].textContent + ' ';
                }
                if (!rowText.toLowerCase().includes(searchTerm)) {
                    showRow = false;
                }
            }

            // Role filter
            if (roleValue && row.dataset.role !== roleValue) {
                showRow = false;
            }

            // Status filter
            if (statusValue && row.dataset.status !== statusValue) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        }
    }

    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
});

// Clear filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('statusFilter').value = '';
    
    // Trigger filter
    const event = new Event('input');
    document.getElementById('searchInput').dispatchEvent(event);
}

// Select all functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox:not(:disabled)');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Bulk actions
function bulkAction(action) {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    
    if (checkboxes.length === 0) {
        alert('Please select at least one user.');
        return;
    }

    const userIds = Array.from(checkboxes).map(cb => cb.value);
    
    document.getElementById('bulkActionType').value = action;
    document.getElementById('bulkUserIds').value = JSON.stringify(userIds);
    document.getElementById('bulkActionForm').submit();
}

// Toggle user status
function toggleUserStatus(userId) {
    if (confirm('Are you sure you want to change this user\'s status?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${userId}/toggle-status`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

// Delete user
function deleteUser(userId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    const form = document.getElementById('deleteUserForm');
    form.action = `/users/${userId}`;
    modal.show();
}
</script>
@endsection 