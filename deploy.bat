@echo off
REM DCS-Best Risk Register - Production Deployment Script (Windows)
REM This script prepares the application for production deployment

echo 🚀 Starting DCS-Best Risk Register Deployment...

REM Check if we're in the right directory
if not exist "artisan" (
    echo [ERROR] Please run this script from the Laravel application root directory
    pause
    exit /b 1
)

echo [INFO] Preparing application for production deployment...

REM 1. Environment Setup
echo [INFO] Setting up environment...
if not exist ".env" (
    if exist "env.example" (
        copy env.example .env
        echo [SUCCESS] Created .env file from env.example
        echo [WARNING] Please update .env file with your production values before continuing
        echo [WARNING] Critical variables to update:
        echo [WARNING]   - APP_KEY (run: php artisan key:generate)
        echo [WARNING]   - DB_DATABASE, DB_USERNAME, DB_PASSWORD
        echo [WARNING]   - REDIS_PASSWORD
        echo [WARNING]   - APP_URL
        pause
    ) else (
        echo [ERROR] env.example file not found
        pause
        exit /b 1
    )
) else (
    echo [SUCCESS] .env file already exists
)

REM 2. Install Dependencies
echo [INFO] Installing production dependencies...
composer install --optimize-autoloader --no-dev --no-interaction
if %errorlevel% neq 0 (
    echo [ERROR] Failed to install dependencies
    pause
    exit /b 1
)
echo [SUCCESS] Dependencies installed successfully

REM 3. Generate Application Key
echo [INFO] Generating application key...
php artisan key:generate --force
if %errorlevel% neq 0 (
    echo [ERROR] Failed to generate application key
    pause
    exit /b 1
)
echo [SUCCESS] Application key generated

REM 4. Database Setup
echo [INFO] Setting up database...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo [ERROR] Database migration failed
    pause
    exit /b 1
)
echo [SUCCESS] Database migrations completed

REM 5. Seed Initial Data
echo [INFO] Seeding initial data...
php artisan db:seed --class=AMLComplianceRiskSeeder --force
if %errorlevel% neq 0 (
    echo [WARNING] Seeding failed or already completed
) else (
    echo [SUCCESS] Initial data seeded successfully
)

REM 6. Clear and Optimize Caches
echo [INFO] Optimizing application...
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

if %errorlevel% neq 0 (
    echo [ERROR] Failed to optimize application
    pause
    exit /b 1
)
echo [SUCCESS] Application optimized successfully

REM 7. Create Health Check
echo [INFO] Creating health check endpoint...
(
echo ^<?php
echo // Simple health check endpoint
echo header^('Content-Type: application/json'^);
echo.
echo $health = [
echo     'status' =^> 'healthy',
echo     'timestamp' =^> date^('Y-m-d H:i:s'^),
echo     'version' =^> '1.0.0',
echo     'environment' =^> $_ENV['APP_ENV'] ?? 'unknown'
echo ];
echo.
echo // Check database connection
echo try {
echo     $pdo = new PDO^(
echo         'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
echo         $_ENV['DB_USERNAME'],
echo         $_ENV['DB_PASSWORD']
echo     ^);
echo     $health['database'] = 'connected';
echo } catch ^(Exception $e^) {
echo     $health['database'] = 'disconnected';
echo     $health['status'] = 'unhealthy';
echo }
echo.
echo echo json_encode^($health, JSON_PRETTY_PRINT^);
echo ^?^>
) > public\health.php

echo [SUCCESS] Health check endpoint created at /health.php

REM 8. Security Check
echo [INFO] Running security checks...

REM Check for sensitive files
findstr /C:"APP_DEBUG=true" .env >nul 2>&1
if %errorlevel% equ 0 (
    echo [WARNING] APP_DEBUG is set to true - should be false in production
)

findstr /C:"APP_KEY=$" .env >nul 2>&1
if %errorlevel% equ 0 (
    echo [ERROR] APP_KEY is empty - please run: php artisan key:generate
)

REM 9. Final Status
echo [INFO] Deployment preparation completed!
echo.
echo [SUCCESS] ✅ Application is ready for production
echo.
echo [INFO] Next steps:
echo   1. Update .env file with production values
echo   2. Configure web server (Apache/Nginx)
echo   3. Set up SSL certificate
echo   4. Configure firewall
echo   5. Set up monitoring and backups
echo.
echo [INFO] Test your deployment:
echo   - Health check: http://your-domain.com/health.php
echo   - Application: http://your-domain.com
echo.
echo [INFO] Documentation: See DEPLOYMENT_GUIDE.md for detailed instructions
echo.
echo [SUCCESS] 🎉 Deployment preparation completed successfully!
pause
