<?php
echo "🚀 Quick Database Setup for DCS Risk Register\n";
echo "=============================================\n\n";

try {
    // Load config
    $config = require_once 'config/database.php';
    
    // Connect to MySQL (without database first)
    $dsn = "mysql:host={$config['host']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    echo "✅ Connected to MySQL server\n";
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}`");
    echo "✅ Database '{$config['database']}' ready\n";
    
    // Connect to the specific database
    $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS `users` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL UNIQUE,
        `email` varchar(100) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `first_name` varchar(50) NOT NULL,
        `last_name` varchar(50) NOT NULL,
        `role` enum('admin','manager','staff','viewer') DEFAULT 'staff',
        `department` varchar(100) DEFAULT NULL,
        `is_active` tinyint(1) DEFAULT 1,
        `last_login` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Users table created\n";
    
    // Create risks table
    $sql = "CREATE TABLE IF NOT EXISTS `risks` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `risk_identification` varchar(255) NOT NULL,
        `risk_description` text,
        `risk_category` varchar(100),
        `risk_likelihood` varchar(50),
        `risk_impact_level` varchar(50),
        `risk_analysis` text,
        `risk_mitigation` text,
        `risk_priority` varchar(50),
        `risk_ownership` varchar(100),
        `risk_status` varchar(50),
        `client_type` varchar(100),
        `service_type` varchar(100),
        `geographical_area` varchar(100),
        `delivery_channel` varchar(100),
        `payment_method` varchar(100),
        `total_risk_rating` int,
        `risk_assessment` varchar(255),
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Risks table created\n";
    
    // Check if users exist, if not create sample users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $count = $stmt->fetch()['count'];
    
    if ($count == 0) {
        echo "📝 Creating sample users...\n";
        
        $users = [
            ['admin', 'admin@dcs.com', 'password123', 'Admin', 'User', 'admin', 'IT'],
            ['manager', 'manager@dcs.com', 'password123', 'Manager', 'User', 'manager', 'Management'],
            ['staff', 'staff@dcs.com', 'password123', 'Staff', 'User', 'staff', 'Operations'],
            ['viewer', 'viewer@dcs.com', 'password123', 'Viewer', 'User', 'viewer', 'Compliance']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, department) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($users as $user) {
            $hashedPassword = password_hash($user[2], PASSWORD_DEFAULT);
            $stmt->execute([$user[0], $user[1], $hashedPassword, $user[3], $user[4], $user[5], $user[6]]);
            echo "  ✅ Created user: {$user[0]} ({$user[5]})\n";
        }
    } else {
        echo "📊 Found $count existing users\n";
    }
    
    // Check if risks exist, if not create sample risks
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM risks");
    $count = $stmt->fetch()['count'];
    
    if ($count == 0) {
        echo "📝 Creating sample risks...\n";
        
        $risks = [
            ['High-Risk Foreign Client', 'Client from high-risk jurisdiction with complex ownership structure', 'Client Risk', 'Likely', 'High', 'Requires enhanced due diligence', 'Enhanced monitoring and reporting', 'High', 'Compliance Team', 'Active'],
            ['Cash Transaction Risk', 'Large cash deposits without clear source of funds', 'Transaction Risk', 'Very likely', 'Very high', 'Potential money laundering risk', 'Cash transaction limits and monitoring', 'Critical', 'Risk Management', 'Active'],
            ['PEP Client Risk', 'Politically Exposed Person with high-value transactions', 'Client Risk', 'Likely', 'High', 'Requires special attention', 'Enhanced due diligence and monitoring', 'High', 'Compliance Team', 'Active']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO risks (risk_identification, risk_description, risk_category, risk_likelihood, risk_impact_level, risk_analysis, risk_mitigation, risk_priority, risk_ownership, risk_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($risks as $risk) {
            $stmt->execute($risk);
            echo "  ✅ Created risk: {$risk[0]}\n";
        }
    } else {
        echo "📊 Found $count existing risks\n";
    }
    
    echo "\n🎉 Installation completed successfully!\n";
    echo "\n📋 Login Credentials:\n";
    echo "  👤 Username: admin | Password: password123 (Full access)\n";
    echo "  👤 Username: manager | Password: password123 (Manager access)\n";
    echo "  👤 Username: staff | Password: password123 (Staff access)\n";
    echo "  👤 Username: viewer | Password: password123 (View-only access)\n";
    echo "\n🌐 Access your system at: http://localhost/DCS-Best/public/login.php\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 