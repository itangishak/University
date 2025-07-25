<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/Auth.php';

$auth = new Auth();
$auth->requireAuth();

$user = $auth->getCurrentUser();
$role = $user['role'];

// Redirect to role-specific dashboard
switch ($role) {
    case 'student':
        header('Location: ' . BASE_PATH . '/modules/admin/dashboard/student/');
        break;
    case 'administrator':
        header('Location: ' . BASE_PATH . '/modules/admin/dashboard/administrator/');
        break;
    case 'communication_officer':
        header('Location: ' . BASE_PATH . '/modules/admin/dashboard/communication_officer/');
        break;
    case 'admission_officer':
        header('Location: ' . BASE_PATH . '/modules/admin/dashboard/admission_officer/');
        break;
    default:
        header('Location: ' . BASE_PATH . '/modules/admin/login/login.php');
        break;
}
exit;
?>
