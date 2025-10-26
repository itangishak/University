<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/oauth.php';
require_once __DIR__ . '/../../../includes/Database.php';
require_once __DIR__ . '/../../../includes/Auth.php';

try {
    // Check if we have an authorization code
    if (!isset($_GET['code'])) {
        throw new Exception('Authorization code not received');
    }
    
    $code = $_GET['code'];
    
    // Exchange code for access token
    $tokenData = getGoogleAccessToken($code);
    
    if (!isset($tokenData['access_token'])) {
        throw new Exception('Failed to get access token: ' . ($tokenData['error_description'] ?? 'Unknown error'));
    }
    
    // Get user info from Google
    $userInfo = getGoogleUserInfo($tokenData['access_token']);
    
    if (!$userInfo || !isset($userInfo['email'])) {
        throw new Exception('Failed to get user information from Google');
    }
    
    $db = Database::getInstance()->getConnection();
    
    // Check if user already exists
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? OR (oauth_provider = 'google' AND oauth_provider_id = ?)");
    $stmt->execute([$userInfo['email'], $userInfo['id']]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingUser) {
        // User exists - update OAuth info if needed and log them in
        if (!$existingUser['oauth_provider']) {
            // Link existing account with Google
            $stmt = $db->prepare("UPDATE users SET oauth_provider = 'google', oauth_provider_id = ?, avatar_url = ? WHERE id = ?");
            $stmt->execute([$userInfo['id'], $userInfo['picture'] ?? null, $existingUser['id']]);
        }
        
        $auth = new Auth();
        $token = $auth->loginUserId($existingUser['id']);
        header('Location: ' . rtrim(BASE_PATH, '/') . '/modules/admin/dashboard/' . $existingUser['role'] . '/?token=' . urlencode($token));
        exit;
    } else {
        // New user - create account
        $firstName = $userInfo['given_name'] ?? '';
        $lastName = $userInfo['family_name'] ?? '';
        
        // If name parts are not available, try to split the full name
        if (empty($firstName) && !empty($userInfo['name'])) {
            $nameParts = explode(' ', $userInfo['name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
        }
        
        $stmt = $db->prepare("
            INSERT INTO users (
                username, email, first_name, last_name, role, status, 
                oauth_provider, oauth_provider_id, avatar_url, 
                email_verified_at, created_at
            ) VALUES (?, ?, ?, ?, 'student', 'active', 'google', ?, ?, NOW(), NOW())
        ");
        
        $result = $stmt->execute([
            $userInfo['email'], // username = email
            $userInfo['email'],
            $firstName,
            $lastName,
            $userInfo['id'],
            $userInfo['picture'] ?? null
        ]);
        
        if ($result) {
            // Get the newly created user
            $userId = $db->lastInsertId();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $newUser = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $auth = new Auth();
            $token = $auth->loginUserId($newUser['id']);
            header('Location: ' . rtrim(BASE_PATH, '/') . '/modules/admin/dashboard/' . $newUser['role'] . '/?welcome=1&token=' . urlencode($token));
            exit;
        } else {
            throw new Exception('Failed to create user account');
        }
    }
    
} catch (Exception $e) {
    // Redirect to signup with error
    $errorMessage = urlencode('Google authentication failed: ' . $e->getMessage());
    header('Location: ' . BASE_PATH . '/modules/admin/signup/signup.php?error=' . $errorMessage);
    exit;
}
