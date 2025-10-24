

<?php $__env->startSection('title', 'Risk Reports'); ?>
<?php $__env->startSection('page-title', 'Reports'); ?>

<?php $__env->startSection('content'); ?>
<style>
    @media print {
        body {
            font-size: 12px;
            line-height: 1.4;
        }
        
        .no-print {
            display: none !important;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .print-header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }
        
        .print-header .subtitle {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        
        .print-summary {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        
        .print-summary h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
        }
        
        .print-summary-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }
        
        .print-stat-item {
            text-align: center;
        }
        
        .print-stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .print-stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        
        .print-table th, .print-table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        
        .print-table th {
            background-color: #333;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        
        .print-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .print-risk-high { background-color: #ffebee; }
        .print-risk-medium { background-color: #fff3e0; }
        .print-risk-low { background-color: #e8f5e8; }
        
        .print-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #333;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .print-risk-rating {
            padding: 2px 4px;
            border-radius: 2px;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
        }
        
        .print-rating-high { background-color: #e74c3c; color: white; }
        .print-rating-medium { background-color: #f39c12; color: white; }
        .print-rating-low { background-color: #27ae60; color: white; }
        .print-rating-critical { background-color: #8e44ad; color: white; }
        
        .page-break {
            page-break-before: always;
        }
    }
</style>
<style>
    .page-header {
        background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 7, 45, 0.2);
    }
    
    /* Mobile-First Reports Optimizations */
    @media (max-width: 768px) {
        .page-header {
            margin: 0 -1rem 1.5rem -1rem;
            border-radius: 0;
            padding: 1rem;
        }
        
        .page-title {
            font-size: 1.25rem;
        }
        
        .page-subtitle {
            font-size: 0.85rem;
        }
        
        /* Mobile Stats Grid */
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-card p {
            font-size: 0.85rem;
            margin: 0;
        }
        
        /* Mobile Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .quick-action-btn {
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .quick-action-btn i {
            font-size: 1.5rem;
        }
        
        .quick-action-btn span {
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        /* Mobile Filter Section */
        .filter-section {
            margin-bottom: 1.5rem;
        }
        
        .filter-section .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .filter-section .card-header {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .filter-section .card-header h6 {
            font-size: 1rem;
            margin: 0;
        }
        
        .filter-section .card-body {
            padding: 1rem;
        }
        
        .filter-section .row {
            margin: 0;
        }
        
        .filter-section .col-md-3 {
            margin-bottom: 1rem;
        }
        
        .filter-section .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .filter-section .form-select {
            font-size: 16px; /* Prevents zoom on iOS */
            padding: 0.75rem;
            border-radius: 8px;
        }
        
        .filter-section .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        /* Mobile Table Optimizations */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1rem;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            font-size: 0.8rem;
            margin-bottom: 0;
            min-width: 600px; /* Ensure horizontal scroll */
        }
        
        .table th,
        .table td {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
            white-space: nowrap;
        }
        
        .table th {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table td {
            font-size: 0.8rem;
        }
        
        /* Mobile Table Actions */
        .btn-group .btn {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
            border-radius: 4px;
        }
        
        /* Mobile Cards */
        .card {
            margin-bottom: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .card-header h5 {
            font-size: 1rem;
            margin: 0;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        /* Mobile Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        
        /* Mobile Badges */
        .badge {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem;
        }
        
        /* Mobile Alerts */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        /* Mobile Spacing */
        .mb-4 {
            margin-bottom: 1rem !important;
        }
        
        .mb-3 {
            margin-bottom: 0.75rem !important;
        }
        
        .mt-4 {
            margin-top: 1rem !important;
        }
        
        .p-3 {
            padding: 0.75rem !important;
        }
        
        .p-4 {
            padding: 1rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .page-header {
            margin: 0 -0.75rem 1rem -0.75rem;
            padding: 0.75rem;
        }
        
        .page-title {
            font-size: 1.1rem;
        }
        
        .page-subtitle {
            font-size: 0.8rem;
        }
        
        .stats-grid {
            gap: 0.75rem;
        }
        
        .stat-card {
            padding: 1rem;
        }
        
        .stat-card h3 {
            font-size: 1.25rem;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .quick-action-btn {
            padding: 0.75rem;
        }
        
        .quick-action-btn i {
            font-size: 1.25rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.75rem;
        }
        
        .card-header,
        .card-body {
            padding: 0.75rem;
        }
        
        .filter-section .col-md-3 {
            margin-bottom: 0.75rem;
        }
    }
    
    @media (max-width: 480px) {
        .page-header {
            margin: 0 -0.5rem 1rem -0.5rem;
            padding: 0.5rem;
        }
        
        .page-title {
            font-size: 1rem;
        }
        
        .stat-card {
            padding: 0.75rem;
        }
        
        .stat-card h3 {
            font-size: 1.1rem;
        }
        
        .table th,
        .table td {
            padding: 0.4rem 0.2rem;
            font-size: 0.7rem;
        }
        
        .card-header,
        .card-body {
            padding: 0.5rem;
        }
        
        .filter-section .col-md-3 {
            margin-bottom: 0.5rem;
        }
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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stat-card.primary { border-left-color: var(--logo-dark-blue-primary); }
    .stat-card.danger { border-left-color: var(--logo-danger); }
    .stat-card.warning { border-left-color: var(--logo-warning); }
    .stat-card.info { border-left-color: var(--logo-info); }
    .stat-card.success { border-left-color: #10b981; }
    
    .filter-section {
        margin-bottom: 2rem;
    }
    
    .filter-section .card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .filter-section .card-header {
        background: var(--logo-dark-blue-primary);
        color: white;
        border: none;
        border-radius: 12px 12px 0 0;
    }
    
    .filter-section .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .filter-section .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
    }
    
    .filter-section .form-select:focus {
        outline: none;
        border-color: var(--logo-dark-blue-primary);
        box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.1);
    }
    
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
    
    .risks-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .risks-table-card .card-header {
        background: var(--logo-dark-blue-primary);
        color: white;
        padding: 1rem 1.5rem;
        border: none;
    }
    
    .risks-table-card .card-header h5 {
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
    
    .risk-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--logo-dark-blue-primary);
        background: rgba(0, 7, 45, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
    }
    
    .risk-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    
    .risk-description {
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
    
    .client-link {
        color: var(--logo-dark-blue-primary);
        text-decoration: none;
        font-weight: 500;
    }
    
    .client-link:hover {
        color: var(--logo-dark-blue-hover);
        text-decoration: underline;
    }
    
    .due-date {
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .due-date.overdue {
        color: #ef4444;
        font-weight: 600;
    }
    
    .due-date.no-date {
        color: #94a3b8;
        font-style: italic;
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
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Risk Reports</h1>
                <p class="page-subtitle">Comprehensive overview of registered risks and their status</p>
            </div>
        </div>
    </div>

    <!-- Executive Summary -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-content">
                <div class="stat-info">
                    <h3><?php echo e($totalRisks ?? 0); ?></h3>
                    <p>Total Risk Assessments</p>
                </div>
                <i class="fas fa-shield-alt stat-icon" style="color: var(--logo-dark-blue-primary);"></i>
            </div>
        </div>

        <div class="stat-card danger">
            <div class="stat-content">
                <div class="stat-info">
                    <h3><?php echo e($highRisks ?? 0); ?></h3>
                    <p>High-Risk Clients</p>
                </div>
                <i class="fas fa-exclamation-triangle stat-icon" style="color: var(--logo-danger);"></i>
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-content">
                <div class="stat-info">
                    <h3><?php echo e($overdueRisks ?? 0); ?></h3>
                    <p>Requires Attention</p>
                </div>
                <i class="fas fa-clock stat-icon" style="color: var(--logo-warning);"></i>
            </div>
        </div>
    </div>

    <!-- Client Statistics Section -->
    <div class="mb-4">
        <h5 class="text-primary mb-3">
            <i class="fas fa-users me-2"></i>Client Statistics
            <small class="text-muted">(Approved clients only)</small>
        </h5>
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3><?php echo e($clientStats['totalClients'] ?? 0); ?></h3>
                        <p>Approved Clients</p>
                    </div>
                    <i class="fas fa-user-check stat-icon" style="color: var(--logo-dark-blue-primary);"></i>
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3><?php echo e($clientStats['pendingClients'] ?? 0); ?></h3>
                        <p>Pending Approvals</p>
                    </div>
                    <i class="fas fa-clock stat-icon" style="color: var(--logo-warning);"></i>
                </div>
            </div>

            <div class="stat-card danger">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3><?php echo e($clientStats['rejectedClients'] ?? 0); ?></h3>
                        <p>Rejected Clients</p>
                    </div>
                    <i class="fas fa-user-times stat-icon" style="color: var(--logo-danger);"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Quick Actions -->
    <div class="row mb-4 no-print">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-download me-2"></i>Export & Compliance Reports</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo e(route('risks.export.csv')); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-file-csv me-1"></i>Export CSV
                        </a>
                        <a href="<?php echo e(route('risks.export.excel')); ?>" class="btn btn-outline-success">
                            <i class="fas fa-file-excel me-1"></i>Export Excel
                        </a>
                        <a href="<?php echo e(route('risks.export.pdf')); ?>" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i>Export PDF
                        </a>
                        <button class="btn btn-outline-warning" onclick="printReport()">
                            <i class="fas fa-print me-1"></i>Print Report
                        </button>
                        <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-outline-info">
                            <i class="fas fa-users me-1"></i>View All Clients
                        </a>
                        <a href="<?php echo e(route('risks.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-plus me-1"></i>Add New Risk Assessment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="filter-section mb-4 no-print">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Reports</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Risk Level</label>
                        <select class="form-select" id="riskLevelFilter">
                            <option value="">All Risk Levels</option>
                            <option value="Low-risk" <?php echo e(request('risk_level') == 'Low-risk' ? 'selected' : ''); ?>>Low Risk</option>
                            <option value="Medium-risk" <?php echo e(request('risk_level') == 'Medium-risk' ? 'selected' : ''); ?>>Medium Risk</option>
                            <option value="High-risk" <?php echo e(request('risk_level') == 'High-risk' ? 'selected' : ''); ?>>High Risk</option>
                            <option value="Very High-risk" <?php echo e(request('risk_level') == 'Very High-risk' ? 'selected' : ''); ?>>Very High Risk</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Assessment Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="Open" <?php echo e(request('status') == 'Open' ? 'selected' : ''); ?>>Open</option>
                            <option value="Closed" <?php echo e(request('status') == 'Closed' ? 'selected' : ''); ?>>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Approval Status</label>
                        <select class="form-select" id="approvalFilter">
                            <option value="">All Approvals</option>
                            <option value="pending" <?php echo e(request('approval_status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="approved" <?php echo e(request('approval_status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                            <option value="rejected" <?php echo e(request('approval_status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quick Filters</label>
                        <select class="form-select" id="quickFilter">
                            <option value="">Quick Filters</option>
                            <option value="high_risk" <?php echo e(request('filter') == 'high_risk' ? 'selected' : ''); ?>>High Risk Clients</option>
                            <option value="overdue" <?php echo e(request('filter') == 'overdue' ? 'selected' : ''); ?>>Overdue Assessments</option>
                            <option value="open" <?php echo e(request('filter') == 'open' ? 'selected' : ''); ?>>Open Assessments</option>
                            <option value="closed" <?php echo e(request('filter') == 'closed' ? 'selected' : ''); ?>>Closed Assessments</option>
                            <option value="pending" <?php echo e(request('filter') == 'pending' ? 'selected' : ''); ?>>Pending Approvals</option>
                            <option value="approved" <?php echo e(request('filter') == 'approved' ? 'selected' : ''); ?>>Approved Clients</option>
                            <option value="rejected" <?php echo e(request('filter') == 'rejected' ? 'selected' : ''); ?>>Rejected Clients</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary me-2" onclick="applyReportFilters()">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                        <a href="<?php echo e(route('risks.reports')); ?>" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-times me-1"></i>Clear All
                        </a>
                        <a href="<?php echo e(route('risks.approval.index')); ?>" class="btn btn-success">
                            <i class="fas fa-check-circle me-1"></i>Manage Approvals
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Risk Summary Table -->
    <div class="risks-table-card">
        <div class="card-header">
            <h5><i class="fas fa-users me-2"></i>
                <?php if(request('filter')): ?>
                    <?php switch(request('filter')):
                        case ('high_risk'): ?>
                            High-Risk Clients
                            <?php break; ?>
                        <?php case ('overdue'): ?>
                            Clients Requiring Attention
                            <?php break; ?>
                        <?php case ('open'): ?>
                            Pending Assessments
                            <?php break; ?>
                        <?php case ('closed'): ?>
                            Approved Clients
                            <?php break; ?>
                        <?php case ('pending'): ?>
                            Pending Approvals
                            <?php break; ?>
                        <?php case ('approved'): ?>
                            Approved Clients
                            <?php break; ?>
                        <?php case ('rejected'): ?>
                            Rejected Clients Analysis
                            <?php break; ?>
                        <?php default: ?>
                            Filtered Client Assessments
                    <?php endswitch; ?>
                <?php else: ?>
                    Client Risk Assessments Summary
                    <small class="text-white ms-2">(Showing risks for approved clients only)</small>
                <?php endif; ?>
            </h5>
            <?php if(request('filter') || request('risk_level') || request('status') || request('approval_status')): ?>
                <div class="mt-2">
                    <span class="badge bg-primary me-2">
                        <i class="fas fa-filter me-1"></i>
                        <?php if(request('filter')): ?>
                            Filtered: <?php echo e(ucfirst(str_replace('_', ' ', request('filter')))); ?>

                        <?php else: ?>
                            Custom Filters Applied
                        <?php endif; ?>
                    </span>
                    <a href="<?php echo e(route('risks.reports')); ?>" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-times me-1"></i>Clear Filter
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-body p-0">
            <?php if(isset($risks) && $risks->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Risk Level</th>
                                <th>Assessment Status</th>
                                <th>Approval Status & Details</th>
                                <th>Assessment Date</th>
                                <th>Next Review</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $risks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $risk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <?php if($risk->client_name): ?>
                                        <div class="client-info">
                                            <div class="client-name fw-bold"><?php echo e($risk->client_name); ?></div>
                                            <div class="client-company text-muted small"><?php echo e($risk->client_type ?? 'Individual'); ?></div>
                                        </div>
                                    <?php elseif($risk->client): ?>
                                        <div class="client-info">
                                            <div class="client-name fw-bold"><?php echo e($risk->client->name); ?></div>
                                            <div class="client-company text-muted small"><?php echo e($risk->client->company ?? 'Individual'); ?></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="client-info">
                                            <div class="client-name fw-bold">Assessment #<?php echo e($risk->id); ?></div>
                                            <div class="client-company text-muted small"><?php echo e($risk->risk_category ?? 'Risk Assessment'); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $riskLevel = $risk->risk_rating ?? 'Not Assessed';
                                        $riskColor = match($riskLevel) {
                                            'High-risk', 'Very High-risk', 'Critical' => 'danger',
                                            'Medium-risk' => 'warning', 
                                            'Low-risk' => 'success',
                                            default => 'secondary'
                                        };
                                    ?>
                                    <span class="badge bg-<?php echo e($riskColor); ?> fs-6">
                                        <?php echo e($riskLevel); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($risk->status_color ?? 'warning'); ?> fs-6">
                                        <?php echo e($risk->status ?? 'In Progress'); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $statusColor = match($risk->assessment_status) {
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'pending' => 'warning',
                                            default => 'warning'
                                        };
                                    ?>
                                    <span class="badge bg-<?php echo e($statusColor); ?> fs-6">
                                        <?php echo e(ucfirst($risk->assessment_status ?? 'Pending')); ?>

                                    </span>
                                    <?php if($risk->approver_name): ?>
                                        <br><small class="text-muted">by <?php echo e($risk->approver_name); ?></small>
                                    <?php endif; ?>
                                    <?php if($risk->approved_at): ?>
                                        <br><small class="text-muted"><?php echo e(\Carbon\Carbon::parse($risk->approved_at)->format('M d, Y H:i')); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted"><?php echo e($risk->created_at->format('M d, Y')); ?></span>
                                </td>
                                <td>
                                    <?php if($risk->due_date): ?>
                                        <?php
                                            $dueDate = \Carbon\Carbon::parse($risk->due_date);
                                            $isOverdue = $dueDate->isPast();
                                        ?>
                                        <span class="due-date <?php echo e($isOverdue ? 'overdue' : ''); ?>">
                                            <?php echo e($dueDate->format('M d, Y')); ?>

                                        </span>
                                        <?php if($isOverdue): ?>
                                            <span class="badge bg-danger ms-1">Overdue</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No review date</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('clients.show', $risk->client_id)); ?>" class="btn btn-sm btn-outline-primary" title="View Client Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($risk->client): ?>
                                            <a href="<?php echo e(route('clients.show', $risk->client)); ?>" class="btn btn-sm btn-outline-success" title="View Client">
                                                <i class="fas fa-user"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if($risk->assessment_status === 'pending'): ?>
                                            <a href="<?php echo e(route('risks.approval.show', $risk)); ?>" class="btn btn-sm btn-warning" title="Approve/Reject">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h5>No Client Assessments Found</h5>
                    <p>No client risk assessments have been completed yet.</p>
                    <a href="<?php echo e(route('risks.index')); ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-1"></i>Start First Assessment
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Rejected Client Details Modal -->
<div class="modal fade" id="rejectedClientModal" tabindex="-1" aria-labelledby="rejectedClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectedClientModalLabel">
                    <i class="fas fa-user-times me-2"></i>Rejected Client Risk Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="rejectedClientDetails">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Global modal cleanup function
function cleanupModals() {
    // Remove all modal backdrops
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // Reset body classes and styles
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // Dispose of all modal instances (only if Bootstrap is available)
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.dispose();
            }
        });
    }
}

// Add event listeners for modal cleanup
document.addEventListener('DOMContentLoaded', function() {
    // Clean up any existing modals on page load
    cleanupModals();
    
    // Add event listener for when modals are hidden
    document.addEventListener('hidden.bs.modal', function() {
        cleanupModals();
    });
});

function applyReportFilters() {
    const riskLevel = document.getElementById('riskLevelFilter').value;
    const status = document.getElementById('statusFilter').value;
    const approval = document.getElementById('approvalFilter').value;
    const quickFilter = document.getElementById('quickFilter').value;
    
    // Build URL with parameters
    let url = new URL(window.location.href);
    url.search = ''; // Clear existing parameters
    
    if (quickFilter) {
        url.searchParams.set('filter', quickFilter);
    } else {
        // Apply individual filters
        if (riskLevel) url.searchParams.set('risk_level', riskLevel);
        if (status) url.searchParams.set('status', status);
        if (approval) url.searchParams.set('approval_status', approval);
    }
    
    // Redirect to filtered URL
    window.location.href = url.toString();
}

// Auto-apply filters when quick filter changes
document.getElementById('quickFilter').addEventListener('change', function() {
    if (this.value) {
        applyReportFilters();
    }
});

// Auto-apply filters when individual filters change
document.getElementById('riskLevelFilter').addEventListener('change', function() {
    if (this.value && !document.getElementById('quickFilter').value) {
        applyReportFilters();
    }
});

document.getElementById('statusFilter').addEventListener('change', function() {
    if (this.value && !document.getElementById('quickFilter').value) {
        applyReportFilters();
    }
});

document.getElementById('approvalFilter').addEventListener('change', function() {
    if (this.value && !document.getElementById('quickFilter').value) {
        applyReportFilters();
    }
});

// Removed rejected client functions

// Print function for better printing experience
function printReport() {
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Get the current report data
    const reportData = <?php echo json_encode($risks ?? [], 15, 512) ?>;
    const totalRisks = reportData.length;
    
    // Count risk levels based on client overall risk rating
    const highRiskCount = reportData.filter(client => 
        client.risk_rating === 'High-risk' || 
        client.risk_rating === 'Very High-risk' ||
        client.risk_rating === 'Critical'
    ).length;
    const mediumRiskCount = reportData.filter(client => 
        client.risk_rating === 'Medium-risk'
    ).length;
    const lowRiskCount = reportData.filter(client => 
        client.risk_rating === 'Low-risk'
    ).length;
    
    // Helper function to get category breakdown
    function getCategoryBreakdown(data) {
        const categories = {};
        data.forEach(client => {
            const category = client.risk_category || 'Comprehensive';
            categories[category] = (categories[category] || 0) + 1;
        });
        
        return Object.entries(categories)
            .map(([category, count]) => `<span style="background: #e9ecef; padding: 2px 6px; border-radius: 3px; margin-right: 5px;">${category}: ${count}</span>`)
            .join('');
    }
    
    // Helper function to get status breakdown
    function getStatusBreakdown(data) {
        const statuses = {};
        data.forEach(client => {
            const status = client.status || 'Unknown';
            statuses[status] = (statuses[status] || 0) + 1;
        });
        
        return Object.entries(statuses)
            .map(([status, count]) => `<span style="background: #e9ecef; padding: 2px 6px; border-radius: 3px; margin-right: 5px;">${status}: ${count}</span>`)
            .join('');
    }
    
    // Build the print content
    let printContent = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Risk Assessments Report - Print</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                line-height: 1.4;
                margin: 0;
                padding: 20px;
                color: #333;
            }
            
            .print-header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #333;
                padding-bottom: 20px;
            }
            
            .print-header h1 {
                color: #333;
                margin: 0;
                font-size: 24px;
            }
            
            .print-header .subtitle {
                color: #666;
                margin: 5px 0 0 0;
                font-size: 14px;
            }
            
            .print-summary {
                background: #f8f9fa;
                padding: 15px;
                margin-bottom: 20px;
                border: 1px solid #ddd;
            }
            
            .print-summary h3 {
                margin: 0 0 10px 0;
                color: #333;
                font-size: 16px;
            }
            
            .print-summary-stats {
                display: flex;
                justify-content: space-around;
                margin-top: 10px;
            }
            
            .print-stat-item {
                text-align: center;
            }
            
            .print-stat-number {
                font-size: 18px;
                font-weight: bold;
                color: #333;
            }
            
            .print-stat-label {
                font-size: 11px;
                color: #666;
                text-transform: uppercase;
            }
            
            .print-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                font-size: 10px;
            }
            
            .print-table th, .print-table td {
                border: 1px solid #333;
                padding: 6px;
                text-align: left;
                vertical-align: top;
            }
            
            .print-table th {
                background-color: #333;
                color: white;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 9px;
            }
            
            .print-table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            
            .print-risk-high { background-color: #ffebee; }
            .print-risk-medium { background-color: #fff3e0; }
            .print-risk-low { background-color: #e8f5e8; }
            
            .print-footer {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #333;
                text-align: center;
                font-size: 10px;
                color: #666;
            }
            
            .print-risk-rating {
                padding: 2px 4px;
                border-radius: 2px;
                font-weight: bold;
                font-size: 8px;
                text-transform: uppercase;
            }
            
            .print-rating-high { background-color: #e74c3c; color: white; }
            .print-rating-medium { background-color: #f39c12; color: white; }
            .print-rating-low { background-color: #27ae60; color: white; }
            .print-rating-critical { background-color: #8e44ad; color: white; }
            
            .page-break {
                page-break-before: always;
            }
        </style>
    </head>
    <body>
        <div class="print-header">
            <h1>DCS Risk Assessments Report</h1>
            <div class="subtitle">Generated on ${new Date().toLocaleString()} by <?php echo e(auth()->user()->name ?? 'System'); ?></div>
            <div class="subtitle" style="margin-top: 5px; font-size: 12px;">
                DCS Risk Register System | No 41, Johann and Sturrock, Windhoek, Namibia
            </div>
        </div>
        
        <div class="print-summary">
            <h3>Report Summary</h3>
            <div class="print-summary-stats">
                <div class="print-stat-item">
                    <div class="print-stat-number">${totalRisks}</div>
                    <div class="print-stat-label">Total Assessments</div>
                    </div>
                <div class="print-stat-item">
                    <div class="print-stat-number" style="color: #e74c3c;">${highRiskCount}</div>
                    <div class="print-stat-label">High Risk</div>
                    </div>
                <div class="print-stat-item">
                    <div class="print-stat-number" style="color: #f39c12;">${mediumRiskCount}</div>
                    <div class="print-stat-label">Medium Risk</div>
                </div>
                <div class="print-stat-item">
                    <div class="print-stat-number" style="color: #27ae60;">${lowRiskCount}</div>
                    <div class="print-stat-label">Low Risk</div>
                </div>
            </div>
            
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #333;">Risk Distribution by Category</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; font-size: 11px;">
                    ${getCategoryBreakdown(reportData)}
            </div>
        </div>
            
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #333;">Status Overview</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; font-size: 11px;">
                    ${getStatusBreakdown(reportData)}
                </div>
            </div>
        </div>
        
        <table class="print-table">
                <thead>
                    <tr>
                    <th style="width: 8%;">Risk ID</th>
                    <th style="width: 12%;">Client</th>
                    <th style="width: 10%;">Company</th>
                    <th style="width: 15%;">Risk Title</th>
                    <th style="width: 10%;">Category</th>
                    <th style="width: 6%;">Rating</th>
                    <th style="width: 5%;">Impact</th>
                    <th style="width: 6%;">Likelihood</th>
                    <th style="width: 5%;">Points</th>
                    <th style="width: 6%;">Status</th>
                    <th style="width: 8%;">Owner</th>
                    <th style="width: 6%;">Created</th>
                    <th style="width: 6%;">Due Date</th>
                    </tr>
                </thead>
            <tbody>`;
    
    // Add each client row
    reportData.forEach(client => {
        const riskRating = client.risk_rating || 'N/A';
        const ratingClass = riskRating.toLowerCase().replace('-', '');
        const rowClass = riskRating.toLowerCase().replace('-', '');
        
        // Format dates
        const createdDate = client.created_at ? new Date(client.created_at).toLocaleDateString() : 'N/A';
        const dueDate = 'N/A'; // Clients don't have due dates
        
        // Get owner name
        const ownerName = client.assigned_user_name || 'Unassigned';
        
        printContent += `
            <tr class="print-risk-${rowClass}">
                <td>#${client.id || 'N/A'}</td>
                <td>${client.client_name || 'N/A'}</td>
                <td>${client.company || 'Individual'}</td>
                <td>${client.title || 'Untitled Client'}</td>
                <td>${client.risk_category || 'Comprehensive'}</td>
                <td>
                    <span class="print-risk-rating print-rating-${ratingClass}">
                        ${riskRating}
                    </span>
                </td>
                <td>N/A</td>
                <td>N/A</td>
                <td>${client.overall_risk_points || 'N/A'}</td>
                <td>${client.status || 'Active'}</td>
                <td>${ownerName}</td>
                <td>${createdDate}</td>
                <td>${dueDate}</td>
            </tr>`;
    });
    
    printContent += `
            </tbody>
        </table>
        
        <div class="print-footer">
            <p><strong>DCS Risk Register System - Professional Risk Management Platform</strong></p>
            <p>This report was generated on ${new Date().toLocaleString()} by <?php echo e(auth()->user()->name ?? 'System'); ?></p>
            <p>Report includes ${totalRisks} risk assessment(s) with comprehensive analysis and compliance data</p>
            <p style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #ccc;">
                <strong>Contact Information:</strong><br>
                Email: ITSupport@dcs.com.na | info@dcs.com.na<br>
                Phone: +264 82 403 2391<br>
                Address: No 41, Johann and Sturrock st, Windhoek, Namibia<br>
                Website: www.dcs.com.na
            </p>
            <p style="margin-top: 10px; font-size: 9px; color: #999;">
                This report is confidential and intended for internal use only. 
                Distribution outside of DCS requires prior authorization.
            </p>
        </div>
    </body>
    </html>`;
    
    // Write content to print window and print
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Wait for content to load, then print
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
}

// Helper function to get risk rating color
function getRiskRatingColor(rating) {
    switch(rating) {
        case 'High': return 'danger';
        case 'Medium': return 'warning';
        case 'Low': return 'success';
        case 'Critical': return 'dark';
        default: return 'secondary';
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\DCS-Best\resources\views/risks/reports.blade.php ENDPATH**/ ?>