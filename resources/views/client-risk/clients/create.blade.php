@extends('layouts.app')

@section('title', 'Register New Client - Risk Assessment')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-plus text-primary"></i>
                        Register New Client
                    </h1>
                    <p class="text-muted">Automated risk assessment will be performed based on your inputs</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('client-risk.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Search Check -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-search"></i> Check for Existing Client
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" id="checkClientName" class="form-control" placeholder="Client Name">
                        </div>
                        <div class="col-md-3">
                            <select id="checkClientType" class="form-control">
                                <option value="">Select Type</option>
                                <option value="individual">Individual</option>
                                <option value="corporate">Corporate</option>
                                <option value="partnership">Partnership</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="checkRegNumber" class="form-control" placeholder="Registration Number">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary" onclick="checkExistingClient()">
                                <i class="fas fa-search"></i> Check
                            </button>
                        </div>
                    </div>
                    
                    <!-- Existing Client Result -->
                    <div id="existingClientResult" class="mt-3" style="display: none;">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Existing Client Found</h6>
                            <div id="existingClientDetails"></div>
                            <div class="mt-2">
                                <a href="#" id="viewExistingClient" class="btn btn-sm btn-primary">View Client Details</a>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="hideExistingClient()">Continue with New Registration</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Client Registration Form -->
    <form id="clientRegistrationForm">
        @csrf
        
        <!-- Basic Client Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user"></i> Basic Client Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_name">Client Name *</label>
                                    <input type="text" id="client_name" name="client_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_type">Client Type *</label>
                                    <select id="client_type" name="client_type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="individual">Individual</option>
                                        <option value="corporate">Corporate</option>
                                        <option value="partnership">Partnership</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="registration_number">Registration Number</label>
                                    <input type="text" id="registration_number" name="registration_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tax_number">Tax Number</label>
                                    <input type="text" id="tax_number" name="tax_number" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" id="contact_person" name="contact_person" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" id="phone" name="phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" id="country" name="country" class="form-control" value="Namibia">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Identification & Screening -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-id-card"></i> Client Identification & Screening
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="identification_done" name="identification_done" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="identification_done">Client Identification Done</label>
                                    </div>
                                </div>
                                <div class="form-group" id="identificationDateGroup" style="display: none;">
                                    <label for="identification_date">Identification Date</label>
                                    <input type="date" id="identification_date" name="identification_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="screening_done" name="screening_done" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="screening_done">Client Screening Done</label>
                                    </div>
                                </div>
                                <div class="form-group" id="screeningGroup" style="display: none;">
                                    <label for="screening_date">Screening Date</label>
                                    <input type="date" id="screening_date" name="screening_date" class="form-control">
                                    <label for="screening_result" class="mt-2">Screening Result</label>
                                    <select id="screening_result" name="screening_result" class="form-control">
                                        <option value="">Select Result</option>
                                        <option value="passed">Passed</option>
                                        <option value="failed">Failed</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group" id="screeningDescriptionGroup" style="display: none;">
                            <label for="screening_description">Screening Description</label>
                            <textarea id="screening_description" name="screening_description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Risk Indicators -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-exclamation-triangle"></i> Risk Indicators
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="is_pep" name="is_pep" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="is_pep">Politically Exposed Person (PEP)</label>
                                    </div>
                                </div>
                                <div class="form-group" id="pepDetailsGroup" style="display: none;">
                                    <label for="pep_details">PEP Details</label>
                                    <textarea id="pep_details" name="pep_details" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="is_high_net_worth" name="is_high_net_worth" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="is_high_net_worth">High Net Worth Individual</label>
                                    </div>
                                </div>
                                <div class="form-group" id="annualIncomeGroup" style="display: none;">
                                    <label for="annual_income">Annual Income (NAD)</label>
                                    <input type="number" id="annual_income" name="annual_income" class="form-control" step="0.01">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="has_opaque_ownership" name="has_opaque_ownership" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="has_opaque_ownership">Opaque Ownership Structure</label>
                                    </div>
                                </div>
                                <div class="form-group" id="ownershipStructureGroup" style="display: none;">
                                    <label for="ownership_structure">Ownership Structure Details</label>
                                    <textarea id="ownership_structure" name="ownership_structure" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="has_inconsistent_docs" name="has_inconsistent_docs" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="has_inconsistent_docs">Inconsistent Documentation</label>
                                    </div>
                                </div>
                                <div class="form-group" id="documentationIssuesGroup" style="display: none;">
                                    <label for="documentation_issues">Documentation Issues</label>
                                    <textarea id="documentation_issues" name="documentation_issues" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Information (for Corporate Clients) -->
        <div class="row mb-4" id="businessInfoSection" style="display: none;">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-building"></i> Business Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_type">Business Type</label>
                                    <input type="text" id="business_type" name="business_type" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="industry_sector">Industry Sector</label>
                                    <input type="text" id="industry_sector" name="industry_sector" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="number_of_employees">Number of Employees</label>
                                    <input type="number" id="number_of_employees" name="number_of_employees" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="annual_turnover">Annual Turnover (NAD)</label>
                                    <input type="number" id="annual_turnover" name="annual_turnover" class="form-control" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requested Services -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-cogs"></i> Requested Services
                        </h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addService()">
                            <i class="fas fa-plus"></i> Add Service
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="servicesContainer">
                            <!-- Services will be added here dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Risk Assessment Preview -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-calculator"></i> Risk Assessment Preview
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="riskAssessmentPreview">
                            <p class="text-muted">Complete the form above to see the automated risk assessment preview.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Register Client & Perform Risk Assessment
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Service Template (hidden) -->
<template id="serviceTemplate">
    <div class="service-item border rounded p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Service #<span class="service-number"></span></h6>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeService(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Service Name *</label>
                    <input type="text" name="services[INDEX][service_name]" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Service Category *</label>
                    <select name="services[INDEX][service_category]" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="accounting_bookkeeping">Accounting & Bookkeeping</option>
                        <option value="hr_payroll">HR & Payroll</option>
                        <option value="accounting_officer">Accounting Officer</option>
                        <option value="business_review">Business Review</option>
                        <option value="risk_advisory">Risk Advisory</option>
                        <option value="tax_consulting">Tax Consulting</option>
                        <option value="insurance_consulting">Insurance Consulting</option>
                        <option value="business_development">Business Development</option>
                        <option value="training">Training</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Delivery Method *</label>
                    <select name="services[INDEX][delivery_method]" class="form-control" required>
                        <option value="">Select Method</option>
                        <option value="remote">Remote</option>
                        <option value="face_to_face">Face to Face</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Payment Method *</label>
                    <select name="services[INDEX][payment_method]" class="form-control" required>
                        <option value="">Select Method</option>
                        <option value="cash">Cash</option>
                        <option value="efts_swift">EFT/SWIFT</option>
                        <option value="pos">POS</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Service Value (NAD)</label>
                    <input type="number" name="services[INDEX][service_value]" class="form-control" step="0.01">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
let serviceIndex = 0;

// Check for existing client
function checkExistingClient() {
    const clientName = document.getElementById('checkClientName').value;
    const clientType = document.getElementById('checkClientType').value;
    const regNumber = document.getElementById('checkRegNumber').value;
    
    if (!clientName || !clientType) {
        alert('Please enter client name and type');
        return;
    }
    
    fetch('{{ route("client-risk.clients.check-existing") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            client_name: clientName,
            client_type: clientType,
            registration_number: regNumber
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            showExistingClient(data.client);
        } else {
            alert('No existing client found. You can proceed with registration.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error checking for existing client');
    });
}

function showExistingClient(client) {
    document.getElementById('existingClientDetails').innerHTML = `
        <strong>${client.client_name}</strong> (${client.client_number})<br>
        Type: ${client.client_type} | Risk Rating: ${client.risk_rating}<br>
        Total Points: ${client.total_points} | Acceptance: ${client.acceptance}<br>
        Last Review: ${client.last_review_date || 'N/A'} | Next Review: ${client.next_review_date || 'N/A'}
    `;
    document.getElementById('viewExistingClient').href = `/client-risk/clients/${client.id}`;
    document.getElementById('existingClientResult').style.display = 'block';
}

function hideExistingClient() {
    document.getElementById('existingClientResult').style.display = 'none';
}

// Add service
function addService() {
    const container = document.getElementById('servicesContainer');
    const template = document.getElementById('serviceTemplate');
    const clone = template.content.cloneNode(true);
    
    // Update index
    clone.querySelectorAll('[name*="INDEX"]').forEach(element => {
        element.name = element.name.replace('INDEX', serviceIndex);
    });
    
    clone.querySelector('.service-number').textContent = serviceIndex + 1;
    
    container.appendChild(clone);
    serviceIndex++;
}

// Remove service
function removeService(button) {
    button.closest('.service-item').remove();
}

// Show/hide conditional fields
document.getElementById('identification_done').addEventListener('change', function() {
    document.getElementById('identificationDateGroup').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('screening_done').addEventListener('change', function() {
    document.getElementById('screeningGroup').style.display = this.checked ? 'block' : 'none';
    document.getElementById('screeningDescriptionGroup').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('is_pep').addEventListener('change', function() {
    document.getElementById('pepDetailsGroup').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('is_high_net_worth').addEventListener('change', function() {
    document.getElementById('annualIncomeGroup').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('has_opaque_ownership').addEventListener('change', function() {
    document.getElementById('ownershipStructureGroup').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('has_inconsistent_docs').addEventListener('change', function() {
    document.getElementById('documentationIssuesGroup').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('client_type').addEventListener('change', function() {
    document.getElementById('businessInfoSection').style.display = 
        this.value === 'corporate' ? 'block' : 'none';
});

// Form submission
document.getElementById('clientRegistrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("client-risk.clients.store") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Client registered successfully!\n\nRisk Assessment Results:\n' +
                  'Risk Rating: ' + data.client.risk_rating + '\n' +
                  'Acceptance: ' + data.client.acceptance + '\n' +
                  'Total Points: ' + data.client.total_points + '\n' +
                  'Monitoring: ' + data.client.monitoring_frequency);
            
            // Redirect to client details
            window.location.href = `/client-risk/clients/${data.client.id}`;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error registering client');
    });
});

// Add first service by default
addService();
</script>

<style>
.service-item {
    background-color: #f8f9fa;
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@endsection 