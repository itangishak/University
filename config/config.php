<?php
// Site Configuration
define('SITE_NAME', 'University Website');
define('SITE_URL', 'https://uab.edu.bi/');
define('DEFAULT_LANGUAGE', 'fr');
define('AVAILABLE_LANGUAGES', ['en', 'fr']);

// Base path configuration
define('BASE_PATH', '/University');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'bau_website');
define('DB_USER', 'root');
define('DB_PASS', '');

// Email configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'itangishakajohnesterique@gmail.com');
define('SMTP_PASSWORD', 'your-app-password'); // Use Gmail App Password
define('FROM_EMAIL', 'itangishakajohnesterique@gmail.com');
define('FROM_NAME', 'Burundi Adventist University');

// Email debug mode (set to true for development)
define('EMAIL_DEBUG', true); // Set to false in production

// Debug Settings
define('DEBUG_MODE', true);

// Other Settings
define('TIMEZONE', 'UTC');

// Initialize language system
require_once __DIR__ . '/../includes/language.php';

// Set current language variable
$current_lang = $_SESSION['language'] ?? DEFAULT_LANGUAGE;
?>
