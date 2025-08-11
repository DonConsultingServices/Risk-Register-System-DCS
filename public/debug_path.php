<?php
echo "Current directory: " . __DIR__ . "\n";
echo "Config path: " . __DIR__ . '/../config/database.php' . "\n";
echo "File exists: " . (file_exists(__DIR__ . '/../config/database.php') ? 'YES' : 'NO') . "\n";

if (file_exists(__DIR__ . '/../config/database.php')) {
    echo "Trying to load config...\n";
    $config = require_once __DIR__ . '/../config/database.php';
    echo "Config loaded: " . (is_array($config) ? 'YES' : 'NO') . "\n";
    if (is_array($config)) {
        echo "Config contents:\n";
        print_r($config);
    }
} else {
    echo "Config file not found!\n";
}
?> 