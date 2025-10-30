<?php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Internal System - No SEO Required -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, nofollow">
    <meta name="description" content="Internal DCS Client Acceptance & Retention Risk Register System">
    <meta name="author" content="DCS Internal Systems">
    
    <title><?php echo $__env->yieldContent('title', 'DCS - Client Acceptance & Retention Risk Register'); ?></title>
    
    <!-- Bootstrap CSS - Load asynchronously -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></noscript>
    
    <!-- Font Awesome - Load asynchronously -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
    
    <!-- Optimized CSS for resource management -->
    <link href="<?php echo e(asset('css/optimized.css')); ?>" rel="stylesheet">
    
    <!-- Font Awesome Fallback -->
    <script>
        // Check if Font Awesome loaded properly
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const testIcon = document.createElement('i');
                testIcon.className = 'fas fa-check';
                testIcon.style.position = 'absolute';
                testIcon.style.left = '-9999px';
                document.body.appendChild(testIcon);
                
                const computedStyle = window.getComputedStyle(testIcon);
                const fontFamily = computedStyle.getPropertyValue('font-family');
                
                if (!fontFamily.includes('Font Awesome')) {
                    console.warn('Font Awesome not loaded properly, applying fallback styles');
                    // Apply fallback styles to all icon elements
                    const style = document.createElement('style');
                    style.textContent = `
                        .fas, .far, .fal, .fab, .fa {
                            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "FontAwesome", sans-serif !important;
                            font-weight: 900 !important;
                            display: inline-block !important;
                            text-rendering: auto !important;
                            -webkit-font-smoothing: antialiased !important;
                        }
                    `;
                    document.head.appendChild(style);
                }
                
                document.body.removeChild(testIcon);
            }, 1000);
        });
    </script>
    
    <!-- DCS Logo Colors -->
    <link href="<?php echo e(asset('logo/logo-colors.css')); ?>" rel="stylesheet">
    
    <!-- Matrix Styles -->
    <link href="<?php echo e(asset('css/matrix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    
    <!-- Responsive Design System -->
    <link href="<?php echo e(asset('css/responsive.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    
    <!-- Font Awesome Fix -->
    <link href="<?php echo e(asset('css/font-awesome-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --bs-primary: var(--logo-dark-blue-primary);
            --bs-primary-rgb: 0, 7, 45;
            --bs-link-color: var(--logo-dark-blue-primary);
            --bs-link-hover-color: var(--logo-dark-blue-hover);
        }
        
        /* Font Awesome Icon Fixes */
        .fas, .far, .fal, .fab, .fa {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "FontAwesome", sans-serif !important;
            font-weight: 900 !important;
            display: inline-block !important;
            text-rendering: auto !important;
            -webkit-font-smoothing: antialiased !important;
            font-style: normal !important;
            font-variant: normal !important;
            line-height: 1 !important;
        }
        
        .far {
            font-weight: 400 !important;
        }
        
        .fal {
            font-weight: 300 !important;
        }
        
        /* DCS Brand Button Overrides */
        .btn-primary {
            background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
            border: 1px solid var(--logo-dark-blue-primary);
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 7, 45, 0.2);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--logo-dark-blue-secondary), var(--logo-dark-blue-primary));
            border-color: var(--logo-dark-blue-secondary);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 7, 45, 0.3);
        }
        
        .btn-primary:focus {
            background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
            border-color: var(--logo-dark-blue-primary);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(0, 7, 45, 0.25);
        }
        
        .btn-primary:active {
            background: linear-gradient(135deg, var(--logo-dark-blue-secondary), var(--logo-dark-blue-primary));
            border-color: var(--logo-dark-blue-secondary);
            color: white;
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 7, 45, 0.2);
        }
        
        body {
            background-color: var(--logo-light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            box-sizing: border-box;
        }
        
        /* Ensure body and html take full width */
        html, body {
            width: 100%;
            height: 100%;
        }
        
        /* Force responsive behavior */
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        
        /* Prevent layout shifts and ensure proper responsive behavior */
        html {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }
        
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-smoothing: antialiased;
        }
        
        /* Force layout recalculation on window resize */
        .main-content-wrapper,
        .sidebar,
        .top-bar {
            will-change: auto;
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
        }
        
        /* Ensure responsive containers work properly */
        .container-fluid,
        .main-content-wrapper,
        .main-content {
            min-width: 0;
            flex-shrink: 1;
        }
        
        /* Ensure sidebar is visible on desktop screens */
        @media (min-width: 1025px) {
            .sidebar {
                visibility: visible !important;
                transform: translateX(0) !important;
                display: flex !important;
            }
        }
        
        /* NO FLICKERING SCROLL OPTIMIZATIONS */
        html {
            scroll-behavior: auto !important;
            -webkit-overflow-scrolling: touch;
            overflow-x: hidden;
            height: 100%;
            position: relative;
        }
        
        body {
            overflow: hidden !important;
            -webkit-overflow-scrolling: touch;
            transform: translateZ(0);
            backface-visibility: hidden;
            height: 100vh;
            position: fixed;
            width: 100vw;
            margin: 0;
            padding: 0;
        }
        
        /* Prevent any scroll from affecting sidebar */
        .sidebar {
            position: fixed !important;
        }
        
        .sidebar {
            position: fixed !important;
            transform: translate3d(0, 0, 0) !important;
            overflow: hidden !important;
        }
        
        /* Completely prevent sidebar from moving or scrolling */
        .sidebar,
        .sidebar::before,
        .sidebar::after,
        .sidebar *,
        .sidebar *::before,
        .sidebar *::after {
            transform: none !important;
            animation: none !important;
            transition: none !important;
            overflow: hidden !important;
            scroll-behavior: auto !important;
            -webkit-overflow-scrolling: auto !important;
            scroll-snap-type: none !important;
            overscroll-behavior: none !important;
        }
        
        .sidebar {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
        }
        
        /* Force static rendering */
        .main-content-wrapper {
            transform: translateZ(0) !important;
            backface-visibility: hidden !important;
            will-change: auto !important;
        }
        
        /* Prevent layout shifts and improve scroll performance */
        .main-content-wrapper,
        .main-content,
        .container-fluid,
        .row,
        .col-12,
        .card,
        .table-responsive {
            will-change: auto;
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }
        
        /* COMPLETELY DISABLE ALL ANIMATIONS TO PREVENT FLICKERING */
        .main-content *,
        .main-content *:before,
        .main-content *:after {
            transition: none !important;
            animation: none !important;
            transform: none !important;
            will-change: auto !important;
        }
        
        /* Force static positioning for scroll performance */
        .main-content .card,
        .main-content .table,
        .main-content .form-control,
        .main-content .btn,
        .main-content .nav-link,
        .main-content .dropdown-item,
        .main-content .table tbody tr,
        .main-content .stat-card {
            transform: translateZ(0) !important;
            backface-visibility: hidden !important;
            perspective: 1000px !important;
            -webkit-transform: translateZ(0) !important;
            -webkit-backface-visibility: hidden !important;
        }
        
        /* Disable hover effects that cause flickering */
        .main-content .btn:hover,
        .main-content .nav-link:hover,
        .main-content .dropdown-item:hover,
        .main-content .card:hover,
        .main-content .stat-card:hover,
        .main-content .table tbody tr:hover {
            transition: none !important;
            animation: none !important;
            transform: translateZ(0) !important;
        }
        
        /* Optimize table scrolling */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        
        .table-responsive::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .table-responsive::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 3px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
        
        .sidebar {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            height: 100vh !important;
            width: 260px !important;
            background: linear-gradient(180deg, var(--logo-dark-blue-primary) 0%, var(--logo-dark-blue-secondary) 100%);
            color: var(--logo-white);
            overflow: hidden !important;
            z-index: 1030 !important;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15) !important;
            display: block !important;
            box-sizing: border-box;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar.collapsed .sidebar-header h4,
        .sidebar.collapsed .sidebar-header p {
            display: none;
        }
        
        .sidebar.collapsed .nav-link {
            padding: 0.875rem 1rem;
            text-align: center;
        }
        
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        
        .sidebar.collapsed .nav-link i {
            font-size: 1.2rem;
            margin-right: 0;
        }
        
        .sidebar.collapsed .mt-auto p-3 {
            display: none;
        }
        
        .sidebar-header {
            padding: 1rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            flex-shrink: 0;
        }
        
        .sidebar-header img {
            width: 100px;
            height: auto;
            margin-bottom: 0.75rem;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.2));
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-weight: normal;
            color: rgba(255,255,255,0.7);
            font-size: 0.875rem;
            letter-spacing: 0px;
            line-height: 1.2;
            text-align: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .sidebar-header p {
            margin: 0.25rem 0 0 0;
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
            font-weight: 300;
            line-height: 1.2;
        }
        
        .nav-item {
            margin: 0.1rem 0.75rem;
            list-style: none;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            padding: 0.6rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            display: block;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 0.8rem;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: rgba(255,255,255,0.1);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::before {
            width: 100%;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.12) !important;
            color: var(--logo-white) !important;
            text-decoration: none;
            transform: translateX(4px);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.15) !important;
            border-left: 3px solid #ffffff;
            font-weight: 600;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 12px;
            color: var(--logo-white);
            font-size: 1rem;
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "FontAwesome", sans-serif;
            font-weight: 900;
            display: inline-block;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
        }
        
        .main-content-wrapper {
            margin-left: 260px !important;
            width: calc(100vw - 260px) !important;
            height: 100vh !important;
            position: fixed !important;
            right: 0 !important;
            top: 0 !important;
            z-index: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            transform: none !important;
            backface-visibility: hidden !important;
            will-change: auto !important;
            transition: none !important;
            max-width: none;
            box-sizing: border-box;
        }
        
        .main-content {
            padding: 1rem 1.5rem;
            min-height: 100vh;
            background: #f8fafc;
            width: 100%;
            max-width: none;
            overflow-x: hidden;
            box-sizing: border-box;
        }
        
        .main-content-wrapper.expanded {
            margin-left: 70px;
            width: calc(100% - 70px);
        }
        
        .top-bar {
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            box-shadow: 0 1px 10px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid rgba(0,0,0,0.04);
            width: 100%;
            max-width: 100%;
            min-height: 60px;
        }
        
        /* Compact Profile Circle Styles */
        .compact-profile {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex: 1;
        }
        
        .profile-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }
        
        .profile-circle:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 7, 45, 0.3);
            border-color: var(--logo-dark-blue-primary);
        }
        
        .profile-circle .notification-indicator {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 18px;
            height: 18px;
            background: #ef4444;
            border-radius: 50%;
            color: white;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Enhanced Profile Dropdown */
        .profile-dropdown {
            min-width: 280px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 0;
            overflow: hidden;
        }
        
        .profile-dropdown .dropdown-header {
            background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
            color: white;
            padding: 1.5rem 1.25rem;
            text-align: center;
            border-bottom: none;
            position: relative;
            overflow: hidden;
        }
        
        .profile-dropdown .dropdown-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%, rgba(255,255,255,0.1) 100%);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
            100% { transform: translateX(100%); }
        }
        
        .profile-dropdown .user-info {
            text-align: center;
        }
        
        .profile-dropdown .user-avatar-large {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.5rem;
            margin: 0 auto 0.75rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .profile-dropdown .user-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .profile-dropdown .user-role {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .profile-dropdown .dropdown-divider {
            margin: 0;
            border-color: #e5e7eb;
        }
        
        .profile-dropdown .dropdown-item {
            padding: 0.875rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            color: #374151;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .profile-dropdown .dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
            transition: width 0.3s ease;
            z-index: 0;
        }
        
        .profile-dropdown .dropdown-item:hover {
            background: linear-gradient(90deg, rgba(0, 7, 45, 0.05), rgba(0, 7, 45, 0.02));
            color: var(--logo-dark-blue-primary);
            transform: translateX(4px);
            box-shadow: inset 3px 0 0 var(--logo-dark-blue-primary);
        }
        
        .profile-dropdown .dropdown-item:hover::before {
            width: 3px;
        }
        
        .profile-dropdown .dropdown-item:hover i {
            transform: scale(1.1);
            color: var(--logo-dark-blue-primary);
        }
        
        .profile-dropdown .dropdown-item i {
            width: 18px;
            text-align: center;
            color: var(--logo-dark-blue-primary);
            transition: all 0.3s ease;
            z-index: 1;
            position: relative;
        }
        
        .profile-dropdown .dropdown-item span {
            z-index: 1;
            position: relative;
        }
        
        .profile-dropdown .dropdown-item.logout {
            color: #ef4444;
            border-top: 1px solid #fecaca;
            margin-top: 0.5rem;
        }
        
        .profile-dropdown .dropdown-item.logout:hover {
            background: linear-gradient(90deg, rgba(239, 68, 68, 0.05), rgba(239, 68, 68, 0.02));
            color: #dc2626;
            transform: translateX(4px);
            box-shadow: inset 3px 0 0 #ef4444;
        }
        
        .profile-dropdown .dropdown-item.logout:hover::before {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }
        
        .profile-dropdown .dropdown-item.logout i {
            color: #ef4444;
            transition: all 0.3s ease;
        }
        
        .profile-dropdown .dropdown-item.logout:hover i {
            transform: scale(1.1);
            color: #dc2626;
        }
        
        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar-toggle-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.9rem;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }
        
        .sidebar-toggle-btn:hover {
            background: var(--logo-dark-blue-primary);
            color: white;
            border-color: var(--logo-dark-blue-primary);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 7, 45, 0.2);
        }
        
        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .top-bar-icon {
            position: relative;
            background: #f8fafc;
            border: 1px solid transparent;
            color: #64748b;
            font-size: 1rem;
            padding: 0.6rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
        }
        
        .top-bar-icon:hover {
            background: var(--logo-dark-blue-primary);
            color: white;
            border-color: var(--logo-dark-blue-primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 7, 45, 0.25);
        }
        
        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }
        
        /* Notification Dropdown Styles */
        .notification-dropdown {
            min-width: 350px;
            max-width: 400px;
            max-height: 500px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            padding: 0;
            overflow: hidden;
        }
        
        .notification-header {
            background: var(--logo-dark-blue-primary);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .notification-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-mark-all-read,
        .btn-clear-all {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-mark-all-read:hover,
        .btn-clear-all:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }
        
        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid var(--logo-border-light);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .notification-item:hover {
            background: #f8fafc;
        }
        
        .notification-item.unread {
            background: rgba(0, 7, 45, 0.02);
            border-left: 3px solid var(--logo-dark-blue-primary);
        }
        
        .notification-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .notification-icon.message {
            background: rgba(8, 145, 178, 0.1);
            color: var(--logo-info);
        }
        
        .notification-icon.risk {
            background: rgba(220, 38, 38, 0.1);
            color: var(--logo-danger);
        }
        
        .notification-icon.client {
            background: rgba(22, 163, 74, 0.1);
            color: var(--logo-success);
        }
        
        .notification-icon.system {
            background: rgba(202, 138, 4, 0.1);
            color: var(--logo-warning);
        }
        
        .notification-content {
            flex: 1;
            min-width: 0;
        }
        
        .notification-item-title {
            font-weight: 600;
            color: var(--logo-text-dark);
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }
        
        .notification-item-message {
            color: var(--logo-text-medium);
            font-size: 0.8rem;
            line-height: 1.4;
            margin-bottom: 0.25rem;
        }
        
        .notification-item-time {
            color: var(--logo-text-muted);
            font-size: 0.75rem;
        }
        
        .notification-priority {
            display: inline-block;
            padding: 0.125rem 0.375rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-left: 0.5rem;
        }
        
        .notification-priority.urgent {
            background: rgba(220, 38, 38, 0.1);
            color: var(--logo-danger);
        }
        
        .notification-priority.high {
            background: rgba(202, 138, 4, 0.1);
            color: var(--logo-warning);
        }
        
        .notification-priority.normal {
            background: rgba(0, 7, 45, 0.1);
            color: var(--logo-dark-blue-primary);
        }
        
        .notification-priority.low {
            background: rgba(107, 114, 128, 0.1);
            color: var(--logo-text-muted);
        }
        
        .notification-footer {
            padding: 1rem;
            text-align: center;
            background: #f8fafc;
            border-top: 1px solid var(--logo-border-light);
        }
        
        .view-all-notifications {
            color: var(--logo-dark-blue-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .view-all-notifications:hover {
            color: var(--logo-dark-blue-hover);
            text-decoration: none;
        }
        
        .notification-loading {
            padding: 2rem;
            text-align: center;
            color: var(--logo-text-muted);
        }
        
        .notification-empty {
            padding: 2rem;
            text-align: center;
            color: var(--logo-text-muted);
        }
        
        .notification-empty i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--logo-border-light);
        }
        
        .user-profile-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
        }
        
        .user-profile-section:hover {
            background: #f8fafc;
            border-color: rgba(0, 7, 45, 0.1);
            transform: translateY(-1px);
            text-decoration: none;
        }
        
        .user-dropdown-arrow {
            font-size: 0.7rem;
            color: var(--logo-text-muted);
            transition: transform 0.3s ease;
        }
        
        .user-profile-section[aria-expanded="true"] .user-dropdown-arrow {
            transform: rotate(180deg);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
            color: var(--logo-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 7, 45, 0.15);
        }
        
        .user-info-text {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
            margin: 0;
            line-height: 1.2;
        }
        
        .user-role {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0;
            line-height: 1.2;
        }
        
        /* User Profile Dropdown Styles */
        .user-profile-dropdown {
            min-width: 280px;
            max-width: 320px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            padding: 0;
            overflow: hidden;
            margin-top: 0.5rem;
        }
        
        .user-profile-header {
            background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
            color: white;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-profile-avatar {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.2);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .user-profile-info {
            flex: 1;
            min-width: 0;
        }
        
        .user-profile-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
            color: white;
        }
        
        .user-profile-email {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 0.25rem;
            word-break: break-all;
        }
        
        .user-profile-role {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.7);
            background: rgba(255,255,255,0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
        }
        
        .user-profile-menu {
            background: white;
            padding: 0.5rem 0;
        }
        
        .user-profile-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: var(--logo-text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .user-profile-item:hover {
            background: #f8fafc;
            color: var(--logo-dark-blue-primary);
            text-decoration: none;
        }
        
        .user-profile-item i {
            width: 16px;
            color: var(--logo-text-muted);
            font-size: 0.9rem;
        }
        
        .user-profile-item:hover i {
            color: var(--logo-dark-blue-primary);
        }
        
        .user-profile-divider {
            height: 1px;
            background: var(--logo-border-light);
            margin: 0.5rem 0;
        }
        
        /* Modal Content Styles */
        .profile-info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--logo-border-light);
        }
        
        .profile-info-item:last-child {
            border-bottom: none;
        }
        
        .profile-info-item label {
            font-weight: 600;
            color: var(--logo-text-dark);
            margin: 0;
        }
        
        .profile-info-item span {
            color: var(--logo-text-medium);
        }
        
        .role-badge {
            background: var(--logo-dark-blue-primary);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        
        .settings-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--logo-border-light);
        }
        
        .settings-item:last-child {
            border-bottom: none;
        }
        
        .preference-item {
            margin-bottom: 1rem;
        }
        
        .preference-item label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--logo-text-dark);
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--logo-border-light);
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-item i {
            font-size: 1.2rem;
        }
        
        .activity-title {
            font-weight: 600;
            color: var(--logo-text-dark);
            margin-bottom: 0.25rem;
        }
        
        .activity-time {
            font-size: 0.8rem;
            color: var(--logo-text-muted);
        }
        
        .help-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .help-list li {
            margin-bottom: 0.5rem;
        }
        
        .help-list a {
            color: var(--logo-dark-blue-primary);
            text-decoration: none;
        }
        
        .help-list a:hover {
            text-decoration: underline;
        }
        
        .about-info {
            margin-top: 1rem;
        }
        
        .about-item {
            margin-bottom: 0.5rem;
            color: var(--logo-text-medium);
        }
        
        .sidebar-toggle-btn {
            background: var(--logo-dark-blue-primary);
            color: var(--logo-white);
            border: none;
            padding: 0.6rem;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            box-shadow: 0 2px 8px rgba(0, 7, 45, 0.15);
        }
        
        .sidebar-toggle-btn:hover {
            background: var(--logo-dark-blue-hover);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 7, 45, 0.3);
        }
        
        .sidebar-toggle-btn:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(0, 7, 45, 0.2);
        }
        
        /* Modal fixes */
        .modal {
            z-index: 1055 !important;
        }
        
        .modal-backdrop {
            z-index: 1050 !important;
        }
        
        /* Ensure modals are above everything */
        .modal.show {
            z-index: 1055 !important;
        }
        
        /* Fix modal backdrop issues */
        body.modal-open {
            overflow: hidden !important;
        }
        
        body.modal-open .modal-backdrop {
            z-index: 1050 !important;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        /* Only hide sidebar completely on mobile */
        @media (max-width: 1024px) {
            .sidebar.collapsed {
                transform: translateX(-100%);
                width: 60vw;
            }
        }
        
        .main-content-wrapper.expanded {
            margin-left: 70px;
            width: calc(100% - 70px);
        }
        
        /* Only expand to full width on mobile */
        @media (max-width: 1024px) {
            .main-content-wrapper.expanded {
                margin-left: 0;
                width: 100%;
            }
        }
        
        .sidebar-toggle-btn .fa-bars {
            transition: transform 0.3s ease;
        }
        
        .sidebar-toggle-btn .fa-times {
            transform: rotate(180deg);
        }
        
        .logout-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            box-shadow: 0 1px 6px rgba(239, 68, 68, 0.15);
        }
        
        .logout-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        }
        
        /* Ensure sidebar content is visible */
        .sidebar ul.nav {
            flex: 1;
            padding: 0.5rem 0;
            margin: 0;
        }
        
        /* Force visibility of navigation items */
        .sidebar .nav-item {
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
        }
        
        .sidebar .nav-link {
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        /* Active state styling */
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            border-left: 3px solid #ffffff;
        }
        
        /* Hover state styling */
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.15) !important;
            color: #ffffff !important;
        }
        
        /* Icon styling */
        .sidebar .nav-link i {
            color: #ffffff !important;
            opacity: 1 !important;
        }
        
        /* Professional Responsive Design - Amazon Quality */
        
        /* Universal Container Fixes */
        .container-fluid {
            max-width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
            margin-left: auto;
            margin-right: auto;
            box-sizing: border-box;
        }
        
        /* Responsive Table Styles */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .table {
            width: 100%;
            margin-bottom: 0;
            font-size: 0.875rem;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--logo-dark-blue-primary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--logo-dark-blue-primary);
        }
        
        .table tbody tr:hover {
            background-color: rgba(0, 7, 45, 0.05);
        }
        
        /* Card Responsive Styles */
        .card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
            max-width: 100%;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Form Responsive Styles */
        .form-control,
        .form-select {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: var(--logo-dark-blue-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 7, 45, 0.25);
        }
        
        /* Button Responsive Styles */
        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1rem;
        }
        
        /* Ultra Large Desktop Screens (4K+) */
        @media (min-width: 1920px) {
            .main-content-wrapper {
                margin-left: 260px;
                width: calc(100vw - 260px);
                max-width: none;
            }
            
            .main-content {
                padding: 1.5rem 2rem;
                max-width: none;
                margin: 0;
            }
            
            .sidebar {
                width: 260px;
            }
        }
        
        /* Large Desktop Screens */
        @media (min-width: 1600px) and (max-width: 1919px) {
            .main-content-wrapper {
                margin-left: 260px;
                width: calc(100vw - 260px);
                max-width: none;
            }
            
            .main-content {
                padding: 1.25rem 1.75rem;
                max-width: none;
                margin: 0;
            }
            
            .sidebar {
                width: 260px;
            }
        }
        
        /* Standard Desktop Screens */
        @media (max-width: 1599px) and (min-width: 1401px) {
            .main-content-wrapper {
                margin-left: 260px;
                width: calc(100vw - 260px);
                max-width: none;
            }
            
            .main-content {
                padding: 1rem 1.5rem;
                max-width: none;
                margin: 0;
            }
            
            .sidebar {
                width: 260px;
            }
        }
        
        @media (max-width: 1400px) and (min-width: 1201px) {
            .sidebar {
                width: 240px;
            }
            
            .main-content-wrapper {
                margin-left: 240px;
                width: calc(100vw - 240px);
                max-width: none;
            }
            
            .main-content {
                padding: 1rem 1.25rem;
                max-width: none;
                margin: 0;
            }
        }
        
        @media (max-width: 1200px) and (min-width: 1025px) {
            .sidebar {
                width: 220px;
            }
            
            .main-content-wrapper {
                margin-left: 220px;
                width: calc(100vw - 220px);
                max-width: none;
            }
            
            .main-content {
                padding: 1rem 1.25rem;
                max-width: none;
                margin: 0;
            }
        }
        
        @media (max-width: 1024px) and (min-width: 993px) {
            .sidebar {
                width: 200px;
                visibility: visible !important;
                transform: translateX(0) !important;
            }
            
            .main-content-wrapper {
                margin-left: 200px;
                width: calc(100vw - 200px);
                max-width: none;
            }
            
            .main-content {
                padding: 1rem 1.25rem;
                max-width: none;
                margin: 0;
            }
            
            .top-bar {
                padding: 1rem 1.25rem;
            }
            
            .page-title {
                font-size: 1.4rem;
            }
        }
        
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%) !important;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                width: 60vw !important;
                max-width: 60vw !important;
                z-index: 1040;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                overflow: hidden;
                visibility: visible;
                display: block;
            }
            
            .sidebar.show {
                transform: translateX(0) !important;
                box-shadow: 0 0 50px rgba(0,0,0,0.3);
                visibility: visible !important;
                display: block !important;
                width: 60vw !important;
                max-width: 60vw !important;
            }
            
            .main-content-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 1rem;
                padding-top: 70px;
                max-width: 100% !important;
            }
            
            /* Enhanced Mobile Container */
            .container-fluid {
                padding: 0.5rem;
                max-width: 100%;
            }
            
            /* Mobile Table Enhancements */
            .table-responsive {
                font-size: 0.8rem;
                border-radius: 8px;
                margin-bottom: 1rem;
            }
            
            .table {
                min-width: 100%;
                width: 100%;
            }
            
            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.75rem;
                white-space: nowrap;
                max-width: 120px;
            }
            
            .table th {
                font-size: 0.7rem;
                padding: 0.375rem 0.125rem;
            }
            
            /* Mobile Card Enhancements */
            .card {
                margin: 0 0 1rem 0;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            .card-header {
                padding: 0.75rem 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            /* Mobile Form Enhancements */
            .form-control,
            .form-select {
                padding: 0.75rem;
                font-size: 16px; /* Prevents zoom on iOS */
                border-radius: 8px;
            }
            
            /* Mobile Button Enhancements */
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
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 60vw !important;
                max-width: 60vw !important;
            }
            
            .sidebar.show {
                width: 60vw !important;
                max-width: 60vw !important;
            }
            
            /* Enhanced Tablet/Mobile Layout */
            .main-content {
                padding: 0.75rem;
                padding-top: 60px;
            }
            
            .container-fluid {
                padding: 0.25rem;
            }
            
            /* Tablet Table Optimizations */
            .table th,
            .table td {
                padding: 0.4rem 0.2rem;
                font-size: 0.7rem;
                max-width: 100px;
            }
            
            .table th {
                font-size: 0.65rem;
                padding: 0.3rem 0.1rem;
            }
            
            /* Tablet Card Optimizations */
            .card {
                margin: 0 0 0.75rem 0;
            }
            
            .card-header {
                padding: 0.5rem 0.75rem;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            /* Tablet Form Optimizations */
            .form-control,
            .form-select {
                padding: 0.625rem;
                font-size: 16px;
            }
            
            /* Tablet Button Optimizations */
            .btn {
                padding: 0.625rem 1.25rem;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar {
                width: 70vw !important;
                max-width: 70vw !important;
            }
            
            .sidebar.show {
                width: 70vw !important;
                max-width: 70vw !important;
            }
            
            /* Small Mobile Optimizations */
            .main-content {
                padding: 0.5rem;
                padding-top: 55px;
            }
            
            .container-fluid {
                padding: 0.125rem;
            }
            
            /* Small Mobile Table */
            .table th,
            .table td {
                padding: 0.3rem 0.1rem;
                font-size: 0.65rem;
                max-width: 80px;
            }
            
            .table th {
                font-size: 0.6rem;
                padding: 0.25rem 0.05rem;
            }
            
            /* Small Mobile Cards */
            .card-header,
            .card-body {
                padding: 0.5rem;
            }
            
            /* Small Mobile Forms */
            .form-control,
            .form-select {
                padding: 0.5rem;
                font-size: 16px;
            }
            
            /* Small Mobile Buttons */
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }
            
            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 0.7rem;
            }
        }
        
        @media (max-width: 480px) {
            .sidebar {
                width: 75vw !important;
                max-width: 75vw !important;
            }
            
            .sidebar.show {
                width: 75vw !important;
                max-width: 75vw !important;
            }
            
            /* Extra Small Mobile */
            .main-content {
                padding: 0.25rem;
                padding-top: 50px;
            }
            
            .container-fluid {
                padding: 0.0625rem;
            }
            
            /* Extra Small Mobile Table */
            .table th,
            .table td {
                padding: 0.25rem 0.05rem;
                font-size: 0.6rem;
                max-width: 60px;
            }
            
            .table th {
                font-size: 0.55rem;
                padding: 0.2rem 0.025rem;
            }
            
            /* Extra Small Mobile Cards */
            .card-header,
            .card-body {
                padding: 0.375rem;
            }
            
            /* Extra Small Mobile Forms */
            .form-control,
            .form-select {
                padding: 0.375rem;
                font-size: 16px;
            }
            
            /* Extra Small Mobile Buttons */
            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.75rem;
            }
        }
            
            /* Ensure sidebar content is visible on mobile */
            .sidebar .nav-link {
                padding: 1rem 1.5rem;
                font-size: 1.1rem;
            }
            
            .sidebar .nav-link span {
                display: inline !important;
                margin-left: 0.75rem;
            }
            
            .sidebar .sidebar-header {
                padding: 1.5rem;
                text-align: center;
            }
            
            .sidebar .sidebar-header h4 {
                font-size: 1.2rem;
                margin-top: 0.5rem;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
            
            .main-content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            .main-content {
                padding: 1.25rem 1.5rem;
                padding-top: 70px; /* Account for mobile header */
            }
            
            .top-bar {
                padding: 0.75rem 1rem;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1040;
                background: white;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .mobile-menu-btn {
                display: block !important;
                background: none;
                border: none;
                font-size: 1.5rem;
                color: var(--logo-dark-blue-primary);
                padding: 0.5rem;
                border-radius: 8px;
                transition: all 0.2s ease;
                cursor: pointer;
                z-index: 1051;
                position: relative;
            }
            
            .mobile-menu-btn:hover {
                background: rgba(0, 7, 45, 0.1);
                transform: scale(1.05);
            }
            
            .mobile-header-content {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex: 1;
                justify-content: space-between;
            }
            
            .mobile-logo {
                height: 35px;
                width: auto;
            }
            
            .mobile-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--logo-dark-blue-primary);
                margin: 0;
            }
            
            .mobile-header-left {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex: 1;
            }
            
            .mobile-actions {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-left: auto;
            }
            
            .mobile-notification-btn,
            .mobile-message-btn,
            .mobile-user-btn,
            .mobile-logout-btn {
                position: relative;
                background: #f8fafc;
                border: 1px solid transparent;
                color: #64748b;
                font-size: 1rem;
                padding: 0.6rem;
                border-radius: 10px;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 44px;
                min-height: 44px;
            }
            
            .mobile-notification-btn:hover,
            .mobile-message-btn:hover,
            .mobile-user-btn:hover,
            .mobile-logout-btn:hover {
                background: var(--logo-dark-blue-primary);
                color: white;
                border-color: var(--logo-dark-blue-primary);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0, 7, 45, 0.25);
            }
            
            .mobile-badge {
                position: absolute;
                top: -6px;
                right: -6px;
                background: #ef4444;
                color: white;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                font-size: 0.7rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 2px solid white;
            }
            
            .mobile-notification-btn,
            .mobile-message-btn {
                position: relative;
                background: none;
                border: none;
                font-size: 1.2rem;
                color: var(--logo-dark-blue-primary);
                padding: 0.5rem;
                border-radius: 8px;
                transition: all 0.2s ease;
            }
            
            .mobile-notification-btn:hover,
            .mobile-message-btn:hover {
                background: rgba(0, 7, 45, 0.1);
                transform: scale(1.05);
            }
            
            .mobile-badge {
                position: absolute;
                top: 2px;
                right: 2px;
                background: var(--logo-danger);
                color: white;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                font-size: 0.7rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
            }
            
            .mobile-user-menu {
                position: relative;
            }
            
            .mobile-user-btn {
                background: none;
                border: none;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem;
                border-radius: 8px;
                transition: all 0.2s ease;
            }
            
            .mobile-user-btn:hover {
                background: rgba(0, 7, 45, 0.1);
            }
            
            .mobile-user-avatar {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: var(--logo-dark-blue-primary);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                font-size: 0.9rem;
            }
            
            .mobile-user-name {
                font-size: 0.9rem;
                font-weight: 500;
                color: var(--logo-dark-blue-primary);
            }
            
            /* Mobile Content Improvements */
            .container-fluid {
                padding: 1rem;
            }
            
            .card {
                margin-bottom: 1rem;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            .card-header {
                padding: 1rem;
                border-bottom: 1px solid #e9ecef;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            /* Mobile Tables */
            .table-responsive {
                border-radius: 8px;
                overflow: hidden;
            }
            
            .table {
                font-size: 0.875rem;
                margin-bottom: 0;
            }
            
            .table th,
            .table td {
                padding: 0.75rem 0.5rem;
                vertical-align: middle;
            }
            
            /* Mobile Forms */
            .form-control,
            .form-select {
                font-size: 16px; /* Prevents zoom on iOS */
                padding: 0.75rem;
                border-radius: 8px;
            }
            
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
            
            /* Mobile Navigation Improvements */
            .nav-link {
                padding: 1rem 1.25rem;
                font-size: 0.95rem;
            }
            
            .nav-link i {
                font-size: 1.1rem;
                width: 24px;
            }
            
            /* Mobile Sidebar Overlay */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }
        
        @media (max-width: 576px) {
            .main-content-wrapper {
                width: 100%;
            }
            
            .main-content {
                padding: 1rem 1.25rem;
                padding-top: 60px;
            }
            
            .top-bar {
                padding: 0.5rem 1rem;
            }
            
            .mobile-title {
                font-size: 1rem;
            }
            
            .container-fluid {
                padding: 0.75rem;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.8rem;
            }
            
            .btn {
                padding: 0.625rem 1.25rem;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 480px) {
            .mobile-header-content {
                gap: 0.5rem;
            }
            
            .mobile-header-left {
                gap: 0.5rem;
            }
            
            .mobile-logo {
                height: 30px;
            }
            
            .mobile-title {
                font-size: 0.9rem;
            }
            
            .mobile-actions {
                gap: 0.5rem;
            }
            
            .mobile-notification-btn,
            .mobile-message-btn,
            .mobile-user-btn,
            .mobile-logout-btn {
                padding: 0.5rem;
                min-width: 40px;
                min-height: 40px;
            }
            
            .container-fluid {
                padding: 0.5rem;
            }
            
            .table th,
            .table td {
                padding: 0.4rem 0.2rem;
                font-size: 0.75rem;
            }
        }
        
        /* Logout Loading Spinner Overlay */
        .logout-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 7, 45, 0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            backdrop-filter: blur(10px);
        }
        
        .logout-loading-overlay.active {
            display: flex !important;
            animation: fadeIn 0.3s ease-in;
        }
        
        .logout-spinner-container {
            text-align: center;
            color: white;
            animation: scaleIn 0.5s ease-out;
        }
        
        .logout-spinner {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border: 6px solid rgba(255, 255, 255, 0.2);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .logout-spinner-text {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .logout-spinner-subtext {
            font-size: 0.9rem;
            opacity: 0.8;
            animation: fadeInOut 2s ease-in-out infinite;
        }
        
        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes scaleIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes fadeInOut {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        
        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
    </style>
    
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <!-- Logout Loading Spinner Overlay -->
    <div class="logout-loading-overlay" id="logoutLoadingOverlay" style="display: none;">
        <div class="logout-spinner-container">
            <div class="logout-spinner"></div>
            <div class="logout-spinner-text">Logging you out<span class="loading-dots"></span></div>
            <div class="logout-spinner-subtext">Please wait a moment</div>
        </div>
    </div>
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <nav class="sidebar" style="position: fixed !important; left: 0 !important; top: 0 !important; width: 260px !important; height: 100vh !important; overflow: hidden !important; z-index: 1000 !important; background: linear-gradient(180deg, #000745 0%, #001e5f 100%) !important;">
        <div class="sidebar-header">
            <img src="<?php echo e(asset('logo/logo.png')); ?>" alt="DCS Logo" onerror="this.style.display='none'">
            <h4 style="font-size: 0.875rem !important; font-weight: normal !important; color: rgba(255,255,255,0.7) !important; margin: 0 !important; line-height: 1.2 !important; letter-spacing: 0px !important; text-align: center !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;">Client Acceptance & Retention Risk Register</h4>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>" style="color: #ffffff !important; display: block !important;">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('clients.*') ? 'active' : ''); ?>" href="<?php echo e(route('clients.index')); ?>" style="color: #ffffff !important; display: block !important;">
                    <i class="fas fa-users"></i>
                    Client Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('risks.index') || request()->routeIs('risks.create') ? 'active' : ''); ?>" href="<?php echo e(route('risks.index')); ?>" style="color: #ffffff !important; display: block !important;">
                    <i class="fas fa-exclamation-triangle"></i>
                    Risk Register
                </a>
            </li>
            <?php if(auth()->user()->canManageRiskCategories()): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('risk-categories.*') ? 'active' : ''); ?>" href="<?php echo e(route('risk-categories.index')); ?>" style="color: #ffffff !important; display: block !important;">
                    <i class="fas fa-folder"></i>
                    Risk Categories
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('risks.reports') ? 'active' : ''); ?>" href="<?php echo e(route('risks.reports')); ?>" style="color: #ffffff !important; display: block !important;">
                    <i class="fas fa-chart-bar"></i>
                    Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('messages.*') ? 'active' : ''); ?>" href="<?php echo e(route('messages.index')); ?>" style="color: #ffffff !important; display: block !important;">
                    <i class="fas fa-envelope"></i>
                    Messages
                </a>
            </li>
            <?php if(auth()->user()->isManagerOrAdmin()): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>" href="<?php echo e(route('users.index')); ?>" style="color: #ffffff !important; display: block !important;">
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('risks.settings') ? 'active' : ''); ?>" href="<?php echo e(route('risks.settings')); ?>" style="color: #ffffff !important; display: block !important;">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <div class="mt-auto" style="padding: 0.75rem; border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="text-center">
                <small class="text-white-50" style="font-size: 0.7rem;">Logged in as:</small><br>
                <span class="text-white" style="font-size: 0.8rem; font-weight: 500;"><?php echo e(auth()->user()->name ?? 'User'); ?></span><br>
                <small class="text-white-50" style="font-size: 0.7rem;"><?php echo e(ucfirst(auth()->user()->role ?? 'admin')); ?></small>
            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content">
        <!-- Universal Navigation Header for All Pages -->
        <div class="top-bar">
            <!-- Mobile/Tablet Header -->
            <div class="d-lg-none mobile-header-content">
                <div class="mobile-header-left">
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <img src="<?php echo e(asset('logo/logo.png')); ?>" alt="DCS Logo" class="mobile-logo" onerror="this.style.display='none'">
                    <h1 class="mobile-title"><?php echo $__env->yieldContent('page-title', 'DCS'); ?></h1>
                </div>
                <div class="mobile-actions">
                    <!-- Mobile Notifications -->
                    <div class="dropdown">
                        <button class="mobile-notification-btn dropdown-toggle" type="button" id="mobileNotificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="mobile-badge" id="mobile-notification-count">0</span>
                        </button>
                        <div class="dropdown-menu notification-dropdown" aria-labelledby="mobileNotificationDropdown">
                            <div class="notification-header">
                                <h6 class="notification-title">Notifications</h6>
                            </div>
                            <div class="notification-list" id="mobile-notification-list">
                                <div class="notification-loading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Loading notifications...
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Messages -->
                    <button class="mobile-message-btn" title="Messages" onclick="window.location.href='<?php echo e(route('messages.index')); ?>'">
                        <i class="fas fa-envelope"></i>
                        <span class="mobile-badge" id="mobile-message-count">0</span>
                    </button>
                    
                    <!-- Mobile User Profile -->
                    <div class="dropdown">
                        <button class="mobile-user-btn dropdown-toggle" type="button" id="mobileUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="dropdown-menu user-profile-dropdown" aria-labelledby="mobileUserDropdown">
                            <div class="user-profile-header">
                                <div class="user-profile-avatar">
                                    <?php echo e(getInitials(auth()->user()->name ?? 'U')); ?>

                                </div>
                                <div class="user-profile-info">
                                    <div class="user-profile-name"><?php echo e(auth()->user()->name ?? 'User'); ?></div>
                                    <div class="user-profile-email"><?php echo e(auth()->user()->email ?? 'user@example.com'); ?></div>
                                    <div class="user-profile-role"><?php echo e(ucfirst(auth()->user()->role ?? 'admin')); ?></div>
                                </div>
                            </div>
                            <div class="user-profile-menu">
                                <a href="<?php echo e(route('users.show', auth()->id())); ?>" class="user-profile-item">
                                    <i class="fas fa-user"></i>
                                    <span>My Profile</span>
                                </a>
                                <a href="<?php echo e(route('risks.settings')); ?>" class="user-profile-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Settings</span>
                                </a>
                                <div class="user-profile-divider"></div>
                                <a href="#" class="user-profile-item" onclick="showHelpModal()">
                                    <i class="fas fa-question-circle"></i>
                                    <span>Help & Support</span>
                                </a>
                                <a href="#" class="user-profile-item" onclick="showAboutModal()">
                                    <i class="fas fa-info-circle"></i>
                                    <span>About</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Logout Button -->
                    <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;" id="mobileLogoutForm" onsubmit="showLogoutSpinner(event)">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="mobile-logout-btn" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Desktop Header -->
            <div class="d-none d-lg-flex w-100 justify-content-end">
                <!-- Compact Profile Circle -->
                <div class="compact-profile">
                    <div class="dropdown">
                        <div class="profile-circle dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Profile & Actions">
                            <?php echo e(getInitials(auth()->user()->name ?? 'U')); ?>

                            <span class="notification-indicator" id="total-notifications" style="display: none;">0</span>
                        </div>
                        
                        <div class="dropdown-menu profile-dropdown" aria-labelledby="profileDropdown">
                            <!-- Profile Header -->
                            <div class="dropdown-header">
                                <div class="user-info">
                                    <div class="user-avatar-large">
                                        <?php echo e(getInitials(auth()->user()->name ?? 'U')); ?>

                                    </div>
                                    <div class="user-name"><?php echo e(auth()->user()->name ?? 'User'); ?></div>
                                    <div class="user-role"><?php echo e(ucfirst(auth()->user()->role ?? 'admin')); ?></div>
                                </div>
                            </div>
                            
                            <!-- Profile Actions -->
                            <a href="<?php echo e(route('users.show', auth()->id())); ?>" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>My Profile</span>
                            </a>
                            
                            <a href="<?php echo e(route('risks.settings')); ?>" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                            
                            <div class="dropdown-divider"></div>
                            
                            <!-- Notifications -->
                            <a href="#" class="dropdown-item" id="view-notifications-link" onclick="event.preventDefault(); openNotificationPanel();">
                                <i class="fas fa-bell"></i>
                                <span>Notifications</span>
                                <span class="notification-badge ms-auto" id="dropdown-notification-count">0</span>
                            </a>
                            
                            <!-- Messages -->
                            <a href="<?php echo e(route('messages.index')); ?>" class="dropdown-item">
                                <i class="fas fa-envelope"></i>
                                <span>Messages</span>
                                <span class="notification-badge ms-auto" id="dropdown-message-count">1</span>
                            </a>
                            
                            <div class="dropdown-divider"></div>
                            
                            <!-- Help & Support -->
                            <div class="dropdown-item" onclick="showHelpModal()" style="cursor: pointer;">
                                <i class="fas fa-question-circle"></i>
                                <span>Help & Support</span>
                                <small class="ms-auto text-muted">+264 82 403 2391</small>
                            </div>
                            
                            <div class="dropdown-item" onclick="showAboutModal()" style="cursor: pointer;">
                                <i class="fas fa-info-circle"></i>
                                <span>About</span>
                            </div>
                            
                            <div class="dropdown-divider"></div>
                            
                            <!-- Logout -->
                            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;" id="desktopLogoutForm" onsubmit="showLogoutSpinner(event)">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item logout" style="width: 100%; border: none; background: none; text-align: left;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <!-- Scripts - Load asynchronously for better performance -->
    <script>
        // Load scripts asynchronously
        function loadScript(src, callback) {
            const script = document.createElement('script');
            script.src = src;
            script.async = true;
            if (callback) script.onload = callback;
            document.head.appendChild(script);
        }
        
        // Load Bootstrap first, then app scripts
        loadScript('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', function() {
            // Load app scripts after Bootstrap
            loadScript('<?php echo e(asset("assets/optimized/app.min.js")); ?>');
        });
        
        // Performance monitoring
        window.addEventListener('load', function() {
            // Page loaded
        });
    </script>
    
    <!-- Responsive Layout Script -->
    <script>
        // Proper responsive layout management
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content-wrapper');
            
            // Only apply mobile-specific fixes on small screens
            function handleResponsiveLayout() {
                if (window.innerWidth <= 768) {
                    // Mobile: Ensure sidebar can be toggled properly
                    if (sidebar) {
                        sidebar.classList.add('mobile-sidebar');
                    }
                } else {
                    // Desktop: Normal layout
                    if (sidebar) {
                        sidebar.classList.remove('mobile-sidebar');
                    }
                }
            }
            
            // Initial call
            handleResponsiveLayout();
            
            // Handle resize events
            window.addEventListener('resize', handleResponsiveLayout);
        });
    </script>
    
    <!-- Optimized Navigation Script -->
    <script>
        // Enhanced mobile menu initialization
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Create overlay if it doesn't exist
            if (!sidebarOverlay) {
                const overlay = document.createElement('div');
                overlay.id = 'sidebarOverlay';
                overlay.className = 'sidebar-overlay';
                document.body.appendChild(overlay);
            }
            
            const overlay = document.getElementById('sidebarOverlay');
            
            if (mobileMenuBtn && sidebar) {
                // Open mobile menu (mobile only)
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Only work on mobile/tablet screens
                    if (window.innerWidth <= 1024) {
                        // Force show the sidebar with responsive width (Amazon-style)
                        const isSmallScreen = window.innerWidth <= 480;
                        const sidebarWidth = isSmallScreen ? '70vw' : '60vw';
                    
                    sidebar.style.cssText = `
                        display: block !important;
                        width: ${sidebarWidth} !important;
                        max-width: ${sidebarWidth} !important;
                        height: 100vh !important;
                        position: fixed !important;
                        top: 0 !important;
                        left: 0 !important;
                        transform: translateX(0) !important;
                        visibility: visible !important;
                        z-index: 1040 !important;
                        overflow: hidden !important;
                    `;
                    
                    sidebar.classList.add('show');
                    overlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                    }
                });
                
                // Close on overlay click
                overlay.addEventListener('click', function() {
                    closeMobileMenu();
                });
                
                // Close on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                        closeMobileMenu();
                    }
                });
                
                // Close on window resize to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 1024) {
                        closeMobileMenu();
                    }
                });
            }
            
            function closeMobileMenu() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
                
                // Reset sidebar styles
                const isSmallScreen = window.innerWidth <= 480;
                const sidebarWidth = isSmallScreen ? '70vw' : '60vw';
                
                sidebar.style.cssText = `
                    display: block;
                    width: ${sidebarWidth};
                    max-width: ${sidebarWidth};
                    height: 100vh;
                    position: fixed;
                    top: 0;
                    left: 0;
                    transform: translateX(-100%);
                    visibility: hidden;
                    z-index: 1040;
                    overflow: hidden;
                `;
            }
        });
    </script>
    
    <!-- Sidebar Toggle JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content-wrapper');
            
            if (!sidebar || !mainContent) {
                console.warn('Sidebar elements not found');
                return;
            }
            
            // Sidebar is now always visible (toggle functionality removed)
            
            // Optimized mobile/tablet menu functionality
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (mobileMenuBtn && sidebarOverlay) {
                // Close mobile sidebar function
                function closeMobileSidebar() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
                
                // Close on window resize to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 1024) {
                        closeMobileSidebar();
                    }
                });
            }
            
            // Simple mobile count sync (no observers)
            function syncMobileCounts() {
                const notificationCount = document.getElementById('notification-count');
                const messageCount = document.getElementById('message-count');
                const mobileNotificationCount = document.getElementById('mobile-notification-count');
                const mobileMessageCount = document.getElementById('mobile-message-count');
                
                if (notificationCount && mobileNotificationCount) {
                    mobileNotificationCount.textContent = notificationCount.textContent;
                }
                
                if (messageCount && mobileMessageCount) {
                    mobileMessageCount.textContent = messageCount.textContent;
                }
            }
            
            // Initial sync only
            syncMobileCounts();
            
            // Sidebar is always visible on desktop (no collapse functionality)
            
            // Load unread message count with a small delay to ensure auth is ready
            // Only load if not already loading from another page
            if (!window.messageCountUpdating) {
                setTimeout(() => {
                    loadUnreadMessageCount();
                }, 300);
            }
            
            // Load unread notification count
            setTimeout(() => {
                loadUnreadNotificationCount();
            }, 400);
            
            // Also refresh counts when the page becomes visible (user switches tabs/windows)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && !messageCountRequestInProgress) {
                    setTimeout(() => {
                        loadUnreadMessageCount();
                        loadUnreadNotificationCount();
                    }, 500);
                }
            });
            
            // Load notifications when dropdown is opened
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (notificationDropdown) {
                notificationDropdown.addEventListener('click', function() {
                    loadNotifications();
                });
            }
            
            // Update counts every 10 minutes (further reduced frequency to prevent resource exhaustion)
            setInterval(() => {
                // Only update if page is visible to save resources
                if (!document.hidden) {
                    loadUnreadMessageCount();
                    loadUnreadNotificationCount();
                }
            }, 600000); // 10 minutes instead of 5
        });
        
        // Request throttling to prevent resource exhaustion
        let messageCountRequestInProgress = false;
        let notificationCountRequestInProgress = false;
        let messageCountCache = { count: 0, timestamp: 0 };
        const MESSAGE_CACHE_DURATION = 30000; // 30 seconds cache
        
        // Optimized function to load unread message count
        function loadUnreadMessageCount() {
            // Prevent multiple simultaneous requests
            if (messageCountRequestInProgress || window.messageCountUpdating) {
                return;
            }
            
            // Only load if user is authenticated (check for CSRF token)
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                return; // User not authenticated, skip loading
            }

            // Check if we're on a page that should have messages
            const currentPath = window.location.pathname;
            const messagePages = ['/messages', '/dashboard', '/clients', '/risks'];
            const shouldLoadMessages = messagePages.some(page => currentPath.startsWith(page));
            
            if (!shouldLoadMessages) {
                return;
            }

            // Check cache first
            const now = Date.now();
            if (messageCountCache.timestamp && (now - messageCountCache.timestamp) < MESSAGE_CACHE_DURATION) {
                updateMessageCountDisplay(messageCountCache.count);
                return;
            }

            messageCountRequestInProgress = true;
            
            fetch('/messages/unread-count', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                credentials: 'same-origin' // Ensure cookies are sent
            })
            .then(response => {
                if (response.status === 401 || response.status === 403) {
                    return;
                }
                if (response.status === 404) {
                    messageCountRequestInProgress = false;
                    return;
                }
                return response.ok ? response.json() : Promise.reject('Failed');
            })
            .then(data => {
                if (!data) return; // No data if not authenticated
                
                // Cache the result
                const count = data.count || 0;
                messageCountCache = {
                    count: count,
                    timestamp: now
                };
                
                // Update the message count in the sidebar
                updateMessageCountDisplay(count);
            })
            .catch(error => {
                console.warn('Failed to load message count:', error);
                const messageCountElement = document.getElementById('message-count');
                if (messageCountElement) {
                    messageCountElement.style.display = 'none';
                }
            })
            .finally(() => {
                messageCountRequestInProgress = false;
            });
        }
        
        // Function to update message count display
        function updateMessageCountDisplay(count) {
            const messageCountElement = document.getElementById('message-count');
            const mobileMessageCountElement = document.getElementById('mobile-message-count');
            const dropdownMessageCountElement = document.getElementById('dropdown-message-count');
            
            // Update main message count
            if (messageCountElement) {
                messageCountElement.textContent = count;
                messageCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
            
            // Update mobile message count
            if (mobileMessageCountElement) {
                mobileMessageCountElement.textContent = count;
                mobileMessageCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
            
            // Update dropdown message count
            if (dropdownMessageCountElement) {
                dropdownMessageCountElement.textContent = count;
                dropdownMessageCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
        }
        
        // Make message count functions globally available
        window.updateMessageCountDisplay = updateMessageCountDisplay;
        window.loadUnreadMessageCount = loadUnreadMessageCount;
        
        // Function to refresh message count (can be called from other pages)
        window.refreshMessageCount = function() {
            if (typeof loadUnreadMessageCount === 'function') {
                loadUnreadMessageCount();
            }
        };
        
        // Function to clear all notifications
        function clearAllNotifications() {
            if (confirm('Are you sure you want to clear all notifications?')) {
                fetch('<?php echo e(route("notifications.clear-all")); ?>', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications();
                        loadUnreadNotificationCount();
                    }
                })
                .catch(error => console.error('Error clearing notifications:', error));
            }
        }
        
        // User Profile Modal Functions
        function showProfileModal() {
            fetch('<?php echo e(route("profile.show")); ?>', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const user = data.user;
                const createdDate = user.created_at ? new Date(user.created_at).toLocaleDateString('en-US', { 
                    year: 'numeric', month: 'short', day: 'numeric' 
                }) : 'N/A';
                
                showModal('Profile Information', `
                    <div class="profile-modal-content">
                        <div class="profile-info-section">
                            <h6>Personal Information</h6>
                            <div class="profile-info-item">
                                <label>Full Name:</label>
                                <span>${user.name || 'User'}</span>
                            </div>
                            <div class="profile-info-item">
                                <label>Email:</label>
                                <span>${user.email || 'user@example.com'}</span>
                            </div>
                            <div class="profile-info-item">
                                <label>Role:</label>
                                <span class="role-badge">${user.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : 'Admin'}</span>
                            </div>
                            <div class="profile-info-item">
                                <label>Member Since:</label>
                                <span>${createdDate}</span>
                            </div>
                        </div>
                    </div>
                `);
            })
            .catch(error => {
                console.error('Error loading profile:', error);
                showModal('Profile Information', '<div class="alert alert-danger">Error loading profile information.</div>');
            });
        }
        
        function showSettingsModal() {
            fetch('<?php echo e(route("profile.show")); ?>', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const preferences = data.preferences;
                showModal('Account Settings', `
                    <div class="settings-modal-content">
                        <div class="settings-section">
                            <h6>Security</h6>
                            <div class="settings-item">
                                <label>Two-Factor Authentication</label>
                                <button class="btn btn-sm ${preferences.two_factor_enabled ? 'btn-success' : 'btn-outline-primary'}" 
                                        onclick="toggleTwoFactor()">
                                    ${preferences.two_factor_enabled ? 'Disable' : 'Enable'}
                                </button>
                            </div>
                            <div class="settings-item">
                                <label>Change Password</label>
                                <button class="btn btn-sm btn-outline-primary" onclick="showPasswordChangeModal()">Change</button>
                            </div>
                        </div>
                        <div class="settings-section">
                            <h6>Notifications</h6>
                            <div class="settings-item">
                                <label>Email Notifications</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" ${preferences.email_notifications ? 'checked' : ''} 
                                           onchange="updatePreference('email_notifications', this.checked)">
                                </div>
                            </div>
                            <div class="settings-item">
                                <label>Push Notifications</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" ${preferences.push_notifications ? 'checked' : ''} 
                                           onchange="updatePreference('push_notifications', this.checked)">
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            })
            .catch(error => {
                console.error('Error loading settings:', error);
                showModal('Account Settings', '<div class="alert alert-danger">Error loading settings.</div>');
            });
        }
        
        function showPreferencesModal() {
            showModal('Preferences', `
                <div class="preferences-modal-content">
                    <div class="preferences-section">
                        <h6>Display Preferences</h6>
                        <div class="preference-item">
                            <label>Theme</label>
                            <select class="form-select form-select-sm">
                                <option>Light</option>
                                <option>Dark</option>
                                <option>Auto</option>
                            </select>
                        </div>
                        <div class="preference-item">
                            <label>Language</label>
                            <select class="form-select form-select-sm">
                                <option>English</option>
                                <option>Spanish</option>
                                <option>French</option>
                            </select>
                        </div>
                        <div class="preference-item">
                            <label>Time Zone</label>
                            <select class="form-select form-select-sm">
                                <option>UTC-5 (EST)</option>
                                <option>UTC-8 (PST)</option>
                                <option>UTC+0 (GMT)</option>
                            </select>
                        </div>
                    </div>
                </div>
            `);
        }
        
        function showActivityModal() {
            fetch('<?php echo e(route("profile.activity")); ?>', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const activities = data.data || [];
                const activityHtml = activities.length > 0 ? 
                    activities.map(activity => `
                        <div class="activity-item">
                            <i class="${activity.action_icon} ${activity.action_color}"></i>
                            <div>
                                <div class="activity-title">${activity.description}</div>
                                <div class="activity-time">${activity.time_ago}</div>
                            </div>
                        </div>
                    `).join('') : 
                    '<div class="text-center text-muted">No recent activity</div>';
                
                showModal('Activity Log', `
                    <div class="activity-modal-content">
                        <div class="activity-list">
                            ${activityHtml}
                        </div>
                    </div>
                `);
            })
            .catch(error => {
                console.error('Error loading activity:', error);
                showModal('Activity Log', '<div class="alert alert-danger">Error loading activity log.</div>');
            });
        }
        
        function showHelpModal() {
            fetch('<?php echo e(route("profile.help-info")); ?>', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                showModal('Help & Support', `
                    <div class="help-modal-content">
                        <div class="help-section">
                            <h6>Getting Started</h6>
                            <ul class="help-list">
                                <li><a href="${data.documentation_url || '#'}" target="_blank">User Guide</a></li>
                                <li><a href="${data.video_tutorials || '#'}" target="_blank">Video Tutorials</a></li>
                                <li><a href="${data.faq_url || '#'}" target="_blank">FAQ</a></li>
                            </ul>
                        </div>
                        <div class="help-section">
                            <h6>Support</h6>
                            <ul class="help-list">
                                <li><a href="mailto:support@dcs.com">Contact Support</a></li>
                                <li><a href="mailto:support@dcs.com?subject=Bug Report">Report Bug</a></li>
                                <li><a href="mailto:support@dcs.com?subject=Feature Request">Feature Request</a></li>
                            </ul>
                        </div>
                        <div class="help-section">
                            <h6>Contact Information</h6>
                            <div class="about-info">
                                <div class="about-item">
                                    <strong>Company:</strong> DCS (Don Consulting Services)
                                </div>
                                <div class="about-item">
                                    <strong>Phone:</strong> <a href="tel:+264824032391">+264 82 403 2391</a>
                                </div>
                                <div class="about-item">
                                    <strong>Email:</strong> <a href="mailto:support@dcs.com">support@dcs.com</a>
                                </div>
                                <div class="about-item">
                                    <strong>Business Hours:</strong> ${data.business_hours || 'Monday - Friday, 8:00 AM - 5:00 PM'}
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            })
            .catch(error => {
                console.error('Error loading help info:', error);
                showModal('Help & Support', `
                    <div class="help-modal-content">
                        <div class="help-section">
                            <h6>Getting Started</h6>
                            <p>This is the DCS Risk Register system for managing client acceptance and retention risks.</p>
                        </div>
                        <div class="help-section">
                            <h6>Support</h6>
                            <div class="contact-info">
                                <p><strong>Phone:</strong> <a href="tel:+264824032391">+264 82 403 2391</a></p>
                                <p><strong>Email:</strong> <a href="mailto:support@dcs.com">support@dcs.com</a></p>
                                <p><strong>Company:</strong> DCS (Don Consulting Services)</p>
                            </div>
                        </div>
                    </div>
                `);
            });
        }
        
        function showAboutModal() {
            fetch('<?php echo e(route("profile.system-info")); ?>', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                showModal('About DCS', `
                    <div class="about-modal-content">
                        <div class="about-section">
                            <h6>DCS - Client Acceptance & Retention Risk Register</h6>
                            <p>Version ${data.app_version}</p>
                            <p>A comprehensive risk management system for client acceptance and retention.</p>
                            <div class="about-info">
                                <div class="about-item">
                                    <strong>Laravel Version:</strong> ${data.laravel_version}
                                </div>
                                <div class="about-item">
                                    <strong>PHP Version:</strong> ${data.php_version}
                                </div>
                                <div class="about-item">
                                    <strong>Server Time:</strong> ${new Date(data.server_time).toLocaleString()}
                                </div>
                                <div class="about-item">
                                    <strong>Timezone:</strong> ${data.timezone}
                                </div>
                                <div class="about-item">
                                    <strong>Contact:</strong> <a href="tel:+264824032391">+264 82 403 2391</a>
                                </div>
                                <div class="about-item">
                                    <strong>Developed by:</strong> DCS (Don Consulting Services)
                                </div>
                                <div class="about-item">
                                    <strong>Copyright:</strong> © 2025 DCS. All rights reserved.
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            })
            .catch(error => {
                console.error('Error loading system info:', error);
                showModal('About DCS', `
                    <div class="about-modal-content">
                        <div class="about-section">
                            <h6>DCS - Client Acceptance & Retention Risk Register</h6>
                            <p>A comprehensive risk management system for client acceptance and retention.</p>
                            <div class="about-info">
                                <div class="about-item">
                                    <strong>Contact:</strong> <a href="tel:+264824032391">+264 82 403 2391</a>
                                </div>
                                <div class="about-item">
                                    <strong>Developed by:</strong> DCS (Don Consulting Services)
                                </div>
                                <div class="about-item">
                                    <strong>Copyright:</strong> © 2025 DCS. All rights reserved.
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });
        }
        
        // Settings functionality
        function toggleTwoFactor() {
            fetch('<?php echo e(route("profile.toggle-2fa")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSettingsModal(); // Refresh the modal
                    showToast(data.message, 'success');
                } else {
                    showToast('Error updating 2FA settings', 'error');
                }
            })
            .catch(error => {
                console.error('Error toggling 2FA:', error);
                showToast('Error updating 2FA settings', 'error');
            });
        }
        
        function updatePreference(key, value) {
            const data = {};
            data[key] = value;
            
            fetch('<?php echo e(route("profile.preferences")); ?>', {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Preference updated successfully', 'success');
                } else {
                    showToast('Error updating preference', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating preference:', error);
                showToast('Error updating preference', 'error');
            });
        }
        
        function showPasswordChangeModal() {
            showModal('Change Password', `
                <div class="password-change-content">
                    <form id="passwordChangeForm">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            `);
            
            // Add form submission handler
            const passwordForm = document.getElementById('passwordChangeForm');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    changePassword();
                });
            }
        }
        
        function changePassword() {
            const form = document.getElementById('passwordChangeForm');
            if (!form) return;
            
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            
            fetch('<?php echo e(route("profile.change-password")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Password changed successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                } else {
                    if (data.errors) {
                        let errorMessage = 'Validation errors:\n';
                        for (const [key, errors] of Object.entries(data.errors)) {
                            errorMessage += `${key}: ${errors.join(', ')}\n`;
                        }
                        showToast(errorMessage, 'error');
                    } else {
                        showToast('Error changing password', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error changing password:', error);
                showToast('Error changing password', 'error');
            });
        }
        
        function showToast(message, type = 'info') {
            const toastHtml = `
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            // Create toast container if it doesn't exist
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = toastContainer.lastElementChild;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            // Remove toast element after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        // Global modal cleanup function
        function cleanupModals() {
            // Remove all modal backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            // Reset body classes and styles
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Dispose of all modal instances
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.dispose();
                }
            });
        }
        
        // Add global event listener for modal cleanup
        document.addEventListener('DOMContentLoaded', function() {
            // Clean up any existing modals on page load
            cleanupModals();
            
            // Add event listener for when modals are hidden
            document.addEventListener('hidden.bs.modal', function() {
                cleanupModals();
            });
        });

        function showModal(title, content) {
            // Remove existing modal and backdrop if any
            const existingModal = document.getElementById('userModal');
            if (existingModal) {
                const modalInstance = bootstrap.Modal.getInstance(existingModal);
                if (modalInstance) {
                    modalInstance.dispose();
                }
                existingModal.remove();
            }
            
            // Remove any lingering modal backdrops
            const existingBackdrops = document.querySelectorAll('.modal-backdrop');
            existingBackdrops.forEach(backdrop => backdrop.remove());
            
            // Reset body overflow
            document.body.style.overflow = '';
            document.body.classList.remove('modal-open');
            
            const modalHtml = `
                <div class="modal fade" id="userModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ${content}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add new modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Show modal with proper cleanup
            const modalElement = document.getElementById('userModal');
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            
            // Add event listeners for proper cleanup
            modalElement.addEventListener('hidden.bs.modal', function() {
                // Clean up modal and backdrop
                modal.dispose();
                modalElement.remove();
                
                // Remove any lingering backdrops
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                
                // Reset body
                document.body.style.overflow = '';
                document.body.classList.remove('modal-open');
            });
            
            modal.show();
        }
        
        // Notification Panel Functions
        function openNotificationPanel() {
            const modalHtml = `
                <div class="modal fade" id="notificationModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header" style="background: var(--logo-dark-blue-primary); color: white;">
                                <h5 class="modal-title">
                                    <i class="fas fa-bell me-2"></i>Notifications
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="notificationModalBody" style="max-height: 60vh; overflow-y: auto;">
                                <div class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                                    <p class="mt-2">Loading notifications...</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="markAllAsRead()">
                                    <i class="fas fa-check-double"></i> Mark All Read
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            const existingModal = document.getElementById('notificationModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modalElement = document.getElementById('notificationModal');
            const modal = new bootstrap.Modal(modalElement);
            
            modalElement.addEventListener('shown.bs.modal', function() {
                loadNotifications();
            });
            
            modalElement.addEventListener('hidden.bs.modal', function() {
                modal.dispose();
                modalElement.remove();
                loadUnreadNotificationCount(); // Refresh count
            });
            
            modal.show();
        }
        
        async function loadNotifications() {
            const modalBody = document.getElementById('notificationModalBody');
            
            try {
                const response = await fetch('/notifications');
                const data = await response.json();
                
                if (!data.notifications || data.notifications.length === 0) {
                    modalBody.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No notifications yet</p>
                        </div>
                    `;
                    return;
                }
                
                let html = '<div class="list-group list-group-flush">';
                data.notifications.forEach(notification => {
                    const isUnread = !notification.read;
                    const iconClass = getNotificationIcon(notification.type);
                    const priorityBadge = notification.priority !== 'normal' ? 
                        `<span class="badge badge-${notification.priority} ms-2">${notification.priority}</span>` : '';
                    
                    html += `
                        <div class="list-group-item ${isUnread ? 'list-group-item-primary' : ''}" 
                             onclick="handleNotificationItemClick(${notification.id}, '${notification.action_url || ''}')"
                             style="cursor: pointer;">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="${iconClass} fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1">${notification.title}${priorityBadge}</h6>
                                        ${isUnread ? '<span class="badge bg-primary rounded-pill">New</span>' : ''}
                                    </div>
                                    <p class="mb-1 small">${notification.message}</p>
                                    <small class="text-muted">${formatTimeAgo(notification.created_at)}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                
                modalBody.innerHTML = html;
            } catch (error) {
                console.error('Error loading notifications:', error);
                modalBody.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                        <p class="text-danger">Failed to load notifications</p>
                    </div>
                `;
            }
        }
        
        async function handleNotificationItemClick(notificationId, actionUrl) {
            await markNotificationAsRead(notificationId);
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('notificationModal'));
            if (modal) {
                modal.hide();
            }
            
            if (actionUrl) {
                window.location.href = actionUrl;
            }
        }
        
        async function markAllAsRead() {
            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    loadNotifications();
                    loadUnreadNotificationCount();
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        }
        
        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            if (seconds < 60) return 'just now';
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return `${minutes}m ago`;
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return `${hours}h ago`;
            const days = Math.floor(hours / 24);
            if (days < 7) return `${days}d ago`;
            return date.toLocaleDateString();
        }
        
        // Update notification count to show ONLY unread notifications
        function loadUnreadNotificationCount() {
            if (notificationCountRequestInProgress) {
                return;
            }
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                return;
            }
            
            notificationCountRequestInProgress = true;
            
            fetch('/notifications/unread-count', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => {
                notificationCountRequestInProgress = false;
                return response.ok ? response.json() : Promise.reject('Failed');
            })
            .then(data => {
                if (!data) return;
                
                const count = data.count || 0;
                
                // Update all notification count elements
                const elements = [
                    'notification-count',
                    'dropdown-notification-count',
                    'mobile-notification-count',
                    'total-notifications'
                ];
                
                elements.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.textContent = count;
                        el.style.display = count > 0 ? 'flex' : 'none';
                    }
                });
            })
            .catch(() => {
                notificationCountRequestInProgress = false;
            });
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUnreadNotificationCount();
            // Refresh notification count every 30 seconds
            setInterval(loadUnreadNotificationCount, 30000);
        });
        
        // Show logout spinner when logging out
        function showLogoutSpinner(event) {
            const overlay = document.getElementById('logoutLoadingOverlay');
            if (overlay) {
                overlay.classList.add('active');
                overlay.style.display = 'flex';
            }
            
            // Optional: disable all other interactions
            document.body.style.overflow = 'hidden';
            
            // Let the form submit normally
            return true;
        }
    </script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>




<?php /**PATH C:\xampp\htdocs\well-known\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>