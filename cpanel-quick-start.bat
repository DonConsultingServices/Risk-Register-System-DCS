@echo off
echo ========================================
echo DCS Risk Register - cPanel Quick Start
echo ========================================
echo.

echo Preparing files for cPanel deployment...

REM Install Composer dependencies
echo Installing Composer dependencies...
composer install --no-dev --optimize-autoloader

REM Clear Laravel cache
echo Clearing Laravel cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

REM Generate application key
echo Generating application key...
php artisan key:generate

REM Create cPanel environment file
echo Creating cPanel environment file...
copy env-cpanel.txt .env.cpanel

echo.
echo ========================================
echo FILES READY FOR UPLOAD!
echo ========================================
echo.
echo Next steps:
echo 1. Login to your cPanel
echo 2. Go to File Manager
echo 3. Navigate to your subdomain folder
echo 4. Upload ALL files from this folder
echo 5. Follow the CPANEL_DEPLOYMENT_GUIDE.md
echo.
echo Important files to upload:
echo - All folders (app, bootstrap, config, etc.)
echo - vendor/ folder
echo - .env.cpanel (rename to .env after upload)
echo - All other files
echo.
echo ========================================
pause
