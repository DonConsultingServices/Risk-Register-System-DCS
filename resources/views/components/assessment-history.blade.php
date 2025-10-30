@props(['assessments', 'showCurrent' => true])

<div class="assessment-history">
    <h5 class="mb-3">
        <i class="fas fa-history me-2"></i>
        Assessment History
        @if($assessments->count() > 0)
            <span class="badge bg-primary ms-2">{{ $assessments->count() }} {{ $assessments->count() === 1 ? 'Assessment' : 'Assessments' }}</span>
        @endif
    </h5>

    @if($assessments->count() > 0)
        <div class="timeline">
            @foreach($assessments as $index => $assessment)
                <div class="timeline-item {{ $index === 0 && $showCurrent ? 'current' : 'historical' }}">
                    <div class="timeline-marker">
                        @if($index === 0 && $showCurrent)
                            <i class="fas fa-star text-warning"></i>
                        @else
                            <i class="fas fa-circle text-muted"></i>
                        @endif
                    </div>
                    <div class="timeline-content">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    @if($index === 0 && $showCurrent)
                                        <i class="fas fa-star text-warning me-1"></i>Current Assessment
                                    @else
                                        Previous Assessment #{{ $index }}
                                    @endif
                                </h6>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-{{ $assessment->risk_level_color ?? 'secondary' }}">
                                        {{ $assessment->overall_risk_rating ?? 'Not Assessed' }}
                                    </span>
                                    <span class="badge bg-{{ $assessment->assessment_status_color ?? 'secondary' }}">
                                        {{ ucfirst($assessment->assessment_status ?? 'Unknown') }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Risk Assessment</h6>
                                        <p class="mb-1">
                                            <strong>Score:</strong> 
                                            <span class="risk-score">{{ $assessment->overall_risk_points ?? 0 }} points</span>
                                        </p>
                                        <p class="mb-1">
                                            <strong>Rating:</strong> 
                                            <span class="badge bg-{{ $assessment->risk_level_color ?? 'secondary' }}">
                                                {{ $assessment->overall_risk_rating ?? 'Not Assessed' }}
                                            </span>
                                        </p>
                                        <p class="mb-1">
                                            <strong>Acceptance:</strong> 
                                            {{ $assessment->client_acceptance ?? 'Not Determined' }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Monitoring:</strong> 
                                            {{ $assessment->ongoing_monitoring ?? 'Not Determined' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Assessment Details</h6>
                                        <p class="mb-1">
                                            <strong>Date:</strong> 
                                            {{ $assessment->assessment_date ? $assessment->assessment_date->format('M d, Y') : 'Unknown' }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Created by:</strong> 
                                            {{ $assessment->creator->name ?? 'Unknown' }}
                                        </p>
                                        @if($assessment->approved_by)
                                            <p class="mb-1">
                                                <strong>Approved by:</strong> 
                                                {{ $assessment->approver->name ?? 'Unknown' }}
                                            </p>
                                        @endif
                                        @if($assessment->approved_at)
                                            <p class="mb-0">
                                                <strong>Approved on:</strong> 
                                                {{ $assessment->approved_at->format('M d, Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($assessment->dcs_comments)
                                    <div class="mt-3">
                                        <h6 class="text-muted mb-2">DCS Comments</h6>
                                        <p class="text-muted small">{{ $assessment->dcs_comments }}</p>
                                    </div>
                                @endif
                                
                                @if($assessment->rejection_reason)
                                    <div class="mt-3">
                                        <h6 class="text-muted mb-2">Rejection Reason</h6>
                                        <p class="text-danger small">{{ $assessment->rejection_reason }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state text-center py-4">
            <i class="fas fa-history fa-3x text-muted mb-3"></i>
            <h6 class="text-muted">No Assessment History</h6>
            <p class="text-muted small">This client has no previous assessments.</p>
        </div>
    @endif
</div>

<style>
.assessment-history .timeline {
    position: relative;
    padding-left: 30px;
}

.assessment-history .timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.assessment-history .timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.assessment-history .timeline-marker {
    position: absolute;
    left: -22px;
    top: 20px;
    width: 14px;
    height: 14px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
}

.assessment-history .timeline-item.current .timeline-marker {
    border-color: #ffc107;
    background: #fff3cd;
}

.assessment-history .timeline-content {
    margin-left: 0;
}

.assessment-history .card {
    border-left: 4px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.assessment-history .timeline-item.current .card {
    border-left-color: #ffc107;
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.2);
}

.assessment-history .risk-score {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: var(--logo-dark-blue-primary);
    background: rgba(0, 7, 45, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
}

.assessment-history .empty-state {
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}
</style>
