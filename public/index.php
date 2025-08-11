<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// Include authentication and authorization FIRST
require_once 'auth.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please enter both email and password.';
        header('Location: index.php?page=login');
        exit;
    }
    
    // Check user credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        $_SESSION['error'] = 'Invalid email or password.';
        header('Location: index.php?page=login');
        exit;
    }
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}



// Create tables if they don't exist
$createRiskTable = "CREATE TABLE IF NOT EXISTS risk_assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    client_identification ENUM('Yes', 'No', 'In-progress') NOT NULL,
    screening_risk_id VARCHAR(50),
    screening_description VARCHAR(255),
    screening_impact VARCHAR(50),
    screening_likelihood VARCHAR(50),
    screening_risk_rating VARCHAR(50),
    client_category_risk_id VARCHAR(50),
    client_category_description VARCHAR(255),
    client_category_impact VARCHAR(50),
    client_category_likelihood VARCHAR(50),
    client_category_risk_rating VARCHAR(50),
    services_risk_id VARCHAR(50),
    services_description VARCHAR(255),
    services_impact VARCHAR(50),
    services_likelihood VARCHAR(50),
    services_risk_rating VARCHAR(50),
    payment_risk_id VARCHAR(50),
    payment_description VARCHAR(255),
    payment_impact VARCHAR(50),
    payment_likelihood VARCHAR(50),
    payment_risk_rating VARCHAR(50),
    delivery_risk_id VARCHAR(50),
    delivery_description VARCHAR(255),
    delivery_impact VARCHAR(50),
    delivery_likelihood VARCHAR(50),
    delivery_risk_rating VARCHAR(50),
    overall_risk_points INT DEFAULT 0,
    overall_risk_rating VARCHAR(50),
    client_acceptance VARCHAR(100),
    ongoing_monitoring VARCHAR(100),
    dcs_risk_appetite ENUM('Conservative', 'Moderate', 'Aggressive'),
    dcs_comments TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$createUsersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'user') DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$createNotificationsTable = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    icon VARCHAR(50) DEFAULT 'bell',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

// Drop existing tables if they exist (for clean setup)
$pdo->exec("DROP TABLE IF EXISTS notifications");
$pdo->exec("DROP TABLE IF EXISTS risk_assessments");
$pdo->exec("DROP TABLE IF EXISTS users");

$pdo->exec($createRiskTable);
$pdo->exec($createUsersTable);
$pdo->exec($createNotificationsTable);

$createUserActivityTable = "CREATE TABLE IF NOT EXISTS user_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
)";

$pdo->exec($createUserActivityTable);

// Check if admin user exists, if not create one
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
$stmt->execute();
$adminCount = $stmt->fetchColumn();

if ($adminCount == 0) {
    // Create default admin user only if no admin exists
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['System Administrator', 'admin@dcs.com', password_hash('admin123', PASSWORD_DEFAULT), 'admin', 'active']);
    
    // Add some sample risk assessments for testing
    $sampleAssessments = [
        [
            'ABC Corporation', 'Yes', 'CR-01', 'PIP / PEP client', 'High', 'Medium', 'High',
            'CC-01', 'PIP / PEP client', 'High', 'Medium', 'High',
            'SR-01', 'High-risk services', 'High', 'High', 'High',
            'PR-01', 'High-risk payment methods', 'High', 'High', 'High',
            'DR-01', 'Remote service risks', 'High', 'Medium', 'High',
            'Very High-risk', 'Do not accept client', 'N/A', 'Conservative',
            'High-risk client requiring enhanced due diligence.'
        ],
        [
            'XYZ Limited', 'Yes', 'CR-03', 'Low-risk client profile', 'Medium', 'Medium', 'Medium',
            'CC-03', 'Low-risk client profile', 'Medium', 'Medium', 'Medium',
            'SR-03', 'Standard services', 'Low', 'Medium', 'Low',
            'PR-03', 'POS Payments', 'Low', 'Medium', 'Low',
            'DR-02', 'Medium-risk delivery methods', 'Medium', 'Low', 'Medium',
            'Low-risk', 'Accept client', 'Annually', 'Moderate',
            'Standard risk assessment completed successfully.'
        ],
        [
            'Tech Solutions Inc', 'Yes', 'CR-02', 'Medium-risk business', 'Medium', 'High', 'High',
            'CC-02', 'Medium-risk business', 'Medium', 'High', 'High',
            'SR-02', 'Medium-risk services', 'Medium', 'High', 'High',
            'PR-02', 'Bank transfers', 'Medium', 'Medium', 'Medium',
            'DR-01', 'Remote service risks', 'High', 'Medium', 'High',
            'High-risk', 'Accept with conditions', 'Quarterly', 'Conservative',
            'Medium-risk client requiring regular monitoring.'
        ]
    ];

    foreach ($sampleAssessments as $assessment) {
        $stmt = $pdo->prepare("INSERT INTO risk_assessments (
            client_name, client_identification, screening_risk_id, screening_description, 
            screening_impact, screening_likelihood, screening_risk_rating, client_category_risk_id,
            client_category_description, client_category_impact, client_category_likelihood, 
            client_category_risk_rating, services_risk_id, services_description, services_impact,
            services_likelihood, services_risk_rating, payment_risk_id, payment_description,
            payment_impact, payment_likelihood, payment_risk_rating, delivery_risk_id,
            delivery_description, delivery_impact, delivery_likelihood, delivery_risk_rating,
            overall_risk_rating, client_acceptance, ongoing_monitoring,
            dcs_risk_appetite, dcs_comments
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($assessment);
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_risk':
                requirePermission('create_risk');
                // Validate required fields
                $requiredFields = [
                    'client_name', 'client_identification', 'screening_risk_id', 'screening_description',
                    'screening_impact', 'screening_likelihood', 'screening_risk_rating', 'client_category_risk_id',
                    'client_category_description', 'client_category_impact', 'client_category_likelihood',
                    'client_category_risk_rating', 'services_risk_id', 'services_description', 'services_impact',
                    'services_likelihood', 'services_risk_rating', 'payment_risk_id', 'payment_description',
                    'payment_impact', 'payment_likelihood', 'payment_risk_rating', 'delivery_risk_id',
                    'delivery_description', 'delivery_impact', 'delivery_likelihood', 'delivery_risk_rating',
                    'overall_risk_rating', 'client_acceptance', 'ongoing_monitoring', 'dcs_risk_appetite'
                ];
                
                $errors = [];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                    }
                }
                
                // Validate client name length
                if (strlen($_POST['client_name']) > 255) {
                    $errors[] = 'Client name must be less than 255 characters';
                }
                
                // Validate risk ratings
                $validRiskRatings = ['Low', 'Medium', 'High', 'Very High'];
                $riskFields = ['screening_risk_rating', 'client_category_risk_rating', 'services_risk_rating', 'payment_risk_rating', 'delivery_risk_rating'];
                foreach ($riskFields as $field) {
                    if (!in_array($_POST[$field], $validRiskRatings)) {
                        $errors[] = 'Invalid risk rating for ' . ucfirst(str_replace('_', ' ', $field));
                    }
                }
                
                if (!empty($errors)) {
                    $_SESSION['error'] = 'Validation errors: ' . implode(', ', $errors);
                    header('Location: index.php?page=risks');
                    exit;
                }
                
                // Sanitize inputs
                $clientName = htmlspecialchars(trim($_POST['client_name']));
                $clientIdentification = htmlspecialchars(trim($_POST['client_identification']));
                $dcsComments = htmlspecialchars(trim($_POST['dcs_comments'] ?? ''));
                
                $stmt = $pdo->prepare("INSERT INTO risk_assessments (
                    client_name, client_identification, screening_risk_id, screening_description, 
                    screening_impact, screening_likelihood, screening_risk_rating, client_category_risk_id,
                    client_category_description, client_category_impact, client_category_likelihood, 
                    client_category_risk_rating, services_risk_id, services_description, services_impact,
                    services_likelihood, services_risk_rating, payment_risk_id, payment_description,
                    payment_impact, payment_likelihood, payment_risk_rating, delivery_risk_id,
                    delivery_description, delivery_impact, delivery_likelihood, delivery_risk_rating,
                    overall_risk_rating, client_acceptance, ongoing_monitoring,
                    dcs_risk_appetite, dcs_comments
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $stmt->execute([
                    $clientName,
                    $clientIdentification,
                    $_POST['screening_risk_id'],
                    $_POST['screening_description'],
                    $_POST['screening_impact'],
                    $_POST['screening_likelihood'],
                    $_POST['screening_risk_rating'],
                    $_POST['client_category_risk_id'],
                    $_POST['client_category_description'],
                    $_POST['client_category_impact'],
                    $_POST['client_category_likelihood'],
                    $_POST['client_category_risk_rating'],
                    $_POST['services_risk_id'],
                    $_POST['services_description'],
                    $_POST['services_impact'],
                    $_POST['services_likelihood'],
                    $_POST['services_risk_rating'],
                    $_POST['payment_risk_id'],
                    $_POST['payment_description'],
                    $_POST['payment_impact'],
                    $_POST['payment_likelihood'],
                    $_POST['payment_risk_rating'],
                    $_POST['delivery_risk_id'],
                    $_POST['delivery_description'],
                    $_POST['delivery_impact'],
                    $_POST['delivery_likelihood'],
                    $_POST['delivery_risk_rating'],
                    $_POST['overall_risk_rating'],
                    $_POST['client_acceptance'],
                    $_POST['ongoing_monitoring'],
                    $_POST['dcs_risk_appetite'],
                    $dcsComments
                ]);
                
                // Log activity
                logActivity('create_risk_assessment', "Created risk assessment for client: $clientName");
                
                // Add notification
                $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, icon) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $_SESSION['user_id'] ?? 1,
                    'New Risk Assessment Created',
                    'Risk assessment for ' . $_POST['client_name'] . ' has been created successfully.',
                    'shield-alt'
                ]);
                
                header('Location: index.php?page=risks&success=assessment_created');
                exit;
                break;

            case 'add_user':
                // Debug: Log the POST data
                error_log("ADD_USER: POST data received: " . print_r($_POST, true));
                
                requirePermission('create_user');
                // Validate required fields
                if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['role'])) {
                    error_log("ADD_USER: Validation failed - missing fields. Name: " . (empty($_POST['name']) ? 'EMPTY' : $_POST['name']) . ", Email: " . (empty($_POST['email']) ? 'EMPTY' : $_POST['email']) . ", Role: " . (empty($_POST['role']) ? 'EMPTY' : $_POST['role']));
                    $_SESSION['error'] = 'All fields are required';
                    header('Location: index.php?page=users');
                    exit;
                }
                
                // Validate email format
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    error_log("ADD_USER: Email validation failed for: " . $_POST['email']);
                    $_SESSION['error'] = 'Invalid email format';
                    header('Location: index.php?page=users');
                    exit;
                }
                
                // Validate password strength
                if (strlen($_POST['password']) < 6) {
                    $_SESSION['error'] = 'Password must be at least 6 characters long';
                    header('Location: index.php?page=users');
                    exit;
                }
                
                // Validate role
                $validRoles = ['admin', 'manager', 'user'];
                if (!in_array($_POST['role'], $validRoles)) {
                    $_SESSION['error'] = 'Invalid role selected';
                    header('Location: index.php?page=users');
                    exit;
                }
                
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->execute([$_POST['email']]);
                if ($stmt->fetchColumn() > 0) {
                    error_log("ADD_USER: Email already exists: " . $_POST['email']);
                    $_SESSION['error'] = 'Email address already exists';
                    header('Location: index.php?page=users');
                    exit;
                }
                
                // Sanitize inputs
                $name = htmlspecialchars(trim($_POST['name']));
                $email = htmlspecialchars(trim($_POST['email']));
                
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                // Simple direct insert without transaction
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    $name,
                    $email,
                    $hashedPassword,
                    $_POST['role'],
                    'active'
                ]);
                
                if (!$result) {
                    $errorInfo = $stmt->errorInfo();
                    error_log("ADD_USER: Database insert failed: " . implode(', ', $errorInfo));
                    $_SESSION['error'] = 'Failed to create user: ' . implode(', ', $errorInfo);
                    header('Location: index.php?page=users');
                    exit;
                }
                
                $userId = $pdo->lastInsertId();
                error_log("ADD_USER: User created successfully! ID: $userId, Name: $name, Email: $email");
                
                // Log activity after successful insert
                logActivity('create_user', "Created new user: $name ($email) with role: {$_POST['role']}");
                
                $_SESSION['success'] = "User '$name' created successfully with ID: $userId";
                
                header('Location: index.php?page=users');
                exit;
                break;

            case 'update_user':
                requirePermission('edit_user');
                $updateFields = "name = ?, email = ?, role = ?, status = ?";
                $params = [$_POST['name'], $_POST['email'], $_POST['role'], $_POST['status']];
                
                if (!empty($_POST['password'])) {
                    $updateFields .= ", password = ?";
                    $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }
                
                $params[] = $_POST['user_id'];
                $stmt = $pdo->prepare("UPDATE users SET $updateFields WHERE id = ?");
                $stmt->execute($params);
                
                header('Location: index.php?page=users&success=user_updated');
                exit;
                break;

            case 'delete_user':
                requirePermission('delete_user');
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
                $stmt->execute([$_POST['user_id']]);
                
                header('Location: index.php?page=users&success=user_deleted');
                exit;
                break;

            case 'delete_assessment':
                $stmt = $pdo->prepare("DELETE FROM risk_assessments WHERE id = ?");
                $stmt->execute([$_POST['assessment_id']]);
                
                header('Location: index.php?page=risks&success=assessment_deleted');
                exit;
                break;

            case 'export_report':
                // Export functionality
                $period = $_POST['period'] ?? '30';
                $assessments = $pdo->query("SELECT * FROM risk_assessments WHERE created_at >= DATE_SUB(NOW(), INTERVAL $period DAY)")->fetchAll();
                
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="risk_assessment_report.csv"');
                
                $output = fopen('php://output', 'w');
                fputcsv($output, ['Client Name', 'Risk Rating', 'Risk Points', 'Client Acceptance', 'Assessment Date']);
                
                foreach ($assessments as $assessment) {
                    fputcsv($output, [
                        $assessment['client_name'],
                        $assessment['overall_risk_rating'],
                        $assessment['overall_risk_points'],
                        $assessment['client_acceptance'],
                        $assessment['created_at']
                    ]);
                }
                fclose($output);
                exit;
                break;
        }
    }
}

// Get statistics for dashboard
$totalAssessments = $pdo->query("SELECT COUNT(*) FROM risk_assessments")->fetchColumn();
$highRiskCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE overall_risk_rating IN ('Very High-risk', 'High-risk')")->fetchColumn();
$lowRiskCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE overall_risk_rating = 'Low-risk'")->fetchColumn();
$pendingCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE client_identification = 'In-progress'")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Calculate real percentage changes based on database data
// Get data from last 30 days vs previous 30 days
$currentPeriodAssessments = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
$previousPeriodAssessments = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

$currentPeriodHighRisk = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE overall_risk_rating IN ('Very High-risk', 'High-risk') AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
$previousPeriodHighRisk = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE overall_risk_rating IN ('Very High-risk', 'High-risk') AND created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

$currentPeriodLowRisk = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE overall_risk_rating = 'Low-risk' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
$previousPeriodLowRisk = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE overall_risk_rating = 'Low-risk' AND created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

$currentPeriodUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
$previousPeriodUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

// Calculate percentage changes
function calculatePercentageChange($current, $previous) {
    if ($previous == 0) {
        return $current > 0 ? '+100%' : '0%';
    }
    $change = (($current - $previous) / $previous) * 100;
    return ($change >= 0 ? '+' : '') . round($change, 1) . '%';
}

$totalAssessmentsChange = calculatePercentageChange($currentPeriodAssessments, $previousPeriodAssessments);
$highRiskChange = calculatePercentageChange($currentPeriodHighRisk, $previousPeriodHighRisk);
$lowRiskChange = calculatePercentageChange($currentPeriodLowRisk, $previousPeriodLowRisk);
$activeUsersChange = calculatePercentageChange($currentPeriodUsers, $previousPeriodUsers);

$recentAssessments = $pdo->query("SELECT * FROM risk_assessments ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentUsers = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 3")->fetchAll();

// Fetch risks for dynamic dropdowns
$screeningRisks = $pdo->query("SELECT * FROM risks WHERE risk_category = 'Client Risk' ORDER BY risk_identification")->fetchAll();
$categoryRisks = $pdo->query("SELECT * FROM risks WHERE risk_category = 'Client Risk' ORDER BY risk_identification")->fetchAll();
$serviceRisks = $pdo->query("SELECT * FROM risks WHERE risk_category = 'Service Risk' ORDER BY risk_identification")->fetchAll();
$paymentRisks = $pdo->query("SELECT * FROM risks WHERE risk_category = 'Payment Risk' ORDER BY risk_identification")->fetchAll();

$page = $_GET['page'] ?? '';

// Check if user is logged in (except for login page)
if ($page !== 'login' && !isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

// Apply role-based access control
if ($page !== 'login' && isLoggedIn()) {
    switch ($page) {
        case 'users':
            requirePermission('users');
            break;
        case 'settings':
            requirePermission('settings');
            break;
        case 'reports':
            requirePermission('reports');
            break;
    }
}

// If user is logged in and trying to access login page, redirect to dashboard
if ($page === 'login' && isLoggedIn()) {
    header('Location: index.php?page=dashboard');
    exit;
}

// If no page specified and user is logged in, default to dashboard
if (empty($_GET['page']) && isLoggedIn()) {
    $page = 'dashboard';
}

// If no page specified and user is not logged in, show login
if (empty($_GET['page']) && !isLoggedIn()) {
    $page = 'login';
}

$currentUser = getCurrentUser();

// Notification functions
function getNotificationCount() {
    global $pdo;
    if (!isLoggedIn()) return 0;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetchColumn();
}

function getNotifications() {
    global $pdo;
    if (!isLoggedIn()) return '';
    
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $notifications = $stmt->fetchAll();
    
    if (empty($notifications)) {
        return '<div class="notification-item empty">No new notifications</div>';
    }
    
    $html = '';
    foreach ($notifications as $notification) {
        $html .= '<div class="notification-item ' . ($notification['is_read'] ? 'read' : 'unread') . '">';
        $html .= '<div class="notification-icon"><i class="fas fa-' . $notification['icon'] . '"></i></div>';
        $html .= '<div class="notification-content">';
        $html .= '<div class="notification-title">' . htmlspecialchars($notification['title']) . '</div>';
        $html .= '<div class="notification-message">' . htmlspecialchars($notification['message']) . '</div>';
        $html .= '<div class="notification-time">' . timeAgo($notification['created_at']) . '</div>';
        $html .= '</div>';
        $html .= '</div>';
    }
    
    return $html;
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    if ($time < 60) return 'Just now';
    if ($time < 3600) return floor($time / 60) . 'm ago';
    if ($time < 86400) return floor($time / 3600) . 'h ago';
    return floor($time / 86400) . 'd ago';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCS Portal - Risk Assessment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php if (isLoggedIn()): ?>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-wrapper">
                        <img src="images/dcs-logo.png" alt="DCS Logo" class="dcs-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="logo-text-fallback" style="display: none;">
                            <div class="dcs-text-logo">
                                <span class="dcs-letter">D</span>
                                <span class="dcs-letter">C</span>
                                <span class="dcs-letter">S</span>
                            </div>
                            <span class="brand-subtitle">Risk Assessment</span>
                        </div>
                        <div class="logo-glow"></div>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item <?= $page === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="index.php?page=risks" class="nav-item <?= $page === 'risks' ? 'active' : '' ?>">
                    <i class="fas fa-shield-alt"></i>
                    <span>Manage Risks</span>
                </a>
                <a href="index.php?page=users" class="nav-item <?= $page === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
                <a href="index.php?page=reports" class="nav-item <?= $page === 'reports' ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports & Analytics</span>
                </a>
                <a href="index.php?page=risk-register" class="nav-item <?= $page === 'risk-register' ? 'active' : '' ?>">
                    <i class="fas fa-table"></i>
                    <span>Risk Register</span>
                </a>
                <?php if (hasPermission('settings')): ?>
                <a href="index.php?page=settings" class="nav-item <?= $page === 'settings' ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <?php endif; ?>
                <div class="nav-divider"></div>
                <a href="auth.php?action=logout" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <?php if ($page === 'dashboard'): ?>
            <div class="content-header">
                <div class="header-left">
                    <h1>Welcome back, <?= htmlspecialchars($currentUser['name']) ?></h1>
                    <p class="header-subtitle">Here's what's happening with your risk assessments today</p>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Search assessments..." onkeyup="performSearch()">
                            <div id="searchResults" class="search-results" style="display: none;"></div>
                        </div>
                        <div class="notification-bell" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notificationCount"><?= getNotificationCount() ?></span>
                            <div class="notification-dropdown" id="notificationDropdown">
                                <div class="notification-header">
                                    <h6>Notifications</h6>
                                    <button onclick="markAllRead()" class="btn-link">Mark all read</button>
                                </div>
                                <div class="notification-list" id="notificationList">
                                    <?= getNotifications() ?>
                                </div>
                            </div>
                        </div>
                        <div class="user-profile" onclick="toggleUserMenu()">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($currentUser['name']) ?>&background=1A3B5D&color=fff" alt="<?= htmlspecialchars($currentUser['name']) ?>" class="avatar">
                            <span><?= htmlspecialchars($currentUser['name']) ?></span>
                            <i class="fas fa-chevron-down"></i>
                            <div class="user-dropdown" id="userDropdown">
                                <div class="user-dropdown-header">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($currentUser['name']) ?>&background=1A3B5D&color=fff" alt="<?= htmlspecialchars($currentUser['name']) ?>" class="avatar-large">
                                    <div class="user-info">
                                        <div class="user-name"><?= htmlspecialchars($currentUser['name']) ?></div>
                                        <div class="user-email"><?= htmlspecialchars($currentUser['email']) ?></div>
                                        <div class="user-role"><?= ucfirst($currentUser['role']) ?></div>
                                    </div>
                                </div>
                                <div class="user-dropdown-menu">
                                    <a href="index.php?page=profile" class="dropdown-item">
                                        <i class="fas fa-user"></i> My Profile
                                    </a>
                                    <a href="index.php?page=settings" class="dropdown-item">
                                        <i class="fas fa-cog"></i> Settings
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="auth.php?action=logout" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="content-body">
                <?php if ($page !== 'dashboard' && $page !== 'login'): ?>
                <div class="page-header-simple">
                    <h2><?= ucfirst($page) ?></h2>
                </div>
                <?php endif; ?>
    <?php else: ?>
    <div class="content-body">
    <?php endif; ?>
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>Operation completed successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($page === 'login'): ?>
                    <!-- Login Page -->
                    <div class="login-container">
                        <div class="login-card">
                            <div class="login-header">
                                <img src="images/dcs-logo.png" alt="DCS Logo" class="login-logo">
                                <h2>Welcome Back</h2>
                                <p>Sign in to your DCS Risk Assessment System</p>
                            </div>
                            
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?= htmlspecialchars($_SESSION['error']) ?>
                                </div>
                                <?php unset($_SESSION['error']); ?>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <?= htmlspecialchars($_SESSION['success']) ?>
                                </div>
                                <?php unset($_SESSION['success']); ?>
                            <?php endif; ?>
                            
                            <form method="POST" action="auth.php" class="login-form">
                                <input type="hidden" name="action" value="login">
                                
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope me-2"></i>
                                        Email Address
                                    </label>
                                    <div class="input-group">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email" class="form-control" name="email" placeholder="Enter your email address" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-lock me-2"></i>
                                        Password
                                    </label>
                                    <div class="input-group">
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Sign In
                                </button>
                            </form>
                            

                        </div>
                    </div>
                <?php elseif ($page === 'dashboard'): ?>
                    <!-- Dashboard Overview -->
                    <div class="stats-grid" style="margin-bottom: 2rem;">
                        <div class="stat-card gradient-primary">
                            <div class="stat-icon">
                                <i class="fas fa-clipboard-list"></i>
                                <div class="icon-bg"></div>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    <span class="counter" data-target="<?= $totalAssessments ?>"><?= $totalAssessments ?></span>
                                    <span class="stat-trend <?= strpos($totalAssessmentsChange, '+') !== false ? 'positive' : 'negative' ?>" data-change="totalAssessments">
                                        <i class="fas fa-arrow-<?= strpos($totalAssessmentsChange, '+') !== false ? 'up' : 'down' ?>"></i> <?= $totalAssessmentsChange ?>
                                    </span>
                                </div>
                                <p>Total Assessments</p>
                                <div class="stat-progress">
                                    <div class="progress-bar" style="width: <?= $totalAssessments > 0 ? min(100, ($totalAssessments / max(1, $totalAssessments)) * 100) : 0 ?>%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card gradient-warning">
                            <div class="stat-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div class="icon-bg"></div>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    <span class="counter" data-target="<?= $highRiskCount ?>"><?= $highRiskCount ?></span>
                                    <span class="stat-trend <?= strpos($highRiskChange, '+') !== false ? 'positive' : 'negative' ?>" data-change="highRisk">
                                        <i class="fas fa-arrow-<?= strpos($highRiskChange, '+') !== false ? 'up' : 'down' ?>"></i> <?= $highRiskChange ?>
                                    </span>
                                </div>
                                <p>High Risk Clients</p>
                                <div class="stat-progress">
                                    <div class="progress-bar" style="width: <?= $totalAssessments > 0 ? min(100, ($highRiskCount / $totalAssessments) * 100) : 0 ?>%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card gradient-success">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                                <div class="icon-bg"></div>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    <span class="counter" data-target="<?= $lowRiskCount ?>"><?= $lowRiskCount ?></span>
                                    <span class="stat-trend <?= strpos($lowRiskChange, '+') !== false ? 'positive' : 'negative' ?>" data-change="lowRisk">
                                        <i class="fas fa-arrow-<?= strpos($lowRiskChange, '+') !== false ? 'up' : 'down' ?>"></i> <?= $lowRiskChange ?>
                                    </span>
                                </div>
                                <p>Low Risk Clients</p>
                                <div class="stat-progress">
                                    <div class="progress-bar" style="width: <?= $totalAssessments > 0 ? min(100, ($lowRiskCount / $totalAssessments) * 100) : 0 ?>%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card gradient-info">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                                <div class="icon-bg"></div>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    <span class="counter" data-target="<?= $totalUsers ?>"><?= $totalUsers ?></span>
                                    <span class="stat-trend <?= strpos($activeUsersChange, '+') !== false ? 'positive' : 'negative' ?>" data-change="users">
                                        <i class="fas fa-arrow-<?= strpos($activeUsersChange, '+') !== false ? 'up' : 'down' ?>"></i> <?= $activeUsersChange ?>
                                    </span>
                                </div>
                                <p>Active Users</p>
                                <div class="stat-progress">
                                    <div class="progress-bar" style="width: <?= $totalUsers > 0 ? min(100, ($totalUsers / max(1, $totalUsers)) * 100) : 0 ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Quick Actions Section -->
                    <div class="dashboard-grid">
                        <!-- Quick Actions -->
                        <div class="dashboard-card">
                            <div class="card-header-modern">
                                <h4>Quick Actions</h4>
                            </div>
                            <div class="quick-actions">
                                <a href="index.php?page=create" class="quick-action-item">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <span class="quick-action-title">New Assessment</span>
                                        <span class="quick-action-subtitle">Create risk assessment</span>
                                    </div>
                                </a>
                                <a href="index.php?page=users" class="quick-action-item">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <span class="quick-action-title">Add User</span>
                                        <span class="quick-action-subtitle">Create new user account</span>
                                    </div>
                                </a>
                                <a href="index.php?page=reports" class="quick-action-item">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <span class="quick-action-title">Generate Report</span>
                                        <span class="quick-action-subtitle">Export risk data</span>
                                    </div>
                                </a>
                                <a href="index.php?page=settings" class="quick-action-item">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <span class="quick-action-title">Settings</span>
                                        <span class="quick-action-subtitle">System configuration</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Risk Assessment Tables -->
                        <div class="dashboard-card" style="margin-top: 2rem;">
                            <div class="card-header-modern">
                                <h4>Risk Assessment Framework</h4>
                            </div>
                            
                            <!-- Individual Risk Assessment Details -->
                            <div class="table-responsive mb-4">
                                <h6 class="mb-3">Individual Risk Assessment Details</h6>
                                <table class="table table-bordered risk-assessment-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Risk ID</th>
                                            <th>Impact (H/M/L)</th>
                                            <th>Likelihood (H/M/L)</th>
                                            <th>Risk Rating (H/M/L)</th>
                                            <th>Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-light">
                                            <td>CR-01</td>
                                            <td>High</td>
                                            <td>Medium</td>
                                            <td>High</td>
                                            <td>5</td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td>CR-02</td>
                                            <td>Medium</td>
                                            <td>High</td>
                                            <td>High</td>
                                            <td>5</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td>CR-03</td>
                                            <td>Low</td>
                                            <td>High</td>
                                            <td>Medium</td>
                                            <td>3</td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td>SR-01</td>
                                            <td>High</td>
                                            <td>High</td>
                                            <td>High</td>
                                            <td>5</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td>SR-02</td>
                                            <td>Medium</td>
                                            <td>Medium</td>
                                            <td>Medium</td>
                                            <td>3</td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td>SR-03</td>
                                            <td>Low</td>
                                            <td>Medium</td>
                                            <td>Low</td>
                                            <td>1</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td>SR-04</td>
                                            <td>High</td>
                                            <td>Low</td>
                                            <td>Medium</td>
                                            <td>3</td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td>PR-01</td>
                                            <td>Medium</td>
                                            <td>High</td>
                                            <td>High</td>
                                            <td>5</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td>PR-02</td>
                                            <td>Low</td>
                                            <td>Low</td>
                                            <td>Low</td>
                                            <td>1</td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td>PR-03</td>
                                            <td>High</td>
                                            <td>Medium</td>
                                            <td>High</td>
                                            <td>5</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td>DR-01</td>
                                            <td>Medium</td>
                                            <td>Medium</td>
                                            <td>Medium</td>
                                            <td>3</td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td>DR-02</td>
                                            <td>Medium</td>
                                            <td>Low</td>
                                            <td>Medium</td>
                                            <td>2</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Risk Rating System -->
                            <div class="table-responsive">
                                <h6 class="mb-3">Risk Rating System</h6>
                                <table class="table table-bordered risk-rating-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Points Total</th>
                                            <th>Overall Risk Rating</th>
                                            <th>Client Acceptance?</th>
                                            <th>Ongoing Monitoring</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="bg-danger text-white fw-bold">20</td>
                                            <td>Very High-risk</td>
                                            <td>Do not accept client</td>
                                            <td>N/A</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-warning text-dark fw-bold">17</td>
                                            <td>High-risk</td>
                                            <td>Accept client</td>
                                            <td>Quarterly review</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-warning text-dark fw-bold">15</td>
                                            <td>Medium-risk</td>
                                            <td>Accept client</td>
                                            <td>Bi-Annually</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-success text-white fw-bold">10</td>
                                            <td>Low-risk</td>
                                            <td>Accept client</td>
                                            <td>Annually</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                <?php elseif ($page === 'risks'): ?>
                    <!-- Risk Management -->
                    <?php if (isset($_GET['success']) && $_GET['success'] === 'assessment_created'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>Risk assessment created successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="page-header">
                        <h2>Manage Risks</h2>
                        <a href="index.php?page=create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>New Assessment
                        </a>
                    </div>
                    
                    <div class="content-card">
                        <?php
                        $assessments = $pdo->query("SELECT * FROM risk_assessments ORDER BY created_at DESC")->fetchAll();
                        if (count($assessments) > 0):
                        ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Client Name</th>
                                            <th>Risk Rating</th>
                                            <th>Risk Points</th>
                                            <th>Client Acceptance</th>
                                            <th>Assessment Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($assessments as $assessment): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($assessment['client_name']) ?></strong></td>
                                            <td>
                                                <?php
                                                $badgeClass = 'bg-secondary';
                                                if ($assessment['overall_risk_rating'] === 'Very High-risk') $badgeClass = 'bg-danger';
                                                elseif ($assessment['overall_risk_rating'] === 'High-risk') $badgeClass = 'bg-warning';
                                                elseif ($assessment['overall_risk_rating'] === 'Medium-risk') $badgeClass = 'bg-info';
                                                elseif ($assessment['overall_risk_rating'] === 'Low-risk') $badgeClass = 'bg-success';
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= $assessment['overall_risk_rating'] ?? 'N/A' ?></span>
                                            </td>
                                            <td><strong><?= $assessment['overall_risk_points'] ?></strong></td>
                                            <td>
                                                <span class="badge bg-<?= $assessment['client_acceptance'] === 'Do not accept client' ? 'danger' : 'success' ?>">
                                                    <?= $assessment['client_acceptance'] ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($assessment['created_at'])) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewAssessment(<?= $assessment['id'] ?>)">View</button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editAssessment(<?= $assessment['id'] ?>)">Edit</button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteAssessment(<?= $assessment['id'] ?>)">Delete</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <h4>No risk assessments found</h4>
                                <p>Start by creating your first risk assessment.</p>
                                <a href="index.php?page=create" class="btn btn-primary">Create Assessment</a>
                            </div>
                        <?php endif; ?>
                    </div>

                <?php elseif ($page === 'users'): ?>
                    <!-- User Management -->
                    <div class="page-header">
                        <h2>User Management</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-plus me-1"></i>Add User
                        </button>
                    </div>
                    
                    <div class="content-card">
                        <?php
                        // Display success/error messages
                        if (isset($_SESSION['success'])) {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>' . htmlspecialchars($_SESSION['success']) . '
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                  </div>';
                            unset($_SESSION['success']);
                        }
                        if (isset($_GET['success']) && $_GET['success'] === 'user_updated') {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>User updated successfully!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                  </div>';
                        }
                        if (isset($_GET['success']) && $_GET['success'] === 'user_deleted') {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>User deleted successfully!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                  </div>';
                        }
                        if (isset($_SESSION['error'])) {
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>' . htmlspecialchars($_SESSION['error']) . '
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                  </div>';
                            unset($_SESSION['error']);
                        }
                        
                        $users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
                        if (count($users) > 0):
                        ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($user['name']) ?></strong></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'manager' ? 'warning' : 'info') ?>">
                                                    <?= ucfirst($user['role']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($user['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="editUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name']) ?>', '<?= htmlspecialchars($user['email']) ?>', '<?= $user['role'] ?>', '<?= $user['status'] ?>')">Edit</button>
                                                <?php if ($user['role'] !== 'admin'): ?>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(<?= $user['id'] ?>)">Delete</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h4>No users found</h4>
                                <p>Start by adding your first user.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
                            </div>
                        <?php endif; ?>
                    </div>

                <?php elseif ($page === 'create'): ?>
                    <!-- Create Risk Assessment Form -->
                    <div class="page-header">
                        <h2>Create Risk Assessment</h2>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                    
                    <div class="content-card">
                        <!-- Mandatory Fields Notice -->
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-1"></i>Important Information
                            </h6>
                            <ul class="mb-0">
                                <li><strong>All fields marked with <span class="text-danger">*</span> are mandatory</strong></li>
                                <li>Risk ID dropdowns will auto-populate Description, Impact, Likelihood, and Risk Rating fields</li>
                                <li>Overall assessment will be calculated automatically based on your selections</li>
                                <li>Please ensure all details are provided for a complete risk assessment</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="action" value="add_risk">
                            
                            <!-- Basic Client Information -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-user me-2"></i>Basic Client Information
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client_name" class="form-label">Client Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="client_name" name="client_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client_identification" class="form-label">Client Identification Done? <span class="text-danger">*</span></label>
                                            <select class="form-select" id="client_identification" name="client_identification" required>
                                                <option value="">▼ Select Status</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                                <option value="In-progress">In-progress</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Client Screening Section -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-search me-2"></i>Client Screening
                                </h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="screening_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                            <select class="form-select risk-selector" id="screening_risk_id" name="screening_risk_id" required>
                                                <option value="">▼ Select Risk ID</option>
                                                <?php foreach ($screeningRisks as $risk): ?>
                                                <option value="<?= $risk['risk_identification'] ?>" 
                                                        data-description="<?= htmlspecialchars($risk['risk_description']) ?>" 
                                                        data-impact="<?= $risk['risk_impact_level'] ?>" 
                                                        data-likelihood="<?= $risk['risk_likelihood'] ?>" 
                                                        data-rating="<?= $risk['total_risk_rating'] >= 4 ? 'High' : ($risk['total_risk_rating'] >= 2 ? 'Medium' : 'Low') ?>" 
                                                        data-points="<?= $risk['total_risk_rating'] ?>">
                                                    <?= $risk['risk_identification'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="screening_description" class="form-label">Description</label>
                                            <input type="text" class="form-control" id="screening_description" name="screening_description" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="screening_impact" class="form-label">Impact</label>
                                            <input type="text" class="form-control" id="screening_impact" name="screening_impact" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="screening_likelihood" class="form-label">Likelihood</label>
                                            <input type="text" class="form-control" id="screening_likelihood" name="screening_likelihood" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="screening_risk_rating" class="form-label">Risk Rating</label>
                                            <input type="text" class="form-control" id="screening_risk_rating" name="screening_risk_rating" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Category of Client Section -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-user-tag me-2"></i>Category of Client
                                </h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="client_category_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                            <select class="form-select risk-selector" id="client_category_risk_id" name="client_category_risk_id" required>
                                                <option value="">▼ Select Risk ID</option>
                                                <?php foreach ($categoryRisks as $risk): ?>
                                                <option value="<?= $risk['risk_identification'] ?>" 
                                                        data-description="<?= htmlspecialchars($risk['risk_description']) ?>" 
                                                        data-impact="<?= $risk['risk_impact_level'] ?>" 
                                                        data-likelihood="<?= $risk['risk_likelihood'] ?>" 
                                                        data-rating="<?= $risk['total_risk_rating'] >= 4 ? 'High' : ($risk['total_risk_rating'] >= 2 ? 'Medium' : 'Low') ?>" 
                                                        data-points="<?= $risk['total_risk_rating'] ?>">
                                                    <?= $risk['risk_identification'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="client_category_description" class="form-label">Description</label>
                                            <input type="text" class="form-control" id="client_category_description" name="client_category_description" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="client_category_impact" class="form-label">Impact</label>
                                            <input type="text" class="form-control" id="client_category_impact" name="client_category_impact" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="client_category_likelihood" class="form-label">Likelihood</label>
                                            <input type="text" class="form-control" id="client_category_likelihood" name="client_category_likelihood" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="client_category_risk_rating" class="form-label">Risk Rating</label>
                                            <input type="text" class="form-control" id="client_category_risk_rating" name="client_category_risk_rating" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Requested Services Section -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-cogs me-2"></i>Requested Services
                                </h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="services_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                            <select class="form-select risk-selector" id="services_risk_id" name="services_risk_id" required>
                                                <option value="">▼ Select Risk ID</option>
                                                <?php foreach ($serviceRisks as $risk): ?>
                                                <option value="<?= $risk['risk_identification'] ?>" 
                                                        data-description="<?= htmlspecialchars($risk['risk_description']) ?>" 
                                                        data-impact="<?= $risk['risk_impact_level'] ?>" 
                                                        data-likelihood="<?= $risk['risk_likelihood'] ?>" 
                                                        data-rating="<?= $risk['total_risk_rating'] >= 4 ? 'High' : ($risk['total_risk_rating'] >= 2 ? 'Medium' : 'Low') ?>" 
                                                        data-points="<?= $risk['total_risk_rating'] ?>">
                                                    <?= $risk['risk_identification'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="services_description" class="form-label">Description</label>
                                            <input type="text" class="form-control" id="services_description" name="services_description" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="services_impact" class="form-label">Impact</label>
                                            <input type="text" class="form-control" id="services_impact" name="services_impact" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="services_likelihood" class="form-label">Likelihood</label>
                                            <input type="text" class="form-control" id="services_likelihood" name="services_likelihood" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="services_risk_rating" class="form-label">Risk Rating</label>
                                            <input type="text" class="form-control" id="services_risk_rating" name="services_risk_rating" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Anticipated Payment Option Section -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-credit-card me-2"></i>Anticipated Payment Option
                                </h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="payment_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                            <select class="form-select risk-selector" id="payment_risk_id" name="payment_risk_id" required>
                                                <option value="">▼ Select Risk ID</option>
                                                <?php foreach ($paymentRisks as $risk): ?>
                                                <option value="<?= $risk['risk_identification'] ?>" 
                                                        data-description="<?= htmlspecialchars($risk['risk_description']) ?>" 
                                                        data-impact="<?= $risk['risk_impact_level'] ?>" 
                                                        data-likelihood="<?= $risk['risk_likelihood'] ?>" 
                                                        data-rating="<?= $risk['total_risk_rating'] >= 4 ? 'High' : ($risk['total_risk_rating'] >= 2 ? 'Medium' : 'Low') ?>" 
                                                        data-points="<?= $risk['total_risk_rating'] ?>">
                                                    <?= $risk['risk_identification'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="payment_description" class="form-label">Description</label>
                                            <input type="text" class="form-control" id="payment_description" name="payment_description" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="payment_impact" class="form-label">Impact</label>
                                            <input type="text" class="form-control" id="payment_impact" name="payment_impact" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="payment_likelihood" class="form-label">Likelihood</label>
                                            <input type="text" class="form-control" id="payment_likelihood" name="payment_likelihood" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="payment_risk_rating" class="form-label">Risk Rating</label>
                                            <input type="text" class="form-control" id="payment_risk_rating" name="payment_risk_rating" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Anticipated Service Delivery Method Section -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-truck me-2"></i>Anticipated Service Delivery Method
                                </h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="delivery_risk_id" class="form-label">Risk ID <span class="text-danger">*</span></label>
                                            <select class="form-select risk-selector" id="delivery_risk_id" name="delivery_risk_id" required>
                                                <option value="">▼ Select Risk ID</option>
                                                <option value="DR-01" data-description="Remote service risks" data-impact="High" data-likelihood="Medium" data-rating="High" data-points="4">DR-01</option>
                                                <option value="DR-02" data-description="Medium-risk delivery methods" data-impact="Medium" data-likelihood="Low" data-rating="Medium" data-points="2">DR-02</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="delivery_description" class="form-label">Description</label>
                                            <input type="text" class="form-control" id="delivery_description" name="delivery_description" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="delivery_impact" class="form-label">Impact</label>
                                            <input type="text" class="form-control" id="delivery_impact" name="delivery_impact" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="delivery_likelihood" class="form-label">Likelihood</label>
                                            <input type="text" class="form-control" id="delivery_likelihood" name="delivery_likelihood" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="delivery_risk_rating" class="form-label">Risk Rating</label>
                                            <input type="text" class="form-control" id="delivery_risk_rating" name="delivery_risk_rating" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Overall Assessment Section -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-chart-bar me-2"></i>Overall Assessment
                                </h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="overall_risk_points" class="form-label">Overall Risk Points</label>
                                            <input type="text" class="form-control" id="overall_risk_points" name="overall_risk_points" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="overall_risk_rating" class="form-label">Overall Risk Rating</label>
                                            <input type="text" class="form-control" id="overall_risk_rating" name="overall_risk_rating" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="client_acceptance" class="form-label">Client Acceptance</label>
                                            <input type="text" class="form-control" id="client_acceptance" name="client_acceptance" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ongoing_monitoring" class="form-label">Ongoing Monitoring</label>
                                            <input type="text" class="form-control" id="ongoing_monitoring" name="ongoing_monitoring" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DCS Section -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-building me-2"></i>DCS Assessment
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dcs_risk_appetite" class="form-label">DCS Risk Appetite <span class="text-danger">*</span></label>
                                            <select class="form-select" id="dcs_risk_appetite" name="dcs_risk_appetite" required>
                                                <option value="">▼ Select Risk Appetite</option>
                                                <option value="Conservative">Conservative</option>
                                                <option value="Moderate">Moderate</option>
                                                <option value="Aggressive">Aggressive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dcs_comments" class="form-label">DCS Comments</label>
                                            <textarea class="form-control" id="dcs_comments" name="dcs_comments" rows="3" placeholder="Enter DCS comments"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Risk Assessment
                                </button>
                            </div>
                        </form>
                    </div>
                <?php elseif ($page === 'settings'): ?>
                    <!-- Settings Management -->
                    <?php include 'settings.php'; ?>
                    
                    <div class="page-header">
                        <h2>System Settings</h2>
                        <p>Configure system parameters and preferences</p>
                    </div>
                    
                    <form method="POST" action="index.php?page=settings">
                        <input type="hidden" name="action" value="update_settings">
                        
                        <?php foreach ($groupedSettings as $category => $categorySettings): ?>
                        <div class="settings-section">
                            <h3 class="section-title">
                                <i class="fas fa-cog me-2"></i><?= ucfirst($category) ?> Settings
                            </h3>
                            <div class="settings-grid">
                                <?php foreach ($categorySettings as $setting): ?>
                                <div class="setting-item">
                                    <label for="<?= $setting['setting_key'] ?>" class="form-label">
                                        <?= ucfirst(str_replace('_', ' ', $setting['setting_key'])) ?>
                                    </label>
                                    <div class="setting-description">
                                        <?= htmlspecialchars($setting['description']) ?>
                                    </div>
                                    
                                    <?php if ($setting['setting_type'] === 'boolean'): ?>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="<?= $setting['setting_key'] ?>" 
                                                   name="settings[<?= $setting['setting_key'] ?>]" 
                                                   value="1" 
                                                   <?= $setting['setting_value'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="<?= $setting['setting_key'] ?>">
                                                Enable
                                            </label>
                                        </div>
                                    <?php elseif ($setting['setting_type'] === 'number'): ?>
                                        <input type="number" class="form-control" 
                                               id="<?= $setting['setting_key'] ?>" 
                                               name="settings[<?= $setting['setting_key'] ?>]" 
                                               value="<?= htmlspecialchars($setting['setting_value']) ?>">
                                    <?php else: ?>
                                        <input type="text" class="form-control" 
                                               id="<?= $setting['setting_key'] ?>" 
                                               name="settings[<?= $setting['setting_key'] ?>]" 
                                               value="<?= htmlspecialchars($setting['setting_value']) ?>">
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Settings
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                    
                <?php elseif ($page === 'reports'): ?>
                    <!-- Reports & Analytics -->
                    <div class="page-header">
                        <h2>Reports & Analytics</h2>
                        <div class="header-actions">
                            <button class="btn btn-outline-primary" onclick="exportReport('pdf')">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </button>
                            <button class="btn btn-outline-primary" onclick="exportReport('excel')">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </button>
                            <button class="btn btn-primary" onclick="printReport()">
                                <i class="fas fa-print me-1"></i>Print Report
                            </button>
                        </div>
                    </div>
                    
                    <div class="reports-grid">
                        <!-- Risk Assessment Summary -->
                        <div class="report-card">
                            <div class="report-header">
                                <h4>Risk Assessment Summary</h4>
                                <div class="report-period">
                                    <select class="form-select form-select-sm" id="reportPeriod" onchange="changeReportPeriod()">
                                        <option value="30">Last 30 Days</option>
                                        <option value="90">Last 90 Days</option>
                                        <option value="180">Last 6 Months</option>
                                        <option value="365">Last Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="report-content">
                                <div class="summary-stats">
                                    <div class="summary-item">
                                        <span class="summary-label">Total Assessments</span>
                                        <span class="summary-value"><?= $totalAssessments ?></span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">High Risk</span>
                                        <span class="summary-value text-danger"><?= $highRiskCount ?></span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Medium Risk</span>
                                        <span class="summary-value text-warning"><?= $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE overall_risk_rating = 'Medium-risk'")->fetchColumn() ?></span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Low Risk</span>
                                        <span class="summary-value text-success"><?= $lowRiskCount ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Trends Chart -->
                        <div class="report-card">
                            <div class="report-header">
                                <h4>Risk Trends</h4>
                                <div class="chart-controls">
                                    <button class="btn-chart active" onclick="changeChartPeriod('monthly')">Monthly</button>
                                    <button class="btn-chart" onclick="changeChartPeriod('quarterly')">Quarterly</button>
                                </div>
                            </div>
                            <div class="report-content">
                                <div class="chart-container">
                                    <canvas id="monthlyTrendChart" height="200"></canvas>
                                    <div class="chart-loading" id="trendChartLoading">
                                        <i class="fas fa-chart-line"></i>
                                        <p>Loading trend data...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Distribution -->
                        <div class="report-card">
                            <div class="report-header">
                                <h4>Risk Distribution by Category</h4>
                            </div>
                            <div class="report-content">
                                <div class="chart-container">
                                    <canvas id="categoryDistributionChart" height="200"></canvas>
                                    <div class="chart-loading" id="distributionChartLoading">
                                        <i class="fas fa-chart-pie"></i>
                                        <p>Loading distribution data...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Risk Factors -->
                        <div class="report-card">
                            <div class="report-header">
                                <h4>Top Risk Factors</h4>
                            </div>
                            <div class="report-content">
                                <div class="risk-factors-list">
                                    <?php
                                    // Get risk factor statistics
                                    $screeningCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE screening_risk_rating IN ('High', 'Very High')")->fetchColumn();
                                    $paymentCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE payment_risk_rating IN ('High', 'Very High')")->fetchColumn();
                                    $serviceCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE services_risk_rating IN ('High', 'Very High')")->fetchColumn();
                                    $categoryCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments WHERE client_category_risk_rating IN ('High', 'Very High')")->fetchColumn();
                                    
                                    $totalAssessments = $pdo->query("SELECT COUNT(*) FROM risk_assessments")->fetchColumn();
                                    $maxCount = max($screeningCount, $paymentCount, $serviceCount, $categoryCount);
                                    ?>
                                    
                                    <div class="risk-factor">
                                        <div class="factor-info">
                                            <span class="factor-name">Client Screening</span>
                                            <div class="factor-bar">
                                                <div class="bar-fill" style="width: <?= $maxCount > 0 ? ($screeningCount / $maxCount) * 100 : 0 ?>%"></div>
                                            </div>
                                        </div>
                                        <span class="factor-count"><?= $screeningCount ?></span>
                                    </div>
                                    <div class="risk-factor">
                                        <div class="factor-info">
                                            <span class="factor-name">Payment Methods</span>
                                            <div class="factor-bar">
                                                <div class="bar-fill" style="width: <?= $maxCount > 0 ? ($paymentCount / $maxCount) * 100 : 0 ?>%"></div>
                                            </div>
                                        </div>
                                        <span class="factor-count"><?= $paymentCount ?></span>
                                    </div>
                                    <div class="risk-factor">
                                        <div class="factor-info">
                                            <span class="factor-name">Service Delivery</span>
                                            <div class="factor-bar">
                                                <div class="bar-fill" style="width: <?= $maxCount > 0 ? ($serviceCount / $maxCount) * 100 : 0 ?>%"></div>
                                            </div>
                                        </div>
                                        <span class="factor-count"><?= $serviceCount ?></span>
                                    </div>
                                    <div class="risk-factor">
                                        <div class="factor-info">
                                            <span class="factor-name">Client Category</span>
                                            <div class="factor-bar">
                                                <div class="bar-fill" style="width: <?= $maxCount > 0 ? ($categoryCount / $maxCount) * 100 : 0 ?>%"></div>
                                            </div>
                                        </div>
                                        <span class="factor-count"><?= $categoryCount ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detailed Reports Table -->
                    <div class="report-card">
                        <div class="report-header">
                            <h4>Detailed Risk Assessments</h4>
                            <div class="table-controls">
                                <input type="text" id="searchTable" placeholder="Search assessments..." class="form-control form-control-sm" style="width: 200px;">
                                <select id="filterRisk" class="form-select form-select-sm" style="width: 150px;">
                                    <option value="">All Risk Levels</option>
                                    <option value="Very High-risk">Very High Risk</option>
                                    <option value="High-risk">High Risk</option>
                                    <option value="Medium-risk">Medium Risk</option>
                                    <option value="Low-risk">Low Risk</option>
                                </select>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="table-responsive">
                                <table class="table table-sm" id="reportsTable">
                                    <thead>
                                        <tr>
                                            <th>Client Name</th>
                                            <th>Risk Rating</th>
                                            <th>Risk Points</th>
                                            <th>Client Acceptance</th>
                                            <th>Assessment Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $reportsAssessments = $pdo->query("SELECT * FROM risk_assessments ORDER BY created_at DESC")->fetchAll();
                                        foreach ($reportsAssessments as $assessment):
                                        ?>
                                        <tr data-risk="<?= $assessment['overall_risk_rating'] ?>">
                                            <td><?= htmlspecialchars($assessment['client_name']) ?></td>
                                            <td>
                                                <?php
                                                $badgeClass = 'bg-secondary';
                                                if ($assessment['overall_risk_rating'] === 'Very High-risk') $badgeClass = 'bg-danger';
                                                elseif ($assessment['overall_risk_rating'] === 'High-risk') $badgeClass = 'bg-warning';
                                                elseif ($assessment['overall_risk_rating'] === 'Medium-risk') $badgeClass = 'bg-info';
                                                elseif ($assessment['overall_risk_rating'] === 'Low-risk') $badgeClass = 'bg-success';
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= $assessment['overall_risk_rating'] ?? 'N/A' ?></span>
                                            </td>
                                            <td><?= $assessment['overall_risk_points'] ?? 'N/A' ?></td>
                                            <td>
                                                <span class="badge bg-<?= $assessment['client_acceptance'] === 'Do not accept client' ? 'danger' : 'success' ?>">
                                                    <?= $assessment['client_acceptance'] ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($assessment['created_at'])) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewAssessmentDetails(<?= $assessment['id'] ?>)">View</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php elseif ($page === 'risk-register'): ?>
                    <!-- Comprehensive Risk Register -->
                    <div class="page-header">
                        <h2>DCS Enhanced Risk Register</h2>
                        <p>Comprehensive risk register for client acceptance and retention</p>
                    </div>
                    
                    <!-- Comprehensive Risk Register Description -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-info-circle"></i> Risk Register Overview
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="lead mb-3">
                                                <strong>DCS Enhanced Risk Register for Client Acceptance and Retention</strong>
                                            </p>
                                            <p class="text-justify">
                                                This risk register identifies, assesses, and mitigates risks in client acceptance and retention while ensuring compliance with Namibia's Financial Intelligence Act (FIA) and Financial Intelligence Centre (FIC) requirements, including Anti-Money Laundering (AML), Counter-Terrorist Financing (CTF), and Know Your Customer (KYC) obligations.
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-shield-alt"></i> Compliance Focus</h6>
                                                <ul class="mb-0 small">
                                                    <li>Financial Intelligence Act (FIA)</li>
                                                    <li>Financial Intelligence Centre (FIC)</li>
                                                    <li>Anti-Money Laundering (AML)</li>
                                                    <li>Counter-Terrorist Financing (CTF)</li>
                                                    <li>Know Your Customer (KYC)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comprehensive Risk Categories & Key Risks -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-exclamation-triangle"></i> Risk Categories & Key Risks
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Risk ID</th>
                                                    <th>Risk Description</th>
                                                    <th>Risk Detail</th>
                                                    <th>Risk Category</th>
                                                    <th>Impact (H/M/L)</th>
                                                    <th>Likelihood (H/M/L)</th>
                                                    <th>Risk Rating (H/M/L)</th>
                                                    <th>Mitigation Strategies</th>
                                                    <th>Owner</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Client Risks -->
                                                <tr class="table-danger">
                                                    <td><strong>CR-01</strong></td>
                                                    <td>PIP / PEP client</td>
                                                    <td>High-risk client (e.g., politically exposed person, high-net-worth individual)</td>
                                                    <td><span class="badge badge-primary">Client Risk</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td>Enhanced Due Diligence (EDD), ongoing monitoring</td>
                                                    <td>Compliance Officer</td>
                                                    <td><span class="badge badge-warning">Open</span></td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <td><strong>CR-02</strong></td>
                                                    <td>Corporate client</td>
                                                    <td>Business entity with complex ownership structure</td>
                                                    <td><span class="badge badge-primary">Client Risk</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td>Corporate verification, beneficial ownership checks</td>
                                                    <td>Compliance Officer</td>
                                                    <td><span class="badge badge-warning">Open</span></td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td><strong>CR-03</strong></td>
                                                    <td>Individual client</td>
                                                    <td>Standard individual client with low-risk profile</td>
                                                    <td><span class="badge badge-primary">Client Risk</span></td>
                                                    <td><span class="badge badge-success">Low</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td>Standard KYC procedures</td>
                                                    <td>Relationship Manager</td>
                                                    <td><span class="badge badge-success">Closed</span></td>
                                                </tr>
                                                
                                                <!-- Service Risks -->
                                                <tr class="table-danger">
                                                    <td><strong>SR-01</strong></td>
                                                    <td>High-risk services</td>
                                                    <td>Services with elevated money laundering risk</td>
                                                    <td><span class="badge badge-info">Service Risk</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td>Enhanced monitoring, transaction limits</td>
                                                    <td>Service Manager</td>
                                                    <td><span class="badge badge-warning">Open</span></td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <td><strong>SR-02</strong></td>
                                                    <td>Complex services</td>
                                                    <td>Services requiring specialized expertise</td>
                                                    <td><span class="badge badge-info">Service Risk</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td>Expert review, quality assurance</td>
                                                    <td>Service Manager</td>
                                                    <td><span class="badge badge-warning">Open</span></td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td><strong>SR-03</strong></td>
                                                    <td>Standard services</td>
                                                    <td>Routine services with established procedures</td>
                                                    <td><span class="badge badge-info">Service Risk</span></td>
                                                    <td><span class="badge badge-success">Low</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-success">Low</span></td>
                                                    <td>Standard procedures, regular review</td>
                                                    <td>Service Manager</td>
                                                    <td><span class="badge badge-success">Closed</span></td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <td><strong>SR-04</strong></td>
                                                    <td>Unrecorded face-to-face transactions</td>
                                                    <td>Cash transactions without proper documentation</td>
                                                    <td><span class="badge badge-info">Service Risk</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td><span class="badge badge-success">Low</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td>Mandatory documentation, audit trail</td>
                                                    <td>Operations Manager</td>
                                                    <td><span class="badge badge-warning">Open</span></td>
                                                </tr>
                                                
                                                <!-- Payment Risks -->
                                                <tr class="table-danger">
                                                    <td><strong>PR-01</strong></td>
                                                    <td>Cash Payments</td>
                                                    <td>High-risk payment method with limited traceability</td>
                                                    <td><span class="badge badge-warning">Payment Risk</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td>Enhanced due diligence, transaction limits</td>
                                                    <td>Finance Manager</td>
                                                    <td><span class="badge badge-warning">Open</span></td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <td><strong>PR-02</strong></td>
                                                    <td>EFTs/SWIFT</td>
                                                    <td>Electronic fund transfers with moderate risk</td>
                                                    <td><span class="badge badge-warning">Payment Risk</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td>Transaction monitoring, source verification</td>
                                                    <td>Finance Manager</td>
                                                    <td><span class="badge badge-warning">Open</span></td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td><strong>PR-03</strong></td>
                                                    <td>POS Payments</td>
                                                    <td>Point-of-sale transactions with low risk</td>
                                                    <td><span class="badge badge-warning">Payment Risk</span></td>
                                                    <td><span class="badge badge-success">Low</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-success">Low</span></td>
                                                    <td>Standard monitoring, regular review</td>
                                                    <td>Finance Manager</td>
                                                    <td><span class="badge badge-success">Closed</span></td>
                                                </tr>
                                                
                                                <!-- Delivery Risks -->
                                                <tr class="table-warning">
                                                    <td><strong>DR-01</strong></td>
                                                    <td>Remote service risks</td>
                                                    <td>Services delivered remotely with security concerns</td>
                                                    <td><span class="badge badge-secondary">Delivery Risk</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-danger">High</span></td>
                                                    <td>Secure platforms, identity verification</td>
                                                    <td>IT Manager</td>
                                                    <td><span class="badge badge-warning">Open</span></td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td><strong>DR-02</strong></td>
                                                    <td>Face-to-face service risks</td>
                                                    <td>In-person service delivery with moderate risk</td>
                                                    <td><span class="badge badge-secondary">Delivery Risk</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td><span class="badge badge-success">Low</span></td>
                                                    <td><span class="badge badge-warning">Medium</span></td>
                                                    <td>Staff training, security protocols</td>
                                                    <td>Operations Manager</td>
                                                    <td><span class="badge badge-warning">Open</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Risk Rating Methodology -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-calculator"></i> Risk Rating Methodology
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card border-primary">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0"><i class="fas fa-exclamation-circle"></i> Impact (I)</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-2">
                                                        <span class="badge badge-danger">High</span>
                                                        <small class="text-muted">Legal penalties, reputational damage, major financial loss</small>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="badge badge-warning">Medium</span>
                                                        <small class="text-muted">Operational disruption, moderate financial impact</small>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="badge badge-success">Low</span>
                                                        <small class="text-muted">Minor inconvenience, minimal financial impact</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-warning">
                                                <div class="card-header bg-warning text-dark">
                                                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Likelihood (L)</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-2">
                                                        <span class="badge badge-danger">High</span>
                                                        <small class="text-muted">Very likely to occur (frequent)</small>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="badge badge-warning">Medium</span>
                                                        <small class="text-muted">May occur occasionally</small>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="badge badge-success">Low</span>
                                                        <small class="text-muted">Unlikely to occur (rare)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-info">
                                                <div class="card-header bg-info text-white">
                                                    <h6 class="mb-0"><i class="fas fa-calculator"></i> Risk Rating (R)</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-2">
                                                        <span class="badge badge-danger">High</span>
                                                        <small class="text-muted">I=High & L=High/Medium OR I=Medium & L=High</small>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="badge badge-warning">Medium</span>
                                                        <small class="text-muted">I=High & L=Low OR I=Medium & L=Medium</small>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="badge badge-success">Low</span>
                                                        <small class="text-muted">I=Low & L=Any OR I=Medium & L=Low</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Key Controls & Best Practices -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-shield-alt"></i> Key Controls & Best Practices
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border-info mb-3">
                                                <div class="card-header bg-info text-white">
                                                    <h6 class="mb-0"><i class="fas fa-users"></i> 1. Client Acceptance</h6>
                                                </div>
                                                <div class="card-body">
                                                    <h6 class="text-info">Corporate Clients</h6>
                                                    <ul class="small">
                                                        <li>Verify legal existence</li>
                                                        <li>Proof of trading address</li>
                                                        <li>Beneficial ownership identification</li>
                                                        <li>Board resolution for account opening</li>
                                                        <li>Financial statements review</li>
                                                    </ul>
                                                    <h6 class="text-info">Individuals</h6>
                                                    <ul class="small">
                                                        <li>Validate ID documents</li>
                                                        <li>Proof of address verification</li>
                                                        <li>Source of funds documentation</li>
                                                        <li>Employment verification</li>
                                                        <li>Risk profile assessment</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-success mb-3">
                                                <div class="card-header bg-success text-white">
                                                    <h6 class="mb-0"><i class="fas fa-cogs"></i> 2. Service Delivery</h6>
                                                </div>
                                                <div class="card-body">
                                                    <h6 class="text-success">High-Risk Services</h6>
                                                    <ul class="small">
                                                        <li>Enhanced due diligence</li>
                                                        <li>Transaction monitoring</li>
                                                        <li>Regular risk reviews</li>
                                                        <li>Management oversight</li>
                                                        <li>Documentation requirements</li>
                                                    </ul>
                                                    <h6 class="text-success">Standard Services</h6>
                                                    <ul class="small">
                                                        <li>Standard procedures</li>
                                                        <li>Regular quality checks</li>
                                                        <li>Client feedback collection</li>
                                                        <li>Performance monitoring</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-warning mb-3">
                                                <div class="card-header bg-warning text-dark">
                                                    <h6 class="mb-0"><i class="fas fa-credit-card"></i> 3. Payment Methods</h6>
                                                </div>
                                                <div class="card-body">
                                                    <h6 class="text-warning">Cash Payments</h6>
                                                    <ul class="small">
                                                        <li>Enhanced due diligence</li>
                                                        <li>Transaction limits</li>
                                                        <li>Source verification</li>
                                                        <li>Regular monitoring</li>
                                                        <li>Reporting requirements</li>
                                                    </ul>
                                                    <h6 class="text-warning">Electronic Payments</h6>
                                                    <ul class="small">
                                                        <li>Account verification</li>
                                                        <li>Transaction monitoring</li>
                                                        <li>Fraud detection</li>
                                                        <li>Compliance checks</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-primary mb-3">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0"><i class="fas fa-eye"></i> 4. Ongoing Monitoring</h6>
                                                </div>
                                                <div class="card-body">
                                                    <h6 class="text-primary">High-Risk Clients</h6>
                                                    <ul class="small">
                                                        <li>Enhanced monitoring</li>
                                                        <li>Quarterly reviews</li>
                                                        <li>Transaction analysis</li>
                                                        <li>Risk reassessment</li>
                                                        <li>Management reporting</li>
                                                    </ul>
                                                    <h6 class="text-primary">Standard Clients</h6>
                                                    <ul class="small">
                                                        <li>Annual reviews</li>
                                                        <li>Transaction monitoring</li>
                                                        <li>Risk updates</li>
                                                        <li>Compliance checks</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review & Approval Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-clipboard-check"></i> Review & Approval
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><strong>Reviewed by:</strong></label>
                                                <input type="text" class="form-control" placeholder="[Compliance Officer / Risk Manager]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><strong>Approved by:</strong></label>
                                                <input type="text" class="form-control" placeholder="[Senior Management]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><strong>Next Review Date:</strong></label>
                                                <input type="text" class="form-control" placeholder="[Annual Review]" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Note:</strong> This risk register ensures a structured approach to managing risks in client acceptance and retention while complying with regulatory requirements (e.g., AML, GDPR, industry-specific laws). Adjust based on your organization's specific risk appetite and policies.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($page === 'settings'): ?>
                    <!-- Settings -->
                    <div class="page-header">
                        <h2>System Settings</h2>
                    </div>
                    
                    <div class="settings-grid">
                        <!-- General Settings -->
                        <div class="settings-card">
                            <div class="settings-header">
                                <h4>General Settings</h4>
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="settings-content">
                                <div class="setting-item">
                                    <label class="setting-label">System Name</label>
                                    <input type="text" class="form-control" value="DCS Risk Assessment System">
                                </div>
                                <div class="setting-item">
                                    <label class="setting-label">Default Risk Appetite</label>
                                    <select class="form-select">
                                        <option>Conservative</option>
                                        <option selected>Moderate</option>
                                        <option>Aggressive</option>
                                    </select>
                                </div>
                                <div class="setting-item">
                                    <label class="setting-label">Assessment Auto-Save</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                        <label class="form-check-label">Enable auto-save</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="settings-card">
                            <div class="settings-header">
                                <h4>Notification Settings</h4>
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="settings-content">
                                <div class="setting-item">
                                    <label class="setting-label">Email Notifications</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                        <label class="form-check-label">High-risk assessments</label>
                                    </div>
                                </div>
                                <div class="setting-item">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                        <label class="form-check-label">Weekly reports</label>
                                    </div>
                                </div>
                                <div class="setting-item">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label">Monthly summaries</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div class="settings-card">
                            <div class="settings-header">
                                <h4>Security Settings</h4>
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="settings-content">
                                <div class="setting-item">
                                    <label class="setting-label">Session Timeout</label>
                                    <select class="form-select">
                                        <option>15 minutes</option>
                                        <option selected>30 minutes</option>
                                        <option>1 hour</option>
                                        <option>2 hours</option>
                                    </select>
                                </div>
                                <div class="setting-item">
                                    <label class="setting-label">Password Policy</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                        <label class="form-check-label">Require strong passwords</label>
                                    </div>
                                </div>
                                <div class="setting-item">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                        <label class="form-check-label">Two-factor authentication</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Backup Settings -->
                        <div class="settings-card">
                            <div class="settings-header">
                                <h4>Backup & Maintenance</h4>
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="settings-content">
                                <div class="setting-item">
                                    <label class="setting-label">Auto Backup</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                        <label class="form-check-label">Daily backup</label>
                                    </div>
                                </div>
                                <div class="setting-item">
                                    <label class="setting-label">Backup Retention</label>
                                    <select class="form-select">
                                        <option>7 days</option>
                                        <option selected>30 days</option>
                                        <option>90 days</option>
                                    </select>
                                </div>
                                <div class="setting-item">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>Manual Backup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($page === 'view_assessment'): ?>
                    <!-- View Risk Assessment -->
                    <?php
                    $assessment_id = $_GET['id'] ?? 0;
                    $stmt = $pdo->prepare("SELECT * FROM risk_assessments WHERE id = ?");
                    $stmt->execute([$assessment_id]);
                    $assessment = $stmt->fetch();
                    
                    if (!$assessment):
                    ?>
                        <div class="alert alert-danger">Assessment not found.</div>
                    <?php else: ?>
                        <div class="page-header">
                            <h2>Risk Assessment Details</h2>
                            <div class="header-actions">
                                <a href="index.php?page=risks" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to List
                                </a>
                                <button class="btn btn-primary" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i>Print
                                </button>
                            </div>
                        </div>
                        
                        <div class="assessment-details">
                            <div class="detail-section">
                                <h4>Client Information</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Client Name:</strong> <?= htmlspecialchars($assessment['client_name']) ?></p>
                                        <p><strong>Identification Status:</strong> <?= $assessment['client_identification'] ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Assessment Date:</strong> <?= date('M d, Y', strtotime($assessment['created_at'])) ?></p>
                                        <p><strong>Risk Appetite:</strong> <?= $assessment['dcs_risk_appetite'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-section">
                                <h4>Risk Assessment Results</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="risk-summary-card">
                                            <h5>Overall Risk Points</h5>
                                            <div class="risk-value"><?= $assessment['overall_risk_points'] ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="risk-summary-card">
                                            <h5>Risk Rating</h5>
                                            <div class="risk-value <?= strtolower(str_replace('-', '-', $assessment['overall_risk_rating'])) ?>">
                                                <?= $assessment['overall_risk_rating'] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="risk-summary-card">
                                            <h5>Client Acceptance</h5>
                                            <div class="risk-value"><?= $assessment['client_acceptance'] ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="risk-summary-card">
                                            <h5>Monitoring</h5>
                                            <div class="risk-value"><?= $assessment['ongoing_monitoring'] ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-section">
                                <h4>Risk Categories</h4>
                                <div class="risk-categories">
                                    <div class="risk-category">
                                        <h6>Client Screening</h6>
                                        <p><strong>Risk ID:</strong> <?= $assessment['screening_risk_id'] ?></p>
                                        <p><strong>Description:</strong> <?= $assessment['screening_description'] ?></p>
                                        <p><strong>Impact:</strong> <?= $assessment['screening_impact'] ?></p>
                                        <p><strong>Likelihood:</strong> <?= $assessment['screening_likelihood'] ?></p>
                                        <p><strong>Rating:</strong> <?= $assessment['screening_risk_rating'] ?></p>
                                    </div>
                                    
                                    <div class="risk-category">
                                        <h6>Client Category</h6>
                                        <p><strong>Risk ID:</strong> <?= $assessment['client_category_risk_id'] ?></p>
                                        <p><strong>Description:</strong> <?= $assessment['client_category_description'] ?></p>
                                        <p><strong>Impact:</strong> <?= $assessment['client_category_impact'] ?></p>
                                        <p><strong>Likelihood:</strong> <?= $assessment['client_category_likelihood'] ?></p>
                                        <p><strong>Rating:</strong> <?= $assessment['client_category_risk_rating'] ?></p>
                                    </div>
                                    
                                    <div class="risk-category">
                                        <h6>Services</h6>
                                        <p><strong>Risk ID:</strong> <?= $assessment['services_risk_id'] ?></p>
                                        <p><strong>Description:</strong> <?= $assessment['services_description'] ?></p>
                                        <p><strong>Impact:</strong> <?= $assessment['services_impact'] ?></p>
                                        <p><strong>Likelihood:</strong> <?= $assessment['services_likelihood'] ?></p>
                                        <p><strong>Rating:</strong> <?= $assessment['services_risk_rating'] ?></p>
                                    </div>
                                    
                                    <div class="risk-category">
                                        <h6>Payment Method</h6>
                                        <p><strong>Risk ID:</strong> <?= $assessment['payment_risk_id'] ?></p>
                                        <p><strong>Description:</strong> <?= $assessment['payment_description'] ?></p>
                                        <p><strong>Impact:</strong> <?= $assessment['payment_impact'] ?></p>
                                        <p><strong>Likelihood:</strong> <?= $assessment['payment_likelihood'] ?></p>
                                        <p><strong>Rating:</strong> <?= $assessment['payment_risk_rating'] ?></p>
                                    </div>
                                    
                                    <div class="risk-category">
                                        <h6>Service Delivery</h6>
                                        <p><strong>Risk ID:</strong> <?= $assessment['delivery_risk_id'] ?></p>
                                        <p><strong>Description:</strong> <?= $assessment['delivery_description'] ?></p>
                                        <p><strong>Impact:</strong> <?= $assessment['delivery_impact'] ?></p>
                                        <p><strong>Likelihood:</strong> <?= $assessment['delivery_likelihood'] ?></p>
                                        <p><strong>Rating:</strong> <?= $assessment['delivery_risk_rating'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($assessment['dcs_comments']): ?>
                            <div class="detail-section">
                                <h4>DCS Comments</h4>
                                <p><?= nl2br(htmlspecialchars($assessment['dcs_comments'])) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php elseif ($page === 'edit_assessment'): ?>
                    <!-- Edit Risk Assessment -->
                    <?php
                    $assessment_id = $_GET['id'] ?? 0;
                    $stmt = $pdo->prepare("SELECT * FROM risk_assessments WHERE id = ?");
                    $stmt->execute([$assessment_id]);
                    $assessment = $stmt->fetch();
                    
                    if (!$assessment):
                    ?>
                        <div class="alert alert-danger">Assessment not found.</div>
                    <?php else: ?>
                        <div class="page-header">
                            <h2>Edit Risk Assessment</h2>
                            <a href="index.php?page=risks" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to List
                            </a>
                        </div>
                        
                        <div class="content-card">
                            <p class="text-muted">Edit functionality will be implemented here. For now, please create a new assessment.</p>
                            <a href="index.php?page=create" class="btn btn-primary">Create New Assessment</a>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        <?php if (isLoggedIn()): ?>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Search functionality
    function performSearch() {
        const searchTerm = document.getElementById('searchInput').value;
        const resultsDiv = document.getElementById('searchResults');
        
        if (searchTerm.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }
        
        fetch('search.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'search=' + encodeURIComponent(searchTerm)
        })
        .then(response => response.text())
        .then(data => {
            resultsDiv.innerHTML = data;
            resultsDiv.style.display = 'block';
        });
    }
    
    // Notification functionality
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }
    
    function markAllRead() {
        fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=mark_all_read'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('notificationCount').textContent = '0';
                document.getElementById('notificationList').innerHTML = '<div class="notification-item empty">No new notifications</div>';
            }
        });
    }
    
    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-box')) {
            document.getElementById('searchResults').style.display = 'none';
        }
        if (!e.target.closest('.notification-bell')) {
            document.getElementById('notificationDropdown').style.display = 'none';
        }
        if (!e.target.closest('.user-profile')) {
            document.getElementById('userDropdown').style.display = 'none';
        }
    });
    
    function toggleUserMenu() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }
    
    function exportReport(format) {
        // Get current filter values
        const period = document.getElementById('reportPeriod')?.value || '30';
        const url = `export.php?format=${format}&period=${period}`;
        window.open(url, '_blank');
    }
    
    function printReport() {
        // Create a print-friendly version of the reports page
        const printWindow = window.open('', '_blank');
        const reportsContent = document.querySelector('.reports-grid').innerHTML;
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>DCS Risk Assessment Reports</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .report-card { border: 1px solid #ddd; margin-bottom: 20px; padding: 15px; }
                    .report-header { border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px; }
                    .summary-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
                    .summary-item { text-align: center; padding: 10px; border: 1px solid #eee; }
                    .risk-factors-list { margin-top: 15px; }
                    .risk-factor { display: flex; justify-content: space-between; margin-bottom: 10px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f5f5f5; }
                    @media print { .no-print { display: none; } }
                </style>
            </head>
            <body>
                <h1>DCS Risk Assessment Reports</h1>
                <p>Generated on ${new Date().toLocaleDateString()}</p>
                ${reportsContent}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
    
    // Real-time data refresh
    function refreshDashboardData() {
        fetch('dashboard_data.php')
            .then(response => response.json())
            .then(data => {
                // Update counters
                document.querySelectorAll('.counter').forEach(counter => {
                    const target = counter.getAttribute('data-target');
                    const newValue = data[target] || 0;
                    counter.textContent = newValue;
                    counter.setAttribute('data-target', newValue);
                });
                
                // Update percentage changes
                if (data.percentageChanges) {
                    updatePercentageChanges(data.percentageChanges);
                }
                
                // Update notification count
                const notificationCount = document.getElementById('notificationCount');
                if (notificationCount) {
                    notificationCount.textContent = data.notificationCount || 0;
                }
                
                // Update trend chart if on dashboard
                if (data.trendData && window.location.search.includes('page=dashboard') || window.location.search === '') {
                    updateDashboardTrendChart(data.trendData);
                }
                

            })
            .catch(error => console.error('Error refreshing data:', error));
    }
    
    function updatePercentageChanges(changes) {
        // Update percentage change indicators
        const assessmentChange = document.querySelector('[data-change="totalAssessments"]');
        const highRiskChange = document.querySelector('[data-change="highRisk"]');
        const lowRiskChange = document.querySelector('[data-change="lowRisk"]');
        const usersChange = document.querySelector('[data-change="users"]');
        
        if (assessmentChange) {
            assessmentChange.textContent = changes.totalAssessments;
            assessmentChange.className = changes.totalAssessments.startsWith('+') ? 'stat-trend positive' : 'stat-trend negative';
        }
        if (highRiskChange) {
            highRiskChange.textContent = changes.highRisk;
            highRiskChange.className = changes.highRisk.startsWith('+') ? 'stat-trend positive' : 'stat-trend negative';
        }
        if (lowRiskChange) {
            lowRiskChange.textContent = changes.lowRisk;
            lowRiskChange.className = changes.lowRisk.startsWith('+') ? 'stat-trend positive' : 'stat-trend negative';
        }
        if (usersChange) {
            usersChange.textContent = changes.users;
            usersChange.className = changes.users.startsWith('+') ? 'stat-trend positive' : 'stat-trend negative';
        }
    }
    
    function updateDashboardTrendChart(trendData) {
        const ctx = document.getElementById('riskTrendChart');
        if (ctx && window.dashboardTrendChart) {
            const labels = trendData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            });
            const values = trendData.map(item => parseInt(item.count));
            
            window.dashboardTrendChart.data.labels = labels;
            window.dashboardTrendChart.data.datasets[0].data = values;
            window.dashboardTrendChart.update();
        }
    }
    

    
    function timeAgo(datetime) {
        const time = Math.floor((new Date() - new Date(datetime)) / 1000);
        if (time < 60) return 'Just now';
        if (time < 3600) return Math.floor(time / 60) + 'm ago';
        if (time < 86400) return Math.floor(time / 3600) + 'h ago';
        return Math.floor(time / 86400) + 'd ago';
    }
    
    // Update header data dynamically
    function updateHeaderData() {
        fetch('dashboard_data.php')
            .then(response => response.json())
            .then(data => {
                // Update notification count
                const notificationCount = document.getElementById('notificationCount');
                if (notificationCount) {
                    notificationCount.textContent = data.notificationCount || 0;
                }
                
                // Update user info if needed
                const userName = document.querySelector('.header-left h1');
                if (userName && data.currentUser) {
                    userName.textContent = `Welcome back, ${data.currentUser.name}`;
                }
            })
            .catch(error => console.error('Error updating header data:', error));
    }
    
    // Auto-refresh every 30 seconds
    setInterval(refreshDashboardData, 30000);
    
    // Update header data every 10 seconds
    setInterval(updateHeaderData, 10000);
    
    // Update notifications in real-time
    function updateNotifications() {
        fetch('notifications.php')
            .then(response => response.text())
            .then(html => {
                const notificationList = document.getElementById('notificationList');
                if (notificationList) {
                    notificationList.innerHTML = html;
                }
            })
            .catch(error => console.error('Error updating notifications:', error));
    }
    
    // Update notifications every 15 seconds
    setInterval(updateNotifications, 15000);
    
    // Reports page functions
    function changeChartPeriod(period) {
        // Update chart buttons
        document.querySelectorAll('.btn-chart').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        
        // Update charts based on period
        updateCharts(period);
    }
    
    function updateCharts(period) {
        // Fetch data for the selected period
        fetch(`reports_data.php?period=${period}`)
            .then(response => response.json())
            .then(data => {
                updateTrendChart(data.trendData);
                updateDistributionChart(data.distributionData);
                updateRiskFactors(data.riskFactors);
            })
            .catch(error => console.error('Error updating charts:', error));
    }
    
    function updateTrendChart(data) {
        const ctx = document.getElementById('monthlyTrendChart');
        if (ctx && window.trendChart) {
            window.trendChart.data.labels = data.labels;
            window.trendChart.data.datasets[0].data = data.values;
            window.trendChart.update();
        }
    }
    
    function updateDistributionChart(data) {
        const ctx = document.getElementById('categoryDistributionChart');
        if (ctx && window.distributionChart) {
            window.distributionChart.data.datasets[0].data = data.values;
            window.distributionChart.update();
        }
    }
    
    function updateRiskFactors(data) {
        const riskFactorsList = document.querySelector('.risk-factors-list');
        if (riskFactorsList) {
            riskFactorsList.innerHTML = data.map(factor => `
                <div class="risk-factor">
                    <div class="factor-info">
                        <span class="factor-name">${factor.name}</span>
                        <div class="factor-bar">
                            <div class="bar-fill" style="width: ${factor.percentage}%"></div>
                        </div>
                    </div>
                    <span class="factor-count">${factor.count}</span>
                </div>
            `).join('');
        }
    }
    
    function changeReportPeriod() {
        const period = document.getElementById('reportPeriod').value;
        updateReportData(period);
    }
    
    function updateReportData(period) {
        fetch(`reports_data.php?period=${period}`)
            .then(response => response.json())
            .then(data => {
                // Update summary stats
                document.querySelectorAll('.summary-value').forEach((el, index) => {
                    const values = [data.totalAssessments, data.highRiskCount, data.mediumRiskCount, data.lowRiskCount];
                    if (values[index] !== undefined) {
                        el.textContent = values[index];
                    }
                });
                
                // Update charts
                updateCharts(period);
            })
            .catch(error => console.error('Error updating report data:', error));
    }
    
    // Table filtering and search functions
    function filterReportsTable() {
        const searchTerm = document.getElementById('searchTable').value.toLowerCase();
        const riskFilter = document.getElementById('filterRisk').value;
        const table = document.getElementById('reportsTable');
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const clientName = row.cells[0].textContent.toLowerCase();
            const riskRating = row.getAttribute('data-risk');
            const riskPoints = row.cells[2].textContent.toLowerCase();
            const acceptance = row.cells[3].textContent.toLowerCase();
            
            const matchesSearch = clientName.includes(searchTerm) || 
                                riskPoints.includes(searchTerm) || 
                                acceptance.includes(searchTerm);
            const matchesRisk = !riskFilter || riskRating === riskFilter;
            
            row.style.display = matchesSearch && matchesRisk ? '' : 'none';
        });
    }
    
    function viewAssessmentDetails(id) {
        // Open assessment details in a modal or new page
        window.open(`index.php?page=view_assessment&id=${id}`, '_blank');
    }
    
    // Add event listeners for table controls
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchTable');
        const filterSelect = document.getElementById('filterRisk');
        
        if (searchInput) {
            searchInput.addEventListener('input', filterReportsTable);
        }
        
        if (filterSelect) {
            filterSelect.addEventListener('change', filterReportsTable);
        }
    });
    
    // Update page title and header based on current page
    function updatePageHeader() {
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') || 'dashboard';
        
        let pageTitle = 'Dashboard';
        let headerTitle = 'Welcome back, <?= htmlspecialchars($currentUser['name']) ?>';
        let headerSubtitle = 'Here\'s what\'s happening with your risk assessments today';
        
        switch(page) {
            case 'risks':
                pageTitle = 'Manage Risks';
                headerTitle = 'Risk Management';
                headerSubtitle = 'View and manage all risk assessments';
                break;
            case 'users':
                pageTitle = 'User Management';
                headerTitle = 'User Management';
                headerSubtitle = 'Manage system users and permissions';
                break;
            case 'reports':
                pageTitle = 'Reports & Analytics';
                headerTitle = 'Reports & Analytics';
                headerSubtitle = 'Generate reports and view analytics';
                break;
            case 'settings':
                pageTitle = 'Settings';
                headerTitle = 'System Settings';
                headerSubtitle = 'Configure system preferences';
                break;
            case 'create':
                pageTitle = 'Create Risk Assessment';
                headerTitle = 'Create New Assessment';
                headerSubtitle = 'Add a new risk assessment';
                break;
        }
        
        // Update page title
        document.title = `DCS Portal - ${pageTitle}`;
        
        // Update header if elements exist
        const headerTitleEl = document.querySelector('.header-left h1');
        const headerSubtitleEl = document.querySelector('.header-subtitle');
        
        if (headerTitleEl) {
            headerTitleEl.textContent = headerTitle;
        }
        if (headerSubtitleEl) {
            headerSubtitleEl.textContent = headerSubtitle;
        }
    }
    
    <script>
    // Counter Animation
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 30);
    }

    // Initialize counters when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Animate counters
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            animateCounter(counter, target);
        });

        // Initialize charts
        initializeCharts();
        
            // Update page header
    updatePageHeader();
    
    // Add navigation event listeners
    document.querySelectorAll('.nav-item').forEach(navItem => {
        navItem.addEventListener('click', function() {
            // Update header after navigation
            setTimeout(updatePageHeader, 100);
        });
    });
    
    // Update active navigation state
    updateActiveNavigation();
    
    // Add chart button event listeners
    document.querySelectorAll('.btn-chart').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-chart').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

// Update active navigation state based on current page
function updateActiveNavigation() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get('page') || 'dashboard';
    
    // Remove active class from all nav items
    document.querySelectorAll('.nav-item').forEach(navItem => {
        navItem.classList.remove('active');
    });
    
    // Add active class to current page nav item
    const currentNavItem = document.querySelector(`[href*="page=${currentPage}"]`);
    if (currentNavItem) {
        currentNavItem.classList.add('active');
    } else if (currentPage === 'dashboard') {
        // Dashboard is the default page
        const dashboardNav = document.querySelector('[href="index.php"]');
        if (dashboardNav) {
            dashboardNav.classList.add('active');
        }
    }
}

    // Initialize Charts
    function initializeCharts() {
        // Initialize reports charts if on reports page
        if (window.location.search.includes('page=reports')) {
            initializeReportsCharts();
        }
        
        // Initialize dashboard charts if on dashboard
        if (window.location.search.includes('page=dashboard') || window.location.search === '') {
            initializeDashboardCharts();
        }
    }
    
    // Initialize Dashboard Charts
    function initializeDashboardCharts() {
        // Risk Trend Chart (for dashboard)
        const trendCtx = document.getElementById('riskTrendChart');
        if (trendCtx) {
            // Get real data from API
            fetch('dashboard_data.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.trendData ? data.trendData.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }) : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    
                    const values = data.trendData ? data.trendData.map(item => parseInt(item.count)) : [0, 0, 0, 0, 0, 0, 0];
                    
                    window.dashboardTrendChart = new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Risk Assessments',
                                data: values,
                                borderColor: '#1e40af',
                                backgroundColor: 'rgba(30, 64, 175, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading dashboard data:', error);
                    // Fallback to empty chart
                    window.dashboardTrendChart = new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                            datasets: [{
                                label: 'Risk Assessments',
                                data: [0, 0, 0, 0, 0, 0, 0],
                                borderColor: '#1e40af',
                                backgroundColor: 'rgba(30, 64, 175, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                });
        }
    }
    }
    
    // Initialize Reports Charts
    function initializeReportsCharts() {
        // Monthly Trend Chart
        const monthlyTrendCtx = document.getElementById('monthlyTrendChart');
        if (monthlyTrendCtx) {
            window.trendChart = new Chart(monthlyTrendCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Risk Assessments',
                        data: [],
                        borderColor: '#1e40af',
                        backgroundColor: 'rgba(30, 64, 175, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Category Distribution Chart
        const distributionCtx = document.getElementById('categoryDistributionChart');
        if (distributionCtx) {
            window.distributionChart = new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['High Risk', 'Medium Risk', 'Low Risk'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: [
                            '#dc2626',
                            '#d97706',
                            '#059669'
                        ],
                        borderWidth: 0,
                        cutout: '70%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
        
        // Load initial data
        updateReportData(30);
    }

        // Risk Distribution Chart
        const distributionCtx = document.getElementById('riskDistributionChart');
        if (distributionCtx) {
            new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['High Risk', 'Medium Risk', 'Low Risk'],
                    datasets: [{
                        data: [30, 45, 25],
                        backgroundColor: [
                            '#DC3545',
                            '#FFC107',
                            '#28A745'
                        ],
                        borderWidth: 0,
                        cutout: '70%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    }

    // Chart period buttons
    document.addEventListener('DOMContentLoaded', function() {
        const chartButtons = document.querySelectorAll('.btn-chart');
        chartButtons.forEach(button => {
            button.addEventListener('click', function() {
                chartButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                // Here you would typically update the chart data
            });
        });

        // Initialize risk selectors with a delay to ensure form is loaded
        setTimeout(function() {
            initializeRiskSelectors();
        }, 100);
    });

    function initializeRiskSelectors() {
        console.log('Initializing risk selectors...');
        const riskSelectors = document.querySelectorAll('.risk-selector');
        console.log('Found risk selectors:', riskSelectors.length);
        
        if (riskSelectors.length === 0) {
            console.log('❌ No risk selectors found! Form might not be loaded yet.');
            // Try again after a longer delay
            setTimeout(function() {
                initializeRiskSelectors();
            }, 500);
            return;
        }
        
        riskSelectors.forEach(selector => {
            console.log('Adding event listener to:', selector.id);
            selector.addEventListener('change', function() {
                console.log('Risk selector changed:', this.id, this.value);
                updateRiskFields(this.id);
                calculateOverallRisk();
            });
        });
        
        console.log('✅ Risk selectors initialized successfully');
    }

    // Auto-populate fields based on Risk ID selection
    function updateRiskFields(selectorId) {
        console.log('Updating risk fields for:', selectorId);
        const select = document.getElementById(selectorId);
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const prefix = selectorId.replace('_risk_id', '');
            
            const description = selectedOption.getAttribute('data-description') || '';
            const impact = selectedOption.getAttribute('data-impact') || '';
            const likelihood = selectedOption.getAttribute('data-likelihood') || '';
            const rating = selectedOption.getAttribute('data-rating') || '';
            
            console.log('Setting values:', { description, impact, likelihood, rating });
            
            document.getElementById(prefix + '_description').value = description;
            document.getElementById(prefix + '_impact').value = impact;
            document.getElementById(prefix + '_likelihood').value = likelihood;
            document.getElementById(prefix + '_risk_rating').value = rating;
        } else {
            const prefix = selectorId.replace('_risk_id', '');
            
            document.getElementById(prefix + '_description').value = '';
            document.getElementById(prefix + '_impact').value = '';
            document.getElementById(prefix + '_likelihood').value = '';
            document.getElementById(prefix + '_risk_rating').value = '';
        }
    }

    function calculateOverallRisk() {
        console.log('Calculating overall risk...');
        const riskSelectors = ['screening_risk_id', 'client_category_risk_id', 'services_risk_id', 'payment_risk_id', 'delivery_risk_id'];
        let totalPoints = 0;
        
        riskSelectors.forEach(selector => {
            const select = document.getElementById(selector);
            if (select) {
                const selectedOption = select.options[select.selectedIndex];
                
                if (selectedOption && selectedOption.value) {
                    const points = parseInt(selectedOption.getAttribute('data-points')) || 0;
                    totalPoints += points;
                    console.log('Adding points for', selector, ':', points);
                }
            }
        });

        console.log('Total points:', totalPoints);
        document.getElementById('overall_risk_points').value = totalPoints;

        // Determine overall risk rating based on total points
        let rating = '';
        let acceptance = '';
        let monitoring = '';

        if (totalPoints >= 20) {
            rating = 'Very High-risk';
            acceptance = 'Do not accept client';
            monitoring = 'N/A';
        } else if (totalPoints >= 17) {
            rating = 'High-risk';
            acceptance = 'Accept client';
            monitoring = 'Quarterly review';
        } else if (totalPoints >= 15) {
            rating = 'Medium-risk';
            acceptance = 'Accept client';
            monitoring = 'Bi-Annually';
        } else if (totalPoints >= 10) {
            rating = 'Low-risk';
            acceptance = 'Accept client';
            monitoring = 'Annually';
        } else {
            rating = 'Low-risk';
            acceptance = 'Accept client';
            monitoring = 'Annually';
        }

        document.getElementById('overall_risk_rating').value = rating;
        document.getElementById('client_acceptance').value = acceptance;
        document.getElementById('ongoing_monitoring').value = monitoring;
    }



    // Export report functions
    function exportReport(type) {
        const period = document.getElementById('reportPeriod') ? document.getElementById('reportPeriod').value : 30;
        window.open(`export.php?type=${type}&period=${period}`, '_blank');
    }

    function printReport() {
        const period = document.getElementById('reportPeriod') ? document.getElementById('reportPeriod').value : 30;
        const printWindow = window.open(`export.php?type=pdf&period=${period}`, '_blank');
        printWindow.onload = function() {
            printWindow.print();
        };
    }

    // Initialize additional charts for reports page
    function initializeReportCharts() {
        // Show loading states
        document.getElementById('trendChartLoading').style.display = 'block';
        document.getElementById('distributionChartLoading').style.display = 'block';
        
        // Fetch real data from server
        fetch('dashboard_data.php?action=chart_data')
            .then(response => response.json())
            .then(data => {
                // Hide loading states
                document.getElementById('trendChartLoading').style.display = 'none';
                document.getElementById('distributionChartLoading').style.display = 'none';
                // Monthly Trend Chart
                const monthlyCtx = document.getElementById('monthlyTrendChart');
                if (monthlyCtx) {
                    new Chart(monthlyCtx, {
                        type: 'bar',
                        data: {
                            labels: data.monthly.labels,
                            datasets: [{
                                label: 'Assessments',
                                data: data.monthly.data,
                                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                                borderColor: '#667eea',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }

                // Category Distribution Chart
                const categoryCtx = document.getElementById('categoryDistributionChart');
                if (categoryCtx) {
                    new Chart(categoryCtx, {
                        type: 'pie',
                        data: {
                            labels: data.categories.labels,
                            datasets: [{
                                data: data.categories.data,
                                backgroundColor: [
                                    '#667eea',
                                    '#f093fb',
                                    '#4facfe',
                                    '#43e97b'
                                ],
                                borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
            // Hide loading states and show error message
            document.getElementById('trendChartLoading').style.display = 'none';
            document.getElementById('distributionChartLoading').style.display = 'none';
            
            // Show error message in charts
            const trendChart = document.getElementById('monthlyTrendChart');
            const distributionChart = document.getElementById('categoryDistributionChart');
            
            if (trendChart) {
                trendChart.style.display = 'none';
                document.getElementById('trendChartLoading').innerHTML = '<i class="fas fa-exclamation-triangle"></i><p>Failed to load data</p>';
                document.getElementById('trendChartLoading').style.display = 'block';
            }
            
            if (distributionChart) {
                distributionChart.style.display = 'none';
                document.getElementById('distributionChartLoading').innerHTML = '<i class="fas fa-exclamation-triangle"></i><p>Failed to load data</p>';
                document.getElementById('distributionChartLoading').style.display = 'block';
            }
        });
    }

    // Initialize report charts when reports page is loaded
    if (window.location.href.includes('page=reports')) {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initializeReportCharts, 100);
        });
    }

    // User Management Functions
    function editUser(id, name, email, role, status) {
        document.getElementById('edit_user_id').value = id;
        document.getElementById('edit_user_name').value = name;
        document.getElementById('edit_user_email').value = email;
        document.getElementById('edit_user_role').value = role;
        document.getElementById('edit_user_status').value = status;
        
        const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        editModal.show();
    }

    function deleteUser(id) {
        document.getElementById('delete_action').value = 'delete_user';
        document.getElementById('delete_user_id').value = id;
        document.getElementById('delete_assessment_id').value = '';
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Risk Assessment Functions
    function viewAssessment(id) {
        // Redirect to view page or show in modal
        window.location.href = `index.php?page=view_assessment&id=${id}`;
    }

    function editAssessment(id) {
        // Redirect to edit page
        window.location.href = `index.php?page=edit_assessment&id=${id}`;
    }

    function deleteAssessment(id) {
        document.getElementById('delete_action').value = 'delete_assessment';
        document.getElementById('delete_assessment_id').value = id;
        document.getElementById('delete_user_id').value = '';
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Search Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-box input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Notification Bell Click
        const notificationBell = document.querySelector('.notification-bell');
        if (notificationBell) {
            notificationBell.addEventListener('click', function() {
                // Show notifications dropdown or modal
                alert('Notifications:\n- New risk assessment submitted\n- High-risk client detected\n- Weekly report ready');
            });
        }

        // User Profile Dropdown
        const userProfile = document.querySelector('.user-profile');
        if (userProfile) {
            userProfile.addEventListener('click', function() {
                // Show user profile dropdown or modal
                // Get current user data dynamically
        fetch('dashboard_data.php?action=user_profile')
            .then(response => response.json())
            .then(data => {
                if (data.user) {
                    alert(`User Profile:\n- ${data.user.name}\n- ${data.user.email}\n- Role: ${data.user.role}`);
                } else {
                    alert('Unable to load user profile');
                }
            })
            .catch(error => {
                alert('Error loading user profile');
            });
            });
        }

        // Export Report Functionality
        const exportButtons = document.querySelectorAll('[onclick*="export"]');
        exportButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="export_report">
                    <input type="hidden" name="period" value="30">
                `;
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });
        });

        // Print Report Functionality
        const printButtons = document.querySelectorAll('[onclick*="print"]');
        printButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                window.print();
            });
        });

        // Auto-save functionality for forms
        const formInputs = document.querySelectorAll('form input, form select, form textarea');
        formInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Save form data to localStorage
                const form = this.closest('form');
                if (form) {
                    const formData = new FormData(form);
                    const formObject = {};
                    for (let [key, value] of formData.entries()) {
                        formObject[key] = value;
                    }
                    localStorage.setItem('form_autosave', JSON.stringify(formObject));
                }
            });
        });

        // Load auto-saved form data
        const savedFormData = localStorage.getItem('form_autosave');
        if (savedFormData && window.location.href.includes('page=create')) {
            const formData = JSON.parse(savedFormData);
            Object.keys(formData).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = formData[key];
                }
            });
        }
    });

    // Success message handling
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success')) {
        let message = '';
        switch(urlParams.get('success')) {
            case '1':
                message = 'Risk assessment created successfully!';
                break;
            case 'user_added':
                message = 'User added successfully!';
                break;
            case 'user_updated':
                message = 'User updated successfully!';
                break;
            case 'user_deleted':
                message = 'User deleted successfully!';
                break;
            case 'assessment_deleted':
                message = 'Risk assessment deleted successfully!';
                break;
        }
        if (message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.content-body').insertBefore(alertDiv, document.querySelector('.content-body').firstChild);
        }
    }
    </script>
    
    <style>
    :root {
        --primary-color: #1A3B5D;
        --secondary-color: #2C5AA0;
        --accent-color: #4A90E2;
        --success-color: #28A745;
        --warning-color: #FFC107;
        --danger-color: #DC3545;
        --light-gray: #F8F9FA;
        --border-color: #E9ECEF;
        --text-dark: #212529;
        --text-muted: #6C757D;
        
        /* Modern Gradients */
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --gradient-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --gradient-dark: linear-gradient(135deg, #1A3B5D 0%, #2C5AA0 100%);
        
        /* Shadows */
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.15);
        --shadow-lg: 0 8px 25px rgba(0,0,0,0.2);
        --shadow-xl: 0 20px 40px rgba(0,0,0,0.25);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-gray);
        color: var(--text-dark);
    }

    .d-flex {
        display: flex;
    }

    /* Sidebar Styles */
    .sidebar {
        width: 280px;
        background: var(--gradient-dark);
        min-height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        box-shadow: var(--shadow-xl);
    }

    .sidebar-header {
        padding: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        background: white;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        padding: 5px;
        box-sizing: border-box;
    }

    .logo-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dcs-logo {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        transition: transform 0.3s ease;
    }

    .logo-wrapper:hover .dcs-logo {
        transform: scale(1.1);
    }

    .logo-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
        50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.1); }
        100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
    }

    .logo-text-fallback {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-align: center;
    }
    
    .dcs-text-logo {
        display: flex;
        gap: 2px;
        align-items: center;
        justify-content: center;
    }
    
    .dcs-letter {
        width: 30px;
        height: 30px;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .logo-text {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .brand-name {
        color: var(--primary-color);
        font-size: 24px;
        font-weight: 700;
        letter-spacing: 2px;
    }

    .brand-subtitle {
        color: var(--text-muted);
        font-size: 10px;
        font-weight: 400;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sidebar-nav {
        padding: 1rem 0;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .nav-item:hover {
        background: rgba(255,255,255,0.1);
        color: white;
        border-left-color: var(--accent-color);
    }

    .nav-item.active {
        background: rgba(255,255,255,0.15);
        color: white;
        border-left-color: var(--accent-color);
    }

    .nav-item i {
        width: 20px;
        text-align: center;
    }

    .nav-divider {
        height: 1px;
        background: rgba(255,255,255,0.1);
        margin: 1rem 1.5rem;
    }

    /* Main Content Styles */
    .main-content {
        flex: 1;
        margin-left: 280px;
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .content-header {
        background: white;
        padding: 2rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }

    .content-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    .header-left h1 {
        color: var(--primary-color);
        font-weight: 700;
        margin: 0;
        font-size: 2rem;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-subtitle {
        color: var(--text-muted);
        margin: 0.5rem 0 0 0;
        font-size: 1rem;
    }

    .header-right {
        display: flex;
        align-items: center;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        color: var(--text-muted);
        z-index: 1;
    }

    .search-box input {
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 2px solid var(--border-color);
        border-radius: 25px;
        background: var(--light-gray);
        width: 250px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--accent-color);
        background: white;
        box-shadow: var(--shadow-md);
    }
    
    .search-box {
        position: relative;
    }
    
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        max-height: 400px;
        overflow-y: auto;
        margin-top: 5px;
    }
    
    .search-section {
        padding: 10px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .search-section:last-child {
        border-bottom: none;
    }
    
    .search-section h6 {
        margin: 0 0 8px 0;
        color: var(--text-muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .search-result-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.2s ease;
    }
    
    .search-result-item:hover {
        background-color: var(--light-gray);
    }
    
    .search-result-item i {
        color: var(--accent-color);
        width: 16px;
    }
    
    .search-result-content {
        flex: 1;
    }
    
    .search-result-title {
        font-weight: 500;
        font-size: 14px;
        color: var(--text-dark);
    }
    
    .search-result-subtitle {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .notification-bell {
        position: relative;
        width: 45px;
        height: 45px;
        background: var(--light-gray);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .notification-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 350px;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        margin-top: 10px;
        display: none;
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .notification-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .btn-link {
        background: none;
        border: none;
        color: var(--accent-color);
        font-size: 12px;
        cursor: pointer;
        text-decoration: underline;
    }
    
    .notification-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
        transition: background-color 0.2s ease;
    }
    
    .notification-item:last-child {
        border-bottom: none;
    }
    
    .notification-item:hover {
        background-color: var(--light-gray);
    }
    
    .notification-item.unread {
        background-color: rgba(102, 126, 234, 0.05);
    }
    
    .notification-item.empty {
        text-align: center;
        color: var(--text-muted);
        font-style: italic;
    }
    
    .notification-icon {
        width: 32px;
        height: 32px;
        background: var(--accent-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        flex-shrink: 0;
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 500;
        font-size: 14px;
        color: var(--text-dark);
        margin-bottom: 4px;
    }
    
    .notification-message {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.4;
        margin-bottom: 4px;
    }
    
    .notification-time {
        font-size: 11px;
        color: var(--text-muted);
    }
    
    .user-profile {
        position: relative;
        cursor: pointer;
    }
    
    .user-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 280px;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        margin-top: 10px;
        display: none;
    }
    
    .user-dropdown-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .avatar-large {
        width: 48px;
        height: 48px;
        border-radius: 50%;
    }
    
    .user-info {
        flex: 1;
    }
    
    .user-name {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-dark);
        margin-bottom: 2px;
    }
    
    .user-email {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 2px;
    }
    
    .user-role {
        font-size: 11px;
        color: var(--accent-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .user-dropdown-menu {
        padding: 8px 0;
    }
    
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        color: var(--text-dark);
        text-decoration: none;
        transition: background-color 0.2s ease;
        font-size: 14px;
    }
    
    .dropdown-item:hover {
        background-color: var(--light-gray);
        color: var(--text-dark);
    }
    
    .dropdown-item i {
        width: 16px;
        text-align: center;
    }
    
    .dropdown-divider {
        height: 1px;
        background: var(--border-color);
        margin: 8px 0;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        padding: 1rem 0;
    }
    
    .quick-action-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 1rem;
        background: var(--light-gray);
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-dark);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .quick-action-item:hover {
        background: white;
        border-color: var(--accent-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: var(--text-dark);
    }
    
    .quick-action-icon {
        width: 40px;
        height: 40px;
        background: var(--accent-color);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
    }
    
    .quick-action-content {
        flex: 1;
    }
    
    .quick-action-title {
        display: block;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 2px;
    }
    
    .quick-action-subtitle {
        display: block;
        font-size: 12px;
        color: var(--text-muted);
    }
    
    /* Professional enhancements */
    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .card, .report-card, .dashboard-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .card:hover, .report-card:hover, .dashboard-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .table thead th {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        color: white;
        border: none;
        font-weight: 600;
    }
    
    .badge {
        border-radius: 20px;
        font-weight: 500;
        padding: 0.5em 0.8em;
    }
    
    /* Loading states */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
    
    .spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
        
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .content-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        
        .header-actions {
            width: 100%;
            justify-content: space-between;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .charts-section {
            grid-template-columns: 1fr;
            margin-bottom: 1rem;
        }
        
        .chart-card {
                    padding: 1.5rem;
    }
    
    .chart-container {
        position: relative;
        min-height: 200px;
    }
    
    .chart-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #6c757d;
        z-index: 10;
    }
    
    .chart-loading i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #dee2e6;
    }
    
    .chart-loading p {
        margin: 0;
        font-size: 0.875rem;
    }
        
        .risk-rating-info {
            margin-top: 1rem;
            padding: 0.75rem;
        }
    }
    
    /* Enhanced animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .dashboard-card, .stat-card, .chart-card {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .dashboard-card:nth-child(1) { animation-delay: 0.1s; }
    .dashboard-card:nth-child(2) { animation-delay: 0.2s; }
    .dashboard-card:nth-child(3) { animation-delay: 0.3s; }
    .dashboard-card:nth-child(4) { animation-delay: 0.4s; }
    
    /* Enhanced hover effects */
    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .chart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .notification-bell:hover {
        background: var(--accent-color);
        color: white;
        transform: translateY(-2px);
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--danger-color);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
        background: var(--light-gray);
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .user-profile:hover {
        background: var(--accent-color);
        color: white;
        transform: translateY(-2px);
    }

    .avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .content-body {
        padding: 2rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .gradient-primary::before { background: var(--gradient-primary); }
    .gradient-success::before { background: var(--gradient-success); }
    .gradient-warning::before { background: var(--gradient-warning); }
    .gradient-info::before { background: var(--gradient-info); }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        position: relative;
        overflow: hidden;
    }

    .icon-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.2);
        border-radius: 16px;
    }

    .gradient-primary .stat-icon { background: var(--gradient-primary); }
    .gradient-success .stat-icon { background: var(--gradient-success); }
    .gradient-warning .stat-icon { background: var(--gradient-warning); }
    .gradient-info .stat-icon { background: var(--gradient-info); }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        display: flex;
        align-items: baseline;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .counter {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1;
    }

    .stat-trend {
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .stat-trend.positive {
        background: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .stat-trend.negative {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
    }

    .stat-content p {
        color: var(--text-muted);
        margin: 0 0 1rem 0;
        font-size: 14px;
        font-weight: 500;
    }

    .stat-progress {
        width: 100%;
        height: 6px;
        background: var(--light-gray);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: var(--gradient-primary);
        border-radius: 3px;
        transition: width 1s ease;
    }

    /* Charts Section */
    .charts-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .chart-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .chart-header h4 {
        color: var(--primary-color);
        font-weight: 700;
        margin: 0;
    }

    .chart-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-chart {
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-color);
        background: white;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-chart.active,
    .btn-chart:hover {
        background: var(--gradient-primary);
        border-color: transparent;
        color: white;
    }

    .chart-legend {
        display: flex;
        gap: 1rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .legend-color.high-risk { background: var(--danger-color); }
    .legend-color.medium-risk { background: var(--warning-color); }
    .legend-color.low-risk { background: var(--success-color); }

    .chart-container {
        height: 300px;
        position: relative;
        margin-bottom: 1rem;
    }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .dashboard-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    .card-header-modern {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .card-header-modern h4 {
        color: var(--primary-color);
        font-weight: 700;
        margin: 0;
    }

    .view-all {
        color: var(--accent-color);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .view-all:hover {
        color: var(--primary-color);
        transform: translateX(2px);
    }

    .card-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 35px;
        height: 35px;
        border: none;
        background: var(--light-gray);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-icon:hover {
        background: var(--accent-color);
        color: white;
        transform: scale(1.1);
    }

    .activity-list {
        margin-top: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        background: var(--light-gray);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
    }

    .activity-content {
        flex: 1;
    }

    .activity-content strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    .activity-content small {
        color: var(--text-muted);
        font-size: 12px;
    }

    .category-list {
        margin-top: 1rem;
    }

    .category-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .category-item:hover {
        background: var(--light-gray);
        margin: 0 -2rem;
        padding: 1.5rem 2rem;
        border-radius: 12px;
    }

    .category-item:last-child {
        border-bottom: none;
    }

    .category-info {
        flex: 1;
        margin-right: 1rem;
    }

    .category-name {
        display: block;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .category-bar {
        width: 100%;
        height: 8px;
        background: var(--light-gray);
        border-radius: 4px;
        overflow: hidden;
    }

    .bar-fill {
        height: 100%;
        background: var(--gradient-primary);
        border-radius: 4px;
        transition: width 1s ease;
    }

    .category-stats {
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Form Styles */
    .form-section {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .section-title {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-gray);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }

    .form-control[readonly] {
        background-color: var(--light-gray);
        color: var(--text-muted);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 2rem;
        background: var(--light-gray);
        border-radius: 16px;
        margin-top: 2rem;
    }

    /* Settings Management */
.settings-section {
    background: #fff;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.section-title {
    color: #1A3B5D;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.setting-item {
    padding: 15px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: #f8f9fa;
}

.setting-item .form-label {
    font-weight: 600;
    color: #1A3B5D;
    margin-bottom: 5px;
}

.setting-description {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 10px;
    line-height: 1.4;
}

.form-actions {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
}

.form-actions .btn {
    margin: 0 10px;
}

/* Reports Grid */
.reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
    }

    .report-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .report-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .report-header h4 {
        color: var(--primary-color);
        font-weight: 700;
        margin: 0;
    }

    .summary-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--light-gray);
        border-radius: 12px;
    }

    .summary-label {
        font-weight: 500;
        color: var(--text-muted);
    }

    .summary-value {
        font-weight: 700;
        font-size: 1.25rem;
    }

    .risk-factors-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .risk-factor {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .risk-factor:last-child {
        border-bottom: none;
    }

    .factor-info {
        flex: 1;
        margin-right: 1rem;
    }

    .factor-name {
        display: block;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .factor-count {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    /* Settings Grid */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .settings-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .settings-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    .settings-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .settings-header h4 {
        color: var(--primary-color);
        font-weight: 700;
        margin: 0;
    }

    .settings-header i {
        color: var(--accent-color);
        font-size: 1.5rem;
    }

    .setting-item {
        margin-bottom: 1.5rem;
    }

    .setting-label {
        display: block;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .form-check-input:checked {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    /* Assessment Details Styles */
    .assessment-details {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    .detail-section {
        padding: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .detail-section:last-child {
        border-bottom: none;
    }

    .detail-section h4 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-gray);
    }

    .risk-summary-card {
        background: var(--light-gray);
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        height: 100%;
    }

    .risk-summary-card h5 {
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .risk-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .risk-value.very-high-risk {
        color: var(--danger-color);
    }

    .risk-value.high-risk {
        color: var(--warning-color);
    }

    .risk-value.medium-risk {
        color: #fd7e14;
    }

    .risk-value.low-risk {
        color: var(--success-color);
    }

    .risk-categories {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .risk-category {
        background: var(--light-gray);
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid var(--accent-color);
    }

    .risk-category h6 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .risk-category p {
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .risk-category strong {
        color: var(--text-dark);
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-header h2 {
        color: var(--primary-color);
        margin: 0;
    }
    
    .page-header-simple {
        background: #fff;
        padding: 20px 30px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-left: 4px solid var(--accent-color);
    }
    
    .page-header-simple h2 {
        margin: 0;
        color: #1A3B5D;
        font-size: 1.5rem;
        font-weight: 600;
    }

    /* Content Card */
    .content-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 2rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--border-color);
    }

    .empty-state h4 {
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background: var(--secondary-color);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: var(--text-muted);
        color: white;
    }

    /* Table Styles */
    .table {
        margin: 0;
    }

    .table th {
        background: var(--light-gray);
        border: none;
        font-weight: 600;
        color: var(--text-dark);
    }

    .table td {
        border: none;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .main-content {
            margin-left: 0;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Login Page Styles */
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .login-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(245, 158, 11, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.2);
        padding: 3rem;
        width: 100%;
        max-width: 450px;
        text-align: center;
        position: relative;
        z-index: 1;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .login-header {
        margin-bottom: 2.5rem;
    }

    .login-logo {
        width: 140px;
        height: auto;
        margin-bottom: 2rem;
        filter: drop-shadow(0 8px 16px rgba(0,0,0,0.1));
        transition: transform 0.3s ease;
    }

    .login-logo:hover {
        transform: scale(1.05);
    }

    .login-header h2 {
        color: #1e293b;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .login-header p {
        color: #64748b;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.6;
        margin-bottom: 2.5rem;
    }

    .form-group {
        margin-bottom: 2rem;
        text-align: left;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #374151;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .input-group {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 1rem;
        transition: color 0.2s ease;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: #ffffff;
        font-weight: 400;
        color: #1f2937;
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: #ffffff;
    }

    .form-control:focus + .input-icon {
        color: #3b82f6;
    }

    .login-form {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-login {
        width: 100%;
        background: #3b82f6;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: relative;
        overflow: hidden;
    }

    .btn-login:hover {
        background: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-login:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }



    .alert {
        border-radius: 16px;
        margin-bottom: 2rem;
        padding: 1.25rem;
        font-size: 0.95rem;
        font-weight: 500;
        border: none;
        backdrop-filter: blur(10px);
    }

    .alert-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-left: 4px solid #dc2626;
    }

    .alert-success {
        background: #f0fdf4;
        color: #059669;
        border: 1px solid #bbf7d0;
        border-left: 4px solid #059669;
    }

    @media (max-width: 768px) {
        .login-card {
            padding: 2.5rem 2rem;
            margin: 1rem;
            border-radius: 20px;
        }
        
        .login-logo {
            width: 120px;
        }
        
        .login-header h2 {
            font-size: 1.8rem;
        }

        .form-control {
            padding: 16px 18px 16px 48px;
        }

        .btn-login {
            padding: 16px;
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .login-container {
            padding: 1rem;
        }
        
        .login-card {
            padding: 2rem 1.5rem;
        }
        
        .login-header h2 {
            font-size: 1.6rem;
        }
    }

    /* Risk Register Styles */
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-danger {
        background-color: #f8d7da !important;
    }

    .table-warning {
        background-color: #fff3cd !important;
    }

    .table-success {
        background-color: #d1edff !important;
    }

    .table-info {
        background-color: #d1ecf1 !important;
    }

    .text-justify {
        text-align: justify;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.25em 0.6em;
        border-radius: 0.375rem;
    }

    .badge-primary {
        background-color: #007bff;
        color: white;
    }

    .badge-info {
        background-color: #17a2b8;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-danger {
        background-color: #dc3545;
        color: white;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }

    .card.border-primary {
        border-color: #007bff !important;
    }

    .card.border-warning {
        border-color: #ffc107 !important;
    }

    .card.border-info {
        border-color: #17a2b8 !important;
    }

    .card.border-success {
        border-color: #28a745 !important;
    }

    .card.border-secondary {
        border-color: #6c757d !important;
    }

    .bg-primary {
        background-color: #007bff !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }

    .text-primary {
        color: #007bff !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-info {
        color: #17a2b8 !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-white {
        color: white !important;
    }

    .text-dark {
        color: #212529 !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .mt-3 {
        margin-top: 1rem !important;
    }

    .small {
        font-size: 0.875em;
    }

    .lead {
        font-size: 1.25rem;
        font-weight: 300;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    .alert-info h6 {
        color: #0c5460;
        font-weight: 600;
    }
    </style>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="add_user">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="user_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="user_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="user_password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="user_password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="user_role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="user_role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="user">User</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="update_user">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_user_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_user_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_user_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="edit_user_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_user_password" class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="edit_user_password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="edit_user_role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_user_role" name="role" required>
                                <option value="user">User</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_user_status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_user_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteForm" style="display: inline;">
                        <input type="hidden" name="action" id="delete_action">
                        <input type="hidden" name="user_id" id="delete_user_id">
                        <input type="hidden" name="assessment_id" id="delete_assessment_id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    </div>
</body>
</html> 