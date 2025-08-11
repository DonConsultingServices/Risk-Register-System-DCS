@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>Risk Assessment Dashboard
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-chart-bar me-1"></i>Risk Rating System Overview
                        </h6>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Points Total</th>
                                        <th>Overall Risk Rating</th>
                                        <th>Client Acceptance?</th>
                                        <th>Ongoing Monitoring</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Risk::RISK_RATING_GUIDE as $points => $guide)
                                    <tr>
                                        <td class="bg-{{ $guide['color'] }} text-white text-center">
                                            <strong>{{ $points }}</strong>
                                        </td>
                                        <td>{{ $guide['rating'] }}</td>
                                        <td>
                                            @if($guide['acceptance'] === 'Accept client')
                                                <span class="badge bg-success">{{ $guide['acceptance'] }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $guide['acceptance'] }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $guide['monitoring'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-list me-1"></i>Available Risk IDs
                            </h6>
                            
                            <div class="row">
                                @php
                                    $riskCategories = [
                                        'Client Risk' => array_filter(\App\Models\Risk::getAvailableRiskIds(), fn($id) => str_starts_with($id, 'CR')),
                                        'Service Risk' => array_filter(\App\Models\Risk::getAvailableRiskIds(), fn($id) => str_starts_with($id, 'SR')),
                                        'Payment Risk' => array_filter(\App\Models\Risk::getAvailableRiskIds(), fn($id) => str_starts_with($id, 'PR')),
                                        'Delivery Risk' => array_filter(\App\Models\Risk::getAvailableRiskIds(), fn($id) => str_starts_with($id, 'DR'))
                                    ];
                                @endphp

                                @foreach($riskCategories as $category => $risks)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">{{ $category }}</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($risks as $riskId)
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="badge bg-secondary">{{ $riskId }}</span>
                                                <span class="badge bg-primary">{{ \App\Models\Risk::getRiskPoints($riskId) }} pts</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-cogs me-1"></i>Quick Actions
                        </h6>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('client-risk.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>New Client Assessment
                            </a>
                            <a href="{{ route('client-risk.index') }}" class="btn btn-info">
                                <i class="fas fa-list me-1"></i>View All Assessments
                            </a>
                            <a href="{{ route('risks.index') }}" class="btn btn-secondary">
                                <i class="fas fa-shield-alt me-1"></i>Risk Register
                            </a>
                            <a href="{{ route('client-risk.export') }}" class="btn btn-success">
                                <i class="fas fa-download me-1"></i>Export Assessments
                            </a>
                        </div>

                        <div class="mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-1"></i>How It Works
                            </h6>
                            
                            <div class="alert alert-info">
                                <ol class="mb-0">
                                    <li>Select risk IDs that apply to the client</li>
                                    <li>System automatically calculates total points</li>
                                    <li>Risk rating and acceptance are determined</li>
                                    <li>Monitoring frequency is assigned</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 