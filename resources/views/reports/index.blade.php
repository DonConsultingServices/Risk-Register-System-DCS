@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Reports & Analytics
                </h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('reports.pdf', ['period' => $period]) }}" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i>Export as PDF
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.csv', ['period' => $period]) }}">
                            <i class="fas fa-file-csv me-1"></i>Export as CSV
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.excel', ['period' => $period]) }}">
                            <i class="fas fa-file-excel me-1"></i>Export as Excel
                        </a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <!-- Report Period Selector -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="reportPeriod" class="form-label">Report Period</label>
                        <select class="form-select" id="reportPeriod" onchange="changeReportPeriod()">
                            <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
                            <option value="180" {{ $period == 180 ? 'selected' : '' }}>Last 6 months</option>
                            <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last year</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button class="btn btn-primary" onclick="printReport()">
                            <i class="fas fa-print me-1"></i>Print Report
                        </button>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['total_assessments'] }}</h4>
                                <small>Total Assessments</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['high_risk_count'] }}</h4>
                                <small>High Risk Clients</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['average_risk_points'] }}</h4>
                                <small>Average Risk Points</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ count($assessments) }}</h4>
                                <small>Period Assessments</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-8">
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
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-pie me-1"></i>Client Acceptance Status
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="acceptanceChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Reports Table -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-table me-1"></i>Detailed Assessment Report
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($assessments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover" id="reportsTable">
                                    <thead>
                                        <tr>
                                            <th>Client Name</th>
                                            <th>Risk Rating</th>
                                            <th>Total Points</th>
                                            <th>Client Acceptance</th>
                                            <th>Monitoring Frequency</th>
                                            <th>Assessment Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assessments as $assessment)
                                        <tr>
                                            <td>
                                                <strong>{{ $assessment->client_name }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $colorClass = $assessment->getRiskColorClass();
                                                @endphp
                                                <span class="badge bg-{{ $colorClass }}">{{ $assessment->overall_risk_rating }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $assessment->total_points }}</span>
                                            </td>
                                            <td>
                                                @if($assessment->client_acceptance)
                                                    @if($assessment->isClientAcceptable())
                                                        <span class="badge bg-success">{{ $assessment->client_acceptance }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ $assessment->client_acceptance }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($assessment->monitoring_frequency)
                                                    <span class="text-primary">{{ $assessment->monitoring_frequency }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $assessment->getFormattedAssessmentDate() }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('client-risk.show', $assessment) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No assessments found for this period</h5>
                                <p class="text-muted">Try selecting a different time period or create new assessments.</p>
                            </div>
                        @endif
                    </div>
                </div>
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
        labels: {!! json_encode(array_keys($stats['risk_rating_stats'])) !!},
        datasets: [{
            label: 'Number of Clients',
            data: {!! json_encode(array_values($stats['risk_rating_stats'])) !!},
            backgroundColor: [
                '#dc3545', // High
                '#ffc107', // Medium
                '#28a745'  // Low
            ],
            borderColor: [
                '#dc3545',
                '#ffc107',
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

// Client Acceptance Status Chart
const acceptanceCtx = document.getElementById('acceptanceChart').getContext('2d');
const acceptanceChart = new Chart(acceptanceCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($stats['acceptance_stats'])) !!},
        datasets: [{
            data: {!! json_encode(array_values($stats['acceptance_stats'])) !!},
            backgroundColor: [
                '#28a745', // Accept client
                '#ffc107', // Accept with conditions
                '#dc3545'  // Reject client
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

// Change report period
function changeReportPeriod() {
    const period = document.getElementById('reportPeriod').value;
    window.location.href = '{{ route("reports.index") }}?period=' + period;
}

// Print report
function printReport() {
    window.open('{{ route("reports.pdf", ["period" => $period]) }}', '_blank');
}

// Filter table
function filterReportsTable() {
    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSelect');
    const table = document.getElementById('reportsTable');
    const rows = table.getElementsByTagName('tr');

    const searchTerm = searchInput.value.toLowerCase();
    const filterValue = filterSelect.value;

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let showRow = true;

        // Search filter
        if (searchTerm) {
            let rowText = '';
            for (let j = 0; j < cells.length; j++) {
                rowText += cells[j].textContent + ' ';
            }
            if (!rowText.toLowerCase().includes(searchTerm)) {
                showRow = false;
            }
        }

        // Risk rating filter
        if (filterValue && filterValue !== 'all') {
            const riskRating = cells[1].textContent.trim();
            if (riskRating !== filterValue) {
                showRow = false;
            }
        }

        row.style.display = showRow ? '' : 'none';
    }
}

// Add search and filter inputs if they don't exist
document.addEventListener('DOMContentLoaded', function() {
    const tableHeader = document.querySelector('#reportsTable thead tr');
    if (tableHeader && !document.getElementById('searchInput')) {
        const searchCell = document.createElement('th');
        searchCell.innerHTML = `
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
        `;
        tableHeader.appendChild(searchCell);

        const filterCell = document.createElement('th');
        filterCell.innerHTML = `
            <select id="filterSelect" class="form-select form-select-sm" onchange="filterReportsTable()">
                <option value="all">All Ratings</option>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
            </select>
        `;
        tableHeader.appendChild(filterCell);

        // Add event listeners
        document.getElementById('searchInput').addEventListener('input', filterReportsTable);
    }
});
</script>
@endsection 