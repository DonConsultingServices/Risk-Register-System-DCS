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

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    exit;
}

$period = $_GET['period'] ?? '30';
$chartPeriod = $_GET['chartPeriod'] ?? 'monthly';

// Get data for the specified period
$dateFilter = "WHERE created_at >= DATE_SUB(NOW(), INTERVAL $period DAY)";

// Summary statistics
$totalAssessments = $pdo->query("SELECT COUNT(*) FROM risk_assessments $dateFilter")->fetchColumn();
$highRiskCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments $dateFilter AND overall_risk_rating IN ('Very High-risk', 'High-risk')")->fetchColumn();
$mediumRiskCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments $dateFilter AND overall_risk_rating = 'Medium-risk'")->fetchColumn();
$lowRiskCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments $dateFilter AND overall_risk_rating = 'Low-risk'")->fetchColumn();

// Trend data
$trendData = [];
if ($chartPeriod === 'monthly') {
    $stmt = $pdo->query("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
        FROM risk_assessments 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month
    ");
    $trendData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query("
        SELECT CONCAT(YEAR(created_at), ' Q', QUARTER(created_at)) as quarter, COUNT(*) as count 
        FROM risk_assessments 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 4 QUARTER)
        GROUP BY YEAR(created_at), QUARTER(created_at)
        ORDER BY quarter
    ");
    $trendData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Distribution data
$distributionData = [
    'labels' => ['High Risk', 'Medium Risk', 'Low Risk'],
    'values' => [$highRiskCount, $mediumRiskCount, $lowRiskCount]
];

// Risk factors data
$riskFactors = [];
$total = $totalAssessments ?: 1; // Avoid division by zero

$screeningCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments $dateFilter AND screening_risk_id IS NOT NULL")->fetchColumn();
$paymentCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments $dateFilter AND payment_risk_id IS NOT NULL")->fetchColumn();
$deliveryCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments $dateFilter AND delivery_risk_id IS NOT NULL")->fetchColumn();
$categoryCount = $pdo->query("SELECT COUNT(*) FROM risk_assessments $dateFilter AND client_category_risk_id IS NOT NULL")->fetchColumn();

$riskFactors = [
    [
        'name' => 'Client Screening',
        'count' => $screeningCount,
        'percentage' => round(($screeningCount / $total) * 100)
    ],
    [
        'name' => 'Payment Methods',
        'count' => $paymentCount,
        'percentage' => round(($paymentCount / $total) * 100)
    ],
    [
        'name' => 'Service Delivery',
        'count' => $deliveryCount,
        'percentage' => round(($deliveryCount / $total) * 100)
    ],
    [
        'name' => 'Client Category',
        'count' => $categoryCount,
        'percentage' => round(($categoryCount / $total) * 100)
    ]
];

// Prepare trend data for charts
$trendLabels = [];
$trendValues = [];
foreach ($trendData as $item) {
    $trendLabels[] = $chartPeriod === 'monthly' ? date('M Y', strtotime($item['month'] . '-01')) : $item['quarter'];
    $trendValues[] = (int)$item['count'];
}

$response = [
    'totalAssessments' => $totalAssessments,
    'highRiskCount' => $highRiskCount,
    'mediumRiskCount' => $mediumRiskCount,
    'lowRiskCount' => $lowRiskCount,
    'trendData' => [
        'labels' => $trendLabels,
        'values' => $trendValues
    ],
    'distributionData' => $distributionData,
    'riskFactors' => $riskFactors
];

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?> 