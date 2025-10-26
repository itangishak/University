<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/Auth.php';

$auth = new Auth();
$auth->requireAuth();

$user = $auth->getCurrentUser();
$role = $user['role'];
$token = $auth->getSessionToken();

// Redirect to role-specific dashboard
switch ($role) {
    case 'student':
        $redir = rtrim(BASE_PATH, '/') . '/modules/admin/dashboard/student/';
        if ($token) { $redir .= '?token=' . urlencode($token); }
        header('Location: ' . $redir);
        break;
    case 'administrator':
        $redir = rtrim(BASE_PATH, '/') . '/modules/admin/dashboard/administrator/';
        if ($token) { $redir .= '?token=' . urlencode($token); }
        header('Location: ' . $redir);
        break;
    case 'communication_officer':
        $redir = rtrim(BASE_PATH, '/') . '/modules/admin/dashboard/communication_officer/';
        if ($token) { $redir .= '?token=' . urlencode($token); }
        header('Location: ' . $redir);
        break;
    case 'admission_officer':
        $redir = rtrim(BASE_PATH, '/') . '/modules/admin/dashboard/admission_officer/';
        if ($token) { $redir .= '?token=' . urlencode($token); }
        header('Location: ' . $redir);
        break;
    default:
        header('Location: ' . BASE_PATH . '/modules/admin/login/login.php');
        break;
}
exit;
?>
