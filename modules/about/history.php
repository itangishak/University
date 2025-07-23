<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4"><?php echo __('history_title'); ?></h1>
                <p class="lead mb-0">Discover the inspiring journey of our institution</p>
            </div>
        </div>
    </div>
</section>

<!-- History Content Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Timeline Introduction -->
                <div class="text-center mb-5">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <h2 class="h3 mb-4">Our Foundation Story</h2>
                            <p class="text-muted">From vision to reality, learn about the establishment and development of Burundi Adventist University.</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-lg-3 mb-4">
                                <div class="sticky-top" style="top: 2rem;">
                                    <div class="bg-light p-4 rounded">
                                        <h5 class="h6 text-uppercase text-muted mb-3">Quick Facts</h5>
                                        <div class="mb-3">
                                            <i class="bi bi-geo-alt text-primary me-2"></i>
                                            <small>Two Campuses</small>
                                        </div>
                                        <div class="mb-3">
                                            <i class="bi bi-translate text-primary me-2"></i>
                                            <small>Bilingual Education</small>
                                        </div>
                                        <div class="mb-3">
                                            <i class="bi bi-book text-primary me-2"></i>
                                            <small>Modern Learning</small>
                                        </div>
                                        <div class="mb-3">
                                            <i class="bi bi-people text-primary me-2"></i>
                                            <small>Quality Education</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="content-text">
                                    <div class="text-content">
                                        <?php echo __('history_content'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Key Features Section -->
                <div class="row mt-5">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-geo-alt text-primary fs-4"></i>
                                </div>
                                <h5>Two Campus Locations</h5>
                                <p class="text-muted mb-0">Jabe campus in Mukaza commune and main campus at Kivoga in Bujumbura province.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-translate text-primary fs-4"></i>
                                </div>
                                <h5>Bilingual Education</h5>
                                <p class="text-muted mb-0">Instruction in both French and English to serve the diverse African continent.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-laptop text-primary fs-4"></i>
                                </div>
                                <h5>Modern Technology</h5>
                                <p class="text-muted mb-0">PowerPoint presentations, Moodle platform, and comprehensive digital resources.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-building text-primary fs-4"></i>
                                </div>
                                <h5>Campus Facilities</h5>
                                <p class="text-muted mb-0">Dormitories, cafeteria, physical and virtual libraries for comprehensive student life.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="text-center mt-5">
                    <div class="bg-light p-4 rounded">
                        <h4 class="mb-3">Ready to Join Our Community?</h4>
                        <p class="text-muted mb-4">Discover more about our programs and admission process.</p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="<?php echo BASE_PATH; ?>/modules/admissions/" class="btn btn-primary">
                                <i class="bi bi-mortarboard me-2"></i><?php echo __('admissions'); ?>
                            </a>
                            <a href="<?php echo BASE_PATH; ?>/modules/academics/" class="btn btn-outline-primary">
                                <i class="bi bi-book me-2"></i><?php echo __('academics'); ?>
                            </a>
                            <a href="<?php echo BASE_PATH; ?>/modules/contact/" class="btn btn-outline-secondary">
                                <i class="bi bi-envelope me-2"></i><?php echo __('contact'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>