@extends('layouts.sidebar')

@section('title', 'Message - DCS-Best')

@section('content')
<style>
    .message-container {
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
    
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-clean {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-clean:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-1px);
        text-decoration: none;
    }
    
    .message-detail {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    
    .message-header {
        border-bottom: 2px solid var(--logo-border-light);
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .message-subject {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--logo-text-dark);
        margin-bottom: 1rem;
    }
    
    .message-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .message-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .message-sender {
        font-weight: 600;
        color: var(--logo-text-dark);
        font-size: 1rem;
    }
    
    .message-recipient {
        color: var(--logo-text-medium);
        font-size: 0.9rem;
    }
    
    .message-time {
        color: var(--logo-text-muted);
        font-size: 0.9rem;
    }
    
    .message-badges {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .badge-priority {
        background: var(--logo-dark-blue-primary);
        color: white;
    }
    
    .badge-important {
        background: var(--logo-warning);
        color: white;
    }
    
    .badge-status {
        background: var(--logo-success);
        color: white;
    }
    
    .message-body {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--logo-text-dark);
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    
    .message-actions {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 2rem;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-reply {
        background: var(--logo-dark-blue-primary);
        color: white;
    }
    
    .btn-reply:hover {
        background: var(--logo-dark-blue-hover);
        color: white;
        text-decoration: none;
    }
    
    .btn-mark-read {
        background: var(--logo-success);
        color: white;
    }
    
    .btn-mark-unread {
        background: var(--logo-warning);
        color: white;
    }
    
    .btn-archive {
        background: var(--logo-text-muted);
        color: white;
    }
    
    .btn-delete {
        background: var(--logo-danger);
        color: white;
    }
    
    .btn-edit {
        background: var(--logo-info);
        color: white;
    }
    
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
    
    .priority-urgent {
        border-left: 4px solid var(--logo-danger);
    }
    
    .priority-high {
        border-left: 4px solid var(--logo-warning);
    }
    
    .priority-normal {
        border-left: 4px solid var(--logo-dark-blue-primary);
    }
    
    .priority-low {
        border-left: 4px solid var(--logo-text-muted);
    }
    
    /* Mobile-First Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem;
        }
        .message-container {
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
        .header-actions {
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .btn-clean {
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            text-align: center;
        }
        .message-card {
            padding: 1rem;
            border-radius: 0;
        }
        .message-meta {
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .message-meta-item {
            font-size: 0.8rem;
        }
        .message-meta-label {
            font-size: 0.75rem;
        }
        .message-content {
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .message-actions {
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 1rem;
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
        .btn-clean {
            padding: 0.625rem 0.75rem;
            font-size: 0.75rem;
        }
        .message-card {
            padding: 0.75rem;
        }
        .message-meta-item {
            font-size: 0.75rem;
        }
        .message-meta-label {
            font-size: 0.7rem;
        }
        .message-content {
            font-size: 0.85rem;
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
        .btn-clean {
            padding: 0.5rem 0.625rem;
            font-size: 0.7rem;
        }
        .message-card {
            padding: 0.5rem;
        }
        .message-meta-item {
            font-size: 0.7rem;
        }
        .message-meta-label {
            font-size: 0.65rem;
        }
        .message-content {
            font-size: 0.8rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
    }
</style>

<div class="message-container">
    <!-- Back Link -->
    <a href="{{ route('messages.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Back to Messages
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Message Details</h1>
                <p class="page-subtitle">View and manage your message</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('messages.create') }}" class="btn-clean">
                    <i class="fas fa-plus me-2"></i>New Message
                </a>
            </div>
        </div>
    </div>

    <!-- Message Detail -->
    <div class="message-detail priority-{{ $message->priority }}">
        <div class="message-header">
            <div class="message-subject">
                {{ $message->subject }}
            </div>
            
            <div class="message-meta">
                <div class="message-info">
                    <div class="message-sender">
                        <i class="fas fa-user me-2"></i>
                        From: {{ $message->sender->name }}
                    </div>
                    <div class="message-recipient">
                        <i class="fas fa-user-friends me-2"></i>
                        To: {{ $message->recipient->name }}
                    </div>
                    <div class="message-time">
                        <i class="fas fa-clock me-2"></i>
                        {{ $message->created_at->format('M j, Y \a\t g:i A') }}
                        ({{ $message->time_ago }})
                    </div>
                </div>
                
                <div class="message-badges">
                    <span class="badge badge-priority">{{ ucfirst($message->priority) }}</span>
                    @if($message->is_important)
                        <span class="badge badge-important">Important</span>
                    @endif
                    <span class="badge badge-status">{{ ucfirst($message->status) }}</span>
                </div>
            </div>
        </div>
        
        <div class="message-body">
            {{ $message->body }}
        </div>
        
        <div class="message-actions">
            @if($message->sender_id === auth()->id())
                <a href="{{ route('messages.edit', $message) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i>
                    Edit Message
                </a>
            @else
                <a href="{{ route('messages.create', ['reply_to' => $message->id]) }}" class="btn-action btn-reply">
                    <i class="fas fa-reply"></i>
                    Reply
                </a>
            @endif
            
            @if($message->isUnread())
                <button class="btn-action btn-mark-read" onclick="markAsRead({{ $message->id }})">
                    <i class="fas fa-check"></i>
                    Mark as Read
                </button>
            @else
                <button class="btn-action btn-mark-unread" onclick="markAsUnread({{ $message->id }})">
                    <i class="fas fa-envelope"></i>
                    Mark as Unread
                </button>
            @endif
            
            <button class="btn-action btn-archive" onclick="archiveMessage({{ $message->id }})">
                <i class="fas fa-archive"></i>
                Archive
            </button>
            
            <form method="POST" action="{{ route('messages.destroy', $message) }}" 
                  style="display: inline;" 
                  onsubmit="return confirm('Are you sure you want to delete this message?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action btn-delete">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-update message count when page loads (since message is auto-marked as read)
document.addEventListener('DOMContentLoaded', function() {
    // Only update if we're not already in a loading state and function exists
    if (typeof loadUnreadMessageCount === 'function' && !window.messageCountUpdating) {
        window.messageCountUpdating = true;
        // Reduced delay since message is already marked as read on server
        setTimeout(() => {
            loadUnreadMessageCount();
            window.messageCountUpdating = false;
        }, 200);
    }
});

function markAsRead(messageId) {
    fetch(`/messages/${messageId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the sidebar count in real-time
            if (typeof loadUnreadMessageCount === 'function') {
                loadUnreadMessageCount();
            }
            
            // Also update count directly if provided
            if (data.unread_count !== undefined && typeof updateMessageCountDisplay === 'function') {
                updateMessageCountDisplay(data.unread_count);
            }
            
            // Update the page without full reload - just refresh the message status
            updateMessageStatus(messageId, 'read');
            
            // Show success message
            showAlert('success', data.message || 'Message marked as read');
        } else {
            showAlert('error', data.error || 'Failed to mark message as read');
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAsUnread(messageId) {
    fetch(`/messages/${messageId}/mark-unread`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the sidebar count in real-time
            if (typeof loadUnreadMessageCount === 'function') {
                loadUnreadMessageCount();
            }
            
            // Also update count directly if provided
            if (data.unread_count !== undefined && typeof updateMessageCountDisplay === 'function') {
                updateMessageCountDisplay(data.unread_count);
            }
            
            // Update the page without full reload - just refresh the message status
            updateMessageStatus(messageId, 'unread');
            
            // Show success message
            showAlert('success', data.message || 'Message marked as unread');
        } else {
            showAlert('error', data.error || 'Failed to mark message as unread');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to update message status without full page reload
function updateMessageStatus(messageId, status) {
    const markReadBtn = document.querySelector('.btn-mark-read');
    const markUnreadBtn = document.querySelector('.btn-mark-unread');
    
    if (status === 'read' && markReadBtn) {
        markReadBtn.style.display = 'none';
        if (markUnreadBtn) markUnreadBtn.style.display = 'inline-block';
        
        // Update any status indicators
        const statusElements = document.querySelectorAll('.message-status');
        statusElements.forEach(el => {
            el.textContent = 'Read';
            el.className = 'message-status read';
        });
    } else if (status === 'unread' && markUnreadBtn) {
        markUnreadBtn.style.display = 'none';
        if (markReadBtn) markReadBtn.style.display = 'inline-block';
        
        // Update any status indicators
        const statusElements = document.querySelectorAll('.message-status');
        statusElements.forEach(el => {
            el.textContent = 'Unread';
            el.className = 'message-status unread';
        });
    }
}

function archiveMessage(messageId) {
    fetch(`/messages/${messageId}/archive`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
