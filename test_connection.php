<?php
echo "🔍 Testing Database Connection\n";
echo "==============================\n\n";

try {
    // Test direct connection
    $pdo = new PDO("mysql:host=localhost;dbname=dcs_risk_register", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);
    
    echo "✅ Direct database connection successful!\n";
    
    // Test config file connection
    $config = require_once 'config/database.php';
    echo "📋 Config loaded:\n";
    echo "  Host: {$config['host']}\n";
    echo "  Username: {$config['username']}\n";
    echo "  Password: " . (empty($config['password']) ? '(empty)' : '(set)') . "\n";
    echo "  Database: {$config['database']}\n";
    
    $dsn = "mysql:host={$config['host']};dbname={$config['database']}";
    $pdo2 = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    echo "✅ Config-based database connection successful!\n";
    
    // Test user query
    $users = $pdo2->query("SELECT COUNT(*) as count FROM users")->fetch();
    echo "📊 Users in database: {$users['count']}\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
?> 