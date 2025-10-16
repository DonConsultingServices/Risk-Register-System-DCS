#!/bin/bash
# cPanel File Permissions Setup Script
# Run this via cPanel Terminal or SSH if available

echo "Setting up file permissions for DCS Risk Register..."

# Navigate to your Laravel application directory
# Change this path to match your actual subdomain directory
cd /home/$(whoami)/public_html/register

# Set directory permissions
echo "Setting directory permissions..."
find . -type d -exec chmod 755 {} \;

# Set file permissions
echo "Setting file permissions..."
find . -type f -exec chmod 644 {} \;

# Set special permissions for Laravel directories
echo "Setting Laravel-specific permissions..."
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod 644 .env

# Set ownership (adjust username as needed)
echo "Setting ownership..."
chown -R $(whoami):$(whoami) .

# Verify permissions
echo "Verifying permissions..."
ls -la storage/
ls -la bootstrap/cache/
ls -la .env

echo "File permissions setup completed!"
echo "If you see any errors, contact your hosting provider."
