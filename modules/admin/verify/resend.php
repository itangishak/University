<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/Database.php';
require_once __DIR__ . '/../../../includes/PHPMailerService.php';

// Handle resend verification request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $input = $_POST;
        }
        
        $email = trim($input['email'] ?? '');
        
        // Validation
        if (empty($email)) {
            throw new Exception('Email address is required');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address');
        }
        
        $db = Database::getInstance()->getConnection();
        
        // Find user with pending verification
        $stmt = $db->prepare("SELECT id, first_name, last_name, email_verification_token FROM users WHERE email = ? AND status = 'pending_verification'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            throw new Exception('No pending verification found for this email address');
        }
        
        // Generate new verification token
        $newVerificationToken = bin2hex(random_bytes(32));
        $confirmationNumber = substr($newVerificationToken, 0, 8);
        
        // Update user with new token
        $stmt = $db->prepare("UPDATE users SET email_verification_token = ? WHERE id = ?");
        $stmt->execute([$newVerificationToken, $user['id']]);
        
        // Send new verification email
        $emailService = new PHPMailerService();
        $emailSent = $emailService->sendVerificationEmail(
            $email,
            $user['first_name'] . ' ' . $user['last_name'],
            $confirmationNumber
        );
        
        if ($emailSent) {
            echo json_encode([
                'success' => true,
                'message' => 'A new verification code has been sent to your email address. Please check your email.'
            ]);
        } else {
            // Email failed but token was updated
            echo json_encode([
                'success' => true,
                'message' => 'New verification code generated. Your code is: ' . strtoupper($confirmationNumber),
                'confirmation_info' => 'Please use this code: ' . strtoupper($confirmationNumber)
            ]);
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

// If accessed directly, redirect to verify page
header('Location: ' . BASE_PATH . '/modules/admin/verify/verify.php');
exit;
