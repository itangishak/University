<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/Database.php';
require_once __DIR__ . '/../../../includes/Auth.php';
require_once __DIR__ . '/../../../includes/SimpleEmailService.php';

// Handle verification request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        set_error_handler(function($severity, $message, $file, $line) { throw new ErrorException($message, 0, $severity, $file, $line); });
        set_exception_handler(function($e) { http_response_code(500); echo json_encode(['success' => false, 'error' => $e->getMessage()]); });
        register_shutdown_function(function() { $error = error_get_last(); if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) { http_response_code(500); header('Content-Type: application/json'); echo json_encode(['success' => false, 'error' => 'Fatal: ' . $error['message'] . ' in ' . $error['file'] . ':' . $error['line']]); } });
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $input = $_POST;
        }
        
        $email = trim($input['email'] ?? '');
        $confirmationNumber = trim($input['confirmation_number'] ?? '');
        
        // Validation
        if (empty($email) || empty($confirmationNumber)) {
            throw new Exception('Email and confirmation number are required');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address');
        }
        
        $db = Database::getInstance()->getConnection();
        
        // Find user with matching email and verification token
        $stmt = $db->prepare("SELECT id, email_verification_token, status FROM users WHERE email = ? AND status = 'pending_verification'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            throw new Exception('No pending verification found for this email address');
        }
        
        // Check if confirmation number matches (first 8 chars of token)
        $expectedConfirmationNumber = strtoupper(substr($user['email_verification_token'], 0, 8));
        if (strtoupper($confirmationNumber) !== $expectedConfirmationNumber) {
            throw new Exception('Invalid confirmation number');
        }
        
        // Activate the user account
        $stmt = $db->prepare("UPDATE users SET status = 'active', email_verification_token = NULL, email_verified_at = NOW() WHERE id = ?");
        $result = $stmt->execute([$user['id']]);
        
        if ($result) {
            // Get user details for welcome email
            $stmt = $db->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Send welcome email
            $emailService = new SimpleEmailService();
            $emailService->sendWelcomeEmail(
                $email,
                $userData['first_name'] . ' ' . $userData['last_name']
            );
            
            echo json_encode([
                'success' => true,
                'message' => 'Email verified successfully! Welcome to Burundi Adventist University. You can now login with your credentials.',
                'redirect' => rtrim(BASE_PATH, '/') . '/modules/admin/login/login.php'
            ]);
        } else {
            throw new Exception('Failed to verify email. Please try again.');
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

// Get email from URL parameter
$email = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Burundi Adventist University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .verify-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 0;
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        
        .verify-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .verify-body {
            padding: 2rem;
        }
        
        .university-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
        }
        
        .btn-verify {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="verify-container">
                    <div class="verify-header">
                        <div class="university-logo mx-auto mb-3 d-flex align-items-center justify-content-center bg-white rounded-circle">
                            <i class="bi bi-shield-check-fill text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h4 class="mb-2">Verify Your Email</h4>
                        <p class="mb-0 opacity-90">Enter the confirmation number sent to your email</p>
                    </div>
                    
                    <div class="verify-body">
                        <div id="alertContainer"></div>
                        
                        <form id="verifyForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-2"></i>Email Address
                                </label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirmation_number" class="form-label">
                                    <i class="bi bi-key-fill me-2"></i>Confirmation Number *
                                </label>
                                <input type="text" class="form-control" id="confirmation_number" name="confirmation_number" required placeholder="Enter 8-character code">
                                <small class="text-muted">Check your email for the confirmation number</small>
                            </div>
                            
                            <button type="submit" class="btn btn-verify btn-primary w-100 mb-3">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="verifySpinner"></span>
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Verify Email
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                Didn't receive the email? 
                                <a href="#" class="text-decoration-none" id="resendLink">
                                    Resend confirmation
                                </a>
                            </small>
                            <br>
                            <small class="text-muted mt-2 d-block">
                                <a href="<?php echo BASE_PATH; ?>/modules/admin/login/login.php" class="text-decoration-none">
                                    Back to Login
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('verifyForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const spinner = document.getElementById('verifySpinner');
            const alertContainer = document.getElementById('alertContainer');
            
            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            alertContainer.innerHTML = '';
            
            try {
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                
                const response = await fetch('verify.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            ${result.message}
                        </div>
                    `;
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 2000);
                } else {
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            ${result.error}
                        </div>
                    `;
                }
            } catch (error) {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        An error occurred. Please try again.
                    </div>
                `;
            } finally {
                // Hide loading state
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
        
        // Handle resend link
        document.getElementById('resendLink').addEventListener('click', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const alertContainer = document.getElementById('alertContainer');
            
            if (!email) {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Email address is required to resend verification code.
                    </div>
                `;
                return;
            }
            
            // Show loading state
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';
            this.disabled = true;
            
            try {
                const response = await fetch('resend.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            ${result.message}
                            ${result.confirmation_info ? '<br><strong>' + result.confirmation_info + '</strong>' : ''}
                        </div>
                    `;
                } else {
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            ${result.error}
                        </div>
                    `;
                }
            } catch (error) {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        An error occurred while resending the verification code.
                    </div>
                `;
            } finally {
                // Reset button state
                this.innerHTML = 'Resend confirmation';
                this.disabled = false;
            }
        });
    </script>
</body>
</html>
