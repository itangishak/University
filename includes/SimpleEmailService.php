<?php
/**
 * Simple Email Service using PHP's built-in mail() function
 * For Gmail SMTP, you'll need to configure your server or use PHPMailer
 */

class SimpleEmailService {
    private $senderEmail = 'info@uab.edu.bi';
    private $senderName = 'Burundi Adventist University';
    
    /**
     * Send verification email with confirmation number
     */
    public function sendVerificationEmail($recipientEmail, $recipientName, $confirmationNumber) {
        if (!function_exists('mail')) {
            error_log("mail() is disabled on this server. Verification email not sent.");
            return false;
        }
        $subject = 'Verify Your Email - Burundi Adventist University';
        $message = $this->getVerificationEmailTemplate($recipientName, $confirmationNumber);
        $headers = $this->getEmailHeaders();
        
        // For development/testing, log the email instead of sending
        if (defined('EMAIL_DEBUG') && EMAIL_DEBUG) {
            error_log("=== EMAIL DEBUG ===");
            error_log("To: $recipientEmail");
            error_log("Subject: $subject");
            error_log("Confirmation Number: $confirmationNumber");
            error_log("==================");
            return true;
        }
        
        // Attempt to send email
        $result = mail($recipientEmail, $subject, $message, $headers);
        
        if (!$result) {
            error_log("Failed to send verification email to: $recipientEmail");
        }
        
        return $result;
    }
    
    /**
     * Get email headers
     */
    private function getEmailHeaders() {
        return implode("\r\n", [
            "From: {$this->senderName} <{$this->senderEmail}>",
            "Reply-To: {$this->senderEmail}",
            "MIME-Version: 1.0",
            "Content-Type: text/html; charset=UTF-8",
            "X-Mailer: PHP/" . phpversion()
        ]);
    }
    
    /**
     * HTML email template for verification
     */
    private function getVerificationEmailTemplate($name, $confirmationNumber) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Email Verification</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    margin: 0; 
                    padding: 0; 
                    background-color: #f4f4f4; 
                }
                .container { 
                    max-width: 600px; 
                    margin: 20px auto; 
                    background: white; 
                    border-radius: 10px; 
                    overflow: hidden; 
                    box-shadow: 0 0 20px rgba(0,0,0,0.1); 
                }
                .header { 
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                    color: white; 
                    padding: 40px 30px; 
                    text-align: center; 
                }
                .header h1 { 
                    margin: 0; 
                    font-size: 28px; 
                }
                .header h2 { 
                    margin: 10px 0 0 0; 
                    font-size: 18px; 
                    opacity: 0.9; 
                }
                .content { 
                    padding: 40px 30px; 
                }
                .confirmation-box { 
                    background: #f8f9ff; 
                    padding: 30px; 
                    border-radius: 8px; 
                    text-align: center; 
                    margin: 30px 0; 
                    border: 2px dashed #667eea; 
                }
                .confirmation-number { 
                    font-size: 36px; 
                    font-weight: bold; 
                    color: #667eea; 
                    letter-spacing: 4px; 
                    margin: 15px 0; 
                    font-family: 'Courier New', monospace; 
                }
                .footer { 
                    background: #f8f9fa; 
                    padding: 20px 30px; 
                    text-align: center; 
                    color: #666; 
                    font-size: 14px; 
                }
                .warning { 
                    background: #fff3cd; 
                    border: 1px solid #ffeaa7; 
                    color: #856404; 
                    padding: 15px; 
                    border-radius: 5px; 
                    margin: 20px 0; 
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎓 Burundi Adventist University</h1>
                    <h2>Email Verification Required</h2>
                </div>
                <div class='content'>
                    <h3>Hello " . htmlspecialchars($name) . ",</h3>
                    <p>Welcome to Burundi Adventist University! To complete your registration and access your student account, please verify your email address.</p>
                    
                    <div class='confirmation-box'>
                        <p><strong>Your Verification Code:</strong></p>
                        <div class='confirmation-number'>" . strtoupper($confirmationNumber) . "</div>
                        <p><small>Enter this 8-character code on the verification page</small></p>
                    </div>
                    
                    <p><strong>Next Steps:</strong></p>
                    <ol>
                        <li>Return to the verification page</li>
                        <li>Enter the verification code above</li>
                        <li>Click 'Verify Email' to activate your account</li>
                    </ol>
                    
                    <h3 style='margin-top:30px;'>University Email Account Settings</h3>
                    <p>Use the following settings to configure your mail client.</p>
                    <table style='width:100%; border-collapse:collapse; background:#f8f9fa; border:1px solid #e9ecef;'>
                        <tbody>
                            <tr>
                                <td style='padding:10px; border:1px solid #e9ecef; width:45%;'><strong>Username</strong></td>
                                <td style='padding:10px; border:1px solid #e9ecef;'>info@uab.edu.bi</td>
                            </tr>
                            <tr>
                                <td style='padding:10px; border:1px solid #e9ecef;'><strong>Password</strong></td>
                                <td style='padding:10px; border:1px solid #e9ecef;'>Default2025!</td>
                            </tr>
                            <tr>
                                <td style='padding:10px; border:1px solid #e9ecef;'><strong>Incoming Server</strong></td>
                                <td style='padding:10px; border:1px solid #e9ecef;'>mail.uab.edu.bi</td>
                            </tr>
                            <tr>
                                <td style='padding:10px; border:1px solid #e9ecef;'><strong>IMAP Port</strong></td>
                                <td style='padding:10px; border:1px solid #e9ecef;'>993 (SSL/TLS)</td>
                            </tr>
                            <tr>
                                <td style='padding:10px; border:1px solid #e9ecef;'><strong>POP3 Port</strong></td>
                                <td style='padding:10px; border:1px solid #e9ecef;'>995 (SSL/TLS)</td>
                            </tr>
                            <tr>
                                <td style='padding:10px; border:1px solid #e9ecef;'><strong>Outgoing Server (SMTP)</strong></td>
                                <td style='padding:10px; border:1px solid #e9ecef;'>mail.uab.edu.bi</td>
                            </tr>
                            <tr>
                                <td style='padding:10px; border:1px solid #e9ecef;'><strong>SMTP Port</strong></td>
                                <td style='padding:10px; border:1px solid #e9ecef;'>465 (SSL/TLS)</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class='warning'>
                        <strong>⚠️ Important:</strong> This verification code will expire in 24 hours for security reasons. If you don't verify within this time, you'll need to register again.
                    </div>
                    
                    <p>If you didn't create an account with Burundi Adventist University, please ignore this email and no account will be created.</p>
                    
                    <p>Need help? Contact our support team for assistance.</p>
                </div>
                <div class='footer'>
                    <p><strong>Burundi Adventist University</strong></p>
                    <p>© 2024 All rights reserved.</p>
                    <p><small>This is an automated message. Please do not reply to this email.</small></p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Send welcome email after successful verification
     */
    public function sendWelcomeEmail($recipientEmail, $recipientName) {
        if (!function_exists('mail')) {
            error_log("mail() is disabled on this server. Welcome email not sent.");
            return false;
        }
        $subject = 'Welcome to Burundi Adventist University!';
        $message = $this->getWelcomeEmailTemplate($recipientName);
        $headers = $this->getEmailHeaders();
        
        // For development/testing, log the email instead of sending
        if (defined('EMAIL_DEBUG') && EMAIL_DEBUG) {
            error_log("=== WELCOME EMAIL DEBUG ===");
            error_log("To: $recipientEmail");
            error_log("Subject: $subject");
            error_log("===========================");
            return true;
        }
        
        $result = mail($recipientEmail, $subject, $message, $headers);
        
        if (!$result) {
            error_log("Failed to send welcome email to: $recipientEmail");
        }
        
        return $result;
    }
    
    private function getWelcomeEmailTemplate($name) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 30px; text-align: center; }
                .content { padding: 40px 30px; }
                .footer { background: #f8f9fa; padding: 20px 30px; text-align: center; color: #666; font-size: 14px; }
                .success-box { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎉 Welcome to BAU!</h1>
                </div>
                <div class='content'>
                    <h3>Hello " . htmlspecialchars($name) . ",</h3>
                    
                    <div class='success-box'>
                        <h4>✅ Email Successfully Verified!</h4>
                        <p>Your account is now active and ready to use.</p>
                    </div>
                    
                    <p>Congratulations! You have successfully joined the Burundi Adventist University community. Your student account is now fully activated.</p>
                    
                    <p><strong>What's Next?</strong></p>
                    <ul>
                        <li>Access your student dashboard</li>
                        <li>Complete your profile information</li>
                        <li>Explore available courses and programs</li>
                        <li>Connect with fellow students and faculty</li>
                    </ul>
                    
                    <p>We're excited to have you as part of our university family!</p>
                    
                    <p>Best regards,<br>
                    <strong>The BAU Team</strong></p>
                </div>
                <div class='footer'>
                    <p><strong>Burundi Adventist University</strong></p>
                    <p>© 2024 All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
