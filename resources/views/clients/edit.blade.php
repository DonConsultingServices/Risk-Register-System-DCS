@extends('layouts.sidebar')

@section('title', 'Edit Client')

@section('content')
<style>
    /* Mobile-First Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem;
        }
        
        .page-title-box {
            padding: 1rem 0;
        }
        
        .page-title {
            font-size: 1.25rem;
        }
        
        .breadcrumb {
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }
        
        .card {
            margin-bottom: 1rem;
        }
        
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .header-title {
            font-size: 1.1rem;
        }
        
        .row {
            margin: 0;
        }
        
        .col-md-6 {
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
        
        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }
        
        .text-end {
            text-align: center !important;
            margin-top: 1rem;
        }
        
        .btn-group {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .btn-group .btn {
            width: 100%;
        }
    }
    
    @media (max-width: 576px) {
        .container-fluid {
            padding: 0.25rem;
        }
        
        .page-title {
            font-size: 1.1rem;
        }
        
        .breadcrumb {
            font-size: 0.75rem;
        }
        
        .card-header {
            padding: 0.5rem 0.75rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .header-title {
            font-size: 1rem;
        }
        
        .col-md-6 {
            padding: 0.125rem;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            padding: 0.625rem;
            font-size: 16px;
        }
        
        .btn {
            padding: 0.625rem 1.25rem;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 480px) {
        .page-title {
            font-size: 1rem;
        }
        
        .breadcrumb {
            font-size: 0.7rem;
        }
        
        .card-header {
            padding: 0.4rem 0.5rem;
        }
        
        .card-body {
            padding: 0.5rem;
        }
        
        .header-title {
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            padding: 0.5rem;
            font-size: 16px;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Client: {{ $client->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Client Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('clients.update', $client) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Client Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $client->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company" class="form-label">Company</label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror" 
                                           id="company" name="company" value="{{ old('company', $client->company) }}">
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $client->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $client->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="industry" class="form-label">Industry</label>
                                    <select class="form-select @error('industry') is-invalid @enderror" 
                                            id="industry" name="industry">
                                        <option value="">Select Industry</option>
                                        <option value="Technology" {{ old('industry', $client->industry) == 'Technology' ? 'selected' : '' }}>Technology</option>
                                        <option value="Finance" {{ old('industry', $client->industry) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="Healthcare" {{ old('industry', $client->industry) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                        <option value="Manufacturing" {{ old('industry', $client->industry) == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                        <option value="Retail" {{ old('industry', $client->industry) == 'Retail' ? 'selected' : '' }}>Retail</option>
                                        <option value="Real Estate" {{ old('industry', $client->industry) == 'Real Estate' ? 'selected' : '' }}>Real Estate</option>
                                        <option value="Education" {{ old('industry', $client->industry) == 'Education' ? 'selected' : '' }}>Education</option>
                                        <option value="Government" {{ old('industry', $client->industry) == 'Government' ? 'selected' : '' }}>Government</option>
                                        <option value="Non-Profit" {{ old('industry', $client->industry) == 'Non-Profit' ? 'selected' : '' }}>Non-Profit</option>
                                        <option value="Other" {{ old('industry', $client->industry) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('industry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="Active" {{ old('status', $client->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status', $client->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Prospect" {{ old('status', $client->status) == 'Prospect' ? 'selected' : '' }}>Prospect</option>
                                        <option value="Suspended" {{ old('status', $client->status) == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="screening_date" class="form-label">Initial Screening Date</label>
                                    <input type="date" class="form-control @error('screening_date') is-invalid @enderror" 
                                           id="screening_date" name="screening_date" 
                                           value="{{ old('screening_date', $client->screening_date ? $client->screening_date->format('Y-m-d') : '') }}">
                                    @error('screening_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="screening_result" class="form-label">Screening Result</label>
                                    <select class="form-select @error('screening_result') is-invalid @enderror" 
                                            id="screening_result" name="screening_result">
                                        <option value="">Select Result</option>
                                        <option value="Approved" {{ old('screening_result', $client->screening_result) == 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="Conditional Approval" {{ old('screening_result', $client->screening_result) == 'Conditional Approval' ? 'selected' : '' }}>Conditional Approval</option>
                                        <option value="Rejected" {{ old('screening_result', $client->screening_result) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="Pending Review" {{ old('screening_result', $client->screening_result) == 'Pending Review' ? 'selected' : '' }}>Pending Review</option>
                                        <option value="Under Investigation" {{ old('screening_result', $client->screening_result) == 'Under Investigation' ? 'selected' : '' }}>Under Investigation</option>
                                    </select>
                                    @error('screening_result')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4">{{ old('notes', $client->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Client</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    
    // Auto-update risk level based on status changes
    const statusSelect = document.getElementById('status');
    statusSelect.addEventListener('change', function() {
        const status = this.value;
        // You can add logic here to automatically adjust risk level based on status
        // console.log('Status changed to:', status);
    });
});
</script>
@endpush
