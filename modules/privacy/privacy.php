<?php
require_once __DIR__ . '/../../includes/header.php';

$pageTitle = __('Privacy Policy');
$metaDescription = __('Learn about our data collection and privacy practices');

// Set canonical URL for SEO
$canonicalUrl = BASE_PATH . '/privacy';

// Include header with navigation
include_once __DIR__ . '/../../includes/header.php';

?>

<main class="container py-5">
    <section class="privacy-hero hero-gradient">
        <div class="hero-content">
            <h1 class="hero-title"><?= $pageTitle ?></h1>
            <p class="hero-subtitle"><?= __('Your privacy matters to us') ?></p>
        </div>
    </section>

    <section class="privacy-content py-5">
        <div class="privacy-card">
            <div class="privacy-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2><?= __('Information Collection') ?></h2>
            <p><?= __('We collect information when you register, use our services, or interact with our website.') ?></p>
        </div>

        <div class="privacy-card">
            <div class="privacy-icon">
                <i class="fas fa-database"></i>
            </div>
            <h2><?= __('Data Usage') ?></h2>
            <p><?= __('Your data helps us improve services and communicate with you.') ?></p>
        </div>

        <div class="privacy-card">
            <div class="privacy-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2><?= __('Data Protection') ?></h2>
            <p><?= __('We implement security measures to protect your information.') ?></p>
        </div>

        <div class="privacy-contact">
            <h3><?= __('Questions?') ?></h3>
            <p><?= __('Contact our Data Protection Officer at') ?> <a href="mailto:privacy@uab.edu.bi">privacy@uab.edu.bi</a></p>
        </div>
    </section>
</main>

<?php
// Include footer
include_once __DIR__ . '/../../includes/footer.php';
?>
