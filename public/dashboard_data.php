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
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'chart_data') {
    // Get monthly trend data for the last 6 months
    $monthlyData = [];
    $monthlyLabels = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $date = date('Y-m', strtotime("-$i months"));
        $monthName = date('M', strtotime("-$i months"));
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM risk_assessments WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
        $stmt->execute([$date]);
        $count = $stmt->fetchColumn();
        
        $monthlyLabels[] = $monthName;
        $monthlyData[] = (int)$count;
    }
    
    // Get category distribution data
    $categoryLabels = ['Client Screening', 'Payment Methods', 'Service Delivery', 'Client Category'];
    $categoryData = [];
    
    // Count by risk categories
    $stmt = $pdo->query("SELECT 
        COUNT(CASE WHEN screening_risk_rating IN ('High', 'Very High') THEN 1 END) as screening_high,
        COUNT(CASE WHEN payment_risk_rating IN ('High', 'Very High') THEN 1 END) as payment_high,
        COUNT(CASE WHEN services_risk_rating IN ('High', 'Very High') THEN 1 END) as services_high,
        COUNT(CASE WHEN client_category_risk_rating IN ('High', 'Very High') THEN 1 END) as category_high
        FROM risk_assessments");
    $categoryCounts = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $categoryData = [
        (int)$categoryCounts['screening_high'],
        (int)$categoryCounts['payment_high'],
        (int)$categoryCounts['services_high'],
        (int)$categoryCounts['category_high']
    ];
    
    // Get risk level distribution
    $riskLevels = [];
    $stmt = $pdo->query("SELECT overall_risk_rating, COUNT(*) as count FROM risk_assessments GROUP BY overall_risk_rating");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $riskLevels[$row['overall_risk_rating']] = (int)$row['count'];
    }
    
    $response = [
        'monthly' => [
            'labels' => $monthlyLabels,
            'data' => $monthlyData
        ],
        'categories' => [
            'labels' => $categoryLabels,
            'data' => $categoryData
        ],
        'risk_levels' => $riskLevels
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
} elseif ($action === 'user_profile') {
    // Get current user profile
    $stmt = $pdo->prepare("SELECT id, name, email, role, status FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $response = [
        'user' => $user
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
}
?> 