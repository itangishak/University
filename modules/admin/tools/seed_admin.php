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

$email = $args['email'] ?? null;
$password = $args['password'] ?? null;
$username = $args['username'] ?? ($email ? strtok($email, '@') : null);
$first = $args['first'] ?? 'System';
$last = $args['last'] ?? 'Administrator';

if (!$email || !$password) {
    fwrite(STDERR, "Usage: php seed_admin.php --email=admin@uab.edu.bi --password=StrongPass123! [--username=admin] [--first=First] [--last=Last]\n");
    exit(2);
}

$db = Database::getInstance()->getConnection();

$existsStmt = $db->prepare("SELECT id FROM users WHERE role = 'administrator' LIMIT 1");
$existsStmt->execute();
$exists = $existsStmt->fetch(PDO::FETCH_ASSOC);
if ($exists) {
    echo "Administrator already exists (id={$exists['id']}).\n";
    exit(0);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (role, username, email, password_hash, first_name, last_name, status, preferred_language, created_at) VALUES ('administrator', ?, ?, ?, ?, ?, 'active', ?, NOW())");
$lang = defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'fr';
$stmt->execute([$username, $email, $hash, $first, $last, $lang]);

$id = $db->lastInsertId();
echo "Administrator created with id={$id}\n";
echo "Email: {$email}\n";
echo "Username: {$username}\n";
echo "Password: {$password}\n";
