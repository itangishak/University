<?php
/**
 * Email Service for sending verification emails
 * Uses PHPMailer with Gmail SMTP
 */

require_once __DIR__ . '/../vendor/autoload.php'; // If using Composer
// If not using Composer, you'll need to download PHPMailer manually

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;
    private $senderEmail = 'itangishakajohnesterique@gmail.com';
    private $senderName = 'Burundi Adventist University';
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configureSMTP();
    }
    
    private function configureSMTP() {
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host       = 'smtp.gmail.com';
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $this->senderEmail;
            $this->mailer->Password   = 'your-app-password'; // Use App Password, not regular password
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port       = 587;
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            
        } catch (Exception $e) {
            error_log("SMTP Configuration Error: " . $e->getMessage());
        }
    }
    
    /**
     * Send verification email with confirmation number
     */
    public function sendVerificationEmail($recipientEmail, $recipientName, $confirmationNumber) {
        try {
            // Recipients
            $this->mailer->setFrom($this->senderEmail, $this->senderName);
            $this->mailer->addAddress($recipientEmail, $recipientName);
            $this->mailer->addReplyTo($this->senderEmail, $this->senderName);
            
            // Content
            $this->mailer->Subject = 'Verify Your Email - Burundi Adventist University';
            $this->mailer->Body = $this->getVerificationEmailTemplate($recipientName, $confirmationNumber);
            $this->mailer->AltBody = $this->getVerificationEmailTextTemplate($recipientName, $confirmationNumber);
            
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            return false;
        } finally {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
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
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .confirmation-box { background: white; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; border: 2px dashed #667eea; }
                .confirmation-number { font-size: 32px; font-weight: bold; color: #667eea; letter-spacing: 3px; margin: 10px 0; }
                .btn { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎓 Burundi Adventist University</h1>
                    <h2>Email Verification</h2>
                </div>
                <div class='content'>
                    <h3>Hello " . htmlspecialchars($name) . ",</h3>
                    <p>Welcome to Burundi Adventist University! To complete your registration, please verify your email address using the confirmation number below:</p>
                    
                    <div class='confirmation-box'>
                        <p><strong>Your Confirmation Number:</strong></p>
                        <div class='confirmation-number'>" . strtoupper($confirmationNumber) . "</div>
                        <p><small>Enter this code on the verification page</small></p>
                    </div>
                    
                    <p>This confirmation number will expire in 24 hours for security reasons.</p>
                    
                    <p>If you didn't create an account with us, please ignore this email.</p>
                    
                    <div class='footer'>
                        <p>© 2024 Burundi Adventist University. All rights reserved.</p>
                        <p>This is an automated message, please do not reply to this email.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Plain text email template for verification
     */
    private function getVerificationEmailTextTemplate($name, $confirmationNumber) {
        return "
BURUNDI ADVENTIST UNIVERSITY
Email Verification

Hello " . $name . ",

Welcome to Burundi Adventist University! To complete your registration, please verify your email address using the confirmation number below:

CONFIRMATION NUMBER: " . strtoupper($confirmationNumber) . "

Enter this code on the verification page to activate your account.

This confirmation number will expire in 24 hours for security reasons.

If you didn't create an account with us, please ignore this email.

© 2024 Burundi Adventist University. All rights reserved.
This is an automated message, please do not reply to this email.
        ";
    }
    
    /**
     * Send welcome email after successful verification
     */
    public function sendWelcomeEmail($recipientEmail, $recipientName) {
        try {
            // Recipients
            $this->mailer->setFrom($this->senderEmail, $this->senderName);
            $this->mailer->addAddress($recipientEmail, $recipientName);
            $this->mailer->addReplyTo($this->senderEmail, $this->senderName);
            
            // Content
            $this->mailer->Subject = 'Welcome to Burundi Adventist University!';
            $this->mailer->Body = $this->getWelcomeEmailTemplate($recipientName);
            $this->mailer->AltBody = $this->getWelcomeEmailTextTemplate($recipientName);
            
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Welcome email sending failed: " . $e->getMessage());
            return false;
        } finally {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
        }
    }
    
    private function getWelcomeEmailTemplate($name) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎓 Welcome to BAU!</h1>
                </div>
                <div class='content'>
                    <h3>Hello " . htmlspecialchars($name) . ",</h3>
                    <p>Your email has been successfully verified! You can now access your student dashboard and explore all the features available to you.</p>
                    <p>Thank you for joining Burundi Adventist University!</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    private function getWelcomeEmailTextTemplate($name) {
        return "
BURUNDI ADVENTIST UNIVERSITY
Welcome!

Hello " . $name . ",

Your email has been successfully verified! You can now access your student dashboard and explore all the features available to you.

Thank you for joining Burundi Adventist University!
        ";
    }
}
