<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Internal System - No SEO Required -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, nofollow">
    <meta name="description" content="Internal DCS Client Acceptance & Retention Risk Register System">
    <meta name="author" content="DCS Internal Systems">
    
    <title>@yield('title', 'Client Acceptance & Retention Risk Register')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- DCS Logo Colors -->
    <link href="{{ asset('logo/logo-colors.css') }}" rel="stylesheet">
    
    <!-- Font Awesome Fix -->
    <link href="{{ asset('css/font-awesome-fix.css') }}?v={{ time() }}" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --bs-primary: var(--logo-dark-blue-primary);
            --bs-primary-rgb: 0, 7, 45;
            --bs-link-color: var(--logo-dark-blue-primary);
            --bs-link-hover-color: var(--logo-dark-blue-hover);
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
        
        .btn-outline-primary {
            color: var(--logo-dark-blue-primary);
            border-color: var(--logo-dark-blue-primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--logo-dark-blue-primary);
            border-color: var(--logo-dark-blue-primary);
            color: var(--logo-white);
        }
        
        .text-primary {
            color: var(--logo-dark-blue-primary) !important;
        }
        
        .bg-primary {
            background-color: var(--logo-dark-blue-primary) !important;
        }
        
        .border-primary {
            border-color: var(--logo-dark-blue-primary) !important;
        }
        
        .form-control:focus {
            border-color: var(--logo-dark-blue-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 7, 45, 0.25);
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--logo-dark-blue-primary) 0%, var(--logo-dark-blue-secondary) 100%);
            color: var(--logo-white);
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .card-header {
            background-color: var(--logo-light-bg);
            border-bottom: 2px solid var(--logo-medium-bg);
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--logo-dark-blue-primary) 0%, var(--logo-dark-blue-secondary) 100%);
            min-height: 100vh;
        }
        
        .sidebar .nav-link {
            color: var(--logo-white);
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--logo-white);
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: var(--logo-white);
            border-left: 4px solid var(--logo-red);
        }
        
        .alert-success {
            background-color: rgba(0, 7, 45, 0.1);
            border-color: var(--logo-dark-blue-primary);
            color: var(--logo-dark-blue-primary);
        }
        
        .alert-danger {
            background-color: rgba(220, 38, 38, 0.1);
            border-color: var(--logo-red);
            color: var(--logo-red);
        }
        
        .alert-warning {
            background-color: rgba(0, 7, 45, 0.1);
            border-color: var(--logo-dark-blue-secondary);
            color: var(--logo-dark-blue-secondary);
        }
    </style>
    
    @yield('styles')
</head>
<body>


    <!-- Main Content -->
    <main>
        @yield('content')
    </main>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

    
    @yield('scripts')
</body>
</html>
