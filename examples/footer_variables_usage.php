<?php
/**
 * Example usage of footer variables throughout the application
 * This demonstrates how the optimized footer variables can be reused
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Variables Usage Examples</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .example { background: #f4f4f4; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .code { background: #333; color: #fff; padding: 10px; border-radius: 3px; font-family: monospace; }
        h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Footer Variables Usage Examples</h1>
    <p>This page demonstrates how the optimized footer variables can be reused throughout the application.</p>

    <div class="example">
        <h2>1. University Contact Information</h2>
        <div class="code">
            &lt;?php echo getUniversityContact('phone1'); ?&gt;<br>
            &lt;?php echo getUniversityContact('email'); ?&gt;
        </div>
        <p><strong>Output:</strong></p>
        <p>Phone: <?php echo getUniversityContact('phone1'); ?></p>
        <p>Email: <?php echo getUniversityContact('email'); ?></p>
        <p>Emergency: <?php echo getUniversityContact('emergency'); ?></p>
    </div>

    <div class="example">
        <h2>2. University Information</h2>
        <div class="code">
            &lt;?php echo getUniversityInfo('full_name'); ?&gt;<br>
            &lt;?php echo getUniversityInfo('motto'); ?&gt;
        </div>
        <p><strong>Output:</strong></p>
        <p>Name: <?php echo getUniversityInfo('full_name'); ?></p>
        <p>Motto: <?php echo getUniversityInfo('motto'); ?></p>
        <p>Established: <?php echo getUniversityInfo('established_year'); ?></p>
    </div>

    <div class="example">
        <h2>3. Social Media Links</h2>
        <div class="code">
            &lt;a href="&lt;?php echo getSocialMediaLink('facebook'); ?&gt;"&gt;Facebook&lt;/a&gt;<br>
            &lt;a href="&lt;?php echo getSocialMediaLink('twitter'); ?&gt;"&gt;Twitter&lt;/a&gt;
        </div>
        <p><strong>Output:</strong></p>
        <p><a href="<?php echo getSocialMediaLink('facebook'); ?>" target="_blank">Facebook</a></p>
        <p><a href="<?php echo getSocialMediaLink('twitter'); ?>" target="_blank">Twitter</a></p>
        <p><a href="<?php echo getSocialMediaLink('linkedin'); ?>" target="_blank">LinkedIn</a></p>
    </div>

    <div class="example">
        <h2>4. All Social Media Links (Array)</h2>
        <div class="code">
            &lt;?php<br>
            $socialLinks = getAllSocialMediaLinks();<br>
            foreach($socialLinks as $platform =&gt; $url) {<br>
            &nbsp;&nbsp;&nbsp;&nbsp;echo "&lt;a href='$url'&gt;$platform&lt;/a&gt;&lt;br&gt;";<br>
            }<br>
            ?&gt;
        </div>
        <p><strong>Output:</strong></p>
        <?php
        $socialLinks = getAllSocialMediaLinks();
        foreach($socialLinks as $platform => $url) {
            echo "<p><a href='$url' target='_blank'>" . ucfirst($platform) . "</a></p>";
        }
        ?>
    </div>

    <div class="example">
        <h2>5. Direct Constant Access</h2>
        <div class="code">
            &lt;?php echo UNIVERSITY_PHONE_1; ?&gt;<br>
            &lt;?php echo UNIVERSITY_EMAIL; ?&gt;<br>
            &lt;?php echo SOCIAL_FACEBOOK; ?&gt;
        </div>
        <p><strong>Output:</strong></p>
        <p>Phone: <?php echo UNIVERSITY_PHONE_1; ?></p>
        <p>Email: <?php echo UNIVERSITY_EMAIL; ?></p>
        <p>Facebook: <?php echo SOCIAL_FACEBOOK; ?></p>
    </div>

    <div class="example">
        <h2>6. Footer Links</h2>
        <div class="code">
            &lt;a href="&lt;?php echo getFooterLink('privacy'); ?&gt;"&gt;Privacy Policy&lt;/a&gt;<br>
            &lt;a href="&lt;?php echo getFooterLink('terms'); ?&gt;"&gt;Terms of Service&lt;/a&gt;
        </div>
        <p><strong>Output:</strong></p>
        <p><a href="<?php echo getFooterLink('privacy'); ?>">Privacy Policy</a></p>
        <p><a href="<?php echo getFooterLink('terms'); ?>">Terms of Service</a></p>
    </div>

    <div class="example">
        <h2>7. Phone Number Formatting</h2>
        <div class="code">
            &lt;a href="tel:&lt;?php echo formatPhoneForTel(UNIVERSITY_PHONE_1); ?&gt;"&gt;<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&lt;?php echo UNIVERSITY_PHONE_1; ?&gt;<br>
            &lt;/a&gt;
        </div>
        <p><strong>Output:</strong></p>
        <p><a href="tel:<?php echo formatPhoneForTel(UNIVERSITY_PHONE_1); ?>"><?php echo UNIVERSITY_PHONE_1; ?></a></p>
    </div>

    <h2>Benefits of This Optimization</h2>
    <ul>
        <li><strong>Centralized Management:</strong> All contact information is managed in one place (config.php)</li>
        <li><strong>Reusability:</strong> Variables can be used anywhere in the application</li>
        <li><strong>Consistency:</strong> Ensures consistent information across all pages</li>
        <li><strong>Easy Updates:</strong> Change once in config.php, updates everywhere</li>
        <li><strong>Maintainability:</strong> Easier to maintain and update contact information</li>
        <li><strong>Flexibility:</strong> Helper functions provide different ways to access the data</li>
    </ul>
</body>
</html>
