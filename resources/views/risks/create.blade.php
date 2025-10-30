@extends('layouts.sidebar')

@section('title', 'Add New Risk - DCS-Best')

@section('content')
<style>
    /* Desktop Layout Fixes */
    .container-fluid {
        max-width: 100%;
        padding: 1rem 1.5rem;
        overflow-x: hidden;
    }
    
    .page-header {
        margin-bottom: 2rem;
        border-radius: 12px;
    }
    
    .card {
        margin-bottom: 1.5rem;
        border-radius: 12px;
        max-width: 100%;
        overflow: hidden;
    }
    
    .table-responsive {
        border-radius: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .risk-matrix-table {
        width: 100%;
        min-width: 100%;
        table-layout: auto;
    }
    
    .risk-matrix-table th,
    .risk-matrix-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .risk-matrix-table th:first-child,
    .risk-matrix-table td:first-child {
        min-width: 200px;
        white-space: normal;
    }
    
    .risk-matrix-table th:last-child,
    .risk-matrix-table td:last-child {
        min-width: 250px;
        white-space: normal;
    }

    /* Mobile-First Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.75rem;
            max-width: 100%;
            overflow-x: hidden;
        }
        
        .page-header {
            margin: 0 0 1.5rem 0;
            border-radius: 8px;
            padding: 1rem;
        }
        
        .page-title {
            font-size: 1.25rem;
        }
        
        .page-subtitle {
            font-size: 0.85rem;
        }
        
        .card {
            margin: 0 0 1rem 0;
            border-radius: 8px;
            max-width: 100%;
        }
        
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-header h5 {
            font-size: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .form-section {
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        
        .row {
            margin: 0;
        }
        
        .col-md-3, .col-md-4 {
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
        
        .table-responsive {
            font-size: 0.75rem;
            border-radius: 8px;
            overflow-x: auto;
        }
        
        .risk-matrix-table {
            min-width: 100%;
            width: 100%;
        }
        
        .risk-matrix-table th,
        .risk-matrix-table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.7rem;
            white-space: nowrap;
        }
        
        .risk-matrix-table th {
            font-size: 0.65rem;
            padding: 0.375rem 0.125rem;
        }
        
        .category-badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }
        
        .risk-badge, .points-badge, .status-badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
        
        .owner-text, .mitigation-text {
            font-size: 0.6rem;
        }
        
        .risk-select {
            font-size: 0.7rem;
            padding: 0.5rem;
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
        
        .card {
            margin: 0 -0.25rem;
        }
        
        .card-header {
            padding: 0.5rem 0.75rem;
        }
        
        .card-header h5 {
            font-size: 0.9rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .section-title {
            font-size: 0.9rem;
        }
        
        .col-md-3, .col-md-4 {
            padding: 0.125rem;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            padding: 0.625rem;
            font-size: 16px;
        }
        
        .table-responsive {
            font-size: 0.7rem;
        }
        
        .risk-matrix-table {
            min-width: 100%;
            width: 100%;
        }
        
        .risk-matrix-table th,
        .risk-matrix-table td {
            padding: 0.375rem 0.125rem;
            font-size: 0.65rem;
        }
        
        .risk-matrix-table th {
            font-size: 0.6rem;
            padding: 0.25rem 0.1rem;
        }
        
        .category-badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
        
        .risk-badge, .points-badge, .status-badge {
            font-size: 0.55rem;
            padding: 0.15rem 0.3rem;
        }
        
        .owner-text, .mitigation-text {
            font-size: 0.55rem;
        }
        
        .risk-select {
            font-size: 0.65rem;
            padding: 0.4rem;
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
        
        .page-subtitle {
            font-size: 0.75rem;
        }
        
        .card-header {
            padding: 0.4rem 0.5rem;
        }
        
        .card-header h5 {
            font-size: 0.85rem;
        }
        
        .card-body {
            padding: 0.5rem;
        }
        
        .section-title {
            font-size: 0.85rem;
        }
        
        .form-control, .form-select {
            padding: 0.5rem;
            font-size: 16px;
        }
        
        .table-responsive {
            font-size: 0.65rem;
        }
        
        .risk-matrix-table {
            min-width: 100%;
            width: 100%;
        }
        
        .risk-matrix-table th,
        .risk-matrix-table td {
            padding: 0.25rem 0.1rem;
            font-size: 0.6rem;
        }
        
        .risk-matrix-table th {
            font-size: 0.55rem;
            padding: 0.2rem 0.05rem;
        }
        
        .category-badge {
            font-size: 0.55rem;
            padding: 0.15rem 0.3rem;
        }
        
        .risk-badge, .points-badge, .status-badge {
            font-size: 0.5rem;
            padding: 0.1rem 0.25rem;
        }
        
        .owner-text, .mitigation-text {
            font-size: 0.5rem;
        }
        
        .risk-select {
            font-size: 0.6rem;
            padding: 0.3rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Add New Risk</h1>
                <p class="page-subtitle">Client Risk Assessment Form</p>
            </div>
            <div class="header-actions">
                <!-- Back button removed - Risk Register directly shows add new risk form -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-plus me-2"></i>Client Risk Assessment</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('risks.store') }}" method="POST" id="riskForm" enctype="multipart/form-data">
                        @csrf
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        
                        <!-- Client Lookup Section -->
                        <div class="form-section">
                            <div class="alert alert-info mb-3">
                                <h6 class="alert-heading">
                                    <i class="fas fa-search me-2"></i>
                                    AML Compliance Check - Search for Existing Client
                                </h6>
                                <p class="mb-3">Before creating a new assessment, search for existing client records to ensure AML compliance and avoid duplicate assessments.</p>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="clientSearch" placeholder="Enter client name to search...">
                                            <button class="btn btn-outline-primary" type="button" id="searchClientBtn">
                                                <i class="fas fa-search me-1"></i>Search
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-success w-100" type="button" id="proceedWithNewClient">
                                            <i class="fas fa-plus me-1"></i>Proceed with New Client
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Search Results -->
                                <div id="clientSearchResults" class="mt-3" style="display: none;">
                                    <h6>Search Results:</h6>
                                    <div id="searchResultsList"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Client Information -->
                        <div class="form-section">
                            <h6 class="section-title">Client Information</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="client_name" class="form-label">Client Name *</label>
                                    <input type="text" class="form-control" name="client_name" id="client_name" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Client Type *</label>
                                    <select class="form-control" name="client_type" id="client_type" required>
                                        <option value="">Select type</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Juristic">Juristic</option>
                                    </select>
                                </div>
                                <div class="col-md-3 individual-only" style="display:none;">
                                    <label class="form-label">Gender</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3 individual-only" style="display:none;">
                                    <label class="form-label">Nationality</label>
                                    <select class="form-control" name="nationality" id="nationality">
                                        <option value="">Select</option>
                                        <option value="Namibian">Namibian</option>
                                        <option value="Foreign">Foreign</option>
                                    </select>
                                </div>
                                <div class="col-md-3 individual-only" style="display:none;">
                                    <label class="form-label">Is Minor (Under 18)</label>
                                    <select class="form-control" name="is_minor" id="is_minor">
                                        <option value="">Select</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-3 individual-only namibian-only adult-only" style="display:none;">
                                    <label class="form-label">Namibian ID Number *</label>
                                    <input type="text" class="form-control" name="id_number" id="id_number" placeholder="Enter Namibian ID">
                                </div>
                                <div class="col-md-3 individual-only foreign-only" style="display:none;">
                                    <label class="form-label">Passport Number *</label>
                                    <input type="text" class="form-control" name="passport_number" id="passport_number" placeholder="Enter Passport Number">
                                </div>
                                <div class="col-md-3 individual-only namibian-only minor-only" style="display:none;">
                                    <label class="form-label">Birth Certificate Number *</label>
                                    <input type="text" class="form-control" name="birth_certificate_number" id="birth_certificate_number" placeholder="Enter Birth Certificate Number">
                                </div>
                                <div class="col-md-3 juristic-only" style="display:none;">
                                    <label class="form-label">Registration Number</label>
                                    <input type="text" class="form-control" name="registration_number" id="registration_number">
                                </div>
                                <div class="col-md-3 juristic-only" style="display:none;">
                                    <label class="form-label">Entity Type</label>
                                    <select class="form-control" name="entity_type" id="entity_type">
                                        <option value="">Select</option>
                                        <option value="PTY Ltd">PTY Ltd</option>
                                        <option value="LTD">LTD</option>
                                        <option value="S21">S21</option>
                                        <option value="Inc">Inc</option>
                                        <option value="CC">CC</option>
                                        <option value="Trust">Trust</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3 juristic-only" style="display:none;">
                                    <label class="form-label">Trading Address</label>
                                    <input type="text" class="form-control" name="trading_address" id="trading_address">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Source of Income</label>
                                    <input type="text" class="form-control" name="income_source" id="income_source" placeholder="e.g., Business operations, Investments">
                                </div>
                                <div class="col-md-3">
                                    <label for="client_email" class="form-label">Client Email *</label>
                                    <input type="email" class="form-control" name="client_email" id="client_email" placeholder="client@example.com" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="client_phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" name="client_phone" id="client_phone" placeholder="+264 81 123 4567" required>
                                </div>
                                <div class="col-md-3 individual-only">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" id="date_of_birth">
                                </div>
                                <div class="col-md-3 individual-only">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control" name="occupation" id="occupation" placeholder="e.g., Engineer, Teacher, Business Owner">
                                </div>
                                <div class="col-md-3 individual-only">
                                    <label for="address" class="form-label">Physical Address</label>
                                    <textarea class="form-control" name="address" id="address" rows="2" placeholder="Enter full physical address"></textarea>
                                </div>
                                <div class="col-md-3 juristic-only">
                                    <label for="company_name" class="form-label">Company Name *</label>
                                    <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Enter company name">
                                </div>
                                <div class="col-md-3 juristic-only">
                                    <label for="director_name" class="form-label">Director/CEO Name</label>
                                    <input type="text" class="form-control" name="director_name" id="director_name" placeholder="Enter director/CEO name">
                                </div>
                                <div class="col-md-3">
                                    <label for="client_industry" class="form-label">Industry</label>
                                    <select name="client_industry" id="client_industry" class="form-select" required>
                                        <option value="">Select Industry</option>
                                        <option value="Financial Services">Financial Services</option>
                                        <option value="Technology">Technology</option>
                                        <option value="Healthcare">Healthcare</option>
                                        <option value="Manufacturing">Manufacturing</option>
                                        <option value="Retail">Retail</option>
                                        <option value="Real Estate">Real Estate</option>
                                        <option value="Transportation">Transportation</option>
                                        <option value="Energy">Energy</option>
                                        <option value="Education">Education</option>
                                        <option value="Government">Government</option>
                                        <option value="Non-Profit">Non-Profit</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="assessment_date" class="form-label">Assessment Date *</label>
                                    <input type="date" class="form-control" name="assessment_date" id="assessment_date" 
                                           min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                    <small class="form-text text-muted">Only current date is allowed for assessments</small>
                                </div>
                            </div>
                            <!-- Document Upload Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-file-upload me-2"></i>Required Documents
                                    </h6>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Individual Documents -->
                                <div class="col-md-3 individual-only namibian-only adult-only" style="display:none;">
                                    <label class="form-label">Namibian ID Document *</label>
                                    <input type="file" class="form-control" name="id_document" id="id_document" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">Upload clear copy of Namibian ID</small>
                                </div>
                                <div class="col-md-3 individual-only namibian-only minor-only" style="display:none;">
                                    <label class="form-label">Birth Certificate *</label>
                                    <input type="file" class="form-control" name="birth_certificate" id="birth_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">Upload clear copy of birth certificate</small>
                                </div>
                                <div class="col-md-3 individual-only foreign-only" style="display:none;">
                                    <label class="form-label">Passport Document *</label>
                                    <input type="file" class="form-control" name="passport_document" id="passport_document" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">Upload clear copy of passport</small>
                                </div>
                                
                                <!-- Juristic Documents -->
                                <div class="col-md-3 juristic-only" style="display:none;">
                                    <label class="form-label">Registration Document *</label>
                                    <input type="file" class="form-control" name="registration_document" id="registration_document" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">Upload company registration certificate</small>
                                </div>
                                <div class="col-md-3 juristic-only" style="display:none;">
                                    <label class="form-label">Company Nationality *</label>
                                    <select class="form-select" name="company_nationality" id="company_nationality">
                                        <option value="">Select Nationality</option>
                                        <option value="Namibian">Namibian</option>
                                        <option value="Foreign">Foreign</option>
                                    </select>
                                    <small class="form-text text-muted">Is the company Namibian or Foreign?</small>
                                </div>
                                
                                <!-- Common Documents -->
                                <div class="col-md-3">
                                    <label class="form-label">Proof of Residence *</label>
                                    <input type="file" class="form-control" name="proof_of_residence" id="proof_of_residence" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">Utility bill, bank statement, etc.</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">KYC Form *</label>
                                    <input type="file" class="form-control" name="kyc_form" id="kyc_form" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">Completed KYC form</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Source of Earnings *</label>
                                    <input type="file" class="form-control" name="source_of_earnings" id="source_of_earnings" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">Bank statements, payslips, etc.</small>
                                </div>
                                <div class="col-md-3 juristic-only" style="display:none;">
                                    <label class="form-label">Tax Certificate (ITAS)</label>
                                    <input type="file" class="form-control" name="tax_certificate" id="tax_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">ITAS tax certificate</small>
                                </div>
                                <div class="col-md-3 juristic-foreign" style="display:none;">
                                    <label class="form-label">Foreign Registration Certificate *</label>
                                    <input type="file" class="form-control" name="foreign_registration" id="foreign_registration" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">Certificate of Incorporation or equivalent</small>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label for="screening_status" class="form-label">Screening Status *</label>
                                    <select name="screening_status" id="screening_status" class="form-select" required>
                                        <option value="">Select Status</option>
                                        <option value="Done">Done</option>
                                        <option value="Not Done">Not Done</option>
                                        <option value="In Progress">In Progress</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="dcs_risk_appetite" class="form-label">DCS Risk Appetite *</label>
                                    <select name="dcs_risk_appetite" id="dcs_risk_appetite" class="form-select" required>
                                        <option value="">Select Risk Appetite</option>
                                        <option value="Conservative">Conservative</option>
                                        <option value="Moderate">Moderate</option>
                                        <option value="Aggressive">Aggressive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Assessment Table -->
                        <div class="form-section">
                            <h6 class="section-title">Risk Assessment - Client Risk Matrix</h6>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered risk-matrix-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="category-col">Category</th>
                                            <th class="risk-selection-col">Risk ID & Description</th>
                                            <th class="impact-col">Impact</th>
                                            <th class="likelihood-col">Likelihood</th>
                                            <th class="rating-col">Risk Rating</th>
                                            <th class="points-col">Points</th>
                                            <th class="owner-col">Owner</th>
                                            <th class="status-col">Status</th>
                                            <th class="mitigation-col">Mitigation Strategies</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Service Risk (SR) Row -->
                                        <tr class="risk-row">
                                            <td class="category-cell">
                                                <span class="category-badge category-sr">
                                                    <i class="fas fa-cogs me-1"></i>SR
                                                </span>
                                            </td>
                                            <td class="risk-selection-cell">
                                                <select name="sr_selection" id="sr_selection" class="form-select risk-select" required>
                                                    <option value="">Select Service Risk</option>
                                                    @foreach($riskCategories->where('risk_category', 'SR') as $risk)
                                                    <option value="{{ $risk->risk_id }}" 
                                                            data-name="{{ $risk->risk_name }}"
                                                            data-detail="{{ $risk->risk_detail }}"
                                                            data-category="{{ $risk->risk_category }}"
                                                            data-impact="{{ $risk->impact }}"
                                                            data-likelihood="{{ $risk->likelihood }}"
                                                            data-rating="{{ $risk->risk_rating }}"
                                                            data-mitigation="{{ $risk->mitigation_strategies }}"
                                                            data-owner="{{ $risk->owner }}"
                                                            data-status="{{ $risk->status }}">
                                                        {{ $risk->risk_id }} - {{ $risk->risk_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="impact-cell">
                                                <span id="sr_impact" class="risk-badge">-</span>
                                            </td>
                                            <td class="likelihood-cell">
                                                <span id="sr_likelihood" class="risk-badge">-</span>
                                            </td>
                                            <td class="rating-cell">
                                                <span id="sr_rating" class="risk-badge">-</span>
                                            </td>
                                            <td class="points-cell">
                                                <span id="sr_points" class="points-badge">-</span>
                                            </td>
                                            <td class="owner-cell">
                                                <span id="sr_owner" class="owner-text">-</span>
                                            </td>
                                            <td class="status-cell">
                                                <span id="sr_status" class="status-badge">-</span>
                                            </td>
                                            <td class="mitigation-cell">
                                                <span id="sr_mitigation" class="mitigation-text">-</span>
                                            </td>
                                        </tr>

                                        <!-- Client Risk (CR) Row -->
                                        <tr class="risk-row">
                                            <td class="category-cell">
                                                <span class="category-badge category-cr">
                                                    <i class="fas fa-user-shield me-1"></i>CR
                                                </span>
                                            </td>
                                            <td class="risk-selection-cell">
                                                <select name="cr_selection" id="cr_selection" class="form-select risk-select" required>
                                                    <option value="">Select Client Risk</option>
                                                    @foreach($riskCategories->where('risk_category', 'CR') as $risk)
                                                    <option value="{{ $risk->risk_id }}" 
                                                            data-name="{{ $risk->risk_name }}"
                                                            data-detail="{{ $risk->risk_detail }}"
                                                            data-category="{{ $risk->risk_category }}"
                                                            data-impact="{{ $risk->impact }}"
                                                            data-likelihood="{{ $risk->likelihood }}"
                                                            data-rating="{{ $risk->risk_rating }}"
                                                            data-mitigation="{{ $risk->mitigation_strategies }}"
                                                            data-owner="{{ $risk->owner }}"
                                                            data-status="{{ $risk->status }}">
                                                        {{ $risk->risk_id }} - {{ $risk->risk_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="impact-cell">
                                                <span id="cr_impact" class="risk-badge">-</span>
                                            </td>
                                            <td class="likelihood-cell">
                                                <span id="cr_likelihood" class="risk-badge">-</span>
                                            </td>
                                            <td class="rating-cell">
                                                <span id="cr_rating" class="risk-badge">-</span>
                                            </td>
                                            <td class="points-cell">
                                                <span id="cr_points" class="points-badge">-</span>
                                            </td>
                                            <td class="owner-cell">
                                                <span id="cr_owner" class="owner-text">-</span>
                                            </td>
                                            <td class="status-cell">
                                                <span id="cr_status" class="status-badge">-</span>
                                            </td>
                                            <td class="mitigation-cell">
                                                <span id="cr_mitigation" class="mitigation-text">-</span>
                                            </td>
                                        </tr>

                                        <!-- Payment Risk (PR) Row -->
                                        <tr class="risk-row">
                                            <td class="category-cell">
                                                <span class="category-badge category-pr">
                                                    <i class="fas fa-credit-card me-1"></i>PR
                                                </span>
                                            </td>
                                            <td class="risk-selection-cell">
                                                <select name="pr_selection" id="pr_selection" class="form-select risk-select" required>
                                                    <option value="">Select Payment Risk</option>
                                                    @foreach($riskCategories->where('risk_category', 'PR') as $risk)
                                                    <option value="{{ $risk->risk_id }}" 
                                                            data-name="{{ $risk->risk_name }}"
                                                            data-detail="{{ $risk->risk_detail }}"
                                                            data-category="{{ $risk->risk_category }}"
                                                            data-impact="{{ $risk->impact }}"
                                                            data-likelihood="{{ $risk->likelihood }}"
                                                            data-rating="{{ $risk->risk_rating }}"
                                                            data-mitigation="{{ $risk->mitigation_strategies }}"
                                                            data-owner="{{ $risk->owner }}"
                                                            data-status="{{ $risk->status }}">
                                                        {{ $risk->risk_id }} - {{ $risk->risk_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="impact-cell">
                                                <span id="pr_impact" class="risk-badge">-</span>
                                            </td>
                                            <td class="likelihood-cell">
                                                <span id="pr_likelihood" class="risk-badge">-</span>
                                            </td>
                                            <td class="rating-cell">
                                                <span id="pr_rating" class="risk-badge">-</span>
                                            </td>
                                            <td class="points-cell">
                                                <span id="pr_points" class="points-badge">-</span>
                                            </td>
                                            <td class="owner-cell">
                                                <span id="pr_owner" class="owner-text">-</span>
                                            </td>
                                            <td class="status-cell">
                                                <span id="pr_status" class="status-badge">-</span>
                                            </td>
                                            <td class="mitigation-cell">
                                                <span id="pr_mitigation" class="mitigation-text">-</span>
                                            </td>
                                        </tr>

                                        <!-- Delivery Risk (DR) Row -->
                                        <tr class="risk-row">
                                            <td class="category-cell">
                                                <span class="category-badge category-dr">
                                                    <i class="fas fa-truck me-1"></i>DR
                                                </span>
                                            </td>
                                            <td class="risk-selection-cell">
                                                <select name="dr_selection" id="dr_selection" class="form-select risk-select" required>
                                                    <option value="">Select Delivery Risk</option>
                                                    @foreach($riskCategories->where('risk_category', 'DR') as $risk)
                                                    <option value="{{ $risk->risk_id }}" 
                                                            data-name="{{ $risk->risk_name }}"
                                                            data-detail="{{ $risk->risk_detail }}"
                                                            data-category="{{ $risk->risk_category }}"
                                                            data-impact="{{ $risk->impact }}"
                                                            data-likelihood="{{ $risk->likelihood }}"
                                                            data-rating="{{ $risk->risk_rating }}"
                                                            data-mitigation="{{ $risk->mitigation_strategies }}"
                                                            data-owner="{{ $risk->owner }}"
                                                            data-status="{{ $risk->status }}">
                                                        {{ $risk->risk_id }} - {{ $risk->risk_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="impact-cell">
                                                <span id="dr_impact" class="risk-badge">-</span>
                                            </td>
                                            <td class="likelihood-cell">
                                                <span id="dr_likelihood" class="risk-badge">-</span>
                                            </td>
                                            <td class="rating-cell">
                                                <span id="dr_rating" class="risk-badge">-</span>
                                            </td>
                                            <td class="points-cell">
                                                <span id="dr_points" class="points-badge">-</span>
                                            </td>
                                            <td class="owner-cell">
                                                <span id="dr_owner" class="owner-text">-</span>
                                            </td>
                                            <td class="status-cell">
                                                <span id="dr_status" class="status-badge">-</span>
                                            </td>
                                            <td class="mitigation-cell">
                                                <span id="dr_mitigation" class="mitigation-text">-</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Risk Scoring System -->
                        <div class="form-section">
                            <h6 class="section-title">Risk Scoring System</h6>
                            
                            <div class="row">
                                <!-- Individual Risk Scoring -->
                                <div class="col-md-6">
                                    <div class="scoring-card">
                                        <h6 class="scoring-title">Individual Risk Points</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered scoring-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Risk Rating</th>
                                                        <th>Points</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><span class="badge bg-danger">High</span></td>
                                                        <td class="text-danger fw-bold">5</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-warning">Medium</span></td>
                                                        <td class="text-warning fw-bold">3</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-success">Low</span></td>
                                                        <td class="text-success fw-bold">1</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Overall Risk Assessment -->
                                <div class="col-md-6">
                                    <div class="scoring-card">
                                        <h6 class="scoring-title">Overall Risk Assessment</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered scoring-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Total Points</th>
                                                        <th>Risk Rating</th>
                                                        <th>Client Decision</th>
                                                        <th>Monitoring</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="total-points-cell total-20">20</td>
                                                        <td><span class="badge bg-danger">Very High-risk</span></td>
                                                        <td class="text-danger fw-bold">Do not accept</td>
                                                        <td>N/A</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="total-points-cell total-17">17</td>
                                                        <td><span class="text-danger fw-bold">High-risk</span></td>
                                                        <td class="text-success fw-bold">Accept client</td>
                                                        <td>Quarterly review</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="total-points-cell total-15">15</td>
                                                        <td><span class="badge bg-warning">Medium-risk</span></td>
                                                        <td class="text-success fw-bold">Accept client</td>
                                                        <td>Bi-Annually</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="total-points-cell total-10">10</td>
                                                        <td><span class="badge bg-success">Low-risk</span></td>
                                                        <td class="text-success fw-bold">Accept client</td>
                                                        <td>Annually</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Assessment Results -->
                        <div class="form-section">
                            <h6 class="section-title">Risk Assessment Results</h6>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="result-card">
                                        <h6>Total Points</h6>
                                        <span id="totalPoints" class="result-value">0</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="result-card">
                                        <h6>Overall Risk Rating</h6>
                                        <span id="overallRiskRating" class="result-value">Not Calculated</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="result-card">
                                        <h6>Client Decision</h6>
                                        <span id="clientDecision" class="result-value">Pending</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="result-card">
                                        <h6>Monitoring Frequency</h6>
                                        <span id="monitoringFrequency" class="result-value">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Fields for Controller -->
                        <input type="hidden" name="risk_description" id="risk_description" value="">
                        <input type="hidden" name="risk_category" id="risk_category" value="">
                        
                        

                        <!-- Client Screening Fields -->
                        <input type="hidden" name="client_screening_date" id="client_screening_date_hidden" value="">
                        <input type="hidden" name="client_screening_result" id="client_screening_result_hidden" value="">
                        <input type="hidden" name="client_email" id="client_email_hidden" value="">
                        <input type="hidden" name="client_industry" id="client_industry_hidden" value="">
                        
                        <!-- DCS Business Decision Fields -->
                        <input type="hidden" name="dcs_risk_appetite" id="dcs_risk_appetite_hidden" value="">
                        <input type="hidden" name="dcs_comments" id="dcs_comments_hidden" value="">
                        
                        <!-- Enhanced Risk Management Fields -->
                        <input type="hidden" name="due_date" id="due_date_hidden" value="">
                        <input type="hidden" name="assigned_user_id" id="assigned_user_id_hidden" value="">
                        <input type="hidden" name="risk_detail" id="risk_detail_hidden" value="">
                        
                        <!-- Comprehensive Risk Details from All Categories -->
                        <input type="hidden" name="sr_risk_id" id="sr_risk_id" value="">
                        <input type="hidden" name="sr_risk_name" id="sr_risk_name" value="">
                        <input type="hidden" name="sr_impact" id="sr_impact_hidden" value="">
                        <input type="hidden" name="sr_likelihood" id="sr_likelihood_hidden" value="">
                        <input type="hidden" name="sr_risk_rating" id="sr_risk_rating_hidden" value="">
                        <input type="hidden" name="sr_points" id="sr_points_hidden" value="">
                        <input type="hidden" name="sr_mitigation" id="sr_mitigation_hidden" value="">
                        <input type="hidden" name="sr_owner" id="sr_owner_hidden" value="">
                        <input type="hidden" name="sr_status" id="sr_status_hidden" value="">
                        
                        <input type="hidden" name="cr_risk_id" id="cr_risk_id" value="">
                        <input type="hidden" name="cr_risk_name" id="cr_risk_name" value="">
                        <input type="hidden" name="cr_impact" id="cr_impact_hidden" value="">
                        <input type="hidden" name="cr_likelihood" id="cr_likelihood_hidden" value="">
                        <input type="hidden" name="cr_risk_rating" id="cr_risk_rating_hidden" value="">
                        <input type="hidden" name="cr_points" id="cr_points_hidden" value="">
                        <input type="hidden" name="cr_mitigation" id="cr_mitigation_hidden" value="">
                        <input type="hidden" name="cr_owner" id="cr_owner_hidden" value="">
                        <input type="hidden" name="cr_status" id="cr_status_hidden" value="">
                        
                        <input type="hidden" name="pr_risk_id" id="pr_risk_id" value="">
                        <input type="hidden" name="pr_risk_name" id="pr_risk_name" value="">
                        <input type="hidden" name="pr_impact" id="pr_impact_hidden" value="">
                        <input type="hidden" name="pr_likelihood" id="pr_likelihood_hidden" value="">
                        <input type="hidden" name="pr_risk_rating" id="pr_risk_rating_hidden" value="">
                        <input type="hidden" name="pr_points" id="pr_points_hidden" value="">
                        <input type="hidden" name="pr_mitigation" id="pr_mitigation_hidden" value="">
                        <input type="hidden" name="pr_owner" id="pr_owner_hidden" value="">
                        <input type="hidden" name="pr_status" id="pr_status_hidden" value="">
                        
                        <input type="hidden" name="dr_risk_id" id="dr_risk_id" value="">
                        <input type="hidden" name="dr_risk_name" id="dr_risk_name" value="">
                        <input type="hidden" name="dr_impact" id="dr_impact_hidden" value="">
                        <input type="hidden" name="dr_likelihood" id="dr_likelihood_hidden" value="">
                        <input type="hidden" name="dr_risk_rating" id="dr_risk_rating_hidden" value="">
                        <input type="hidden" name="dr_points" id="dr_points_hidden" value="">
                        <input type="hidden" name="dr_mitigation" id="dr_mitigation_hidden" value="">
                        <input type="hidden" name="dr_owner" id="dr_owner_hidden" value="">
                        <input type="hidden" name="dr_status" id="dr_status_hidden" value="">
                        
                        <!-- Overall Assessment Fields -->
                        <input type="hidden" name="total_risk_points" id="total_risk_points_hidden" value="">
                        <input type="hidden" name="overall_risk_rating" id="overall_risk_rating_hidden" value="">
                        <input type="hidden" name="client_acceptance" id="client_acceptance_hidden" value="">
                        <input type="hidden" name="ongoing_monitoring" id="ongoing_monitoring_hidden" value="">
                        
                        <!-- Legacy fields for backward compatibility -->
                        <input type="hidden" name="impact" id="impact" value="">
                        <input type="hidden" name="likelihood" id="likelihood" value="">
                        <input type="hidden" name="status" id="status" value="">
                        <input type="hidden" name="mitigation_strategies" id="mitigation_strategies" value="">
                        <input type="hidden" name="owner" id="owner" value="">

                        <!-- Client Acceptance Decision -->
                        <div class="form-section">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="acceptance-decision-card">
                                        <h6 class="decision-title">Client Acceptance Decision</h6>
                                        <div id="finalDecision" class="final-decision">
                                            <div class="decision-content">
                                                <p class="decision-text">Complete the risk assessment to get the client acceptance decision</p>
                                                <div class="decision-details" style="display: none;">
                                                    <div class="decision-summary">
                                                        <span class="summary-label">Total Risk Points:</span>
                                                        <span id="finalTotalPoints" class="summary-value">0</span>
                                                    </div>
                                                    <div class="decision-summary">
                                                        <span class="summary-label">Overall Risk Rating:</span>
                                                        <span id="finalRiskRating" class="summary-value">Not Calculated</span>
                                                    </div>
                                                    <div class="decision-recommendation">
                                                        <span class="recommendation-label">RECOMMENDATION:</span>
                                                        <span id="finalClientDecision" class="recommendation-value">Pending</span>
                                                    </div>
                                                    <div class="monitoring-requirement" id="monitoringSection" style="display: none;">
                                                        <span class="monitoring-label">Required Monitoring:</span>
                                                        <span id="finalMonitoring" class="monitoring-value">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-3">
                                        <button type="button" class="btn btn-primary btn-lg" onclick="calculateAcceptance()">
                                            <i class="fas fa-calculator me-2"></i>Calculate Acceptance
                                        </button>
                                        <button type="button" class="btn btn-info btn-lg me-2" id="saveProgressBtn" onclick="saveProgress()">
                                            <i class="fas fa-save me-2"></i>Save Progress
                                        </button>
                                        <button type="button" class="btn btn-warning btn-lg me-2" id="clearFormBtn" onclick="clearForm()">
                                            <i class="fas fa-eraser me-2"></i>Clear Form
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn" style="display: none;" onclick="// console.log('Submit button clicked');">
                                            <i class="fas fa-check me-2"></i>Confirm & Save
                                        </button>
                                        <a href="{{ route('risks.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Function to update risk details display and hidden fields
function updateRiskDetails(selectElement, prefix) {
    console.log('updateRiskDetails called with prefix:', prefix);
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    if (selectedOption.value) {
        console.log('Selected option:', selectedOption.value, 'Data:', selectedOption.dataset);
        // Update all the detail spans
        const impactEl = document.getElementById(prefix + '_impact');
        const likelihoodEl = document.getElementById(prefix + '_likelihood');
        const ratingEl = document.getElementById(prefix + '_rating');
        const ownerEl = document.getElementById(prefix + '_owner');
        const statusEl = document.getElementById(prefix + '_status');
        const mitigationEl = document.getElementById(prefix + '_mitigation');
        
        console.log('Elements found:', {
            impact: !!impactEl,
            likelihood: !!likelihoodEl,
            rating: !!ratingEl,
            owner: !!ownerEl,
            status: !!statusEl,
            mitigation: !!mitigationEl
        });
        
        if (impactEl) impactEl.textContent = selectedOption.dataset.impact || '-';
        if (likelihoodEl) likelihoodEl.textContent = selectedOption.dataset.likelihood || '-';
        if (ratingEl) ratingEl.textContent = selectedOption.dataset.rating || '-';
        if (ownerEl) ownerEl.textContent = selectedOption.dataset.owner || '-';
        if (statusEl) statusEl.textContent = selectedOption.dataset.status || '-';
        if (mitigationEl) mitigationEl.textContent = selectedOption.dataset.mitigation || '-';
        
        // Calculate points based on OFFICIAL RISK ASSESSMENT TABLE
        const impact = selectedOption.dataset.impact;
        const likelihood = selectedOption.dataset.likelihood;
        
        // OFFICIAL POINTS CALCULATION FROM YOUR TABLE
        let points = 0;
        
        if (impact === 'High' && likelihood === 'High') points = 5;
        else if (impact === 'High' && likelihood === 'Medium') points = 5; // CR-01: High/Medium = 5 points
        else if (impact === 'High' && likelihood === 'Low') points = 3;
        else if (impact === 'Medium' && likelihood === 'High') points = 4; // CR-02: Medium/High = 4 points
        else if (impact === 'Medium' && likelihood === 'Medium') points = 3; // CR-03, SR-02, PR-02: Medium/Medium = 3 points
        else if (impact === 'Medium' && likelihood === 'Low') points = 2; // DR-02: Medium/Low = 2 points
        else if (impact === 'Low' && likelihood === 'High') points = 2;
        else if (impact === 'Low' && likelihood === 'Medium') points = 1; // SR-03, PR-03: Low/Medium = 1 point
        else if (impact === 'Low' && likelihood === 'Low') points = 1;
        else points = 0;
        
        const pointsEl = document.getElementById(prefix + '_points');
        console.log('Points element found:', !!pointsEl, 'Points value:', points);
        if (pointsEl) {
            pointsEl.textContent = points;
            pointsEl.className = 'points-badge points-' + points;
        }
        
        // Update badge classes for styling
        if (impactEl) impactEl.className = 'risk-badge impact-' + selectedOption.dataset.impact.toLowerCase();
        if (likelihoodEl) likelihoodEl.className = 'risk-badge likelihood-' + selectedOption.dataset.impact.toLowerCase();
        if (ratingEl) ratingEl.className = 'risk-badge rating-' + selectedOption.dataset.rating.toLowerCase();
        if (statusEl) statusEl.className = 'status-badge status-' + selectedOption.dataset.status.toLowerCase();
        
        // Update comprehensive hidden fields for form submission
        document.getElementById(prefix + '_risk_id').value = selectedOption.value;
        document.getElementById(prefix + '_risk_name').value = selectedOption.dataset.name;
        document.getElementById(prefix + '_impact_hidden').value = selectedOption.dataset.impact;
        document.getElementById(prefix + '_likelihood_hidden').value = selectedOption.dataset.likelihood;
        document.getElementById(prefix + '_risk_rating_hidden').value = selectedOption.dataset.rating;
        document.getElementById(prefix + '_points_hidden').value = points;
        document.getElementById(prefix + '_mitigation_hidden').value = selectedOption.dataset.mitigation;
        document.getElementById(prefix + '_owner_hidden').value = selectedOption.dataset.owner;
        document.getElementById(prefix + '_status_hidden').value = selectedOption.dataset.status;
        
        // Also update the fields without _hidden suffix for controller compatibility
        const impactField = document.getElementById(prefix + '_impact');
        const likelihoodField = document.getElementById(prefix + '_likelihood');
        const ratingField = document.getElementById(prefix + '_risk_rating');
        const mitigationField = document.getElementById(prefix + '_mitigation');
        const ownerField = document.getElementById(prefix + '_owner');
        const statusField = document.getElementById(prefix + '_status');
        
        if (impactField) impactField.value = selectedOption.dataset.impact;
        if (likelihoodField) likelihoodField.value = selectedOption.dataset.likelihood;
        if (ratingField) ratingField.value = selectedOption.dataset.rating;
        if (mitigationField) mitigationField.value = selectedOption.dataset.mitigation;
        if (ownerField) ownerField.value = selectedOption.dataset.owner;
        if (statusField) statusField.value = selectedOption.dataset.status;
        
        // Update legacy fields for backward compatibility (use highest risk values)
        updateLegacyFields();
        
        // Calculate overall risk assessment
        calculateOverallRisk();
    } else {
        // Reset to default
        document.getElementById(prefix + '_impact').textContent = '-';
        document.getElementById(prefix + '_likelihood').textContent = '-';
        document.getElementById(prefix + '_rating').textContent = '-';
        document.getElementById(prefix + '_points').textContent = '-';
        document.getElementById(prefix + '_owner').textContent = '-';
        document.getElementById(prefix + '_status').textContent = '-';
        document.getElementById(prefix + '_mitigation').textContent = '-';
        
        // Remove color coding classes
        document.getElementById(prefix + '_impact').className = 'risk-badge';
        document.getElementById(prefix + '_likelihood').className = 'risk-badge';
        document.getElementById(prefix + '_rating').className = 'risk-badge';
        document.getElementById(prefix + '_points').className = 'points-badge';
        document.getElementById(prefix + '_status').className = 'status-badge';
        
        // Clear hidden fields
        document.getElementById(prefix + '_risk_id').value = '';
        document.getElementById(prefix + '_risk_name').value = '';
        document.getElementById(prefix + '_impact_hidden').value = '';
        document.getElementById(prefix + '_likelihood_hidden').value = '';
        document.getElementById(prefix + '_risk_rating_hidden').value = '';
        document.getElementById(prefix + '_points_hidden').value = '';
        document.getElementById(prefix + '_mitigation_hidden').value = '';
        document.getElementById(prefix + '_owner_hidden').value = '';
        document.getElementById(prefix + '_status_hidden').value = '';
        
        // Recalculate overall risk assessment
        calculateOverallRisk();
    }
}

// Function to update legacy fields with highest risk values
function updateLegacyFields() {
    const srPoints = parseInt(document.getElementById('sr_points_hidden').value) || 0;
    const crPoints = parseInt(document.getElementById('cr_points_hidden').value) || 0;
    const prPoints = parseInt(document.getElementById('pr_points_hidden').value) || 0;
    const drPoints = parseInt(document.getElementById('dr_points_hidden').value) || 0;
    
    // Find the risk with highest points for legacy fields
    let highestRisk = { points: 0, impact: '', likelihood: '', rating: '', mitigation: '', owner: '', status: '' };
    
    if (srPoints > highestRisk.points) {
        highestRisk = {
            points: srPoints,
            impact: document.getElementById('sr_impact_hidden').value,
            likelihood: document.getElementById('sr_likelihood_hidden').value,
            rating: document.getElementById('sr_risk_rating_hidden').value,
            mitigation: document.getElementById('sr_mitigation_hidden').value,
            owner: document.getElementById('sr_owner_hidden').value,
            status: document.getElementById('sr_status_hidden').value
        };
    }
    
    if (crPoints > highestRisk.points) {
        highestRisk = {
            points: crPoints,
            impact: document.getElementById('cr_impact_hidden').value,
            likelihood: document.getElementById('cr_likelihood_hidden').value,
            rating: document.getElementById('cr_risk_rating_hidden').value,
            mitigation: document.getElementById('cr_mitigation_hidden').value,
            owner: document.getElementById('cr_owner_hidden').value,
            status: document.getElementById('cr_status_hidden').value
        };
    }
    
    if (prPoints > highestRisk.points) {
        highestRisk = {
            points: prPoints,
            impact: document.getElementById('pr_impact_hidden').value,
            likelihood: document.getElementById('pr_likelihood_hidden').value,
            rating: document.getElementById('pr_risk_rating_hidden').value,
            mitigation: document.getElementById('pr_mitigation_hidden').value,
            owner: document.getElementById('pr_owner_hidden').value,
            status: document.getElementById('pr_status_hidden').value
        };
    }
    
    if (drPoints > highestRisk.points) {
        highestRisk = {
            points: drPoints,
            impact: document.getElementById('dr_impact_hidden').value,
            likelihood: document.getElementById('dr_impact_hidden').value,
            rating: document.getElementById('dr_risk_rating_hidden').value,
            mitigation: document.getElementById('dr_mitigation_hidden').value,
            owner: document.getElementById('dr_owner_hidden').value,
            status: document.getElementById('dr_status_hidden').value
        };
    }
    
    // Update legacy fields with highest risk values
    document.getElementById('risk_description').value = 'Multiple risk categories assessed';
    document.getElementById('risk_category').value = 'Comprehensive';
    document.getElementById('impact').value = highestRisk.impact;
    document.getElementById('likelihood').value = highestRisk.likelihood;
    document.getElementById('status').value = highestRisk.status;
    document.getElementById('mitigation_strategies').value = highestRisk.mitigation;
    document.getElementById('owner').value = highestRisk.owner;
}

// Function to calculate overall risk assessment
function calculateOverallRisk() {
    const srPoints = parseInt(document.getElementById('sr_points').textContent) || 0;
    const crPoints = parseInt(document.getElementById('cr_points').textContent) || 0;
    const prPoints = parseInt(document.getElementById('pr_points').textContent) || 0;
    const drPoints = parseInt(document.getElementById('dr_points').textContent) || 0;
    
    const totalPoints = srPoints + crPoints + prPoints + drPoints;
    
    // Update total points display
    document.getElementById('totalPoints').textContent = totalPoints;
    
    // Determine overall risk rating based on OFFICIAL TABLE
    let overallRiskRating = 'Not Calculated';
    let clientDecision = 'Pending';
    let monitoringFrequency = '-';
    
    if (totalPoints >= 20) {
        overallRiskRating = 'Very High-risk';
        clientDecision = 'Do not accept client';
        monitoringFrequency = 'N/A';
    } else if (totalPoints >= 17) {
        overallRiskRating = 'High-risk';
        clientDecision = 'Accept client';
        monitoringFrequency = 'Quarterly review';
    } else if (totalPoints >= 15) {
        overallRiskRating = 'Medium-risk';
        clientDecision = 'Accept client';
        monitoringFrequency = 'Bi-Annually';
    } else if (totalPoints >= 10) {
        overallRiskRating = 'Low-risk';
        clientDecision = 'Accept client';
        monitoringFrequency = 'Annually';
    } else if (totalPoints > 0) {
        overallRiskRating = 'Low-risk';
        clientDecision = 'Accept client';
        monitoringFrequency = 'Annually';
    }
    
    // Update display
    document.getElementById('overallRiskRating').textContent = overallRiskRating;
    document.getElementById('clientDecision').textContent = clientDecision;
    document.getElementById('monitoringFrequency').textContent = monitoringFrequency;
    
    // Update comprehensive hidden fields for form submission
    document.getElementById('total_risk_points_hidden').value = totalPoints;
    document.getElementById('overall_risk_rating_hidden').value = overallRiskRating;
    document.getElementById('client_acceptance_hidden').value = clientDecision;
    document.getElementById('ongoing_monitoring_hidden').value = monitoringFrequency;
    
    // Update new client information hidden fields
    document.getElementById('client_email_hidden').value = document.getElementById('client_email').value;
    document.getElementById('client_industry_hidden').value = document.getElementById('client_industry').value;
    
    // Update styling based on risk level
    const totalPointsElement = document.getElementById('totalPoints');
    const overallRatingElement = document.getElementById('overallRiskRating');
    const clientDecisionElement = document.getElementById('clientDecision');
    
    // Remove existing classes
    totalPointsElement.className = 'result-value';
    overallRatingElement.className = 'result-value';
    clientDecisionElement.className = 'result-value';
    
    // Add appropriate classes
    if (totalPoints >= 20) {
        totalPointsElement.classList.add('text-danger', 'fw-bold');
        overallRatingElement.classList.add('text-danger', 'fw-bold');
        clientDecisionElement.classList.add('text-danger', 'fw-bold');
    } else if (totalPoints >= 17) {
        totalPointsElement.classList.add('text-danger', 'fw-bold');
        overallRatingElement.classList.add('text-danger', 'fw-bold');
        clientDecisionElement.classList.add('text-success', 'fw-bold');
    } else if (totalPoints >= 15) {
        totalPointsElement.classList.add('text-warning', 'fw-bold');
        overallRatingElement.classList.add('text-warning', 'fw-bold');
        clientDecisionElement.classList.add('text-success', 'fw-bold');
    } else if (totalPoints > 0) {
        totalPointsElement.classList.add('text-success', 'fw-bold');
        overallRatingElement.classList.add('text-success', 'fw-bold');
        clientDecisionElement.classList.add('text-success', 'fw-bold');
    }
}

// Add event listeners to all risk selection dropdowns
document.addEventListener('DOMContentLoaded', function() {
    // Service Risk (SR)
    document.getElementById('sr_selection').addEventListener('change', function() {
        updateRiskDetails(this, 'sr');
    });
    
    // Client Risk (CR)
    document.getElementById('cr_selection').addEventListener('change', function() {
        updateRiskDetails(this, 'cr');
    });
    
    // Payment Risk (PR)
    document.getElementById('pr_selection').addEventListener('change', function() {
        updateRiskDetails(this, 'pr');
    });
    
    // Delivery Risk (DR)
    document.getElementById('dr_selection').addEventListener('change', function() {
        updateRiskDetails(this, 'dr');
    });
    
    // Screening Status
    document.getElementById('screening_status').addEventListener('change', function() {
        document.getElementById('client_screening_result_hidden').value = this.value;
        validateField(this);
    });
    
    // DCS Risk Appetite
    document.getElementById('dcs_risk_appetite').addEventListener('change', function() {
        document.getElementById('dcs_risk_appetite_hidden').value = this.value;
        validateField(this);
    });
    
    // Set today's date as default and enforce date restrictions
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('assessment_date').value = today;
    
    // Add validation to prevent date changes
    document.getElementById('assessment_date').addEventListener('change', function() {
        if (this.value !== today) {
            alert('Only the current date is allowed for assessments. Please select today\'s date.');
            this.value = today;
        }
    });

    // --- KYC dynamic UI behavior ---
    const clientType = document.getElementById('client_type');
    const nationality = document.getElementById('nationality');
    const isMinor = document.getElementById('is_minor');
    const companyNationality = document.getElementById('company_nationality');

    function toggleKycSections() {
        const type = clientType ? clientType.value : '';
        document.querySelectorAll('.individual-only').forEach(function(el){ 
            const isVisible = (type === 'Individual');
            el.style.display = isVisible ? '' : 'none';
            // Disable inputs in hidden sections to prevent HTML5 validation errors
            el.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = !isVisible;
                if (!isVisible) input.value = '';
            });
        });
        document.querySelectorAll('.juristic-only').forEach(function(el){ 
            const isVisible = (type === 'Juristic');
            el.style.display = isVisible ? '' : 'none';
            // Disable inputs in hidden sections, but preserve values for critical fields
            el.querySelectorAll('input, select, textarea').forEach(input => {
                // Don't disable company_nationality and tax_certificate to ensure they submit
                if (input.id === 'company_nationality' || input.id === 'tax_certificate') {
                    // Keep these enabled but hidden via CSS
                    input.disabled = false;
                    if (!isVisible) {
                        input.style.visibility = 'hidden';
                        input.style.position = 'absolute';
                        input.removeAttribute('required'); // Remove required when hidden
                    } else {
                        input.style.visibility = '';
                        input.style.position = '';
                        if (input.id === 'company_nationality') {
                            input.setAttribute('required', 'required'); // Add required when visible for juristic
                        }
                    }
                } else {
                    input.disabled = !isVisible;
                    if (!isVisible) input.value = '';
                }
            });
        });
        
        // Handle juristic-foreign fields (foreign company documents)
        const companyNat = companyNationality ? companyNationality.value : '';
        document.querySelectorAll('.juristic-foreign').forEach(function(el){ 
            const isVisible = (type === 'Juristic' && companyNat === 'Foreign');
            el.style.display = isVisible ? '' : 'none';
            // Disable inputs in hidden sections
            el.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = !isVisible;
                if (!isVisible) input.value = '';
            });
        });

        const nat = nationality ? nationality.value : '';
        document.querySelectorAll('.namibian-only').forEach(function(el){ 
            const isVisible = (type === 'Individual' && nat === 'Namibian');
            el.style.display = isVisible ? '' : 'none';
            // Disable inputs in hidden sections
            el.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = !isVisible;
                if (!isVisible) input.value = '';
            });
        });
        document.querySelectorAll('.foreign-only').forEach(function(el){ 
            const isVisible = (type === 'Individual' && nat === 'Foreign');
            el.style.display = isVisible ? '' : 'none';
            // Disable inputs in hidden sections
            el.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = !isVisible;
                if (!isVisible) input.value = '';
            });
        });

        const minorVal = isMinor ? isMinor.value : '';
        document.querySelectorAll('.minor-only').forEach(function(el){ 
            const isVisible = (type === 'Individual' && nat === 'Namibian' && minorVal === '1');
            el.style.display = isVisible ? '' : 'none';
            // Disable inputs in hidden sections
            el.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = !isVisible;
                if (!isVisible) input.value = '';
            });
        });
        
        // Handle adult-only fields (Namibian adults - not minors)
        document.querySelectorAll('.adult-only').forEach(function(el){ 
            const isVisible = (type === 'Individual' && nat === 'Namibian' && minorVal === '0');
            el.style.display = isVisible ? '' : 'none';
            // Disable inputs in hidden sections
            el.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = !isVisible;
                if (!isVisible) input.value = '';
            });
        });

        // Explicitly ensure only one of ID or Birth Certificate is shown based on minor selection
        const idDocInput = document.getElementById('id_document');
        const birthCertInput = document.getElementById('birth_certificate');
        const passportDocInput = document.getElementById('passport_document');
        const idDocWrap = idDocInput ? idDocInput.closest('.col-md-3') : null;
        const birthCertWrap = birthCertInput ? birthCertInput.closest('.col-md-3') : null;
        const passportDocWrap = passportDocInput ? passportDocInput.closest('.col-md-3') : null;

        // Visibility rules
        // Birth certificate: ONLY if minor (any nationality) and only relevant for Namibian individuals per policy
        const showBirth = (type === 'Individual' && minorVal === '1' && nat === 'Namibian');
        // Passport: ONLY if foreign individual and not minor
        const showPassport = (type === 'Individual' && nat === 'Foreign' && minorVal !== '1');
        // ID: ONLY if Namibian individual and not minor
        const showIdDoc = (type === 'Individual' && nat === 'Namibian' && minorVal !== '1');

        if (idDocWrap) { idDocWrap.style.display = showIdDoc ? '' : 'none'; idDocWrap.hidden = !showIdDoc; }
        if (birthCertWrap) { birthCertWrap.style.display = showBirth ? '' : 'none'; birthCertWrap.hidden = !showBirth; }

        // Clear and disable the opposite input so both cannot be submitted
        if (idDocInput) {
            idDocInput.disabled = !showIdDoc;
            if (!showIdDoc) idDocInput.value = '';
        }
        if (birthCertInput) {
            birthCertInput.disabled = !showBirth;
            if (!showBirth) birthCertInput.value = '';
        }
        if (passportDocInput) {
            passportDocInput.disabled = !showPassport;
            if (!showPassport) passportDocInput.value = '';
        }
        if (passportDocWrap) { passportDocWrap.style.display = showPassport ? '' : 'none'; passportDocWrap.hidden = !showPassport; }

        // Toggle required attributes for better UX (server also enforces)
        const idDoc = document.getElementById('id_document');
        const birthCert = document.getElementById('birth_certificate');
        const passDoc = document.getElementById('passport_document');
        const por = document.getElementById('proof_of_residence');
        const porLabel = document.getElementById('por_label');
        const kycForm = document.getElementById('kyc_form');
        const gender = document.getElementById('gender');
        const minor = document.getElementById('is_minor');
        const registration = document.getElementById('registration_number');
        const entityType = document.getElementById('entity_type');
        const tradingAddress = document.getElementById('trading_address');
        const idNum = document.getElementById('id_number');
        const idNumWrap = idNum ? idNum.closest('.col-md-3') : null;
        const passNum = document.getElementById('passport_number');
        const passNumWrap = passNum ? passNum.closest('.col-md-3') : null;

        if (idDoc) idDoc.required = showIdDoc;
        if (birthCert) birthCert.required = showBirth;
        if (passDoc) passDoc.required = showPassport;
        if (por) por.required = (type === 'Individual' || type === 'Juristic');
        if (porLabel) porLabel.textContent = (type === 'Juristic') ? 'Trading Address Residence' : 'Proof of Residence';
        if (kycForm) kycForm.required = true;

        // Required on selects
        if (gender) {
            gender.required = (type === 'Individual');
            gender.disabled = (type !== 'Individual');
        }
        if (nationality) {
            nationality.required = (type === 'Individual');
            nationality.disabled = (type !== 'Individual');
        }
        if (minor) {
            // is_minor should ONLY be required for Individual, not Juristic
            minor.required = (type === 'Individual');
            minor.disabled = (type !== 'Individual');
            if (type !== 'Individual') minor.value = '';
        }
        if (registration) {
            registration.required = (type === 'Juristic');
            registration.disabled = (type !== 'Juristic');
        }
        if (entityType) {
            entityType.required = (type === 'Juristic');
            entityType.disabled = (type !== 'Juristic');
        }
        if (tradingAddress) {
            tradingAddress.required = (type === 'Juristic');
            tradingAddress.disabled = (type !== 'Juristic');
        }
        
        const incomeSource = document.getElementById('income_source');
        if (incomeSource) {
            incomeSource.required = true; // Always required
            incomeSource.disabled = false; // Never disabled
        }

        // Toggle ID/Passport number fields visibility and requirement
        const showIdNum = type === 'Individual' && nat === 'Namibian' && minorVal !== '1';
        const showPassNum = type === 'Individual' && nat === 'Foreign' && minorVal !== '1';
        if (idNumWrap) idNumWrap.style.display = showIdNum ? '' : 'none';
        if (idNum) {
            idNum.disabled = !showIdNum;
            idNum.required = showIdNum;
            if (!showIdNum) idNum.value = '';
        }
        if (passNumWrap) passNumWrap.style.display = showPassNum ? '' : 'none';
        if (passNum) {
            passNum.disabled = !showPassNum;
            passNum.required = showPassNum;
            if (!showPassNum) passNum.value = '';
        }
    }

    if (clientType) clientType.addEventListener('change', toggleKycSections);
    if (nationality) nationality.addEventListener('change', toggleKycSections);
    if (isMinor) isMinor.addEventListener('change', toggleKycSections);
    if (companyNationality) companyNationality.addEventListener('change', toggleKycSections);
    toggleKycSections();
    
    // Set default values for required fields
    document.getElementById('screening_status').value = 'Pass';
    document.getElementById('dcs_risk_appetite').value = 'Moderate';
    
    // Update hidden fields with default values
    document.getElementById('client_screening_result_hidden').value = 'Pass';
    document.getElementById('dcs_risk_appetite_hidden').value = 'Moderate';
    
    // Add real-time validation for all required fields
    addRealTimeValidation();
    
    // Initialize auto-save
    initializeAutoSave();
    
    // Load saved progress if available
    loadProgress();
    
    // Auto-save progress every 30 seconds
    setInterval(autoSaveProgress, 30000);
    
    // Warn user before leaving page with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        const hasData = document.getElementById('client_name').value.trim() || 
                       document.getElementById('sr_selection').value ||
                       document.getElementById('cr_selection').value ||
                       document.getElementById('pr_selection').value ||
                       document.getElementById('dr_selection').value;
        
        if (hasData) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
});

// Auto-save functionality
let autoSaveInterval;
let isAutoSaveEnabled = true;
let lastSavedTime = null;

function initializeAutoSave() {
    // Add auto-save toggle button
    addAutoSaveToggle();
    
    // Start auto-save interval (every 30 seconds)
    autoSaveInterval = setInterval(autoSaveProgress, 30000);
    
    // Auto-save on form changes
    addFormChangeListeners();
    
    // Show auto-save status
    showAutoSaveStatus();
}

function addAutoSaveToggle() {
    const form = document.querySelector('form');
    if (form && !document.getElementById('auto-save-toggle')) {
        const toggleContainer = document.createElement('div');
        toggleContainer.className = 'auto-save-controls mb-3';
        toggleContainer.innerHTML = `
            <div class="d-flex align-items-center justify-content-between">
                <div class="auto-save-status">
                    <i class="fas fa-save me-2"></i>
                    <span id="auto-save-text">Auto-save: ON</span>
                    <small id="last-saved" class="text-muted ms-2"></small>
                </div>
                <button type="button" id="auto-save-toggle" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-pause me-1"></i>Pause
                </button>
            </div>
        `;
        
        form.insertBefore(toggleContainer, form.firstChild);
        
        // Add toggle functionality
        document.getElementById('auto-save-toggle').onclick = toggleAutoSave;
    }
}

function toggleAutoSave() {
    const toggleBtn = document.getElementById('auto-save-toggle');
    const statusText = document.getElementById('auto-save-text');
    
    if (isAutoSaveEnabled) {
        clearInterval(autoSaveInterval);
        isAutoSaveEnabled = false;
        toggleBtn.innerHTML = '<i class="fas fa-play me-1"></i>Resume';
        statusText.textContent = 'Auto-save: OFF';
        showNotification('Auto-save paused', 'warning');
    } else {
        autoSaveInterval = setInterval(autoSaveProgress, 30000);
        isAutoSaveEnabled = true;
        toggleBtn.innerHTML = '<i class="fas fa-pause me-1"></i>Pause';
        statusText.textContent = 'Auto-save: ON';
        showNotification('Auto-save resumed', 'success');
        // Save immediately when resuming
        autoSaveProgress();
    }
}

function addFormChangeListeners() {
    const form = document.querySelector('form');
    if (!form) return;
    
    // Listen to all form inputs
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', debounce(autoSaveProgress, 2000));
        input.addEventListener('change', debounce(autoSaveProgress, 1000));
    });
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function autoSaveProgress() {
    if (!isAutoSaveEnabled) return;
    
    try {
        const formData = collectFormData();
        localStorage.setItem('risk_assessment_progress', JSON.stringify({
            ...formData,
            timestamp: new Date().toISOString()
        }));
        
        lastSavedTime = new Date();
        updateLastSavedTime();
        showAutoSaveIndicator();
        
    } catch (error) {
        console.error('Auto-save failed:', error);
        showNotification('Auto-save failed', 'error');
    }
}

function collectFormData() {
    const form = document.querySelector('form');
    const formData = {};
    
    if (!form) return formData;
    
    // Collect all form inputs
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.name && input.type !== 'submit' && input.type !== 'button') {
            if (input.type === 'checkbox' || input.type === 'radio') {
                formData[input.name] = input.checked;
            } else {
                formData[input.name] = input.value;
            }
        }
    });
    
    return formData;
}

function updateLastSavedTime() {
    const lastSavedElement = document.getElementById('last-saved');
    if (lastSavedElement && lastSavedTime) {
        const timeAgo = getTimeAgo(lastSavedTime);
        lastSavedElement.textContent = `Last saved: ${timeAgo}`;
    }
}

function getTimeAgo(date) {
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) {
        return 'just now';
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes}m ago`;
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours}h ago`;
    } else {
        return date.toLocaleDateString();
    }
}

function showAutoSaveIndicator() {
    const statusText = document.getElementById('auto-save-text');
    if (statusText) {
        const originalText = statusText.textContent;
        statusText.textContent = 'Auto-save: SAVED';
        statusText.style.color = 'var(--logo-success)';
        
        setTimeout(() => {
            statusText.textContent = originalText;
            statusText.style.color = '';
        }, 2000);
    }
}

function showAutoSaveStatus() {
    const savedData = localStorage.getItem('risk_assessment_progress');
    if (savedData) {
        try {
            const data = JSON.parse(savedData);
            if (data.timestamp) {
                lastSavedTime = new Date(data.timestamp);
                updateLastSavedTime();
            }
        } catch (error) {
            console.error('Failed to parse saved data:', error);
        }
    }
}

// Function to add real-time validation
function addRealTimeValidation() {
    const requiredFields = document.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        // Validate on blur (when user leaves the field)
        field.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Validate on input (as user types)
        if (field.type === 'text' || field.type === 'email') {
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    clearFieldError(this);
                    this.classList.remove('is-invalid');
                }
            });
        }
        
        // Validate on change (for select fields)
        if (field.tagName === 'SELECT') {
            field.addEventListener('change', function() {
                validateField(this);
            });
        }
    });
}

// Function to validate a single field
function validateField(field) {
    if (!field.value.trim()) {
        field.classList.add('is-invalid');
        showFieldError(field, 'This field is required');
    } else {
        field.classList.remove('is-invalid');
        clearFieldError(field);
    }
}

// Function to save progress to localStorage
function saveProgress() {
    const formData = {
        client_name: document.getElementById('client_name').value,
        client_email: document.getElementById('client_email').value,
        client_industry: document.getElementById('client_industry').value,
        assessment_date: document.getElementById('assessment_date').value,
        screening_status: document.getElementById('screening_status').value,
        screening_status: document.getElementById('screening_status').value,
        dcs_risk_appetite: document.getElementById('dcs_risk_appetite').value,
        sr_selection: document.getElementById('sr_selection').value,
        cr_selection: document.getElementById('cr_selection').value,
        pr_selection: document.getElementById('pr_selection').value,
        dr_selection: document.getElementById('dr_selection').value,
        timestamp: new Date().toISOString()
    };
    
    localStorage.setItem('risk_assessment_progress', JSON.stringify(formData));
    
    // Show success message
    showProgressMessage('Progress saved successfully!', 'success');
}

// Function to auto-save progress
function autoSaveProgress() {
    // Only auto-save if user has started filling the form
    if (document.getElementById('client_name').value.trim()) {
        saveProgress();
    }
}

// Function to load progress from localStorage
function loadProgress() {
    const savedProgress = localStorage.getItem('risk_assessment_progress');
    
    if (savedProgress) {
        try {
            const formData = JSON.parse(savedProgress);
            
            // Check if saved data is not too old (7 days)
            const savedDate = new Date(formData.timestamp);
            const currentDate = new Date();
            const daysDiff = (currentDate - savedDate) / (1000 * 60 * 60 * 24);
            
            if (daysDiff <= 7) {
                // Restore form data
                Object.keys(formData).forEach(key => {
                    const element = document.getElementById(key);
                    if (element && key !== 'timestamp') {
                        // Handle field name changes due to consolidation
                        if (key === 'client_identification_done') {
                            // Map old field to new screening_status field
                            const screeningElement = document.getElementById('screening_status');
                            if (screeningElement) {
                                if (formData[key] === 'Yes') {
                                    screeningElement.value = 'Pass';
                                } else if (formData[key] === 'No') {
                                    screeningElement.value = 'Fail';
                                } else {
                                    screeningElement.value = 'Pending';
                                }
                                screeningElement.dispatchEvent(new Event('change'));
                            }
                        } else {
                            element.value = formData[key];
                            
                            // Trigger change events for select fields
                            if (element.tagName === 'SELECT') {
                                element.dispatchEvent(new Event('change'));
                            }
                        }
                    }
                });
                
                showProgressMessage('Previous progress loaded successfully!', 'info');
            } else {
                // Clear old data
                localStorage.removeItem('risk_assessment_progress');
            }
        } catch (error) {
            console.error('Error loading progress:', error);
            localStorage.removeItem('risk_assessment_progress');
        }
    }
}

// Function to show progress message
function showProgressMessage(message, type) {
    // Remove existing message
    const existingMessage = document.querySelector('.progress-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert alert-${type} progress-message`;
    messageDiv.style.position = 'fixed';
    messageDiv.style.top = '20px';
    messageDiv.style.right = '20px';
    messageDiv.style.zIndex = '9999';
    messageDiv.style.minWidth = '300px';
    messageDiv.style.borderRadius = '8px';
    messageDiv.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    messageDiv.style.animation = 'slideInRight 0.3s ease';
    
    messageDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(messageDiv);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => messageDiv.remove(), 300);
        }
    }, 3000);
}

// Function to clear all form fields
function clearForm() {
    if (confirm('Are you sure you want to clear all form data? This action cannot be undone.')) {
        // Clear all visible form fields
        const fieldsToClear = [
            'client_name', 'client_email', 'client_industry', 'assessment_date',
            'screening_status', 'dcs_risk_appetite', 'sr_selection', 'cr_selection',
            'pr_selection', 'dr_selection'
        ];
        
        fieldsToClear.forEach(fieldId => {
            const element = document.getElementById(fieldId);
            if (element) {
                element.value = '';
                // Trigger change event for select fields to update displays
                if (element.tagName === 'SELECT') {
                    element.dispatchEvent(new Event('change'));
                }
            }
        });
        
        // Clear all hidden fields
        const hiddenFields = document.querySelectorAll('input[type="hidden"]');
        hiddenFields.forEach(field => {
            field.value = '';
        });
        
        // Reset risk detail displays to default state
        const riskTypes = ['sr', 'cr', 'pr', 'dr'];
        riskTypes.forEach(prefix => {
            const elements = [
                prefix + '_impact', prefix + '_likelihood', prefix + '_rating',
                prefix + '_points', prefix + '_owner', prefix + '_status', prefix + '_mitigation'
            ];
            
            elements.forEach(elementId => {
                const element = document.getElementById(elementId);
                if (element) {
                    element.textContent = '-';
                    element.className = element.className.split(' ')[0]; // Keep base class, remove color classes
                }
            });
        });
        
        // Reset overall risk calculation display
        const overallElements = ['totalPoints', 'overallRiskRating', 'clientDecision', 'monitoringFrequency'];
        overallElements.forEach(elementId => {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = '-';
            }
        });
        
        // Reset final decision display
        const finalElements = ['finalTotalPoints', 'finalRiskRating', 'finalClientDecision', 'finalMonitoring'];
        finalElements.forEach(elementId => {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = '-';
            }
        });
        
        // Hide decision details and show initial text
        const decisionText = document.querySelector('.decision-text');
        const decisionDetails = document.querySelector('.decision-details');
        if (decisionText) decisionText.style.display = 'block';
        if (decisionDetails) decisionDetails.style.display = 'none';
        
        // Hide monitoring section
        const monitoringSection = document.getElementById('monitoringSection');
        if (monitoringSection) monitoringSection.style.display = 'none';
        
        // Clear localStorage progress
        localStorage.removeItem('risk_assessment_progress');
        
        // Clear any error messages
        clearAllErrorMessages();
        
        // Show success message
        showProgressMessage('Form cleared successfully!', 'success');
        
        // Hide submit button since form is empty
        document.getElementById('submitBtn').style.display = 'none';
    }
}

// Add CSS animations for progress messages
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Function to calculate client acceptance decision
function calculateAcceptance() {
    // Check if all risks are selected
    const srSelected = document.getElementById('sr_selection').value;
    const crSelected = document.getElementById('cr_selection').value;
    const prSelected = document.getElementById('pr_selection').value;
    const drSelected = document.getElementById('dr_selection').value;
    
    if (!srSelected || !crSelected || !prSelected || !drSelected) {
        alert('Please select a risk from each category (SR, CR, PR, DR) before calculating acceptance.');
        return;
    }
    
    // Get total points and other values
    const totalPoints = parseInt(document.getElementById('totalPoints').textContent) || 0;
    const overallRiskRating = document.getElementById('overallRiskRating').textContent;
    const clientDecision = document.getElementById('clientDecision').textContent;
    const monitoringFrequency = document.getElementById('monitoringFrequency').textContent;
    
    // Update final decision display
    document.getElementById('finalTotalPoints').textContent = totalPoints;
    document.getElementById('finalRiskRating').textContent = overallRiskRating;
    document.getElementById('finalClientDecision').textContent = clientDecision;
    document.getElementById('finalMonitoring').textContent = monitoringFrequency;
    
    // Show decision details and hide initial text
    document.querySelector('.decision-text').style.display = 'none';
    document.querySelector('.decision-details').style.display = 'block';
    
    // Show monitoring section only if client is accepted
    if (clientDecision.includes('Accept')) {
        document.getElementById('monitoringSection').style.display = 'block';
    } else {
        document.getElementById('monitoringSection').style.display = 'none';
    }
    
    // Style the final decision based on result
    const finalDecisionElement = document.getElementById('finalClientDecision');
    const finalDecisionCard = document.querySelector('.acceptance-decision-card');
    
    // Remove existing classes
    finalDecisionCard.classList.remove('decision-accept', 'decision-reject');
    finalDecisionElement.classList.remove('text-success', 'text-danger', 'fw-bold');
    
    // Add appropriate styling
    if (clientDecision.includes('Do not accept') || clientDecision.includes('Reject')) {
        finalDecisionCard.classList.add('decision-reject');
        finalDecisionElement.classList.add('text-danger', 'fw-bold');
        
        // Show rejection message
        showDecisionMessage('REJECT CLIENT', 'This client poses too high a risk and should be rejected.', 'danger');
    } else if (clientDecision.includes('Accept')) {
        finalDecisionCard.classList.add('decision-accept');
        finalDecisionElement.classList.add('text-success', 'fw-bold');
        
        // Show acceptance message
        showDecisionMessage('ACCEPT CLIENT', `Client can be accepted with ${monitoringFrequency.toLowerCase()} monitoring.`, 'success');
    }
    
    // Show the submit button
    document.getElementById('submitBtn').style.display = 'block';
    
    // Ensure all required hidden fields are populated
    updateAllHiddenFields();
}

// Function to update all hidden fields before form submission
function updateAllHiddenFields() {
    // Update client screening and DCS fields
    const screeningStatus = document.getElementById('screening_status').value;
    const dcsRiskAppetite = document.getElementById('dcs_risk_appetite').value;
    
    document.getElementById('client_screening_result_hidden').value = screeningStatus;
    document.getElementById('dcs_risk_appetite_hidden').value = dcsRiskAppetite;
    
    // Update client screening date and result
    document.getElementById('client_screening_date_hidden').value = document.getElementById('assessment_date').value;
    document.getElementById('client_screening_result_hidden').value = document.getElementById('screening_status').value;
    
    // Update client email and industry
    document.getElementById('client_email_hidden').value = document.getElementById('client_email').value;
    document.getElementById('client_industry_hidden').value = document.getElementById('client_industry').value;
    
    // Update DCS comments
    document.getElementById('dcs_comments_hidden').value = 'Risk assessment completed via system';
    
    // Debug logging
    // console.log('Updated hidden fields:', {
    //     screening_status: screeningStatus,
    //     dcs_risk_appetite: dcsRiskAppetite,
    //     client_screening_date: document.getElementById('client_screening_date_hidden').value,
    //     client_screening_result: document.getElementById('client_screening_result_hidden').value
    // });
}

// Add form submission event listener
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Re-enable all fields before submission so they get submitted with the form
            // (They were disabled for validation purposes but need to be enabled to submit)
            const clientType = document.getElementById('client_type')?.value;
            if (clientType === 'Juristic') {
                document.querySelectorAll('.juristic-only input, .juristic-only select, .juristic-only textarea').forEach(input => {
                    input.disabled = false;
                });
                
                // Validate company_nationality for juristic clients
                const companyNat = document.getElementById('company_nationality');
                if (companyNat && !companyNat.value) {
                    e.preventDefault();
                    alert('Please select Company Nationality for juristic clients');
                    companyNat.focus();
                    return false;
                }
                
                // Debug: Log company_nationality value
                console.log('Company Nationality value:', companyNat?.value);
            }
            
            // Update all hidden fields before submission
            updateAllHiddenFields();
            
            // Clear previous error messages
            clearAllErrorMessages();
            
            // Validate all required fields
            const validationResult = validateAllFields();
            
            if (!validationResult.isValid) {
                e.preventDefault();
                
                // Log validation errors to console for debugging
                console.error('Form validation failed:', validationResult.errors);
                
                // Show error summary
                showErrorSummary(validationResult.errors);
                
                // Show alert with first error
                alert('Please fix the following errors before submitting:\n\n' + validationResult.errors.slice(0, 5).join('\n'));
                
                // Scroll to first error field
                if (validationResult.firstErrorField) {
                    scrollToField(validationResult.firstErrorField);
                }
                
                return false;
            }
            
            // Enhanced validation of hidden fields
            const hiddenFieldValidation = validateHiddenFields();
            
            if (!hiddenFieldValidation.isValid) {
                e.preventDefault();
                
                // Log hidden field validation errors
                console.error('Hidden field validation failed:', hiddenFieldValidation.errors);
                
                showErrorSummary(hiddenFieldValidation.errors);
                
                // Show alert with errors
                alert('Please fix the following errors before submitting:\n\n' + hiddenFieldValidation.errors.slice(0, 5).join('\n'));
                
                scrollToFirstError(hiddenFieldValidation.firstErrorField);
                return false;
            }
            
            // console.log('Form validation passed, submitting...');
            
            // Show loading indicator
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            }
            
            // Add retry mechanism for failed submissions
            addFormRetryMechanism();
            
            // Allow form to submit
            return true;
        });
    }
});

// Enhanced hidden field validation
function validateHiddenFields() {
    const errors = [];
    let firstErrorField = null;
    let isValid = true;
    
    // Check client screening result
    const screeningResultHidden = document.getElementById('client_screening_result_hidden');
    const screeningStatus = document.getElementById('screening_status');
    
    if (!screeningResultHidden || !screeningResultHidden.value.trim()) {
        if (screeningStatus && !screeningStatus.value.trim()) {
            errors.push('Client screening status is required');
            if (screeningStatus) {
                screeningStatus.classList.add('is-invalid');
                showFieldError(screeningStatus, 'Please select a screening status');
                if (!firstErrorField) firstErrorField = screeningStatus;
            }
            isValid = false;
        }
    }
    
    // Check DCS risk appetite
    const riskAppetiteHidden = document.getElementById('dcs_risk_appetite_hidden');
    const riskAppetiteSelect = document.getElementById('dcs_risk_appetite');
    
    if (!riskAppetiteHidden || !riskAppetiteHidden.value.trim()) {
        if (riskAppetiteSelect && !riskAppetiteSelect.value.trim()) {
            errors.push('DCS risk appetite is required');
            if (riskAppetiteSelect) {
                riskAppetiteSelect.classList.add('is-invalid');
                showFieldError(riskAppetiteSelect, 'Please select a risk appetite');
                if (!firstErrorField) firstErrorField = riskAppetiteSelect;
            }
            isValid = false;
        }
    }
    
    // Check risk selections
    const riskSelections = ['sr_selection', 'cr_selection', 'pr_selection', 'dr_selection'];
    const riskLabels = ['Service Risk', 'Client Risk', 'Payment Risk', 'Delivery Risk'];
    
    riskSelections.forEach((selectionId, index) => {
        const field = document.getElementById(selectionId);
        if (!field || !field.value.trim()) {
            errors.push(`${riskLabels[index]} selection is required`);
            if (field) {
                field.classList.add('is-invalid');
                showFieldError(field, 'Please select a risk from this category');
                if (!firstErrorField) firstErrorField = field;
            }
            isValid = false;
        }
    });
    
    return { isValid, errors, firstErrorField };
}

// Function to validate all fields
function validateAllFields() {
    const requiredFields = document.querySelectorAll('[required]');
    const errors = [];
    let firstErrorField = null;
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            showFieldError(field, 'This field is required');
            errors.push(`${field.previousElementSibling?.textContent?.replace(' *', '') || 'Field'} is required`);
            
            if (!firstErrorField) {
                firstErrorField = field;
            }
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
            clearFieldError(field);
        }
    });
    
    // Validate risk selections
    const riskSelections = ['sr_selection', 'cr_selection', 'pr_selection', 'dr_selection'];
    const riskLabels = ['Service Risk', 'Client Risk', 'Payment Risk', 'Delivery Risk'];
    
    riskSelections.forEach((selectionId, index) => {
        const field = document.getElementById(selectionId);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            showFieldError(field, 'Please select a risk from this category');
            errors.push(`${riskLabels[index]} selection is required`);
            
            if (!firstErrorField) {
                firstErrorField = field;
            }
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
            clearFieldError(field);
        }
    });
    
    return { isValid, errors, firstErrorField };
}

// Form retry mechanism
function addFormRetryMechanism() {
    // Add retry button if form submission fails
    const form = document.querySelector('form');
    if (form && !document.getElementById('retry-submission-btn')) {
        const retryBtn = document.createElement('button');
        retryBtn.type = 'button';
        retryBtn.id = 'retry-submission-btn';
        retryBtn.className = 'btn btn-warning btn-sm mt-2';
        retryBtn.innerHTML = '<i class="fas fa-redo me-2"></i>Retry Submission';
        retryBtn.style.display = 'none';
        retryBtn.onclick = function() {
            if (recoverFromValidationErrors()) {
                form.submit();
            }
        };
        
        form.appendChild(retryBtn);
    }
}

// Enhanced error recovery function
function recoverFromValidationErrors() {
    // Clear all error states
    clearAllErrorMessages();
    
    // Re-validate all fields
    const validationResult = validateAllFields();
    const hiddenValidationResult = validateHiddenFields();
    
    if (!validationResult.isValid || !hiddenValidationResult.isValid) {
        const allErrors = [...validationResult.errors, ...hiddenValidationResult.errors];
        showErrorSummary(allErrors);
        
        // Scroll to first error
        const firstError = validationResult.firstErrorField || hiddenValidationResult.firstErrorField;
        if (firstError) {
            scrollToField(firstError);
        }
        
        return false;
    }
    
    return true;
}

// Function to show field-specific error message
function showFieldError(field, message) {
    // Remove existing error message
    clearFieldError(field);
    
    // Create error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback d-block';
    errorDiv.textContent = message;
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '0.25rem';
    
    // Insert after the field
    field.parentNode.appendChild(errorDiv);
}

// Function to clear field-specific error message
function clearFieldError(field) {
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
}

// Function to clear all error messages
function clearAllErrorMessages() {
    document.querySelectorAll('.invalid-feedback').forEach(error => error.remove());
    document.querySelectorAll('.is-invalid').forEach(field => field.classList.remove('is-invalid'));
}

// Function to show error summary
function showErrorSummary(errors) {
    // Remove existing error summary
    const existingSummary = document.querySelector('.error-summary');
    if (existingSummary) {
        existingSummary.remove();
    }
    
    // Create error summary
    const summaryDiv = document.createElement('div');
    summaryDiv.className = 'alert alert-danger error-summary';
    summaryDiv.style.marginBottom = '1rem';
    summaryDiv.style.borderRadius = '8px';
    summaryDiv.style.border = '1px solid #f5c6cb';
    summaryDiv.style.backgroundColor = '#f8d7da';
    summaryDiv.style.color = '#721c24';
    summaryDiv.style.padding = '1rem';
    
    const title = document.createElement('h6');
    title.style.marginBottom = '0.5rem';
    title.style.fontWeight = '600';
    title.textContent = 'Please fix the following errors:';
    
    const errorList = document.createElement('ul');
    errorList.style.marginBottom = '0';
    errorList.style.paddingLeft = '1.5rem';
    
    errors.forEach(error => {
        const listItem = document.createElement('li');
        listItem.textContent = error;
        errorList.appendChild(listItem);
    });
    
    summaryDiv.appendChild(title);
    summaryDiv.appendChild(errorList);
    
    // Insert at the top of the form
    const form = document.querySelector('form');
    form.insertBefore(summaryDiv, form.firstChild);
    
    // Auto-remove after 10 seconds
    setTimeout(() => {
        if (summaryDiv.parentNode) {
            summaryDiv.remove();
        }
    }, 10000);
}

// Function to scroll to a specific field
function scrollToField(field) {
    const offset = 100; // Offset from top
    const fieldPosition = field.getBoundingClientRect().top + window.pageYOffset;
    const targetPosition = fieldPosition - offset;
    
    window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
    });
    
    // Highlight the field briefly
    field.style.transition = 'all 0.3s ease';
    field.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
    field.style.borderColor = '#dc3545';
    
    setTimeout(() => {
        field.style.boxShadow = '';
        field.style.borderColor = '';
    }, 2000);
}

// Function to show decision message
function showDecisionMessage(title, message, type) {
    // Create or update decision alert
    let alertDiv = document.getElementById('decisionAlert');
    if (!alertDiv) {
        alertDiv = document.createElement('div');
        alertDiv.id = 'decisionAlert';
        alertDiv.style.marginTop = '1rem';
        document.querySelector('.acceptance-decision-card').appendChild(alertDiv);
    }
    
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';
    
    alertDiv.innerHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${iconClass} me-2"></i>
            <strong>${title}</strong><br>
            ${message}
        </div>
    `;
}
</script>

<style>
.page-header {
    background: linear-gradient(135deg, #00072D 0%, #1a365d 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.page-subtitle {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
}

.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
}

.card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 12px 12px 0 0 !important;
    padding: 1.5rem;
}

.form-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.section-title {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    border-bottom: 2px solid #00072D;
    padding-bottom: 0.5rem;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 0.75rem;
    font-size: 0.875rem;
}

.form-control:focus, .form-select:focus {
    border-color: #00072D;
    box-shadow: 0 0 0 0.2rem rgba(0, 7, 45, 0.25);
}

/* Risk Matrix Table Styles */
.risk-matrix-table {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.risk-matrix-table th {
    background: #00072D !important;
    color: white !important;
    border: 1px solid #00072D !important;
    font-weight: 600;
    text-align: center;
    padding: 1rem 0.75rem;
    font-size: 0.875rem;
    white-space: nowrap;
}

.risk-matrix-table td {
    border: 1px solid #dee2e6;
    padding: 1rem 0.75rem;
    vertical-align: middle;
    text-align: center;
}

.risk-row:nth-child(even) {
    background-color: #f8f9fa;
}

.risk-row:hover {
    background-color: #e3f2fd;
}

/* Column Widths */
.category-col { width: 8%; }
.risk-selection-col { width: 20%; }
.impact-col { width: 8%; }
.likelihood-col { width: 8%; }
.rating-col { width: 8%; }
.points-col { width: 8%; }
.owner-col { width: 12%; }
.status-col { width: 8%; }
.mitigation-col { width: 20%; }

/* Category Badges */
.category-badge {
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.category-sr {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #f59e0b;
}

.category-cr {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

.category-pr {
    background: #fce7f3;
    color: #be185d;
    border: 1px solid #ec4899;
}

.category-dr {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #22c55e;
}

/* Risk Select Styling */
.risk-select {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    padding: 0.5rem;
    font-size: 0.875rem;
    background: white;
}

.risk-select:focus {
    border-color: #00072D;
    box-shadow: 0 0 0 0.2rem rgba(0, 7, 45, 0.25);
}

/* Risk Badges */
.risk-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    min-width: 60px;
}

.impact-high, .likelihood-high, .rating-high {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.impact-medium, .likelihood-medium, .rating-medium {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fed7aa;
}

.impact-low, .likelihood-low, .rating-low {
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    min-width: 60px;
}

.status-open {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

.status-closed {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #22c55e;
}

.owner-text {
    font-weight: 500;
    color: #374151;
    font-size: 0.875rem;
}

.mitigation-text {
    color: #6b7280;
    font-size: 0.75rem;
    line-height: 1.4;
    text-align: left;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Points Badge Styling */
.points-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    min-width: 30px;
    display: inline-block;
}

.points-5 {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.points-3 {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fed7aa;
}

.points-1 {
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

/* Scoring System Styling */
.scoring-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.scoring-title {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1rem;
    border-bottom: 2px solid #00072D;
    padding-bottom: 0.5rem;
}

.scoring-table {
    margin-bottom: 0;
}

.scoring-table th {
    background: #00072D !important;
    color: white !important;
    border: 1px solid #00072D !important;
    font-weight: 600;
    text-align: center;
    padding: 0.75rem 0.5rem;
    font-size: 0.875rem;
}

.scoring-table td {
    border: 1px solid #dee2e6;
    padding: 0.75rem 0.5rem;
    text-align: center;
    vertical-align: middle;
}

.total-points-cell {
    font-weight: 700;
    font-size: 1.1rem;
    text-align: center;
}

.total-20 {
    background: #fef2f2;
    color: #dc2626;
}

.total-17 {
    background: #fffbeb;
    color: #d97706;
}

.total-15 {
    background: #f0fdf4;
    color: #16a34a;
}

.total-10 {
    background: #f0fdf4;
    color: #16a34a;
}

/* Risk Assessment Results */
.result-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.result-card h6 {
    color: #374151;
    font-weight: 600;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.result-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    display: block;
}

/* Client Acceptance Decision Styling */
.acceptance-decision-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.decision-accept {
    border-color: #22c55e;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
}

.decision-reject {
    border-color: #ef4444;
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
}

.decision-title {
    color: #1e293b;
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-size: 1.25rem;
    border-bottom: 3px solid #00072D;
    padding-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.final-decision {
    min-height: 200px;
}

.decision-text {
    color: #6b7280;
    font-style: italic;
    text-align: center;
    padding: 2rem;
    font-size: 1.1rem;
}

.decision-details {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.decision-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.summary-label {
    font-weight: 600;
    color: #374151;
    font-size: 1rem;
}

.summary-value {
    font-weight: 700;
    font-size: 1.1rem;
    color: #1e293b;
}

.decision-recommendation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
    border: 2px solid #00072D;
    margin: 0.5rem 0;
}

.recommendation-label {
    font-weight: 700;
    color: #00072D;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.recommendation-value {
    font-weight: 700;
    font-size: 1.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.monitoring-requirement {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.monitoring-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.95rem;
}

.monitoring-value {
    font-weight: 600;
    font-size: 1rem;
    color: #059669;
}

.btn-primary {
    background: #00072D;
    border: #00072D;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}

.btn-primary:hover {
    background: #1a365d;
    border: #1a365d;
}

.btn-secondary {
    background: #6b7280;
    border: #6b7280;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}

.btn-secondary:hover {
    background: #4b5563;
    border: #4b5563;
}

.btn-warning {
    background: #f59e0b;
    border: #f59e0b;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    color: #ffffff;
}

.btn-warning:hover {
    background: #d97706;
    border: #d97706;
    color: #ffffff;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .risk-matrix-table th,
    .risk-matrix-table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.75rem;
    }
    
    .mitigation-text {
        font-size: 0.7rem;
        max-width: 150px;
    }
}
</style>

<script>
// CSRF Token handling
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token from meta tag
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Set up AJAX defaults
    if (typeof axios !== 'undefined') {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    }
    
    // Handle form submission
    const form = document.getElementById('riskForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Refresh CSRF token before submission
            fetch('/risks', {
                method: 'HEAD',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => {
                // Get fresh CSRF token
                const csrfInput = form.querySelector('input[name="_token"]');
                if (csrfInput) {
                    csrfInput.value = token;
                }
            }).catch(() => {
                // console.log('CSRF token refresh failed, proceeding with form submission');
            });
        });
    }
    
    // Refresh CSRF token every 5 minutes
    setInterval(function() {
        fetch('/csrf-token', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => response.json()).then(data => {
            // Update CSRF token in form
            const csrfInput = form.querySelector('input[name="_token"]');
            if (csrfInput) {
                csrfInput.value = data.token;
            }
        }).catch(() => {
            // console.log('CSRF token refresh failed');
        });
    }, 300000); // 5 minutes

    // Client Lookup Functionality
    const clientSearch = document.getElementById('clientSearch');
    const searchClientBtn = document.getElementById('searchClientBtn');
    const proceedWithNewClientBtn = document.getElementById('proceedWithNewClient');
    const clientSearchResults = document.getElementById('clientSearchResults');
    const searchResultsList = document.getElementById('searchResultsList');
    const clientNameInput = document.getElementById('client_name');

    // Search for clients
    if (searchClientBtn) {
        searchClientBtn.addEventListener('click', function() {
            const searchTerm = clientSearch.value.trim();
            if (!searchTerm) {
                alert('Please enter a client name to search');
                return;
            }
            searchClients(searchTerm);
        });
    }

    // Allow Enter key to search
    if (clientSearch) {
        clientSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (searchClientBtn) searchClientBtn.click();
            }
        });
    }

    // Proceed with new client
    if (proceedWithNewClientBtn) {
        proceedWithNewClientBtn.addEventListener('click', function() {
            if (clientSearchResults) clientSearchResults.style.display = 'none';
            if (clientNameInput) clientNameInput.focus();
        });
    }

    function searchClients(searchTerm) {
        fetch(`/api/clients/search?q=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                if (data.clients && data.clients.length > 0) {
                    displaySearchResults(data.clients);
                } else {
                    if (searchResultsList) {
                        searchResultsList.innerHTML = '<div class="alert alert-warning">No existing clients found with that name.</div>';
                        if (clientSearchResults) clientSearchResults.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                console.error('Error searching clients:', error);
                if (searchResultsList) {
                    searchResultsList.innerHTML = '<div class="alert alert-danger">Error searching for clients. Please try again.</div>';
                    if (clientSearchResults) clientSearchResults.style.display = 'block';
                }
            });
    }

    function displaySearchResults(clients) {
        if (!searchResultsList) return;
        
        let html = '';
        
        clients.forEach(client => {
            const assessmentCount = client.assessment_count || 1;
            const lastAssessment = client.last_assessment;
            const riskRating = lastAssessment ? lastAssessment.overall_risk_rating : 'Unknown';
            const assessmentDate = lastAssessment ? new Date(lastAssessment.created_at).toLocaleDateString() : 'Unknown';
            const status = lastAssessment ? lastAssessment.assessment_status : 'Unknown';
            
            html += `
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6 class="mb-1">${client.name}</h6>
                                <small class="text-muted">ID: ${client.id}</small>
                            </div>
                            <div class="col-md-2">
                                <span class="badge bg-info">${assessmentCount} Assessment${assessmentCount > 1 ? 's' : ''}</span>
                            </div>
                            <div class="col-md-2">
                                <span class="badge bg-${getRiskRatingColor(riskRating)}">${riskRating}</span>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">${assessmentDate}</small>
                            </div>
                            <div class="col-md-2">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewClientHistory(${client.id}, '${client.name}')">
                                        <i class="fas fa-history"></i> History
                                    </button>
                                    <button class="btn btn-sm btn-success" onclick="selectClient(${client.id}, '${client.name}')">
                                        <i class="fas fa-check"></i> Select
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        searchResultsList.innerHTML = html;
        if (clientSearchResults) clientSearchResults.style.display = 'block';
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

    // Global functions for onclick handlers
    window.viewClientHistory = function(clientId, clientName) {
        // Redirect to client management page to view history
        window.open(`/clients?search=${encodeURIComponent(clientName)}`, '_blank');
    };

    window.selectClient = function(clientId, clientName) {
        if (clientNameInput) {
            clientNameInput.value = clientName;
            if (clientSearchResults) clientSearchResults.style.display = 'none';
            clientNameInput.focus();
        }
    };
});
</script>
@endsection