<?php
require_once '../../includes/header.php';
?>

<div class="container-fluid" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem 0;">
    <div class="container">
        <!-- Page Header -->
        <div class="text-center mb-5">
            <div class="d-inline-block p-3 bg-primary rounded-circle mb-3">
                <i class="fas fa-question-circle text-white" style="font-size: 2rem;"></i>
            </div>
            <h1 class="display-4 fw-bold text-primary mb-3"><?php echo __('faq_title'); ?></h1>
            <p class="lead text-muted mb-4"><?php echo __('faq_subtitle'); ?></p>
            
            <!-- Search Box -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="faqSearch" 
                               placeholder="<?php echo __('faq_search_placeholder'); ?>" 
                               style="box-shadow: none;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                    <button class="btn btn-outline-primary category-filter active" data-category="all">
                        <i class="fas fa-list me-2"></i>All
                    </button>
                    <button class="btn btn-outline-primary category-filter" data-category="general">
                        <i class="fas fa-info-circle me-2"></i><?php echo __('faq_category_general'); ?>
                    </button>
                    <button class="btn btn-outline-primary category-filter" data-category="admission">
                        <i class="fas fa-user-graduate me-2"></i><?php echo __('faq_category_admission'); ?>
                    </button>
                    <button class="btn btn-outline-primary category-filter" data-category="academic">
                        <i class="fas fa-graduation-cap me-2"></i><?php echo __('faq_category_academic'); ?>
                    </button>
                    <button class="btn btn-outline-primary category-filter" data-category="student_life">
                        <i class="fas fa-users me-2"></i><?php echo __('faq_category_student_life'); ?>
                    </button>
                    <button class="btn btn-outline-primary category-filter" data-category="financial">
                        <i class="fas fa-dollar-sign me-2"></i><?php echo __('faq_category_financial'); ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- FAQ Items -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div id="faqAccordion">
                    <!-- General Questions -->
                    <div class="faq-item mb-3" data-category="general">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0" id="faq1">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-start w-100 text-decoration-none collapsed" 
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" 
                                            aria-expanded="false" aria-controls="collapse1">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-university text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold text-dark"><?php echo __('faq_q1'); ?></span>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fas fa-chevron-down transition-all"></i>
                                            </div>
                                        </div>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse1" class="collapse" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                                <div class="card-body pt-0">
                                    <p class="text-muted mb-0"><?php echo __('faq_a1'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item mb-3" data-category="academic">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0" id="faq2">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-start w-100 text-decoration-none collapsed" 
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" 
                                            aria-expanded="false" aria-controls="collapse2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-book text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold text-dark"><?php echo __('faq_q2'); ?></span>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fas fa-chevron-down transition-all"></i>
                                            </div>
                                        </div>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse2" class="collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                                <div class="card-body pt-0">
                                    <p class="text-muted mb-0"><?php echo __('faq_a2'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item mb-3" data-category="admission">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0" id="faq3">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-start w-100 text-decoration-none collapsed" 
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" 
                                            aria-expanded="false" aria-controls="collapse3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-user-graduate text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold text-dark"><?php echo __('faq_q3'); ?></span>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fas fa-chevron-down transition-all"></i>
                                            </div>
                                        </div>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse3" class="collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                                <div class="card-body pt-0">
                                    <p class="text-muted mb-0"><?php echo __('faq_a3'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item mb-3" data-category="admission">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0" id="faq4">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-start w-100 text-decoration-none collapsed" 
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" 
                                            aria-expanded="false" aria-controls="collapse4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-clipboard-check text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold text-dark"><?php echo __('faq_q4'); ?></span>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fas fa-chevron-down transition-all"></i>
                                            </div>
                                        </div>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse4" class="collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                                <div class="card-body pt-0">
                                    <p class="text-muted mb-0"><?php echo __('faq_a4'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item mb-3" data-category="financial">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0" id="faq5">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-start w-100 text-decoration-none collapsed" 
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" 
                                            aria-expanded="false" aria-controls="collapse5">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-scholarship text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold text-dark"><?php echo __('faq_q5'); ?></span>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fas fa-chevron-down transition-all"></i>
                                            </div>
                                        </div>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse5" class="collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                                <div class="card-body pt-0">
                                    <p class="text-muted mb-0"><?php echo __('faq_a5'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item mb-3" data-category="general">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0" id="faq6">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-start w-100 text-decoration-none collapsed" 
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" 
                                            aria-expanded="false" aria-controls="collapse6">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-map-marker-alt text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold text-dark"><?php echo __('faq_q6'); ?></span>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fas fa-chevron-down transition-all"></i>
                                            </div>
                                        </div>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse6" class="collapse" aria-labelledby="faq6" data-bs-parent="#faqAccordion">
                                <div class="card-body pt-0">
                                    <p class="text-muted mb-0"><?php echo __('faq_a6'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item mb-3" data-category="student_life">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0" id="faq7">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-start w-100 text-decoration-none collapsed" 
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" 
                                            aria-expanded="false" aria-controls="collapse7">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-home text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold text-dark"><?php echo __('faq_q7'); ?></span>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fas fa-chevron-down transition-all"></i>
                                            </div>
                                        </div>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse7" class="collapse" aria-labelledby="faq7" data-bs-parent="#faqAccordion">
                                <div class="card-body pt-0">
                                    <p class="text-muted mb-0"><?php echo __('faq_a7'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item mb-3" data-category="general">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0" id="faq8">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-start w-100 text-decoration-none collapsed" 
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" 
                                            aria-expanded="false" aria-controls="collapse8">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-phone text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold text-dark"><?php echo __('faq_q8'); ?></span>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fas fa-chevron-down transition-all"></i>
                                            </div>
                                        </div>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse8" class="collapse" aria-labelledby="faq8" data-bs-parent="#faqAccordion">
                                <div class="card-body pt-0">
                                    <p class="text-muted mb-0"><?php echo __('faq_a8'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Results Message -->
                <div id="noResults" class="text-center py-5" style="display: none;">
                    <div class="mb-3">
                        <i class="fas fa-search text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3"><?php echo __('faq_no_results'); ?></h4>
                    <p class="text-muted"><?php echo __('faq_contact_support'); ?></p>
                    <a href="<?php echo BASE_PATH; ?>/modules/contact/contact.php" class="btn btn-primary">
                        <i class="fas fa-envelope me-2"></i><?php echo __('faq_contact_us'); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Support Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-primary text-white border-0 shadow-lg">
                    <div class="card-body text-center py-5">
                        <h3 class="mb-3"><?php echo __('faq_contact_support'); ?></h3>
                        <p class="mb-4 opacity-75">Our support team is here to help you with any questions not covered in our FAQ.</p>
                        <a href="<?php echo BASE_PATH; ?>/modules/contact/contact.php" class="btn btn-light btn-lg">
                            <i class="fas fa-envelope me-2"></i><?php echo __('faq_contact_us'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.transition-all {
    transition: all 0.3s ease;
}

.btn-link[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}

.category-filter {
    transition: all 0.3s ease;
}

.category-filter.active {
    background-color: var(--bs-primary) !important;
    color: white !important;
    border-color: var(--bs-primary) !important;
}

.faq-item {
    transition: all 0.3s ease;
}

.faq-item:hover {
    transform: translateY(-2px);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.input-group-lg .form-control {
    border-radius: 0.5rem;
}

.input-group-lg .input-group-text {
    border-radius: 0.5rem 0 0 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('faqSearch');
    const categoryFilters = document.querySelectorAll('.category-filter');
    const faqItems = document.querySelectorAll('.faq-item');
    const noResults = document.getElementById('noResults');
    
    let currentCategory = 'all';
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterFAQs(searchTerm, currentCategory);
    });
    
    // Category filter functionality
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            // Update active category
            categoryFilters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
            currentCategory = this.dataset.category;
            const searchTerm = searchInput.value.toLowerCase();
            filterFAQs(searchTerm, currentCategory);
        });
    });
    
    function filterFAQs(searchTerm, category) {
        let visibleCount = 0;
        
        faqItems.forEach(item => {
            const itemCategory = item.dataset.category;
            const questionText = item.querySelector('.fw-semibold').textContent.toLowerCase();
            const answerText = item.querySelector('.card-body p').textContent.toLowerCase();
            
            const matchesSearch = searchTerm === '' || 
                                questionText.includes(searchTerm) || 
                                answerText.includes(searchTerm);
            
            const matchesCategory = category === 'all' || itemCategory === category;
            
            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    }
    
    // Smooth scroll to opened accordion
    const accordionButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            setTimeout(() => {
                if (this.getAttribute('aria-expanded') === 'true') {
                    this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 350);
        });
    });
});
</script>

<?php
require_once '../../includes/footer.php';
?>