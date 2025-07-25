<?php
session_start();

// Debug output
if (isset($_GET['debug_lang']) && $_GET['debug_lang'] === 'raw') {
    header('Content-Type: text/plain');
    echo 'Current Session Language: ' . ($_SESSION['language'] ?? 'not set') . "\n";
    echo 'Current Translations: ' . print_r($GLOBALS['translations'] ?? [], true);
    exit;
}

// Default language
$default_language = 'fr';

// Detect browser language if not set in session
if (!isset($_SESSION['language'])) {
    $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $_SESSION['language'] = in_array($browser_lang, ['fr', 'en']) ? $browser_lang : $default_language;
}

// Manual language switch
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
    $_SESSION['language'] = $_GET['lang'];
    
    // Get current path without query parameters
    $current_path = strtok($_SERVER['REQUEST_URI'], '?');
    $query = $_GET;
    unset($query['lang']);
    
    // Build redirect URL
    $redirect_url = $current_path;
    if (count($query) > 0) {
        $redirect_url .= '?' . http_build_query($query);
    }
    
    // Ensure proper path handling for different environments
    if ($_SERVER['HTTP_HOST'] === 'uab.edu.bi') {
        // Handle production URLs with full domain
        $base_url = 'https://uab.edu.bi';
        if ($current_path === '/' || $current_path === '/index.php') {
            $redirect_url = $base_url . '/index.php';
        } else {
            $redirect_url = $base_url . $current_path;
        }
        if (count($query) > 0) {
            $redirect_url .= '?' . http_build_query($query);
        }
    }
    // Handle local development paths
    elseif (strpos($current_path, '/University/') === false) {
        // Handle root paths
        if ($current_path === '/' || $current_path === 'index.php') {
            $redirect_url = 'index.php';
            if (count($query) > 0) {
                $redirect_url .= '?' . http_build_query($query);
            }
        }
    }
    
    header('Location: ' . $redirect_url);
    exit;
}

// Load language file
$current_lang = $_SESSION['language'];

// Debug path
$langPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $current_lang . '.php';
if (!file_exists($langPath)) {
    die('Language file not found at: ' . $langPath);
}
require_once $langPath;

// Translation function
function __($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}
?>