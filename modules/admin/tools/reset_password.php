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

$identifier = $args['identifier'] ?? null; // email or username
$newPassword = $args['password'] ?? null;
if (!$identifier || !$newPassword) {
    fwrite(STDERR, "Usage: php reset_password.php --identifier=admin@uab.edu.bi --password='NewStrongPass!234'\n");
    exit(2);
}

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id, email, username, status FROM users WHERE username = ? OR email = ? LIMIT 1");
$stmt->execute([$identifier, $identifier]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    fwrite(STDERR, "User not found for identifier: {$identifier}\n");
    exit(3);
}

$hash = password_hash($newPassword, PASSWORD_DEFAULT);
$upd = $db->prepare("UPDATE users SET password_hash = ?, status = 'active', updated_at = NOW() WHERE id = ?");
$upd->execute([$hash, $user['id']]);

echo "Password reset for user id={$user['id']} (email={$user['email']}, username={$user['username']}).\n";
echo "New password: {$newPassword}\n";
