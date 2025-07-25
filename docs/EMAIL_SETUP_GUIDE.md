# Email Setup Guide for University Website

This guide will help you configure email functionality for the University website using Gmail SMTP.

## Prerequisites

- Gmail account: `itangishakajohnesterique@gmail.com`
- Gmail App Password (not regular password)
- PHP with mail() function enabled OR PHPMailer library

## Step 1: Configure Gmail App Password

1. **Enable 2-Factor Authentication** on your Gmail account
2. Go to [Google Account Settings](https://myaccount.google.com/)
3. Navigate to **Security** > **2-Step Verification**
4. Scroll down to **App passwords**
5. Generate a new app password for "Mail"
6. Copy the 16-character app password

## Step 2: Update Configuration

Update `/config/config.php` with your Gmail credentials:

```php
// Email configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'itangishakajohnesterique@gmail.com');
define('SMTP_PASSWORD', 'your-16-char-app-password'); // Replace with actual app password
define('FROM_EMAIL', 'itangishakajohnesterique@gmail.com');
define('FROM_NAME', 'Burundi Adventist University');

// Email debug mode (set to true for development)
define('EMAIL_DEBUG', false); // Set to true for testing
```

## Step 3: Email Service Options

### Option 1: Simple Email Service (Current Implementation)
- Uses PHP's built-in `mail()` function
- Requires server mail configuration
- File: `/includes/SimpleEmailService.php`

### Option 2: PHPMailer (Recommended for Production)
- More reliable SMTP authentication
- Better error handling
- File: `/includes/EmailService.php`

To use PHPMailer:
1. Install via Composer: `composer require phpmailer/phpmailer`
2. Or download manually from [PHPMailer GitHub](https://github.com/PHPMailer/PHPMailer)

## Step 4: Server Configuration

### For Shared Hosting:
Most shared hosting providers support PHP mail() function out of the box.

### For Local Development (XAMPP/WAMP):
1. **Edit php.ini**:
   ```ini
   [mail function]
   SMTP = smtp.gmail.com
   smtp_port = 587
   sendmail_from = itangishakajohnesterique@gmail.com
   ```

2. **For Windows (sendmail.exe)**:
   - Download sendmail for Windows
   - Configure sendmail.ini with Gmail SMTP settings

### For VPS/Dedicated Server:
Install and configure a mail server like Postfix or use SMTP relay.

## Step 5: Testing Email Functionality

### Enable Debug Mode:
```php
// In config.php
define('EMAIL_DEBUG', true);
```

### Test Signup Process:
1. Navigate to signup page
2. Create a test account
3. Check server logs for email debug output
4. Verify email templates are properly formatted

### Check Email Logs:
- PHP error logs: `/var/log/php_errors.log`
- Apache/Nginx logs
- Application logs in `/logs/` directory

## Step 6: Email Templates

The system includes two email templates:

### 1. Verification Email
- **Purpose**: Sent after user registration
- **Contains**: 8-character verification code
- **Template**: Professional design with university branding
- **Expiry**: 24 hours

### 2. Welcome Email
- **Purpose**: Sent after successful email verification
- **Contains**: Welcome message and next steps
- **Template**: Congratulatory design

## Step 7: Production Deployment

### Security Checklist:
- [ ] Use App Password, not regular Gmail password
- [ ] Set `EMAIL_DEBUG = false` in production
- [ ] Enable HTTPS for all email-related pages
- [ ] Implement rate limiting for email sending
- [ ] Monitor email sending logs

### Performance Optimization:
- [ ] Consider using email queue for bulk sending
- [ ] Implement email templates caching
- [ ] Use CDN for email assets (images, CSS)

## Troubleshooting

### Common Issues:

1. **"Authentication failed"**
   - Verify App Password is correct
   - Ensure 2FA is enabled on Gmail
   - Check SMTP settings

2. **"Could not instantiate mail function"**
   - Verify PHP mail() is enabled
   - Check server mail configuration
   - Consider using PHPMailer instead

3. **Emails not being received**
   - Check spam/junk folders
   - Verify recipient email address
   - Check email server logs

4. **HTML not rendering properly**
   - Verify Content-Type header
   - Test email templates in different clients
   - Use inline CSS for better compatibility

### Debug Commands:

```php
// Test email configuration
$emailService = new SimpleEmailService();
$result = $emailService->sendVerificationEmail(
    'test@example.com',
    'Test User',
    'ABC12345'
);
var_dump($result);
```

## Email Flow Summary

1. **User Registration** → Account created with `pending_verification` status
2. **Verification Email Sent** → 8-character code sent to user's email
3. **User Verifies** → Enters code on verification page
4. **Account Activated** → Status changed to `active`
5. **Welcome Email Sent** → Confirmation of successful verification

## Features Implemented

- ✅ Verification email with 8-character code
- ✅ Welcome email after verification
- ✅ Resend verification functionality
- ✅ Email debug mode for development
- ✅ Professional email templates
- ✅ Error handling and fallbacks
- ✅ Gmail SMTP configuration

## Next Steps

1. Configure Gmail App Password
2. Update config.php with credentials
3. Test email functionality
4. Deploy to production
5. Monitor email delivery rates

The email system is now fully integrated with the signup and verification process!
