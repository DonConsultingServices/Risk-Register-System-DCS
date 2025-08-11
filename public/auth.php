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

// Role-based permissions
$permissions = [
    'admin' => [
        'dashboard' => true,
        'users' => true,
        'risks' => true,
        'reports' => true,
        'settings' => true,
        'create_user' => true,
        'edit_user' => true,
        'delete_user' => true,
        'create_risk' => true,
        'edit_risk' => true,
        'delete_risk' => true,
        'view_all_reports' => true,
        'export_data' => true,
        'manage_settings' => true
    ],
    'manager' => [
        'dashboard' => true,
        'users' => false,
        'risks' => true,
        'reports' => true,
        'settings' => false,
        'create_user' => false,
        'edit_user' => false,
        'delete_user' => false,
        'create_risk' => true,
        'edit_risk' => true,
        'delete_risk' => false,
        'view_all_reports' => true,
        'export_data' => true,
        'manage_settings' => false
    ],
    'user' => [
        'dashboard' => true,
        'users' => false,
        'risks' => true,
        'reports' => false,
        'settings' => false,
        'create_user' => false,
        'edit_user' => false,
        'delete_user' => false,
        'create_risk' => true,
        'edit_risk' => false,
        'delete_risk' => false,
        'view_all_reports' => false,
        'export_data' => false,
        'manage_settings' => false
    ]
];

// Function to check if user has permission
function hasPermission($permission) {
    global $permissions;
    
    if (!isset($_SESSION['user_role']) || !isset($permissions[$_SESSION['user_role']])) {
        return false;
    }
    
    return $permissions[$_SESSION['user_role']][$permission] ?? false;
}

// Function to require permission
function requirePermission($permission) {
    if (!hasPermission($permission)) {
        $_SESSION['error'] = 'Access denied. You do not have permission to perform this action.';
        header('Location: index.php?page=dashboard');
        exit;
    }
}

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
        
        // Log login activity
        $stmt = $pdo->prepare("INSERT INTO user_activity (user_id, action, details) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], 'login', 'User logged in successfully']);
        
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
    // Log logout activity
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("INSERT INTO user_activity (user_id, action, details) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], 'logout', 'User logged out']);
    }
    
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Require authentication
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
}

// Get current user info
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['email'],
        'name' => $_SESSION['user_name'],
        'role' => $_SESSION['user_role']
    ];
}

// Log user activity
function logActivity($action, $details = '') {
    global $pdo;
    
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("INSERT INTO user_activity (user_id, action, details) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $action, $details]);
    }
}
?> 