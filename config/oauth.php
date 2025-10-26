<?php
/**
 * OAuth Configuration
 * Google OAuth 2.0 settings for user authentication
 */

// Google OAuth Configuration
$googleClientConfigPath = __DIR__ . '/../assets/js/client_secret_1041275507083-hpk89sbs7npu61jgh0o35ljsn3i1a8eu.apps.googleusercontent.com.json';
$googleConfig = [];
if (is_readable($googleClientConfigPath)) {
    $json = file_get_contents($googleClientConfigPath);
    $decoded = json_decode($json, true);
    if (is_array($decoded) && isset($decoded['web'])) {
        $googleConfig = $decoded['web'];
    }
}

define('GOOGLE_CLIENT_ID', $googleConfig['client_id'] ?? 'your-google-client-id.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', $googleConfig['client_secret'] ?? 'your-google-client-secret');
$redirectFromJson = $googleConfig['redirect_uris'][0] ?? null;
define('GOOGLE_REDIRECT_URI', $redirectFromJson ?: (rtrim(BASE_PATH, '/') . '/modules/admin/oauth/google-callback.php'));
define('GOOGLE_AUTH_URI', $googleConfig['auth_uri'] ?? 'https://accounts.google.com/o/oauth2/auth');
define('GOOGLE_TOKEN_URI', $googleConfig['token_uri'] ?? 'https://oauth2.googleapis.com/token');

// OAuth Scopes
define('GOOGLE_SCOPES', [
    'https://www.googleapis.com/auth/userinfo.email',
    'https://www.googleapis.com/auth/userinfo.profile'
]);

/**
 * Get Google OAuth authorization URL
 */
function getGoogleAuthUrl() {
    $params = [
        'client_id' => GOOGLE_CLIENT_ID,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'scope' => implode(' ', GOOGLE_SCOPES),
        'response_type' => 'code',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];
    
    return GOOGLE_AUTH_URI . '?' . http_build_query($params);
}

/**
 * Exchange authorization code for access token
 */
function getGoogleAccessToken($code) {
    $data = [
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'grant_type' => 'authorization_code',
        'code' => $code
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, GOOGLE_TOKEN_URI);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

/**
 * Get user info from Google API
 */
function getGoogleUserInfo($accessToken) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v2/userinfo');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

