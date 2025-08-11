<?php
// Simple test to verify client risk assessment functionality

// Simulate the risk points calculation
function calculateTotalRiskPoints($data) {
    $riskPoints = [
        'R001' => 2,
        'R002' => 1,
        'R003' => 3,
        'R004' => 2,
        'R005' => 1,
        'R006' => 5,
        'R007' => 4,
        'R008' => 5,
        'R009' => 4,
        'R010' => 5
    ];

    $totalPoints = 0;
    $riskFields = [
        'client_screening_risk_id',
        'client_category_risk_id', 
        'requested_services_risk_id',
        'payment_option_risk_id',
        'delivery_method_risk_id'
    ];

    foreach ($riskFields as $field) {
        if (!empty($data[$field]) && isset($riskPoints[$data[$field]])) {
            $totalPoints += $riskPoints[$data[$field]];
        }
    }

    return $totalPoints;
}

function calculateOverallRiskRating($totalPoints) {
    if ($totalPoints >= 11) {
        return 'High';
    } elseif ($totalPoints >= 6) {
        return 'Medium';
    } else {
        return 'Low';
    }
}

function determineClientAcceptance($totalPoints) {
    if ($totalPoints >= 11) {
        return 'Reject client';
    } elseif ($totalPoints >= 6) {
        return 'Accept with conditions';
    } else {
        return 'Accept client';
    }
}

function determineMonitoringFrequency($totalPoints) {
    if ($totalPoints >= 11) {
        return 'Not applicable';
    } elseif ($totalPoints >= 6) {
        return 'Enhanced monitoring';
    } else {
        return 'Standard monitoring';
    }
}

// Test data
$testData = [
    'client_name' => 'Test Client',
    'client_screening_risk_id' => 'R001',
    'client_category_risk_id' => 'R002',
    'requested_services_risk_id' => 'R003',
    'payment_option_risk_id' => 'R004',
    'delivery_method_risk_id' => 'R005'
];

echo "=== Client Risk Assessment Test ===\n\n";

echo "Test Data:\n";
foreach ($testData as $key => $value) {
    echo "- $key: $value\n";
}

echo "\nCalculations:\n";

$totalPoints = calculateTotalRiskPoints($testData);
echo "- Total Risk Points: $totalPoints\n";

$overallRating = calculateOverallRiskRating($totalPoints);
echo "- Overall Risk Rating: $overallRating\n";

$clientAcceptance = determineClientAcceptance($totalPoints);
echo "- Client Acceptance: $clientAcceptance\n";

$monitoringFrequency = determineMonitoringFrequency($totalPoints);
echo "- Monitoring Frequency: $monitoringFrequency\n";

echo "\n=== Test Complete ===\n";

// Test with high-risk scenario
echo "\n=== High Risk Scenario Test ===\n";
$highRiskData = [
    'client_name' => 'High Risk Client',
    'client_screening_risk_id' => 'R006', // 5 points
    'client_category_risk_id' => 'R007',  // 4 points
    'requested_services_risk_id' => 'R008', // 5 points
    'payment_option_risk_id' => 'R009',   // 4 points
    'delivery_method_risk_id' => 'R010'   // 5 points
];

$totalPoints = calculateTotalRiskPoints($highRiskData);
echo "- Total Risk Points: $totalPoints\n";

$overallRating = calculateOverallRiskRating($totalPoints);
echo "- Overall Risk Rating: $overallRating\n";

$clientAcceptance = determineClientAcceptance($totalPoints);
echo "- Client Acceptance: $clientAcceptance\n";

$monitoringFrequency = determineMonitoringFrequency($totalPoints);
echo "- Monitoring Frequency: $monitoringFrequency\n";

echo "\n=== High Risk Test Complete ===\n";
?> 