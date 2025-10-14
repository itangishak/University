<?php
/**
 * Test Signup Flow
 * Quick test to verify signup and email verification work correctly
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/Database.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Signup Flow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 50px;
            background: #f5f5f5;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .test-result {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid;
        }
        .test-result.success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .test-result.error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .test-result.info {
            background: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🧪 Signup Flow Test</h1>
        <p>This page tests the complete signup and verification flow.</p>
        
        <hr>
        
        <h3>Configuration Check</h3>
        <div class="test-result info">
            <strong>SMTP Settings:</strong><br>
            Host: <?= SMTP_HOST ?><br>
            Port: <?= SMTP_PORT ?><br>
            Username: <?= SMTP_USERNAME ?><br>
            From: <?= FROM_EMAIL ?><br>
        </div>
        
        <h3>Database Connection</h3>
        <?php
        try {
            $db = Database::getInstance()->getConnection();
            echo '<div class="test-result success">✅ Database connection successful</div>';
            
            // Check users table structure
            $stmt = $db->query("DESCRIBE users");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $requiredColumns = ['email_verification_token', 'status', 'email'];
            $hasAllColumns = true;
            foreach ($requiredColumns as $col) {
                if (!in_array($col, $columns)) {
                    $hasAllColumns = false;
                    break;
                }
            }
            
            if ($hasAllColumns) {
                echo '<div class="test-result success">✅ Users table has all required columns</div>';
            } else {
                echo '<div class="test-result error">❌ Missing required columns in users table</div>';
            }
            
        } catch (Exception $e) {
            echo '<div class="test-result error">❌ Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
        
        <h3>PHPMailer Check</h3>
        <?php
        $phpmailerPath = __DIR__ . '/includes/PHPMailer/src/PHPMailer.php';
        if (file_exists($phpmailerPath)) {
            echo '<div class="test-result success">✅ PHPMailer is installed</div>';
            
            require_once __DIR__ . '/includes/PHPMailerService.php';
            try {
                $mailer = new PHPMailerService();
                echo '<div class="test-result success">✅ PHPMailerService initialized successfully</div>';
            } catch (Exception $e) {
                echo '<div class="test-result error">❌ PHPMailerService error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        } else {
            echo '<div class="test-result error">❌ PHPMailer not found at: ' . htmlspecialchars($phpmailerPath) . '</div>';
        }
        ?>
        
        <h3>Test URLs</h3>
        <div class="test-result info">
            <strong>Signup Page:</strong><br>
            <a href="<?= BASE_PATH ?>modules/admin/signup/signup.php" target="_blank">
                <?= BASE_PATH ?>modules/admin/signup/signup.php
            </a>
            <br><br>
            
            <strong>Verify Page:</strong><br>
            <a href="<?= BASE_PATH ?>modules/admin/verify/verify.php" target="_blank">
                <?= BASE_PATH ?>modules/admin/verify/verify.php
            </a>
            <br><br>
            
            <strong>Login Page:</strong><br>
            <a href="<?= BASE_PATH ?>modules/admin/login/login.php" target="_blank">
                <?= BASE_PATH ?>modules/admin/login/login.php
            </a>
        </div>
        
        <h3>Manual Test Steps</h3>
        <ol style="line-height: 2;">
            <li>Go to the <a href="<?= BASE_PATH ?>modules/admin/signup/signup.php" target="_blank">Signup Page</a></li>
            <li>Fill in the registration form with valid data</li>
            <li>Submit the form (AJAX will handle it)</li>
            <li>You should see a success message</li>
            <li>Check your email for verification code (or it will be shown on screen if email fails)</li>
            <li>Enter the code on the verification page</li>
            <li>After verification, you should be redirected to login</li>
            <li>Login with your credentials</li>
        </ol>
        
        <h3>Performance Notes</h3>
        <ul>
            <li>✅ AJAX submission - no page reload</li>
            <li>✅ Email timeout set to 10 seconds - won't block indefinitely</li>
            <li>✅ User account created immediately - email sent asynchronously</li>
            <li>✅ If email fails, code is shown directly to user</li>
            <li>✅ Clean error handling - graceful failures</li>
        </ul>
        
        <div class="mt-4">
            <a href="<?= BASE_PATH ?>modules/admin/signup/signup.php" class="btn btn-primary btn-lg">
                Go to Signup Page
            </a>
        </div>
    </div>
</body>
</html>
