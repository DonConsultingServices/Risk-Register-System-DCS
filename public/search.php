<?php
session_start();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    
    if (strlen($searchTerm) < 2) {
        echo '<div class="search-result-item">Please enter at least 2 characters</div>';
        exit;
    }
    
    $searchTerm = '%' . $searchTerm . '%';
    
    // Search risk assessments
    $stmt = $pdo->prepare("SELECT * FROM risk_assessments WHERE client_name LIKE ? OR screening_description LIKE ? OR client_category_description LIKE ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $assessments = $stmt->fetchAll();
    
    // Search users
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC LIMIT 3");
    $stmt->execute([$searchTerm, $searchTerm]);
    $users = $stmt->fetchAll();
    
    $html = '';
    
    if (!empty($assessments)) {
        $html .= '<div class="search-section"><h6>Risk Assessments</h6>';
        foreach ($assessments as $assessment) {
            $html .= '<div class="search-result-item" onclick="window.location.href=\'index.php?page=view_assessment&id=' . $assessment['id'] . '\'">';
            $html .= '<i class="fas fa-shield-alt"></i>';
            $html .= '<div class="search-result-content">';
            $html .= '<div class="search-result-title">' . htmlspecialchars($assessment['client_name']) . '</div>';
            $html .= '<div class="search-result-subtitle">' . htmlspecialchars($assessment['screening_description']) . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    
    if (!empty($users)) {
        $html .= '<div class="search-section"><h6>Users</h6>';
        foreach ($users as $user) {
            $html .= '<div class="search-result-item" onclick="window.location.href=\'index.php?page=users\'">';
            $html .= '<i class="fas fa-user"></i>';
            $html .= '<div class="search-result-content">';
            $html .= '<div class="search-result-title">' . htmlspecialchars($user['name']) . '</div>';
            $html .= '<div class="search-result-subtitle">' . htmlspecialchars($user['email']) . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    
    if (empty($assessments) && empty($users)) {
        $html = '<div class="search-result-item">No results found</div>';
    }
    
    echo $html;
}
?> 