@extends('layouts.sidebar')

@section('title', 'Client Risk Assessment - Client Acceptance & Retention Risk Register')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Client Risk Assessment</h1>
                    <p class="text-muted">Comprehensive risk evaluation for new and existing clients</p>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user-shield me-2"></i>
                                Risk Assessment Form
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Client Lookup Section -->
                            <div class="alert alert-info mb-4">
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

                            <form method="POST" action="{{ route('client-risk-assessment.index') }}" id="assessmentForm">
                                @csrf
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="client_name" class="form-label">Client Name *</label>
                                        <input type="text" class="form-control @error('client_name') is-invalid @enderror" 
                                               id="client_name" name="client_name" value="{{ old('client_name') }}" required>
                                        @error('client_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="identification_status" class="form-label">Identification Status *</label>
                                        <select class="form-select @error('identification_status') is-invalid @enderror" 
                                                id="identification_status" name="identification_status" required>
                                            <option value="">Select status</option>
                                            <option value="Yes" {{ old('identification_status') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ old('identification_status') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                        @error('identification_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="screening_date" class="form-label">Screening Date *</label>
                                        <input type="date" class="form-control @error('screening_date') is-invalid @enderror" 
                                               id="screening_date" name="screening_date" value="{{ old('screening_date', date('Y-m-d')) }}" 
                                               min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                        <small class="form-text text-muted">Only current date is allowed for assessments</small>
                                        @error('screening_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="screening_result" class="form-label">Screening Result</label>
                                        <input type="text" class="form-control @error('screening_result') is-invalid @enderror" 
                                               id="screening_result" name="screening_result" value="{{ old('screening_result') }}">
                                        @error('screening_result')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="mb-3">Select Applicable Risks</h6>
                                
                                @if($predefinedRisks->count() > 0)
                                    @foreach($predefinedRisks as $categoryName => $risks)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h6 class="mb-0">{{ $categoryName }}</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($risks as $risk)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="selected_risks[]" value="{{ $risk->id }}" 
                                                           id="risk_{{ $risk->id }}">
                                                    <label class="form-check-label" for="risk_{{ $risk->id }}">
                                                        <strong>{{ $risk->title }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $risk->description }}</small>
                                                        <br>
                                                        <span class="badge bg-{{ $risk->risk_level_color }} me-2">{{ $risk->risk_level }}</span>
                                                        <span class="badge bg-secondary">{{ $risk->getFormattedPoints() }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        No predefined risks are available. Please contact your administrator to set up risk categories and predefined risks.
                                    </div>
                                @endif

                                @error('selected_risks')
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-calculator me-2"></i>
                                        Calculate Risk Assessment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Recent Assessments
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recentAssessments->count() > 0)
                                @foreach($recentAssessments as $assessment)
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                        <div>
                                            <strong>{{ $assessment->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $assessment->screening_date ? $assessment->screening_date->format('M d, Y') : 'No date' }}</small>
                                        </div>
                                        <span class="badge bg-{{ $assessment->risk_rating == 'High' ? 'danger' : ($assessment->risk_rating == 'Medium' ? 'warning' : 'success') }}">
                                            {{ $assessment->risk_rating }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>No recent assessments</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Assessment Guide
                            </h5>
                        </div>
                        <div class="card-body">
                            <h6>Risk Levels:</h6>
                            <ul class="list-unstyled">
                                <li><span class="badge bg-success me-2">Low</span> Accept with basic monitoring</li>
                                <li><span class="badge bg-warning me-2">Medium</span> Accept with standard monitoring</li>
                                <li><span class="badge bg-danger me-2">High</span> Enhanced due diligence required</li>
                            </ul>
                            
                            <h6>Process:</h6>
                            <ol class="small">
                                <li>Enter client details</li>
                                <li>Select applicable risks</li>
                                <li>Review assessment results</li>
                                <li>Make client decision</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Previous Assessment History Modal -->
<div class="modal fade" id="previousAssessmentModal" tabindex="-1" aria-labelledby="previousAssessmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previousAssessmentModalLabel">
                    <i class="fas fa-history me-2"></i>
                    Previous Assessment History
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="previousAssessmentContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="useSelectedClient">
                    <i class="fas fa-check me-1"></i>Use This Client for New Assessment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientSearch = document.getElementById('clientSearch');
    const searchClientBtn = document.getElementById('searchClientBtn');
    const proceedWithNewClientBtn = document.getElementById('proceedWithNewClient');
    const clientSearchResults = document.getElementById('clientSearchResults');
    const searchResultsList = document.getElementById('searchResultsList');
    const clientNameInput = document.getElementById('client_name');
    const assessmentForm = document.getElementById('assessmentForm');
    const previousAssessmentModal = new bootstrap.Modal(document.getElementById('previousAssessmentModal'));
    const useSelectedClientBtn = document.getElementById('useSelectedClient');
    
    let selectedClientId = null;
    let selectedClientName = null;

    // Enforce date restrictions for screening date
    const today = new Date().toISOString().split('T')[0];
    const screeningDateInput = document.getElementById('screening_date');
    if (screeningDateInput) {
        screeningDateInput.value = today;
        
        // Add validation to prevent date changes
        screeningDateInput.addEventListener('change', function() {
            if (this.value !== today) {
                alert('Only the current date is allowed for assessments. Please select today\'s date.');
                this.value = today;
            }
        });
    }

    // Search for clients
    searchClientBtn.addEventListener('click', function() {
        const searchTerm = clientSearch.value.trim();
        if (!searchTerm) {
            alert('Please enter a client name to search');
            return;
        }
        
        searchClients(searchTerm);
    });

    // Allow Enter key to search
    clientSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchClientBtn.click();
        }
    });

    // Proceed with new client
    proceedWithNewClientBtn.addEventListener('click', function() {
        clientSearchResults.style.display = 'none';
        clientNameInput.focus();
    });

    // Use selected client for new assessment
    useSelectedClientBtn.addEventListener('click', function() {
        if (selectedClientName) {
            clientNameInput.value = selectedClientName;
            clientSearchResults.style.display = 'none';
            previousAssessmentModal.hide();
            clientNameInput.focus();
        }
    });

    function searchClients(searchTerm) {
        fetch(`/api/clients/search?q=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                if (data.clients && data.clients.length > 0) {
                    displaySearchResults(data.clients);
                } else {
                    searchResultsList.innerHTML = '<div class="alert alert-warning">No existing clients found with that name.</div>';
                    clientSearchResults.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error searching clients:', error);
                searchResultsList.innerHTML = '<div class="alert alert-danger">Error searching for clients. Please try again.</div>';
                clientSearchResults.style.display = 'block';
            });
    }

    function displaySearchResults(clients) {
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
        clientSearchResults.style.display = 'block';
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
        selectedClientId = clientId;
        selectedClientName = clientName;
        
        // Load client history
        fetch(`/api/clients/${clientId}/history`)
            .then(response => response.json())
            .then(data => {
                displayClientHistory(data, clientName);
                previousAssessmentModal.show();
            })
            .catch(error => {
                console.error('Error loading client history:', error);
                alert('Error loading client history. Please try again.');
            });
    };

    window.selectClient = function(clientId, clientName) {
        selectedClientId = clientId;
        selectedClientName = clientName;
        clientNameInput.value = clientName;
        clientSearchResults.style.display = 'none';
        clientNameInput.focus();
    };

    function displayClientHistory(data, clientName) {
        const content = document.getElementById('previousAssessmentContent');
        
        let html = `
            <div class="alert alert-info">
                <h6><i class="fas fa-user me-2"></i>Client: ${clientName}</h6>
                <p class="mb-0">Review previous assessments to ensure AML compliance and track client risk progression.</p>
            </div>
        `;

        if (data.assessments && data.assessments.length > 0) {
            html += '<div class="table-responsive">';
            html += '<table class="table table-striped">';
            html += '<thead><tr><th>Date</th><th>Risk Rating</th><th>Score</th><th>Status</th><th>Decision</th><th>Monitoring</th><th>Actions</th></tr></thead>';
            html += '<tbody>';
            
            data.assessments.forEach((assessment, index) => {
                const date = new Date(assessment.created_at).toLocaleDateString();
                const riskColor = getRiskRatingColor(assessment.overall_risk_rating);
                const statusColor = getStatusColor(assessment.assessment_status);
                
                html += `
                    <tr>
                        <td>${date}</td>
                        <td><span class="badge bg-${riskColor}">${assessment.overall_risk_rating}</span></td>
                        <td>${assessment.overall_risk_points || 'N/A'}</td>
                        <td><span class="badge bg-${statusColor}">${assessment.assessment_status}</span></td>
                        <td>${assessment.client_acceptance || 'N/A'}</td>
                        <td>${assessment.ongoing_monitoring || 'N/A'}</td>
                        <td>
                            <a href="/clients/${assessment.id}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';
        } else {
            html += '<div class="alert alert-warning">No previous assessments found for this client.</div>';
        }
        
        content.innerHTML = html;
    }

    function getStatusColor(status) {
        switch(status.toLowerCase()) {
            case 'approved': return 'success';
            case 'pending': return 'warning';
            case 'rejected': return 'danger';
            default: return 'secondary';
        }
    }
});
</script>
@endsection
