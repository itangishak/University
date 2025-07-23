<?php
// Define the base path for the project
define('BASE_PATH', '/University');

define('ASSETS_PATH', BASE_PATH . '/assets');

define('MODULES_PATH', BASE_PATH . '/modules');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'university');

// Language initialization
require_once __DIR__ . '/includes/language.php';
