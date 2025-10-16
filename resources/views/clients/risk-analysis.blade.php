@extends('layouts.sidebar')

@section('title', 'Client Risk Analysis')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a></li>
                        <li class="breadcrumb-item active">Risk Analysis</li>
                    </ol>
                </div>
                <h4 class="page-title">Risk Analysis: {{ $client->name }}</h4>
            </div>
        </div>
    </div>

    <!-- Risk Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Total Risks">Total Risks</h5>
                            <h3 class="mt-3 mb-3">{{ $client->total_risks }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="mdi mdi-alert-circle font-20 text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="High Risks">High Risks</h5>
                            <h3 class="mt-3 mb-3 text-danger">{{ $client->high_risks }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-danger rounded">
                                <i class="mdi mdi-alert font-20 text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Open Risks">Open Risks</h5>
                            <h3 class="mt-3 mb-3 text-warning">{{ $client->open_risks }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded">
                                <i class="mdi mdi-clock font-20 text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Overdue Risks">Overdue Risks</h5>
                            <h3 class="mt-3 mb-3 text-danger">{{ $overdueRisks ?? 0 }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-danger rounded">
                                <i class="mdi mdi-calendar-alert font-20 text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Risk Distribution Chart -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Risk Level Distribution</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="riskLevelChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Risk Status Chart -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Risk Status Distribution</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="riskStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Matrix -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Client Risk Matrix</h4>
                    <p class="text-muted mb-0">Visual representation of client risks by impact and likelihood</p>
                </div>
                <div class="card-body">
                    <div class="risk-matrix-container">
                        <div class="risk-matrix">
                            <div class="matrix-header">
                                <div class="matrix-cell header-cell">Impact →</div>
                                <div class="matrix-cell header-cell">Low</div>
                                <div class="matrix-cell header-cell">Medium</div>
                                <div class="matrix-cell header-cell">High</div>
                                <div class="matrix-cell header-cell">Critical</div>
                            </div>
                            
                            <div class="matrix-row">
                                <div class="matrix-cell row-header">Very High</div>
                                <div class="matrix-cell risk-medium" data-risk="Medium Risk">
                                    <div class="risk-count">{{ $matrixData['Very High']['Low'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-high" data-risk="High Risk">
                                    <div class="risk-count">{{ $matrixData['Very High']['Medium'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-high" data-risk="High Risk">
                                    <div class="risk-count">{{ $matrixData['Very High']['High'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-critical" data-risk="Critical Risk">
                                    <div class="risk-count">{{ $matrixData['Very High']['Critical'] ?? 0 }}</div>
                                </div>
                            </div>
                            
                            <div class="matrix-row">
                                <div class="matrix-cell row-header">High</div>
                                <div class="matrix-cell risk-low" data-risk="Low Risk">
                                    <div class="risk-count">{{ $matrixData['High']['Low'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-medium" data-risk="Medium Risk">
                                    <div class="risk-count">{{ $matrixData['High']['Medium'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-high" data-risk="High Risk">
                                    <div class="risk-count">{{ $matrixData['High']['High'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-high" data-risk="High Risk">
                                    <div class="risk-count">{{ $matrixData['High']['Critical'] ?? 0 }}</div>
                                </div>
                            </div>
                            
                            <div class="matrix-row">
                                <div class="matrix-cell row-header">Medium</div>
                                <div class="matrix-cell risk-low" data-risk="Low Risk">
                                    <div class="risk-count">{{ $matrixData['Medium']['Low'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-low" data-risk="Low Risk">
                                    <div class="risk-count">{{ $matrixData['Medium']['Medium'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-medium" data-risk="Medium Risk">
                                    <div class="risk-count">{{ $matrixData['Medium']['High'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-high" data-risk="High Risk">
                                    <div class="risk-count">{{ $matrixData['Medium']['Critical'] ?? 0 }}</div>
                                </div>
                            </div>
                            
                            <div class="matrix-row">
                                <div class="matrix-cell row-header">Low</div>
                                <div class="matrix-cell risk-low" data-risk="Low Risk">
                                    <div class="risk-count">{{ $matrixData['Low']['Low'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-low" data-risk="Low Risk">
                                    <div class="risk-count">{{ $matrixData['Low']['Medium'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-low" data-risk="Low Risk">
                                    <div class="risk-count">{{ $matrixData['Low']['High'] ?? 0 }}</div>
                                </div>
                                <div class="matrix-cell risk-medium" data-risk="Medium Risk">
                                    <div class="risk-count">{{ $matrixData['Low']['Critical'] ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="matrix-legend mt-3">
                            <div class="legend-item">
                                <span class="legend-color risk-critical"></span>
                                <span>Critical Risk</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color risk-high"></span>
                                <span>High Risk</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color risk-medium"></span>
                                <span>Medium Risk</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color risk-low"></span>
                                <span>Low Risk</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Risks Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title">All Client Risks</h4>
                        <a href="{{ route('risks.create', ['client_id' => $client->id]) }}" class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i>Add New Risk
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($client->risks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Risk ID</th>
                                        <th>Risk</th>
                                        <th>Category</th>
                                        <th>Risk Level</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>Assigned To</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->risks as $risk)
                                    <tr>
                                        <td>
                                            <div class="risk-id-container" 
                                                 data-risk-id="{{ $risk->risk_id ?? $risk->id }}"
                                                 data-risk-title="{{ $risk->title }}"
                                                 data-risk-description="{{ $risk->description }}"
                                                 data-risk-category="{{ $risk->category ? $risk->category->name : 'No category' }}"
                                                 data-risk-level="{{ $risk->risk_level }}"
                                                 data-risk-status="{{ $risk->status }}"
                                                 data-risk-impact="{{ $risk->impact }}"
                                                 data-risk-likelihood="{{ $risk->likelihood }}"
                                                 data-risk-owner="{{ $risk->owner }}"
                                                 data-risk-created="{{ $risk->created_at->format('M d, Y') }}"
                                                 data-risk-due="{{ $risk->due_date ? $risk->due_date->format('M d, Y') : 'No due date' }}">
                                                <span class="risk-id-badge">{{ $risk->risk_id ?? 'R-' . str_pad($risk->id, 4, '0', STR_PAD_LEFT) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="risk-description-container" 
                                                 data-risk-id="{{ $risk->risk_id ?? $risk->id }}"
                                                 data-risk-title="{{ $risk->title }}"
                                                 data-risk-description="{{ $risk->description }}"
                                                 data-risk-category="{{ $risk->category ? $risk->category->name : 'No category' }}"
                                                 data-risk-level="{{ $risk->risk_level }}"
                                                 data-risk-status="{{ $risk->status }}"
                                                 data-risk-impact="{{ $risk->impact }}"
                                                 data-risk-likelihood="{{ $risk->likelihood }}"
                                                 data-risk-owner="{{ $risk->owner }}"
                                                 data-risk-created="{{ $risk->created_at->format('M d, Y') }}"
                                                 data-risk-due="{{ $risk->due_date ? $risk->due_date->format('M d, Y') : 'No due date' }}">
                                                <h6 class="mb-0 risk-title-hover">{{ $risk->title }}</h6>
                                                <small class="text-muted risk-description-hover">{{ Str::limit($risk->description, 50) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($risk->category)
                                                <span class="badge" style="background-color: {{ $risk->category->color }}; color: white;">
                                                    {{ $risk->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">No category</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $risk->risk_rating_color }}">{{ $risk->risk_level }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $risk->status_color }}">{{ $risk->status }}</span>
                                        </td>
                                        <td>
                                            @if($risk->due_date)
                                                {{ $risk->due_date->format('M d, Y') }}
                                                @if($risk->isOverdue())
                                                    <span class="badge bg-danger ms-1">Overdue</span>
                                                @elseif($risk->getDaysUntilDue() <= 7)
                                                    <span class="badge bg-warning ms-1">Due Soon</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No due date</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($risk->assignedUser)
                                                {{ $risk->assignedUser->name }}
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('risks.show', $risk) }}" class="btn btn-outline-primary">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('risks.edit', $risk) }}" class="btn btn-outline-secondary" 
                                                   onclick="return confirmEdit('risk')">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-shield-check text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-success">No Risks Found</h5>
                            <p class="text-muted">This client has no associated risks yet.</p>
                            <a href="{{ route('client-risk-assessment.index') }}?client_id={{ $client->id }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i>Add First Risk
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Trends -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Risk Trends Over Time</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="riskTrendsChart"></canvas>
                    </div>
                    
                    <!-- Risk Trends Chart Script -->
                    <script>
                    // Load Chart.js dynamically
                    function loadChartJS() {
                        return new Promise((resolve, reject) => {
                            if (typeof Chart !== 'undefined') {
                                resolve();
                                return;
                            }
                            
                            const script = document.createElement('script');
                            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js';
                            script.onload = () => resolve();
                            script.onerror = () => reject();
                            document.head.appendChild(script);
                        });
                    }
                    
                    function createChart() {
                        try {
                            const canvas = document.getElementById('riskTrendsChart');
                            
                            if (!canvas || typeof Chart === 'undefined') {
                                return;
                            }
                            
                            const trendLabels = {!! json_encode($trendLabels ?? []) !!};
                            const trendData = {!! json_encode($trendData ?? []) !!};
                            
                            const ctx = canvas.getContext('2d');
                            
                            // Clear any existing chart
                            if (window.trendsChart) {
                                window.trendsChart.destroy();
                            }
                            
                            window.trendsChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: trendLabels,
                                    datasets: [{
                                        label: 'Total Risks',
                                        data: trendData,
                                        borderColor: '#007bff',
                                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                        tension: 0.4,
                                        fill: true,
                                        pointBackgroundColor: '#007bff',
                                        pointBorderColor: '#ffffff',
                                        pointBorderWidth: 2,
                                        pointRadius: 6,
                                        pointHoverRadius: 8
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top'
                                        },
                                        tooltip: {
                                            mode: 'index',
                                            intersect: false
                                        }
                                    },
                                    scales: {
                                        x: {
                                            display: true,
                                            title: {
                                                display: true,
                                                text: 'Month'
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            },
                                            title: {
                                                display: true,
                                                text: 'Number of Risks'
                                            }
                                        }
                                    },
                                    interaction: {
                                        mode: 'nearest',
                                        axis: 'x',
                                        intersect: false
                                    }
                                }
                            });
                            
                        } catch (error) {
                            console.error('Chart creation error:', error);
                        }
                    }
                    
                    // Load Chart.js and create chart
                    loadChartJS().then(createChart).catch(() => {
                        console.error('Failed to load Chart.js');
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/matrix.css') }}">
<style>
.risk-matrix-container {
    max-width: 800px;
    margin: 0 auto;
}

.risk-matrix {
    border: 2px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background-color: #ffffff;
}

.matrix-header {
    display: grid;
    grid-template-columns: 120px repeat(4, 1fr);
    background-color: #f8f9fa;
}

.matrix-row {
    display: grid;
    grid-template-columns: 120px repeat(4, 1fr);
    border-top: 1px solid #dee2e6;
}

.matrix-cell {
    padding: 15px;
    text-align: center;
    border-right: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    min-height: 60px;
}

.matrix-cell:last-child {
    border-right: none;
}

.header-cell {
    background-color: #e9ecef;
    font-weight: 700;
    color: #495057;
}

.row-header {
    background-color: #f8f9fa;
    font-weight: 700;
    color: #495057;
    justify-content: flex-start;
}

.risk-critical {
    background-color: #dc3545;
    color: white;
    font-weight: 700;
}

.risk-high {
    background-color: #fd7e14;
    color: white;
    font-weight: 700;
}

.risk-medium {
    background-color: #ffc107;
    color: #212529;
    font-weight: 700;
}

.risk-low {
    background-color: #28a745;
    color: white;
    font-weight: 700;
}

.risk-count {
    font-size: 1.2rem;
    font-weight: 700;
}

.matrix-legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 4px;
}

.legend-color.risk-critical { background-color: #dc3545; }
.legend-color.risk-high { background-color: #fd7e14; }
.legend-color.risk-medium { background-color: #ffc107; }
.legend-color.risk-low { background-color: #28a745; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Simple test function
function testJavaScript() {
    const debugElement = document.getElementById('chartElementStatus');
    if (debugElement) {
        debugElement.textContent = 'JavaScript is working!';
        
        // Try to create a simple chart immediately
        try {
            const canvas = document.getElementById('riskTrendsChart');
            if (canvas && typeof Chart !== 'undefined') {
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Test'],
                        datasets: [{
                            label: 'Test',
                            data: [1],
                            borderColor: '#ff0000'
                        }]
                    }
                });
                debugElement.textContent = 'Test chart created!';
            }
        } catch (e) {
            console.error('Test chart error:', e);
            debugElement.textContent = 'Test chart failed: ' + e.message;
        }
    } else {
        console.error('Debug element not found!');
    }
}

// Immediate test
setTimeout(function() {
    const debugElement = document.getElementById('chartElementStatus');
    if (debugElement) {
        debugElement.textContent = 'Script is running!';
    } else {
        console.error('Debug element not found in timeout!');
    }
}, 1000);

document.addEventListener('DOMContentLoaded', function() {
    const debugElement = document.getElementById('chartElementStatus');
    if (debugElement) {
        debugElement.textContent = 'DOM loaded - script running!';
    } else {
        console.error('Debug element not found in DOMContentLoaded!');
    }
});

// Confirmation functions for edit and delete actions
function confirmEdit(type) {
    const messages = {
        'client': 'Are you sure you want to edit this client? Any changes will be saved immediately.',
        'risk': 'Are you sure you want to edit this risk assessment? Any changes will be saved immediately.',
        'category': 'Are you sure you want to edit this risk category? Any changes will be saved immediately.'
    };
    return confirm(messages[type] || 'Are you sure you want to proceed with editing?');
}

function confirmDelete(type, count = 1) {
    const messages = {
        'client': `Are you sure you want to delete ${count > 1 ? 'these clients' : 'this client'}? This will also delete all associated risks and cannot be undone.`,
        'risk': `Are you sure you want to delete ${count > 1 ? 'these risks' : 'this risk'}? This action cannot be undone.`,
        'category': `Are you sure you want to delete ${count > 1 ? 'these categories' : 'this category'}? This will also affect all associated risks and cannot be undone.`
    };
    return confirm(messages[type] || `Are you sure you want to delete ${count > 1 ? 'these items' : 'this item'}?`);
}

document.addEventListener('DOMContentLoaded', function() {
    // Risk Level Chart
    const levelCtx = document.getElementById('riskLevelChart').getContext('2d');
    new Chart(levelCtx, {
        type: 'doughnut',
        data: {
            labels: ['Low', 'Medium', 'High', 'Critical'],
            datasets: [{
                data: [{{ $client->low_risks }}, {{ $client->medium_risks }}, {{ $client->high_risks }}, {{ $criticalRisks ?? 0 }}],
                backgroundColor: ['#28a745', '#ffc107', '#fd7e14', '#dc3545'],
                borderWidth: 0
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

    // Risk Status Chart
    const statusCtx = document.getElementById('riskStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'In Progress', 'Closed', 'On Hold'],
            datasets: [{
                data: [{{ $openRisks ?? 0 }}, {{ $inProgressRisks ?? 0 }}, {{ $closedRisks ?? 0 }}, {{ $onHoldRisks ?? 0 }}],
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#6c757d'],
                borderWidth: 0
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

    // Risk Trends Chart - Run after all other scripts
    setTimeout(function() {
        try {
            const trendsCanvas = document.getElementById('riskTrendsChart');
            
            if (!trendsCanvas) {
                document.getElementById('chartElementStatus').textContent = 'Canvas not found!';
                return;
            }
            
            if (typeof Chart === 'undefined') {
                document.getElementById('chartElementStatus').textContent = 'Chart.js not loaded!';
                return;
            }
            
            const trendLabels = {!! json_encode($trendLabels ?? []) !!};
            const trendData = {!! json_encode($trendData ?? []) !!};
            
            const trendsCtx = trendsCanvas.getContext('2d');
            
            // Create the simplest possible chart
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Total Risks',
                        data: trendData,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
            
            document.getElementById('chartElementStatus').textContent = 'Chart created!';
            
        } catch (error) {
            console.error('Chart error:', error);
            document.getElementById('chartElementStatus').textContent = 'Error: ' + error.message;
        }
    }, 5000); // Increased delay to 5 seconds

    // Matrix cell tooltips
    const matrixCells = document.querySelectorAll('.matrix-cell[data-risk]');
    matrixCells.forEach(cell => {
        cell.addEventListener('mouseenter', function() {
            const riskLevel = this.getAttribute('data-risk');
            const count = this.querySelector('.risk-count')?.textContent || 0;
            this.title = `${riskLevel}: ${count} risks`;
        });
    });
});
</script>

<!-- Separate Chart Script -->
<script>
// Test function
function testChart() {
    const debugElement = document.getElementById('chartElementStatus');
    if (debugElement) {
        debugElement.textContent = 'Testing chart...';
        
        try {
            const canvas = document.getElementById('riskTrendsChart');
            if (canvas && typeof Chart !== 'undefined') {
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Test'],
                        datasets: [{
                            label: 'Test',
                            data: [1],
                            borderColor: '#ff0000'
                        }]
                    }
                });
                debugElement.textContent = 'Test chart created!';
            } else {
                debugElement.textContent = 'Canvas or Chart.js not available';
            }
        } catch (e) {
            console.error('Test chart error:', e);
            debugElement.textContent = 'Error: ' + e.message;
        }
    }
}

// Create the actual chart
setTimeout(function() {
    try {
        const canvas = document.getElementById('riskTrendsChart');
        const debugElement = document.getElementById('chartElementStatus');
        
        if (!canvas) {
            if (debugElement) debugElement.textContent = 'Canvas not found!';
            return;
        }
        
        if (typeof Chart === 'undefined') {
            if (debugElement) debugElement.textContent = 'Chart.js not loaded!';
            return;
        }
        
        const trendLabels = {!! json_encode($trendLabels ?? []) !!};
        const trendData = {!! json_encode($trendData ?? []) !!};
        
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Total Risks',
                    data: trendData,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        if (debugElement) debugElement.textContent = 'Chart created!';
        
    } catch (error) {
        console.error('Chart creation error:', error);
        const debugElement = document.getElementById('chartElementStatus');
        if (debugElement) debugElement.textContent = 'Error: ' + error.message;
    }
}, 3000);
</script>

<!-- Risk Description Hover Preview Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create tooltip element
    const tooltip = document.createElement('div');
    tooltip.className = 'risk-preview-tooltip';
    tooltip.innerHTML = `
        <div class="risk-preview-header">
            <h6 class="risk-preview-title"></h6>
            <p class="risk-preview-id"></p>
        </div>
        <div class="risk-preview-body">
            <div class="risk-preview-item">
                <span class="risk-preview-label">Category:</span>
                <span class="risk-preview-value risk-category"></span>
            </div>
            <div class="risk-preview-item">
                <span class="risk-preview-label">Level:</span>
                <span class="risk-preview-value risk-level"></span>
            </div>
            <div class="risk-preview-item">
                <span class="risk-preview-label">Status:</span>
                <span class="risk-preview-value risk-status"></span>
            </div>
            <div class="risk-preview-item">
                <span class="risk-preview-label">Impact:</span>
                <span class="risk-preview-value risk-impact"></span>
            </div>
            <div class="risk-preview-item">
                <span class="risk-preview-label">Likelihood:</span>
                <span class="risk-preview-value risk-likelihood"></span>
            </div>
            <div class="risk-preview-item">
                <span class="risk-preview-label">Owner:</span>
                <span class="risk-preview-value risk-owner"></span>
            </div>
            <div class="risk-preview-item">
                <span class="risk-preview-label">Created:</span>
                <span class="risk-preview-value risk-created"></span>
            </div>
            <div class="risk-preview-item">
                <span class="risk-preview-label">Due Date:</span>
                <span class="risk-preview-value risk-due"></span>
            </div>
            <div class="risk-preview-description"></div>
        </div>
    `;
    document.body.appendChild(tooltip);

    // Add hover event listeners to all risk description containers
    const riskContainers = document.querySelectorAll('.risk-description-container');
    
    riskContainers.forEach(container => {
        let hoverTimeout;
        
        container.addEventListener('mouseenter', function() {
            // Clear any existing timeout
            clearTimeout(hoverTimeout);
            
            // Get data attributes
            const riskId = this.dataset.riskId;
            const riskTitle = this.dataset.riskTitle;
            const riskDescription = this.dataset.riskDescription;
            const riskCategory = this.dataset.riskCategory;
            const riskLevel = this.dataset.riskLevel;
            const riskStatus = this.dataset.riskStatus;
            const riskImpact = this.dataset.riskImpact;
            const riskLikelihood = this.dataset.riskLikelihood;
            const riskOwner = this.dataset.riskOwner;
            const riskCreated = this.dataset.riskCreated;
            const riskDue = this.dataset.riskDue;
            
            // Update tooltip content
            tooltip.querySelector('.risk-preview-title').textContent = riskTitle;
            tooltip.querySelector('.risk-preview-id').textContent = `Risk ID: ${riskId}`;
            tooltip.querySelector('.risk-category').textContent = riskCategory;
            tooltip.querySelector('.risk-level').textContent = riskLevel;
            tooltip.querySelector('.risk-status').textContent = riskStatus;
            tooltip.querySelector('.risk-impact').textContent = riskImpact;
            tooltip.querySelector('.risk-likelihood').textContent = riskLikelihood;
            tooltip.querySelector('.risk-owner').textContent = riskOwner || 'Unassigned';
            tooltip.querySelector('.risk-created').textContent = riskCreated;
            tooltip.querySelector('.risk-due').textContent = riskDue;
            tooltip.querySelector('.risk-preview-description').textContent = riskDescription;
            
            // Position tooltip
            const rect = this.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();
            
            // Calculate position
            let left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
            let top = rect.top - tooltipRect.height - 10;
            
            // Adjust if tooltip goes off screen
            if (left < 10) left = 10;
            if (left + tooltipRect.width > window.innerWidth - 10) {
                left = window.innerWidth - tooltipRect.width - 10;
            }
            if (top < 10) {
                top = rect.bottom + 10;
                tooltip.style.transform = 'translateX(-50%) translateY(8px)';
                tooltip.querySelector('::after').style.borderTopColor = 'transparent';
                tooltip.querySelector('::after').style.borderBottomColor = 'white';
            } else {
                tooltip.style.transform = 'translateX(-50%) translateY(-8px)';
            }
            
            tooltip.style.left = left + 'px';
            tooltip.style.top = top + 'px';
            
            // Show tooltip with delay
            hoverTimeout = setTimeout(() => {
                tooltip.classList.add('show');
            }, 300);
        });
        
        container.addEventListener('mouseleave', function() {
            clearTimeout(hoverTimeout);
            tooltip.classList.remove('show');
        });
    });
    
    // Hide tooltip when clicking elsewhere
    document.addEventListener('click', function() {
        tooltip.classList.remove('show');
    });
});
</script>
@endpush
