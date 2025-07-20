<?php
session_start();

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
    
    // Redirect to remove 'lang' from URL
    $redirect_url = strtok($_SERVER['REQUEST_URI'], '?');
    $query = $_GET;
    unset($query['lang']);
    if (count($query) > 0) {
        $redirect_url .= '?' . http_build_query($query);
    }
    header('Location: ' . $redirect_url);
    exit;
}

// Load language file
$current_lang = $_SESSION['language'];
require_once __DIR__ . '/../languages/' . $current_lang . '.php';

// Translation function
function __($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}
?>