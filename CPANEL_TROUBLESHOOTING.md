# 🔧 cPanel Troubleshooting Guide

**Common issues and solutions when deploying DCS Risk Register to cPanel**

## 🚨 Critical Issues

### Issue 1: "500 Internal Server Error"

**Symptoms**: Blank white page or "Internal Server Error"

**Solutions**:
1. **Check PHP Version**:
   - Go to cPanel → **"Select PHP Version"**
   - Ensure PHP 8.1 or higher is selected
   - Click **"Set as current"**

2. **Check Error Logs**:
   - Go to cPanel → **"Error Logs"**
   - Look for specific error messages
   - Common errors: PHP version, missing extensions, permissions

3. **Check File Permissions**:
   ```bash
   # Via cPanel Terminal (if available)
   chmod -R 755 .
   chmod -R 775 storage/ bootstrap/cache/
   ```

4. **Verify .htaccess**:
   - Ensure `.htaccess` exists in `/public` folder
   - Check for syntax errors

### Issue 2: "Database Connection Failed"

**Symptoms**: Database-related errors on application load

**Solutions**:
1. **Check Database Credentials**:
   ```env
   DB_HOST=localhost
   DB_DATABASE=your_full_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

2. **Verify Database Exists**:
   - Go to cPanel → **"MySQL Databases"**
   - Check if database and user exist
   - Ensure user has "All Privileges" on database

3. **Test Database Connection**:
   ```bash
   # Via cPanel Terminal
   mysql -h localhost -u your_username -p your_database_name
   ```

### Issue 3: "Application Key Not Set"

**Symptoms**: "No application encryption key has been specified"

**Solutions**:
1. **Generate Application Key**:
   ```bash
   # Via cPanel Terminal
   php artisan key:generate
   ```

2. **Manual Key Generation**:
   - Generate key: `php artisan key:generate --show`
   - Copy the key to `.env` file: `APP_KEY=base64:...`

## ⚠️ Common Issues

### Issue 4: "Styles/CSS Not Loading"

**Symptoms**: Website loads but looks broken, no styling

**Solutions**:
1. **Check Document Root**:
   - Ensure subdomain points to `/public` folder
   - Or create redirect in root `.htaccess`

2. **Clear Cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

3. **Check File Permissions**:
   ```bash
   chmod -R 755 public/
   ```

### Issue 5: "Permission Denied" Errors

**Symptoms**: Cannot write to storage, cache issues

**Solutions**:
1. **Set Correct Permissions**:
   ```bash
   chmod -R 775 storage/
   chmod -R 775 bootstrap/cache/
   chmod 644 .env
   ```

2. **Check Ownership**:
   ```bash
   chown -R username:username .
   ```

### Issue 6: "Composer Dependencies Missing"

**Symptoms**: Class not found errors, missing vendor files

**Solutions**:
1. **Re-upload vendor folder**:
   - Run `composer install --no-dev` locally
   - Upload the entire `vendor/` folder

2. **Install via Terminal** (if available):
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

### Issue 7: "SSL Certificate Issues"

**Symptoms**: HTTPS not working, mixed content warnings

**Solutions**:
1. **Enable SSL in cPanel**:
   - Go to **"SSL/TLS"**
   - Enable **"Force HTTPS Redirect"**

2. **Update APP_URL**:
   ```env
   APP_URL=https://register.dcs.com.na
   ```

## 🔍 Diagnostic Commands

### Check PHP Configuration
```bash
# Via cPanel Terminal
php -v                    # Check PHP version
php -m                    # List loaded modules
php -i | grep memory      # Check memory limit
```

### Check Laravel Status
```bash
# Via cPanel Terminal
php artisan --version     # Check Laravel version
php artisan route:list    # Check routes
php artisan config:show   # Check configuration
```

### Check Database Status
```bash
# Via cPanel Terminal
php artisan migrate:status    # Check migration status
php artisan db:show          # Show database info
```

## 📊 Performance Issues

### Issue 8: "Slow Loading"

**Solutions**:
1. **Enable Caching**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Check PHP Memory Limit**:
   - Increase in cPanel PHP settings
   - Recommended: 256M or higher

3. **Optimize Database**:
   - Run database optimization queries
   - Check for missing indexes

### Issue 9: "Memory Exhausted"

**Solutions**:
1. **Increase PHP Memory**:
   - cPanel → **"Select PHP Version"** → **"Options"**
   - Set `memory_limit` to 256M or higher

2. **Optimize Code**:
   - Check for memory-intensive operations
   - Use pagination for large datasets

## 🛠️ Emergency Recovery

### Complete Reset (if everything fails)

1. **Backup Current Files**:
   ```bash
   # Download current files via cPanel File Manager
   ```

2. **Fresh Upload**:
   - Delete all files in subdomain folder
   - Upload fresh copy of your application
   - Reconfigure environment variables

3. **Database Reset**:
   - Drop and recreate database
   - Run fresh migrations and seeders

## 📞 Getting Help

### cPanel Resources
- **cPanel Documentation**: Check your hosting provider's cPanel docs
- **Error Logs**: Always check cPanel Error Logs first
- **Hosting Support**: Contact your hosting provider for cPanel issues

### Laravel Resources
- **Laravel Logs**: Check `storage/logs/laravel.log`
- **Laravel Documentation**: Official Laravel docs
- **Stack Overflow**: Laravel community support

### Quick Health Check Commands
```bash
# Run these to check system health
php artisan about           # Laravel system info
php artisan config:cache    # Cache configuration
php artisan route:cache     # Cache routes
php artisan view:cache      # Cache views
php artisan migrate:status  # Check migrations
```

## ✅ Success Indicators

Your deployment is successful when:
- [ ] Application loads at `https://register.dcs.com.na`
- [ ] No errors in cPanel Error Logs
- [ ] Database connection working
- [ ] User login/registration working
- [ ] File uploads working
- [ ] SSL certificate active
- [ ] All pages loading correctly

---

**Remember**: Always backup before making changes and contact your hosting provider for cPanel-specific issues!
