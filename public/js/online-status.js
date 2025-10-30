/**
 * Online Status System
 * Tracks user activity and shows online/offline status
 */

class OnlineStatusSystem {
    constructor() {
        this.isOnline = true;
        this.lastActivity = Date.now();
        this.activityTimeout = null;
        this.heartbeatInterval = null;
        this.heartbeatIntervalMs = 30000; // 30 seconds
        this.lastUpdateSent = 0;
        this.updateThrottleMs = 10000; // Only send updates every 10 seconds
        this.pendingUpdate = false;
        this.updateQueue = [];
        
        this.init();
    }
    
    init() {
        this.bindActivityEvents();
        this.startHeartbeat();
        this.updateUserActivity();
    }
    
    bindActivityEvents() {
        // Track user activity - be more selective to reduce frequency
        const events = ['mousedown', 'keypress', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, (e) => {
                // Don't interfere with modal interactions
                const target = e.target;
                const isModalElement = target.closest('.modal') || target.closest('.modal-dialog') || target.closest('.modal-content');
                
                if (!isModalElement) {
                    this.updateUserActivity();
                }
            }, false); // Changed from true to false to not capture events
        });
        
        // Track scroll and mousemove less frequently
        let scrollTimeout;
        document.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.updateUserActivity();
            }, 2000); // Only update after 2 seconds of no scrolling
        }, false); // Changed from true to false
        
        let mouseTimeout;
        document.addEventListener('mousemove', () => {
            clearTimeout(mouseTimeout);
            mouseTimeout = setTimeout(() => {
                this.updateUserActivity();
            }, 5000); // Only update after 5 seconds of no mouse movement
        }, false); // Changed from true to false
        
        // Track page visibility
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.handlePageHidden();
            } else {
                this.handlePageVisible();
            }
        });
        
        // Track beforeunload
        window.addEventListener('beforeunload', () => {
            this.markUserOffline();
        });
    }
    
    updateUserActivity() {
        this.lastActivity = Date.now();
        
        // Clear existing timeout
        if (this.activityTimeout) {
            clearTimeout(this.activityTimeout);
        }
        
        // Set new timeout to mark as offline after 5 minutes of inactivity
        this.activityTimeout = setTimeout(() => {
            this.markUserOffline();
        }, 5 * 60 * 1000); // 5 minutes
        
        // Throttle activity updates to prevent overwhelming the server
        this.throttledSendActivityUpdate();
    }
    
    throttledSendActivityUpdate() {
        const now = Date.now();
        
        // If we've sent an update recently, don't send another one
        if (now - this.lastUpdateSent < this.updateThrottleMs) {
            return;
        }
        
        // If there's already a pending update, don't queue another one
        if (this.pendingUpdate) {
            return;
        }
        
        this.sendActivityUpdate();
    }
    
    async sendActivityUpdate() {
        // Only send activity updates if user is authenticated
        if (!this.isUserAuthenticated()) {
            return;
        }

        // Mark that we have a pending update
        this.pendingUpdate = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.warn('CSRF token not found, skipping activity update');
                this.pendingUpdate = false;
                return;
            }

            const response = await fetch('/user-activity/update', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    activity_type: 'page_view',
                    page_url: window.location.href
                }),
                // Add timeout to prevent hanging requests
                signal: AbortSignal.timeout(5000)
            });

            if (!response.ok) {
                // If unauthorized, stop trying to send updates
                if (response.status === 401 || response.status === 403) {
                    this.isOnline = false;
                    this.pendingUpdate = false;
                    return;
                }
                
                // For server errors, increase throttle time temporarily
                if (response.status >= 500) {
                    this.updateThrottleMs = 30000; // 30 seconds
                    setTimeout(() => {
                        this.updateThrottleMs = 10000; // Reset to 10 seconds
                    }, 60000); // After 1 minute
                }
                
                // Don't log every error to avoid spam
                if (Math.random() < 0.1) { // Only log 10% of errors
                    console.warn(`Activity update failed (${response.status})`);
                }
                this.pendingUpdate = false;
                return;
            }

            // Update successful, reset throttle
            this.lastUpdateSent = Date.now();
            this.pendingUpdate = false;

        } catch (error) {
            this.pendingUpdate = false;
            
            // Handle specific error types
            if (error.name === 'AbortError') {
                // Request timed out, increase throttle temporarily
                this.updateThrottleMs = 30000;
                setTimeout(() => {
                    this.updateThrottleMs = 10000;
                }, 60000);
                return;
            }
            
            if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                // Network error, increase throttle temporarily
                this.updateThrottleMs = 30000;
                setTimeout(() => {
                    this.updateThrottleMs = 10000;
                }, 60000);
                return;
            }
            
            // Only log errors occasionally to avoid spam
            if (Math.random() < 0.05) { // Only log 5% of errors
                console.warn('Failed to update user activity:', error.message);
            }
        }
    }
    
    startHeartbeat() {
        this.heartbeatInterval = setInterval(() => {
            if (this.isOnline && !this.pendingUpdate) {
                this.sendActivityUpdate();
            }
        }, this.heartbeatIntervalMs);
    }
    
    handlePageHidden() {
        // Page is hidden, reduce activity updates
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
            this.heartbeatInterval = setInterval(() => {
                this.sendActivityUpdate();
            }, 60000); // 1 minute when hidden
        }
    }
    
    handlePageVisible() {
        // Page is visible, resume normal activity updates
        this.updateUserActivity();
        
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
            this.startHeartbeat();
        }
    }
    
    async markUserOffline() {
        if (!this.isOnline || !this.isUserAuthenticated()) return;
        
        this.isOnline = false;
        
        try {
            await fetch('/user-activity/offline', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
        } catch (error) {
            console.warn('Failed to mark user offline:', error);
        }
    }
    
    // Check if user is authenticated
    isUserAuthenticated() {
        // Check if we're on a page that requires authentication
        const authRequiredPages = ['/dashboard', '/clients', '/risks', '/messages', '/notifications', '/profile'];
        const currentPath = window.location.pathname;
        
        // If we're on a public page, don't try to send activity updates
        if (!authRequiredPages.some(page => currentPath.startsWith(page))) {
            return false;
        }
        
        // Check if CSRF token exists (indicates authenticated session)
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        return csrfToken !== null;
    }

    // Get online status for a specific user
    async getUserOnlineStatus(userId) {
        if (!this.isUserAuthenticated()) return null;

        try {
            const response = await fetch(`/user-activity/status/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                return data;
            }
        } catch (error) {
            console.warn('Failed to get user online status:', error);
        }
        
        return null;
    }
    
    // Get all online users
    async getOnlineUsers() {
        if (!this.isUserAuthenticated()) return [];

        try {
            const response = await fetch('/user-activity/online-users', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                return data.users || [];
            }
        } catch (error) {
            console.warn('Failed to get online users:', error);
        }
        
        return [];
    }
    
    // Render online status indicator
    renderOnlineStatus(userId, status) {
        const indicator = document.querySelector(`[data-user-id="${userId}"] .online-status`);
        if (!indicator) return;
        
        const statusText = status.is_online ? 'Online' : status.status_text || 'Offline';
        const statusColor = status.is_online ? '#28a745' : status.color || '#6c757d';
        
        indicator.innerHTML = `
            <span class="status-dot" style="background-color: ${statusColor}"></span>
            <span class="status-text">${statusText}</span>
        `;
    }
    
    // Update online status for all users in a list
    async updateOnlineStatuses() {
        const onlineUsers = await this.getOnlineUsers();
        
        onlineUsers.forEach(user => {
            this.renderOnlineStatus(user.id, {
                is_online: user.is_online,
                status_text: user.status_text,
                color: user.status_color
            });
        });
    }
    
    // Start periodic status updates
    startStatusUpdates() {
        setInterval(() => {
            this.updateOnlineStatuses();
        }, 30000); // Update every 30 seconds
    }
}

// Initialize online status system when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.onlineStatusSystem = new OnlineStatusSystem();
    
    // Start status updates for messaging pages
    if (window.location.pathname.includes('/messages')) {
        window.onlineStatusSystem.startStatusUpdates();
    }
});

// Export for use in other scripts
window.OnlineStatusSystem = OnlineStatusSystem;
