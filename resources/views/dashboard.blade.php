@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Welcome Section -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">
                            <i class="fas fa-chart-line me-2"></i>DCS Risk Assessment Dashboard
                        </h4>
                        <p class="mb-0">Welcome to your comprehensive risk assessment overview</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group">
                            <a href="{{ route('client-risk.create') }}" class="btn btn-light">
                                <i class="fas fa-plus me-1"></i>New Assessment
                            </a>
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-light">
                                <i class="fas fa-chart-bar me-1"></i>Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card border-left-primary h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Assessments
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['total_assessments'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-danger h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            High Risk Clients
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['risk_rating_stats']['Very High-risk'] ?? 0 + $stats['risk_rating_stats']['High-risk'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Low Risk Clients
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['risk_rating_stats']['Low-risk'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Identification
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['identification_stats']['In-progress'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-1"></i>Risk Rating Distribution
                </h6>
            </div>
            <div class="card-body">
                <canvas id="riskRatingChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie me-1"></i>Client Identification Status
                </h6>
            </div>
            <div class="card-body">
                <canvas id="identificationChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Assessments -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-1"></i>Recent Risk Assessments
                </h6>
            </div>
            <div class="card-body">
                @if($stats['recent_assessments']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Risk Rating</th>
                                    <th>Risk Points</th>
                                    <th>Client Acceptance</th>
                                    <th>Assessment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_assessments'] as $assessment)
                                <tr>
                                    <td>
                                        <strong>{{ $assessment->client_name }}</strong>
                                    </td>
                                    <td>
                                        @switch($assessment->overall_risk_rating)
                                            @case('Very High-risk')
                                                <span class="badge bg-danger">{{ $assessment->overall_risk_rating }}</span>
                                                @break
                                            @case('High-risk')
                                                <span class="badge bg-warning">{{ $assessment->overall_risk_rating }}</span>
                                                @break
                                            @case('Medium-risk')
                                                <span class="badge bg-info">{{ $assessment->overall_risk_rating }}</span>
                                                @break
                                            @case('Low-risk')
                                                <span class="badge bg-success">{{ $assessment->overall_risk_rating }}</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $assessment->overall_risk_rating ?? 'N/A' }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <strong>{{ $assessment->overall_risk_points }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $assessment->client_acceptance == 'Do not accept client' ? 'danger' : 'success' }}">
                                            {{ $assessment->client_acceptance }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $assessment->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('risk-assessments.show', $assessment) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No assessments yet</h5>
                        <p class="text-muted">Start by creating your first risk assessment.</p>
                        <a href="{{ route('risk-assessments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create Assessment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Risk Rating Distribution Chart
const riskRatingCtx = document.getElementById('riskRatingChart').getContext('2d');
const riskRatingChart = new Chart(riskRatingCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($riskDistribution->pluck('overall_risk_rating')) !!},
        datasets: [{
            label: 'Number of Clients',
            data: {!! json_encode($riskDistribution->pluck('count')) !!},
            backgroundColor: [
                '#dc3545', // Very High-risk
                '#ffc107', // High-risk
                '#17a2b8', // Medium-risk
                '#28a745'  // Low-risk
            ],
            borderColor: [
                '#dc3545',
                '#ffc107',
                '#17a2b8',
                '#28a745'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Client Identification Status Chart
const identificationCtx = document.getElementById('identificationChart').getContext('2d');
const identificationChart = new Chart(identificationCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($stats['identification_stats'])) !!},
        datasets: [{
            data: {!! json_encode(array_values($stats['identification_stats'])) !!},
            backgroundColor: [
                '#28a745', // Yes
                '#dc3545', // No
                '#ffc107'  // In-progress
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-danger {
    border-left: 4px solid #e74a3b !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
.text-xs {
    font-size: 0.7rem;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
</style>
@endsection 