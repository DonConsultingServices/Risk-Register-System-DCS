@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Client Risk Assessment Details
                </h5>
                <div>
                    <a href="{{ route('client-risk.edit', $clientRisk) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('client-risk.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Client Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user me-1"></i>Client Information
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Client Name:</strong> {{ $clientRisk->client_name }}</p>
                        <p><strong>Identification Status:</strong> 
                            @if($clientRisk->client_identification_status)
                                <span class="badge bg-info">{{ $clientRisk->client_identification_status }}</span>
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Assessment Date:</strong> {{ $clientRisk->getFormattedAssessmentDate() }}</p>
                        <p><strong>Created:</strong> {{ $clientRisk->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <hr>

                <!-- Risk Assessment Results -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-calculator me-1"></i>Risk Assessment Results
                        </h6>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ $clientRisk->total_points ?? 0 }}</h4>
                                <small>Total Risk Points</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-{{ $clientRisk->getRiskColorClass() }} text-white">
                            <div class="card-body text-center">
                                <h4>{{ $clientRisk->overall_risk_rating ?? 'N/A' }}</h4>
                                <small>Overall Risk Rating</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-{{ $clientRisk->isClientAcceptable() ? 'success' : 'danger' }} text-white">
                            <div class="card-body text-center">
                                <h4>{{ $clientRisk->client_acceptance ?? 'N/A' }}</h4>
                                <small>Client Acceptance</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>{{ $clientRisk->monitoring_frequency ?? 'N/A' }}</h4>
                                <small>Monitoring Frequency</small>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Risk Details -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-exclamation-triangle me-1"></i>Risk Details
                        </h6>
                    </div>
                    
                    <!-- Client Screening -->
                    @if($clientRisk->client_screening_risk_id)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Client Screening</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><strong>Risk ID:</strong> {{ $clientRisk->client_screening_risk_id }}</p>
                                        <p><strong>Date:</strong> {{ $clientRisk->client_screening_date ? $clientRisk->client_screening_date->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Result:</strong> {{ $clientRisk->client_screening_result ?? 'N/A' }}</p>
                                        <p><strong>Impact:</strong> {{ $clientRisk->client_screening_impact ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Likelihood:</strong> {{ $clientRisk->client_screening_likelihood ?? 'N/A' }}</p>
                                        <p><strong>Risk Rating:</strong> {{ $clientRisk->client_screening_risk_rating ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Client Category -->
                    @if($clientRisk->client_category_risk_id)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Client Category</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><strong>Risk ID:</strong> {{ $clientRisk->client_category_risk_id }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Impact:</strong> {{ $clientRisk->client_category_impact ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Likelihood:</strong> {{ $clientRisk->client_category_likelihood ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Risk Rating:</strong> {{ $clientRisk->client_category_risk_rating ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Requested Services -->
                    @if($clientRisk->requested_services_risk_id)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Requested Services</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><strong>Risk ID:</strong> {{ $clientRisk->requested_services_risk_id }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Impact:</strong> {{ $clientRisk->requested_services_impact ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Likelihood:</strong> {{ $clientRisk->requested_services_likelihood ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Risk Rating:</strong> {{ $clientRisk->requested_services_risk_rating ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Payment Option -->
                    @if($clientRisk->payment_option_risk_id)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Payment Option</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><strong>Risk ID:</strong> {{ $clientRisk->payment_option_risk_id }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Impact:</strong> {{ $clientRisk->payment_option_impact ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Likelihood:</strong> {{ $clientRisk->payment_option_likelihood ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Risk Rating:</strong> {{ $clientRisk->payment_option_risk_rating ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Delivery Method -->
                    @if($clientRisk->delivery_method_risk_id)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Delivery Method</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><strong>Risk ID:</strong> {{ $clientRisk->delivery_method_risk_id }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Impact:</strong> {{ $clientRisk->delivery_method_impact ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Likelihood:</strong> {{ $clientRisk->delivery_method_likelihood ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Risk Rating:</strong> {{ $clientRisk->delivery_method_risk_rating ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <hr>

                <!-- DCS Information -->
                @if($clientRisk->dcs_risk_appetite || $clientRisk->dcs_comments)
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-building me-1"></i>DCS Information
                        </h6>
                    </div>
                    @if($clientRisk->dcs_risk_appetite)
                    <div class="col-md-6">
                        <p><strong>Risk Appetite:</strong> {{ $clientRisk->dcs_risk_appetite }}</p>
                    </div>
                    @endif
                    @if($clientRisk->dcs_comments)
                    <div class="col-md-6">
                        <p><strong>Comments:</strong> {{ $clientRisk->dcs_comments }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Selected Risk IDs -->
                @if($clientRisk->selected_risk_ids && count($clientRisk->selected_risk_ids) > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-list me-1"></i>Selected Risk IDs
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($clientRisk->selected_risk_ids as $riskId)
                                <span class="badge bg-secondary">{{ $riskId }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 