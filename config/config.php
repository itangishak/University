<?php
// Site Configuration
define('SITE_NAME', 'University Website');
define('SITE_URL', 'https://uab.edu.bi');
define('DEFAULT_LANGUAGE', 'fr');
define('AVAILABLE_LANGUAGES', ['en', 'fr']);

// Base path configuration
define('BASE_PATH', 'https://uab.edu.bi/');

// Database Configuration
define('DB_HOST', 'localhost'); // Change to your production DB server if different
define('DB_NAME', 'uab_website'); // Use production database name
define('DB_USER', 'uab_admin'); // Use production database username
define('DB_PASS', 'secure_password_here'); // Use strong production password

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

// University Contact Information
define('UNIVERSITY_PHONE_1', '+257 69210815');
define('UNIVERSITY_PHONE_2', '+257 79155869');
define('UNIVERSITY_EMAIL', 'info@uab.edu.bi');
define('UNIVERSITY_ADDRESS', 'Bujumbura, Commune Mukaza, Avenue de la Jeunesse'); // Can be overridden by translations

// Social Media Links
define('SOCIAL_FACEBOOK', 'https://www.facebook.com/profile.php?id=61578755886310');
define('SOCIAL_TWITTER', '#');
define('SOCIAL_INSTAGRAM', '#');
define('SOCIAL_YOUTUBE', 'https://youtube.com/@UniversitéAdventisteduBurundi');
define('SOCIAL_LINKEDIN', '#');

// Footer Links
define('PRIVACY_POLICY_URL', BASE_PATH . '/modules/privacy/privacy.php');
define('TERMS_SERVICE_URL', BASE_PATH . '/modules/terms/terms.php');

// University Information
define('UNIVERSITY_FULL_NAME', 'Burundi Adventist University');
define('UNIVERSITY_SHORT_NAME', 'UAB');
define('UNIVERSITY_ESTABLISHED_YEAR', '2024');
define('UNIVERSITY_MOTTO', 'Excellence in Education, Faith in Action');

// Operating Hours
define('UNIVERSITY_HOURS_WEEKDAY', '8:00 AM - 5:00 PM');
define('UNIVERSITY_HOURS_WEEKEND', 'Closed');

// Emergency Contact
define('EMERGENCY_CONTACT', '+257 69210815');

// Website Information
define('WEBSITE_VERSION', '2.0');
define('LAST_UPDATED', '2024');

// Initialize language system
require_once __DIR__ . '/../includes/language.php';

// Set current language variable
$current_lang = $_SESSION['language'] ?? DEFAULT_LANGUAGE;
?>
