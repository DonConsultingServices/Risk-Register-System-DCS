@extends('layouts.sidebar')

@section('title', 'Risk Register - Client Acceptance & Retention Risk Register')
@section('page-title', 'Risk Register')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--logo-dark-blue-primary) 0%, var(--logo-dark-blue-secondary) 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }
    
    .page-subtitle {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }
    
    .header-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-new-risk {
        background: var(--logo-dark-blue-primary);
        border-color: var(--logo-dark-blue-primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        white-space: nowrap;
    }
    
    .btn-new-risk:hover {
        background: var(--logo-dark-blue-hover);
        border-color: var(--logo-dark-blue-hover);
        color: white;
        transform: translateY(-1px);
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .page-subtitle {
            font-size: 0.9rem;
        }
        
        .header-actions {
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-new-risk {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1rem;
        }
        
        .welcome-card {
            padding: 2rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .welcome-icon {
            font-size: 3rem;
        }
        
        .welcome-title {
            font-size: 1.5rem;
        }
        
        .welcome-description {
            font-size: 1rem;
        }
        
        .feature-cards {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .feature-card {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-header {
            padding: 1rem;
        }
        
        .page-title {
            font-size: 1.25rem;
        }
        
        .welcome-card {
            padding: 1.5rem 1rem;
        }
        
        .welcome-icon {
            font-size: 2.5rem;
        }
        
        .welcome-title {
            font-size: 1.25rem;
        }
        
        .welcome-description {
            font-size: 0.9rem;
        }
        
        .feature-card {
            padding: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .page-header {
            padding: 0.75rem;
        }
        
        .page-title {
            font-size: 1.1rem;
        }
        
        .welcome-card {
            padding: 1rem 0.75rem;
        }
        
        .welcome-icon {
            font-size: 2rem;
        }
        
        .welcome-title {
            font-size: 1.1rem;
        }
        
        .welcome-description {
            font-size: 0.85rem;
        }
        
        .feature-card {
            padding: 0.75rem;
        }
    }
    
    .welcome-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 3rem;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .welcome-icon {
        font-size: 4rem;
        color: #00072D;
        margin-bottom: 1.5rem;
    }
    
    .welcome-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
    }
    
    .welcome-description {
        color: #6b7280;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .feature-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .feature-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        text-align: center;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .feature-icon {
        font-size: 2.5rem;
        color: #00072D;
        margin-bottom: 1rem;
    }
    
    .feature-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }
    
    .feature-description {
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }
    
    .feature-btn {
        background: #00072D;
        border-color: #00072D;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        width: 100%;
        justify-content: center;
    }
    
    .feature-btn:hover {
        background: #1a365d;
        border-color: #1a365d;
        color: white;
        transform: translateY(-1px);
    }
    
    .info-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
    }
    
    .info-section h6 {
        color: #00072D;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }
    
    .info-section p {
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 0.75rem;
    }
    
    .info-section ul {
        color: #6b7280;
        line-height: 1.6;
        padding-left: 1.5rem;
    }
    
    .info-section li {
        margin-bottom: 0.5rem;
    }
    
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
            font-size: 1.5rem;
        }
        
        .page-subtitle {
            font-size: 0.9rem;
        }
        
        .header-actions {
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .btn-new-risk {
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .welcome-card {
            margin: 0 -0.5rem 1.5rem -0.5rem;
            border-radius: 0;
            padding: 2rem 1rem;
        }
        
        .welcome-icon {
            font-size: 3rem;
        }
        
        .welcome-title {
            font-size: 1.5rem;
        }
        
        .welcome-description {
            font-size: 1rem;
        }
        
        .feature-cards {
            grid-template-columns: 1fr;
            gap: 1rem;
            margin: 0 -0.5rem 1.5rem -0.5rem;
        }
        
        .feature-card {
            padding: 1.5rem;
            margin: 0 0.5rem;
        }
        
        .feature-icon {
            font-size: 2rem;
        }
        
        .feature-title {
            font-size: 1.1rem;
        }
        
        .feature-description {
            font-size: 0.9rem;
        }
        
        .feature-btn {
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
        }
        
        .info-section {
            margin: 0 -0.5rem 1.5rem -0.5rem;
            border-radius: 0;
            padding: 1.5rem 1rem;
        }
        
        .info-section h6 {
            font-size: 1rem;
        }
        
        .info-section p {
            font-size: 0.9rem;
        }
        
        .info-section ul {
            padding-left: 1.25rem;
        }
        
        .info-section li {
            font-size: 0.9rem;
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
            font-size: 1.25rem;
        }
        
        .page-subtitle {
            font-size: 0.85rem;
        }
        
        .welcome-card {
            margin: 0 -0.25rem 1rem -0.25rem;
            padding: 1.5rem 0.75rem;
        }
        
        .welcome-icon {
            font-size: 2.5rem;
        }
        
        .welcome-title {
            font-size: 1.25rem;
        }
        
        .welcome-description {
            font-size: 0.9rem;
        }
        
        .feature-cards {
            margin: 0 -0.25rem 1rem -0.25rem;
        }
        
        .feature-card {
            padding: 1.25rem;
            margin: 0 0.25rem;
        }
        
        .feature-icon {
            font-size: 1.75rem;
        }
        
        .feature-title {
            font-size: 1rem;
        }
        
        .feature-description {
            font-size: 0.85rem;
        }
        
        .feature-btn {
            padding: 0.625rem 1rem;
            font-size: 0.85rem;
        }
        
        .info-section {
            margin: 0 -0.25rem 1rem -0.25rem;
            padding: 1.25rem 0.75rem;
        }
        
        .info-section h6 {
            font-size: 0.95rem;
        }
        
        .info-section p {
            font-size: 0.85rem;
        }
        
        .info-section li {
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 480px) {
        .page-header {
            padding: 0.5rem;
        }
        
        .page-title {
            font-size: 1.1rem;
        }
        
        .page-subtitle {
            font-size: 0.8rem;
        }
        
        .welcome-card {
            padding: 1rem 0.5rem;
        }
        
        .welcome-icon {
            font-size: 2rem;
        }
        
        .welcome-title {
            font-size: 1.1rem;
        }
        
        .welcome-description {
            font-size: 0.85rem;
        }
        
        .feature-card {
            padding: 1rem;
        }
        
        .feature-icon {
            font-size: 1.5rem;
        }
        
        .feature-title {
            font-size: 0.95rem;
        }
        
        .feature-description {
            font-size: 0.8rem;
        }
        
        .feature-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .info-section {
            padding: 1rem 0.5rem;
        }
        
        .info-section h6 {
            font-size: 0.9rem;
        }
        
        .info-section p {
            font-size: 0.8rem;
        }
        
        .info-section li {
            font-size: 0.8rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Risk Register</h1>
                <p class="page-subtitle">Create comprehensive risk assessments and manage client onboarding</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('risks.create') }}" class="btn-new-risk">
                    <i class="fas fa-plus"></i>
                    <span>Add New Risk Assessment</span>
                </a>
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

    <!-- Welcome Section -->
    <div class="welcome-card">
        <div class="welcome-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h2 class="welcome-title">Welcome to Risk Register</h2>
        <p class="welcome-description">
            This is your central hub for creating comprehensive risk assessments and onboarding new clients. 
            All risk data is automatically managed and displayed in the Client Management dashboard for complete oversight.
        </p>
    </div>

    <!-- Feature Cards -->
    <div class="feature-cards">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <h3 class="feature-title">Create Risk Assessment</h3>
            <p class="feature-description">
                Add comprehensive risk assessments across all categories: Client Risk (CR), Service Risk (SR), 
                Payment Risk (PR), and Delivery Risk (DR).
            </p>
            <a href="{{ route('risks.create') }}" class="feature-btn">
                <i class="fas fa-plus"></i>
                Start New Assessment
            </a>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="feature-title">Client Management</h3>
            <p class="feature-description">
                View and manage all client risk assessments, screening results, and compliance data in the 
                comprehensive Client Management dashboard.
            </p>
            <a href="{{ route('clients.index') }}" class="feature-btn">
                <i class="fas fa-external-link-alt"></i>
                View Client Dashboard
            </a>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="feature-title">Risk Analytics</h3>
            <p class="feature-description">
                Access detailed risk analytics, compliance reports, and regulatory insights through the 
                comprehensive risk assessment system.
            </p>
            <a href="{{ route('dashboard') }}" class="feature-btn">
                <i class="fas fa-chart-bar"></i>
                View Analytics
            </a>
        </div>
    </div>

    <!-- Risk Statistics and Recent Risks -->
    @if(isset($risks) && $risks->count() > 0)
    <div class="row mb-4">
        <!-- Risk Statistics -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Risk Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-primary mb-1">{{ $totalRisks }}</h3>
                                <p class="text-muted mb-0">Total Risks</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-warning mb-1">{{ $openRisks }}</h3>
                                <p class="text-muted mb-0">Open Risks</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-danger mb-1">{{ $highRisks }}</h3>
                                <p class="text-muted mb-0">High Risks</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Risks -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Risk Assessments</h5>
                    <a href="{{ route('risks.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add New Risk
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Risk Title</th>
                                    <th>Client</th>
                                    <th>Category</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($risks as $risk)
                                <tr>
                                    <td>
                                        <strong>{{ $risk->title }}</strong>
                                        @if($risk->description)
                                            <br><small class="text-muted">{{ Str::limit($risk->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($risk->client)
                                            <a href="{{ route('clients.show', $risk->client) }}" class="text-decoration-none">
                                                {{ $risk->client->name }}
                                            </a>
                                        @elseif($risk->client_name)
                                            <span class="text-primary">{{ $risk->client_name }}</span>
                                        @else
                                            <span class="text-muted">No Client</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($risk->category)
                                            <span class="badge bg-info">{{ $risk->category->name }}</span>
                                        @elseif($risk->risk_category)
                                            <span class="badge bg-info">{{ $risk->risk_category }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $risk->risk_rating == 'High' ? 'danger' : ($risk->risk_rating == 'Medium' ? 'warning' : 'success') }}">
                                            {{ $risk->risk_rating }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $risk->status == 'Open' ? 'primary' : ($risk->status == 'Closed' ? 'success' : 'secondary') }}">
                                            {{ $risk->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $risk->created_at->format('M d, Y') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No Risks Message -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-center">
                <div class="card-body py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Risk Assessments Yet</h5>
                    <p class="text-muted mb-4">Start by creating your first risk assessment to begin managing client risks.</p>
                    <a href="{{ route('risks.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Risk Assessment
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Information Section -->
    <div class="info-section">
        <h6><i class="fas fa-info-circle me-2"></i>How Risk Register Works</h6>
        <p><strong>Risk Register</strong> is your entry point for creating comprehensive risk assessments. Here's how it works:</p>
        
        <ul>
            <li><strong>Create Assessment:</strong> Add new risk assessments with detailed client information, screening data, and risk evaluations</li>
            <li><strong>Automatic Client Creation:</strong> New clients are automatically created when you add risk assessments</li>
            <li><strong>Comprehensive Data:</strong> All risk data is captured across the 4 risk categories (CR, SR, PR, DR)</li>
            <li><strong>Regulatory Compliance:</strong> Built-in compliance features for Namibia FIC and regulatory requirements</li>
            <li><strong>Centralized Management:</strong> All data is automatically available in Client Management for oversight</li>
        </ul>
        
        <p class="mt-3 mb-0">
            <strong>Note:</strong> To view and manage all client data, use the 
            <a href="{{ route('clients.index') }}" class="text-decoration-none fw-bold">Client Management</a> 
            section which provides the complete 33-column comprehensive view.
        </p>
    </div>
</div>
@endsection
