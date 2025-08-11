<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include authentication and authorization
require_once 'auth.php';

// Check if user is logged in and has settings permission
if (!isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

requirePermission('manage_settings');

// Database connection
$host = 'localhost';
$dbname = 'dcs_risk_register';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create settings table if it doesn't exist
$createSettingsTable = "CREATE TABLE IF NOT EXISTS system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    category VARCHAR(50) DEFAULT 'general',
    is_public TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$pdo->exec($createSettingsTable);

// Initialize default settings if they don't exist
$defaultSettings = [
    ['company_name', 'DCS Risk Assessment System', 'string', 'Company name displayed in the system', 'company'],
    ['company_email', 'admin@dcs.com', 'string', 'Primary contact email for the system', 'company'],
    ['risk_assessment_expiry_days', '365', 'number', 'Number of days before risk assessments expire', 'risk'],
    ['high_risk_threshold', '75', 'number', 'Risk score threshold for high-risk classification', 'risk'],
    ['medium_risk_threshold', '50', 'number', 'Risk score threshold for medium-risk classification', 'risk'],
    ['enable_email_notifications', '1', 'boolean', 'Enable email notifications for system events', 'notifications'],
    ['enable_audit_log', '1', 'boolean', 'Enable detailed audit logging', 'security'],
    ['session_timeout_minutes', '30', 'number', 'Session timeout in minutes', 'security'],
    ['max_login_attempts', '5', 'number', 'Maximum failed login attempts before lockout', 'security'],
    ['password_min_length', '8', 'number', 'Minimum password length requirement', 'security'],
    ['require_password_complexity', '1', 'boolean', 'Require complex passwords (uppercase, lowercase, numbers, symbols)', 'security'],
    ['backup_frequency_days', '7', 'number', 'Automatic backup frequency in days', 'system'],
    ['data_retention_days', '2555', 'number', 'Number of days to retain data (7 years)', 'system']
];

foreach ($defaultSettings as $setting) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO system_settings (setting_key, setting_value, setting_type, description, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute($setting);
}

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_settings') {
    $updatedCount = 0;
    
    foreach ($_POST['settings'] as $key => $value) {
        // Validate setting exists
        $stmt = $pdo->prepare("SELECT setting_type FROM system_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $setting = $stmt->fetch();
        
        if ($setting) {
            // Validate value based on type
            $validValue = true;
            switch ($setting['setting_type']) {
                case 'number':
                    if (!is_numeric($value)) {
                        $validValue = false;
                    }
                    break;
                case 'boolean':
                    $value = $value ? '1' : '0';
                    break;
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $validValue = false;
                    }
                    break;
            }
            
            if ($validValue) {
                $stmt = $pdo->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
                $updatedCount++;
            }
        }
    }
    
    if ($updatedCount > 0) {
        logActivity('update_settings', "Updated $updatedCount system settings");
        $_SESSION['success'] = "Settings updated successfully!";
    } else {
        $_SESSION['error'] = "No settings were updated.";
    }
    
    header('Location: index.php?page=settings');
    exit;
}

// Get all settings
$stmt = $pdo->query("SELECT * FROM system_settings ORDER BY category, setting_key");
$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group settings by category
$groupedSettings = [];
foreach ($settings as $setting) {
    $groupedSettings[$setting['category']][] = $setting;
}

// Function to get setting value
function getSetting($key, $default = null) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT setting_value, setting_type FROM system_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $setting = $stmt->fetch();
    
    if ($setting) {
        switch ($setting['setting_type']) {
            case 'number':
                return (int)$setting['setting_value'];
            case 'boolean':
                return (bool)$setting['setting_value'];
            case 'json':
                return json_decode($setting['setting_value'], true);
            default:
                return $setting['setting_value'];
        }
    }
    
    return $default;
}

// Function to set setting value
function setSetting($key, $value) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = ?");
    return $stmt->execute([$value, $key]);
}
?> 