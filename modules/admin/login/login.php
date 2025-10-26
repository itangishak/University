<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/oauth.php';
require_once __DIR__ . '/../../../includes/Auth.php';
require_once __DIR__ . '/../../../includes/NotificationSystem.php';

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $input = $_POST;
        }
        
        $identifier = $input['identifier'] ?? '';
        $password = $input['password'] ?? '';
        
        if (empty($identifier) || empty($password)) {
            throw new Exception('Username/email and password are required');
        }
        
        $auth = new Auth();
        $sessionToken = $auth->login($identifier, $password);
        
        if ($sessionToken) {
            $user = $auth->getCurrentUser();
            
            try {
                $notificationSystem = new NotificationSystem();
                $notificationSystem->notify(
                    $user['id'],
                    'welcome',
                    'Welcome back!',
                    'You have successfully logged in to your account.',
                    null,
                    [],
                    false
                );
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
            
            echo json_encode([
                'success' => true,
                'token' => $sessionToken,
                'user' => $user,
                'redirect' => rtrim(BASE_PATH, '/') . '/modules/admin/dashboard/' . $user['role'] . '/'
            ]);
        } else {
            throw new Exception('Invalid credentials');
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

// Handle logout request
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $auth = new Auth();
    $auth->logout();
    
    header('Location: ' . BASE_PATH . '/modules/admin/login/login.php');
    exit;
}

// If already logged in, redirect to dashboard
$auth = new Auth();
if ($auth->isAuthenticated()) {
    $user = $auth->getCurrentUser();
    header('Location: ' . rtrim(BASE_PATH, '/') . '/modules/admin/dashboard/' . $user['role'] . '/');
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
    <title>Login - Burundi Adventist University</title>
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
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(74, 144, 226, 0.3);
        }
        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 2px;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .university-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-container">
                    <div class="login-header">
                        <div class="university-logo mx-auto mb-3 d-flex align-items-center justify-content-center bg-white rounded-circle">
                            <i class="bi bi-mortarboard-fill text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h2 class="mb-0">Welcome Back</h2>
                        <p class="mb-0 opacity-75">Burundi Adventist University</p>
                    </div>
                    
                    <div class="login-body">
                        <div id="alertContainer"></div>
                        
                        <?php if ($errorMessage): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?php echo htmlspecialchars($errorMessage); ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Google Sign In Button -->
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
                        
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="identifier" class="form-label">
                                    <i class="bi bi-person-fill me-2"></i>Username or Email
                                </label>
                                <input type="text" class="form-control" id="identifier" name="identifier" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-2"></i>Password
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login btn-primary w-100 mb-3">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="loginSpinner"></span>
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Sign In
                            </button>
                        </form>
                        
                        <div class="text-center mb-3">
                            <a href="#" class="text-decoration-none text-primary" onclick="showForgotPassword()">
                                <i class="bi bi-key-fill me-1"></i>Forgot Password?
                            </a>
                        </div>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                Don't have an account? 
                                <a href="<?php echo BASE_PATH; ?>/modules/admin/signup/signup.php" class="text-decoration-none">
                                    Create Student Account
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
        
        // Handle login form submission
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const spinner = document.getElementById('loginSpinner');
            const alertContainer = document.getElementById('alertContainer');
            
            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            alertContainer.innerHTML = '';
            
            try {
                const formData = new FormData(this);
                const data = {
                    identifier: formData.get('identifier'),
                    password: formData.get('password')
                };
                
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Store token for future requests
                    sessionStorage.setItem('auth_token', result.token);
                    
                    // Show success message
                    showAlert('Login successful! Redirecting...', 'success');
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                } else {
                    showAlert(result.error || 'Login failed', 'danger');
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
        
        function showForgotPassword() {
            const email = prompt('Please enter your email address to reset your password:');
            if (email && email.includes('@')) {
                // Here you would typically send a request to a password reset endpoint
                showAlert('Password reset instructions have been sent to your email address.', 'info');
            } else if (email) {
                showAlert('Please enter a valid email address.', 'warning');
            }
        }
        
        // Auto-focus on identifier field
        document.getElementById('identifier').focus();
    </script>
</body>
</html>