<?php
/**
 * Email Service using PHPMailer with SMTP
 * Sends verification and welcome emails via SMTP
 */

require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PHPMailerService {
    private $mailer;
    private $senderEmail;
    private $senderName;
    
    public function __construct() {
        $this->senderEmail = defined('FROM_EMAIL') ? FROM_EMAIL : 'info@uab.edu.bi';
        $this->senderName = defined('FROM_NAME') ? FROM_NAME : 'Burundi Adventist University';
        
        // Create PHPMailer instance
        $this->mailer = new PHPMailer(true);
        
        // Configure SMTP settings
        $this->configureSMTP();
    }
    
    /**
     * Configure SMTP settings
     */
    private function configureSMTP() {
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = defined('SMTP_HOST') ? SMTP_HOST : 'mail.uab.edu.bi';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = defined('SMTP_USERNAME') ? SMTP_USERNAME : 'info@uab.edu.bi';
            $this->mailer->Password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : 'Default2025!';
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL/TLS
            $this->mailer->Port = defined('SMTP_PORT') ? SMTP_PORT : 465;
            
            // Sender info
            $this->mailer->setFrom($this->senderEmail, $this->senderName);
            
            // Enable debug output if in debug mode
            if (defined('EMAIL_DEBUG') && EMAIL_DEBUG) {
                $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
                $this->mailer->Debugoutput = function($str, $level) {
                    error_log("PHPMailer Debug [$level]: $str");
                };
            } else {
                $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
            }
            
            // Additional settings
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;
            
        } catch (Exception $e) {
            error_log("PHPMailer configuration error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Send verification email with confirmation number
     */
    public function sendVerificationEmail($recipientEmail, $recipientName, $confirmationNumber) {
        try {
            // Reset recipients for new email
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Recipients
            $this->mailer->addAddress($recipientEmail, $recipientName);
            
            // Content
            $this->mailer->Subject = 'Verify Your Email - Burundi Adventist University';
            $this->mailer->Body = $this->getVerificationEmailTemplate($recipientName, $confirmationNumber);
            $this->mailer->AltBody = strip_tags($this->getVerificationEmailTemplate($recipientName, $confirmationNumber));
            
            // For development/testing, log the email instead of sending
            if (defined('EMAIL_DEBUG') && EMAIL_DEBUG) {
                error_log("=== EMAIL DEBUG MODE ===");
                error_log("To: $recipientEmail");
                error_log("Subject: " . $this->mailer->Subject);
                error_log("Confirmation Number: $confirmationNumber");
                error_log("========================");
                return true;
            }
            
            // Send email
            $result = $this->mailer->send();
            
            if ($result) {
                error_log("Verification email sent successfully to: $recipientEmail");
            } else {
                error_log("Failed to send verification email to: $recipientEmail");
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $this->mailer->ErrorInfo);
            error_log("Exception: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send welcome email after successful verification
     */
    public function sendWelcomeEmail($recipientEmail, $recipientName) {
        try {
            // Reset recipients for new email
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Recipients
            $this->mailer->addAddress($recipientEmail, $recipientName);
            
            // Content
            $this->mailer->Subject = 'Welcome to Burundi Adventist University!';
            $this->mailer->Body = $this->getWelcomeEmailTemplate($recipientName);
            $this->mailer->AltBody = strip_tags($this->getWelcomeEmailTemplate($recipientName));
            
            // For development/testing, log the email instead of sending
            if (defined('EMAIL_DEBUG') && EMAIL_DEBUG) {
                error_log("=== WELCOME EMAIL DEBUG MODE ===");
                error_log("To: $recipientEmail");
                error_log("Subject: " . $this->mailer->Subject);
                error_log("================================");
                return true;
            }
            
            // Send email
            $result = $this->mailer->send();
            
            if ($result) {
                error_log("Welcome email sent successfully to: $recipientEmail");
            } else {
                error_log("Failed to send welcome email to: $recipientEmail");
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $this->mailer->ErrorInfo);
            error_log("Exception: " . $e->getMessage());
            return false;
        }
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
                    
                    <div class='warning'>
                        <strong>⚠️ Important:</strong> This verification code will expire in 24 hours for security reasons. If you don't verify within this time, you'll need to register again.
                    </div>
                    
                    <p>If you didn't create an account with Burundi Adventist University, please ignore this email and no account will be created.</p>
                    
                    <p>Need help? Contact our support team at " . htmlspecialchars($this->senderEmail) . " for assistance.</p>
                </div>
                <div class='footer'>
                    <p><strong>Burundi Adventist University</strong></p>
                    <p>© " . date('Y') . " All rights reserved.</p>
                    <p><small>This is an automated message. Please do not reply to this email.</small></p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * HTML email template for welcome message
     */
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
                    <p>© " . date('Y') . " All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
