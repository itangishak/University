<?php
// Helper Functions

/**
 * Get current language
 * @return string Current language code
 */
function getCurrentLanguage() {
    return isset($_SESSION['language']) ? $_SESSION['language'] : DEFAULT_LANGUAGE;
}

/**
 * Translate text based on current language
 * @param string $key Translation key
 * @return string Translated text
 */
function translate($key) {
    global $translations;
    $lang = getCurrentLanguage();
    return isset($translations[$key]) ? $translations[$key] : $key;
}

/**
 * Sanitize input
 * @param string $input Input to sanitize
 * @return string Sanitized input
 */
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

/**
 * Database connection helper
 * @return PDO Database connection
 */
function getDbConnection() {
    try {
        require_once __DIR__ . '/../config/database.php';
        return new PDO(
            "mysql:host=$db_host;dbname=$db_name",
            $db_user,
            $db_pass
        );
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
