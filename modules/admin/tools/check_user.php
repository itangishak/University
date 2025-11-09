<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/Database.php';

if (php_sapi_name() !== 'cli') { http_response_code(403); echo "Forbidden\n"; exit(1); }

$args = [];
foreach (array_slice($argv, 1) as $arg) {
    if (strpos($arg, '--') === 0) {
        $parts = explode('=', substr($arg, 2), 2);
        $key = $parts[0];
        $val = $parts[1] ?? null;
        $args[$key] = $val;
    }
}

$identifier = $args['identifier'] ?? null;
$testPassword = $args['password'] ?? null;
if (!$identifier) {
    fwrite(STDERR, "Usage: php check_user.php --identifier=admin@uab.edu.bi [--password=StrongPass123!]\n");
    exit(2);
}

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id, username, email, role, status, password_hash FROM users WHERE username = ? OR email = ? LIMIT 1");
$stmt->execute([$identifier, $identifier]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$result = [ 'found' => false ];
if ($user) {
    $result['found'] = true;
    $result['id'] = (int)$user['id'];
    $result['username'] = $user['username'];
    $result['email'] = $user['email'];
    $result['role'] = $user['role'];
    $result['status'] = $user['status'];
    $result['has_password'] = $user['password_hash'] !== null && $user['password_hash'] !== '';
    if ($testPassword !== null) {
        $result['password_match'] = password_verify($testPassword, $user['password_hash'] ?? '');
    }
}

echo json_encode($result, JSON_PRETTY_PRINT), "\n";
