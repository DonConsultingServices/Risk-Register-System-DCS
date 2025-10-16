<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Client Acceptance & Retention Risk Register</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- DCS Logo Colors -->
    <link href="{{ asset('logo/logo-colors.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, var(--logo-light-bg) 0%, var(--logo-medium-bg) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .reset-password-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 7, 45, 0.3);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        
        .reset-password-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .reset-password-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--logo-dark-blue-primary);
            margin-bottom: 0.5rem;
        }
        
        .reset-password-header p {
            color: var(--logo-text-medium);
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            border-color: var(--logo-dark-blue-primary);
            box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.1);
            outline: none;
        }
        
        .btn-reset {
            background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-reset:hover {
            background: linear-gradient(135deg, var(--logo-dark-blue-hover), var(--logo-dark-blue-primary));
            transform: translateY(-2px);
            color: white;
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-to-login a {
            color: var(--logo-dark-blue-primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-to-login a:hover {
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: rgba(0, 7, 45, 0.1);
            border-left: 4px solid var(--logo-dark-blue-primary);
            color: var(--logo-dark-blue-primary);
        }
        
        .alert-danger {
            background-color: rgba(220, 38, 38, 0.1);
            border-left: 4px solid var(--logo-red);
            color: var(--logo-red);
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--logo-text-muted);
            cursor: pointer;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: var(--logo-dark-blue-primary);
        }
        
        /* Mobile-First Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
                min-height: 100vh;
            }
            
            .reset-password-container {
                padding: 1.5rem;
                border-radius: 16px;
                max-width: 100%;
                margin: 0;
            }
            
            .reset-password-header h1 {
                font-size: 1.5rem;
            }
            
            .reset-password-header p {
                font-size: 0.85rem;
            }
            
            .form-control {
                padding: 0.875rem 1rem;
                font-size: 16px; /* Prevents zoom on iOS */
                border-radius: 10px;
            }
            
            .form-label {
                font-size: 0.9rem;
                margin-bottom: 0.375rem;
            }
            
            .btn-reset {
                padding: 0.875rem 1.5rem;
                font-size: 0.95rem;
                border-radius: 10px;
            }
            
            .back-to-login {
                margin-top: 1.25rem;
            }
            
            .back-to-login a {
                font-size: 0.85rem;
            }
            
            .password-toggle {
                right: 12px;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 0.75rem;
            }
            
            .reset-password-container {
                padding: 1.25rem;
                border-radius: 12px;
            }
            
            .reset-password-header h1 {
                font-size: 1.25rem;
            }
            
            .form-control {
                padding: 0.75rem 0.875rem;
            }
            
            .btn-reset {
                padding: 0.75rem 1.25rem;
                font-size: 0.9rem;
            }
            
            .password-toggle {
                right: 10px;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
            }
            
            .reset-password-container {
                padding: 1rem;
                border-radius: 10px;
            }
            
            .reset-password-header h1 {
                font-size: 1.1rem;
            }
            
            .form-control {
                padding: 0.625rem 0.75rem;
                font-size: 16px;
            }
            
            .btn-reset {
                padding: 0.625rem 1rem;
                font-size: 0.85rem;
            }
            
            .password-toggle {
                right: 8px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="reset-password-header">
            <h1>Reset Password</h1>
            <p>Enter your new password below.</p>
        </div>
        
        @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error!</strong> Please check your information and try again.
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ $email ?? old('email') }}"
                       required 
                       readonly>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <div class="position-relative">
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your new password"
                           required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-icon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <div class="position-relative">
                    <input type="password" 
                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="Confirm your new password"
                           required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye" id="password_confirmation-icon"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-reset">
                <i class="fas fa-key me-2"></i>
                Reset Password
            </button>
        </form>
        
        <div class="back-to-login">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Login
            </a>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId + '-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
