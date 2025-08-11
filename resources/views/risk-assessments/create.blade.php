@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Client Risk Assessment Form
                </h5>
            </div>
            <div class="card-body">
                <!-- Mandatory Fields Notice -->
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-1"></i>Important Information
                    </h6>
                    <ul class="mb-0">
                        <li><strong>All fields marked with <span class="text-danger">*</span> are mandatory</strong></li>
                        <li>Risk ID dropdowns will auto-populate Description, Impact, Likelihood, and Risk Rating fields</li>
                        <li>Overall assessment will be calculated automatically based on your selections</li>
                        <li>Please ensure all details are provided for a complete risk assessment</li>
                    </ul>
                </div>

                <form action="{{ route('risk-assessments.store') }}" method="POST">
                    @csrf
                    
                    <!-- Basic Client Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_name" class="form-label">Client Name *</label>
                                <input type="text" class="form-control @error('client_name') is-invalid @enderror" 
                                       id="client_name" name="client_name" value="{{ old('client_name') }}" 
                                       placeholder="Enter client name" required>
                                @error('client_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_identification" class="form-label">Client Identification Done? *</label>
                                <select class="form-select @error('client_identification') is-invalid @enderror" 
                                        id="client_identification" name="client_identification" required>
                                    <option value="">Select Status</option>
                                    <option value="Yes" {{ old('client_identification') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('client_identification') == 'No' ? 'selected' : '' }}>No</option>
                                    <option value="In-progress" {{ old('client_identification') == 'In-progress' ? 'selected' : '' }}>In-progress</option>
                                </select>
                                @error('client_identification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Client Screening Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-search me-1"></i>Client Screening
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="screening_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                    <select class="form-select" id="screening_risk_id" name="screening_risk_id" required>
                                        <option value=""> Select Risk ID</option>
                                        <option value="CR-01" data-description="PIP / PEP client" data-impact="High" data-likelihood="Medium" data-rating="High" data-points="5">CR-01</option>
                                        <option value="CR-02" data-description="Medium-risk client profile" data-impact="Medium" data-likelihood="High" data-rating="Medium" data-points="4">CR-02</option>
                                        <option value="CR-03" data-description="Low-risk client profile" data-impact="Medium" data-likelihood="Medium" data-rating="Medium" data-points="3">CR-03</option>
                                    </select>
                                    <small class="form-text text-muted">Please select a Risk ID to auto-populate other fields</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="screening_description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="screening_description" name="screening_description" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="screening_impact" class="form-label">Impact</label>
                                    <input type="text" class="form-control" id="screening_impact" name="screening_impact" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="screening_likelihood" class="form-label">Likelihood</label>
                                    <input type="text" class="form-control" id="screening_likelihood" name="screening_likelihood" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="screening_risk_rating" class="form-label">Risk Rating</label>
                                    <input type="text" class="form-control" id="screening_risk_rating" name="screening_risk_rating" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category of Client Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user me-1"></i>Category of Client
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="client_category_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                    <select class="form-select" id="client_category_risk_id" name="client_category_risk_id" required>
                                        <option value=""> Select Risk ID</option>
                                        <option value="CR-01" data-description="PIP / PEP client" data-impact="High" data-likelihood="Medium" data-rating="High" data-points="5">CR-01</option>
                                        <option value="CR-02" data-description="Medium-risk client profile" data-impact="Medium" data-likelihood="High" data-rating="Medium" data-points="4">CR-02</option>
                                        <option value="CR-03" data-description="Low-risk client profile" data-impact="Medium" data-likelihood="Medium" data-rating="Medium" data-points="3">CR-03</option>
                                    </select>
                                    <small class="form-text text-muted">Please select a Risk ID to auto-populate other fields</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="client_category_description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="client_category_description" name="client_category_description" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="client_category_impact" class="form-label">Impact</label>
                                    <input type="text" class="form-control" id="client_category_impact" name="client_category_impact" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="client_category_likelihood" class="form-label">Likelihood</label>
                                    <input type="text" class="form-control" id="client_category_likelihood" name="client_category_likelihood" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="client_category_risk_rating" class="form-label">Risk Rating</label>
                                    <input type="text" class="form-control" id="client_category_risk_rating" name="client_category_risk_rating" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requested Services Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-cogs me-1"></i>Requested Services
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="services_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                    <select class="form-select" id="services_risk_id" name="services_risk_id" required>
                                        <option value=""> Select Risk ID</option>
                                        <option value="SR-01" data-description="High-risk services" data-impact="High" data-likelihood="High" data-rating="High" data-points="5">SR-01</option>
                                        <option value="SR-02" data-description="Medium-risk services" data-impact="Medium" data-likelihood="Medium" data-rating="Medium" data-points="3">SR-02</option>
                                        <option value="SR-03" data-description="Standard services" data-impact="Low" data-likelihood="Medium" data-rating="Low" data-points="1">SR-03</option>
                                        <option value="SR-04" data-description="Standard services" data-impact="Medium" data-likelihood="Medium" data-rating="Medium" data-points="3">SR-04</option>
                                    </select>
                                    <small class="form-text text-muted">Please select a Risk ID to auto-populate other fields</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="services_description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="services_description" name="services_description" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="services_impact" class="form-label">Impact</label>
                                    <input type="text" class="form-control" id="services_impact" name="services_impact" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="services_likelihood" class="form-label">Likelihood</label>
                                    <input type="text" class="form-control" id="services_likelihood" name="services_likelihood" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="services_risk_rating" class="form-label">Risk Rating</label>
                                    <input type="text" class="form-control" id="services_risk_rating" name="services_risk_rating" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Anticipated Payment Option Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-credit-card me-1"></i>Anticipated Payment Option
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="payment_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                    <select class="form-select" id="payment_risk_id" name="payment_risk_id" required>
                                        <option value=""> Select Risk ID</option>
                                        <option value="PR-01" data-description="High-risk payment methods" data-impact="High" data-likelihood="High" data-rating="High" data-points="5">PR-01</option>
                                        <option value="PR-02" data-description="EFTs/SWIFT" data-impact="Medium" data-likelihood="Medium" data-rating="Medium" data-points="3">PR-02</option>
                                        <option value="PR-03" data-description="POS Payments" data-impact="Low" data-likelihood="Medium" data-rating="Low" data-points="1">PR-03</option>
                                    </select>
                                    <small class="form-text text-muted">Please select a Risk ID to auto-populate other fields</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="payment_description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="payment_description" name="payment_description" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="payment_impact" class="form-label">Impact</label>
                                    <input type="text" class="form-control" id="payment_impact" name="payment_impact" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="payment_likelihood" class="form-label">Likelihood</label>
                                    <input type="text" class="form-control" id="payment_likelihood" name="payment_likelihood" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="payment_risk_rating" class="form-label">Risk Rating</label>
                                    <input type="text" class="form-control" id="payment_risk_rating" name="payment_risk_rating" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Anticipated Service Delivery Method Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-truck me-1"></i>Anticipated Service Delivery Method
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="delivery_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                    <select class="form-select" id="delivery_risk_id" name="delivery_risk_id" required>
                                        <option value=""> Select Risk ID</option>
                                        <option value="DR-01" data-description="Remote service risks" data-impact="High" data-likelihood="Medium" data-rating="High" data-points="4">DR-01</option>
                                        <option value="DR-02" data-description="Medium-risk delivery methods" data-impact="Medium" data-likelihood="Low" data-rating="Medium" data-points="2">DR-02</option>
                                    </select>
                                    <small class="form-text text-muted">Please select a Risk ID to auto-populate other fields</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="delivery_description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="delivery_description" name="delivery_description" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="delivery_impact" class="form-label">Impact</label>
                                    <input type="text" class="form-control" id="delivery_impact" name="delivery_impact" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="delivery_likelihood" class="form-label">Likelihood</label>
                                    <input type="text" class="form-control" id="delivery_likelihood" name="delivery_likelihood" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="delivery_risk_rating" class="form-label">Risk Rating</label>
                                    <input type="text" class="form-control" id="delivery_risk_rating" name="delivery_risk_rating" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overall Assessment Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-chart-bar me-1"></i>Overall Assessment
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="overall_risk_points" class="form-label">Overall Risk Points</label>
                                    <input type="text" class="form-control" id="overall_risk_points" name="overall_risk_points" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="overall_risk_rating" class="form-label">Overall Risk Rating</label>
                                    <input type="text" class="form-control" id="overall_risk_rating" name="overall_risk_rating" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="client_acceptance" class="form-label">Client Acceptance</label>
                                    <input type="text" class="form-control" id="client_acceptance" name="client_acceptance" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="ongoing_monitoring" class="form-label">Ongoing Monitoring</label>
                                    <input type="text" class="form-control" id="ongoing_monitoring" name="ongoing_monitoring" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DCS Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-building me-1"></i>DCS Assessment
                        </h6>
                        
                        <div class="row">
                                                            <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="dcs_risk_appetite" class="form-label">DCS Risk Appetite <span class="text-danger">*</span></label>
                                        <select class="form-select @error('dcs_risk_appetite') is-invalid @enderror" 
                                                id="dcs_risk_appetite" name="dcs_risk_appetite" required>
                                            <option value="">▼ Select Risk Appetite</option>
                                            <option value="Conservative" {{ old('dcs_risk_appetite') == 'Conservative' ? 'selected' : '' }}>Conservative</option>
                                            <option value="Moderate" {{ old('dcs_risk_appetite') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                            <option value="Aggressive" {{ old('dcs_risk_appetite') == 'Aggressive' ? 'selected' : '' }}>Aggressive</option>
                                        </select>
                                        <small class="form-text text-muted">Please select DCS risk appetite preference</small>
                                        @error('dcs_risk_appetite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dcs_comments" class="form-label">DCS Comments</label>
                                    <textarea class="form-control @error('dcs_comments') is-invalid @enderror" 
                                              id="dcs_comments" name="dcs_comments" rows="3" 
                                              placeholder="Enter DCS comments">{{ old('dcs_comments') }}</textarea>
                                    @error('dcs_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Risk Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-populate fields based on Risk ID selection
document.addEventListener('DOMContentLoaded', function() {
    const riskSelectors = [
        'screening_risk_id',
        'client_category_risk_id', 
        'services_risk_id',
        'payment_risk_id',
        'delivery_risk_id'
    ];

    riskSelectors.forEach(selector => {
        const select = document.getElementById(selector);
        if (select) {
            select.addEventListener('change', function() {
                updateRiskFields(selector);
                calculateOverallRisk();
            });
        }
    });

    function updateRiskFields(selector) {
        const select = document.getElementById(selector);
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const prefix = selector.replace('_risk_id', '');
            
            document.getElementById(prefix + '_description').value = selectedOption.getAttribute('data-description') || '';
            document.getElementById(prefix + '_impact').value = selectedOption.getAttribute('data-impact') || '';
            document.getElementById(prefix + '_likelihood').value = selectedOption.getAttribute('data-likelihood') || '';
            document.getElementById(prefix + '_risk_rating').value = selectedOption.getAttribute('data-rating') || '';
        } else {
            const prefix = selector.replace('_risk_id', '');
            
            document.getElementById(prefix + '_description').value = '';
            document.getElementById(prefix + '_impact').value = '';
            document.getElementById(prefix + '_likelihood').value = '';
            document.getElementById(prefix + '_risk_rating').value = '';
        }
    }

    function calculateOverallRisk() {
        let totalPoints = 0;
        
        riskSelectors.forEach(selector => {
            const select = document.getElementById(selector);
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const points = parseInt(selectedOption.getAttribute('data-points')) || 0;
                totalPoints += points;
            }
        });

        document.getElementById('overall_risk_points').value = totalPoints;

        // Determine overall risk rating based on total points
        let rating = '';
        let acceptance = '';
        let monitoring = '';

        if (totalPoints >= 20) {
            rating = 'Very High-risk';
            acceptance = 'Do not accept client';
            monitoring = 'N/A';
        } else if (totalPoints >= 17) {
            rating = 'High-risk';
            acceptance = 'Accept client';
            monitoring = 'Quarterly review';
        } else if (totalPoints >= 15) {
            rating = 'Medium-risk';
            acceptance = 'Accept client';
            monitoring = 'Bi-Annually';
        } else if (totalPoints >= 10) {
            rating = 'Low-risk';
            acceptance = 'Accept client';
            monitoring = 'Annually';
        } else {
            rating = 'Low-risk';
            acceptance = 'Accept client';
            monitoring = 'Annually';
        }

        document.getElementById('overall_risk_rating').value = rating;
        document.getElementById('client_acceptance').value = acceptance;
        document.getElementById('ongoing_monitoring').value = monitoring;
    }
});
</script>
@endsection 