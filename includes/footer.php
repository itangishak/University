</main>

<footer class="bg-light text-center text-lg-start mt-4">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-3"><?php echo __('quick_links'); ?></h5>
                <ul class="list-unstyled mb-0">
                    <li><a href="/about" class="text-dark"><?php echo __('menu_about'); ?></a></li>
                    <li><a href="/programs" class="text-dark"><?php echo __('menu_programs'); ?></a></li>
                    <li><a href="/news" class="text-dark"><?php echo __('menu_news'); ?></a></li>
                    <li><a href="/contact" class="text-dark"><?php echo __('menu_contact'); ?></a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-3"><?php echo __('contact_us'); ?></h5>
                <p>
                    Burundi Adventist University<br>
                    BP 123, Bujumbura, Burundi<br>
                    info@bau.edu.bi
                </p>
            </div>
            <div class="col-lg-4 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-3"><?php echo __('newsletter_signup'); ?></h5>
                <form action="#" method="post" class="d-flex">
                    <input type="email" class="form-control me-2" name="email" placeholder="<?php echo __('email_placeholder'); ?>" required>
                    <button class="btn btn-primary" type="submit"><?php echo __('subscribe'); ?></button>
                </form>
            </div>
        </div>
    </div>
    <div class="text-center p-3 bg-primary text-white mt-4">
        <?php echo __('copyright'); ?>
    </div>
</footer>

<script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
