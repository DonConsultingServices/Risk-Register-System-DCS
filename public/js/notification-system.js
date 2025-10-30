/**
 * Comprehensive Notification System with Sounds
 * Real-time notifications with audio alerts
 */

class NotificationSystem {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.soundEnabled = true;
        this.pollingInterval = null;
        this.isPolling = false;
        this.lastNotificationId = 0;
        
        this.init();
    }
    
    init() {
        this.loadNotificationSettings();
        this.bindEvents();
        this.startPolling();
        this.loadInitialNotifications();
        this.setupSoundSystem();
    }
    
    loadNotificationSettings() {
        // Load user preferences from localStorage
        this.soundEnabled = localStorage.getItem('notification_sound_enabled') !== 'false';
        this.pollingInterval = parseInt(localStorage.getItem('notification_polling_interval')) || 5000;
    }
    
    bindEvents() {
        // Bind notification dropdown events
        this.bindNotificationDropdown();
        
        // Bind mark as read events
        this.bindMarkAsReadEvents();
        
        // Bind sound toggle events
        this.bindSoundToggleEvents();
        
        // Bind page visibility events
        this.bindPageVisibilityEvents();
        
        // Bind beforeunload events
        this.bindBeforeUnloadEvents();
    }
    
    bindNotificationDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        const mobileDropdown = document.getElementById('mobileNotificationDropdown');
        
        if (dropdown) {
            dropdown.addEventListener('show.bs.dropdown', () => {
                this.loadNotifications();
            });
        }
        
        if (mobileDropdown) {
            mobileDropdown.addEventListener('show.bs.dropdown', () => {
                this.loadNotifications();
            });
        }
    }
    
    bindMarkAsReadEvents() {
        // Mark all as read button
        const markAllReadBtn = document.querySelector('.btn-mark-all-read');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', () => {
                this.markAllAsRead();
            });
        }
        
        // Clear all button
        const clearAllBtn = document.querySelector('.btn-clear-all');
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', () => {
                this.clearAllNotifications();
            });
        }
    }
    
    bindSoundToggleEvents() {
        // Add sound toggle button to notification dropdown
        this.addSoundToggleButton();
    }
    
    bindPageVisibilityEvents() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.pausePolling();
            } else {
                this.resumePolling();
            }
        });
    }
    
    bindBeforeUnloadEvents() {
        window.addEventListener('beforeunload', () => {
            this.markUserOffline();
        });
    }
    
    setupSoundSystem() {
        // Create audio context for better sound control
        this.audioContext = null;
        this.sounds = {};
        
        // Initialize audio context on user interaction
        document.addEventListener('click', () => {
            if (!this.audioContext) {
                this.initAudioContext();
            }
        }, { once: true });
    }
    
    initAudioContext() {
        try {
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
            this.loadNotificationSounds();
        } catch (e) {
            console.warn('Audio context not supported:', e);
        }
    }
    
    loadNotificationSounds() {
        const soundFiles = {
            'normal': '/sounds/notifications/normal-notification.mp3',
            'high': '/sounds/notifications/high-notification.mp3',
            'urgent': '/sounds/notifications/urgent-notification.mp3',
            'message': '/sounds/notifications/message-notification.mp3',
            'broadcast': '/sounds/notifications/broadcast-notification.mp3'
        };
        
        Object.entries(soundFiles).forEach(([type, url]) => {
            this.loadSound(type, url);
        });
    }
    
    loadSound(type, url) {
        // Skip loading external sound files and use fallback sounds directly
        this.sounds[type] = this.createBeepSound(type);
    }
    
    createBeepSound(type) {
        if (!this.audioContext) return null;
        
        const duration = type === 'urgent' ? 0.5 : 0.3;
        const frequency = type === 'urgent' ? 800 : type === 'high' ? 600 : 400;
        const sampleRate = this.audioContext.sampleRate;
        const length = sampleRate * duration;
        const buffer = this.audioContext.createBuffer(1, length, sampleRate);
        const data = buffer.getChannelData(0);
        
        for (let i = 0; i < length; i++) {
            data[i] = Math.sin(2 * Math.PI * frequency * i / sampleRate) * 0.3;
        }
        
        return buffer;
    }
    
    playNotificationSound(priority, type) {
        if (!this.soundEnabled || !this.audioContext) return;
        
        const soundType = type === 'message' ? 'message' : 
                         type === 'broadcast' ? 'broadcast' : 
                         priority;
        
        const sound = this.sounds[soundType] || this.sounds['normal'];
        if (!sound) return;
        
        try {
            const source = this.audioContext.createBufferSource();
            source.buffer = sound;
            source.connect(this.audioContext.destination);
            source.start();
        } catch (e) {
            console.warn('Could not play notification sound:', e);
        }
    }
    
    startPolling() {
        if (this.isPolling) return;
        
        this.isPolling = true;
        this.pollingInterval = setInterval(() => {
            this.checkForNewNotifications();
        }, this.pollingInterval);
    }
    
    pausePolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.isPolling = false;
        }
    }
    
    resumePolling() {
        if (!this.isPolling) {
            this.startPolling();
        }
    }
    
    async checkForNewNotifications() {
        try {
            const response = await fetch('/notifications/unread-count', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.updateUnreadCount(data.count);
            }
        } catch (error) {
            console.warn('Failed to check notifications:', error);
        }
    }
    
    async loadInitialNotifications() {
        try {
            const response = await fetch('/notifications/recent', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.notifications = data.notifications || [];
                this.updateUnreadCount(data.unread_count || 0);
            }
        } catch (error) {
            console.warn('Failed to load initial notifications:', error);
        }
    }
    
    async loadNotifications() {
        try {
            const response = await fetch('/notifications/recent', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.notifications = data.notifications || [];
                this.renderNotifications();
            }
        } catch (error) {
            console.warn('Failed to load notifications:', error);
        }
    }
    
    renderNotifications() {
        const container = document.getElementById('notification-list');
        const mobileContainer = document.getElementById('mobile-notification-list');
        
        if (!container && !mobileContainer) return;
        
        const html = this.notifications.map(notification => this.renderNotificationItem(notification)).join('');
        
        if (container) {
            container.innerHTML = html;
        }
        if (mobileContainer) {
            mobileContainer.innerHTML = html;
        }
    }
    
    renderNotificationItem(notification) {
        const icon = this.getNotificationIcon(notification.type);
        const color = this.getNotificationColor(notification.priority);
        const timeAgo = this.getTimeAgo(notification.created_at);
        const isUnread = !notification.read;
        
        return `
            <div class="notification-item ${isUnread ? 'unread' : ''}" data-id="${notification.id}">
                <div class="notification-icon" style="color: ${color}">
                    <i class="${icon}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">${timeAgo}</div>
                </div>
                <div class="notification-actions">
                    ${isUnread ? `<button class="btn-mark-read" onclick="notificationSystem.markAsRead(${notification.id})" title="Mark as read">
                        <i class="fas fa-check"></i>
                    </button>` : ''}
                    <button class="btn-delete-notification" onclick="notificationSystem.deleteNotification(${notification.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    }
    
    getNotificationIcon(type) {
        const icons = {
            'message': 'fas fa-envelope',
            'broadcast': 'fas fa-bullhorn',
            'risk': 'fas fa-exclamation-triangle',
            'approval': 'fas fa-check-circle',
            'system': 'fas fa-cog',
            'client': 'fas fa-user'
        };
        return icons[type] || 'fas fa-bell';
    }
    
    getNotificationColor(priority) {
        const colors = {
            'urgent': '#dc3545',
            'high': '#fd7e14',
            'normal': '#0d6efd',
            'low': '#6c757d'
        };
        return colors[priority] || '#0d6efd';
    }
    
    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
        return `${Math.floor(diffInSeconds / 86400)}d ago`;
    }
    
    updateUnreadCount(count) {
        this.unreadCount = count;
        
        // Update desktop notification badge
        const badge = document.getElementById('notification-count');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'block' : 'none';
        }
        
        // Update mobile notification badge
        const mobileBadge = document.getElementById('mobile-notification-count');
        if (mobileBadge) {
            mobileBadge.textContent = count;
            mobileBadge.style.display = count > 0 ? 'block' : 'none';
        }
        
        // Update page title if there are unread notifications
        if (count > 0) {
            document.title = `(${count}) ${document.title.replace(/^\(\d+\)\s*/, '')}`;
        } else {
            document.title = document.title.replace(/^\(\d+\)\s*/, '');
        }
    }
    
    async markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                // Update local notification
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification) {
                    notification.read = true;
                    notification.read_at = new Date().toISOString();
                }
                
                // Re-render notifications
                this.renderNotifications();
                
                // Update unread count
                this.updateUnreadCount(Math.max(0, this.unreadCount - 1));
            }
        } catch (error) {
            console.warn('Failed to mark notification as read:', error);
        }
    }
    
    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                // Update all local notifications
                this.notifications.forEach(notification => {
                    notification.read = true;
                    notification.read_at = new Date().toISOString();
                });
                
                // Re-render notifications
                this.renderNotifications();
                
                // Update unread count
                this.updateUnreadCount(0);
            }
        } catch (error) {
            console.warn('Failed to mark all notifications as read:', error);
        }
    }
    
    async deleteNotification(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                // Remove from local notifications
                this.notifications = this.notifications.filter(n => n.id !== notificationId);
                
                // Re-render notifications
                this.renderNotifications();
                
                // Update unread count
                const unreadCount = this.notifications.filter(n => !n.read).length;
                this.updateUnreadCount(unreadCount);
            }
        } catch (error) {
            console.warn('Failed to delete notification:', error);
        }
    }
    
    async clearAllNotifications() {
        try {
            const response = await fetch('/notifications/clear-all', {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                // Clear local notifications
                this.notifications = [];
                
                // Re-render notifications
                this.renderNotifications();
                
                // Update unread count
                this.updateUnreadCount(0);
            }
        } catch (error) {
            console.warn('Failed to clear all notifications:', error);
        }
    }
    
    addSoundToggleButton() {
        const notificationHeader = document.querySelector('.notification-header');
        if (!notificationHeader) return;
        
        const soundToggle = document.createElement('button');
        soundToggle.className = 'btn-sound-toggle';
        soundToggle.innerHTML = `<i class="fas ${this.soundEnabled ? 'fa-volume-up' : 'fa-volume-mute'}"></i>`;
        soundToggle.title = this.soundEnabled ? 'Disable sounds' : 'Enable sounds';
        soundToggle.onclick = () => this.toggleSound();
        
        const actions = notificationHeader.querySelector('.notification-actions');
        if (actions) {
            actions.appendChild(soundToggle);
        }
    }
    
    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        localStorage.setItem('notification_sound_enabled', this.soundEnabled);
        
        const button = document.querySelector('.btn-sound-toggle');
        if (button) {
            button.innerHTML = `<i class="fas ${this.soundEnabled ? 'fa-volume-up' : 'fa-volume-mute'}"></i>`;
            button.title = this.soundEnabled ? 'Disable sounds' : 'Enable sounds';
        }
    }
    
    async markUserOffline() {
        try {
            await fetch('/user-activity/offline', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
        } catch (error) {
            console.warn('Failed to mark user offline:', error);
        }
    }
    
    // Public method to create a test notification
    createTestNotification(type = 'message', priority = 'normal') {
        const testNotification = {
            id: Date.now(),
            type: type,
            title: 'Test Notification',
            message: 'This is a test notification',
            priority: priority,
            read: false,
            created_at: new Date().toISOString()
        };
        
        this.notifications.unshift(testNotification);
        this.renderNotifications();
        this.updateUnreadCount(this.unreadCount + 1);
        this.playNotificationSound(priority, type);
    }
}

// Initialize notification system when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.notificationSystem = new NotificationSystem();
});

// Export for use in other scripts
window.NotificationSystem = NotificationSystem;
