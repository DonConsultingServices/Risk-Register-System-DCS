@extends('layouts.sidebar')

@section('title', 'Assessment Results - Client Acceptance & Retention Risk Register')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Assessment Results</h1>
                    <p class="text-muted">Risk assessment completed for {{ $client->name }}</p>
                </div>
                <div>
                    <a href="{{ route('client-risk-assessment.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Assessment
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Assessment Results -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>
                                Assessment Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-{{ $riskRating === 'Low' ? 'success' : ($riskRating === 'Medium' ? 'warning' : 'danger') }}">
                                            {{ $riskScore }}
                                        </h3>
                                        <p class="text-muted mb-0">Risk Score</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <span class="badge bg-{{ $riskRating === 'Low' ? 'success' : ($riskRating === 'Medium' ? 'warning' : 'danger') }} fs-6">
                                            {{ $riskRating }}
                                        </span>
                                        <p class="text-muted mb-0 mt-2">Risk Rating</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <span class="badge bg-{{ $clientDecision === 'Accept client' ? 'success' : 'danger' }} fs-6">
                                            {{ $clientDecision }}
                                        </span>
                                        <p class="text-muted mb-0 mt-2">Decision</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <span class="badge bg-info fs-6">
                                            {{ $monitoringFrequency }}
                                        </span>
                                        <p class="text-muted mb-0 mt-2">Monitoring</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Previous Assessment Comparison -->
                    <div class="card mt-4" id="comparisonCard" style="display: none;">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-balance-scale me-2"></i>
                                Risk Score Comparison
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="comparisonContent">
                                <!-- Comparison content will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- Selected Risks -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>
                                Selected Risk Factors
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($selectedRisks->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Risk Factor</th>
                                                <th>Category</th>
                                                <th>Risk Level</th>
                                                <th>Points</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($selectedRisks as $risk)
                                                <tr>
                                                    <td><strong>{{ $risk->title }}</strong></td>
                                                    <td>{{ $risk->category->name }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $risk->risk_level === 'Low' ? 'success' : ($risk->risk_level === 'Medium' ? 'warning' : 'danger') }}">
                                                            {{ $risk->risk_level }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $risk->points }}</td>
                                                    <td>{{ $risk->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No risk factors were selected for this assessment.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- AML Compliance Info -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-shield-alt me-2"></i>
                                AML Compliance Status
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>FIC Compliance</h6>
                                <p class="mb-0">This assessment meets FIC (Financial Intelligence Centre) requirements for AML compliance in Namibia.</p>
                            </div>
                            
                            <div class="mt-3">
                                <h6>Compliance Checklist:</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Client identification completed
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Risk assessment performed
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Due diligence documented
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Monitoring requirements set
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-3">
                                <h6>Next Steps:</h6>
                                <ol class="small">
                                    <li>Review assessment results</li>
                                    <li>Make client acceptance decision</li>
                                    <li>Set up monitoring schedule</li>
                                    <li>Document in client file</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>
                                Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-1"></i>View Client Details
                                </a>
                                <a href="{{ route('client-risk-assessment.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-plus me-1"></i>New Assessment
                                </a>
                                <button class="btn btn-outline-info" onclick="loadPreviousAssessments()">
                                    <i class="fas fa-history me-1"></i>Compare with Previous
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadPreviousAssessments() {
    const clientName = '{{ $client->name }}';
    
    fetch(`/api/clients/search?q=${encodeURIComponent(clientName)}`)
        .then(response => response.json())
        .then(data => {
            if (data.clients && data.clients.length > 0) {
                const client = data.clients[0];
                if (client.assessment_count > 1) {
                    fetch(`/api/clients/${client.id}/history`)
                        .then(response => response.json())
                        .then(historyData => {
                            displayComparison(historyData, {{ $riskScore }}, '{{ $riskRating }}');
                        })
                        .catch(error => {
                            console.error('Error loading history:', error);
                            alert('Error loading previous assessments');
                        });
                } else {
                    alert('No previous assessments found for comparison');
                }
            } else {
                alert('No previous assessments found for comparison');
            }
        })
        .catch(error => {
            console.error('Error searching for client:', error);
            alert('Error searching for previous assessments');
        });
}

function displayComparison(historyData, currentScore, currentRating) {
    const comparisonCard = document.getElementById('comparisonCard');
    const comparisonContent = document.getElementById('comparisonContent');
    
    if (historyData.assessments && historyData.assessments.length > 1) {
        const previousAssessment = historyData.assessments[1]; // Second most recent (current is first)
        const previousScore = previousAssessment.overall_risk_points || 0;
        const previousRating = previousAssessment.overall_risk_rating || 'Unknown';
        
        const scoreChange = currentScore - previousScore;
        const scoreChangeText = scoreChange > 0 ? `+${scoreChange}` : scoreChange.toString();
        const scoreChangeColor = scoreChange > 0 ? 'danger' : (scoreChange < 0 ? 'success' : 'info');
        
        let ratingChangeText = '';
        let ratingChangeColor = 'info';
        
        if (previousRating !== 'Unknown') {
            const ratingOrder = ['Low', 'Medium', 'High', 'Critical'];
            const previousIndex = ratingOrder.indexOf(previousRating);
            const currentIndex = ratingOrder.indexOf(currentRating);
            
            if (currentIndex > previousIndex) {
                ratingChangeText = 'Increased Risk';
                ratingChangeColor = 'danger';
            } else if (currentIndex < previousIndex) {
                ratingChangeText = 'Decreased Risk';
                ratingChangeColor = 'success';
            } else {
                ratingChangeText = 'No Change';
                ratingChangeColor = 'info';
            }
        }
        
        comparisonContent.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Previous Assessment</h6>
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h4 class="text-${getRiskRatingColor(previousRating)}">${previousScore}</h4>
                            <p class="mb-0">${previousRating}</p>
                            <small class="text-muted">${new Date(previousAssessment.created_at).toLocaleDateString()}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6>Current Assessment</h6>
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h4 class="text-${getRiskRatingColor(currentRating)}">${currentScore}</h4>
                            <p class="mb-0">${currentRating}</p>
                            <small class="text-muted">Today</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-12">
                    <div class="alert alert-${scoreChangeColor}">
                        <h6><i class="fas fa-chart-line me-2"></i>Risk Score Change: ${scoreChangeText} points</h6>
                        <p class="mb-0">${ratingChangeText} - ${scoreChange > 0 ? 'Client risk has increased' : scoreChange < 0 ? 'Client risk has decreased' : 'No significant change in risk level'}</p>
                    </div>
                </div>
            </div>
        `;
        
        comparisonCard.style.display = 'block';
    } else {
        alert('No previous assessments found for comparison');
    }
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
</script>
@endsection
