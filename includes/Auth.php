<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Database.php';

/**
 * Authentication Class
 * Handles user authentication and session management without cookies
 */
class Auth {
    private $db;
    private $currentUser = null;
    private $sessionToken = null;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->initializeSession();
    }
    
    /**
     * Initialize session from Authorization header or query parameter
     */
    private function initializeSession() {
        $token = null;
        
        // Check Authorization header first
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }
        
        // Fallback to query parameter (for initial login)
        if (!$token && isset($_GET['token'])) {
            $token = $_GET['token'];
        }
        
        // Fallback to POST parameter
        if (!$token && isset($_POST['token'])) {
            $token = $_POST['token'];
        }
        
        if ($token) {
            $this->validateSession($token);
        }
    }
    
    /**
     * Validate session token and load user data
     */
    private function validateSession($token) {
        $sql = "SELECT s.*, u.* FROM user_sessions s 
                JOIN users u ON s.user_id = u.id 
                WHERE s.session_token = ? AND s.expires_at > NOW()";
        
        $session = $this->db->fetch($sql, [$token]);
        
        if ($session) {
            // Update last activity
            $this->db->execute(
                "UPDATE user_sessions SET last_activity = NOW() WHERE session_token = ?",
                [$token]
            );
            
            $this->sessionToken = $token;
            $this->currentUser = [
                'id' => $session['user_id'],
                'username' => $session['username'],
                'email' => $session['email'],
                'role' => $session['role'],
                'first_name' => $session['first_name'],
                'last_name' => $session['last_name'],
                'preferred_language' => $session['preferred_language']
            ];
        }
    }
    
    /**
     * Authenticate user with username/email and password
     */
    public function login($identifier, $password) {
        // Find user by username or email
        $sql = "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1";
        $user = $this->db->fetch($sql, [$identifier, $identifier]);
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }
        
        // Check if user account is active
        if ($user['status'] !== 'active') {
            // Return specific error for different statuses
            switch ($user['status']) {
                case 'pending_verification':
                    throw new Exception('Please verify your email address before logging in. Check your email for the verification code.');
                case 'suspended':
                    throw new Exception('Your account has been suspended. Please contact support.');
                case 'inactive':
                    throw new Exception('Your account is inactive. Please contact support.');
                default:
                    throw new Exception('Your account is not active. Please contact support.');
            }
        }
        
        // Create new session
        $sessionToken = $this->generateSecureToken();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $sql = "INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, expires_at) 
                VALUES (?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $user['id'],
            $sessionToken,
            $ipAddress,
            $userAgent,
            $expiresAt
        ]);
        
        $this->sessionToken = $sessionToken;
        $this->currentUser = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'preferred_language' => $user['preferred_language']
        ];
        
        return $sessionToken;
    }
    
    public function loginUserId($userId) {
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ? LIMIT 1", [$userId]);
        
        if (!$user) {
            throw new Exception('User not found');
        }
        
        if ($user['status'] !== 'active') {
            switch ($user['status']) {
                case 'pending_verification':
                    throw new Exception('Please verify your email address before logging in. Check your email for the verification code.');
                case 'suspended':
                    throw new Exception('Your account has been suspended. Please contact support.');
                case 'inactive':
                    throw new Exception('Your account is inactive. Please contact support.');
                default:
                    throw new Exception('Your account is not active. Please contact support.');
            }
        }
        
        $sessionToken = $this->generateSecureToken();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $sql = "INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, expires_at) 
                VALUES (?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $user['id'],
            $sessionToken,
            $ipAddress,
            $userAgent,
            $expiresAt
        ]);
        
        $this->sessionToken = $sessionToken;
        $this->currentUser = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'preferred_language' => $user['preferred_language']
        ];
        
        return $sessionToken;
    }
    
    /**
     * Register new user
     */
    public function register($userData) {
        // Check if username or email already exists
        $sql = "SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1";
        $existing = $this->db->fetch($sql, [$userData['username'], $userData['email']]);
        
        if ($existing) {
            throw new Exception("Username or email already exists");
        }
        
        // Hash password
        $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Insert new user
        $sql = "INSERT INTO users (role, username, email, password_hash, first_name, last_name, preferred_language) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $userData['role'] ?? 'student',
            $userData['username'],
            $userData['email'],
            $passwordHash,
            $userData['first_name'] ?? null,
            $userData['last_name'] ?? null,
            $userData['preferred_language'] ?? 'en'
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Logout current user
     */
    public function logout() {
        if ($this->sessionToken) {
            $this->db->execute(
                "DELETE FROM user_sessions WHERE session_token = ?",
                [$this->sessionToken]
            );
        }
        
        $this->currentUser = null;
        $this->sessionToken = null;
    }
    
    /**
     * Check if user is authenticated
     */
    public function isAuthenticated() {
        return $this->currentUser !== null;
    }
    
    /**
     * Get current user data
     */
    public function getCurrentUser() {
        return $this->currentUser;
    }
    
    /**
     * Get current session token
     */
    public function getSessionToken() {
        return $this->sessionToken;
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($role) {
        return $this->currentUser && $this->currentUser['role'] === $role;
    }
    
    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole($roles) {
        if (!$this->currentUser) {
            return false;
        }
        
        return in_array($this->currentUser['role'], $roles);
    }
    
    /**
     * Require authentication
     */
    public function requireAuth() {
        if (!$this->isAuthenticated()) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Authentication required']);
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    public function requireRole($role) {
        $this->requireAuth();
        
        if (!$this->hasRole($role)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Insufficient permissions']);
            exit;
        }
    }
    
    /**
     * Require any of the specified roles
     */
    public function requireAnyRole($roles) {
        $this->requireAuth();
        
        if (!$this->hasAnyRole($roles)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Insufficient permissions']);
            exit;
        }
    }
    
    /**
     * Generate secure random token
     */
    private function generateSecureToken($length = 64) {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Clean expired sessions
     */
    public function cleanExpiredSessions() {
        $this->db->execute("DELETE FROM user_sessions WHERE expires_at < NOW()");
    }
    
    /**
     * Change user password
     */
    public function changePassword($userId, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?";
        $this->db->execute($sql, [$passwordHash, $userId]);
        
        // Invalidate all sessions for this user except current
        $sql = "DELETE FROM user_sessions WHERE user_id = ? AND session_token != ?";
        $this->db->execute($sql, [$userId, $this->sessionToken]);
    }
    
    /**
     * Update user profile
     */
    public function updateProfile($userId, $profileData) {
        $allowedFields = ['first_name', 'last_name', 'preferred_language'];
        $updateFields = [];
        $params = [];
        
        foreach ($allowedFields as $field) {
            if (isset($profileData[$field])) {
                $updateFields[] = "$field = ?";
                $params[] = $profileData[$field];
            }
        }
        
        if (!empty($updateFields)) {
            $params[] = $userId;
            $sql = "UPDATE users SET " . implode(', ', $updateFields) . ", updated_at = NOW() WHERE id = ?";
            $this->db->execute($sql, $params);
            
            // Update current user data if it's the same user
            if ($this->currentUser && $this->currentUser['id'] == $userId) {
                foreach ($allowedFields as $field) {
                    if (isset($profileData[$field])) {
                        $this->currentUser[$field] = $profileData[$field];
                    }
                }
            }
        }
    }
}
