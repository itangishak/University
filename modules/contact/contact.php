<?php
require_once '../../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4"><?php echo __('contact_title'); ?></h1>
                <p class="lead mb-0"><?php echo __('contact_subtitle'); ?></p>
            </div>
        </div>
    </div>
</section>

<div class="container-fluid" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem 0;">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-4">
                        <form id="contactForm" action="#" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="fullName" class="form-label fw-semibold">
                                        <i class="fas fa-user me-2 text-primary"></i><?php echo __('contact_name'); ?>
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="fullName" name="fullName" required>
                                    <div class="invalid-feedback"><?php echo __('contact_required'); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-2 text-primary"></i><?php echo __('contact_email'); ?>
                                    </label>
                                    <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                                    <div class="invalid-feedback"><?php echo __('contact_required'); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">
                                        <i class="fas fa-phone me-2 text-primary"></i><?php echo __('contact_phone'); ?>
                                    </label>
                                    <input type="tel" class="form-control form-control-lg" id="phone" name="phone">
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label fw-semibold">
                                        <i class="fas fa-tag me-2 text-primary"></i><?php echo __('contact_subject'); ?>
                                    </label>
                                    <select class="form-select form-select-lg" id="subject" name="subject" required>
                                        <option value="">Choose a subject...</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="admissions"><?php echo __('contact_admissions_dept'); ?></option>
                                        <option value="academic"><?php echo __('contact_academic_dept'); ?></option>
                                        <option value="student"><?php echo __('contact_student_dept'); ?></option>
                                        <option value="finance"><?php echo __('contact_finance_dept'); ?></option>
                                    </select>
                                    <div class="invalid-feedback"><?php echo __('contact_required'); ?></div>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label fw-semibold">
                                        <i class="fas fa-comment me-2 text-primary"></i><?php echo __('contact_message'); ?>
                                    </label>
                                    <textarea class="form-control" id="message" name="message" rows="6" required 
                                              placeholder="Please provide details about your inquiry..."></textarea>
                                    <div class="invalid-feedback"><?php echo __('contact_required'); ?></div>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i><?php echo __('contact_send'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="row g-4">
                    <!-- Contact Info Card -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light border-0">
                                <h4 class="card-title mb-0 text-primary">
                                    <i class="fas fa-info-circle me-2"></i><?php echo __('contact_info_title'); ?>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="contact-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="contact-icon me-3">
                                            <i class="fas fa-map-marker-alt text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold"><?php echo __('contact_location_title'); ?></h6>
                                            <p class="mb-0 text-muted"><?php echo __('contact_address'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="contact-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="contact-icon me-3">
                                            <i class="fas fa-phone text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold">Phone</h6>
                                            <p class="mb-0 text-muted">
                                                <a href="tel:<?php echo __('contact_phone_number'); ?>" class="text-decoration-none">
                                                    <?php echo __('contact_phone_number'); ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="contact-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="contact-icon me-3">
                                            <i class="fas fa-envelope text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold">Email</h6>
                                            <p class="mb-0 text-muted">
                                                <a href="mailto:<?php echo __('contact_email_address'); ?>" class="text-decoration-none">
                                                    <?php echo __('contact_email_address'); ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <div class="d-flex align-items-center">
                                        <div class="contact-icon me-3">
                                            <i class="fas fa-globe text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold">Website</h6>
                                            <p class="mb-0 text-muted">
                                                <a href="http://<?php echo __('contact_website'); ?>" class="text-decoration-none" target="_blank">
                                                    <?php echo __('contact_website'); ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Office Hours Card -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light border-0">
                                <h4 class="card-title mb-0 text-primary">
                                    <i class="fas fa-clock me-2"></i><?php echo __('contact_hours_title'); ?>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="hours-item mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold"><?php echo __('contact_hours_weekdays'); ?></span>
                                    </div>
                                </div>
                                <div class="hours-item mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold"><?php echo __('contact_hours_saturday'); ?></span>
                                    </div>
                                </div>
                                <div class="hours-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold text-muted"><?php echo __('contact_hours_sunday'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Departments Card -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light border-0">
                                <h4 class="card-title mb-0 text-primary">
                                    <i class="fas fa-building me-2"></i><?php echo __('contact_departments_title'); ?>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="dept-item mb-2">
                                    <i class="fas fa-user-graduate me-2 text-primary"></i>
                                    <span><?php echo __('contact_admissions_dept'); ?></span>
                                </div>
                                <div class="dept-item mb-2">
                                    <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                    <span><?php echo __('contact_academic_dept'); ?></span>
                                </div>
                                <div class="dept-item mb-2">
                                    <i class="fas fa-users me-2 text-primary"></i>
                                    <span><?php echo __('contact_student_dept'); ?></span>
                                </div>
                                <div class="dept-item">
                                    <i class="fas fa-dollar-sign me-2 text-primary"></i>
                                    <span><?php echo __('contact_finance_dept'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white border-0">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-map me-2"></i><?php echo __('contact_location_title'); ?>
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="map-container" style="height: 450px; position: relative;">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d995.7293093747251!2d29.367374199197382!3d-3.370406700000007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19c18300878f78f3%3A0x1b33c52222978898!2sJ9H9%2BRF6%2C%20Bujumbura!5e0!3m2!1sen!2sbi!4v1753294434900!5m2!1sen!2sbi" 
                                    width="100%" 
                                    height="100%" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i><?php echo __('contact_success'); ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo __('contact_error'); ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<style>
.contact-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--bs-primary-rgb), 0.1);
    border-radius: 50%;
    font-size: 1.2rem;
}

.contact-item {
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
}

.contact-item:last-child {
    border-bottom: none;
}

.hours-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.hours-item:last-child {
    border-bottom: none;
}

.dept-item {
    padding: 0.5rem 0;
    display: flex;
    align-items: center;
}

.form-control:focus, .form-select:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
    
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic form validation
        const requiredFields = ['fullName', 'email', 'subject', 'message'];
        let isValid = true;
        
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }
        });
        
        // Email validation
        const emailField = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailField.value && !emailRegex.test(emailField.value)) {
            emailField.classList.add('is-invalid');
            isValid = false;
        }
        
        if (isValid) {
            // Simulate form submission
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                // Show success message
                successToast.show();
                
                // Reset form
                contactForm.reset();
                contactForm.querySelectorAll('.is-valid').forEach(field => {
                    field.classList.remove('is-valid');
                });
                
                console.log('Contact form submitted:', {
                    name: document.getElementById('fullName').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    subject: document.getElementById('subject').value,
                    message: document.getElementById('message').value,
                    timestamp: new Date().toISOString()
                });
            }, 2000);
        } else {
            errorToast.show();
        }
    });
    
    // Real-time validation
    const inputs = contactForm.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
});
</script>

<?php
require_once '../../includes/footer.php';
?>