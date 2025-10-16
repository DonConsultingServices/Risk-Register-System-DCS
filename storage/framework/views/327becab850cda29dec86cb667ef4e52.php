<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Client Acceptance & Retention Risk Register</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- DCS Logo Colors -->
    <link href="<?php echo e(asset('logo/logo-colors.css')); ?>" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, var(--logo-light-bg) 0%, var(--logo-medium-bg) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
        }
        
        /* Enhanced Floating Particles Animation */
        .particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
            box-shadow: 0 0 10px rgba(0, 7, 45, 0.3);
        }
        
        .particle:nth-child(1) { 
            width: 8px; height: 8px; 
            left: 10%; 
            background: linear-gradient(45deg, var(--logo-dark-blue-primary), var(--logo-medium-blue));
            animation-delay: 0s; 
            animation-duration: 6s;
        }
        .particle:nth-child(2) { 
            width: 12px; height: 12px; 
            left: 20%; 
            background: linear-gradient(45deg, var(--logo-dark-blue-secondary), var(--logo-light-blue));
            animation-delay: 1s; 
            animation-duration: 7s;
        }
        .particle:nth-child(3) { 
            width: 6px; height: 6px; 
            left: 30%; 
            background: linear-gradient(45deg, var(--logo-dark-blue-primary), var(--logo-red));
            animation-delay: 2s; 
            animation-duration: 5s;
        }
        .particle:nth-child(4) { 
            width: 10px; height: 10px; 
            left: 40%; 
            background: linear-gradient(45deg, var(--logo-medium-blue), var(--logo-dark-blue-primary));
            animation-delay: 3s; 
            animation-duration: 8s;
        }
        .particle:nth-child(5) { 
            width: 8px; height: 8px; 
            left: 50%; 
            background: linear-gradient(45deg, var(--logo-dark-blue-secondary), var(--logo-green));
            animation-delay: 4s; 
            animation-duration: 6.5s;
        }
        .particle:nth-child(6) { 
            width: 14px; height: 14px; 
            left: 60%; 
            background: linear-gradient(45deg, var(--logo-dark-blue-primary), var(--logo-orange));
            animation-delay: 5s; 
            animation-duration: 7.5s;
        }
        .particle:nth-child(7) { 
            width: 6px; height: 6px; 
            left: 70%; 
            background: linear-gradient(45deg, var(--logo-light-blue), var(--logo-dark-blue-primary));
            animation-delay: 0.5s; 
            animation-duration: 5.5s;
        }
        .particle:nth-child(8) { 
            width: 10px; height: 10px; 
            left: 80%; 
            background: linear-gradient(45deg, var(--logo-dark-blue-secondary), var(--logo-yellow));
            animation-delay: 1.5s; 
            animation-duration: 6.8s;
        }
        .particle:nth-child(9) { 
            width: 8px; height: 8px; 
            left: 90%; 
            background: linear-gradient(45deg, var(--logo-medium-blue), var(--logo-red));
            animation-delay: 2.5s; 
            animation-duration: 7.2s;
        }
        .particle:nth-child(10) { 
            width: 12px; height: 12px; 
            left: 15%; 
            background: linear-gradient(45deg, var(--logo-dark-blue-primary), var(--logo-green));
            animation-delay: 3.5s; 
            animation-duration: 6.2s;
        }
        
        @keyframes float {
            0%, 100% { 
                transform: translateY(100vh) rotate(0deg) scale(0.5); 
                opacity: 0; 
            }
            10% { 
                opacity: 1; 
                transform: translateY(90vh) rotate(36deg) scale(0.8);
            }
            50% { 
                transform: translateY(-10vh) rotate(180deg) scale(1.2); 
                opacity: 0.8;
            }
            90% { 
                opacity: 0.6; 
                transform: translateY(-20vh) rotate(324deg) scale(0.9);
            }
        }
        
        /* Animated Background Shapes */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(0, 7, 45, 0.1), rgba(0, 16, 102, 0.05));
            animation: shapeFloat 20s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: 5s;
        }
        
        .shape:nth-child(3) {
            width: 100px;
            height: 100px;
            top: 30%;
            left: 70%;
            animation-delay: 10s;
        }
        
        .shape:nth-child(4) {
            width: 180px;
            height: 180px;
            bottom: 20%;
            left: 20%;
            animation-delay: 15s;
        }
        
        @keyframes shapeFloat {
            0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
            25% { transform: translateY(-20px) rotate(90deg) scale(1.1); }
            50% { transform: translateY(-40px) rotate(180deg) scale(0.9); }
            75% { transform: translateY(-20px) rotate(270deg) scale(1.05); }
        }
        
        /* Glowing Orbs */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 7, 45, 0.3), transparent);
            filter: blur(1px);
            animation: glowPulse 4s ease-in-out infinite;
        }
        
        .glow-orb:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 20%;
            left: 5%;
            animation-delay: 0s;
        }
        
        .glow-orb:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 70%;
            right: 5%;
            animation-delay: 2s;
        }
        
        .glow-orb:nth-child(3) {
            width: 250px;
            height: 250px;
            bottom: 10%;
            left: 50%;
            animation-delay: 4s;
        }
        
        @keyframes glowPulse {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.2); }
        }
        
        /* Mouse Trail Effect */
        .mouse-trail {
            position: fixed;
            width: 4px;
            height: 4px;
            background: var(--logo-dark-blue-primary);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1000;
            opacity: 0.15;
            animation: trailFade 0.8s ease-out forwards;
        }
        
        @keyframes trailFade {
            0% { opacity: 0.15; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.2); }
        }
        
        /* Typing Animation */
        .typing-text {
            overflow: hidden;
            border-right: 2px solid white;
            white-space: nowrap;
            animation: typing 3s steps(40, end), blink-caret 0.75s step-end infinite;
        }
        
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        
        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: white; }
        }
        
        /* Enhanced Pulsing Welcome Text */
        .welcome-content h1 {
            animation: pulse 2s ease-in-out infinite, glow 3s ease-in-out infinite;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
            position: relative;
        }
        
        .welcome-content h1::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            border-radius: 10px;
            animation: shimmer 2s ease-in-out infinite;
            z-index: -1;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes glow {
            0%, 100% { text-shadow: 0 0 20px rgba(255, 255, 255, 0.5), 0 0 40px rgba(255, 255, 255, 0.3); }
            50% { text-shadow: 0 0 30px rgba(255, 255, 255, 0.8), 0 0 60px rgba(255, 255, 255, 0.5); }
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Animated Background Gradient */
        .left-panel {
            background: linear-gradient(135deg, var(--logo-dark-blue-primary) 0%, var(--logo-dark-blue-secondary) 100%);
            animation: gradientShift 8s ease-in-out infinite;
        }
        
        @keyframes gradientShift {
            0%, 100% { background: linear-gradient(135deg, var(--logo-dark-blue-primary) 0%, var(--logo-dark-blue-secondary) 100%); }
            25% { background: linear-gradient(135deg, var(--logo-dark-blue-secondary) 0%, var(--logo-dark-blue-primary) 100%); }
            50% { background: linear-gradient(135deg, var(--logo-dark-blue-primary) 0%, var(--logo-medium-blue) 100%); }
            75% { background: linear-gradient(135deg, var(--logo-medium-blue) 0%, var(--logo-dark-blue-primary) 100%); }
        }
        
        /* Floating Icons */
        .floating-icons {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
        }
        
        .floating-icon {
            position: absolute;
            font-size: 24px;
            color: rgba(255, 255, 255, 0.3);
            animation: floatIcon 15s ease-in-out infinite;
            transform: translateZ(60px);
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }
        
        .floating-icon:nth-child(1) { top: 20%; left: 20%; animation-delay: 0s; }
        .floating-icon:nth-child(2) { top: 40%; right: 20%; animation-delay: 3s; }
        .floating-icon:nth-child(3) { bottom: 30%; left: 30%; animation-delay: 6s; }
        .floating-icon:nth-child(4) { top: 60%; left: 60%; animation-delay: 9s; }
        .floating-icon:nth-child(5) { bottom: 20%; right: 30%; animation-delay: 12s; }
        
        @keyframes floatIcon {
            0%, 100% { transform: translateY(0) rotate(0deg) scale(1); opacity: 0.3; }
            25% { transform: translateY(-20px) rotate(90deg) scale(1.1); opacity: 0.6; }
            50% { transform: translateY(-40px) rotate(180deg) scale(0.9); opacity: 0.4; }
            75% { transform: translateY(-20px) rotate(270deg) scale(1.05); opacity: 0.5; }
        }
        
        /* Enhanced Form Animation */
        .login-container {
            animation: slideInRight 1s ease-out, containerGlow 4s ease-in-out infinite;
            position: relative;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--logo-dark-blue-primary), var(--logo-medium-blue), var(--logo-dark-blue-primary));
            border-radius: 12px;
            z-index: -1;
            animation: borderGlow 3s ease-in-out infinite;
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100px) rotateY(15deg); opacity: 0; }
            to { transform: translateX(0) rotateY(0deg); opacity: 1; }
        }
        
        @keyframes containerGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(0, 7, 45, 0.1); }
            50% { box-shadow: 0 0 40px rgba(0, 7, 45, 0.3); }
        }
        
        @keyframes borderGlow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        
        .form-group {
            animation: fadeInUp 0.8s ease-out, formPulse 2s ease-in-out infinite;
            animation-fill-mode: both;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .form-group:nth-child(1) { animation-delay: 0.2s; }
        .form-group:nth-child(2) { animation-delay: 0.4s; }
        .form-group:nth-child(3) { animation-delay: 0.6s; }
        .form-group:nth-child(4) { animation-delay: 0.8s; }
        .form-group:nth-child(5) { animation-delay: 1s; }
        
        .form-group:hover {
            transform: translateY(-2px) scale(1.02);
        }
        
        @keyframes fadeInUp {
            from { transform: translateY(30px) rotateX(10deg); opacity: 0; }
            to { transform: translateY(0) rotateX(0deg); opacity: 1; }
        }
        
        @keyframes formPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.01); }
        }
        
        /* Enhanced Input Fields */
        .form-control {
            position: relative;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 1));
        }
        
        .form-control::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(0, 7, 45, 0.1), transparent);
            border-radius: 8px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .form-control:focus::before {
            opacity: 1;
        }
        
        /* Animated Labels */
        .form-label {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .form-label::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(45deg, var(--logo-dark-blue-primary), var(--logo-medium-blue));
            transition: width 0.3s ease;
        }
        
        .form-group:focus-within .form-label::after {
            width: 100%;
        }
        
        /* 3D Perspective Container */
        body {
            perspective: 1000px;
            transform-style: preserve-3d;
        }
        
        .login-wrapper {
            transform-style: preserve-3d;
            animation: rotate3D 20s ease-in-out infinite;
        }
        
        @keyframes rotate3D {
            0%, 100% { transform: rotateY(0deg) rotateX(0deg); }
            25% { transform: rotateY(5deg) rotateX(2deg); }
            50% { transform: rotateY(0deg) rotateX(5deg); }
            75% { transform: rotateY(-5deg) rotateX(2deg); }
        }
        
        /* Breathing Animation for Login Button */
        .btn-login {
            animation: breathe 3s ease-in-out infinite;
        }
        
        @keyframes breathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        /* Hover Effects */
        .form-control:hover {
            transform: translateY(-2px) translateZ(30px) rotateX(5deg);
            box-shadow: 
                0 12px 24px rgba(0, 7, 45, 0.2),
                0 0 0 1px rgba(0, 7, 45, 0.1);
        }
        
        .form-control:focus {
            transform: translateY(-2px) translateZ(40px) rotateX(3deg);
            box-shadow: 
                0 15px 30px rgba(0, 7, 45, 0.3),
                0 0 0 2px rgba(0, 7, 45, 0.2);
        }
        
        .login-wrapper {
            display: flex;
            width: 100%;
            flex: 1;
            height: calc(100vh - 200px);
            margin: 0;
            padding: 0;
        }
        
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--logo-dark-blue-primary) 0%, var(--logo-dark-blue-secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transform: translateZ(50px) rotateY(-5deg);
            box-shadow: 
                0 20px 40px rgba(0, 7, 45, 0.3),
                inset 0 0 100px rgba(255, 255, 255, 0.1);
            animation: leftPanelFloat 6s ease-in-out infinite;
        }
        
        @keyframes leftPanelFloat {
            0%, 100% { transform: translateZ(50px) rotateY(-5deg) translateY(0px); }
            50% { transform: translateZ(60px) rotateY(-3deg) translateY(-10px); }
        }
        
        .left-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .welcome-content {
            text-align: center;
            color: white;
            z-index: 2;
            position: relative;
            padding: 2rem;
            transform: translateZ(80px) rotateX(5deg);
            animation: welcome3D 12s ease-in-out infinite;
        }
        
        @keyframes welcome3D {
            0%, 100% { transform: translateZ(80px) rotateX(5deg) rotateY(0deg); }
            33% { transform: translateZ(90px) rotateX(3deg) rotateY(3deg); }
            66% { transform: translateZ(85px) rotateX(7deg) rotateY(-2deg); }
        }
        
        .welcome-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #ffffff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .welcome-content p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            font-weight: 300;
        }
        
        .welcome-content .subtitle {
            font-size: 1rem;
            opacity: 0.8;
            font-weight: 400;
        }
        
        .right-panel {
            flex: 0.5;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transform: translateZ(30px) rotateY(5deg);
            box-shadow: 
                0 25px 50px rgba(0, 7, 45, 0.2),
                inset 0 0 50px rgba(0, 7, 45, 0.05);
            animation: rightPanelFloat 8s ease-in-out infinite;
        }
        
        @keyframes rightPanelFloat {
            0%, 100% { transform: translateZ(30px) rotateY(5deg) translateY(0px); }
            50% { transform: translateZ(40px) rotateY(3deg) translateY(-5px); }
        }
        
        .right-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 40%;
            height: 200%;
            background: linear-gradient(45deg, rgba(0, 7, 45, 0.03), rgba(0, 16, 102, 0.05));
            transform: rotate(15deg);
            border-radius: 50px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 
                0 30px 60px rgba(0, 7, 45, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            overflow: visible;
            width: 100%;
            max-width: 350px;
            padding: 2rem 1.5rem;
            position: relative;
            z-index: 2;
            transform: translateZ(100px) rotateX(5deg);
            animation: container3D 10s ease-in-out infinite;
            backdrop-filter: blur(10px);
        }
        
        @keyframes container3D {
            0%, 100% { transform: translateZ(100px) rotateX(5deg) rotateY(0deg); }
            25% { transform: translateZ(110px) rotateX(3deg) rotateY(2deg); }
            50% { transform: translateZ(120px) rotateX(7deg) rotateY(0deg); }
            75% { transform: translateZ(110px) rotateX(3deg) rotateY(-2deg); }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--logo-dark-blue-primary);
            margin-bottom: 0.1rem;
        }
        
        .login-header p {
            color: var(--logo-text-medium);
            font-size: 0.9rem;
            margin: 0;
        }
        
        .login-body {
            padding: 0;
        }
        
        .form-group {
            margin-bottom: 0.25rem;
        }
        
        .form-label {
            display: block;
            color: var(--logo-text-dark);
            font-weight: 500;
            margin-bottom: 0.5rem;
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
            transform: translateZ(20px);
            box-shadow: 
                0 8px 16px rgba(0, 7, 45, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        
        .form-control:focus {
            border-color: var(--logo-dark-blue-primary);
            box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.1);
            outline: none;
        }
        
        .form-control::placeholder {
            color: #94a3b8;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            margin: 1rem 0;
        }
        
        .form-check-input {
            margin-right: 0.5rem;
        }
        
        .form-check-input:checked {
            background-color: var(--logo-dark-blue-primary);
            border-color: var(--logo-dark-blue-primary);
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.1);
        }
        
        .form-check-label {
            color: var(--logo-text-medium);
            font-size: 0.9rem;
        }
        
        .forgot-password {
            text-align: right;
            margin-top: 0.5rem;
        }
        
        .forgot-password a {
            color: var(--logo-dark-blue-primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--logo-dark-blue-primary), var(--logo-dark-blue-secondary));
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            transform: translateZ(30px);
            box-shadow: 
                0 10px 20px rgba(0, 7, 45, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, var(--logo-dark-blue-hover), var(--logo-dark-blue-primary));
            transform: translateY(-3px) translateZ(40px) rotateX(5deg);
            box-shadow: 
                0 15px 30px rgba(0, 7, 45, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(-1px) translateZ(35px) rotateX(2deg);
        }
        
        .btn-login:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 7, 45, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: rgba(220, 38, 38, 0.1);
            border-left: 4px solid var(--logo-red);
            color: var(--logo-red);
        }
        
        .alert-success {
            background-color: rgba(0, 7, 45, 0.1);
            border-left: 4px solid var(--logo-dark-blue-primary);
            color: var(--logo-dark-blue-primary);
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
        
        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: var(--logo-text-muted);
            font-size: 0.9rem;
        }
        
        .footer-text a {
            color: var(--logo-dark-blue-primary);
            text-decoration: none;
        }
        
        .footer-text a:hover {
            color: var(--logo-dark-blue-hover);
            text-decoration: underline;
        }
        
        /* Enhanced Mobile-First Responsive Design */
        @media (max-width: 768px) {
            body {
                overflow-x: hidden;
            }
            
            .login-wrapper {
                flex-direction: column;
                min-height: 100vh;
                padding: 0;
                margin: 0;
            }
            
            .left-panel {
                min-height: 35vh;
                padding: 1.5rem 1rem;
                transform: none;
                animation: none;
            }
            
            .welcome-content {
                transform: none;
                animation: none;
                padding: 1rem;
            }
            
            .welcome-content h1 {
                font-size: 1.75rem;
                margin-bottom: 0.75rem;
            }
            
            .welcome-content p {
                font-size: 0.95rem;
                margin-bottom: 1.5rem;
            }
            
            .welcome-content .subtitle {
                font-size: 0.85rem;
            }
            
            .right-panel {
                min-height: 65vh;
                padding: 1rem;
                transform: none;
                animation: none;
            }
            
            .login-container {
                padding: 1.5rem 1rem;
                max-width: 100%;
                margin: 0;
                transform: none;
                animation: none;
                border-radius: 16px;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }
            
            .login-header p {
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
            
            .btn-login {
                padding: 0.875rem 1.5rem;
                font-size: 0.95rem;
                border-radius: 10px;
            }
            
            .form-check-label {
                font-size: 0.85rem;
            }
            
            .forgot-password a {
                font-size: 0.85rem;
            }
            
            .footer-text {
                font-size: 0.8rem;
                margin-top: 1.5rem;
            }
            
            /* Disable complex animations on mobile */
            .particle, .shape, .glow-orb, .floating-icon {
                display: none;
            }
            
            .left-panel::before,
            .right-panel::before {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .login-wrapper {
                min-height: 100vh;
            }
            
            .left-panel {
                min-height: 30vh;
                padding: 1rem 0.75rem;
            }
            
            .welcome-content h1 {
                font-size: 1.5rem;
            }
            
            .welcome-content p {
                font-size: 0.9rem;
            }
            
            .right-panel {
                min-height: 70vh;
                padding: 0.75rem;
            }
            
            .login-container {
                padding: 1.25rem 0.75rem;
                border-radius: 12px;
            }
            
            .login-header h1 {
                font-size: 1.25rem;
            }
            
            .form-control {
                padding: 0.75rem 0.875rem;
            }
            
            .btn-login {
                padding: 0.75rem 1.25rem;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 480px) {
            .left-panel {
                min-height: 25vh;
                padding: 0.75rem 0.5rem;
            }
            
            .welcome-content h1 {
                font-size: 1.25rem;
            }
            
            .welcome-content p {
                font-size: 0.85rem;
            }
            
            .right-panel {
                min-height: 75vh;
                padding: 0.5rem;
            }
            
            .login-container {
                padding: 1rem 0.5rem;
                border-radius: 10px;
            }
            
            .login-header h1 {
                font-size: 1.1rem;
            }
            
            .form-control {
                padding: 0.625rem 0.75rem;
                font-size: 16px;
            }
            
            .btn-login {
                padding: 0.625rem 1rem;
                font-size: 0.85rem;
            }
        }
        
        /* Landscape mobile optimization */
        @media (max-width: 768px) and (orientation: landscape) {
            .left-panel {
                min-height: 25vh;
            }
            
            .right-panel {
                min-height: 75vh;
            }
            
            .welcome-content h1 {
                font-size: 1.25rem;
            }
            
            .welcome-content p {
                font-size: 0.8rem;
            }
        }
        
        /* Additional mobile enhancements */
        @media (max-width: 360px) {
            .left-panel {
                min-height: 20vh;
                padding: 0.5rem 0.25rem;
            }
            
            .welcome-content h1 {
                font-size: 1.1rem;
            }
            
            .welcome-content p {
                font-size: 0.8rem;
            }
            
            .welcome-content .subtitle {
                font-size: 0.75rem;
            }
            
            .right-panel {
                min-height: 80vh;
                padding: 0.25rem;
            }
            
            .login-container {
                padding: 0.75rem 0.5rem;
                border-radius: 8px;
            }
            
            .login-header h1 {
                font-size: 1rem;
            }
            
            .login-header p {
                font-size: 0.75rem;
            }
            
            .form-control {
                padding: 0.5rem 0.625rem;
                font-size: 16px;
            }
            
            .btn-login {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }
            
            .form-check-label {
                font-size: 0.8rem;
            }
            
            .forgot-password a {
                font-size: 0.8rem;
            }
            
            .footer-text {
                font-size: 0.75rem;
            }
        }
        
        /* Ultra-wide mobile screens */
        @media (max-width: 414px) and (min-width: 361px) {
            .login-container {
                padding: 1.25rem 1rem;
            }
            
            .form-control {
                padding: 0.75rem 1rem;
            }
            
            .btn-login {
                padding: 0.75rem 1.5rem;
            }
        }
        
        /* Touch improvements */
        @media (hover: none) and (pointer: coarse) {
            .btn-login {
                min-height: 48px;
                min-width: 48px;
            }
            
            .form-control {
                min-height: 48px;
            }
            
            .form-check-input {
                min-width: 20px;
                min-height: 20px;
            }
            
            .forgot-password a {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                padding: 0.5rem;
            }
        }
        
        /* High DPI mobile screens */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .login-container {
                box-shadow: 0 8px 32px rgba(0, 7, 45, 0.15);
            }
            
            .form-control {
                border-width: 1px;
            }
        }
    </style>
</head>
<body>
    <!-- Enhanced Visual Effects -->
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="glow-orb"></div>
    <div class="glow-orb"></div>
    <div class="glow-orb"></div>
    
    <!-- Enhanced Floating Particles -->
    <div class="particles-container">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    
    <div class="login-wrapper">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="floating-icons">
                <i class="fas fa-shield-alt floating-icon"></i>
                <i class="fas fa-chart-line floating-icon"></i>
                <i class="fas fa-users floating-icon"></i>
                <i class="fas fa-cog floating-icon"></i>
                <i class="fas fa-database floating-icon"></i>
            </div>
            <div class="welcome-content">
                <h1>Welcome Back!</h1>
                <div class="subtitle">Client Acceptance & Retention Risk Register</div>
                <p class="typing-text">Secure Your Business Future</p>
            </div>
        </div>
        
        <!-- Right Panel -->
        <div class="right-panel">
            <div class="login-container">
                <!-- Header -->
                <div class="login-header">
                    <h1>Login</h1>
                    <p>Sign in to your account</p>
                </div>
                
                <!-- Login Form -->
                <div class="login-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Login failed!</strong> Please check your credentials and try again.
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo e(route('login')); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Enter your email address"
                                   value="<?php echo e(old('email')); ?>"
                                   required 
                                   autofocus>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="position-relative">
                                <input type="password" 
                                       class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        
                        <div class="forgot-password">
                            <a href="<?php echo e(route('password.request')); ?>">Forgot password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-login">
                            Login
                        </button>
                    </form>
                
                    <div class="footer-text">
                        <p>Need help? Contact your system administrator<br>Email: ITSupport@dcs.com.na</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
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
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
        
        // Enhanced mouse trail effect
        let mouseTrails = [];
        let trailCount = 0;
        let lastMouseMove = 0;
        document.addEventListener('mousemove', function(e) {
            const now = Date.now();
            if (now - lastMouseMove > 50) { // Throttle to every 50ms
                createMouseTrail(e.clientX, e.clientY);
                createRipple(e.clientX, e.clientY);
                lastMouseMove = now;
            }
        });
        
        function createMouseTrail(x, y) {
            const trail = document.createElement('div');
            trail.className = 'mouse-trail';
            trail.style.left = x + 'px';
            trail.style.top = y + 'px';
            trail.style.background = `hsl(${(trailCount * 10) % 360}, 30%, 60%)`;
            trail.style.boxShadow = `0 0 3px hsl(${(trailCount * 10) % 360}, 30%, 60%)`;
            document.body.appendChild(trail);
            
            mouseTrails.push(trail);
            trailCount++;
            
            setTimeout(() => {
                if (trail.parentNode) {
                    trail.parentNode.removeChild(trail);
                }
                mouseTrails = mouseTrails.filter(t => t !== trail);
            }, 800);
        }
        
        function createRipple(x, y) {
            const ripple = document.createElement('div');
            ripple.style.position = 'fixed';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.width = '2px';
            ripple.style.height = '2px';
            ripple.style.background = 'rgba(0, 7, 45, 0.2)';
            ripple.style.borderRadius = '50%';
            ripple.style.pointerEvents = 'none';
            ripple.style.animation = 'ripple 1.5s ease-out forwards';
            ripple.style.zIndex = '1000';
            
            document.body.appendChild(ripple);
            
            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.parentNode.removeChild(ripple);
                }
            }, 1500);
        }
        
        // Add ripple animation
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            @keyframes ripple {
                0% { transform: scale(0); opacity: 0.2; }
                100% { transform: scale(15); opacity: 0; }
            }
        `;
        document.head.appendChild(rippleStyle);
        
        // 3D Mouse Interaction
        document.addEventListener('mousemove', function(e) {
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            // Apply subtle 3D rotation based on mouse position
            const rotateX = (mouseY - 0.5) * 2;
            const rotateY = (mouseX - 0.5) * 2;
            
            document.querySelector('.login-wrapper').style.transform = 
                `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });
        
        // Form input animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Enhanced sparkle effects on button hover
        document.querySelector('.btn-login').addEventListener('mouseenter', function() {
            createSparkles(this);
            createButtonGlow(this);
        });
        
        function createSparkles(element) {
            const rect = element.getBoundingClientRect();
            for (let i = 0; i < 8; i++) {
                const sparkle = document.createElement('div');
                sparkle.style.position = 'fixed';
                sparkle.style.left = (rect.left + Math.random() * rect.width) + 'px';
                sparkle.style.top = (rect.top + Math.random() * rect.height) + 'px';
                sparkle.style.width = '6px';
                sparkle.style.height = '6px';
                sparkle.style.background = `hsl(${Math.random() * 360}, 70%, 60%)`;
                sparkle.style.borderRadius = '50%';
                sparkle.style.pointerEvents = 'none';
                sparkle.style.animation = 'sparkle 0.8s ease-out forwards';
                sparkle.style.zIndex = '1000';
                sparkle.style.boxShadow = `0 0 10px hsl(${Math.random() * 360}, 70%, 60%)`;
                
                document.body.appendChild(sparkle);
                
                setTimeout(() => {
                    if (sparkle.parentNode) {
                        sparkle.parentNode.removeChild(sparkle);
                    }
                }, 800);
            }
        }
        
        function createButtonGlow(element) {
            const glow = document.createElement('div');
            glow.style.position = 'fixed';
            const rect = element.getBoundingClientRect();
            glow.style.left = (rect.left - 10) + 'px';
            glow.style.top = (rect.top - 10) + 'px';
            glow.style.width = (rect.width + 20) + 'px';
            glow.style.height = (rect.height + 20) + 'px';
            glow.style.background = 'radial-gradient(circle, rgba(0, 7, 45, 0.3), transparent)';
            glow.style.borderRadius = '15px';
            glow.style.pointerEvents = 'none';
            glow.style.animation = 'buttonGlow 1s ease-out forwards';
            glow.style.zIndex = '999';
            
            document.body.appendChild(glow);
            
            setTimeout(() => {
                if (glow.parentNode) {
                    glow.parentNode.removeChild(glow);
                }
            }, 1000);
        }
        
        // Add button glow animation
        const buttonGlowStyle = document.createElement('style');
        buttonGlowStyle.textContent = `
            @keyframes buttonGlow {
                0% { opacity: 0; transform: scale(0.8); }
                50% { opacity: 1; transform: scale(1.1); }
                100% { opacity: 0; transform: scale(1.2); }
            }
        `;
        document.head.appendChild(buttonGlowStyle);
        
        // Add sparkle animation
        const sparkleStyle = document.createElement('style');
        sparkleStyle.textContent = `
            @keyframes sparkle {
                0% { opacity: 1; transform: scale(0) rotate(0deg); }
                50% { opacity: 1; transform: scale(1) rotate(180deg); }
                100% { opacity: 0; transform: scale(0) rotate(360deg); }
            }
        `;
        document.head.appendChild(sparkleStyle);
        
        // Enhanced welcome text typing effect
        window.addEventListener('load', function() {
            const typingText = document.querySelector('.typing-text');
            if (typingText) {
                const text = typingText.textContent;
                typingText.textContent = '';
                typingText.style.borderRight = '2px solid white';
                
                let i = 0;
                const typeWriter = () => {
                    if (i < text.length) {
                        typingText.textContent += text.charAt(i);
                        // Add character glow effect
                        createCharacterGlow(typingText);
                        i++;
                        setTimeout(typeWriter, 100);
                    } else {
                        setTimeout(() => {
                            typingText.style.borderRight = 'none';
                        }, 1000);
                    }
                };
                
                setTimeout(typeWriter, 1000);
            }
            
            // Add random floating elements
            setInterval(createRandomFloatingElement, 3000);
        });
        
        function createCharacterGlow(element) {
            const glow = document.createElement('div');
            glow.style.position = 'absolute';
            glow.style.left = '0';
            glow.style.top = '0';
            glow.style.width = '100%';
            glow.style.height = '100%';
            glow.style.background = 'linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent)';
            glow.style.animation = 'characterGlow 0.5s ease-out forwards';
            glow.style.pointerEvents = 'none';
            glow.style.zIndex = '1';
            
            element.style.position = 'relative';
            element.appendChild(glow);
            
            setTimeout(() => {
                if (glow.parentNode) {
                    glow.parentNode.removeChild(glow);
                }
            }, 500);
        }
        
        function createRandomFloatingElement() {
            const element = document.createElement('div');
            element.style.position = 'fixed';
            element.style.left = Math.random() * window.innerWidth + 'px';
            element.style.top = window.innerHeight + 'px';
            element.style.width = '20px';
            element.style.height = '20px';
            element.style.background = `hsl(${Math.random() * 360}, 70%, 60%)`;
            element.style.borderRadius = '50%';
            element.style.pointerEvents = 'none';
            element.style.animation = 'randomFloat 8s ease-out forwards';
            element.style.zIndex = '1';
            element.style.boxShadow = `0 0 20px hsl(${Math.random() * 360}, 70%, 60%)`;
            
            document.body.appendChild(element);
            
            setTimeout(() => {
                if (element.parentNode) {
                    element.parentNode.removeChild(element);
                }
            }, 8000);
        }
        
        // Add character glow animation
        const characterGlowStyle = document.createElement('style');
        characterGlowStyle.textContent = `
            @keyframes characterGlow {
                0% { opacity: 0; transform: scale(0.8); }
                50% { opacity: 1; transform: scale(1.1); }
                100% { opacity: 0; transform: scale(1.2); }
            }
            @keyframes randomFloat {
                0% { transform: translateY(0) rotate(0deg); opacity: 1; }
                100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
            }
        `;
        document.head.appendChild(characterGlowStyle);
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\DCS-Best\resources\views/auth/login.blade.php ENDPATH**/ ?>