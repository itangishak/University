# Google OAuth Setup Guide

This guide will help you set up Google OAuth authentication for the University website.

## Prerequisites

- Google Cloud Console account
- Domain access (for production)
- PHP with cURL extension enabled

## Step 1: Create Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google+ API and Google OAuth2 API

## Step 2: Configure OAuth Consent Screen

1. In Google Cloud Console, go to **APIs & Services** > **OAuth consent screen**
2. Choose **External** user type (unless you have G Suite)
3. Fill in the required information:
   - **App name**: Burundi Adventist University
   - **User support email**: Your university email
   - **Developer contact information**: Your email
4. Add scopes:
   - `https://www.googleapis.com/auth/userinfo.email`
   - `https://www.googleapis.com/auth/userinfo.profile`
5. Add test users (for development)

## Step 3: Create OAuth 2.0 Credentials

1. Go to **APIs & Services** > **Credentials**
2. Click **Create Credentials** > **OAuth 2.0 Client IDs**
3. Choose **Web application**
4. Configure:
   - **Name**: University Website OAuth
   - **Authorized JavaScript origins**: 
     - `http://localhost` (for development)
     - `https://yourdomain.com` (for production)
   - **Authorized redirect URIs**:
     - `http://localhost/University/modules/admin/oauth/google-callback.php` (development)
     - `https://yourdomain.com/University/modules/admin/oauth/google-callback.php` (production)

## Step 4: Update Configuration

1. Copy your **Client ID** and **Client Secret**
2. Update `/config/oauth.php`:

```php
// Replace these with your actual credentials
define('GOOGLE_CLIENT_ID', 'your-actual-client-id.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'your-actual-client-secret');
```

## Step 5: Test the Integration

1. Navigate to the signup page
2. Click "Continue with Google"
3. Complete the OAuth flow
4. Verify the user is created in the database

## Database Schema Changes

The following fields have been added to support OAuth:

```sql
ALTER TABLE users 
ADD COLUMN oauth_provider VARCHAR(20),
ADD COLUMN oauth_provider_id VARCHAR(100),
ADD COLUMN avatar_url VARCHAR(255),
MODIFY COLUMN password_hash VARCHAR(255) NULL;
```

## Security Considerations

1. **HTTPS Required**: Google OAuth requires HTTPS in production
2. **Client Secret**: Keep your client secret secure and never expose it in frontend code
3. **Redirect URI**: Ensure redirect URIs are exactly configured in Google Console
4. **Scope Limitation**: Only request necessary scopes (email and profile)

## Troubleshooting

### Common Issues:

1. **"redirect_uri_mismatch"**: 
   - Check that redirect URI in Google Console matches exactly
   - Include protocol (http/https) and full path

2. **"invalid_client"**:
   - Verify Client ID and Client Secret are correct
   - Check that OAuth consent screen is configured

3. **"access_denied"**:
   - User cancelled the OAuth flow
   - Check OAuth consent screen configuration

### Debug Mode:

To enable debug mode, add error logging in the callback:

```php
// In google-callback.php
error_log('OAuth Error: ' . print_r($tokenData, true));
```

## Production Deployment

1. Update redirect URIs to use your production domain
2. Ensure HTTPS is enabled
3. Update `BASE_PATH` in config.php
4. Test the complete flow on production

## Features Implemented

- ✅ Google OAuth signup
- ✅ Google OAuth login  
- ✅ Automatic account creation for new Google users
- ✅ Account linking for existing users
- ✅ Profile picture integration
- ✅ Email verification bypass for Google users (email already verified by Google)

## User Experience

1. **New Users**: Click "Continue with Google" → Account created automatically → Redirected to dashboard
2. **Existing Users**: Click "Continue with Google" → Account linked → Logged in → Redirected to dashboard
3. **Fallback**: Traditional email/password signup and login still available

The system provides a seamless authentication experience while maintaining security and user choice.
