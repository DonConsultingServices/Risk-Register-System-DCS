

<?php $__env->startSection('title', 'Messages - DCS-Best'); ?>
<?php $__env->startSection('page-title', 'Messages'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .messages-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Mobile-First Messages Optimizations */
    @media (max-width: 768px) {
        .messages-container {
            padding: 0;
        }
        
        .page-header {
            margin: 0 -1rem 1.5rem -1rem;
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
            font-size: 0.9rem;
            text-align: center;
        }
        
        /* Mobile Filter Tabs */
        .filter-tabs {
            display: flex;
            overflow-x: auto;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding: 0.5rem 0;
            -webkit-overflow-scrolling: touch;
        }
        
        .filter-tab {
            flex-shrink: 0;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        
        /* Mobile Message Cards */
        .message-card {
            margin-bottom: 0.75rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }
        
        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .message-header {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .message-sender {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--logo-dark-blue-primary);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .online-status {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        
        .status-text {
            font-size: 0.7rem;
            color: #6c757d;
        }
        
        .message-time {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .message-subject {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        
        .message-preview {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.4;
            margin-bottom: 0.75rem;
        }
        
        .message-actions {
            padding: 0.75rem 1rem;
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }
        
        .btn-action {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 6px;
        }
        
        /* Mobile Search */
        .search-container {
            margin-bottom: 1rem;
        }
        
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px; /* Prevents zoom on iOS */
        }
        
        .search-input:focus {
            border-color: var(--logo-dark-blue-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 7, 45, 0.25);
        }
        
        /* Mobile Pagination */
        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
        }
        
        .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }
        
        /* Mobile Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        .empty-state h4 {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            font-size: 0.9rem;
            color: #999;
            margin-bottom: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-header {
            margin: 0 -0.75rem 1rem -0.75rem;
            padding: 0.75rem;
        }
        
        .page-title {
            font-size: 1.1rem;
        }
        
        .page-subtitle {
            font-size: 0.8rem;
        }
        
        .filter-tab {
            padding: 0.625rem 0.875rem;
            font-size: 0.8rem;
        }
        
        .message-header {
            padding: 0.75rem;
        }
        
        .message-sender {
            font-size: 0.85rem;
        }
        
        .message-time {
            font-size: 0.7rem;
        }
        
        .message-subject {
            font-size: 0.9rem;
        }
        
        .message-preview {
            font-size: 0.8rem;
        }
        
        .message-actions {
            padding: 0.5rem 0.75rem;
        }
        
        .btn-action {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 480px) {
        .page-header {
            margin: 0 -0.5rem 1rem -0.5rem;
            padding: 0.5rem;
        }
        
        .page-title {
            font-size: 1rem;
        }
        
        .filter-tab {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .message-header {
            padding: 0.5rem;
        }
        
        .message-sender {
            font-size: 0.8rem;
        }
        
        .message-subject {
            font-size: 0.85rem;
        }
        
        .message-preview {
            font-size: 0.75rem;
        }
        
        .message-actions {
            padding: 0.4rem 0.5rem;
        }
        
        .btn-action {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
        }
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
    
    .filter-tabs {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .filter-tab {
        display: inline-block;
        padding: 0.5rem 1rem;
        margin-right: 0.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .filter-tab.active {
        background: var(--logo-dark-blue-primary);
        color: white;
        border-color: var(--logo-dark-blue-primary);
    }
    
    .filter-tab:not(.active) {
        color: var(--logo-text-medium);
        background: #f8fafc;
    }
    
    .filter-tab:not(.active):hover {
        background: var(--logo-dark-blue-primary);
        color: white;
        text-decoration: none;
    }
    
    .search-bar {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .search-input {
        border: 2px solid var(--logo-border-light);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        width: 100%;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--logo-dark-blue-primary);
        box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.1);
    }
    
    .message-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 4px solid;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .message-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .message-card.unread {
        border-left-color: var(--logo-dark-blue-primary);
        background: rgba(0, 7, 45, 0.02);
    }
    
    .message-card.read {
        border-left-color: var(--logo-border-light);
    }
    
    .message-card.important {
        border-left-color: var(--logo-warning);
        background: rgba(202, 138, 4, 0.02);
    }
    
    .message-card.urgent {
        border-left-color: var(--logo-danger);
        background: rgba(220, 38, 38, 0.02);
    }
    
    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }
    
    .message-sender {
        font-weight: 600;
        color: var(--logo-text-dark);
        font-size: 0.95rem;
    }
    
    .message-time {
        color: var(--logo-text-muted);
        font-size: 0.8rem;
    }
    
    .message-subject {
        font-weight: 600;
        color: var(--logo-text-dark);
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }
    
    .message-preview {
        color: var(--logo-text-medium);
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 0.75rem;
    }
    
    .message-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .message-badges {
        display: flex;
        gap: 0.5rem;
    }
    
    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
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
    
    .badge-unread {
        background: var(--logo-danger);
        color: white;
    }
    
    .badge-broadcast {
        background: #28a745;
        color: white;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }
    
    .message-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.25rem 0.5rem;
        border: none;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
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
    
    .btn-action:hover {
        transform: scale(1.05);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--logo-text-muted);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--logo-border-light);
    }
    
    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }
    
    .pagination .page-link {
        color: var(--logo-dark-blue-primary);
        border-color: var(--logo-border-light);
    }
    
    .pagination .page-link:hover {
        background: var(--logo-dark-blue-primary);
        color: white;
        border-color: var(--logo-dark-blue-primary);
    }
    
    .pagination .page-item.active .page-link {
        background: var(--logo-dark-blue-primary);
        border-color: var(--logo-dark-blue-primary);
    }
    
    /* DCS Brand Button Styles */
    .btn-dcs-primary {
        background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
        border: 1px solid var(--logo-dark-blue-primary);
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 7, 45, 0.2);
    }
    
    .btn-dcs-primary:hover {
        background: linear-gradient(135deg, var(--logo-dark-blue-secondary), var(--logo-dark-blue-primary));
        border-color: var(--logo-dark-blue-secondary);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 7, 45, 0.3);
    }
    
    .btn-dcs-primary:focus {
        background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
        border-color: var(--logo-dark-blue-primary);
        color: white;
        box-shadow: 0 0 0 0.2rem rgba(0, 7, 45, 0.25);
    }
    
    .btn-dcs-primary:active {
        background: linear-gradient(135deg, var(--logo-dark-blue-secondary), var(--logo-dark-blue-primary));
        border-color: var(--logo-dark-blue-secondary);
        color: white;
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 7, 45, 0.2);
    }
</style>

<div class="messages-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Messages</h1>
                <p class="page-subtitle">Manage your internal communications</p>
            </div>
            <div class="header-actions">
                <a href="<?php echo e(route('messages.create')); ?>" class="btn-clean">
                    <i class="fas fa-plus me-2"></i>New Message
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="<?php echo e(route('messages.index', ['filter' => 'all'])); ?>" 
           class="filter-tab <?php echo e($filter === 'all' ? 'active' : ''); ?>">
            All Messages (<?php echo e($counts['all']); ?>)
        </a>
        <a href="<?php echo e(route('messages.index', ['filter' => 'unread'])); ?>" 
           class="filter-tab <?php echo e($filter === 'unread' ? 'active' : ''); ?>">
            Unread (<?php echo e($counts['unread']); ?>)
        </a>
        <a href="<?php echo e(route('messages.index', ['filter' => 'read'])); ?>" 
           class="filter-tab <?php echo e($filter === 'read' ? 'active' : ''); ?>">
            Read (<?php echo e($counts['read']); ?>)
        </a>
        <a href="<?php echo e(route('messages.index', ['filter' => 'important'])); ?>" 
           class="filter-tab <?php echo e($filter === 'important' ? 'active' : ''); ?>">
            Important (<?php echo e($counts['important']); ?>)
        </a>
        <a href="<?php echo e(route('messages.index', ['filter' => 'urgent'])); ?>" 
           class="filter-tab <?php echo e($filter === 'urgent' ? 'active' : ''); ?>">
            Urgent (<?php echo e($counts['urgent']); ?>)
        </a>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="GET" action="<?php echo e(route('messages.index')); ?>">
            <input type="hidden" name="filter" value="<?php echo e($filter); ?>">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Search messages by subject, content, or sender..."
                       value="<?php echo e($search); ?>">
                <button type="submit" class="btn btn-dcs-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Messages List -->
    <?php if($messages->count() > 0): ?>
        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="message-card <?php echo e($message->isUnread() ? 'unread' : 'read'); ?> <?php echo e($message->is_important ? 'important' : ''); ?> <?php echo e($message->priority === 'urgent' ? 'urgent' : ''); ?>"
                 onclick="window.location.href='<?php echo e(route('messages.show', $message)); ?>'">
                <div class="message-header">
                    <div class="message-sender">
                        <i class="fas fa-user me-2"></i>
                        <?php echo e($message->sender->name); ?>

                    </div>
                    <div class="message-time">
                        <?php echo e($message->time_ago); ?>

                    </div>
                </div>
                
                <div class="message-subject">
                    <?php echo e($message->subject); ?>

                    <?php if($message->isUnread()): ?>
                        <span class="badge badge-unread ms-2">New</span>
                    <?php endif; ?>
                    <?php if($message->is_broadcast): ?>
                        <span class="badge badge-broadcast ms-2">
                            <i class="fas fa-bullhorn me-1"></i>Broadcast
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="message-preview">
                    <?php echo e(Str::limit(strip_tags($message->body), 150)); ?>

                </div>
                
                <div class="message-meta">
                    <div class="message-badges">
                        <span class="badge badge-priority"><?php echo e(ucfirst($message->priority)); ?></span>
                        <?php if($message->is_important): ?>
                            <span class="badge badge-important">Important</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="message-actions" onclick="event.stopPropagation()">
                        <?php if($message->isUnread()): ?>
                            <button class="btn-action btn-mark-read" 
                                    onclick="markAsRead(<?php echo e($message->id); ?>)"
                                    title="Mark as Read">
                                <i class="fas fa-check"></i>
                            </button>
                        <?php else: ?>
                            <button class="btn-action btn-mark-unread" 
                                    onclick="markAsUnread(<?php echo e($message->id); ?>)"
                                    title="Mark as Unread">
                                <i class="fas fa-envelope"></i>
                            </button>
                        <?php endif; ?>
                        
                        <button class="btn-action btn-archive" 
                                onclick="archiveMessage(<?php echo e($message->id); ?>)"
                                title="Archive">
                            <i class="fas fa-archive"></i>
                        </button>
                        
                        <form method="POST" action="<?php echo e(route('messages.destroy', $message)); ?>" 
                              style="display: inline;" 
                              onsubmit="return confirm('Are you sure you want to delete this message?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-action btn-delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            <?php echo e($messages->appends(request()->query())->links()); ?>

        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-envelope-open"></i>
            <h4>No messages found</h4>
            <p>
                <?php if($search): ?>
                    No messages match your search criteria.
                <?php else: ?>
                    You don't have any messages in this category yet.
                <?php endif; ?>
            </p>
            <a href="<?php echo e(route('messages.create')); ?>" class="btn btn-dcs-primary">
                <i class="fas fa-plus me-2"></i>Send New Message
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
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
            location.reload();
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
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\DCS-Best\resources\views/messages/index.blade.php ENDPATH**/ ?>