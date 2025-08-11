<?php
// Redirect to main application's login page
header('Location: index.php?page=login');
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCS Portal - Risk Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(245, 158, 11, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .portal-container {
            width: 100%;
            max-width: 480px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .logo-section {
            margin-bottom: 0;
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 2.5rem 2rem 1.5rem 2rem;
            border-radius: 20px 20px 0 0;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-bottom: none;
        }
        
        .dcs-logo {
            width: 100%;
            max-width: 300px;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            filter: drop-shadow(0 8px 16px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
        }

        .dcs-logo:hover {
            transform: scale(1.05);
        }
        
        .portal-title {
            display: none;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 0 0 20px 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-top: none;
        }
        
        .login-content {
            padding: 2.5rem;
        }
        
        .login-heading {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }
        
        .login-subtitle {
            color: #64748b;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }
        
        .form-group {
            margin-bottom: 2rem;
            text-align: left;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .form-control {
            width: 100%;
            padding: 16px 18px 16px 48px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            font-weight: 500;
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-control:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.4);
            transform: translateY(-1px);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            transition: color 0.3s ease;
        }
        
        .btn-login {
            width: 100%;
            background: rgba(0, 0, 0, 0.4);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
            background: rgba(0, 0, 0, 0.5);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .login-links {
            text-align: center;
        }
        
        .login-links a {
            display: block;
            color: #667eea;
            text-decoration: none;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            font-weight: 500;
        }
        
        .login-links a:hover {
            color: #5a67d8;
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 1.5rem;
            padding: 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            border: none;
            backdrop-filter: blur(10px);
        }
        
        .alert-danger {
            background: rgba(0, 0, 0, 0.4);
            color: #fca5a5;
            border-left: 4px solid #fca5a5;
        }
        
        .alert-success {
            background: rgba(0, 0, 0, 0.4);
            color: #86efac;
            border-left: 4px solid #86efac;
        }
    </style>
</head>
<body>
    <div class="portal-container">
        <!-- Logo and Title Section -->
        <div class="logo-section">
            <div class="logo-container">
                <img src="images/dcs-logo.png" alt="DCS Logo" class="dcs-logo">
            </div>
        </div>
        
        <!-- Login Card -->
        <div class="login-card">
            <div class="login-content">
                <h2 class="login-heading">Login</h2>
                <p class="login-subtitle">Access your risk register system</p>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <form method="POST" action="auth.php">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                            <i class="fas fa-key input-icon"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-login">Login</button>
                    
                    <div class="login-links">
                        <a href="#">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 