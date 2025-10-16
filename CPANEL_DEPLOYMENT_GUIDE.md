# 🚀 cPanel Deployment Guide for DCS Risk Register

**Deploy your Laravel application to `register.dcs.com.na` using cPanel hosting**

## 📋 Prerequisites Checklist

Before starting, ensure your cPanel hosting has:
- ✅ **PHP 8.1 or higher** (Laravel 10 requirement)
- ✅ **MySQL database** access
- ✅ **Composer** (check if available)
- ✅ **File Manager** or **FTP access**
- ✅ **SSL certificate** (usually included)

## 🎯 Step-by-Step Deployment

### Step 1: Prepare Your Local Files

1. **Open your project folder** (`C:\xampp\htdocs\DCS-Best`)

2. **Run these commands** to prepare files:
   ```bash
   # Install dependencies
   composer install --no-dev --optimize-autoloader
   
   # Clear cache
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   
   # Generate application key
   php artisan key:generate
   ```

3. **Copy the cPanel environment file**:
   ```bash
   copy .env.cpanel .env
   ```

### Step 2: Create Database in cPanel

1. **Login to your cPanel**
2. **Go to "MySQL Databases"**
3. **Create new database**:
   - Database name: `register_dcs_risk` (or similar)
   - Note down the full database name (usually includes your username)
4. **Create database user**:
   - Username: `register_user` (or similar)
   - Password: Generate a strong password
5. **Add user to database** with "All Privileges"

### Step 3: Upload Files to cPanel

#### Option A: Using cPanel File Manager
1. **Login to cPanel**
2. **Open "File Manager"**
3. **Navigate to your subdomain folder** (usually `public_html/register` or similar)
4. **Upload all files** from your local project folder
5. **Extract if needed** (if uploaded as ZIP)

#### Option B: Using FTP Client
1. **Use FileZilla** or similar FTP client
2. **Connect to your hosting** using cPanel FTP credentials
3. **Upload all files** to the subdomain directory

### Step 4: Configure Environment Variables

1. **In cPanel File Manager**, find your uploaded `.env` file
2. **Edit the file** and update these values:

```env
# Application
APP_NAME="DCS Risk Register"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://register.dcs.com.na

# Database (from Step 2)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_full_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

# Cache and Sessions
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Mail (configure with your hosting provider)
MAIL_MAILER=smtp
MAIL_HOST=your-hosting-smtp-server
MAIL_PORT=587
MAIL_USERNAME=your-email@dcs.com.na
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
```

### Step 5: Set File Permissions

1. **In cPanel File Manager**, set these permissions:
   - `storage/` folder: **755**
   - `bootstrap/cache/` folder: **755**
   - `.env` file: **644**

### Step 6: Run Laravel Setup Commands

#### Option A: Using cPanel Terminal (if available)
```bash
cd /home/yourusername/public_html/register
php artisan migrate
php artisan db:seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Option B: Using cPanel Cron Jobs
1. **Go to "Cron Jobs"** in cPanel
2. **Add these commands**:
   ```
   * * * * * cd /home/yourusername/public_html/register && php artisan schedule:run >> /dev/null 2>&1
   ```

### Step 7: Configure Web Server

1. **Create `.htaccess`** file in your subdomain root (if not exists):
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^(.*)$ public/$1 [L]
   </IfModule>
   ```

2. **Or point document root** to `/public` folder in cPanel subdomain settings

### Step 8: Test Your Application

1. **Visit**: `https://register.dcs.com.na`
2. **Check if application loads**
3. **Test login functionality**
4. **Verify database connection**

## 🔧 Troubleshooting Common Issues

### Issue: "Application key not set"
**Solution**: 
```bash
php artisan key:generate
```

### Issue: "Database connection failed"
**Solution**: 
- Check database credentials in `.env`
- Verify database exists and user has permissions
- Check if database host is `localhost`

### Issue: "Permission denied"
**Solution**:
- Set correct file permissions (755 for folders, 644 for files)
- Ensure `storage/` and `bootstrap/cache/` are writable

### Issue: "500 Internal Server Error"
**Solution**:
- Check error logs in cPanel
- Verify PHP version is 8.1+
- Check file permissions

### Issue: "Styles/CSS not loading"
**Solution**:
- Run: `php artisan config:cache`
- Check if document root points to `/public` folder

## 📞 Support Information

### cPanel Features to Use:
- **Error Logs**: Check for PHP errors
- **PHP Version**: Ensure 8.1+
- **Database**: MySQL management
- **SSL**: Enable HTTPS
- **Backups**: Regular database backups

### Important Files to Monitor:
- `.env` - Environment configuration
- `storage/logs/` - Application logs
- `public/` - Web-accessible files

## ✅ Post-Deployment Checklist

- [ ] Application loads at `https://register.dcs.com.na`
- [ ] Database connection working
- [ ] User registration/login working
- [ ] File uploads working
- [ ] SSL certificate active
- [ ] Error logs clean
- [ ] Performance acceptable

## 🎉 Congratulations!

Your DCS Risk Register is now live at `https://register.dcs.com.na`!

### Next Steps:
1. **Set up regular backups**
2. **Monitor application performance**
3. **Configure email notifications**
4. **Set up monitoring alerts**

---

**Need Help?** Check the troubleshooting section or contact your hosting provider for cPanel-specific issues.
