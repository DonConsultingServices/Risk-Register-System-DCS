<?php
// Test database connection and check tables
try {
    $config = require_once 'config/database.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    echo "✅ Database connection successful!\n\n";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table exists!\n";
        
        // Check users count
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $stmt->fetch()['count'];
        echo "📊 Total users: $count\n\n";
        
        // Show all users
        $users = $pdo->query("SELECT username, email, role, is_active FROM users")->fetchAll();
        echo "👥 User List:\n";
        foreach ($users as $user) {
            echo "- Username: {$user['username']} | Email: {$user['email']} | Role: {$user['role']} | Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
        }
        
    } else {
        echo "❌ Users table does not exist!\n";
    }
    
    // Check if risks table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'risks'");
    if ($stmt->rowCount() > 0) {
        echo "\n✅ Risks table exists!\n";
        
        // Check risks count
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM risks");
        $count = $stmt->fetch()['count'];
        echo "📊 Total risks: $count\n";
        
    } else {
        echo "\n❌ Risks table does not exist!\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
?> 