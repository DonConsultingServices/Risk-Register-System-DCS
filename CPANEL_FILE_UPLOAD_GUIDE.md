# 📁 cPanel File Upload Guide

**How to upload your DCS Risk Register files to your cPanel hosting**

## 🎯 Upload Methods

### Method 1: cPanel File Manager (Recommended)

1. **Login to your cPanel**
2. **Open "File Manager"**
3. **Navigate to your subdomain folder**:
   - Usually: `public_html/register` or similar
   - Or: `public_html/subdomains/register`
4. **Delete any existing files** (if this is a fresh setup)
5. **Upload your files**:

#### Step-by-Step Upload:
1. **Select all files** from your local project folder
2. **Right-click** → **Compress** → **ZIP** (create a ZIP file)
3. **Upload the ZIP file** to your subdomain folder
4. **Extract the ZIP file** in cPanel File Manager
5. **Delete the ZIP file** after extraction

### Method 2: FTP Client (Alternative)

1. **Download FileZilla** (free FTP client)
2. **Get FTP credentials** from cPanel:
   - Go to **"FTP Accounts"** in cPanel
   - Note down: Host, Username, Password, Port
3. **Connect to your server**
4. **Navigate to subdomain folder**
5. **Upload all files** from your local project

## 📂 File Structure After Upload

Your subdomain folder should look like this:
```
register/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          ← This should be your document root
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env             ← Your environment file
├── .htaccess        ← Web server configuration
├── artisan
├── composer.json
└── composer.lock
```

## ⚠️ Important Notes

### Files to Upload:
✅ **Upload ALL files** from your project folder  
✅ **Include the `vendor/` folder** (from `composer install`)  
✅ **Include the `.env` file** (copy from `env-cpanel.txt`)  

### Files to Exclude:
❌ **Don't upload** `node_modules/` (if exists)  
❌ **Don't upload** `.git/` folder  
❌ **Don't upload** temporary files  

## 🔧 Post-Upload Configuration

### 1. Set Document Root
- **Option A**: Point subdomain to `/public` folder in cPanel subdomain settings
- **Option B**: Create `.htaccess` in root folder to redirect to `/public`

### 2. Create Root `.htaccess` (if needed)
If your document root is not set to `/public`, create this file in your subdomain root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect all requests to public folder
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L,QSA]
</IfModule>
```

### 3. Verify File Permissions
After upload, check these permissions:
- **Folders**: 755
- **Files**: 644
- **storage/**: 775
- **bootstrap/cache/**: 775

## 🚨 Common Upload Issues

### Issue: "Upload failed" or "File too large"
**Solutions**:
- **Compress files** before upload
- **Upload in smaller batches**
- **Increase upload limits** in cPanel (if possible)
- **Use FTP** instead of File Manager

### Issue: "Permission denied" after upload
**Solutions**:
- **Set correct permissions** (755 for folders, 644 for files)
- **Use cPanel Terminal** to run: `chmod -R 755 .`
- **Contact hosting support** if issues persist

### Issue: "Files not showing" after upload
**Solutions**:
- **Check if files uploaded** to correct directory
- **Refresh File Manager**
- **Check for hidden files** (files starting with `.`)

## ✅ Upload Verification Checklist

After uploading, verify:
- [ ] All folders are uploaded (app, bootstrap, config, etc.)
- [ ] `vendor/` folder exists and has content
- [ ] `.env` file is uploaded and configured
- [ ] `public/` folder contains index.php
- [ ] File permissions are correct
- [ ] No error messages in upload process

## 🎯 Quick Upload Commands (if Terminal available)

If you have SSH/Terminal access:

```bash
# Navigate to your subdomain directory
cd /home/yourusername/public_html/register

# Upload via rsync (if you have SSH)
rsync -avz /path/to/local/project/ ./

# Set permissions
chmod -R 755 .
chmod -R 775 storage/ bootstrap/cache/
```

## 💡 Pro Tips

1. **Always backup** before uploading
2. **Test on a staging subdomain** first (if available)
3. **Keep local copy** of your files
4. **Use version control** (Git) for future updates
5. **Monitor upload progress** for large files

## 🆘 Need Help?

If you encounter issues:
1. **Check cPanel error logs**
2. **Contact your hosting provider**
3. **Try alternative upload method**
4. **Verify file permissions**

---

**Next Step**: After successful upload, follow the main deployment guide to configure your database and environment variables.
