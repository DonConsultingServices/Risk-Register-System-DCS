<?php
echo "🔧 Creating Test User\n";
echo "====================\n\n";

try {
    $config = require_once 'config/database.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    // Delete existing test user if exists
    $pdo->exec("DELETE FROM users WHERE username = 'test'");
    
    // Create a simple test user
    $username = 'test';
    $password = 'test123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, email, password, first_name, last_name, role, department, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, 'test@dcs.com', $hashedPassword, 'Test', 'User', 'admin', 'IT', 1]);
    
    echo "✅ Created test user successfully!\n";
    echo "👤 Username: test\n";
    echo "🔑 Password: test123\n";
    echo "👑 Role: admin\n\n";
    
    // Verify the password works
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        echo "✅ Password verification works!\n";
    } else {
        echo "❌ Password verification failed!\n";
    }
    
    echo "\n🌐 Try logging in at: http://localhost/DCS-Best/public/login.php\n";
    echo "   Username: test\n";
    echo "   Password: test123\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 