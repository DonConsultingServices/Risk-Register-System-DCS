@extends('layouts.sidebar')

@section('title', 'Create Risk Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('risk-categories.index') }}">Risk Categories</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
                <h4 class="page-title">Create New Risk Category</h4>
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
                    <form action="{{ route('risk-categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required 
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
                                      placeholder="Describe what this category represents">{{ old('description') }}</textarea>
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
                                               id="color" name="color" value="{{ old('color', '#007bff') }}" required>
                                        <input type="text" class="form-control" id="color_hex" 
                                               value="{{ old('color', '#007bff') }}" placeholder="#007bff">
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
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Category
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Inactive categories won't be available for new risks</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('risk-categories.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Category</button>
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
                            <span class="preview-text">Category</span>
                        </div>
                        <p class="text-muted">This is how your category will appear</p>
                    </div>
                    
                    <hr>
                    
                    <h6 class="mb-3">Suggested Colors</h6>
                    <div class="row">
                        <div class="col-4 mb-2">
                            <div class="suggested-color" data-color="#007bff" style="background-color: #007bff;"></div>
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

@push('styles')
<style>
.form-control-color {
    width: 100%;
    height: 38px;
}

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
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.suggested-color {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.suggested-color:hover {
    border-color: #495057;
    transform: scale(1.1);
}

.suggested-color.active {
    border-color: #495057;
    transform: scale(1.1);
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
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const nameInput = document.getElementById('name');
        if (!nameInput.value.trim()) {
            e.preventDefault();
            nameInput.classList.add('is-invalid');
            alert('Please enter a category name.');
        }
    });
});
</script>
@endpush
