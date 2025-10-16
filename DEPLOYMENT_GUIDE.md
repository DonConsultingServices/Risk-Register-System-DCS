# DCS-Best Risk Register - Deployment Guide

## Pre-Deployment Checklist ✅

### Critical Issues Fixed
- [x] Created comprehensive `env.example` file with all required variables
- [x] Fixed Redis password configuration in database config
- [x] Standardized error handling across controllers
- [x] Consolidated duplicate CSS files
- [x] Enhanced rate limiting configuration
- [x] Implemented AML compliance risk categories (CR-, SR-, PR-, DR-)
- [x] Fixed client history API authentication issues
- [x] Added date restrictions for assessments (current date only)
- [x] Enhanced client lookup and history tracking system
- [x] Optimized database queries and caching

## Deployment Steps

### 1. Environment Setup
```bash
# Copy environment template
cp env.example .env

# Edit .env file with your production values
nano .env
```

### 2. Required Environment Variables
Ensure these critical variables are set in your `.env` file:

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=dcs_risk_register
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

# Redis (Recommended for production)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=your-redis-host
REDIS_PASSWORD=your-redis-password

# Security
SESSION_SECURE_COOKIES=true
SESSION_HTTP_ONLY=true
```

### 3. Installation Commands
```bash
# Install dependencies (production only)
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Clear and optimize caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Web Server Configuration

#### Apache (.htaccess should be included)
```apache
# Enable mod_rewrite
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]
```

#### Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/app/public;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    
    index index.php;
    
    charset utf-8;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 5. SSL Configuration (Recommended)
```bash
# Using Let's Encrypt
sudo certbot --nginx -d your-domain.com
```

### 6. Performance Optimization

#### Enable Redis (Recommended)
```bash
# Install Redis
sudo apt-get install redis-server

# Start Redis
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

#### Database Optimization
```sql
-- Add these indexes if not already present
ALTER TABLE risks ADD INDEX idx_risks_perf_1 (deleted_at, status);
ALTER TABLE clients ADD INDEX idx_clients_perf_1 (assessment_status, deleted_at);
```

### 7. Monitoring Setup

#### Log Rotation
```bash
# Configure log rotation
sudo nano /etc/logrotate.d/dcs-risk-register
```

Add:
```
/path/to/your/app/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0644 www-data www-data
}
```

#### Health Check
Create a simple health check endpoint:
```bash
curl https://your-domain.com/health
```

### 8. Security Checklist

- [ ] Change default database passwords
- [ ] Use strong Redis passwords
- [ ] Enable HTTPS/SSL
- [ ] Set secure session cookies
- [ ] Configure firewall (UFW/iptables)
- [ ] Regular security updates
- [ ] Backup strategy in place

### 9. Backup Configuration

#### Database Backup
```bash
#!/bin/bash
# daily-backup.sh
mysqldump -u username -p dcs_risk_register > /backups/dcs_risk_register_$(date +%Y%m%d).sql
```

#### File Backup
```bash
#!/bin/bash
# file-backup.sh
tar -czf /backups/dcs_risk_register_files_$(date +%Y%m%d).tar.gz /path/to/your/app
```

### 10. Post-Deployment Testing

1. **Functionality Tests**
   - Login/logout
   - Risk creation and management
   - Client assessment workflow
   - Dashboard statistics
   - Notification system

2. **Performance Tests**
   - Page load times
   - Database query performance
   - Cache effectiveness

3. **Security Tests**
   - SQL injection prevention
   - XSS protection
   - CSRF token validation

### 11. Troubleshooting

#### Common Issues

1. **500 Error**
   - Check file permissions
   - Verify .env configuration
   - Check Laravel logs

2. **Database Connection Issues**
   - Verify database credentials
   - Check network connectivity
   - Ensure database exists

3. **Cache Issues**
   - Clear application cache
   - Check Redis connection
   - Verify cache permissions

#### Log Locations
- Application logs: `storage/logs/laravel.log`
- Web server logs: `/var/log/apache2/` or `/var/log/nginx/`
- System logs: `/var/log/syslog`

### 12. Maintenance

#### Regular Tasks
- Monitor disk space
- Check application logs
- Update dependencies (monthly)
- Database optimization (weekly)
- Backup verification (daily)

#### Performance Monitoring
- Use `htop` or `top` for system resources
- Monitor database performance
- Check cache hit rates
- Review slow query logs

## Support

For technical support or issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Application documentation
3. Database query logs (if enabled)

---

**Note**: This deployment guide assumes a standard LAMP/LEMP stack. Adjust configurations based on your specific hosting environment.
