<?php
require_once 'config/env.php';

echo "Testing environment variables:\n";
echo "DB_HOST: " . env('DB_HOST', 'NOT_SET') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME', 'NOT_SET') . "\n";
echo "DB_PASSWORD: " . env('DB_PASSWORD', 'NOT_SET') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE', 'NOT_SET') . "\n";

$config = require_once 'config/database.php';
echo "\nDatabase config:\n";
print_r($config);
?> 