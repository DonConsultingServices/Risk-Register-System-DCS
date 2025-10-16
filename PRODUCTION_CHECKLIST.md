# DCS-Best Risk Register - Production Deployment Checklist

## ✅ Pre-Deployment Checklist

### Environment Configuration
- [ ] `.env` file configured with production values
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` generated and set
- [ ] Database credentials configured
- [ ] Redis configuration set (if using)
- [ ] Mail configuration set (if using)
- [ ] `APP_URL` set to production domain

### Security Configuration
- [ ] Strong database passwords
- [ ] Strong Redis passwords (if using)
- [ ] Secure session configuration
- [ ] CSRF protection enabled
- [ ] Rate limiting configured
- [ ] File permissions set correctly (755 for directories, 644 for files)

### Database Setup
- [ ] Database created
- [ ] Migrations run successfully
- [ ] Initial data seeded (AML Compliance Risks)
- [ ] Database indexes optimized
- [ ] Backup strategy in place

### Application Optimization
- [ ] Composer dependencies installed with `--no-dev`
- [ ] Application key generated
- [ ] Configuration cached
- [ ] Routes cached
- [ ] Views cached
- [ ] Autoloader optimized

### Web Server Configuration
- [ ] Apache/Nginx configured
- [ ] Document root set to `public/` directory
- [ ] URL rewriting enabled
- [ ] SSL certificate installed
- [ ] Security headers configured
- [ ] Gzip compression enabled

### File System
- [ ] `storage/` directory writable
- [ ] `bootstrap/cache/` directory writable
- [ ] Log files writable
- [ ] Upload directories created and writable
- [ ] Proper ownership set (www-data:www-data on Linux)

## 🚀 Deployment Steps

### 1. Server Preparation
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required software
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip redis-server

# Configure firewall
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### 2. Database Setup
```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
mysql -u root -p
CREATE DATABASE dcs_risk_register;
CREATE USER 'dcs_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON dcs_risk_register.* TO 'dcs_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Application Deployment
```bash
# Clone or upload application files
# Set proper permissions
sudo chown -R www-data:www-data /var/www/dcs-risk-register
sudo chmod -R 755 /var/www/dcs-risk-register
sudo chmod -R 775 /var/www/dcs-risk-register/storage
sudo chmod -R 775 /var/www/dcs-risk-register/bootstrap/cache

# Run deployment script
./deploy.sh  # or deploy.bat on Windows
```

### 4. Web Server Configuration (Nginx)
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/dcs-risk-register/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

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

### 5. SSL Configuration
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d your-domain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## 🔍 Post-Deployment Testing

### Functionality Tests
- [ ] Login/logout works
- [ ] Dashboard loads correctly
- [ ] Risk creation works
- [ ] Client assessment workflow works
- [ ] Client history viewing works
- [ ] Approval process works
- [ ] Reports generate correctly
- [ ] Notifications work

### Performance Tests
- [ ] Page load times < 3 seconds
- [ ] Database queries optimized
- [ ] Cache working effectively
- [ ] Memory usage acceptable
- [ ] CPU usage acceptable

### Security Tests
- [ ] HTTPS redirect works
- [ ] SQL injection protection
- [ ] XSS protection
- [ ] CSRF tokens working
- [ ] File upload restrictions
- [ ] Access control working

### Browser Compatibility
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile browsers

## 📊 Monitoring Setup

### Application Monitoring
- [ ] Error logging enabled
- [ ] Performance monitoring
- [ ] Database query logging
- [ ] User activity logging
- [ ] Health check endpoint working

### System Monitoring
- [ ] Server resources monitoring
- [ ] Database performance monitoring
- [ ] Disk space monitoring
- [ ] Network monitoring
- [ ] Backup monitoring

### Alert Configuration
- [ ] Error rate alerts
- [ ] Performance degradation alerts
- [ ] Disk space alerts
- [ ] Database connection alerts
- [ ] SSL certificate expiry alerts

## 🔄 Maintenance Tasks

### Daily
- [ ] Check application logs
- [ ] Monitor system resources
- [ ] Verify backup completion
- [ ] Check SSL certificate status

### Weekly
- [ ] Review error logs
- [ ] Database optimization
- [ ] Cache cleanup
- [ ] Security updates check

### Monthly
- [ ] Dependency updates
- [ ] Security audit
- [ ] Performance review
- [ ] Backup restoration test

## 🆘 Troubleshooting

### Common Issues
1. **500 Internal Server Error**
   - Check file permissions
   - Verify .env configuration
   - Check Laravel logs
   - Verify PHP extensions

2. **Database Connection Issues**
   - Verify database credentials
   - Check database server status
   - Verify network connectivity
   - Check database permissions

3. **Cache Issues**
   - Clear application cache
   - Check Redis/Memcached status
   - Verify cache permissions
   - Restart cache services

4. **Performance Issues**
   - Check database queries
   - Review server resources
   - Optimize images/assets
   - Enable compression

### Log Locations
- Application: `storage/logs/laravel.log`
- Nginx: `/var/log/nginx/`
- PHP-FPM: `/var/log/php8.1-fpm.log`
- System: `/var/log/syslog`

## 📞 Support Information

### Emergency Contacts
- System Administrator: [Contact Info]
- Database Administrator: [Contact Info]
- Application Developer: [Contact Info]

### Documentation
- User Manual: [Location]
- Technical Documentation: [Location]
- API Documentation: [Location]

### Backup Information
- Database Backup Location: [Path]
- File Backup Location: [Path]
- Backup Schedule: [Details]
- Restoration Procedures: [Location]

---

**Deployment Date**: ___________
**Deployed By**: ___________
**Version**: 1.0.0
**Environment**: Production

**Sign-off**:
- [ ] System Administrator: ___________
- [ ] Database Administrator: ___________
- [ ] Security Officer: ___________
- [ ] Business Owner: ___________
