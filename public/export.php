<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php?page=login');
    exit;
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

$type = $_GET['type'] ?? '';
$period = $_GET['period'] ?? 30;

// Get risk assessments data
$stmt = $pdo->prepare("SELECT * FROM risk_assessments WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) ORDER BY created_at DESC");
$stmt->execute([$period]);
$assessments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get summary statistics
$totalAssessments = count($assessments);
$highRiskCount = 0;
$mediumRiskCount = 0;
$lowRiskCount = 0;

foreach ($assessments as $assessment) {
    switch ($assessment['overall_risk_rating']) {
        case 'Very High-risk':
        case 'High-risk':
            $highRiskCount++;
            break;
        case 'Medium-risk':
            $mediumRiskCount++;
            break;
        case 'Low-risk':
            $lowRiskCount++;
            break;
    }
}

if ($type === 'pdf') {
    // Generate PDF Report
    require_once('vendor/autoload.php'); // Make sure you have TCPDF or similar library
    
    // For now, we'll create a simple HTML report that can be converted to PDF
    header('Content-Type: text/html');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>DCS Risk Assessment Report</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
            .summary { margin-bottom: 30px; }
            .summary table { width: 100%; border-collapse: collapse; }
            .summary th, .summary td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            .summary th { background-color: #f2f2f2; }
            .assessments { margin-bottom: 30px; }
            .assessments table { width: 100%; border-collapse: collapse; font-size: 12px; }
            .assessments th, .assessments td { border: 1px solid #ddd; padding: 6px; text-align: left; }
            .assessments th { background-color: #f2f2f2; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>DCS Risk Assessment Report</h1>
            <p>Generated on <?= date('F j, Y') ?> | Period: Last <?= $period ?> days</p>
        </div>
        
        <div class="summary">
            <h2>Summary Statistics</h2>
            <table>
                <tr>
                    <th>Metric</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
                <tr>
                    <td>Total Assessments</td>
                    <td><?= $totalAssessments ?></td>
                    <td>100%</td>
                </tr>
                <tr>
                    <td>High Risk</td>
                    <td><?= $highRiskCount ?></td>
                    <td><?= $totalAssessments > 0 ? round(($highRiskCount / $totalAssessments) * 100, 1) : 0 ?>%</td>
                </tr>
                <tr>
                    <td>Medium Risk</td>
                    <td><?= $mediumRiskCount ?></td>
                    <td><?= $totalAssessments > 0 ? round(($mediumRiskCount / $totalAssessments) * 100, 1) : 0 ?>%</td>
                </tr>
                <tr>
                    <td>Low Risk</td>
                    <td><?= $lowRiskCount ?></td>
                    <td><?= $totalAssessments > 0 ? round(($lowRiskCount / $totalAssessments) * 100, 1) : 0 ?>%</td>
                </tr>
            </table>
        </div>
        
        <div class="assessments">
            <h2>Risk Assessments Details</h2>
            <table>
                <tr>
                    <th>Client Name</th>
                    <th>Risk Rating</th>
                    <th>Client Acceptance</th>
                    <th>Monitoring</th>
                    <th>Created Date</th>
                </tr>
                <?php foreach ($assessments as $assessment): ?>
                <tr>
                    <td><?= htmlspecialchars($assessment['client_name']) ?></td>
                    <td><?= htmlspecialchars($assessment['overall_risk_rating']) ?></td>
                    <td><?= htmlspecialchars($assessment['client_acceptance']) ?></td>
                    <td><?= htmlspecialchars($assessment['ongoing_monitoring']) ?></td>
                    <td><?= date('M j, Y', strtotime($assessment['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <div class="footer">
            <p>This report was generated by the DCS Risk Assessment System</p>
        </div>
    </body>
    </html>
    <?php
    
} elseif ($type === 'excel') {
    // Generate Excel/CSV Report
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="dcs_risk_report_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add headers
    fputcsv($output, ['DCS Risk Assessment Report']);
    fputcsv($output, ['Generated on', date('F j, Y')]);
    fputcsv($output, ['Period', 'Last ' . $period . ' days']);
    fputcsv($output, []);
    
    // Summary section
    fputcsv($output, ['Summary Statistics']);
    fputcsv($output, ['Metric', 'Count', 'Percentage']);
    fputcsv($output, ['Total Assessments', $totalAssessments, '100%']);
    fputcsv($output, ['High Risk', $highRiskCount, $totalAssessments > 0 ? round(($highRiskCount / $totalAssessments) * 100, 1) . '%' : '0%']);
    fputcsv($output, ['Medium Risk', $mediumRiskCount, $totalAssessments > 0 ? round(($mediumRiskCount / $totalAssessments) * 100, 1) . '%' : '0%']);
    fputcsv($output, ['Low Risk', $lowRiskCount, $totalAssessments > 0 ? round(($lowRiskCount / $totalAssessments) * 100, 1) . '%' : '0%']);
    fputcsv($output, []);
    
    // Detailed data
    fputcsv($output, ['Risk Assessments Details']);
    fputcsv($output, ['Client Name', 'Risk Rating', 'Client Acceptance', 'Ongoing Monitoring', 'Created Date', 'DCS Comments']);
    
    foreach ($assessments as $assessment) {
        fputcsv($output, [
            $assessment['client_name'],
            $assessment['overall_risk_rating'],
            $assessment['client_acceptance'],
            $assessment['ongoing_monitoring'],
            date('Y-m-d', strtotime($assessment['created_at'])),
            $assessment['dcs_comments']
        ]);
    }
    
    fclose($output);
    
} else {
    // Invalid export type
    header('Location: index.php?page=reports&error=invalid_export');
    exit;
}
?> 