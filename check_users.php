<?php
echo "🔍 Checking Users in Database\n";
echo "============================\n\n";

try {
    $config = require_once 'config/database.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table exists!\n\n";
        
        // Get all users
        $users = $pdo->query("SELECT id, username, email, first_name, last_name, role, is_active, created_at FROM users ORDER BY id")->fetchAll();
        
        echo "📊 Total users found: " . count($users) . "\n\n";
        
        foreach ($users as $user) {
            echo "👤 User ID: {$user['id']}\n";
            echo "   Username: {$user['username']}\n";
            echo "   Email: {$user['email']}\n";
            echo "   Name: {$user['first_name']} {$user['last_name']}\n";
            echo "   Role: {$user['role']}\n";
            echo "   Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
            echo "   Created: {$user['created_at']}\n";
            echo "   ---\n";
        }
        
        // Test password verification
        echo "\n🔐 Testing Password Verification:\n";
        $testUsers = ['admin', 'manager', 'staff', 'viewer'];
        
        foreach ($testUsers as $username) {
            $stmt = $pdo->prepare("SELECT username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user) {
                $passwordWorks = password_verify('password123', $user['password']);
                echo "   {$username}: " . ($passwordWorks ? '✅ password123 works' : '❌ password123 does NOT work') . "\n";
            } else {
                echo "   {$username}: ❌ User not found\n";
            }
        }
        
    } else {
        echo "❌ Users table does not exist!\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
?> 