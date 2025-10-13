<?php
// Footer content
?>
</main>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="<?php echo BASE_PATH; ?>/assets/images/logo.png" alt="University Logo">
                    <span><?php echo __('site_name'); ?></span>
                </div>
                <div class="social-links-container">
                    <h3><?php echo __('follow_us'); ?></h3>
                    <div class="social-links">
                        <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo SOCIAL_TWITTER; ?>" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="<?php echo SOCIAL_YOUTUBE; ?>" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-section">
                <h3><?php echo __('quick_links'); ?></h3>
                <ul>
                    <li><a href="<?php echo BASE_PATH; ?>/modules/about/history.php"><?php echo __('about_us_link'); ?></a></li>
                    <li><a href="<?php echo BASE_PATH; ?>/modules/admission/admissions.php"><?php echo __('admissions'); ?></a></li>
                    <li><a href="<?php echo BASE_PATH; ?>/modules/faculty/faculties.php"><?php echo __('academics'); ?></a></li>
                    <li><a href="<?php echo BASE_PATH; ?>/modules/contact/contact.php"><?php echo __('contact'); ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section contact-section">
                <h3><i class="fas fa-envelope-open-text mr-2"></i> <?php echo __('contact_us'); ?></h3>
                <div class="contact-info-single-line">
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo __('address'); ?></span>
                    <span><i class="fas fa-clock"></i> <?php echo __('hours'); ?></span>
                    <span><i class="fas fa-phone"></i> <a href="tel:<?php echo str_replace(' ', '', UNIVERSITY_PHONE_1); ?>"><?php echo UNIVERSITY_PHONE_1; ?></a> / <a href="tel:<?php echo str_replace(' ', '', UNIVERSITY_PHONE_2); ?>"><?php echo UNIVERSITY_PHONE_2; ?></a></span>
                    <span><i class="fas fa-envelope"></i> <a href="mailto:<?php echo UNIVERSITY_EMAIL; ?>"><?php echo UNIVERSITY_EMAIL; ?></a></span>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo UNIVERSITY_FULL_NAME; ?>. <?php echo __('all_rights_reserved'); ?>.</p>
            <div class="footer-links">
                <a href="<?php echo PRIVACY_POLICY_URL; ?>"><?php echo __('privacy_policy'); ?></a>
                <a href="<?php echo TERMS_SERVICE_URL; ?>"><?php echo __('terms_of_service'); ?></a>
            </div>
        </div>
    </div>
</footer>
    <script src="<?php echo BASE_PATH; ?>/assets/js/main.js"></script>
</body>
</html>
