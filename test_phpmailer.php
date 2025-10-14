<?php
/**
 * Test script for PHPMailer email service
 * This script tests if emails can be sent using PHPMailer
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/PHPMailerService.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>PHPMailer Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .result {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid;
        }
        .success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .info {
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
        .config {
            margin-top: 30px;
        }
        .config h3 {
            color: #667eea;
        }
        .config-item {
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📧 PHPMailer Email Service Test</h1>";

// Display current email configuration
echo "<div class='config'>
        <h3>Current Email Configuration:</h3>
        <div class='config-item'><strong>SMTP Host:</strong> " . (defined('SMTP_HOST') ? SMTP_HOST : 'Not defined') . "</div>
        <div class='config-item'><strong>SMTP Port:</strong> " . (defined('SMTP_PORT') ? SMTP_PORT : 'Not defined') . "</div>
        <div class='config-item'><strong>SMTP Username:</strong> " . (defined('SMTP_USERNAME') ? SMTP_USERNAME : 'Not defined') . "</div>
        <div class='config-item'><strong>From Email:</strong> " . (defined('FROM_EMAIL') ? FROM_EMAIL : 'Not defined') . "</div>
        <div class='config-item'><strong>From Name:</strong> " . (defined('FROM_NAME') ? FROM_NAME : 'Not defined') . "</div>
        <div class='config-item'><strong>Email Debug Mode:</strong> " . (defined('EMAIL_DEBUG') && EMAIL_DEBUG ? 'Enabled' : 'Disabled') . "</div>
      </div>";

// Diagnostics: OpenSSL, DNS, and socket connectivity
echo "<div class='config' style='margin-top:20px;'>
        <h3>Diagnostics</h3>";

// OpenSSL
$opensslLoaded = extension_loaded('openssl');
$opensslVer = defined('OPENSSL_VERSION_TEXT') ? OPENSSL_VERSION_TEXT : 'Unknown';
echo "<div class='config-item'><strong>OpenSSL Loaded:</strong> " . ($opensslLoaded ? 'Yes' : 'No') . "</div>";
echo "<div class='config-item'><strong>OpenSSL Version:</strong> " . htmlspecialchars($opensslVer) . "</div>";

// DNS resolution
$host = defined('SMTP_HOST') ? SMTP_HOST : '';
$resolved = $host ? gethostbyname($host) : '';
$dnsOk = $resolved && $resolved !== $host;
echo "<div class='config-item'><strong>DNS Resolution:</strong> Host '" . htmlspecialchars($host) . "' → " . htmlspecialchars($resolved ?: 'N/A') . " (" . ($dnsOk ? 'OK' : 'Failed') . ")</div>";

// Socket connectivity tests
function test_socket($scheme, $host, $port, $timeout = 10) {
    $target = $scheme . '://' . $host . ':' . $port;
    $errno = 0; $errstr = '';
    $start = microtime(true);
    $ctx = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
            'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT | STREAM_CRYPTO_METHOD_SSLv23_CLIENT,
        ]
    ]);
    $fp = @stream_socket_client($target, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $ctx);
    $elapsed = round((microtime(true) - $start) * 1000);
    if ($fp) {
        fclose($fp);
        return [true, "Connected to $target in {$elapsed}ms"]; 
    }
    return [false, "Failed to connect to $target in {$elapsed}ms: [$errno] $errstr"]; 
}

$port = defined('SMTP_PORT') ? SMTP_PORT : 465;
// For port 465, SMTPS (implicit TLS)
list($ok465, $msg465) = test_socket('ssl', $host, $port);
echo "<div class='config-item'><strong>Socket Test (ssl://$host:$port):</strong> " . ($ok465 ? '<span style=\'color:green\'>OK</span>' : '<span style=\'color:red\'>Failed</span>') . " — " . htmlspecialchars($msg465) . "</div>";

// Also try STARTTLS endpoint 587 (optional check)
list($ok587, $msg587) = test_socket('tcp', $host, 587);
echo "<div class='config-item'><strong>Socket Test (tcp://$host:587):</strong> " . ($ok587 ? '<span style=\'color:green\'>OK</span>' : '<span style=\'color:red\'>Failed</span>') . " — " . htmlspecialchars($msg587) . "</div>";

echo "</div>";

// Test email sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_email'])) {
    $testEmail = filter_var($_POST['test_email'], FILTER_VALIDATE_EMAIL);
    
    if (!$testEmail) {
        echo "<div class='result error'>
                <strong>❌ Error:</strong> Please provide a valid email address.
              </div>";
    } else {
        echo "<div class='result info'>
                <strong>ℹ️ Testing email functionality...</strong>
              </div>";
        
        try {
            // Create email service
            $emailService = new PHPMailerService();
            
            // Generate a test confirmation number
            $testConfirmationNumber = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            
            // Attempt to send verification email
            $result = $emailService->sendVerificationEmail(
                $testEmail,
                'Test User',
                $testConfirmationNumber
            );
            
            if ($result) {
                echo "<div class='result success'>
                        <strong>✅ Success!</strong> Test verification email sent to: <strong>$testEmail</strong>
                        <br><br>
                        <strong>Confirmation Number:</strong> $testConfirmationNumber
                        <br><br>
                        Please check your email inbox (and spam folder).
                      </div>";
            } else {
                echo "<div class='result error'>
                        <strong>❌ Failed!</strong> Could not send email to: <strong>$testEmail</strong>
                        <br><br>
                        Check the error log for more details.
                      </div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='result error'>
                    <strong>❌ Exception:</strong> " . htmlspecialchars($e->getMessage()) . "
                    <br><br>
                    <strong>Trace:</strong>
                    <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
                  </div>";
        }
    }
}

// Display test form
echo "
        <div style='margin-top: 30px;'>
            <h3>Send Test Email</h3>
            <form method='POST' action=''>
                <div style='margin-bottom: 15px;'>
                    <label for='test_email' style='display: block; margin-bottom: 5px; font-weight: bold;'>
                        Enter your email address to receive a test verification email:
                    </label>
                    <input 
                        type='email' 
                        id='test_email' 
                        name='test_email' 
                        required 
                        placeholder='your.email@example.com'
                        style='width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 16px;'
                    />
                </div>
                <button 
                    type='submit' 
                    style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;'
                >
                    Send Test Email
                </button>
            </form>
        </div>
        
        <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;'>
            <h3>Troubleshooting Tips:</h3>
            <ul style='line-height: 2;'>
                <li>Make sure your SMTP credentials are correct in <code>/config/config.php</code></li>
                <li>Verify that your SMTP server allows connections from this server</li>
                <li>Check if your firewall allows outbound connections on port 465 (or your configured SMTP port)</li>
                <li>Enable EMAIL_DEBUG mode in config.php to see detailed SMTP logs</li>
                <li>Check your email spam folder if you don't receive the test email</li>
                <li>Check server error logs at: <code>/var/log/apache2/error.log</code> or similar</li>
            </ul>
        </div>
    </div>
</body>
</html>";
?>
