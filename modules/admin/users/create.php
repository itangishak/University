<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/Auth.php';
require_once __DIR__ . '/../../../includes/Database.php';

header('Content-Type: application/json');

try {
    $auth = new Auth();
    $auth->requireRole('administrator');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) { $input = $_POST; }

    $email = trim($input['email'] ?? '');
    $first = trim($input['first_name'] ?? '');
    $last = trim($input['last_name'] ?? '');
    $role = trim($input['role'] ?? 'student');
    $password = $input['password'] ?? null;

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Valid email is required']);
        exit;
    }

    $allowedRoles = ['student','administrator','communication_officer','admission_officer'];
    if (!in_array($role, $allowedRoles, true)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid role']);
        exit;
    }

    $db = Database::getInstance()->getConnection();

    $exists = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $exists->execute([$email]);
    if ($exists->fetch(PDO::FETCH_ASSOC)) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already exists']);
        exit;
    }

    if (empty($password)) {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789@#$%&*!?';
        $len = strlen($alphabet);
        $pwd = '';
        for ($i = 0; $i < 12; $i++) { $pwd .= $alphabet[random_int(0, $len - 1)]; }
        $password = $pwd;
        $temp = true;
    } else {
        $temp = false;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $username = $input['username'] ?? $email;
    $lang = defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'fr';

    $stmt = $db->prepare("INSERT INTO users (role, username, email, password_hash, first_name, last_name, status, preferred_language, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', ?, NOW())");
    $stmt->execute([$role, $username, $email, $hash, $first, $last, $lang]);
    $id = $db->lastInsertId();

    $response = [
        'success' => true,
        'user' => [
            'id' => (int)$id,
            'email' => $email,
            'role' => $role,
            'first_name' => $first,
            'last_name' => $last,
            'username' => $username
        ]
    ];
    if ($temp) { $response['temporary_password'] = $password; }

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
