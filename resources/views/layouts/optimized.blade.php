@php
    // Helper function to generate two initials from full name
    function getInitials($name) {
        if (empty($name) || $name === 'U') {
            return 'U';
        }
        
        $nameParts = array_filter(explode(' ', trim($name)));
        
        if (count($nameParts) >= 2) {
            // Take first letter of first name and first letter of last name
            return strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        } else {
            // If only one name part, take first two letters
            return strtoupper(substr($name, 0, 2));
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Internal System - No SEO Required -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, nofollow">
    <meta name="description" content="Internal DCS Client Acceptance & Retention Risk Register System">
    <meta name="author" content="DCS Internal Systems">
    
    <title>@yield('title', 'DCS - Client Acceptance & Retention Risk Register')</title>
    
    <!-- Critical CSS - Inline for fastest loading -->
    <style>
        /* Critical CSS - Above the fold */
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; line-height: 1.6; color: #333; }
        .navbar { background: #00073d; color: white; padding: 1rem; position: sticky; top: 0; z-index: 1000; }
        .page-header { background: linear-gradient(135deg, #00073d, #001a5c); color: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; border-radius: 8px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .btn { display: inline-block; padding: 0.5rem 1rem; border: none; border-radius: 4px; text-decoration: none; cursor: pointer; font-weight: 500; transition: all 0.2s; }
        .btn-primary { background: #00073d; color: white; }
        .btn-primary:hover { background: #001a5c; color: white; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #dee2e6; }
        .table th { background: #f8f9fa; font-weight: 600; }
        .container-fluid { padding: 0 1rem; }
        .loading { opacity: 0.6; pointer-events: none; }
        .fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .container-fluid { padding: 0 0.5rem; }
            .page-header { padding: 1rem; margin-bottom: 1rem; }
        }
    </style>
    
    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('assets/optimized/app.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/optimized/app.min.css') }}"></noscript>
    
    <!-- Preload fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>
    
    <!-- DCS Logo Colors - Critical -->
    <link href="{{ asset('logo/logo-colors.css') }}" rel="stylesheet">
    
    <!-- Font Awesome - Load asynchronously -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
    
    <!-- Performance monitoring -->
    <script>
        // Performance monitoring
        window.performanceData = {
            start: performance.now(),
            marks: {}
        };
        
        // Mark critical points
        window.performanceData.marks.domContentLoaded = performance.now();
        
        // Lazy load non-critical resources
        function loadNonCriticalResources() {
            // Load Bootstrap asynchronously
            const bootstrapLink = document.createElement('link');
            bootstrapLink.rel = 'stylesheet';
            bootstrapLink.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';
            bootstrapLink.media = 'print';
            bootstrapLink.onload = function() { this.media = 'all'; };
            document.head.appendChild(bootstrapLink);
        }
        
        // Load non-critical resources after page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', loadNonCriticalResources);
        } else {
            loadNonCriticalResources();
        }
    </script>
</head>
<body class="fade-in">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('logo/logo.png') }}" alt="DCS Logo" height="32" width="32" class="me-2">
                <span class="fw-bold">DCS</span>
            </div>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clients.index') }}">
                            <i class="fas fa-users me-1"></i>Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('risks.index') }}">
                            <i class="fas fa-exclamation-triangle me-1"></i>Risks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('risks.reports') }}">
                            <i class="fas fa-chart-bar me-1"></i>Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('risks.settings') }}">
                            <i class="fas fa-cog me-1"></i>Settings
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell me-1"></i>
                            <span class="badge bg-danger">1</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">New notification</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-envelope me-1"></i>
                            <span class="badge bg-primary">0</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">No new messages</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-1">
                                {{ getInitials(auth()->user()->name ?? 'U') }}
                            </div>
                            {{ auth()->user()->name ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <!-- Main Content -->
    <div class="container-fluid">
        @yield('content')
    </div>
    
    <!-- Loading indicator -->
    <div id="loading-indicator" class="loading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; align-items: center; justify-content: center;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    
    <!-- Scripts - Load asynchronously -->
    <script>
        // Load scripts asynchronously
        function loadScript(src, callback) {
            const script = document.createElement('script');
            script.src = src;
            script.async = true;
            if (callback) script.onload = callback;
            document.head.appendChild(script);
        }
        
        // Load critical scripts first
        loadScript('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', function() {
            // Load app scripts after Bootstrap
            loadScript('{{ asset("assets/optimized/app.min.js") }}');
        });
        
        // Performance monitoring
        window.addEventListener('load', function() {
            window.performanceData.marks.pageLoad = performance.now();
            // console.log('Page load time:', window.performanceData.marks.pageLoad - window.performanceData.start, 'ms');
        });
        
        // Global loading indicator
        window.showLoading = function() {
            document.getElementById('loading-indicator').style.display = 'flex';
        };
        
        window.hideLoading = function() {
            document.getElementById('loading-indicator').style.display = 'none';
        };
        
        // Auto-hide loading after 3 seconds max
        setTimeout(function() {
            window.hideLoading();
        }, 3000);
    </script>
    
    @yield('scripts')
</body>
</html>
