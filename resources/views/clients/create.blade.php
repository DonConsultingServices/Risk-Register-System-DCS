@extends('layouts.sidebar')

@section('title', 'Client Creation Redirect')

@section('content')
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
        
        .card-body {
            padding: 1rem;
        }
        
        .header-title {
            font-size: 1.1rem;
        }
        
        .fa-3x {
            font-size: 2rem !important;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }
        
        .d-grid {
            gap: 0.75rem;
        }
        
        .d-md-flex {
            flex-direction: column;
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
        
        .card-body {
            padding: 0.75rem;
        }
        
        .header-title {
            font-size: 1rem;
        }
        
        .fa-3x {
            font-size: 1.5rem !important;
        }
        
        .btn-lg {
            padding: 0.625rem 1.25rem;
            font-size: 0.85rem;
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
        
        .card-body {
            padding: 0.5rem;
        }
        
        .header-title {
            font-size: 0.9rem;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="page-title">Client Creation Redirect</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Client Creation Process</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-info-circle fa-3x text-info mb-3"></i>
                        <h5>Clients can only be added through the Risk Assessment process</h5>
                        <p class="text-muted">To ensure proper risk evaluation and compliance, all new clients must go through the comprehensive risk assessment process.</p>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('client-risk-assessment.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Start Client Risk Assessment
                        </a>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Clients
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection