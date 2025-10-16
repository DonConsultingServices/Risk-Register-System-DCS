@extends('layouts.sidebar')

@section('title', 'Risk Categories')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Risk Categories</li>
                    </ol>
                </div>
                <h4 class="page-title">Risk Categories</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Manage Risk Categories</h4>
                        <div class="d-flex gap-2">
                            <form class="d-flex" method="GET" action="{{ route('risk-categories.index') }}">
                                <input type="text" class="form-control me-2" name="search" 
                                       placeholder="Search categories..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                            <a href="{{ route('risks.create') }}" class="btn btn-success">
                                <i class="fas fa-exclamation-triangle me-1"></i>Add New Risk
                            </a>
                            <a href="{{ route('risk-categories.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i>Add Category
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <form id="bulkActionsForm" method="POST" action="{{ route('risk-categories.bulk-action') }}">
                                @csrf
                                <div class="mb-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                        <label for="selectAll" class="form-check-label">Select All</label>
                                        <select name="action" class="form-select form-select-sm" style="width: auto;">
                                            <option value="">Bulk Actions</option>
                                            <option value="activate">Activate</option>
                                            <option value="deactivate">Deactivate</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Are you sure?')">
                                            Apply
                                        </button>
                                    </div>
                                </div>
                                <table class="table table-centered table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="headerSelectAll" class="form-check-input">
                                            </th>
                                            <th>Category & Description</th>
                                            <th>Color</th>
                                            <th>Risks</th>
                                            <th>Predefined</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_categories[]" value="{{ $category->id }}" class="form-check-input category-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <div class="avatar-title rounded-circle" 
                                                         style="background-color: {{ $category->color }}; color: white;">
                                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $category->name }}</h6>
                                                    @if($category->description)
                                                        <small class="text-muted">{{ Str::limit($category->description, 60) }}</small>
                                                    @else
                                                        <small class="text-muted fst-italic">No description</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="color-preview me-2" 
                                                     style="width: 20px; height: 20px; background-color: {{ $category->color }}; border-radius: 4px;"></div>
                                                <span class="text-muted">{{ $category->color }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                @php
                                                    $stats = $riskStatistics[$category->id] ?? [];
                                                    $riskCount = $stats['risk_count'] ?? 0;
                                                    $totalPoints = $stats['total_points'] ?? 0;
                                                @endphp
                                                <span class="badge bg-info fs-6">{{ $riskCount }}</span>
                                                <div class="small text-muted mt-1">risks</div>
                                                @if($totalPoints > 0)
                                                    <div class="small text-primary fw-bold">{{ $totalPoints }} pts</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                @php
                                                    $predefinedCount = $stats['predefined_count'] ?? 0;
                                                @endphp
                                                <span class="badge bg-secondary fs-6">{{ $predefinedCount }}</span>
                                                <div class="small text-muted mt-1">predefined</div>
                                                @if($predefinedCount > 0)
                                                    <div class="small text-warning">{{ $stats['risk_levels']['High'] ?? 0 }}H {{ $stats['risk_levels']['Medium'] ?? 0 }}M {{ $stats['risk_levels']['Low'] ?? 0 }}L</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($category->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('risk-categories.show', $category) }}">
                                                        <i class="mdi mdi-eye me-2"></i>View
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('risk-categories.edit', $category) }}" 
                                                           onclick="return confirmEdit('category')">
                                                        <i class="mdi mdi-pencil me-2"></i>Edit
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('risk-categories.destroy', $category) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" 
                                                                    onclick="return confirmDelete('category')">
                                                                <i class="mdi mdi-delete me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </form>
                        </div>
                        
                        @if($categories->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $categories->links('pagination::bootstrap-5') }}
                        </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-folder-open text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">No Categories Found</h5>
                            <p class="text-muted">Get started by creating your first risk category.</p>
                            <a href="{{ route('risk-categories.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i>Create Category
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($categories->count() > 0)
    <!-- Risk Assessment Matrix -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Risk Assessment Matrix</h5>
                    <small class="text-muted">Impact × Likelihood = Risk Points</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Impact \ Likelihood</th>
                                    <th>Low</th>
                                    <th>Medium</th>
                                    <th>High</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-danger fw-bold">High</td>
                                    <td class="table-warning">4 pts</td>
                                    <td class="table-danger">5 pts</td>
                                    <td class="table-danger">5 pts</td>
                                </tr>
                                <tr>
                                    <td class="table-warning fw-bold">Medium</td>
                                    <td class="table-success">2 pts</td>
                                    <td class="table-warning">3 pts</td>
                                    <td class="table-warning">4 pts</td>
                                </tr>
                                <tr>
                                    <td class="table-success fw-bold">Low</td>
                                    <td class="table-success">1 pt</td>
                                    <td class="table-success">1 pt</td>
                                    <td class="table-warning">3 pts</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Risk Levels:</strong> 1-2 pts = Low Risk, 3-4 pts = Medium Risk, 5 pts = High Risk
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
</div>
@endsection

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
        margin: 0 -0.5rem;
        border-radius: 0;
    }
    .card-header {
        padding: 0.75rem 1rem;
    }
    .header-title {
        font-size: 1.1rem;
    }
    .card-body {
        padding: 1rem;
    }
    .d-flex {
        flex-direction: column;
        gap: 0.75rem;
    }
    .form-control {
        padding: 0.75rem;
        font-size: 16px; /* Prevents zoom on iOS */
        border-radius: 8px;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
    }
    .table-responsive {
        font-size: 0.75rem;
        border-radius: 0;
    }
    .table {
        min-width: 100%;
        width: 100%;
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
    .card {
        margin: 0 -0.25rem;
    }
    .card-header {
        padding: 0.5rem 0.75rem;
    }
    .header-title {
        font-size: 1rem;
    }
    .card-body {
        padding: 0.75rem;
    }
    .form-control {
        padding: 0.625rem;
        font-size: 16px;
    }
    .btn {
        padding: 0.625rem 1.25rem;
        font-size: 0.85rem;
    }
    .table {
        min-width: 100%;
        width: 100%;
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
    .header-title {
        font-size: 0.9rem;
    }
    .card-body {
        padding: 0.5rem;
    }
    .form-control {
        padding: 0.5rem;
        font-size: 16px;
    }
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
    .table {
        min-width: 100%;
        width: 100%;
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
    .alert {
        font-size: 0.75rem;
        padding: 0.5rem 0.625rem;
    }
}
</style>

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-lg {
    width: 60px;
    height: 60px;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.color-preview {
    border: 1px solid #dee2e6;
}

/* Enhanced count styling */
.badge.fs-6 {
    font-size: 0.875rem !important;
    padding: 0.5rem 0.75rem;
    min-width: 40px;
}

.text-center .small {
    font-size: 0.75rem;
    line-height: 1.2;
}

/* Category name and description styling */
.avatar-sm + div h6 {
    margin-bottom: 0.25rem;
}

.avatar-sm + div small {
    line-height: 1.2;
    display: block;
}

/* Custom Pagination Styling */
.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    color: var(--logo-dark-blue-primary);
    border-color: var(--logo-border-light);
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 6px;
    margin: 0 2px;
}

.pagination .page-link:hover {
    background-color: var(--logo-dark-blue-primary);
    border-color: var(--logo-dark-blue-primary);
    color: white;
}

.pagination .page-item.active .page-link {
    background-color: var(--logo-dark-blue-primary);
    border-color: var(--logo-dark-blue-primary);
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: var(--logo-text-muted);
    border-color: var(--logo-border-light);
    background-color: transparent;
}

.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    border-radius: 6px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Bulk Actions JavaScript
    const selectAll = document.getElementById('selectAll');
    const headerSelectAll = document.getElementById('headerSelectAll');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    
    // Select all functionality
    function updateSelectAll() {
        const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
        const totalBoxes = categoryCheckboxes.length;
        
        selectAll.checked = checkedBoxes.length === totalBoxes;
        headerSelectAll.checked = checkedBoxes.length === totalBoxes;
    }
    
    // Header select all
    headerSelectAll.addEventListener('change', function() {
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectAll();
    });
    
    // Individual checkboxes
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAll);
    });
    
    // Main select all
    selectAll.addEventListener('change', function() {
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        headerSelectAll.checked = this.checked;
    });
    
    // Enhanced bulk actions with validation
    const bulkActionForm = document.querySelector('form[action*="bulk"]');
    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one category to perform bulk actions.');
                return false;
            }
            
            const action = document.querySelector('select[name="action"]').value;
            if (!action) {
                e.preventDefault();
                alert('Please select an action to perform.');
                return false;
            }
            
            // Confirm destructive actions
            if (['delete', 'deactivate'].includes(action)) {
                const actionText = action === 'delete' ? 'delete' : 'deactivate';
                if (!confirm(`Are you sure you want to ${actionText} ${checkedBoxes.length} selected categor${checkedBoxes.length > 1 ? 'ies' : 'y'}?`)) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    }
    
    // Add visual feedback for bulk actions
    function updateBulkActionUI() {
        const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
        const bulkActions = document.querySelector('.bulk-actions');
        
        if (checkedBoxes.length > 0) {
            if (bulkActions) {
                bulkActions.style.display = 'block';
                bulkActions.classList.add('show');
            }
        } else {
            if (bulkActions) {
                bulkActions.style.display = 'none';
                bulkActions.classList.remove('show');
            }
        }
    }
    
    // Update UI when checkboxes change
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionUI);
    });
    
    // Initial UI update
    updateBulkActionUI();
    

});
</script>
@endpush
