<?php
// Simple system test to verify everything is working
echo "🧪 Testing DCS Risk Assessment System...\n\n";

// Test 1: Check if auth.php exists and can be included
echo "1. Testing authentication system...\n";
if (file_exists('public/auth.php')) {
    echo "   ✅ auth.php exists\n";
    try {
        require_once 'public/auth.php';
        echo "   ✅ auth.php can be included without errors\n";
    } catch (Exception $e) {
        echo "   ❌ Error including auth.php: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ❌ auth.php not found\n";
}

// Test 2: Check if settings.php exists
echo "\n2. Testing settings system...\n";
if (file_exists('public/settings.php')) {
    echo "   ✅ settings.php exists\n";
} else {
    echo "   ❌ settings.php not found\n";
}

// Test 3: Check if index.php exists
echo "\n3. Testing main application...\n";
if (file_exists('public/index.php')) {
    echo "   ✅ index.php exists\n";
} else {
    echo "   ❌ index.php not found\n";
}

// Test 4: Check database connection
echo "\n4. Testing database connection...\n";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=dcs_risk_register;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✅ Database connection successful\n";
    
    // Check if tables exist
    $tables = ['users', 'risk_assessments', 'notifications', 'user_activity', 'system_settings'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "   ✅ Table '$table' exists\n";
        } else {
            echo "   ❌ Table '$table' missing\n";
        }
    }
} catch (PDOException $e) {
    echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\n🎯 System test completed!\n";
echo "📝 If all tests passed, your system should be working properly.\n";
echo "🌐 Access your system at: http://localhost/DCS-Best/public/\n";
?> 