# PHPMailer Integration Documentation

## Overview
This document describes the PHPMailer integration for the Burundi Adventist University website to fix the verification email issue in the signup process.

## What Was Changed

### 1. PHPMailer Installation
- Downloaded PHPMailer v6.9.1 from GitHub
- Installed in `/includes/PHPMailer/` directory
- No composer required - standalone installation

### 2. New Email Service Created
- Created `PHPMailerService.php` in `/includes/` directory
- Replaces the old `SimpleEmailService.php` which used PHP's unreliable `mail()` function
- Uses proper SMTP authentication for reliable email delivery

### 3. Updated Files
The following files were updated to use `PHPMailerService` instead of `SimpleEmailService`:

- `/modules/admin/signup/signup.php` - User registration with email verification
- `/modules/admin/verify/verify.php` - Email verification and welcome email
- `/modules/admin/verify/resend.php` - Resend verification code

### 4. Test Script Created
- Created `test_phpmailer.php` in the root directory
- Allows you to test email functionality before going live
- Access it at: `https://uab.edu.bi/test_phpmailer.php`

## Configuration

### SMTP Settings
The SMTP configuration is defined in `/config/config.php`:

```php
// Email configuration
define('SMTP_HOST', 'mail.uab.edu.bi');
define('SMTP_PORT', 465);
define('SMTP_USERNAME', 'info@uab.edu.bi');
define('SMTP_PASSWORD', 'Default2025!');
define('FROM_EMAIL', 'info@uab.edu.bi');
define('FROM_NAME', 'Burundi Adventist University');

// Email debug mode (set to true for development)
define('EMAIL_DEBUG', false);
```

### Debug Mode
To enable detailed SMTP debugging, set `EMAIL_DEBUG` to `true` in config.php:

```php
define('EMAIL_DEBUG', true);
```

This will log detailed SMTP communication to your PHP error log.

## Features

### PHPMailerService Class

#### Methods:
1. **sendVerificationEmail($recipientEmail, $recipientName, $confirmationNumber)**
   - Sends a verification email with an 8-character confirmation code
   - Used during user signup
   - Returns: boolean (true on success, false on failure)

2. **sendWelcomeEmail($recipientEmail, $recipientName)**
   - Sends a welcome email after successful verification
   - Used after email verification is complete
   - Returns: boolean (true on success, false on failure)

### Email Templates
Both email templates are professionally designed with:
- Responsive HTML layout
- University branding (gradient purple theme)
- Clear call-to-action
- Mobile-friendly design

## Testing the Integration

### Step 1: Test Email Functionality
1. Navigate to `https://uab.edu.bi/test_phpmailer.php`
2. Enter your email address
3. Click "Send Test Email"
4. Check your inbox (and spam folder) for the test email

### Step 2: Test Signup Process
1. Go to `https://uab.edu.bi/modules/admin/signup/signup.php`
2. Fill in the registration form
3. Submit the form
4. Check your email for the verification code
5. Enter the code on the verification page
6. You should receive a welcome email

## Troubleshooting

### Email Not Sending?

1. **Check SMTP Credentials**
   - Verify SMTP_HOST, SMTP_PORT, SMTP_USERNAME, and SMTP_PASSWORD in config.php
   - Test credentials using an email client (Thunderbird, Outlook, etc.)

2. **Check Server Firewall**
   - Ensure outbound connections on port 465 (or your SMTP port) are allowed
   - Test with: `telnet mail.uab.edu.bi 465`

3. **Enable Debug Mode**
   - Set `EMAIL_DEBUG = true` in config.php
   - Check error logs: `/var/log/apache2/error.log` or `/var/log/php_errors.log`

4. **Check Email Spam Folder**
   - Sometimes verification emails end up in spam
   - Add info@uab.edu.bi to contacts/whitelist

5. **SMTP Authentication Issues**
   - Some SMTP servers require specific authentication methods
   - PHPMailer supports: LOGIN, PLAIN, CRAM-MD5, XOAUTH2

### Common Error Messages

**"SMTP connect() failed"**
- SMTP host or port is incorrect
- Firewall blocking connection
- SMTP server is down

**"SMTP Error: Could not authenticate"**
- Incorrect username or password
- Account locked or disabled
- Two-factor authentication enabled (requires app password)

**"Message could not be sent. Mailer Error: ..."**
- Check the specific error message in the logs
- Enable EMAIL_DEBUG for detailed information

## Security Considerations

1. **Protect config.php**
   - Never commit passwords to version control
   - Use environment variables in production
   - Restrict file permissions: `chmod 600 config/config.php`

2. **Rate Limiting**
   - Consider implementing rate limiting to prevent email spam
   - Limit verification email resends per user per hour

3. **Token Security**
   - Verification tokens are 64-character random strings
   - Only first 8 characters are shown to user
   - Tokens expire (implement expiration logic if needed)

## Migration from SimpleEmailService

The old `SimpleEmailService` can be safely kept as a backup:
- Renamed to `SimpleEmailService.php.backup`
- Or moved to `/includes/deprecated/`
- All references have been updated to use `PHPMailerService`

No changes needed to database schema or other components.

## Production Deployment Checklist

- [ ] Verify SMTP credentials are correct
- [ ] Set `EMAIL_DEBUG = false` in production
- [ ] Test email sending with `test_phpmailer.php`
- [ ] Test complete signup → verify → login flow
- [ ] Check email deliverability (not landing in spam)
- [ ] Monitor error logs for the first few days
- [ ] Consider setting up email monitoring/alerts
- [ ] Remove or restrict access to `test_phpmailer.php`

## Support

### PHPMailer Documentation
- Official Docs: https://github.com/PHPMailer/PHPMailer
- Wiki: https://github.com/PHPMailer/PHPMailer/wiki
- Troubleshooting: https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting

### Email Server Configuration
Contact your hosting provider or email administrator for:
- SMTP server details
- Port numbers and encryption methods
- Authentication requirements
- IP whitelisting if needed

## License
PHPMailer is open source under the LGPL 2.1 license.

---

**Last Updated:** October 14, 2025
**Version:** 1.0
**Author:** System Integration Team
