@extends('layouts.sidebar')

@section('title', 'Edit Risk Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('risk-categories.index') }}">Risk Categories</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Risk Category: {{ $category->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Category Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('risk-categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required 
                                   placeholder="Enter category name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Choose a descriptive name for the risk category</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Describe what this category represents">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Provide a detailed description of the category</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Category Color *</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                               id="color" name="color" value="{{ old('color', $category->color) }}" required>
                                        <input type="text" class="form-control" id="color_hex" 
                                               value="{{ old('color', $category->color) }}" placeholder="#00072D">
                                    </div>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Choose a color to represent this category</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Category
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Inactive categories won't be available for new risks</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('risk-categories.show', $category) }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Color Preview</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="color-preview-large mb-3" id="colorPreview">
                            <span class="preview-text">{{ $category->name }}</span>
                        </div>
                        <p class="text-muted">This is how your category will appear</p>
                    </div>
                    
                    <hr>
                    
                    <h6 class="mb-3">Suggested Colors</h6>
                    <div class="row">
                        <div class="col-4 mb-2">
                            <div class="suggested-color" data-color="#00072D" style="background-color: #00072D;"></div>
                        </div>
                        <div class="col-4 mb-2">
                            <div class="suggested-color" data-color="#28a745" style="background-color: #28a745;"></div>
                        </div>
                        <div class="col-4 mb-2">
                            <div class="suggested-color" data-color="#ffc107" style="background-color: #ffc107;"></div>
                        </div>
                        <div class="col-4 mb-2">
                            <div class="suggested-color" data-color="#dc3545" style="background-color: #dc3545;"></div>
                        </div>
                        <div class="col-4 mb-2">
                            <div class="suggested-color" data-color="#6f42c1" style="background-color: #6f42c1;"></div>
                        </div>
                        <div class="col-4 mb-2">
                            <div class="suggested-color" data-color="#fd7e14" style="background-color: #fd7e14;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="header-title">Category Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Risks</label>
                        <p class="mb-0">{{ $riskStats['risk_count'] ?? 0 }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Predefined Risks</label>
                        <p class="mb-0">{{ $riskStats['predefined_count'] ?? 0 }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Risk Points</label>
                        <p class="mb-0 text-primary fw-bold">{{ $riskStats['total_points'] ?? 0 }} pts</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Risk Level Distribution</label>
                        <div class="d-flex gap-2">
                            <span class="badge bg-danger">{{ $riskStats['risk_levels']['High'] ?? 0 }} High</span>
                            <span class="badge bg-warning">{{ $riskStats['risk_levels']['Medium'] ?? 0 }} Medium</span>
                            <span class="badge bg-success">{{ $riskStats['risk_levels']['Low'] ?? 0 }} Low</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Created</label>
                        <p class="mb-0">{{ $category->created_at ? $category->created_at->format('M d, Y H:i') : 'Not available' }}</p>
                    </div>
                    
                    @if($category->updated_at && $category->updated_at != $category->created_at)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Last Updated</label>
                        <p class="mb-0">{{ $category->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('risk-categories.show', $category) }}" class="btn btn-outline-primary btn-sm">
                            <i class="mdi mdi-eye me-1"></i>View Category
                        </a>
                        @if($category->risks->count() > 0)
                        <a href="{{ route('risks.index', ['category' => $category->id]) }}" class="btn btn-outline-info btn-sm">
                            <i class="mdi mdi-list me-1"></i>View Risks
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="header-title">Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="mdi mdi-check-circle text-success me-2"></i>
                            Use descriptive names
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-check-circle text-success me-2"></i>
                            Choose contrasting colors
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-check-circle text-success me-2"></i>
                            Keep descriptions concise
                        </li>
                        <li class="mb-0">
                            <i class="mdi mdi-check-circle text-success me-2"></i>
                            Consider risk level associations
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Form Controls */
.form-control-color {
    width: 100%;
    height: 38px;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}

.form-control:focus,
.form-control-color:focus {
    border-color: var(--logo-dark-blue-primary);
    box-shadow: 0 0 0 0.2rem rgba(0, 7, 45, 0.25);
}

/* Color Preview */
.color-preview-large {
    width: 120px;
    height: 60px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border: 2px solid #e3e6f0;
}

/* Suggested Colors */
.suggested-color {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.suggested-color:hover {
    border-color: var(--logo-dark-blue-primary);
    transform: scale(1.05);
    box-shadow: 0 4px 8px var(--logo-shadow-medium);
}

.suggested-color.active {
    border-color: var(--logo-dark-blue-primary);
    transform: scale(1.05);
    box-shadow: 0 4px 8px var(--logo-shadow-dark);
}

/* Cards */
.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border-radius: 0.35rem;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    font-weight: 600;
}

/* Buttons */
.btn {
    border-radius: 0.35rem;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
}

.btn-primary {
    background-color: var(--logo-dark-blue-primary);
    border-color: var(--logo-dark-blue-primary);
}

.btn-primary:hover {
    background-color: var(--logo-dark-blue-hover);
    border-color: var(--logo-dark-blue-hover);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px var(--logo-shadow-medium);
}

.btn-outline-primary {
    color: var(--logo-dark-blue-primary);
    border-color: var(--logo-dark-blue-primary);
}

.btn-outline-primary:hover {
    background-color: var(--logo-dark-blue-primary);
    border-color: var(--logo-dark-blue-primary);
    transform: translateY(-1px);
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: #5a5c69;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 0.75rem;
    transition: all 0.15s ease-in-out;
}

.form-control:focus {
    border-color: var(--logo-dark-blue-primary);
    box-shadow: 0 0 0 0.2rem rgba(0, 7, 45, 0.25);
}

/* Switch */
.form-check-input:checked {
    background-color: var(--logo-dark-blue-primary);
    border-color: var(--logo-dark-blue-primary);
}

/* Statistics */
.form-label.fw-bold {
    color: #5a5c69;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

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
        margin: 0 -0.5rem;
        border-radius: 0;
    }
    .card-header {
        padding: 0.75rem 1rem;
    }
    .header-title {
        font-size: 1.1rem;
    }
    .card-body {
        padding: 1rem;
    }
    .row {
        margin: 0;
    }
    .col-lg-8, .col-lg-4 {
        padding: 0.25rem;
        margin-bottom: 1rem;
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
    .alert {
        font-size: 0.85rem;
        padding: 0.75rem 1rem;
    }
    .color-preview-large {
        width: 100px;
        height: 50px;
        font-size: 1rem;
    }
    .suggested-color {
        width: 35px;
        height: 35px;
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
    .card {
        margin: 0 -0.25rem;
    }
    .card-header {
        padding: 0.5rem 0.75rem;
    }
    .header-title {
        font-size: 1rem;
    }
    .card-body {
        padding: 0.75rem;
    }
    .col-lg-8, .col-lg-4 {
        padding: 0.125rem;
        margin-bottom: 0.75rem;
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
    .alert {
        font-size: 0.8rem;
        padding: 0.625rem 0.75rem;
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
    .header-title {
        font-size: 0.9rem;
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
    .alert {
        font-size: 0.75rem;
        padding: 0.5rem 0.625rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorHexInput = document.getElementById('color_hex');
    const colorPreview = document.getElementById('colorPreview');
    const suggestedColors = document.querySelectorAll('.suggested-color');
    
    // Update preview when color changes
    function updateColorPreview() {
        const color = colorInput.value;
        colorPreview.style.backgroundColor = color;
        colorHexInput.value = color;
        
        // Update text color for better contrast
        const rgb = hexToRgb(color);
        const brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
        colorPreview.style.color = brightness > 128 ? '#000' : '#fff';
    }
    
    // Convert hex to RGB
    function hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
    
    // Event listeners
    colorInput.addEventListener('input', updateColorPreview);
    colorHexInput.addEventListener('input', function() {
        const hex = this.value;
        if (/^#[0-9A-F]{6}$/i.test(hex)) {
            colorInput.value = hex;
            updateColorPreview();
        }
    });
    
    // Suggested color clicks
    suggestedColors.forEach(colorDiv => {
        colorDiv.addEventListener('click', function() {
            const color = this.getAttribute('data-color');
            colorInput.value = color;
            updateColorPreview();
            
            // Update active state
            suggestedColors.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Initialize preview
    updateColorPreview();
    
    // Add real-time validation
    addRealTimeValidation();
    
    // Enhanced form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous validation errors
        clearValidationErrors();
        
        // Validate all fields
        const validationResult = validateForm();
        
        if (validationResult.isValid) {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Updating...';
            submitBtn.disabled = true;
            
            // Submit the form
            form.submit();
        } else {
            // Show validation errors
            showValidationErrors(validationResult.errors);
            scrollToFirstError(validationResult.firstErrorField);
        }
    });
    
    // Clear validation errors
    function clearValidationErrors() {
        const errorElements = document.querySelectorAll('.is-invalid');
        errorElements.forEach(element => {
            element.classList.remove('is-invalid');
        });
        
        const errorMessages = document.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(message => {
            message.remove();
        });
    }
    
    // Validate form
    function validateForm() {
        const errors = [];
        let firstErrorField = null;
        
        // Validate category name
        const nameInput = document.getElementById('name');
        if (!nameInput.value.trim()) {
            errors.push('Category name is required');
            nameInput.classList.add('is-invalid');
            if (!firstErrorField) firstErrorField = nameInput;
        } else if (nameInput.value.trim().length < 2) {
            errors.push('Category name must be at least 2 characters long');
            nameInput.classList.add('is-invalid');
            if (!firstErrorField) firstErrorField = nameInput;
        }
        
        // Validate color
        const colorInput = document.getElementById('color');
        if (!colorInput.value) {
            errors.push('Category color is required');
            colorInput.classList.add('is-invalid');
            if (!firstErrorField) firstErrorField = colorInput;
        }
        
        // Validate description length
        const descriptionInput = document.getElementById('description');
        if (descriptionInput.value && descriptionInput.value.length > 500) {
            errors.push('Description must be less than 500 characters');
            descriptionInput.classList.add('is-invalid');
            if (!firstErrorField) firstErrorField = descriptionInput;
        }
        
        return {
            isValid: errors.length === 0,
            errors: errors,
            firstErrorField: firstErrorField
        };
    }
    
    // Show validation errors
    function showValidationErrors(errors) {
        // Create error summary
        const errorSummary = document.createElement('div');
        errorSummary.className = 'alert alert-danger';
        errorSummary.innerHTML = `
            <h6><i class="mdi mdi-alert-circle me-2"></i>Please fix the following errors:</h6>
            <ul class="mb-0">
                ${errors.map(error => `<li>${error}</li>`).join('')}
            </ul>
        `;
        
        // Insert error summary at the top of the form
        const formBody = document.querySelector('.card-body');
        formBody.insertBefore(errorSummary, formBody.firstChild);
        
        // Auto-remove after 10 seconds
        setTimeout(() => {
            if (errorSummary.parentNode) {
                errorSummary.remove();
            }
        }, 10000);
    }
    
    // Scroll to first error field
    function scrollToFirstError(field) {
        if (field) {
            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            field.focus();
        }
    }
    
    // Add real-time validation
    function addRealTimeValidation() {
        const nameInput = document.getElementById('name');
        const colorInput = document.getElementById('color');
        const descriptionInput = document.getElementById('description');
        
        // Name validation
        nameInput.addEventListener('input', function() {
            const value = this.value.trim();
            if (value.length > 0 && value.length < 2) {
                showFieldError(this, 'Category name must be at least 2 characters long');
            } else {
                clearFieldError(this);
            }
        });
        
        // Color validation
        colorInput.addEventListener('change', function() {
            if (!this.value) {
                showFieldError(this, 'Category color is required');
            } else {
                clearFieldError(this);
            }
        });
        
        // Description validation
        descriptionInput.addEventListener('input', function() {
            const value = this.value;
            if (value.length > 500) {
                showFieldError(this, `Description is ${value.length} characters. Maximum allowed is 500.`);
            } else {
                clearFieldError(this);
            }
        });
    }
    
    // Show field-specific error
    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    // Clear field-specific error
    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    // Check if category has associated risks
    const riskCount = {{ $riskStats['risk_count'] ?? 0 }};
    if (riskCount > 0) {
        const statusSwitch = document.getElementById('is_active');
        statusSwitch.addEventListener('change', function() {
            if (!this.checked) {
                if (confirm('This category has ' + riskCount + ' associated risks. Deactivating it may affect risk management. Are you sure?')) {
                    // Allow deactivation
                } else {
                    this.checked = true;
                }
            }
        });
    }
});
</script>
@endpush
