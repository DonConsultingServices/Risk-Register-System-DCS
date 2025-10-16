@extends('layouts.sidebar')

@section('title', 'Edit Risk - DCS-Best')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Edit Risk</h1>
                <p class="page-subtitle">Update risk information using our business risk methodology</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('risks.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Risk Register
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-edit me-2"></i>Update Risk Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('risks.update', $risk) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Risk Category Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="business_risk_category" class="form-label fw-bold">Business Risk Category *</label>
                                <select name="business_risk_category" id="business_risk_category" class="form-select" required>
                                    <option value="">Select Risk Category</option>
                                    <option value="CR" {{ $risk->business_risk_category == 'CR' ? 'selected' : '' }}>CR - Client Risk</option>
                                    <option value="SR" {{ $risk->business_risk_category == 'SR' ? 'selected' : '' }}>SR - Service Risk</option>
                                    <option value="PR" {{ $risk->business_risk_category == 'PR' ? 'selected' : '' }}>PR - Payment Risk</option>
                                    <option value="DR" {{ $risk->business_risk_category == 'DR' ? 'selected' : '' }}>DR - Delivery Risk</option>
                                </select>
                                <div class="form-text">Select the primary business risk category</div>
                            </div>
                            <div class="col-md-6">
                                <label for="risk_id" class="form-label fw-bold">Risk ID</label>
                                <input type="text" class="form-control" id="risk_id" value="{{ $risk->risk_id ?? '' }}" readonly>
                                <div class="form-text">Auto-generated based on category and sequence</div>
                            </div>
                        </div>

                        <!-- Risk Title and Description -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="title" class="form-label fw-bold">Risk Title *</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{ $risk->title }}" required 
                                       placeholder="e.g., High-Risk Client Onboarding">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="description" class="form-label fw-bold">Risk Description *</label>
                                <textarea class="form-control" name="description" id="description" rows="4" required
                                          placeholder="Provide detailed description of the risk...">{{ $risk->description }}</textarea>
                            </div>
                        </div>

                        <!-- Risk Rating Methodology -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="impact" class="form-label fw-bold">Impact (I) *</label>
                                <select name="impact" id="impact" class="form-select" required>
                                    <option value="">Select Impact Level</option>
                                    <option value="High" {{ $risk->impact == 'High' ? 'selected' : '' }}>High - Significant financial/reputational impact</option>
                                    <option value="Medium" {{ $risk->impact == 'Medium' ? 'selected' : '' }}>Medium - Moderate impact on operations</option>
                                    <option value="Low" {{ $risk->impact == 'Low' ? 'selected' : '' }}>Low - Minimal impact on business</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="likelihood" class="form-label fw-bold">Likelihood (L) *</label>
                                <select name="likelihood" id="likelihood" class="form-select" required>
                                    <option value="">Select Likelihood</option>
                                    <option value="High" {{ $risk->likelihood == 'High' ? 'selected' : '' }}>High - Very likely to occur</option>
                                    <option value="Medium" {{ $risk->likelihood == 'Medium' ? 'selected' : '' }}>Medium - May occur occasionally</option>
                                    <option value="Low" {{ $risk->likelihood == 'Low' ? 'selected' : '' }}>Low - Rare occurrence</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="risk_rating" class="form-label fw-bold">Risk Rating (RR) *</label>
                                <select name="risk_rating" id="risk_rating" class="form-select" required>
                                    <option value="">Auto-calculated</option>
                                    <option value="High" {{ $risk->risk_rating == 'High' ? 'selected' : '' }}>High - Immediate action required</option>
                                    <option value="Medium" {{ $risk->risk_rating == 'Medium' ? 'selected' : '' }}>Medium - Monitoring and controls needed</option>
                                    <option value="Low" {{ $risk->risk_rating == 'Low' ? 'selected' : '' }}>Low - Acceptable with standard controls</option>
                                </select>
                            </div>
                        </div>

                        <!-- Client and Assignment -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="client_id" class="form-label fw-bold">Client *</label>
                                <select name="client_id" id="client_id" class="form-select" required>
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ $risk->client_id == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="assigned_user_id" class="form-label fw-bold">Assigned To</label>
                                <select name="assigned_user_id" id="assigned_user_id" class="form-select">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $risk->assigned_user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Due Date and Status -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="due_date" class="form-label fw-bold">Due Date</label>
                                <input type="date" class="form-control" name="due_date" id="due_date" 
                                       value="{{ $risk->due_date ? $risk->due_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="Open" {{ $risk->status == 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="In Progress" {{ $risk->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Closed" {{ $risk->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Mitigation Strategies -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="mitigation_strategies" class="form-label fw-bold">Mitigation Strategies</label>
                                <textarea class="form-control" name="mitigation_strategies" id="mitigation_strategies" rows="3"
                                          placeholder="Describe the strategies to mitigate this risk...">{{ $risk->mitigation_strategies }}</textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('risks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Risk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Risk Rating Guide -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6><i class="fas fa-info-circle me-2"></i>Risk Rating Guide</h6>
                </div>
                <div class="card-body">
                    <div class="rating-guide">
                        <div class="rating-item high">
                            <div class="rating-label">High Risk</div>
                            <div class="rating-description">Immediate action required, senior management involvement</div>
                        </div>
                        <div class="rating-item medium">
                            <div class="rating-label">Medium Risk</div>
                            <div class="rating-description">Monitoring and controls needed, regular reviews</div>
                        </div>
                        <div class="rating-item low">
                            <div class="rating-label">Low Risk</div>
                            <div class="rating-description">Acceptable with standard controls, routine monitoring</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #00072D 0%, #1a365d 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.page-subtitle {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
}

.card {
    border: none;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
}

.card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 12px 12px 0 0 !important;
    padding: 1.5rem;
}

.form-label {
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: #00072D;
    box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.1);
}

.rating-guide {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.rating-item {
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid;
}

.rating-item.high {
    background: #fef2f2;
    border-left-color: #dc2626;
}

.rating-item.medium {
    background: #fffbeb;
    border-left-color: #d97706;
}

.rating-item.low {
    background: #f0fdf4;
    border-left-color: #16a34a;
}

.rating-label {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.rating-description {
    font-size: 0.875rem;
    color: #6b7280;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-primary {
    background: #00072D;
    border-color: #00072D;
}

.btn-primary:hover {
    background: #1a365d;
    border-color: #1a365d;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('business_risk_category');
    const riskIdInput = document.getElementById('risk_id');
    const impactSelect = document.getElementById('impact');
    const likelihoodSelect = document.getElementById('likelihood');
    const riskRatingSelect = document.getElementById('risk_rating');

    // Auto-generate Risk ID when category is selected
    categorySelect.addEventListener('change', function() {
        if (this.value) {
            generateRiskId(this.value);
        } else {
            riskIdInput.value = '';
        }
    });

    // Auto-calculate Risk Rating based on Impact and Likelihood
    impactSelect.addEventListener('change', calculateRiskRating);
    likelihoodSelect.addEventListener('change', calculateRiskRating);

    function generateRiskId(category) {
        // This would typically call an API to get the next sequence number
        // For now, we'll use a timestamp-based approach
        const timestamp = Date.now().toString().slice(-4);
        riskIdInput.value = `${category}-${timestamp}`;
    }

    function calculateRiskRating() {
        const impact = impactSelect.value;
        const likelihood = likelihoodSelect.value;
        
        if (impact && likelihood) {
            let rating = 'Low';
            
            if (impact === 'High' && likelihood === 'High') {
                rating = 'High';
            } else if (impact === 'High' || likelihood === 'High') {
                rating = 'Medium';
            } else if (impact === 'Medium' && likelihood === 'Medium') {
                rating = 'Medium';
            }
            
            riskRatingSelect.value = rating;
        }
    }

    // Initialize risk ID if category is already selected
    if (categorySelect.value) {
        generateRiskId(categorySelect.value);
    }
});
</script>
@endsection

<style>
/* Mobile-First Responsive Design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0.5rem;
    }
    .page-header {
        margin: 0 -0.5rem 1.5rem -0.5rem;
        border-radius: 0;
        padding: 1rem;
    }
    .page-title {
        font-size: 1.25rem;
    }
    .page-subtitle {
        font-size: 0.85rem;
    }
    .header-actions {
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .card {
        margin: 0 -0.5rem;
        border-radius: 0;
    }
    .card-header {
        padding: 0.75rem 1rem;
    }
    .card-header h5 {
        font-size: 1rem;
    }
    .card-body {
        padding: 1rem;
    }
    .row {
        margin: 0;
    }
    .col-md-6, .col-md-4 {
        padding: 0.25rem;
        margin-bottom: 0.75rem;
    }
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.375rem;
    }
    .form-control, .form-select {
        padding: 0.75rem;
        font-size: 16px; /* Prevents zoom on iOS */
        border-radius: 8px;
    }
    .form-text {
        font-size: 0.8rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
    }
    .btn-group {
        flex-direction: column;
        gap: 0.5rem;
    }
    .btn-group .btn {
        width: 100%;
    }
    .text-end {
        text-align: center !important;
        margin-top: 1rem;
    }
    .table-responsive {
        font-size: 0.75rem;
        border-radius: 8px;
        overflow-x: auto;
    }
    .risk-matrix-table {
        min-width: 100%;
        width: 100%;
    }
    .risk-matrix-table th,
    .risk-matrix-table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.7rem;
        white-space: nowrap;
    }
    .risk-matrix-table th {
        font-size: 0.65rem;
        padding: 0.375rem 0.125rem;
    }
    .category-badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
    .risk-badge, .points-badge, .status-badge {
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
    }
    .owner-text, .mitigation-text {
        font-size: 0.6rem;
    }
    .risk-select {
        font-size: 0.7rem;
        padding: 0.5rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0.25rem;
    }
    .page-header {
        margin: 0 -0.25rem 1rem -0.25rem;
        padding: 0.75rem;
    }
    .page-title {
        font-size: 1.1rem;
    }
    .page-subtitle {
        font-size: 0.8rem;
    }
    .card {
        margin: 0 -0.25rem;
    }
    .card-header {
        padding: 0.5rem 0.75rem;
    }
    .card-header h5 {
        font-size: 0.9rem;
    }
    .card-body {
        padding: 0.75rem;
    }
    .col-md-6, .col-md-4 {
        padding: 0.125rem;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        padding: 0.625rem;
        font-size: 16px;
    }
    .form-text {
        font-size: 0.75rem;
    }
    .btn {
        padding: 0.625rem 1.25rem;
        font-size: 0.85rem;
    }
    .table-responsive {
        font-size: 0.7rem;
    }
    .risk-matrix-table {
        min-width: 100%;
        width: 100%;
    }
    .risk-matrix-table th,
    .risk-matrix-table td {
        padding: 0.375rem 0.125rem;
        font-size: 0.65rem;
    }
    .risk-matrix-table th {
        font-size: 0.6rem;
        padding: 0.25rem 0.1rem;
    }
    .category-badge {
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
    }
    .risk-badge, .points-badge, .status-badge {
        font-size: 0.55rem;
        padding: 0.15rem 0.3rem;
    }
    .owner-text, .mitigation-text {
        font-size: 0.55rem;
    }
    .risk-select {
        font-size: 0.65rem;
        padding: 0.4rem;
    }
}

@media (max-width: 480px) {
    .page-header {
        padding: 0.5rem;
    }
    .page-title {
        font-size: 1rem;
    }
    .page-subtitle {
        font-size: 0.75rem;
    }
    .card-header {
        padding: 0.4rem 0.5rem;
    }
    .card-header h5 {
        font-size: 0.85rem;
    }
    .card-body {
        padding: 0.5rem;
    }
    .form-control, .form-select {
        padding: 0.5rem;
        font-size: 16px;
    }
    .form-text {
        font-size: 0.7rem;
    }
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
    .table-responsive {
        font-size: 0.65rem;
    }
    .risk-matrix-table {
        min-width: 100%;
        width: 100%;
    }
    .risk-matrix-table th,
    .risk-matrix-table td {
        padding: 0.25rem 0.1rem;
        font-size: 0.6rem;
    }
    .risk-matrix-table th {
        font-size: 0.55rem;
        padding: 0.2rem 0.05rem;
    }
    .category-badge {
        font-size: 0.55rem;
        padding: 0.15rem 0.3rem;
    }
    .risk-badge, .points-badge, .status-badge {
        font-size: 0.5rem;
        padding: 0.1rem 0.25rem;
    }
    .owner-text, .mitigation-text {
        font-size: 0.5rem;
    }
    .risk-select {
        font-size: 0.6rem;
        padding: 0.3rem;
    }
}
</style>