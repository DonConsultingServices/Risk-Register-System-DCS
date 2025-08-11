<?php
echo "🔍 Testing MySQL Character Sets\n";
echo "==============================\n\n";

try {
    // Try connecting without specifying charset first
    $pdo = new PDO("mysql:host=localhost;dbname=dcs_risk_register", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ Connected to database successfully!\n";
    
    // Check available character sets
    $charsets = $pdo->query("SHOW CHARACTER SET")->fetchAll();
    echo "\n📋 Available character sets:\n";
    foreach ($charsets as $charset) {
        if (in_array($charset['Charset'], ['utf8', 'utf8mb4', 'latin1', 'ascii'])) {
            echo "  - {$charset['Charset']}: {$charset['Description']}\n";
        }
    }
    
    // Test different character sets
    $testCharsets = ['utf8', 'utf8mb4', 'latin1', 'ascii'];
    
    foreach ($testCharsets as $charset) {
        try {
            $testPdo = new PDO("mysql:host=localhost;dbname=dcs_risk_register;charset=$charset", "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            echo "✅ Charset '$charset' works!\n";
            break;
        } catch (PDOException $e) {
            echo "❌ Charset '$charset' failed: " . $e->getMessage() . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
?> 