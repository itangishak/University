<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/oauth.php';
require_once __DIR__ . '/../../../includes/Database.php';
require_once __DIR__ . '/../../../includes/Auth.php';
require_once __DIR__ . '/../../../includes/SimpleEmailService.php';

// Handle signup request
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
        
        $firstName = trim($input['first_name'] ?? '');
        $lastName = trim($input['last_name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $password = $input['password'] ?? '';
        $confirmPassword = $input['confirm_password'] ?? '';
        
        // Validation
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            throw new Exception('All required fields must be filled');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address');
        }
        
        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long');
        }
        
        if ($password !== $confirmPassword) {
            throw new Exception('Passwords do not match');
        }
        
        $db = Database::getInstance()->getConnection();
        
        // Check if email already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception('Email already exists');
        }
        
        // Generate email verification token
        $verificationToken = bin2hex(random_bytes(32));
        
        // Create user account with student role (email as username) - status pending verification
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password_hash, first_name, last_name, phone, role, status, email_verification_token, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 'student', 'pending_verification', ?, NOW())
        ");
        
        $result = $stmt->execute([$email, $email, $hashedPassword, $firstName, $lastName, $phone, $verificationToken]);
        
        if ($result) {
            $confirmationNumber = substr($verificationToken, 0, 8);
            $response = [
                'success' => true,
                'message' => 'Account created successfully! A verification code has been sent to your email address. Please check your email and enter the code to verify your account.',
                'redirect' => rtrim(BASE_PATH, '/') . '/modules/admin/verify/verify.php?email=' . urlencode($email)
            ];
            echo json_encode($response);
            header('Connection: close');
            ignore_user_abort(true);
            if (function_exists('fastcgi_finish_request')) { 
                fastcgi_finish_request(); 
            } else { 
                while (ob_get_level() > 0) { @ob_end_flush(); }
                flush(); 
            }
            $emailService = new SimpleEmailService();
            $emailService->sendVerificationEmail(
                $email,
                $firstName . ' ' . $lastName,
                $confirmationNumber
            );
        } else {
            throw new Exception('Failed to create account. Please try again.');
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

// If already logged in, redirect to dashboard
$auth = new Auth();
if ($auth->isAuthenticated()) {
    $user = $auth->getCurrentUser();
    header('Location: ' . BASE_PATH . '/dashboard/' . $user['role']);
    exit;
}

// Get Google OAuth URL
$googleAuthUrl = getGoogleAuthUrl();

// Handle error from OAuth callback
$errorMessage = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student Account - Burundi Adventist University</title>
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
        .signup-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .signup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .university-logo {
            width: 80px;
            height: 80px;
        }
        .signup-body {
            padding: 2rem;
        }
        .btn-signup {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-signup:hover {
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
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="signup-container">
                    <div class="signup-header">
                        <div class="university-logo mx-auto mb-3 d-flex align-items-center justify-content-center bg-white rounded-circle">
                            <i class="bi bi-mortarboard-fill text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h2 class="mb-0">Create Student Account</h2>
                        <p class="mb-0 opacity-75">Burundi Adventist University</p>
                    </div>
                    
                    <div class="signup-body">
                        <div id="alertContainer"></div>
                        
                        <?php if ($errorMessage): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?php echo htmlspecialchars($errorMessage); ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Google Sign Up Button -->
                        <div class="mb-4">
                            <a href="<?php echo htmlspecialchars($googleAuthUrl); ?>" class="btn btn-outline-danger w-100 py-3 mb-3">
                                <svg width="18" height="18" viewBox="0 0 24 24" class="me-2">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                Continue with Google
                            </a>
                        </div>
                        
                        <!-- Divider -->
                        <div class="text-center mb-4">
                            <div class="d-flex align-items-center">
                                <hr class="flex-grow-1">
                                <span class="px-3 text-muted small">OR</span>
                                <hr class="flex-grow-1">
                            </div>
                        </div>
                        
                        <form id="signupForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">
                                        <i class="bi bi-person-fill me-2"></i>First Name *
                                    </label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">
                                        <i class="bi bi-person-fill me-2"></i>Last Name *
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-2"></i>Email Address *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone-fill me-2"></i>Phone Number
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-2"></i>Password *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-lock-fill me-2"></i>Confirm Password *
                                </label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-signup btn-primary w-100 mb-3">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="signupSpinner"></span>
                                <i class="bi bi-person-plus-fill me-2"></i>
                                Create Account
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                Already have an account? 
                                <a href="<?php echo BASE_PATH; ?>/modules/admin/login/login.php" class="text-decoration-none">
                                    Sign In
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
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.className = 'bi bi-eye-slash-fill';
            } else {
                passwordField.type = 'password';
                icon.className = 'bi bi-eye-fill';
            }
        });
        
        // Handle signup form submission
        document.getElementById('signupForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const spinner = document.getElementById('signupSpinner');
            const alertContainer = document.getElementById('alertContainer');
            
            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            alertContainer.innerHTML = '';
            
            try {
                const formData = new FormData(this);
                const data = {
                    first_name: formData.get('first_name'),
                    last_name: formData.get('last_name'),
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    username: formData.get('username'),
                    password: formData.get('password'),
                    confirm_password: formData.get('confirm_password')
                };
                
                const response = await fetch('/modules/admin/signup/signup.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 2000);
                } else {
                    showAlert(result.error || 'Signup failed', 'danger');
                }
                
            } catch (error) {
                showAlert('Network error. Please try again.', 'danger');
            } finally {
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
        
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }
        
        // Auto-focus on first name field
        document.getElementById('first_name').focus();
    </script>
</body>
</html>