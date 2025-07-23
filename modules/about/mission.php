<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4"><?php echo __('mission_title'); ?></h1>
                <p class="lead mb-0">Shaping minds, building character, serving humanity</p>
            </div>
        </div>
    </div>
</section>

<!-- Mission Content Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Vision Section -->
                <div class="mb-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col-lg-2 text-center mb-4 mb-lg-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="bi bi-eye text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <h2 class="h3 text-primary mb-3"><?php echo __('vision_title'); ?></h2>
                                    <p class="text-muted mb-0 lead"><?php echo __('vision_content'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mission Section -->
                <div class="mb-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col-lg-2 text-center mb-4 mb-lg-0">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="bi bi-target text-success" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <h2 class="h3 text-success mb-3"><?php echo __('mission_section_title'); ?></h2>
                                    <p class="text-muted mb-4"><?php echo __('mission_content'); ?></p>
                                    
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-check2 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="mb-0"><?php echo __('mission_point_1'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-check2 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="mb-0"><?php echo __('mission_point_2'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-check2 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="mb-0"><?php echo __('mission_point_3'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-check2 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="mb-0"><?php echo __('mission_point_4'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Global Objective Section -->
                <div class="mb-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col-lg-2 text-center mb-4 mb-lg-0">
                                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="bi bi-globe text-warning" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <h2 class="h3 text-warning mb-3"><?php echo __('global_objective_title'); ?></h2>
                                    <p class="text-muted mb-0 lead"><?php echo __('global_objective_content'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Core Values Section -->
                <div class="row mt-5">
                    <div class="col-12 text-center mb-5">
                        <h2 class="h3 mb-4">Our Core Values</h2>
                        <p class="text-muted">The principles that guide everything we do</p>
                    </div>
                </div>
                
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center">
                            <div class="card-body p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-heart text-primary fs-4"></i>
                                </div>
                                <h5 class="card-title">Integrity</h5>
                                <p class="card-text text-muted">Upholding the highest standards of honesty and moral principles in all our endeavors.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center">
                            <div class="card-body p-4">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-trophy text-success fs-4"></i>
                                </div>
                                <h5 class="card-title">Excellence</h5>
                                <p class="card-text text-muted">Pursuing the highest quality in education, research, and service to our community.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center">
                            <div class="card-body p-4">
                                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-people text-info fs-4"></i>
                                </div>
                                <h5 class="card-title">Service</h5>
                                <p class="card-text text-muted">Dedicating ourselves to serving God, humanity, and our local and global communities.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="text-center mt-5">
                    <div class="bg-light p-5 rounded">
                        <h3 class="mb-3">Join Our Mission</h3>
                        <p class="text-muted mb-4">Be part of a community dedicated to academic excellence and Christian values.</p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="<?php echo BASE_PATH; ?>/modules/admissions/" class="btn btn-primary btn-lg">
                                <i class="bi bi-mortarboard me-2"></i><?php echo __('admissions'); ?>
                            </a>
                            <a href="<?php echo BASE_PATH; ?>/modules/academics/" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-book me-2"></i><?php echo __('academics'); ?>
                            </a>
                            <a href="<?php echo BASE_PATH; ?>/modules/about/history.php" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-clock-history me-2"></i>Our History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>