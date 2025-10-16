-- DCS Risk Register Database Setup for cPanel
-- Run this in your cPanel MySQL database after creating it

-- Create database (if not exists - usually done via cPanel)
-- CREATE DATABASE IF NOT EXISTS `register_dcs_risk` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
-- USE `register_dcs_risk`;

-- Set timezone (optional)
SET time_zone = '+02:00';

-- Create a backup table for important data (optional)
CREATE TABLE IF NOT EXISTS `deployment_log` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `deployment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `version` varchar(255) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert deployment record
INSERT INTO `deployment_log` (`version`, `notes`) VALUES ('1.0.0', 'Initial deployment to cPanel hosting');

-- Optimize database for better performance
OPTIMIZE TABLE `deployment_log`;

-- Show database info
SELECT 'Database setup completed successfully' as status;
SELECT DATABASE() as current_database;
SELECT NOW() as current_time;
