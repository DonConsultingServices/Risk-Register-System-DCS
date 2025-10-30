@extends('layouts.sidebar')

@section('title', 'New Message - DCS-Best')
@section('page-title', 'New Message')

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
    
    .user-search-container {
        position: relative;
    }
    
    .user-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid var(--logo-border-light);
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }
    
    .user-search-result {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid var(--logo-border-light);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: background-color 0.2s ease;
    }
    
    .user-search-result:hover {
        background-color: rgba(0, 7, 45, 0.05);
    }
    
    .user-search-result:last-child {
        border-bottom: none;
    }
    
    .user-avatar-small {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .user-result-info {
        flex: 1;
    }
    
    .user-result-name {
        font-weight: 500;
        color: var(--logo-text-dark);
        margin-bottom: 0.25rem;
    }
    
    .user-result-email {
        font-size: 0.8rem;
        color: var(--logo-text-muted);
    }
    
    .user-result-role {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        background: var(--logo-border-light);
        color: var(--logo-text-dark);
    }
    
    .selected-user {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(0, 7, 45, 0.05);
        border: 2px solid var(--logo-dark-blue-primary);
        border-radius: 8px;
    }
    
    .selected-user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-details {
        flex: 1;
    }
    
    .user-name {
        font-weight: 600;
        color: var(--logo-text-dark);
        margin-bottom: 0.25rem;
    }
    
    .user-email {
        font-size: 0.875rem;
        color: var(--logo-text-muted);
    }
    
    .btn-remove-user {
        background: none;
        border: none;
        color: var(--logo-text-muted);
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .btn-remove-user:hover {
        background: var(--logo-danger);
        color: white;
    }

    /* Recipient selection toggle styles */
    .recipient-selection-toggle {
        margin-bottom: 1rem;
    }

    .recipient-selection-toggle .btn-group .btn {
        border-radius: 0.375rem;
        margin-right: 0.25rem;
    }

    .recipient-selection-toggle .btn-group .btn:last-child {
        margin-right: 0;
    }

    .recipient-selection-toggle .btn.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .recipient-selection-toggle .btn:not(.active) {
        background-color: white;
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .recipient-selection-toggle .btn:not(.active):hover {
        background-color: #0d6efd;
        color: white;
    }

    /* User dropdown styles */
    .user-dropdown-container select {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        padding: 0.5rem 0.75rem;
    }

    .user-dropdown-container select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Broadcast container styles */
    .broadcast-container .alert {
        border-radius: 0.5rem;
        border: 1px solid #b3d7ff;
        background-color: #e7f3ff;
    }

    .broadcast-container .alert h6 {
        color: #0c5460;
        font-weight: 600;
    }

    .broadcast-container .alert p {
        color: #0c5460;
    }

    .broadcast-container .alert small {
        color: #6c757d;
    }

    /* Broadcast button styles */
    .recipient-selection-toggle .btn-outline-success {
        border-color: #198754;
        color: #198754;
    }

    .recipient-selection-toggle .btn-outline-success:hover {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }

    .recipient-selection-toggle .btn-outline-success.active {
        background-color: #198754;
        border-color: #198754;
        color: white;
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
        .recipient-tags {
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        .recipient-tag {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .recipient-tag .btn-close {
            font-size: 0.7rem;
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
        .recipient-tag {
            font-size: 0.75rem;
            padding: 0.2rem 0.4rem;
        }
        .recipient-tag .btn-close {
            font-size: 0.65rem;
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
        .recipient-tag {
            font-size: 0.7rem;
            padding: 0.15rem 0.3rem;
        }
        .recipient-tag .btn-close {
            font-size: 0.6rem;
        }
    }
</style>

<div class="message-form-container">
    <!-- Back Link -->
    <a href="{{ route('messages.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Back to Messages
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">New Message</h1>
                <p class="page-subtitle">Send a message to your team members</p>
            </div>
        </div>
    </div>

    <!-- Message Form -->
    <div class="message-form">
        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="recipient_search" class="form-label">
                    <i class="fas fa-user-friends me-2"></i>Recipient
                </label>
                
                <!-- Toggle between search, dropdown, and broadcast -->
                <div class="recipient-selection-toggle mb-3">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm active" id="search-toggle" onclick="toggleRecipientMode('search')">
                            <i class="fas fa-search me-1"></i>Search Users
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="dropdown-toggle" onclick="toggleRecipientMode('dropdown')">
                            <i class="fas fa-list me-1"></i>Select from List
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" id="broadcast-toggle" onclick="toggleRecipientMode('broadcast')">
                            <i class="fas fa-bullhorn me-1"></i>Broadcast to All
                        </button>
                    </div>
                </div>

                <!-- Search Mode -->
                <div class="user-search-container" id="search-container">
                    <input type="text" 
                           id="recipient_search" 
                           class="form-control @error('recipient_id') is-invalid @enderror" 
                           placeholder="Type to search for a user..."
                           autocomplete="off">
                    <input type="hidden" name="recipient_id" id="recipient_id" value="{{ old('recipient_id') }}" required>
                    <div class="user-search-results" id="user_search_results"></div>
                </div>

                <!-- Dropdown Mode -->
                <div class="user-dropdown-container" id="dropdown-container" style="display: none;">
                    <select name="recipient_id_dropdown" id="recipient_id_dropdown" class="form-control form-select @error('recipient_id') is-invalid @enderror">
                        <option value="">Select a user from the list...</option>
                        @foreach(\App\Models\User::where('is_active', true)->where('id', '!=', auth()->id())->orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}" {{ old('recipient_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }}) - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Broadcast Mode -->
                <div class="broadcast-container" id="broadcast-container" style="display: none;">
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-bullhorn me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-1">Broadcast Message</h6>
                                <p class="mb-0">This message will be sent to all active users in the system.</p>
                                <small class="text-muted">
                                    <strong>Recipients:</strong> 
                                    <span id="broadcast-count">{{ \App\Models\User::where('is_active', true)->where('id', '!=', auth()->id())->count() }}</span> users
                                </small>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="is_broadcast" id="is_broadcast" value="0">
                </div>

                <!-- Selected User Display -->
                <div class="selected-user" id="selected_user" style="display: none;">
                    <div class="selected-user-info">
                        <div class="user-avatar-small">
                            <span id="selected_user_initial"></span>
                        </div>
                        <div class="user-details">
                            <div class="user-name" id="selected_user_name"></div>
                            <div class="user-email" id="selected_user_email"></div>
                        </div>
                        <button type="button" class="btn-remove-user" onclick="clearSelectedUser()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
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
                       value="{{ old('subject') }}" 
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
                    <label class="priority-option {{ old('priority', 'normal') === 'low' ? 'selected' : '' }}">
                        <input type="radio" name="priority" value="low" {{ old('priority', 'normal') === 'low' ? 'checked' : '' }}>
                        <div>
                            <div class="priority-label">Low</div>
                            <div class="priority-description">Not urgent</div>
                        </div>
                    </label>
                    
                    <label class="priority-option {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}">
                        <input type="radio" name="priority" value="normal" {{ old('priority', 'normal') === 'normal' ? 'checked' : '' }}>
                        <div>
                            <div class="priority-label">Normal</div>
                            <div class="priority-description">Standard priority</div>
                        </div>
                    </label>
                    
                    <label class="priority-option {{ old('priority', 'normal') === 'high' ? 'selected' : '' }}">
                        <input type="radio" name="priority" value="high" {{ old('priority', 'normal') === 'high' ? 'checked' : '' }}>
                        <div>
                            <div class="priority-label">High</div>
                            <div class="priority-description">Important</div>
                        </div>
                    </label>
                    
                    <label class="priority-option {{ old('priority', 'normal') === 'urgent' ? 'selected' : '' }}">
                        <input type="radio" name="priority" value="urgent" {{ old('priority', 'normal') === 'urgent' ? 'checked' : '' }}>
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
                          required>{{ old('body') }}</textarea>
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
                       {{ old('is_important') ? 'checked' : '' }}>
                <label for="is_important" class="form-check-label">
                    <i class="fas fa-star me-2"></i>Mark as Important
                </label>
            </div>
            
            <div class="btn-group">
                <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Send Message
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

// User selection functionality
let searchTimeout;
let currentMode = 'search'; // 'search', 'dropdown', or 'broadcast'
const searchInput = document.getElementById('recipient_search');
const searchResults = document.getElementById('user_search_results');
const recipientIdInput = document.getElementById('recipient_id');
const selectedUserDiv = document.getElementById('selected_user');
const dropdownSelect = document.getElementById('recipient_id_dropdown');
const isBroadcastInput = document.getElementById('is_broadcast');

// Toggle between search, dropdown, and broadcast modes
function toggleRecipientMode(mode) {
    currentMode = mode;
    
    // Update button states
    document.getElementById('search-toggle').classList.toggle('active', mode === 'search');
    document.getElementById('dropdown-toggle').classList.toggle('active', mode === 'dropdown');
    document.getElementById('broadcast-toggle').classList.toggle('active', mode === 'broadcast');
    
    // Show/hide containers
    document.getElementById('search-container').style.display = mode === 'search' ? 'block' : 'none';
    document.getElementById('dropdown-container').style.display = mode === 'dropdown' ? 'block' : 'none';
    document.getElementById('broadcast-container').style.display = mode === 'broadcast' ? 'block' : 'none';
    
    // Update form validation
    if (mode === 'broadcast') {
        recipientIdInput.removeAttribute('required');
        isBroadcastInput.value = '1';
    } else {
        recipientIdInput.setAttribute('required', 'required');
        isBroadcastInput.value = '0';
    }
    
    // Clear any selected user when switching modes
    clearSelectedUser();
}

// Search functionality
searchInput.addEventListener('input', function() {
    const query = this.value.trim();
    
    clearTimeout(searchTimeout);
    
    if (query.length < 2) {
        searchResults.style.display = 'none';
        return;
    }
    
    searchTimeout = setTimeout(() => {
        searchUsers(query);
    }, 300);
});

searchInput.addEventListener('focus', function() {
    if (this.value.trim().length >= 2) {
        searchResults.style.display = 'block';
    }
});

// Dropdown functionality
dropdownSelect.addEventListener('change', function() {
    if (this.value) {
        const selectedOption = this.options[this.selectedIndex];
        const userName = selectedOption.text.split(' (')[0];
        const userEmail = selectedOption.text.split(' - ')[1];
        
        selectUser(this.value, userName, userEmail, '');
    }
});

// Hide search results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.user-search-container')) {
        searchResults.style.display = 'none';
    }
});

function searchUsers(query) {
    fetch(`{{ url('/users/search') }}?q=${encodeURIComponent(query)}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(users => {
        displaySearchResults(users);
    })
    .catch(error => {
        console.error('Error searching users:', error);
        searchResults.innerHTML = '<div class="user-search-result"><div class="text-danger">Error loading users. Please try again.</div></div>';
        searchResults.style.display = 'block';
    });
}

function displaySearchResults(users) {
    if (users.length === 0) {
        searchResults.innerHTML = '<div class="user-search-result"><div class="text-muted">No users found</div></div>';
    } else {
        searchResults.innerHTML = users.map(user => `
            <div class="user-search-result" onclick="selectUser(${user.id}, '${user.name}', '${user.email}', '${user.role}')">
                <div class="user-avatar-small">${user.name.charAt(0).toUpperCase()}</div>
                <div class="user-result-info">
                    <div class="user-result-name">${user.name}</div>
                    <div class="user-result-email">${user.email}</div>
                </div>
                <div class="user-result-role">${user.role_display}</div>
            </div>
        `).join('');
    }
    
    searchResults.style.display = 'block';
}

function selectUser(userId, userName, userEmail, userRole) {
    // Set the hidden input value
    recipientIdInput.value = userId;
    
    // Update the selected user display
    document.getElementById('selected_user_initial').textContent = userName.charAt(0).toUpperCase();
    document.getElementById('selected_user_name').textContent = userName;
    document.getElementById('selected_user_email').textContent = userEmail;
    
    // Show selected user div and hide input methods
    selectedUserDiv.style.display = 'block';
    document.getElementById('search-container').style.display = 'none';
    document.getElementById('dropdown-container').style.display = 'none';
    searchResults.style.display = 'none';
}

function clearSelectedUser() {
    // Clear the hidden input value
    recipientIdInput.value = '';
    
    // Clear dropdown selection
    dropdownSelect.value = '';
    
    // Hide selected user div and show current mode input
    selectedUserDiv.style.display = 'none';
    document.getElementById('search-container').style.display = currentMode === 'search' ? 'block' : 'none';
    document.getElementById('dropdown-container').style.display = currentMode === 'dropdown' ? 'block' : 'none';
    document.getElementById('broadcast-container').style.display = currentMode === 'broadcast' ? 'block' : 'none';
    searchInput.value = '';
}

// Initialize with old value if exists
@if(old('recipient_id'))
    // If there's an old recipient_id, we need to fetch the user details
        fetch(`{{ url('/users') }}/{{ old('recipient_id') }}/details`)
        .then(response => response.json())
        .then(user => {
            selectUser(user.id, user.name, user.email, user.role);
        })
        .catch(error => {
            console.error('Error loading user details:', error);
        });
@endif
</script>
@endsection
