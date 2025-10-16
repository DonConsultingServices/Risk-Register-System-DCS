@extends('layouts.sidebar')

@section('title', 'Risk Category Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('risk-categories.index') }}">Risk Categories</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Risk Category Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg me-3">
                                <div class="avatar-title rounded-circle" 
                                     style="background-color: {{ $category->color }}; color: white; width: 60px; height: 60px; font-size: 1.5rem;">
                                    {{ strtoupper(substr($category->name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <h4 class="header-title mb-1">{{ $category->name }}</h4>
                                <p class="text-muted mb-0">{{ $category->description }}</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3">Category Information</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <p class="mb-0">{{ $category->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <p class="mb-0">{{ $category->description ?? 'No description provided' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Color</label>
                                <div class="d-flex align-items-center">
                                    <div class="color-preview me-2" 
                                         style="width: 30px; height: 30px; background-color: {{ $category->color }}; border-radius: 6px;"></div>
                                    <span>{{ $category->color }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3">Statistics</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                @if($category->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                            
                                                        <div class="mb-3">
                                <label class="form-label fw-bold">Total Risks</label>
                                <p class="mb-0">{{ $riskStats['risk_count'] ?? 0 }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Predefined Risks</label>
                                <p class="mb-0">{{ $riskStats['predefined_count'] ?? 0 }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Total Risk Points</label>
                                <p class="mb-0 text-primary fw-bold">{{ $riskStats['total_points'] ?? 0 }} pts</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Risk Level Distribution</label>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-danger">{{ $riskStats['risk_levels']['High'] ?? 0 }} High</span>
                                    <span class="badge bg-warning">{{ $riskStats['risk_levels']['Medium'] ?? 0 }} Medium</span>
                                    <span class="badge bg-success">{{ $riskStats['risk_levels']['Low'] ?? 0 }} Low</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Created</label>
                                <p class="mb-0">{{ $category->created_at ? $category->created_at->format('M d, Y H:i') : 'Not available' }}</p>
                            </div>
                            
                            @if($category->updated_at && $category->updated_at != $category->created_at)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Updated</label>
                                <p class="mb-0">{{ $category->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('risk-categories.edit', $category) }}" class="btn btn-primary" 
                           onclick="return confirmEdit('category')">
                            <i class="mdi mdi-pencil me-1"></i>Edit Category
                        </a>
                        <a href="{{ route('risks.create', ['category_id' => $category->id]) }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-plus me-1"></i>Add Risk to Category
                        </a>
                        @if($category->risks->count() > 0)
                        <a href="{{ route('risks.index', ['category' => $category->id]) }}" class="btn btn-outline-info">
                            <i class="mdi mdi-list me-1"></i>View All Risks
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($category->predefinedRisks->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="header-title">Predefined Risks</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($category->predefinedRisks->take(5) as $predefinedRisk)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $predefinedRisk->title }}</h6>
                                    <small class="text-muted">{{ Str::limit($predefinedRisk->description, 60) }}</small>
                                </div>
                                <span class="badge bg-{{ $predefinedRisk->risk_level_color }}">{{ $predefinedRisk->risk_level }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($category->predefinedRisks->count() > 5)
                    <div class="text-center mt-3">
                        <small class="text-muted">Showing 5 of {{ $category->predefinedRisks->count() }} predefined risks</small>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Associated Risks -->
    @if($category->risks->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Associated Risks</h5>
                    <a href="{{ route('risks.index', ['category' => $category->id]) }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Risk</th>
                                    <th>Client</th>
                                    <th>Risk Level</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->risks->take(10) as $risk)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $risk->title }}</h6>
                                            <small class="text-muted">{{ Str::limit($risk->description, 50) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($risk->client)
                                            <a href="{{ route('clients.show', $risk->client) }}" class="text-decoration-none">
                                                {{ $risk->client->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">No client</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $risk->risk_rating_color }}">{{ $risk->risk_level }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $risk->status_color }}">{{ $risk->status }}</span>
                                    </td>
                                    <td>
                                        @if($risk->due_date)
                                            {{ $risk->due_date->format('M d, Y') }}
                                            @if($risk->isOverdue())
                                                <span class="badge bg-danger ms-1">Overdue</span>
                                            @elseif($risk->getDaysUntilDue() <= 7)
                                                <span class="badge bg-warning ms-1">Due Soon</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No due date</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('risks.show', $risk) }}" class="btn btn-outline-primary">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('risks.edit', $risk) }}" class="btn btn-outline-secondary" 
                                               onclick="return confirmEdit('risk')">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($category->risks->count() > 10)
                    <div class="text-center mt-3">
                        <small class="text-muted">Showing 10 of {{ $category->risks->count() }} risks</small>
                        <br>
                        <a href="{{ route('risks.index', ['category' => $category->id]) }}" class="btn btn-sm btn-outline-primary mt-2">
                            View All Risks
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="mdi mdi-folder-open text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Risks Found</h5>
                    <p class="text-muted">This category has no associated risks yet.</p>
                    <a href="{{ route('risks.create', ['category_id' => $category->id]) }}" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i>Add First Risk
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Category Timeline -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Category Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                                                 @if($category->created_at)
                         <div class="timeline-item">
                             <div class="timeline-marker bg-primary"></div>
                             <div class="timeline-content">
                                 <h6 class="timeline-title">Category Created</h6>
                                 <p class="timeline-text">{{ $category->created_at->format('M d, Y H:i') }}</p>
                                 <small class="text-muted">Category was added to the system</small>
                             </div>
                         </div>
                         @endif
                         
                         @if($category->updated_at && $category->updated_at != $category->created_at)
                         <div class="timeline-item">
                             <div class="timeline-marker bg-info"></div>
                             <div class="timeline-content">
                                 <h6 class="timeline-title">Category Updated</h6>
                                 <p class="timeline-text">{{ $category->updated_at->format('M d, Y H:i') }}</p>
                                 <small class="text-muted">Information was modified</small>
                             </div>
                         </div>
                         @endif
                        
                        @if($category->risks->count() > 0)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">First Risk Added</h6>
                                <p class="timeline-text">{{ $category->risks->first()->created_at->format('M d, Y') }}</p>
                                <small class="text-muted">{{ $category->risks->count() }} total risks now</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($category->predefinedRisks->count() > 0)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Predefined Risks Added</h6>
                                <p class="timeline-text">{{ $category->predefinedRisks->first()->created_at->format('M d, Y') }}</p>
                                <small class="text-muted">{{ $category->predefinedRisks->count() }} predefined risks</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
@endpush

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
        margin-bottom: 1rem;
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
    .row {
        margin: 0;
    }
    .col-lg-8, .col-lg-4 {
        padding: 0.25rem;
        margin-bottom: 1rem;
    }
    .d-flex {
        flex-direction: column;
        gap: 0.75rem;
    }
    .avatar-lg {
        margin-bottom: 0.5rem;
    }
    .avatar-title {
        width: 50px !important;
        height: 50px !important;
        font-size: 1.25rem !important;
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
    .header-title {
        font-size: 1rem;
    }
    .card-body {
        padding: 0.75rem;
    }
    .col-lg-8, .col-lg-4 {
        padding: 0.125rem;
        margin-bottom: 0.75rem;
    }
    .avatar-title {
        width: 40px !important;
        height: 40px !important;
        font-size: 1rem !important;
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
    .avatar-title {
        width: 35px !important;
        height: 35px !important;
        font-size: 0.875rem !important;
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
    .alert {
        font-size: 0.75rem;
        padding: 0.5rem 0.625rem;
    }
}
</style>

@push('styles')
<style>
/* Avatar and Profile */
.avatar-lg {
    width: 60px;
    height: 60px;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Color Preview */
.color-preview {
    border: 2px solid #e3e6f0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Cards */
.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border-radius: 0.35rem;
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    font-weight: 600;
    color: #5a5c69;
}

.card-body {
    padding: 1.5rem;
}

/* Buttons */
.btn {
    border-radius: 0.35rem;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
    padding: 0.5rem 1rem;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-outline-primary {
    color: #007bff;
    border-color: #007bff;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
    transform: translateY(-1px);
}

.btn-outline-info {
    color: #17a2b8;
    border-color: #17a2b8;
}

.btn-outline-info:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
    transform: translateY(-1px);
}

/* Badges */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    font-weight: 500;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-secondary {
    background-color: #6c757d !important;
}

.bg-danger {
    background-color: #dc3545 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

/* Table */
.table {
    border-radius: 0.35rem;
    overflow: hidden;
}

.table thead th {
    background-color: #f8f9fc;
    border-bottom: 2px solid #e3e6f0;
    color: #5a5c69;
    font-weight: 600;
    padding: 1rem;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #e3e6f0;
}

.table tbody tr:hover {
    background-color: #f8f9fc;
}

/* List Group */
.list-group-item {
    border: 1px solid #e3e6f0;
    padding: 1rem;
    transition: background-color 0.15s ease-in-out;
}

.list-group-item:hover {
    background-color: #f8f9fc;
}

/* Timeline */
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
    color: #5a5c69;
}

.timeline-text {
    margin-bottom: 5px;
    color: #6c757d;
    font-size: 0.875rem;
}

/* Form Labels */
.form-label.fw-bold {
    color: #5a5c69;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

/* Dropdown */
.dropdown-menu {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border-radius: 0.35rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: background-color 0.15s ease-in-out;
}

.dropdown-item:hover {
    background-color: #f8f9fc;
}

.dropdown-item.text-danger:hover {
    background-color: #f8d7da;
    color: #721c24 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .avatar-lg {
        width: 50px;
        height: 50px;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 0.75rem;
    }
    
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -35px;
    }
}
</style>
@endpush
