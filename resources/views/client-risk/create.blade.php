@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>New Client Risk Assessment
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('client-risk.store') }}" method="POST" id="assessmentForm">
                    @csrf
                    
                    <!-- Client Information Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-1"></i>Client Information
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_name" class="form-label">Client Name *</label>
                                <input type="text" class="form-control @error('client_name') is-invalid @enderror" 
                                       id="client_name" name="client_name" value="{{ old('client_name') }}" required>
                                @error('client_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_identification_status" class="form-label">Client Identification Done?</label>
                                <select class="form-select @error('client_identification_status') is-invalid @enderror" 
                                        id="client_identification_status" name="client_identification_status">
                                    <option value="">Please select</option>
                                    @foreach($identificationStatusOptions as $status)
                                        <option value="{{ $status }}" {{ old('client_identification_status') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_identification_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Client Screening Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-search me-1"></i>Client Screening
                            </h6>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="client_screening_date" class="form-label">Date</label>
                                <input type="date" class="form-control @error('client_screening_date') is-invalid @enderror" 
                                       id="client_screening_date" name="client_screening_date" 
                                       value="{{ old('client_screening_date') }}">
                                @error('client_screening_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="client_screening_result" class="form-label">Result</label>
                                <input type="text" class="form-control @error('client_screening_result') is-invalid @enderror" 
                                       id="client_screening_result" name="client_screening_result" 
                                       value="{{ old('client_screening_result') }}">
                                @error('client_screening_result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_screening_risk_id" class="form-label">Risk ID Description</label>
                                <select class="form-select risk-select @error('client_screening_risk_id') is-invalid @enderror" 
                                        id="client_screening_risk_id" name="client_screening_risk_id" 
                                        data-section="client_screening">
                                    <option value="">Please select Risk ID</option>
                                    @foreach($availableRiskIds as $riskId => $description)
                                        <option value="{{ $riskId }}" {{ old('client_screening_risk_id') == $riskId ? 'selected' : '' }}>
                                            {{ $riskId }} - {{ $description }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_screening_risk_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_screening_description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="client_screening_description" 
                                       name="client_screening_description" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="client_screening_impact" class="form-label">Impact</label>
                                <select class="form-select impact-select @error('client_screening_impact') is-invalid @enderror" 
                                        id="client_screening_impact" name="client_screening_impact" 
                                        data-section="client_screening">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('client_screening_impact') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('client_screening_impact') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('client_screening_impact') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('client_screening_impact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="client_screening_likelihood" class="form-label">Likelihood</label>
                                <select class="form-select likelihood-select @error('client_screening_likelihood') is-invalid @enderror" 
                                        id="client_screening_likelihood" name="client_screening_likelihood" 
                                        data-section="client_screening">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('client_screening_likelihood') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('client_screening_likelihood') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('client_screening_likelihood') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('client_screening_likelihood')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="client_screening_risk_rating" class="form-label">Risk Rating</label>
                                <input type="text" class="form-control risk-rating-display" 
                                       id="client_screening_risk_rating" name="client_screening_risk_rating" 
                                       value="{{ old('client_screening_risk_rating') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Client Category Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-tag me-1"></i>Category of Client
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_category_risk_id" class="form-label">Risk ID Description</label>
                                <select class="form-select risk-select @error('client_category_risk_id') is-invalid @enderror" 
                                        id="client_category_risk_id" name="client_category_risk_id" 
                                        data-section="client_category">
                                    <option value="">Please select Risk ID</option>
                                    @foreach($availableRiskIds as $riskId => $description)
                                        <option value="{{ $riskId }}" {{ old('client_category_risk_id') == $riskId ? 'selected' : '' }}>
                                            {{ $riskId }} - {{ $description }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_category_risk_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_category_description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="client_category_description" 
                                       name="client_category_description" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="client_category_impact" class="form-label">Impact</label>
                                <select class="form-select impact-select @error('client_category_impact') is-invalid @enderror" 
                                        id="client_category_impact" name="client_category_impact" 
                                        data-section="client_category">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('client_category_impact') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('client_category_impact') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('client_category_impact') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('client_category_impact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="client_category_likelihood" class="form-label">Likelihood</label>
                                <select class="form-select likelihood-select @error('client_category_likelihood') is-invalid @enderror" 
                                        id="client_category_likelihood" name="client_category_likelihood" 
                                        data-section="client_category">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('client_category_likelihood') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('client_category_likelihood') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('client_category_likelihood') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('client_category_likelihood')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="client_category_risk_rating" class="form-label">Risk Rating</label>
                                <input type="text" class="form-control risk-rating-display" 
                                       id="client_category_risk_rating" name="client_category_risk_rating" 
                                       value="{{ old('client_category_risk_rating') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Requested Services Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-cogs me-1"></i>Requested Services?
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="requested_services_risk_id" class="form-label">Risk ID Description</label>
                                <select class="form-select risk-select @error('requested_services_risk_id') is-invalid @enderror" 
                                        id="requested_services_risk_id" name="requested_services_risk_id" 
                                        data-section="requested_services">
                                    <option value="">Please select Risk ID</option>
                                    @foreach($availableRiskIds as $riskId => $description)
                                        <option value="{{ $riskId }}" {{ old('requested_services_risk_id') == $riskId ? 'selected' : '' }}>
                                            {{ $riskId }} - {{ $description }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('requested_services_risk_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="requested_services_description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="requested_services_description" 
                                       name="requested_services_description" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="requested_services_impact" class="form-label">Impact</label>
                                <select class="form-select impact-select @error('requested_services_impact') is-invalid @enderror" 
                                        id="requested_services_impact" name="requested_services_impact" 
                                        data-section="requested_services">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('requested_services_impact') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('requested_services_impact') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('requested_services_impact') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('requested_services_impact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="requested_services_likelihood" class="form-label">Likelihood</label>
                                <select class="form-select likelihood-select @error('requested_services_likelihood') is-invalid @enderror" 
                                        id="requested_services_likelihood" name="requested_services_likelihood" 
                                        data-section="requested_services">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('requested_services_likelihood') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('requested_services_likelihood') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('requested_services_likelihood') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('requested_services_likelihood')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="requested_services_risk_rating" class="form-label">Risk Rating</label>
                                <input type="text" class="form-control risk-rating-display" 
                                       id="requested_services_risk_rating" name="requested_services_risk_rating" 
                                       value="{{ old('requested_services_risk_rating') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Payment Option Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-credit-card me-1"></i>Anticipated Payment Option?
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_option_risk_id" class="form-label">Risk ID Description</label>
                                <select class="form-select risk-select @error('payment_option_risk_id') is-invalid @enderror" 
                                        id="payment_option_risk_id" name="payment_option_risk_id" 
                                        data-section="payment_option">
                                    <option value="">Please select Risk ID</option>
                                    @foreach($availableRiskIds as $riskId => $description)
                                        <option value="{{ $riskId }}" {{ old('payment_option_risk_id') == $riskId ? 'selected' : '' }}>
                                            {{ $riskId }} - {{ $description }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_option_risk_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_option_description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="payment_option_description" 
                                       name="payment_option_description" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="payment_option_impact" class="form-label">Impact</label>
                                <select class="form-select impact-select @error('payment_option_impact') is-invalid @enderror" 
                                        id="payment_option_impact" name="payment_option_impact" 
                                        data-section="payment_option">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('payment_option_impact') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('payment_option_impact') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('payment_option_impact') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('payment_option_impact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="payment_option_likelihood" class="form-label">Likelihood</label>
                                <select class="form-select likelihood-select @error('payment_option_likelihood') is-invalid @enderror" 
                                        id="payment_option_likelihood" name="payment_option_likelihood" 
                                        data-section="payment_option">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('payment_option_likelihood') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('payment_option_likelihood') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('payment_option_likelihood') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('payment_option_likelihood')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="payment_option_risk_rating" class="form-label">Risk Rating</label>
                                <input type="text" class="form-control risk-rating-display" 
                                       id="payment_option_risk_rating" name="payment_option_risk_rating" 
                                       value="{{ old('payment_option_risk_rating') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Delivery Method Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-truck me-1"></i>Anticipated Service Delivery Method?
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="delivery_method_risk_id" class="form-label">Risk ID Description</label>
                                <select class="form-select risk-select @error('delivery_method_risk_id') is-invalid @enderror" 
                                        id="delivery_method_risk_id" name="delivery_method_risk_id" 
                                        data-section="delivery_method">
                                    <option value="">Please select Risk ID</option>
                                    @foreach($availableRiskIds as $riskId => $description)
                                        <option value="{{ $riskId }}" {{ old('delivery_method_risk_id') == $riskId ? 'selected' : '' }}>
                                            {{ $riskId }} - {{ $description }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('delivery_method_risk_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="delivery_method_description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="delivery_method_description" 
                                       name="delivery_method_description" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="delivery_method_impact" class="form-label">Impact</label>
                                <select class="form-select impact-select @error('delivery_method_impact') is-invalid @enderror" 
                                        id="delivery_method_impact" name="delivery_method_impact" 
                                        data-section="delivery_method">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('delivery_method_impact') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('delivery_method_impact') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('delivery_method_impact') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('delivery_method_impact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="delivery_method_likelihood" class="form-label">Likelihood</label>
                                <select class="form-select likelihood-select @error('delivery_method_likelihood') is-invalid @enderror" 
                                        id="delivery_method_likelihood" name="delivery_method_likelihood" 
                                        data-section="delivery_method">
                                    <option value="">Select</option>
                                    <option value="High" {{ old('delivery_method_likelihood') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('delivery_method_likelihood') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('delivery_method_likelihood') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                                @error('delivery_method_likelihood')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="delivery_method_risk_rating" class="form-label">Risk Rating</label>
                                <input type="text" class="form-control risk-rating-display" 
                                       id="delivery_method_risk_rating" name="delivery_method_risk_rating" 
                                       value="{{ old('delivery_method_risk_rating') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Overall Assessment Results -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-calculator me-1"></i>Overall Assessment Results
                            </h6>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4 id="overallRiskPoints">0</h4>
                                    <small>Overall Risk Points</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card" id="overallRiskRatingCard">
                                <div class="card-body text-center">
                                    <h4 id="overallRiskRating">-</h4>
                                    <small>Overall Risk Rating</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card" id="clientAcceptanceCard">
                                <div class="card-body text-center">
                                    <h4 id="clientAcceptance">-</h4>
                                    <small>Client Acceptance</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4 id="ongoingMonitoring">-</h4>
                                    <small>Ongoing Monitoring</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- DCS Specific Fields -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-building me-1"></i>DCS Specific Information
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dcs_risk_appetite" class="form-label">DCS Risk Appetite</label>
                                <input type="text" class="form-control @error('dcs_risk_appetite') is-invalid @enderror" 
                                       id="dcs_risk_appetite" name="dcs_risk_appetite" 
                                       value="{{ old('dcs_risk_appetite') }}">
                                @error('dcs_risk_appetite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dcs_comments" class="form-label">DCS Comments</label>
                                <textarea class="form-control @error('dcs_comments') is-invalid @enderror" 
                                          id="dcs_comments" name="dcs_comments" rows="3">{{ old('dcs_comments') }}</textarea>
                                @error('dcs_comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('client-risk.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Assessment
                        </button>
                        <button type="button" class="btn btn-info" onclick="testAutoFill()">
                            <i class="fas fa-bug me-1"></i>Test Auto-Fill
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    const riskRatingGuide = @json($riskRatingGuide);
    const riskMatrix = @json($riskMatrix);
    console.log('riskMatrix loaded:', riskMatrix);
    
    // Auto-fill fields when Risk ID is selected
    function autoFillRiskFields(section, riskId) {
        console.log('autoFillRiskFields called with:', section, riskId);
        console.log('riskMatrix:', riskMatrix);
        
        if (riskId && riskMatrix[riskId]) {
            const riskData = riskMatrix[riskId];
            console.log('Found risk data:', riskData);
            
            // Fill description field
            const descriptionField = document.getElementById(section + '_description');
            console.log('Description field:', descriptionField);
            if (descriptionField) {
                descriptionField.value = riskData.description;
                console.log('Set description to:', riskData.description);
            }
            
            // Fill impact field
            const impactField = document.getElementById(section + '_impact');
            console.log('Impact field:', impactField);
            if (impactField) {
                impactField.value = riskData.impact;
                console.log('Set impact to:', riskData.impact);
            }
            
            // Fill likelihood field
            const likelihoodField = document.getElementById(section + '_likelihood');
            console.log('Likelihood field:', likelihoodField);
            if (likelihoodField) {
                likelihoodField.value = riskData.likelihood;
                console.log('Set likelihood to:', riskData.likelihood);
            }
            
            // Calculate and fill risk rating
            calculateIndividualRisk(section);
        } else {
            console.log('No risk data found for:', riskId);
            // Clear fields if no risk ID selected
            const descriptionField = document.getElementById(section + '_description');
            if (descriptionField) {
                descriptionField.value = '';
            }
            
            const impactField = document.getElementById(section + '_impact');
            if (impactField) {
                impactField.value = '';
            }
            
            const likelihoodField = document.getElementById(section + '_likelihood');
            if (likelihoodField) {
                likelihoodField.value = '';
            }
            
            const riskRatingField = document.getElementById(section + '_risk_rating');
            if (riskRatingField) {
                riskRatingField.value = '';
            }
        }
    }
    
    // Calculate individual risk rating
    function calculateIndividualRisk(section) {
        const impact = document.getElementById(section + '_impact').value;
        const likelihood = document.getElementById(section + '_likelihood').value;
        
        if (impact && likelihood) {
            const impactScore = getLevelScore(impact);
            const likelihoodScore = getLevelScore(likelihood);
            const totalScore = impactScore + likelihoodScore;
            
            let riskRating = 'Low';
            if (totalScore >= 6) {
                riskRating = 'High';
            } else if (totalScore >= 4) {
                riskRating = 'Medium';
            }
            
            document.getElementById(section + '_risk_rating').value = riskRating;
        }
    }
    
    // Calculate overall risk assessment
    function calculateOverallRisk() {
        const riskSections = ['client_screening', 'client_category', 'requested_services', 'payment_option', 'delivery_method'];
        let totalPoints = 0;
        
        riskSections.forEach(section => {
            const riskId = document.getElementById(section + '_risk_id').value;
            if (riskId) {
                const points = getRiskPoints(riskId);
                totalPoints += points;
            }
        });
        
        // Update overall risk points
        document.getElementById('overallRiskPoints').textContent = totalPoints;
        
        // Get overall assessment
        const assessment = getOverallRiskAssessment(totalPoints);
        
        // Update overall risk rating
        document.getElementById('overallRiskRating').textContent = assessment.rating;
        document.getElementById('overallRiskRatingCard').className = `card bg-${assessment.color} text-white`;
        
        // Update client acceptance
        document.getElementById('clientAcceptance').textContent = assessment.acceptance;
        if (assessment.acceptance === 'Accept client') {
            document.getElementById('clientAcceptanceCard').className = 'card bg-success text-white';
        } else {
            document.getElementById('clientAcceptanceCard').className = 'card bg-danger text-white';
        }
        
        // Update ongoing monitoring
        document.getElementById('ongoingMonitoring').textContent = assessment.monitoring;
    }
    
    function getLevelScore(level) {
        switch (level) {
            case 'High': return 3;
            case 'Medium': return 2;
            case 'Low': return 1;
            default: return 1;
        }
    }
    
    function getRiskPoints(riskId) {
        const riskPoints = {
            'R001': 2,
            'R002': 1,
            'R003': 3,
            'R004': 2,
            'R005': 1,
            'R006': 5,
            'R007': 4,
            'R008': 5,
            'R009': 4,
            'R010': 5
        };
        return riskPoints[riskId] || 0;
    }
    
    function getOverallRiskAssessment(totalPoints) {
        if (totalPoints >= 11) {
            return {
                rating: 'High',
                acceptance: 'Reject client',
                monitoring: 'Not applicable',
                color: 'danger'
            };
        } else if (totalPoints >= 6) {
            return {
                rating: 'Medium',
                acceptance: 'Accept with conditions',
                monitoring: 'Enhanced monitoring',
                color: 'warning'
            };
        } else {
            return {
                rating: 'Low',
                acceptance: 'Accept client',
                monitoring: 'Standard monitoring',
                color: 'success'
            };
        }
    }
    
    // Test function for debugging
    window.testAutoFill = function() {
        console.log('Test button clicked');
        console.log('Testing with R001');
        autoFillRiskFields('client_screening', 'R001');
    };
    
    // Add event listeners for impact and likelihood changes
    document.querySelectorAll('.impact-select, .likelihood-select').forEach(select => {
        select.addEventListener('change', function() {
            const section = this.dataset.section;
            calculateIndividualRisk(section);
            calculateOverallRisk();
        });
    });
    
    // Add event listeners for risk ID changes
    console.log('Setting up event listeners for risk-select elements');
    const riskSelects = document.querySelectorAll('.risk-select');
    console.log('Found risk-select elements:', riskSelects.length);
    
    riskSelects.forEach(select => {
        console.log('Adding event listener to:', select.id);
        select.addEventListener('change', function() {
            console.log('Risk select changed:', this.id, 'value:', this.value);
            const section = this.dataset.section;
            const riskId = this.value;
            console.log('Section:', section, 'Risk ID:', riskId);
            autoFillRiskFields(section, riskId);
            calculateOverallRisk();
        });
    });
    
    // Initial calculation
    console.log('Running initial calculation');
    calculateOverallRisk();
});
</script>
@endsection 