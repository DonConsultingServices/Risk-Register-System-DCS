@extends('layouts.sidebar')

@section('title', 'Edit Message - DCS-Best')

@section('content')
<style>
    .message-form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .page-header {
        background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 7, 45, 0.2);
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 600;
        margin: 0;
        color: white;
    }
    
    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        margin: 0.25rem 0 0 0;
        font-size: 0.95rem;
    }
    
    .message-form {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--logo-text-dark);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        border: 2px solid var(--logo-border-light);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--logo-dark-blue-primary);
        box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.1);
    }
    
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem;
    }
    
    .form-textarea {
        min-height: 200px;
        resize: vertical;
    }
    
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin: 0;
    }
    
    .form-check-input:checked {
        background-color: var(--logo-dark-blue-primary);
        border-color: var(--logo-dark-blue-primary);
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.1);
    }
    
    .form-check-label {
        font-weight: 500;
        color: var(--logo-text-dark);
        margin: 0;
    }
    
    .btn-group {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: var(--logo-dark-blue-primary);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--logo-dark-blue-hover);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 7, 45, 0.3);
    }
    
    .btn-secondary {
        background: var(--logo-text-muted);
        color: white;
    }
    
    .btn-secondary:hover {
        background: var(--logo-text-medium);
        color: white;
        text-decoration: none;
    }
    
    .invalid-feedback {
        color: var(--logo-danger);
        font-size: 0.8rem;
        margin-top: 0.25rem;
        display: block;
    }
    
    .is-invalid {
        border-color: var(--logo-danger);
    }
    
    .back-link {
        color: var(--logo-dark-blue-primary);
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .back-link:hover {
        color: var(--logo-dark-blue-hover);
        text-decoration: none;
    }
    
    .priority-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }
    
    .priority-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem;
        border: 2px solid var(--logo-border-light);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .priority-option:hover {
        border-color: var(--logo-dark-blue-primary);
        background: rgba(0, 7, 45, 0.05);
    }
    
    .priority-option.selected {
        border-color: var(--logo-dark-blue-primary);
        background: rgba(0, 7, 45, 0.1);
    }
    
    .priority-option input[type="radio"] {
        margin: 0;
    }
    
    .priority-option .priority-label {
        font-weight: 500;
        color: var(--logo-text-dark);
    }
    
    .priority-option .priority-description {
        font-size: 0.8rem;
        color: var(--logo-text-muted);
    }
    
    .edit-warning {
        background: rgba(202, 138, 4, 0.1);
        border: 1px solid var(--logo-warning);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        color: var(--logo-warning);
        font-size: 0.9rem;
    }
    
    /* Mobile-First Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem;
        }
        .message-form-container {
            max-width: 100%;
            margin: 0;
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
        .message-form {
            padding: 1rem;
            border-radius: 0;
        }
        .form-group {
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
        .message-form {
            padding: 0.75rem;
        }
        .form-group {
            margin-bottom: 0.75rem;
        }
        .form-label {
            font-size: 0.85rem;
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
        .message-form {
            padding: 0.5rem;
        }
        .form-label {
            font-size: 0.8rem;
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
    }
</style>

<div class="message-form-container">
    <!-- Back Link -->
    <a href="{{ route('messages.show', $message) }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Back to Message
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Edit Message</h1>
                <p class="page-subtitle">Modify your message details</p>
            </div>
        </div>
    </div>

    <!-- Edit Warning -->
    <div class="edit-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Note:</strong> Messages can only be edited within 24 hours of sending. 
        The recipient will be notified of any changes.
    </div>

    <!-- Message Form -->
    <div class="message-form">
        <form method="POST" action="{{ route('messages.update', $message) }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="recipient_id" class="form-label">
                    <i class="fas fa-user-friends me-2"></i>Recipient
                </label>
                <select name="recipient_id" id="recipient_id" class="form-control form-select @error('recipient_id') is-invalid @enderror" required>
                    <option value="">Select a recipient...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('recipient_id', $message->recipient_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ ucfirst($user->role) }}) - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                @error('recipient_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="subject" class="form-label">
                    <i class="fas fa-tag me-2"></i>Subject
                </label>
                <input type="text" 
                       name="subject" 
                       id="subject" 
                       class="form-control @error('subject') is-invalid @enderror" 
                       value="{{ old('subject', $message->subject) }}" 
                       placeholder="Enter message subject..."
                       required>
                @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-exclamation-circle me-2"></i>Priority
                </label>
                <div class="priority-options">
                    <label class="priority-option {{ old('priority', $message->priority) === 'low' ? 'selected' : '' }}">
                        <input type="radio" name="priority" value="low" {{ old('priority', $message->priority) === 'low' ? 'checked' : '' }}>
                        <div>
                            <div class="priority-label">Low</div>
                            <div class="priority-description">Not urgent</div>
                        </div>
                    </label>
                    
                    <label class="priority-option {{ old('priority', $message->priority) === 'normal' ? 'selected' : '' }}">
                        <input type="radio" name="priority" value="normal" {{ old('priority', $message->priority) === 'normal' ? 'checked' : '' }}>
                        <div>
                            <div class="priority-label">Normal</div>
                            <div class="priority-description">Standard priority</div>
                        </div>
                    </label>
                    
                    <label class="priority-option {{ old('priority', $message->priority) === 'high' ? 'selected' : '' }}">
                        <input type="radio" name="priority" value="high" {{ old('priority', $message->priority) === 'high' ? 'checked' : '' }}>
                        <div>
                            <div class="priority-label">High</div>
                            <div class="priority-description">Important</div>
                        </div>
                    </label>
                    
                    <label class="priority-option {{ old('priority', $message->priority) === 'urgent' ? 'selected' : '' }}">
                        <input type="radio" name="priority" value="urgent" {{ old('priority', $message->priority) === 'urgent' ? 'checked' : '' }}>
                        <div>
                            <div class="priority-label">Urgent</div>
                            <div class="priority-description">Requires immediate attention</div>
                        </div>
                    </label>
                </div>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="body" class="form-label">
                    <i class="fas fa-comment me-2"></i>Message
                </label>
                <textarea name="body" 
                          id="body" 
                          class="form-control form-textarea @error('body') is-invalid @enderror" 
                          placeholder="Type your message here..."
                          required>{{ old('body', $message->body) }}</textarea>
                @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-check">
                <input type="checkbox" 
                       name="is_important" 
                       id="is_important" 
                       class="form-check-input" 
                       value="1" 
                       {{ old('is_important', $message->is_important) ? 'checked' : '' }}>
                <label for="is_important" class="form-check-label">
                    <i class="fas fa-star me-2"></i>Mark as Important
                </label>
            </div>
            
            <div class="btn-group">
                <a href="{{ route('messages.show', $message) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Message
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Priority selection handling
document.querySelectorAll('.priority-option').forEach(option => {
    option.addEventListener('click', function() {
        // Remove selected class from all options
        document.querySelectorAll('.priority-option').forEach(opt => opt.classList.remove('selected'));
        
        // Add selected class to clicked option
        this.classList.add('selected');
        
        // Check the radio button
        this.querySelector('input[type="radio"]').checked = true;
    });
});

// Auto-resize textarea
document.getElementById('body').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});
</script>
@endsection
