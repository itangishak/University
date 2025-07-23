<?php
// Footer content
?>
</main>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="<?php echo BASE_PATH; ?>/assets/images/logouab.png" alt="University Logo">
                    <span><?php echo __('site_name'); ?></span>
                </div>
                <div class="social-links-container">
                    <h3><?php echo __('follow_us'); ?></h3>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-section">
                <h3><?php echo __('quick_links'); ?></h3>
                <ul>
                    <li><a href="/about"><?php echo __('about_us_link'); ?></a></li>
                    <li><a href="/admissions"><?php echo __('admissions'); ?></a></li>
                    <li><a href="/academics"><?php echo __('academics'); ?></a></li>
                    <li><a href="/contact"><?php echo __('contact'); ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section contact-section">
                <h3><i class="fas fa-envelope-open-text mr-2"></i> <?php echo __('contact_us'); ?></h3>
                <div class="contact-info-single-line">
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo __('address'); ?></span>
                    <span><i class="fas fa-clock"></i> <?php echo __('hours'); ?></span>
                    <span><i class="fas fa-phone"></i> <a href="tel:+25769210815">+257 69210815</a> / <a href="tel:+25779155869">+257 79155869</a></span>
                    <span><i class="fas fa-envelope"></i> <a href="mailto:info@uab.edu.bi">info@uab.edu.bi</a></span>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo __('site_name'); ?>. <?php echo __('all_rights_reserved'); ?>.</p>
            <div class="footer-links">
                <a href="/privacy"><?php echo __('privacy_policy'); ?></a>
                <a href="/terms"><?php echo __('terms_of_service'); ?></a>
            </div>
        </div>
    </div>
</footer>
    <script src="<?php echo BASE_PATH; ?>/assets/js/main.js"></script>
</body>
</html>
