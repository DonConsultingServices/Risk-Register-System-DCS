

<?php $__env->startSection('title', 'Dashboard - DCS-Best'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .dashboard-container {
        max-width: 100%;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
    }
    
    /* Mobile-First Dashboard Optimizations */
    @media (max-width: 768px) {
        .dashboard-container {
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
        
        /* Mobile Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-card p {
            font-size: 0.85rem;
            margin: 0;
        }
        
        /* Mobile Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .quick-action-btn {
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .quick-action-btn i {
            font-size: 1.5rem;
        }
        
        .quick-action-btn span {
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        /* Mobile Tables */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .table {
            font-size: 0.8rem;
            margin-bottom: 0;
        }
        
        .table th,
        .table td {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
        }
        
        .table th {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Mobile Cards */
        .card {
            margin-bottom: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .card-header h5 {
            font-size: 1rem;
            margin: 0;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        /* Mobile Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        
        /* Mobile Badges */
        .badge {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem;
        }
        
        /* Mobile Progress Bars */
        .progress {
            height: 8px;
            border-radius: 4px;
        }
        
        .progress-bar {
            border-radius: 4px;
        }
        
        /* Mobile Alerts */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        /* Mobile Spacing */
        .mb-4 {
            margin-bottom: 1rem !important;
        }
        
        .mb-3 {
            margin-bottom: 0.75rem !important;
        }
        
        .mt-4 {
            margin-top: 1rem !important;
        }
        
        .p-3 {
            padding: 0.75rem !important;
        }
        
        .p-4 {
            padding: 1rem !important;
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
        
        .stats-grid {
            gap: 0.75rem;
        }
        
        .stat-card {
            padding: 1rem;
        }
        
        .stat-card h3 {
            font-size: 1.25rem;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .quick-action-btn {
            padding: 0.75rem;
        }
        
        .quick-action-btn i {
            font-size: 1.25rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.75rem;
        }
        
        .card-header,
        .card-body {
            padding: 0.75rem;
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
        
        .stat-card {
            padding: 0.75rem;
        }
        
        .stat-card h3 {
            font-size: 1.1rem;
        }
        
        .table th,
        .table td {
            padding: 0.4rem 0.2rem;
            font-size: 0.7rem;
        }
        
        .card-header,
        .card-body {
            padding: 0.5rem;
        }
    }
    
    .page-header {
        background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 7, 45, 0.2);
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        color: white;
    }
    
    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        margin: 0.25rem 0 0 0;
        font-size: 0.85rem;
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
    }
    
    .btn-clean:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-1px);
    }
    
    /* Activities List Styling */
    .activities-list {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .activity-item {
        display: flex;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--logo-border-light);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }
    
    .activity-icon.risk {
        background-color: rgba(220, 38, 38, 0.1);
        color: var(--logo-danger);
    }
    
    .activity-icon.client {
        background-color: rgba(8, 145, 178, 0.1);
        color: var(--logo-info);
    }
    
    .activity-icon.user {
        background-color: rgba(22, 163, 74, 0.1);
        color: var(--logo-success);
    }
    
    .activity-icon.system {
        background-color: rgba(202, 138, 4, 0.1);
        color: var(--logo-warning);
    }
    
    .activity-content {
        flex: 1;
        min-width: 0;
    }
    
    .activity-title {
        font-weight: 600;
        color: var(--logo-text-dark);
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }
    
    .activity-description {
        color: var(--logo-text-medium);
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
    
    .activity-time {
        color: var(--logo-text-muted);
        font-size: 0.75rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 4px solid;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background: currentColor;
        opacity: 0.1;
        border-radius: 50%;
        transform: translate(20px, -20px);
    }
    
    .stat-card.primary { border-left-color: var(--logo-dark-blue-primary); color: var(--logo-dark-blue-primary); }
    .stat-card.success { border-left-color: var(--logo-green); color: var(--logo-green); }
    .stat-card.warning { border-left-color: var(--logo-warning); color: var(--logo-warning); }
    .stat-card.info { border-left-color: var(--logo-info); color: var(--logo-info); }
    .stat-card.danger { border-left-color: var(--logo-danger); color: var(--logo-danger); }
    
    .stat-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: #2d3748;
    }
    
    .stat-info p {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0.5rem 0 0 0;
        opacity: 0.8;
    }
    
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .quick-actions {
        background: white;
        border-radius: 8px;
        padding: 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }
    
    .quick-actions h5 {
        color: var(--logo-dark-blue-primary);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1rem;
    }
    
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    
    .action-btn.primary {
        background: var(--logo-dark-blue-primary);
        color: white;
        border-color: var(--logo-dark-blue-primary);
    }
    
    .action-btn.success {
        background: var(--logo-green);
        color: white;
        border-color: var(--logo-green);
    }
    
    .action-btn.info {
        background: var(--logo-info);
        color: white;
        border-color: var(--logo-info);
    }
    
    .action-btn.warning {
        background: var(--logo-warning);
        color: white;
        border-color: var(--logo-warning);
    }
    
    .welcome-section {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .welcome-title {
        color: var(--logo-dark-blue-primary);
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }
    
    .welcome-message {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    
    .welcome-time {
        color: var(--logo-dark-blue-primary);
        font-weight: 500;
        font-size: 0.8rem;
    }
</style>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Risk Management Overview</p>
            </div>
            <div class="header-actions">
                <button class="btn-clean" onclick="refreshDashboard()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>


    <!-- Welcome Section -->
    <div class="welcome-section">
        <h2 class="welcome-title">Welcome back, <?php echo e(auth()->user()->name ?? 'User'); ?>!</h2>
        <p class="welcome-message">
            Here's your risk management overview for today. Monitor your key metrics and take quick actions to manage risks effectively.
        </p>
        <div class="welcome-time" id="currentTime"></div>
    </div>

    <!-- Quick User Guide (Collapsible) -->
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <div class="d-flex align-items-start">
            <i class="fas fa-question-circle fa-2x me-3 mt-1" style="color: #00072D;"></i>
            <div style="flex: 1;">
                <h5 class="alert-heading mb-0" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#quickGuideContent" aria-expanded="false" onclick="toggleGuideChevron()">
                    <strong>
                        <i class="fas fa-book-open me-2"></i>Quick Start Guide
                        <i class="fas fa-chevron-down float-end" id="guideChevron" style="transition: transform 0.3s ease; display: inline-block;"></i>
                    </strong>
                </h5>
                
                <div class="collapse" id="quickGuideContent">
                    <hr class="my-3">
                    <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-primary mb-2"><i class="fas fa-user-plus me-2"></i>1. Add a New Client Assessment</h6>
                        <ul style="font-size: 0.95rem; line-height: 1.8;">
                            <li>Go to <strong>Risk Register</strong> (left menu)</li>
                            <li>Click <span class="badge bg-success">Add New Risk Assessment</span></li>
                            <li>Fill in client details and select risk categories</li>
                            <li>System automatically creates client record</li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-primary mb-2"><i class="fas fa-users me-2"></i>2. View Client Information</h6>
                        <ul style="font-size: 0.95rem; line-height: 1.8;">
                            <li>Go to <strong>Clients</strong> (left menu)</li>
                            <li>View all approved clients with complete risk data</li>
                            <li>Click <i class="fas fa-eye text-primary"></i> to view details</li>
                            <li>Click <i class="fas fa-history text-info"></i> to see assessment history</li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-primary mb-2"><i class="fas fa-chart-line me-2"></i>3. Generate Reports</h6>
                        <ul style="font-size: 0.95rem; line-height: 1.8;">
                            <li>Go to <strong>Reports</strong> (left menu)</li>
                            <li>View comprehensive risk statistics</li>
                            <li>Filter by risk level, status, or category</li>
                            <li>Export data for regulatory compliance</li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-primary mb-2"><i class="fas fa-cog me-2"></i>4. Manage System Settings</h6>
                        <ul style="font-size: 0.95rem; line-height: 1.8;">
                            <li>Go to <strong>Settings</strong> (left menu)</li>
                            <li>Configure risk thresholds and monitoring</li>
                            <li>Manage users and permissions</li>
                            <li>Customize system preferences</li>
                        </ul>
                    </div>
                </div>
                
                    <div class="mt-2 p-2 bg-light rounded">
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            <strong>Tip:</strong> All navigation items are in the left sidebar. Click on any menu item to access different sections of the system.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <a href="<?php echo e(route('risks.reports')); ?>" class="stat-card primary clickable-stat">
            <div class="stat-content">
                <div class="stat-info">
                    <h3 id="total-risks"><?php echo e($totalRisks ?? 0); ?></h3>
                    <p>Risk Records</p>
                </div>
                <i class="fas fa-exclamation-triangle stat-icon"></i>
            </div>
        </a>

        <a href="<?php echo e(route('clients.index')); ?>" class="stat-card success clickable-stat">
            <div class="stat-content">
                <div class="stat-info">
                    <h3 id="active-clients"><?php echo e($activeClients ?? 0); ?></h3>
                    <p>Approved Clients</p>
                </div>
                <i class="fas fa-users stat-icon"></i>
            </div>
        </a>

        

        <a href="<?php echo e(route('risks.reports', ['filter' => 'high_risk'])); ?>" class="stat-card warning clickable-stat">
            <div class="stat-content">
                <div class="stat-info">
                    <h3 id="high-risk-clients"><?php echo e($highRiskClients ?? 0); ?></h3>
                    <p>High Risk Clients</p>
                </div>
                <i class="fas fa-exclamation-circle stat-icon"></i>
            </div>
        </a>

        <?php if(auth()->user()->canApproveRisks()): ?>
        <a href="<?php echo e(route('client-assessments.approval.index')); ?>" class="stat-card warning clickable-stat">
            <div class="stat-content">
                <div class="stat-info">
                    <h3 id="pending-client-assessments"><?php echo e($pendingClientAssessments ?? 0); ?></h3>
                    <p>Pending Client Assessments</p>
                </div>
                <i class="fas fa-clipboard-check stat-icon"></i>
            </div>
        </a>
        <?php endif; ?>

        <a href="<?php echo e(route('risks.reports', ['filter' => 'rejected'])); ?>" class="stat-card danger clickable-stat">
            <div class="stat-content">
                <div class="stat-info">
                    <h3 id="rejected-clients"><?php echo e($rejectedClients ?? 0); ?></h3>
                    <p>Rejected Clients</p>
                </div>
                <i class="fas fa-user-times stat-icon"></i>
            </div>
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
        <div class="actions-grid">
            <a href="<?php echo e(route('risk-categories.index')); ?>" class="action-btn primary">
                <i class="fas fa-folder"></i>
                <span>Add Risk</span>
            </a>
            <a href="<?php echo e(route('risks.reports')); ?>" class="action-btn info">
                <i class="fas fa-chart-bar"></i>
                <span>View Reports</span>
            </a>
            <?php if(auth()->user()->canApproveRisks()): ?>
            <a href="<?php echo e(route('risks.approval.index')); ?>" class="action-btn warning">
                <i class="fas fa-clipboard-check"></i>
                <span>Review Approvals</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Real-time dashboard functionality
let updateInterval;
let isRealTimeEnabled = true;

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    updateTime();
    setInterval(updateTime, 1000);
    
    // Enable real-time updates
    startRealTimeUpdates();
    
    // Check for success messages and refresh stats
    checkForUpdates();
    
});

// Check for success messages and refresh dashboard
function checkForUpdates() {
    // Check if we're coming from an approval/rejection action
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('updated') === 'true' || 
        window.location.href.includes('success') ||
        document.querySelector('.alert-success')) {
        // Refresh dashboard stats immediately
        refreshDashboardStats();
    }
    
    // Check for dashboard refresh flag from session
    <?php if(session('dashboard_refresh')): ?>
        refreshDashboardStats();
    <?php endif; ?>
}

function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('currentTime').textContent = timeString;
}

function startRealTimeUpdates() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
    
    // Update every 30 seconds
    updateInterval = setInterval(fetchRealTimeStats, 30000);
    
    // Initial load
    fetchRealTimeStats();
}

// Function to immediately refresh dashboard stats
function refreshDashboardStats() {
    fetchRealTimeStats();
}

function stopRealTimeUpdates() {
    if (updateInterval) {
        clearInterval(updateInterval);
        updateInterval = null;
    }
}

async function fetchRealTimeStats() {
    try {
        const response = await fetch('/api/dashboard-updates/stats');
        const result = await response.json();
        
        
        if (result.success) {
            updateDashboardStats(result.data);
            updateRecentActivities(result.data.recentActivities);
        }
    } catch (error) {
        console.error('Failed to fetch real-time stats:', error);
        showNotification('Failed to update dashboard data', 'warning');
    }
}

function updateDashboardStats(data) {
    // Update the existing dashboard statistics cards
    updateStatCard('total-risks', data.totalRisks);
    updateStatCard('active-clients', data.activeClients);
    
    updateStatCard('high-risk-clients', data.highRiskClients || data.highRisks);
    updateStatCard('overdue-items', data.overdueItems || 0);
}

function updateStatCard(elementId, value) {
    const element = document.getElementById(elementId);
    if (element) {
        // Add animation for value changes
        element.style.transform = 'scale(1.05)';
        element.textContent = value;
        
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 200);
    }
}

function updateRecentActivities(activities) {
    const activitiesContainer = document.getElementById('recent-activities');
    if (!activitiesContainer || !activities) return;
    
    activitiesContainer.innerHTML = activities.map(activity => `
        <div class="activity-item">
            <div class="activity-icon ${activity.type}">
                <i class="fas fa-${getActivityIcon(activity.type)}"></i>
            </div>
            <div class="activity-content">
                <div class="activity-title">${activity.title}</div>
                <div class="activity-description">${activity.description}</div>
                <div class="activity-time">${activity.timestamp}</div>
            </div>
        </div>
    `).join('');
}

function getActivityIcon(type) {
    const icons = {
        'risk': 'exclamation-triangle',
        'client': 'user',
        'user': 'users',
        'system': 'cog'
    };
    return icons[type] || 'circle';
}

function addRealTimeToggle() {
    // Add real-time toggle button to header
    const headerActions = document.querySelector('.header-actions');
    if (headerActions) {
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'btn-clean';
        toggleBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Real-time: ON';
        toggleBtn.id = 'real-time-toggle';
        toggleBtn.onclick = toggleRealTime;
        headerActions.appendChild(toggleBtn);
    }
}

function toggleRealTime() {
    const toggleBtn = document.getElementById('real-time-toggle');
    
    if (isRealTimeEnabled) {
        stopRealTimeUpdates();
        isRealTimeEnabled = false;
        toggleBtn.innerHTML = '<i class="fas fa-pause me-2"></i>Real-time: OFF';
        toggleBtn.style.opacity = '0.6';
    } else {
        startRealTimeUpdates();
        isRealTimeEnabled = true;
        toggleBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Real-time: ON';
        toggleBtn.style.opacity = '1';
    }
}

function refreshDashboard() {
    const refreshBtn = event.target;
    const originalText = refreshBtn.innerHTML;
    
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
    refreshBtn.disabled = true;
    
    // Simple page refresh instead of API call
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Performance monitoring
async function fetchLiveMetrics() {
    try {
        const response = await fetch('/api/dashboard-updates/live-metrics');
        const result = await response.json();
        
        if (result.success) {
            updatePerformanceMetrics(result.data);
        }
    } catch (error) {
        console.error('Failed to fetch live metrics:', error);
    }
}

function updatePerformanceMetrics(metrics) {
    // Update performance indicators if they exist
    const memoryElement = document.getElementById('memory-usage');
    if (memoryElement) {
        memoryElement.textContent = `${metrics.memory_usage_percent}%`;
    }
    
    const cacheElement = document.getElementById('cache-efficiency');
    if (cacheElement) {
        cacheElement.textContent = `${metrics.cache_efficiency}%`;
    }
}

// Clear caches function
async function clearAllCaches() {
    if (!confirm('Are you sure you want to clear all caches? This may temporarily slow down the system.')) {
        return;
    }
    
    try {
        const response = await fetch('/api/dashboard-updates/clear-all-caches', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('All caches cleared successfully', 'success');
            // Refresh dashboard after clearing caches
            setTimeout(fetchRealTimeStats, 1000);
        } else {
            showNotification('Failed to clear caches', 'error');
        }
    } catch (error) {
        console.error('Failed to clear caches:', error);
        showNotification('Failed to clear caches', 'error');
    }
}


// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    stopRealTimeUpdates();
});

// Quick Guide Collapse Toggle - Rotate chevron
let guideExpanded = false;

function toggleGuideChevron() {
    const guideChevron = document.getElementById('guideChevron');
    if (guideChevron) {
        guideExpanded = !guideExpanded;
        if (guideExpanded) {
            guideChevron.style.transform = 'rotate(180deg)';
        } else {
            guideChevron.style.transform = 'rotate(0deg)';
        }
    }
}

// Also listen to Bootstrap collapse events for edge cases
document.addEventListener('DOMContentLoaded', function() {
    const quickGuideContent = document.getElementById('quickGuideContent');
    const guideChevron = document.getElementById('guideChevron');
    
    if (quickGuideContent && guideChevron) {
        quickGuideContent.addEventListener('shown.bs.collapse', function() {
            guideExpanded = true;
            guideChevron.style.transform = 'rotate(180deg)';
        });
        
        quickGuideContent.addEventListener('hidden.bs.collapse', function() {
            guideExpanded = false;
            guideChevron.style.transform = 'rotate(0deg)';
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\well-known\resources\views/dashboard.blade.php ENDPATH**/ ?>