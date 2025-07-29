<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4"><?php echo __('faculties_title'); ?></h1>
                <p class="lead mb-4"><?php echo __('faculties_intro'); ?></p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <span class="badge bg-light text-primary px-3 py-2 fs-6"><?php echo __('bachelor_degree'); ?></span>
                    <span class="badge bg-light text-primary px-3 py-2 fs-6"><?php echo __('credits_required'); ?></span>
                    <span class="badge bg-light text-primary px-3 py-2 fs-6"><?php echo __('duration_3_years'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Faculties Section -->
<div class="container-fluid" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem 0;">
<section class="py-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Faculty of Theology -->
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-lg faculty-card">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-book-half" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="h4 mb-0"><?php echo __('faculty_theology'); ?></h3>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted mb-4"><?php echo __('faculty_theology_desc'); ?></p>
                        
                        <h6 class="fw-bold mb-3"><?php echo __('departments'); ?>:</h6>
                        <div class="features-list">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('department_theology'); ?></small>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3 mt-4"><?php echo __('key_subjects'); ?>:</h6>
                        <div class="features-list">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('biblical_studies'); ?></small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('church_history'); ?></small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('pastoral_ministry'); ?></small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('adventist_theology'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <div class="d-grid">
                            <a href="#" class="btn btn-outline-primary"><?php echo __('learn_more'); ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faculty of Economic Sciences and Management -->
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-lg faculty-card">
                    <div class="card-header bg-success text-white text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-graph-up-arrow" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="h4 mb-0"><?php echo __('faculty_economics_management'); ?></h3>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted mb-4"><?php echo __('faculty_economics_management_desc'); ?></p>
                        
                        <h6 class="fw-bold mb-3"><?php echo __('departments'); ?>:</h6>
                        <div class="features-list">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('department_management_business'); ?></small>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3 mt-4"><?php echo __('specialization_option'); ?>:</h6>
                        <div class="features-list">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-star text-warning me-2"></i>
                                <small><?php echo __('option_entrepreneurship'); ?></small>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3 mt-4"><?php echo __('key_subjects'); ?>:</h6>
                        <div class="features-list">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('business_administration'); ?></small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('economics'); ?></small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('entrepreneurship'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <div class="d-grid">
                            <a href="#" class="btn btn-outline-success"><?php echo __('learn_more'); ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faculty of Sciences and Technologies -->
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-lg faculty-card">
                    <div class="card-header bg-warning text-white text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-cpu" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="h4 mb-0"><?php echo __('faculty_sciences_technologies'); ?></h3>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted mb-4"><?php echo __('faculty_sciences_technologies_desc'); ?></p>
                        
                        <h6 class="fw-bold mb-3"><?php echo __('departments'); ?>:</h6>
                        <div class="features-list">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('department_computer_sciences'); ?></small>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3 mt-4"><?php echo __('specialization_option'); ?>:</h6>
                        <div class="features-list">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-star text-warning me-2"></i>
                                <small><?php echo __('option_management_information_systems'); ?></small>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3 mt-4"><?php echo __('key_subjects'); ?>:</h6>
                        <div class="features-list">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('programming_development'); ?></small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('information_systems'); ?></small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small><?php echo __('database_management'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <div class="d-grid">
                            <a href="#" class="btn btn-outline-warning"><?php echo __('learn_more'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Academic Information Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="text-center mb-5">
                    <h2 class="h3 mb-4">Informations Académiques</h2>
                    <p class="text-muted">Découvrez notre approche pédagogique et notre système d'évaluation</p>
                </div>

                <!-- Training Organization -->
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-diagram-3 text-primary fs-4"></i>
                                    </div>
                                    <h4 class="h5 mb-0"><?php echo __('training_organization_title'); ?></h4>
                                </div>
                                <p class="text-muted mb-3"><?php echo __('training_organization_content'); ?></p>
                                <div class="bg-light p-3 rounded">
                                    <small class="text-muted"><strong>Modèle BMD:</strong> Baccalauréat → Master → Doctorat</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-clock text-success fs-4"></i>
                                    </div>
                                    <h4 class="h5 mb-0"><?php echo __('credit_system_title'); ?></h4>
                                </div>
                                <p class="text-muted mb-3"><?php echo __('credit_system_content'); ?></p>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="bg-light p-2 rounded text-center">
                                            <div class="fw-bold text-success">15h</div>
                                            <small class="text-muted">Présentiel</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-light p-2 rounded text-center">
                                            <div class="fw-bold text-info">10h</div>
                                            <small class="text-muted">Personnel</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pedagogical Approach & Evaluation -->
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-mortarboard text-info fs-4"></i>
                                    </div>
                                    <h4 class="h5 mb-0"><?php echo __('pedagogical_approach_title'); ?></h4>
                                </div>
                                <p class="text-muted mb-3"><?php echo __('pedagogical_approach_content'); ?></p>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info bg-opacity-10 text-info">Théorique</span>
                                    <span class="badge bg-info bg-opacity-10 text-info">Pratique</span>
                                    <span class="badge bg-info bg-opacity-10 text-info">Laboratoire</span>
                                    <span class="badge bg-info bg-opacity-10 text-info">Travaux dirigés</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-clipboard-check text-warning fs-4"></i>
                                    </div>
                                    <h4 class="h5 mb-0"><?php echo __('evaluation_title'); ?></h4>
                                </div>
                                <p class="text-muted mb-3"><?php echo __('evaluation_content'); ?></p>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <div class="bg-light p-2 rounded mb-2">
                                            <small class="fw-bold text-primary"><?php echo __('continuous_evaluation'); ?></small>
                                            <div class="small text-muted">Présence, participation, travaux</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="bg-light p-2 rounded">
                                            <small class="fw-bold text-success"><?php echo __('summative_evaluation'); ?></small>
                                            <div class="small text-muted">Examens finaux, rapports</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Internship & Calendar -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-purple bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-briefcase text-purple fs-4" style="color: #6f42c1 !important;"></i>
                                    </div>
                                    <h4 class="h5 mb-0"><?php echo __('internship_title'); ?></h4>
                                </div>
                                <p class="text-muted"><?php echo __('internship_content'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-calendar-event text-danger fs-4"></i>
                                    </div>
                                    <h4 class="h5 mb-0"><?php echo __('academic_calendar_title'); ?></h4>
                                </div>
                                <p class="text-muted mb-3"><?php echo __('academic_calendar_content'); ?></p>
                                <div class="d-flex justify-content-between">
                                    <div class="text-center">
                                        <div class="fw-bold text-danger">Février</div>
                                        <small class="text-muted">Début</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="fw-bold text-success">Décembre</div>
                                        <small class="text-muted">Fin</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h3 mb-4">Prêt à rejoindre nos facultés ?</h2>
                <p class="lead mb-4">Découvrez nos programmes et commencez votre parcours académique avec nous.</p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="<?php echo BASE_PATH; ?>/modules/admission/admissions.php" class="btn btn-light btn-lg">
                        <i class="bi bi-mortarboard me-2"></i><?php echo __('admissions'); ?>
                    </a>
                    <a href="<?php echo BASE_PATH; ?>/modules/contact/" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-envelope me-2"></i><?php echo __('contact'); ?>
                    </a>
                    <a href="<?php echo BASE_PATH; ?>/modules/about/mission.php" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-bullseye me-2"></i>Notre Mission
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.faculty-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.faculty-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
}

.text-purple {
    color: #6f42c1 !important;
}

.bg-purple {
    background-color: #6f42c1 !important;
}
</style>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>