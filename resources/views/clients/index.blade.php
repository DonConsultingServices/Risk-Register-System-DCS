@extends('layouts.sidebar')

@section('title', 'Client Management - Client Acceptance & Retention Risk Register')
@section('page-title', 'Clients')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 7, 45, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        color: white;
    }
    
    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        margin: 0.25rem 0 0 0;
        font-size: 0.9rem;
    }
    
    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 1.25rem;
        }
        
        .page-subtitle {
            font-size: 0.85rem;
        }
        
        .header-actions {
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-new-client {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-header {
            padding: 1rem;
        }
        
        .page-title {
            font-size: 1.1rem;
        }
        
        .page-subtitle {
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 480px) {
        .page-header {
            padding: 0.75rem;
        }
        
        .page-title {
            font-size: 1rem;
        }
        
        .page-subtitle {
            font-size: 0.75rem;
        }
    }
    
    .btn-new-client {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-new-client:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-1px);
        text-decoration: none;
    }
    
    .filters-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .filters-section h6 {
        color: var(--logo-dark-blue-primary);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1rem;
    }
    
    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
    }
    
    .filter-input {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--logo-dark-blue-primary);
        box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.1);
    }
    
    .filter-actions {
        display: flex;
        gap: 0.75rem;
        align-items: end;
    }
    
    .btn-filter {
        background: var(--logo-dark-blue-primary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        background: var(--logo-dark-blue-hover);
        transform: translateY(-1px);
    }
    
    .btn-clear {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-clear:hover {
        background: #e2e8f0;
        color: #1e293b;
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
    
    .empty-state p {
        margin-bottom: 1.5rem;
    }
    
    .bulk-actions {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0,0,0,0.05);
        display: none;
    }
    
    .bulk-actions.show {
        display: block;
    }
    
    .bulk-actions-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .bulk-count {
        font-weight: 500;
        color: var(--logo-dark-blue-primary);
    }
    
    .bulk-buttons {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-bulk {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-bulk-danger {
        background: #ef4444;
        color: white;
    }
    
    .btn-bulk-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }
    
    .btn-bulk-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-bulk-secondary:hover {
        background: #4b5563;
        transform: translateY(-1px);
    }
    
    .clients-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .table {
        margin-bottom: 0;
    }
    
         .table th {
         background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
         color: white;
         font-weight: 600;
         font-size: 0.75rem;
         text-transform: uppercase;
         letter-spacing: 0.5px;
         border: none;
         padding: 0.5rem 0.25rem;
         vertical-align: middle;
         white-space: nowrap;
     }
     
     .table-info th {
         background: #17a2b8 !important;
         color: white;
         font-size: 0.7rem;
         padding: 0.25rem 0.25rem;
     }
     
     .table td {
         padding: 0.25rem 0.25rem;
         vertical-align: middle;
         border-color: #e2e8f0;
         font-size: 0.8rem;
     }
     
     .table-responsive {
         overflow-x: auto;
         max-width: 100%;
     }
     
          .table {
         min-width: 2000px;
     }
     
     .table-scroll-indicator {
         position: sticky;
         bottom: 0;
         background: rgba(0, 123, 255, 0.1);
         color: #0056b3;
         text-align: center;
         padding: 0.5rem;
         font-size: 0.8rem;
         border-top: 2px solid #0056b3;
         z-index: 1000;
     }
     
     /* Assessment History Styles */
     .assessment-history-container {
         background: #f8fafc;
         border: 1px solid #e2e8f0;
         border-radius: 8px;
         margin: 0.5rem;
         padding: 1rem;
     }
     
     .assessment-history-header {
         border-bottom: 1px solid #e2e8f0;
         padding-bottom: 0.5rem;
         margin-bottom: 1rem;
     }
     
     .assessment-history-header h6 {
         color: var(--logo-dark-blue-primary);
         font-weight: 600;
         margin: 0;
     }
     
     .assessment-history-item {
         background: white;
         border: 1px solid #e2e8f0;
         border-radius: 6px;
         padding: 0.75rem;
         margin-bottom: 0.5rem;
         transition: all 0.3s ease;
     }
     
     .assessment-history-item:hover {
         box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
         transform: translateY(-1px);
     }
     
     .assessment-history-item:last-child {
         margin-bottom: 0;
     }
     
     .client-main-row {
         cursor: pointer;
         transition: background-color 0.3s ease;
     }
     
     .client-main-row:hover {
         background-color: #f8fafc;
     }
     
     .toggle-history-btn {
         background: none;
         border: none;
         color: var(--logo-dark-blue-primary);
         font-size: 0.875rem;
         padding: 0.25rem 0.5rem;
         border-radius: 4px;
         transition: all 0.3s ease;
     }
     
     .toggle-history-btn:hover {
         background-color: rgba(0, 7, 45, 0.1);
     }
     
     .table-container {
         position: relative;
         overflow-x: auto;
         border-radius: 12px;
         box-shadow: 0 2px 10px rgba(0,0,0,0.08);
     }
     
     .table tbody tr:nth-child(even) {
         background-color: #f8fafc;
     }
     
     .table tbody tr:nth-child(odd) {
         background-color: #ffffff;
     }
     
     .table tbody tr:hover {
         background-color: #e3f2fd !important;
     }
     
     .badge {
         font-size: 0.65rem;
         padding: 0.2rem 0.4rem;
         border-radius: 6px;
     }
     
     .table td small {
         font-size: 0.7rem;
     }
     
     .table-primary th {
         background: linear-gradient(135deg, #007bff, #0056b3) !important;
         color: white;
         font-weight: 600;
     }
     
     .table-info th {
         background: #17a2b8 !important;
         color: white;
         font-weight: 500;
     }
     
     .table td {
         border: 1px solid #dee2e6;
     }
     
     .table th {
         border: 1px solid #dee2e6;
     }
    
    .btn-group .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
    }
    
    .form-check-input {
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: var(--logo-dark-blue-primary);
        border-color: var(--logo-dark-blue-primary);
    }
    
    /* Enhanced Mobile-First Responsive Design */
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
        
        .header-actions {
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .filters-section {
            margin: 0 -0.5rem 1.5rem -0.5rem;
            border-radius: 0;
            padding: 1rem;
        }
        
        .filter-row {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .filter-actions {
            justify-content: center;
            margin-top: 0.5rem;
        }
        
        .btn-filter, .btn-clear {
            padding: 0.625rem 1.25rem;
            font-size: 0.85rem;
        }
        
        .clients-table-container {
            margin: 0 -0.5rem;
            border-radius: 0;
        }
        
        .table-responsive {
            font-size: 0.75rem;
            border-radius: 0;
        }
        
        .table {
            min-width: 1200px; /* Reduced from 2000px for better mobile scrolling */
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
        
        .assessment-history-container {
            margin: 0.25rem;
            padding: 0.75rem;
        }
        
        .assessment-history-item {
            padding: 0.5rem;
        }
        
        .assessment-history-item .row {
            margin: 0;
        }
        
        .assessment-history-item .col-md-3,
        .assessment-history-item .col-md-2,
        .assessment-history-item .col-md-1 {
            padding: 0.25rem;
            margin-bottom: 0.5rem;
        }
        
        .bulk-actions {
            margin: 0 -0.5rem 1rem -0.5rem;
            border-radius: 0;
            padding: 0.75rem 1rem;
        }
        
        .bulk-actions-content {
            flex-direction: column;
            gap: 0.75rem;
            text-align: center;
        }
        
        .bulk-buttons {
            justify-content: center;
        }
        
        .btn-bulk {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
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
        
        .filters-section {
            margin: 0 -0.25rem 1rem -0.25rem;
            padding: 0.75rem;
        }
        
        .filter-group {
            margin-bottom: 0.5rem;
        }
        
        .filter-label {
            font-size: 0.8rem;
        }
        
        .filter-input {
            padding: 0.5rem 0.625rem;
            font-size: 0.8rem;
        }
        
        .clients-table-container {
            margin: 0 -0.25rem;
        }
        
        .table {
            min-width: 1000px;
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
        
        .assessment-history-item .col-md-3,
        .assessment-history-item .col-md-2,
        .assessment-history-item .col-md-1 {
            padding: 0.2rem;
            margin-bottom: 0.25rem;
        }
        
        .bulk-actions {
            margin: 0 -0.25rem 0.75rem -0.25rem;
            padding: 0.5rem 0.75rem;
        }
        
        .btn-bulk {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
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
        
        .filters-section {
            padding: 0.5rem;
        }
        
        .filter-input {
            padding: 0.4rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .table {
            min-width: 900px;
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
        
        .btn-bulk {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
        }
    }
    
    /* Dashboard Statistics Cards Styles - Compact Design */
    .stats-card {
        background: white;
        border-radius: 8px;
        padding: 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 4px solid;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background: currentColor;
        opacity: 0.1;
        border-radius: 50%;
        transform: translate(20px, -20px);
    }
    
    .stats-card-primary { border-left-color: var(--logo-dark-blue-primary); color: var(--logo-dark-blue-primary); }
    .stats-card-success { border-left-color: var(--logo-green); color: var(--logo-green); }
    .stats-card-warning { border-left-color: var(--logo-warning); color: var(--logo-warning); }
    .stats-card-danger { border-left-color: var(--logo-danger); color: var(--logo-danger); }
    .stats-card-info { border-left-color: var(--logo-info); color: var(--logo-info); }
    .stats-card-secondary { border-left-color: #6c757d; color: #6c757d; }
    .stats-card-dark { border-left-color: #343a40; color: #343a40; }
    
    .stat-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: #2d3748;
    }
    
    .stat-info p {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0.5rem 0 0 0;
        opacity: 0.8;
    }
    
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    /* Mobile Responsive for Stats Cards */
    @media (max-width: 768px) {
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stats-card {
            padding: 1rem;
        }
        
        .stat-info h3 {
            font-size: 1.5rem;
        }
        
        .stat-icon {
            font-size: 2rem;
        }
        
        .stat-info p {
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .stats-grid {
            gap: 0.75rem;
        }
        
        .stats-card {
            padding: 0.75rem;
        }
        
        .stat-info h3 {
            font-size: 1.25rem;
        }
        
        .stat-icon {
            font-size: 1.75rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Client Management</h1>
                <p class="page-subtitle">Manage client information and risk assessments</p>
            </div>
            <div class="header-actions">
                <!-- New Client button removed - only Risk Register can add clients -->
            </div>
        </div>
    </div>

    <!-- Dashboard Statistics Cards -->
    <div class="stats-grid mb-4">
        <div class="stats-card stats-card-primary">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $totalClients }}</h3>
                    <p>Approved Clients</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card stats-card-success">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $lowRiskClients }}</h3>
                    <p>Low Risk</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card stats-card-warning">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $mediumRiskClients }}</h3>
                    <p>Medium Risk</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card stats-card-danger">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $highRiskClients }}</h3>
                    <p>High Risk</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
        
        @if(($pendingClients ?? 0) > 0)
        <div class="stats-card stats-card-secondary">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $pendingClients }}</h3>
                    <p>Pending</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        @endif
        
        <div class="stats-card stats-card-danger">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $rejectedClients }}</h3>
                    <p>Rejected</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="filters-section">
        <h6><i class="fas fa-filter me-2"></i>Search & Filters</h6>
        <form id="filtersForm">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <input type="text" class="filter-input" id="searchInput" placeholder="Search clients, companies, emails...">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Risk Category</label>
                    <select class="filter-input" id="industryFilter">
                        <option value="">All Categories</option>
                        <option value="SR">Service Risk (SR)</option>
                        <option value="CR">Client Risk (CR)</option>
                        <option value="PR">Payment Risk (PR)</option>
                        <option value="DR">Delivery Risk (DR)</option>
                        <option value="Comprehensive">Comprehensive</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Screening Status</label>
                    <select class="filter-input" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="Done">Done</option>
                        <option value="Not Done">Not Done</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Pass">Pass</option>
                        <option value="Fail">Fail</option>
                        <option value="Pending">Pending</option>
                        <option value="Review Required">Review Required</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Risk Level</label>
                    <select class="filter-input" id="riskLevelFilter">
                        <option value="">All Risk Levels</option>
                        <option value="Low-risk">Low Risk</option>
                        <option value="Medium-risk">Medium Risk</option>
                        <option value="High-risk">High Risk</option>
                        <option value="Very High-risk">Very High Risk</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="button" class="btn-filter" onclick="applyFilters()">
                        <i class="fas fa-search me-2"></i>Apply
                    </button>
                    <button type="button" class="btn-clear" onclick="clearFilters()">
                        <i class="fas fa-times me-2"></i>Clear
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions">
        <div class="bulk-actions-content">
            <span class="bulk-count" id="bulkCount">0 clients selected</span>
            <div class="bulk-buttons">
                <button type="button" class="btn-bulk btn-bulk-secondary" onclick="exportSelected()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <button type="button" class="btn-bulk btn-bulk-danger" onclick="deleteSelected()">
                    <i class="fas fa-trash me-2"></i>Delete
                </button>
            </div>
        </div>
    </div>

         <!-- Table Summary -->
     <div class="alert alert-info" role="alert">
         <div class="d-flex align-items-center">
             <i class="fas fa-info-circle me-3" style="font-size: 1.5rem;"></i>
             <div>
                 <h6 class="alert-heading mb-1">Comprehensive Risk Assessment Dashboard</h6>
                 <p class="mb-0">This table displays detailed risk assessments across all categories: <strong>Client Risk (CR)</strong>, <strong>Service Risk (SR)</strong>, <strong>Payment Risk (PR)</strong>, and <strong>Delivery Risk (DR)</strong>. Each client's complete risk profile is shown with impact, likelihood, and risk ratings for regulatory compliance. <strong>History tracking:</strong> Clients with multiple assessments show a history badge <span class="badge bg-info ms-1"><i class="fas fa-history me-1"></i>N</span> indicating the number of assessments.</p>
             </div>
         </div>
     </div>
     
     <!-- Clients Table -->
     <div class="clients-table-container">
         @if($clients->count() > 0)
             <div class="table-container">
                 <div class="table-responsive">
                     <table class="table table-bordered table-hover" id="clientsTable">
                                         <thead>
                         <!-- Main Header Row -->
                         <tr class="table-primary">
                             <th class="text-center" style="width: 40px;">
                                 <input type="checkbox" id="selectAll" class="form-check-input">
                             </th>
                             <th class="text-center" style="width: 50px;">No.</th>
                             <th class="text-center" style="width: 120px;">Client Name</th>
                             <th class="text-center" colspan="2" style="width: 160px;">Screening Status</th>
                             <th class="text-center" colspan="4" style="width: 320px;">Category of Client</th>
                             <th class="text-center" colspan="4" style="width: 320px;">Requested Services?</th>
                             <th class="text-center" colspan="4" style="width: 320px;">Anticipated Payment Option?</th>
                             <th class="text-center" colspan="5" style="width: 400px;">Anticipated Service Delivery Method?</th>
                             <th class="text-center" style="width: 80px;">Overall Risk Points</th>
                             <th class="text-center" style="width: 100px;">Overall Risk Rating</th>
                             <th class="text-center" style="width: 120px;">Client Acceptance</th>
                             <th class="text-center" style="width: 120px;">Ongoing Monitoring</th>
                             <th class="text-center" style="width: 100px;">DCS Risk Appetite</th>
                             <th class="text-center" style="width: 120px;">DCS Comments</th>
                             <th class="text-center" style="width: 100px;">Actions</th>
                         </tr>
                         <!-- Sub-Header Row -->
                         <tr class="table-info">
                             <th></th>
                             <th></th>
                             <th></th>
                             <th class="text-center" style="width: 80px;">Date</th>
                             <th class="text-center" style="width: 80px;">Result</th>
                             <th class="text-center" style="width: 80px;">Risk ID</th>
                             <th class="text-center" style="width: 80px;">Description</th>
                             <th class="text-center" style="width: 80px;">Impact</th>
                             <th class="text-center" style="width: 80px;">Risk Rating</th>
                             <th class="text-center" style="width: 80px;">Risk ID</th>
                             <th class="text-center" style="width: 80px;">Description</th>
                             <th class="text-center" style="width: 80px;">Impact</th>
                             <th class="text-center" style="width: 80px;">Risk Rating</th>
                             <th class="text-center" style="width: 80px;">Risk ID</th>
                             <th class="text-center" style="width: 80px;">Description</th>
                             <th class="text-center" style="width: 80px;">Impact</th>
                             <th class="text-center" style="width: 80px;">Risk Rating</th>
                             <th class="text-center" style="width: 80px;">Risk ID</th>
                             <th class="text-center" style="width: 80px;">Description</th>
                             <th class="text-center" style="width: 80px;">Impact</th>
                             <th class="text-center" style="width: 80px;">Likelihood</th>
                             <th class="text-center" style="width: 80px;">Risk Rating</th>
                             <th></th>
                             <th></th>
                             <th></th>
                             <th></th>
                             <th></th>
                             <th></th>
                         </tr>
                    <tbody>
                        @foreach($clients as $index => $client)
                        <tr data-client-name="{{ $client->name }}" class="client-main-row">
                            <!-- Checkbox and Basic Info -->
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input client-checkbox" value="{{ $client->id }}" data-client-name="{{ $client->name }}">
                                <small class="text-muted d-block">ID: {{ $client->id }}</small>
                            </td>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        {{ $client->name }}
                                        @php
                                            // Check if this client has multiple assessments
                                            $clientName = $client->name;
                                            $assessmentCount = \App\Models\Client::where('name', $clientName)
                                                ->where('assessment_status', 'approved')
                                                ->where('deleted_at', null)
                                                ->count();
                                        @endphp
                                        @if($assessmentCount > 1)
                                            <small class="badge bg-info ms-1" title="This client has {{ $assessmentCount }} assessments">
                                                <i class="fas fa-history me-1"></i>{{ $assessmentCount }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Client Screening -->
                            <td class="text-center">
                                @if($client->client_screening_date)
                                    {{ \Carbon\Carbon::parse($client->client_screening_date)->format('d-M-Y') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($client->client_screening_result)
                                    <span class="badge bg-info">{{ $client->client_screening_result }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Category of Client (CR) -->
                            <td class="text-center">
                                @if(isset($client->cr_risk_id) && $client->cr_risk_id)
                                    <span class="badge bg-primary">{{ $client->cr_risk_id }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->cr_risk_name) && $client->cr_risk_name)
                                    <small class="text-muted">{{ Str::limit($client->cr_risk_name, 20) }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->cr_impact) && $client->cr_impact)
                                    @php
                                        $crImpactColor = $client->cr_impact === 'High' ? 'danger' : 
                                                       ($client->cr_impact === 'Medium' ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $crImpactColor }}">{{ $client->cr_impact }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->cr_risk_rating) && $client->cr_risk_rating)
                                    @php
                                        $crRatingColor = str_contains($client->cr_risk_rating, 'High') ? 'danger' : 
                                                       (str_contains($client->cr_risk_rating, 'Medium') ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $crRatingColor }}">{{ $client->cr_risk_rating }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Requested Services? (SR) -->
                            <td class="text-center">
                                @if(isset($client->sr_risk_id) && $client->sr_risk_id)
                                    <span class="badge bg-primary">{{ $client->sr_risk_id }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->sr_risk_name) && $client->sr_risk_name)
                        <small class="text-muted">{{ Str::limit($client->sr_risk_name, 20) }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->sr_impact) && $client->sr_impact)
                                    @php
                                        $srImpactColor = $client->sr_impact === 'High' ? 'danger' : 
                                                       ($client->sr_impact === 'Medium' ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $srImpactColor }}">{{ $client->sr_impact }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->sr_risk_rating) && $client->sr_risk_rating)
                                    @php
                                        $srRatingColor = str_contains($client->sr_risk_rating, 'High') ? 'danger' : 
                                                       (str_contains($client->sr_risk_rating, 'Medium') ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $srRatingColor }}">{{ $client->sr_risk_rating }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Anticipated Payment Option? (PR) -->
                            <td class="text-center">
                                @if(isset($client->pr_risk_id) && $client->pr_risk_id)
                                    <span class="badge bg-primary">{{ $client->pr_risk_id }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->pr_risk_name) && $client->pr_risk_name)
                        <small class="text-muted">{{ Str::limit($client->pr_risk_name, 20) }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->pr_impact) && $client->pr_impact)
                                    @php
                                        $prImpactColor = $client->pr_impact === 'High' ? 'danger' : 
                                                       ($client->pr_impact === 'Medium' ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $prImpactColor }}">{{ $client->pr_impact }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->pr_risk_rating) && $client->pr_risk_rating)
                                    @php
                                        $prRatingColor = str_contains($client->pr_risk_rating, 'High') ? 'danger' : 
                                                       (str_contains($client->pr_risk_rating, 'Medium') ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $prRatingColor }}">{{ $client->pr_risk_rating }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Anticipated Service Delivery Method? (DR) -->
                            <td class="text-center">
                                @if(isset($client->dr_risk_id) && $client->dr_risk_id)
                                    <span class="badge bg-primary">{{ $client->dr_risk_id }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->dr_risk_name) && $client->dr_risk_name)
                                    <small class="text-muted">{{ Str::limit($client->dr_risk_name, 20) }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->dr_impact) && $client->dr_impact)
                                    @php
                                        $drImpactColor = $client->dr_impact === 'High' ? 'danger' : 
                                                       ($client->dr_impact === 'Medium' ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $drImpactColor }}">{{ $client->dr_impact }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->dr_likelihood) && $client->dr_likelihood)
                                    @php
                                        $drLikelihoodColor = $client->dr_likelihood === 'High' ? 'danger' : 
                                                           ($client->dr_likelihood === 'Medium' ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $drLikelihoodColor }}">{{ $client->dr_likelihood }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->dr_risk_rating) && $client->dr_risk_rating)
                                    @php
                                        $drRatingColor = str_contains($client->dr_risk_rating, 'High') ? 'danger' : 
                                                       (str_contains($client->dr_risk_rating, 'Medium') ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $drRatingColor }}">{{ $client->dr_risk_rating }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Overall Assessment Fields -->
                            <td class="text-center">
                                @if(isset($client->total_points) && $client->total_points)
                                    <span class="badge bg-{{ $client->total_points >= 15 ? 'danger' : ($client->total_points >= 9 ? 'warning' : 'success') }}">
                                        {{ $client->total_points }}
                                    </span>
                                @elseif($client->overall_risk_points)
                                    <span class="badge bg-{{ $client->overall_risk_points >= 15 ? 'danger' : ($client->overall_risk_points >= 9 ? 'warning' : 'success') }}">
                                        {{ $client->overall_risk_points }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->cra_overall_risk_rating) && $client->cra_overall_risk_rating)
                                    @php
                                        $overallRatingColor = str_contains($client->cra_overall_risk_rating, 'High') || str_contains($client->cra_overall_risk_rating, 'Critical') ? 'danger' : 
                                                           (str_contains($client->cra_overall_risk_rating, 'Medium') ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $overallRatingColor }}">{{ $client->cra_overall_risk_rating }}</span>
                                @elseif($client->overall_risk_rating)
                                    @php
                                        $overallRatingColor = str_contains($client->overall_risk_rating, 'High') || str_contains($client->overall_risk_rating, 'Critical') ? 'danger' : 
                                                           (str_contains($client->overall_risk_rating, 'Medium') ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $overallRatingColor }}">{{ $client->overall_risk_rating }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->comprehensive_acceptance) && $client->comprehensive_acceptance)
                                    @php
                                        $acceptanceColor = $client->comprehensive_acceptance === 'Accept client' ? 'success' : 
                                                         ($client->comprehensive_acceptance === 'Reject client' ? 'danger' : 'warning');
                                    @endphp
                                    <span class="badge bg-{{ $acceptanceColor }}">{{ $client->comprehensive_acceptance }}</span>
                                @elseif($client->client_acceptance)
                                    @php
                                        $acceptanceColor = $client->client_acceptance === 'Accept client' ? 'success' : 
                                                         ($client->client_acceptance === 'Reject client' ? 'danger' : 'warning');
                                    @endphp
                                    <span class="badge bg-{{ $acceptanceColor }}">{{ $client->client_acceptance }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($client->comprehensive_monitoring) && $client->comprehensive_monitoring)
                                    <span class="badge bg-info">{{ $client->comprehensive_monitoring }}</span>
                                @elseif($client->ongoing_monitoring)
                                    <span class="badge bg-info">{{ $client->ongoing_monitoring }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($client->dcs_risk_appetite)
                                    <span class="badge bg-secondary">{{ $client->dcs_risk_appetite }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($client->dcs_comments)
                                    <small>{{ Str::limit($client->dcs_comments, 25) }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary view-client-details" title="View Details" data-url="{{ route('clients.modal', $client) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" title="View Complete History" 
                                            onclick="viewClientHistory('{{ $client->name }}', {{ $client->id }})">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-warning" title="Edit" 
                                       onclick="return confirmEdit('client')">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" 
                                                onclick="return confirmDelete('client')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        
                        {{-- Assessment History Row (Collapsible) - Disabled for performance optimization
                        @if($client->assessment_history->count() > 0)
                        <tr class="assessment-history-row" style="display: none;" data-client-name="{{ $client->name }}">
                            <td colspan="25" class="p-0">
                                <div class="assessment-history-container">
                                    <div class="assessment-history-header">
                                        <h6 class="mb-3">
                                            <i class="fas fa-history me-2"></i>
                                            Previous Assessment History for {{ $client->name }}
                                        </h6>
                                    </div>
                                    <div class="assessment-history-content">
                                        {{-- @foreach($client->assessment_history as $historyIndex => $historyAssessment) --}}
                                        {{-- <div class="assessment-history-item">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Assessment #{{ $historyIndex + 2 }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $historyAssessment->created_at->format('M d, Y H:i') }}</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge bg-{{ $historyAssessment->overall_risk_rating == 'High' ? 'danger' : ($historyAssessment->overall_risk_rating == 'Medium' ? 'warning' : 'success') }}">
                                                        {{ $historyAssessment->overall_risk_rating }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">{{ $historyAssessment->overall_risk_points }} points</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <small>{{ $historyAssessment->client_acceptance ?? 'N/A' }}</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <small>{{ $historyAssessment->ongoing_monitoring ?? 'N/A' }}</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <small>{{ $historyAssessment->dcs_risk_appetite ?? 'N/A' }}</small>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="{{ route('clients.show', $historyAssessment) }}" class="btn btn-sm btn-outline-primary" title="View Assessment">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- @endforeach --}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {{-- @endif --}}
                        @endforeach
                    </tbody>
                </table>
                <div class="table-scroll-indicator">
                    <i class="fas fa-arrows-alt-h me-2"></i>
                    Scroll horizontally to see all risk assessment details
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h5>No Clients Found</h5>
                <p>No clients match your current filters. Try adjusting your search criteria or add new clients through the Risk Register.</p>
                <a href="{{ route('risks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Client via Risk Register
                </a>
            </div>
        @endif
    </div>

    <!-- Client Details Modal -->
    <div class="modal fade" id="clientDetailsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user me-2"></i>Client Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="height:80vh;">
                    <iframe id="clientDetailsFrame" src="about:blank" style="border:0; width:100%; height:100%;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="openInNewTab()">
                        <i class="fas fa-external-link-alt me-1"></i>Open in New Tab
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('clientDetailsModal');
        const frame = document.getElementById('clientDetailsFrame');

        function openDetails(url) {
            if (!modalEl || !frame) { window.open(url, '_blank'); return; }
            frame.src = url;
            // Use Bootstrap if available; otherwise fallback to new tab
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                // Clear iframe on close to release focus and avoid blocking UI
                modalEl.addEventListener('hidden.bs.modal', function onHide(){
                    frame.src = 'about:blank';
                    modalEl.removeEventListener('hidden.bs.modal', onHide);
                });
                modal.show();
            } else {
                window.open(url, '_blank');
            }
        }

        document.querySelectorAll('.view-client-details').forEach(function(btn){
            btn.addEventListener('click', function(){
                const url = this.getAttribute('data-url');
                openDetails(url);
            });
        });
    });

    // Function to open current modal content in new tab
    function openInNewTab() {
        const frame = document.getElementById('clientDetailsFrame');
        if (frame && frame.src && frame.src !== 'about:blank') {
            window.open(frame.src, '_blank');
        }
    }
    </script>

    <!-- Pagination -->
    @if($clients->count() > 0)
        <div class="d-flex justify-content-center">
            {{ $clients->links() }}
        </div>
    @endif
    
    <!-- Hidden Forms for Bulk Operations -->
    <form id="bulkDeleteForm" method="POST" action="{{ route('clients.bulk-delete') }}" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="client_ids" id="bulkDeleteIds">
    </form>
    
    <form id="bulkExportForm" method="POST" action="{{ route('clients.bulk-export') }}" style="display: none;">
        @csrf
        <input type="hidden" name="client_ids" id="bulkExportIds">
    </form>
</div>

<script>
let selectedClients = new Set();

// Initialize checkboxes for bulk selection
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const clientCheckboxes = document.querySelectorAll('.client-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            clientCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                const clientId = checkbox.value;
                if (this.checked) {
                    selectedClients.add(clientId);
                } else {
                    selectedClients.delete(clientId);
                }
            });
            updateBulkActions();
        });
    }
    
    // Individual checkbox functionality
    clientCheckboxes.forEach((checkbox, index) => {
        checkbox.addEventListener('change', function() {
            const clientId = this.value;
            const clientName = this.getAttribute('data-client-name');
            
            if (this.checked) {
                selectedClients.add(clientId);
            } else {
                selectedClients.delete(clientId);
            }
            
            // Update select all checkbox
            if (selectAllCheckbox) {
                const allChecked = Array.from(clientCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(clientCheckboxes).some(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
            
            updateBulkActions();
        });
    });
});

function updateBulkActions() {
    const bulkActions = document.getElementById('bulkActions');
    const bulkCount = document.getElementById('bulkCount');
    
    if (selectedClients.size > 0) {
        bulkActions.classList.add('show');
        bulkCount.textContent = `${selectedClients.size} client${selectedClients.size > 1 ? 's' : ''} selected`;
    } else {
        bulkActions.classList.remove('show');
    }
}

function applyFilters() {
    const searchInput = document.getElementById('searchInput');
    const industryFilter = document.getElementById('industryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const riskLevelFilter = document.getElementById('riskLevelFilter');
    const clientsTable = document.getElementById('clientsTable');
    
    if (!searchInput || !industryFilter || !statusFilter || !riskLevelFilter) {
        console.warn('Filter elements not found');
        return;
    }
    
    if (!clientsTable) {
        console.warn('Clients table not found');
        return;
    }
    
    const searchTerm = searchInput.value.toLowerCase();
    const industry = industryFilter.value;
    const status = statusFilter.value;
    const riskLevel = riskLevelFilter.value;
    
    // Add visual feedback for active filters
    updateFilterIndicators(searchTerm, industry, status, riskLevel);
    
    const tableRows = document.querySelectorAll('#clientsTable tbody tr');
    
    if (tableRows.length === 0) {
        console.warn('No table rows found');
        return;
    }
    
    tableRows.forEach(row => {
        // Get data from correct columns for the new table structure
        const clientNameCell = row.querySelector('td:nth-child(3)');
        const clientName = clientNameCell ? clientNameCell.textContent.toLowerCase() : '';
        const clientRiskCategory = row.querySelector('td:nth-child(8) .badge')?.textContent || 'N/A'; // CR Risk ID column
        const clientStatus = row.querySelector('td:nth-child(5) .badge')?.textContent || 'N/A'; // Client Screening Result column
        const clientRiskLevel = row.querySelector('td:nth-child(32) .badge')?.textContent || 'N/A'; // Overall Risk Rating column
        
        let show = true;
        
        // Search filter - search across multiple fields
        if (searchTerm) {
            const crRiskId = row.querySelector('td:nth-child(8) .badge')?.textContent.toLowerCase() || '';
            const srRiskId = row.querySelector('td:nth-child(16) .badge')?.textContent.toLowerCase() || '';
            const prRiskId = row.querySelector('td:nth-child(24) .badge')?.textContent.toLowerCase() || '';
            const drRiskId = row.querySelector('td:nth-child(32) .badge')?.textContent.toLowerCase() || '';
            
            const searchInFields = clientName + ' ' + crRiskId + ' ' + srRiskId + ' ' + prRiskId + ' ' + drRiskId;
            if (!searchInFields.includes(searchTerm)) {
                show = false;
            }
        }
        
        // Risk Category filter (using CR Risk ID)
        if (industry && clientRiskCategory !== industry) {
            show = false;
        }
        
        // Screening Status filter (using Client Screening Result)
        if (status && clientStatus !== status) {
            show = false;
        }
        
        // Risk Level filter
        if (riskLevel && clientRiskLevel !== riskLevel) {
            show = false;
        }
        
        row.style.display = show ? 'table-row' : 'none';
    });
    
    // Update row numbers for visible rows
    updateRowNumbers();
    
    // Show results count
    const visibleRows = document.querySelectorAll('#clientsTable tbody tr:not([style*="display: none"])');
    const totalRows = document.querySelectorAll('#clientsTable tbody tr').length;
    
    // Add results count display
    let resultsDisplay = document.getElementById('resultsCount');
    if (!resultsDisplay) {
        resultsDisplay = document.createElement('div');
        resultsDisplay.id = 'resultsCount';
        resultsDisplay.className = 'text-muted text-center mt-2';
        document.getElementById('clientsTable').parentNode.appendChild(resultsDisplay);
    }
    
    if (visibleRows.length === totalRows) {
        resultsDisplay.textContent = `Showing all ${totalRows} clients`;
    } else {
        resultsDisplay.textContent = `Showing ${visibleRows.length} of ${totalRows} clients`;
    }
}

function updateFilterIndicators(searchTerm, industry, status, riskLevel) {
    // Update filter button appearance based on active filters
    const filterBtn = document.querySelector('.btn-filter');
    const hasActiveFilters = searchTerm || industry || status || riskLevel;
    
    if (filterBtn) {
        if (hasActiveFilters) {
            filterBtn.innerHTML = '<i class="fas fa-filter me-2"></i>Filters Active';
            filterBtn.classList.add('btn-warning');
            filterBtn.classList.remove('btn-primary');
        } else {
            filterBtn.innerHTML = '<i class="fas fa-search me-2"></i>Apply';
            filterBtn.classList.remove('btn-warning');
            filterBtn.classList.add('btn-primary');
        }
    }
}

function clearFilters() {
    const searchInput = document.getElementById('searchInput');
    const industryFilter = document.getElementById('industryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const riskLevelFilter = document.getElementById('riskLevelFilter');
    
    if (searchInput) searchInput.value = '';
    if (industryFilter) industryFilter.value = '';
    if (statusFilter) statusFilter.value = '';
    if (riskLevelFilter) riskLevelFilter.value = '';
    
    const tableRows = document.querySelectorAll('#clientsTable tbody tr');
    if (tableRows.length > 0) {
        tableRows.forEach(row => {
            row.style.display = 'table-row';
        });
    }
    
    updateRowNumbers();
    
    // Update results count
    const totalRows = tableRows.length;
    let resultsDisplay = document.getElementById('resultsCount');
    if (resultsDisplay) {
        resultsDisplay.textContent = `Showing all ${totalRows} clients`;
    }
    
    // Reset filter button appearance
    updateFilterIndicators('', '', '', '');
}

function updateRowNumbers() {
    const visibleRows = document.querySelectorAll('#clientsTable tbody tr:not([style*="display: none"])');
    visibleRows.forEach((row, index) => {
        const numberCell = row.querySelector('td:nth-child(2)');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

function exportSelected() {
    if (selectedClients.size === 0) {
        alert('Please select clients to export');
        return;
    }
    
    // Set the client IDs in the hidden form
    const bulkExportIds = document.getElementById('bulkExportIds');
    if (bulkExportIds) {
        bulkExportIds.value = Array.from(selectedClients).join(',');
    }
    
    // Show loading state
    const exportBtn = document.querySelector('.btn-bulk-secondary');
    if (exportBtn) {
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Exporting...';
        exportBtn.disabled = true;
        
        // Reset button after a delay
        setTimeout(() => {
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        }, 2000);
    }
    
    // Submit the form
    const bulkExportForm = document.getElementById('bulkExportForm');
    if (bulkExportForm) {
        bulkExportForm.submit();
    }
}

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

function deleteSelected() {
    if (selectedClients.size === 0) {
        alert('Please select clients to delete');
        return;
    }
    
    if (confirmDelete('client', selectedClients.size)) {
        // Set the client IDs in the hidden form
        const bulkDeleteIds = document.getElementById('bulkDeleteIds');
        if (bulkDeleteIds) {
            bulkDeleteIds.value = Array.from(selectedClients).join(',');
        }
        
        // Show loading state
        const deleteBtn = document.querySelector('.btn-bulk-danger');
        if (deleteBtn) {
            const originalText = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';
            deleteBtn.disabled = true;
            
            // Reset button after a delay
            setTimeout(() => {
                deleteBtn.innerHTML = originalText;
                deleteBtn.disabled = false;
            }, 2000);
        }
        
        // Submit the form
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        if (bulkDeleteForm) {
            bulkDeleteForm.submit();
        }
    }
}


// Search functionality with debouncing for better performance
let searchTimeout;

// Initialize all event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 300); // Wait 300ms after user stops typing
        });
    }

    // Filter change events
    const industryFilter = document.getElementById('industryFilter');
    if (industryFilter) {
        industryFilter.addEventListener('change', applyFilters);
    }
    
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
    
    const riskLevelFilter = document.getElementById('riskLevelFilter');
    if (riskLevelFilter) {
        riskLevelFilter.addEventListener('change', applyFilters);
    }

    // Initialize filter indicators
    updateFilterIndicators('', '', '', '');
});

// Toggle assessment history visibility
function toggleAssessmentHistory(clientName) {
    const historyRow = document.querySelector(`tr.assessment-history-row[data-client-name="${clientName}"]`);
    const toggleIcon = document.getElementById(`toggle-icon-${clientName}`);
    
    if (historyRow && toggleIcon) {
        if (historyRow.style.display === 'none' || historyRow.style.display === '') {
            historyRow.style.display = 'table-row';
            toggleIcon.classList.remove('fa-chevron-down');
            toggleIcon.classList.add('fa-chevron-up');
        } else {
            historyRow.style.display = 'none';
            toggleIcon.classList.remove('fa-chevron-up');
            toggleIcon.classList.add('fa-chevron-down');
        }
    }
}

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
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(document.getElementById('clientHistoryModal'));
        modal.show();
    } else {
        console.error('Bootstrap Modal is not available');
    }
    
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
@endsection
