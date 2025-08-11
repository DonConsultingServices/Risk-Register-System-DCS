<?php
/**
 * DCS-Best Risk Register - Installation Script
 * 
 * This script sets up the database and creates the initial structure
 * for the risk register system.
 */

// Prevent direct access if already installed
if (file_exists('config/installed.lock')) {
    die("System is already installed. Remove config/installed.lock to reinstall.");
}

// Load database configuration
$config = require_once 'config/database.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>DCS-Best Risk Register - Installation</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>
    <style>
        body { background-color: #f8f9fa; }
        .install-container { max-width: 800px; margin: 50px auto; }
        .step { margin-bottom: 30px; }
        .step-header { background: #3498db; color: white; padding: 15px; border-radius: 5px; }
        .step-content { background: white; padding: 20px; border-radius: 5px; margin-top: 10px; }
        .success { color: #27ae60; }
        .error { color: #e74c3c; }
        .warning { color: #f39c12; }
    </style>
</head>
<body>
    <div class='container install-container'>
        <div class='text-center mb-4'>
            <h1><i class='fas fa-shield-alt text-primary'></i> DCS-Best Risk Register</h1>
            <h3>Installation Wizard</h3>
        </div>";

try {
    echo "<div class='step'>
            <div class='step-header'>
                <h5><i class='fas fa-database me-2'></i>Step 1: Database Connection</h5>
            </div>
            <div class='step-content'>";
    
    // Test database connection
    $dsn = "mysql:host={$config['host']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    echo "<p class='success'><i class='fas fa-check-circle'></i> Database connection successful</p>";
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    echo "<p class='success'><i class='fas fa-check-circle'></i> Database '{$config['database']}' created successfully</p>";
    
    // Connect to the specific database
    $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    echo "</div></div>";
    
    echo "<div class='step'>
            <div class='step-header'>
                <h5><i class='fas fa-table me-2'></i>Step 2: Create Tables</h5>
            </div>
            <div class='step-content'>";
    
    // Create risks table
    $sql = "CREATE TABLE IF NOT EXISTS `risks` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `risk_identification` varchar(255) DEFAULT NULL,
        `risk_name` varchar(255) NOT NULL,
        `identification_date` date DEFAULT CURRENT_TIMESTAMP,
        `subtitle` varchar(255) DEFAULT NULL,
        `risk_description` text DEFAULT NULL,
        `risk_category` varchar(255) DEFAULT NULL,
        `risk_likelihood` enum('Not likely','Likely','Very likely') DEFAULT NULL,
        `risk_analysis` text DEFAULT NULL,
        `risk_impact_level` enum('Very low','Low','Medium','High','Very high') DEFAULT NULL,
        `risk_mitigation_plan` text DEFAULT NULL,
        `risk_priority` enum('1','2','3') DEFAULT '1',
        `risk_owner` varchar(255) DEFAULT NULL,
        `risk_status` enum('Open','In progress','Closed','Active','Not started','Hold','Ongoing','Complete') DEFAULT 'Open',
        `risk_trigger` text DEFAULT NULL,
        `response_type` varchar(255) DEFAULT NULL,
        `timeline` date DEFAULT NULL,
        `client_type` enum('Natural Person','Legal Person') DEFAULT NULL,
        `service_type` varchar(255) DEFAULT NULL,
        `geographical_area` enum('Domestic client','Regional client','Foreign client') DEFAULT NULL,
        `delivery_channel` varchar(255) DEFAULT NULL,
        `payment_method` enum('EFTs','SWIFT','Cash','POS') DEFAULT NULL,
        `inherent_risk_rating` int(11) DEFAULT NULL,
        `residual_risk_rating` int(11) DEFAULT NULL,
        `total_risk_rating` int(11) DEFAULT NULL,
        `risk_assessment` varchar(255) DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `deleted_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $pdo->exec($sql);
    echo "<p class='success'><i class='fas fa-check-circle'></i> Risks table created successfully</p>";
    
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
    echo "<p class='success'><i class='fas fa-check-circle'></i> Users table created successfully</p>";
    
    echo "</div></div>";
    
    echo "<div class='step'>
            <div class='step-header'>
                <h5><i class='fas fa-seedling me-2'></i>Step 3: Create Sample Users</h5>
            </div>
            <div class='step-content'>";
    
    // Insert sample users
    $sampleUsers = [
        [
            'username' => 'admin',
            'email' => 'admin@dcs.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'role' => 'admin',
            'department' => 'IT & Compliance'
        ],
        [
            'username' => 'compliance',
            'email' => 'compliance@dcs.com',
            'password' => password_hash('compliance123', PASSWORD_DEFAULT),
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'role' => 'manager',
            'department' => 'Compliance'
        ],
        [
            'username' => 'aml_officer',
            'email' => 'aml@dcs.com',
            'password' => password_hash('aml123', PASSWORD_DEFAULT),
            'first_name' => 'Michael',
            'last_name' => 'Chen',
            'role' => 'manager',
            'department' => 'AML & Risk'
        ],
        [
            'username' => 'auditor1',
            'email' => 'auditor1@dcs.com',
            'password' => password_hash('auditor123', PASSWORD_DEFAULT),
            'first_name' => 'Lisa',
            'last_name' => 'Williams',
            'role' => 'staff',
            'department' => 'Audit'
        ],
        [
            'username' => 'viewer',
            'email' => 'viewer@dcs.com',
            'password' => password_hash('viewer123', PASSWORD_DEFAULT),
            'first_name' => 'David',
            'last_name' => 'Brown',
            'role' => 'viewer',
            'department' => 'Management'
        ]
    ];

    $userStmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, department) VALUES (?, ?, ?, ?, ?, ?, ?)");

    $userCount = 0;
    foreach ($sampleUsers as $user) {
        try {
            $userStmt->execute([
                $user['username'],
                $user['email'],
                $user['password'],
                $user['first_name'],
                $user['last_name'],
                $user['role'],
                $user['department']
            ]);
            $userCount++;
        } catch (Exception $e) {
            echo "<p class='warning'><i class='fas fa-exclamation-triangle'></i> Warning: Could not insert user {$user['username']}: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<p class='success'><i class='fas fa-check-circle'></i> {$userCount} sample users created successfully</p>";
    
    echo "</div></div>";
    
    echo "<div class='step'>
            <div class='step-header'>
                <h5><i class='fas fa-seedling me-2'></i>Step 4: Insert Sample Risk Data</h5>
            </div>
            <div class='step-content'>";
    
    // Insert sample data
    $sampleData = [
        [
            'risk_name' => 'High-Risk Foreign Client',
            'identification_date' => '2024-01-15',
            'subtitle' => 'AML compliance risk',
            'risk_description' => 'Foreign client from high-risk jurisdiction with complex ownership structure requiring enhanced due diligence under FIC regulations.',
            'risk_category' => 'Compliance',
            'risk_likelihood' => 'Likely',
            'risk_impact_level' => 'High',
            'risk_priority' => '3',
            'risk_owner' => 'Compliance Officer',
            'risk_status' => 'Open',
            'client_type' => 'Legal Person',
            'service_type' => 'Accounting & bookkeeping services',
            'geographical_area' => 'Foreign client',
            'delivery_channel' => 'Non-face to face',
            'payment_method' => 'SWIFT',
            'total_risk_rating' => 8,
            'risk_assessment' => 'Medium Risk Rated Client - Accept'
        ],
        [
            'risk_name' => 'Cash Transaction Risk',
            'identification_date' => '2024-01-16',
            'subtitle' => 'FIC reporting requirement',
            'risk_description' => 'Client requesting cash payments above FIC reporting thresholds, requiring enhanced monitoring and suspicious transaction reporting.',
            'risk_category' => 'Operations',
            'risk_likelihood' => 'Very likely',
            'risk_impact_level' => 'Very high',
            'risk_priority' => '3',
            'risk_owner' => 'AML Officer',
            'risk_status' => 'In progress',
            'client_type' => 'Natural Person',
            'service_type' => 'Tax consulting and advisory',
            'geographical_area' => 'Domestic client',
            'delivery_channel' => 'Face to face',
            'payment_method' => 'Cash',
            'total_risk_rating' => 9,
            'risk_assessment' => 'High Risk Rated Client - Enhanced Due Diligence Required'
        ],
        [
            'risk_name' => 'PEP Client Risk',
            'identification_date' => '2024-01-17',
            'subtitle' => 'Politically Exposed Person',
            'risk_description' => 'Client identified as Politically Exposed Person (PEP) requiring enhanced due diligence and ongoing monitoring under AML regulations.',
            'risk_category' => 'Compliance',
            'risk_likelihood' => 'Likely',
            'risk_impact_level' => 'High',
            'risk_priority' => '3',
            'risk_owner' => 'Risk Manager',
            'risk_status' => 'Open',
            'client_type' => 'Natural Person',
            'service_type' => 'Risk advisory',
            'geographical_area' => 'Regional client',
            'delivery_channel' => 'Combination of face to face and non-face to face',
            'payment_method' => 'EFTs',
            'total_risk_rating' => 7,
            'risk_assessment' => 'Medium Risk Rated Client - Accept'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO risks (risk_name, identification_date, subtitle, risk_description, risk_category, risk_likelihood, risk_impact_level, risk_mitigation_plan, risk_priority, risk_owner, risk_status, client_type, service_type, geographical_area, delivery_channel, payment_method, total_risk_rating, risk_assessment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $insertedCount = 0;
    foreach ($sampleData as $data) {
        try {
            $stmt->execute([
                $data['risk_name'],
                $data['identification_date'],
                $data['subtitle'],
                $data['risk_description'],
                $data['risk_category'],
                $data['risk_likelihood'],
                $data['risk_impact_level'],
                $data['risk_mitigation_plan'] ?? '',
                $data['risk_priority'],
                $data['risk_owner'],
                $data['risk_status'],
                $data['client_type'],
                $data['service_type'],
                $data['geographical_area'],
                $data['delivery_channel'],
                $data['payment_method'],
                $data['total_risk_rating'],
                $data['risk_assessment']
            ]);
            $insertedCount++;
        } catch (Exception $e) {
            echo "<p class='warning'><i class='fas fa-exclamation-triangle'></i> Warning: Could not insert sample data: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<p class='success'><i class='fas fa-check-circle'></i> {$insertedCount} sample records inserted successfully</p>";
    
    echo "</div></div>";
    
    echo "<div class='step'>
            <div class='step-header'>
                <h5><i class='fas fa-check-double me-2'></i>Step 5: Finalize Installation</h5>
            </div>
            <div class='step-content'>";
    
    // Create installation lock file
    file_put_contents('config/installed.lock', date('Y-m-d H:i:s'));
    echo "<p class='success'><i class='fas fa-check-circle'></i> Installation completed successfully</p>";
    
    echo "<div class='alert alert-success'>
            <h5><i class='fas fa-trophy'></i> Installation Complete!</h5>
            <p>Your DCS-Best Risk Register system has been successfully installed.</p>
            <hr>
            <p><strong>Sample Users Created:</strong></p>
            <div class='table-responsive'>
                <table class='table table-sm'>
                    <thead>
                        <tr><th>Username</th><th>Password</th><th>Role</th><th>Department</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>admin</td><td>admin123</td><td>Admin</td><td>IT & Compliance</td></tr>
                        <tr><td>compliance</td><td>compliance123</td><td>Manager</td><td>Compliance</td></tr>
                        <tr><td>aml_officer</td><td>aml123</td><td>Manager</td><td>AML & Risk</td></tr>
                        <tr><td>auditor1</td><td>auditor123</td><td>Staff</td><td>Audit</td></tr>
                        <tr><td>viewer</td><td>viewer123</td><td>Viewer</td><td>Management</td></tr>
                    </tbody>
                </table>
            </div>
            <p><strong>Next Steps:</strong></p>
            <ul>
                <li>Access your system at: <a href='public/index.php' class='alert-link'>http://localhost/DCS-Best/public/index.php</a></li>
                <li>Sign in with one of the sample users above</li>
                <li>Review the sample risk data to understand the system</li>
                <li>Customize user roles and permissions as needed</li>
            </ul>
            <div class='mt-3'>
                <a href='public/index.php' class='btn btn-primary'>
                    <i class='fas fa-rocket me-1'></i>Launch Application
                </a>
                <a href='README.md' class='btn btn-outline-secondary ms-2'>
                    <i class='fas fa-book me-1'></i>View Documentation
                </a>
            </div>
          </div>";
    
    echo "</div></div>";

} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <h5><i class='fas fa-exclamation-triangle'></i> Installation Failed</h5>
            <p><strong>Error:</strong> " . $e->getMessage() . "</p>
            <hr>
            <p><strong>Troubleshooting:</strong></p>
            <ul>
                <li>Ensure MySQL service is running</li>
                <li>Check database credentials in config/database.php</li>
                <li>Verify PHP has PDO MySQL extension enabled</li>
                <li>Ensure proper file permissions</li>
            </ul>
            <div class='mt-3'>
                <a href='install.php' class='btn btn-primary'>Retry Installation</a>
            </div>
          </div>";
}

echo "</div>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
?> 