/**
 * Mobile Gestures and Touch Interactions
 * Amazon-quality mobile experience enhancements
 */

class MobileGestures {
    constructor() {
        this.touchStartX = 0;
        this.touchStartY = 0;
        this.touchEndX = 0;
        this.touchEndY = 0;
        this.minSwipeDistance = 50;
        this.maxSwipeTime = 300;
        this.touchStartTime = 0;
        
        this.init();
    }
    
    init() {
        this.bindTouchEvents();
        this.bindPullToRefresh();
        this.bindSwipeGestures();
        this.bindHapticFeedback();
        this.bindMobileOptimizations();
    }
    
    bindTouchEvents() {
        // Add touch feedback to interactive elements
        const touchElements = document.querySelectorAll('button, .btn, .card, .nav-link, .dropdown-toggle');
        
        touchElements.forEach(element => {
            element.addEventListener('touchstart', (e) => {
                element.classList.add('touch-active');
            });
            
            element.addEventListener('touchend', (e) => {
                setTimeout(() => {
                    element.classList.remove('touch-active');
                }, 150);
            });
            
            element.addEventListener('touchcancel', (e) => {
                element.classList.remove('touch-active');
            });
        });
    }
    
    bindPullToRefresh() {
        let startY = 0;
        let currentY = 0;
        let isPulling = false;
        let pullDistance = 0;
        const maxPullDistance = 100;
        
        document.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                isPulling = true;
            }
        });
        
        document.addEventListener('touchmove', (e) => {
            if (isPulling && window.scrollY === 0) {
                currentY = e.touches[0].clientY;
                pullDistance = currentY - startY;
                
                if (pullDistance > 0) {
                    // Only prevent default if we're actually pulling to refresh
                    // and not inside a modal or form element
                    const target = e.target;
                    const isModalElement = target.closest('.modal') || target.closest('.modal-dialog') || target.closest('.modal-content');
                    const isFormElement = target.closest('form') || target.closest('button') || target.closest('input') || target.closest('textarea');
                    
                    if (!isModalElement && !isFormElement) {
                        e.preventDefault();
                        this.showPullToRefreshIndicator(pullDistance);
                    }
                }
            }
        });
        
        document.addEventListener('touchend', (e) => {
            if (isPulling && pullDistance > this.minSwipeDistance) {
                this.triggerPullToRefresh();
            }
            this.hidePullToRefreshIndicator();
            isPulling = false;
            pullDistance = 0;
        });
    }
    
    bindSwipeGestures() {
        // Swipe to go back functionality
        document.addEventListener('touchstart', (e) => {
            this.touchStartX = e.touches[0].clientX;
            this.touchStartY = e.touches[0].clientY;
            this.touchStartTime = Date.now();
        });
        
        document.addEventListener('touchend', (e) => {
            this.touchEndX = e.changedTouches[0].clientX;
            this.touchEndY = e.changedTouches[0].clientY;
            
            const swipeDistanceX = this.touchEndX - this.touchStartX;
            const swipeDistanceY = this.touchEndY - this.touchStartY;
            const swipeTime = Date.now() - this.touchStartTime;
            
            // Horizontal swipe detection
            if (Math.abs(swipeDistanceX) > Math.abs(swipeDistanceY) && 
                Math.abs(swipeDistanceX) > this.minSwipeDistance && 
                swipeTime < this.maxSwipeTime) {
                
                if (swipeDistanceX > 0) {
                    // Swipe right - go back
                    this.handleSwipeRight();
                } else {
                    // Swipe left - forward
                    this.handleSwipeLeft();
                }
            }
            
            // Vertical swipe detection
            if (Math.abs(swipeDistanceY) > Math.abs(swipeDistanceX) && 
                Math.abs(swipeDistanceY) > this.minSwipeDistance && 
                swipeTime < this.maxSwipeTime) {
                
                if (swipeDistanceY > 0) {
                    // Swipe down
                    this.handleSwipeDown();
                } else {
                    // Swipe up
                    this.handleSwipeUp();
                }
            }
        });
    }
    
    bindHapticFeedback() {
        // Add haptic feedback for supported devices
        if ('vibrate' in navigator) {
            const feedbackElements = document.querySelectorAll('button, .btn, .nav-link, .dropdown-toggle');
            
            feedbackElements.forEach(element => {
                element.addEventListener('touchstart', () => {
                    // Only vibrate after user has interacted with the page
                    if (document.hasFocus()) {
                        try {
                            navigator.vibrate(10); // Short vibration
                        } catch (error) {
                            // Silently ignore vibration errors
                        }
                    }
                });
            });
        }
    }
    
    bindMobileOptimizations() {
        // Prevent zoom on double tap, but allow normal interactions in modals
        let lastTouchEnd = 0;
        document.addEventListener('touchend', (e) => {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                // Only prevent default if not inside a modal or interactive element
                const target = e.target;
                const isModalElement = target.closest('.modal') || target.closest('.modal-dialog') || target.closest('.modal-content');
                const isInteractiveElement = target.closest('button') || target.closest('a') || target.closest('input') || target.closest('textarea') || target.closest('select');
                
                if (!isModalElement && !isInteractiveElement) {
                    e.preventDefault();
                }
            }
            lastTouchEnd = now;
        });
        
        // Optimize scroll performance
        let ticking = false;
        document.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    this.handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        // Handle orientation change
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                this.handleOrientationChange();
            }, 100);
        });
        
        // Handle viewport changes
        window.addEventListener('resize', () => {
            this.handleViewportChange();
        });
    }
    
    showPullToRefreshIndicator(distance) {
        let indicator = document.getElementById('pull-to-refresh-indicator');
        if (!indicator) {
            indicator = this.createPullToRefreshIndicator();
        }
        
        const opacity = Math.min(distance / this.minSwipeDistance, 1);
        const scale = Math.min(distance / this.minSwipeDistance, 1);
        
        indicator.style.opacity = opacity;
        indicator.style.transform = `scale(${scale})`;
        indicator.style.display = 'block';
    }
    
    hidePullToRefreshIndicator() {
        const indicator = document.getElementById('pull-to-refresh-indicator');
        if (indicator) {
            indicator.style.display = 'none';
        }
    }
    
    createPullToRefreshIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'pull-to-refresh-indicator';
        indicator.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i><br>Pull to refresh';
        indicator.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 7, 45, 0.9);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            text-align: center;
            z-index: 9999;
            display: none;
            transition: all 0.3s ease;
        `;
        document.body.appendChild(indicator);
        return indicator;
    }
    
    triggerPullToRefresh() {
        // Trigger page refresh or data reload
        window.location.reload();
    }
    
    handleSwipeRight() {
        // Go back in history
        if (window.history.length > 1) {
            window.history.back();
        }
    }
    
    handleSwipeLeft() {
        // Go forward in history
        if (window.history.length > 1) {
            window.history.forward();
        }
    }
    
    handleSwipeDown() {
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    handleSwipeUp() {
        // Scroll to bottom
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }
    
    handleScroll() {
        // Add scroll-based animations and optimizations
        const scrollY = window.scrollY;
        
        // Hide/show mobile header based on scroll direction
        const header = document.querySelector('.top-bar');
        if (header) {
            if (scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
        
        // Parallax effects for cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            const rect = card.getBoundingClientRect();
            const speed = 0.5;
            const yPos = -(rect.top * speed);
            card.style.transform = `translateY(${yPos}px)`;
        });
    }
    
    handleOrientationChange() {
        // Recalculate layouts after orientation change
        setTimeout(() => {
            this.recalculateLayouts();
        }, 100);
    }
    
    handleViewportChange() {
        // Handle viewport changes
        this.recalculateLayouts();
    }
    
    recalculateLayouts() {
        // Recalculate any layout-dependent elements
        const tables = document.querySelectorAll('.table-responsive');
        tables.forEach(table => {
            // Trigger table recalculation
            table.style.width = '100%';
        });
    }
}

// Add CSS for touch feedback
const touchStyles = `
    .touch-active {
        transform: scale(0.95);
        opacity: 0.8;
        transition: all 0.1s ease;
    }
    
    .top-bar.scrolled {
        box-shadow: 0 2px 20px rgba(0,0,0,0.15);
        backdrop-filter: blur(10px);
    }
    
    @media (max-width: 768px) {
        .card {
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .btn, .nav-link, .dropdown-toggle {
            transition: all 0.2s ease;
        }
        
        .btn:active, .nav-link:active, .dropdown-toggle:active {
            transform: scale(0.95);
        }
    }
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = touchStyles;
document.head.appendChild(styleSheet);

// Initialize mobile gestures when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new MobileGestures();
});

// Export for use in other scripts
window.MobileGestures = MobileGestures;
