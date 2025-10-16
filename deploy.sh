#!/bin/bash

# DCS-Best Risk Register - Production Deployment Script
# This script prepares the application for production deployment

echo "🚀 Starting DCS-Best Risk Register Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "Please run this script from the Laravel application root directory"
    exit 1
fi

print_status "Preparing application for production deployment..."

# 1. Environment Setup
print_status "Setting up environment..."
if [ ! -f ".env" ]; then
    if [ -f "env.example" ]; then
        cp env.example .env
        print_success "Created .env file from env.example"
        print_warning "Please update .env file with your production values before continuing"
        print_warning "Critical variables to update:"
        print_warning "  - APP_KEY (run: php artisan key:generate)"
        print_warning "  - DB_DATABASE, DB_USERNAME, DB_PASSWORD"
        print_warning "  - REDIS_PASSWORD"
        print_warning "  - APP_URL"
        read -p "Press Enter after updating .env file..."
    else
        print_error "env.example file not found"
        exit 1
    fi
else
    print_success ".env file already exists"
fi

# 2. Install Dependencies
print_status "Installing production dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction
if [ $? -eq 0 ]; then
    print_success "Dependencies installed successfully"
else
    print_error "Failed to install dependencies"
    exit 1
fi

# 3. Generate Application Key
print_status "Generating application key..."
php artisan key:generate --force
if [ $? -eq 0 ]; then
    print_success "Application key generated"
else
    print_error "Failed to generate application key"
    exit 1
fi

# 4. Database Setup
print_status "Setting up database..."
php artisan migrate --force
if [ $? -eq 0 ]; then
    print_success "Database migrations completed"
else
    print_error "Database migration failed"
    exit 1
fi

# 5. Seed Initial Data
print_status "Seeding initial data..."
php artisan db:seed --class=AMLComplianceRiskSeeder --force
if [ $? -eq 0 ]; then
    print_success "Initial data seeded successfully"
else
    print_warning "Seeding failed or already completed"
fi

# 6. Clear and Optimize Caches
print_status "Optimizing application..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ $? -eq 0 ]; then
    print_success "Application optimized successfully"
else
    print_error "Failed to optimize application"
    exit 1
fi

# 7. Set Permissions
print_status "Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs/
if [ $? -eq 0 ]; then
    print_success "File permissions set correctly"
else
    print_warning "Failed to set some permissions (may need sudo)"
fi

# 8. Create Health Check
print_status "Creating health check endpoint..."
cat > public/health.php << 'EOF'
<?php
// Simple health check endpoint
header('Content-Type: application/json');

$health = [
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'version' => '1.0.0',
    'environment' => $_ENV['APP_ENV'] ?? 'unknown'
];

// Check database connection
try {
    $pdo = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    $health['database'] = 'connected';
} catch (Exception $e) {
    $health['database'] = 'disconnected';
    $health['status'] = 'unhealthy';
}

echo json_encode($health, JSON_PRETTY_PRINT);
EOF

print_success "Health check endpoint created at /health.php"

# 9. Security Check
print_status "Running security checks..."

# Check for sensitive files
if [ -f ".env" ] && grep -q "APP_DEBUG=true" .env; then
    print_warning "APP_DEBUG is set to true - should be false in production"
fi

if [ -f ".env" ] && grep -q "APP_KEY=$" .env; then
    print_error "APP_KEY is empty - please run: php artisan key:generate"
fi

# 10. Final Status
print_status "Deployment preparation completed!"
echo ""
print_success "✅ Application is ready for production"
echo ""
print_status "Next steps:"
echo "  1. Update .env file with production values"
echo "  2. Configure web server (Apache/Nginx)"
echo "  3. Set up SSL certificate"
echo "  4. Configure firewall"
echo "  5. Set up monitoring and backups"
echo ""
print_status "Test your deployment:"
echo "  - Health check: curl http://your-domain.com/health.php"
echo "  - Application: http://your-domain.com"
echo ""
print_status "Documentation: See DEPLOYMENT_GUIDE.md for detailed instructions"
echo ""
print_success "🎉 Deployment preparation completed successfully!"
