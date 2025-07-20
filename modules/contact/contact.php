<?php
require_once 'includes/header.php'; ?>

<h1><?php echo __('contact'); ?></h1>

<form action="/contact-submit.php" method="post">
    <div class="mb-3">
        <label for="name" class="form-label"><?php echo __('name'); ?></label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label"><?php echo __('email'); ?></label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="message" class="form-label"><?php echo __('message'); ?></label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo __('send'); ?></button>
</form>

<?php require_once 'includes/footer.php'; ?>