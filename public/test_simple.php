<?php
echo "Testing database config...\n";

// Test 1: Check if file exists
if (file_exists('db_config.php')) {
    echo "✅ db_config.php file exists\n";
} else {
    echo "❌ db_config.php file NOT found\n";
    exit;
}

// Test 2: Try to load the config
try {
    $config = require_once 'db_config.php';
    echo "✅ Config loaded successfully\n";
    
    if (is_array($config)) {
        echo "✅ Config is an array\n";
        echo "Host: " . $config['host'] . "\n";
        echo "Database: " . $config['database'] . "\n";
    } else {
        echo "❌ Config is not an array\n";
    }
} catch (Exception $e) {
    echo "❌ Error loading config: " . $e->getMessage() . "\n";
}
?> 