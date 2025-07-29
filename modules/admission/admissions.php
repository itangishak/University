<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4"><?php echo __('admissions_title'); ?></h1>
                <p class="lead mb-4"><?php echo __('admissions_subtitle'); ?></p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <span class="badge bg-light text-success px-3 py-2 fs-6"><?php echo __('regular_student'); ?></span>
                    <span class="badge bg-light text-success px-3 py-2 fs-6"><?php echo __('part_time_student'); ?></span>
                    <span class="badge bg-light text-success px-3 py-2 fs-6"><?php echo __('free_student'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admission Requirements Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="text-center mb-5">
                    <h2 class="h3 mb-4"><?php echo __('admission_requirements_title'); ?></h2>
                    <p class="text-muted"><?php echo __('discover_admission_conditions'); ?></p>
                </div>

                <!-- First Year Admission -->
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg h-100 admission-card">
                            <div class="card-header bg-primary text-white text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-mortarboard" style="font-size: 3rem;"></i>
                                </div>
                                <h3 class="h4 mb-0"><?php echo __('first_year_admission_title'); ?></h3>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-muted mb-4"><?php echo __('first_year_admission_content'); ?></p>
                                <div class="requirements-list">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-check-circle text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo __('diploma_state'); ?></div>
                                            <small class="text-muted"><?php echo __('diploma_state_required'); ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-check-circle text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo __('equivalent_diploma'); ?></div>
                                            <small class="text-muted"><?php echo __('recognized_by_authority'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg h-100 admission-card">
                            <div class="card-header bg-warning text-white text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-shield-check" style="font-size: 3rem;"></i>
                                </div>
                                <h3 class="h4 mb-0"><?php echo __('general_requirements_title'); ?></h3>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-muted mb-4"><?php echo __('general_requirements_content'); ?></p>
                                <div class="alert alert-warning border-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong><?php echo __('important'); ?>:</strong> <?php echo __('ministry_compliance'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other Years & Transfer Students -->
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg h-100 admission-card">
                            <div class="card-header bg-success text-white text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-arrow-up-circle" style="font-size: 3rem;"></i>
                                </div>
                                <h3 class="h4 mb-0"><?php echo __('other_years_admission_title'); ?></h3>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-muted mb-4"><?php echo __('other_years_admission_content'); ?></p>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                </div>
                                <small class="text-success fw-bold"><?php echo __('previous_year_success_required'); ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg h-100 admission-card">
                            <div class="card-header bg-info text-white text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-arrow-left-right" style="font-size: 3rem;"></i>
                                </div>
                                <h3 class="h4 mb-0"><?php echo __('transfer_students_title'); ?></h3>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-muted mb-4"><?php echo __('transfer_students_content'); ?></p>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-university text-info me-2"></i>
                                    <small class="text-muted"><?php echo __('prerequisite_evaluation_needed'); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credit Recognition -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg admission-card">
                            <div class="card-header bg-purple text-white text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-award" style="font-size: 3rem;"></i>
                                </div>
                                <h3 class="h4 mb-0"><?php echo __('credit_recognition_title'); ?></h3>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-muted mb-4"><?php echo __('credit_recognition_content'); ?></p>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="bg-light p-3 rounded text-center">
                                            <i class="bi bi-people text-purple mb-2" style="font-size: 2rem;"></i>
                                            <div class="fw-bold"><?php echo __('department_council'); ?></div>
                                            <small class="text-muted"><?php echo __('official_evaluation'); ?></small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="bg-light p-3 rounded text-center">
                                            <i class="bi bi-calculator text-purple mb-2" style="font-size: 2rem;"></i>
                                            <div class="fw-bold"><?php echo __('credit_equivalence'); ?></div>
                                            <small class="text-muted"><?php echo __('academic_recognition'); ?></small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="bg-light p-3 rounded text-center">
                                            <i class="bi bi-file-earmark-check text-purple mb-2" style="font-size: 2rem;"></i>
                                            <div class="fw-bold"><?php echo __('validation'); ?></div>
                                            <small class="text-muted"><?php echo __('final_decision'); ?></small>
                                        </div>
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

<!-- Required Documents & Application Process -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="row g-4">
                    <!-- Required Documents -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                                    </div>
                                    <h4 class="h5 mb-0"><?php echo __('required_documents_title'); ?></h4>
                                </div>
                                <div class="documents-list">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-check-circle text-success me-3"></i>
                                        <span><?php echo __('diploma_state'); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-check-circle text-success me-3"></i>
                                        <span><?php echo __('academic_transcripts'); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-check-circle text-success me-3"></i>
                                        <span><?php echo __('identity_document'); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-check-circle text-success me-3"></i>
                                        <span><?php echo __('passport_photos'); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-3"></i>
                                        <span><?php echo __('application_form'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Process -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-list-check text-success fs-4"></i>
                                    </div>
                                    <h4 class="h5 mb-0"><?php echo __('application_process_title'); ?></h4>
                                </div>
                                <div class="process-steps">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 0.8rem;">1</div>
                                        <div>
                                            <div class="fw-bold"><?php echo __('submit_documents'); ?></div>
                                            <small class="text-muted"><?php echo __('complete_file_required'); ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 0.8rem;">2</div>
                                        <div>
                                            <div class="fw-bold"><?php echo __('review_application'); ?></div>
                                            <small class="text-muted"><?php echo __('administrative_evaluation'); ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 0.8rem;">3</div>
                                        <div>
                                            <div class="fw-bold"><?php echo __('interview_evaluation'); ?></div>
                                            <small class="text-muted"><?php echo __('candidate_meeting'); ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 0.8rem;">4</div>
                                        <div>
                                            <div class="fw-bold"><?php echo __('admission_decision'); ?></div>
                                            <small class="text-muted"><?php echo __('official_notification'); ?></small>
                                        </div>
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

<!-- Online Application Form Section -->
<section class="py-5 bg-success text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="text-center mb-5">
                    <h2 class="h3 mb-4"><?php echo __('online_application_title'); ?></h2>
                    <p class="lead mb-4"><?php echo __('online_application_subtitle'); ?></p>
                    
                    <!-- Quick Form Notice -->
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                            <div class="text-start flex-grow-1">
                                <h6 class="alert-heading mb-2"><?php echo __('quick_form_notice'); ?></h6>
                                <p class="mb-2"><?php echo __('quick_form_message'); ?></p>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo BASE_PATH; ?>/modules/auth/register.php" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-person-plus me-1"></i><?php echo __('create_account'); ?>
                                    </a>
                                    <a href="<?php echo BASE_PATH; ?>/modules/auth/login.php" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-box-arrow-in-right me-1"></i><?php echo __('login'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p class="mb-0"><?php echo __('form_instructions'); ?></p>
                </div>

                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <!-- Progress Bar -->
                        <div class="progress-container mb-5">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success progress-bar-animated" role="progressbar" style="width: 25%" id="progressBar"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <div class="step-indicator active" data-step="1">
                                    <div class="step-circle">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                    <small class="step-label"><?php echo __('step_1_title'); ?></small>
                                </div>
                                <div class="step-indicator" data-step="2">
                                    <div class="step-circle">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                    <small class="step-label"><?php echo __('step_2_title'); ?></small>
                                </div>
                                <div class="step-indicator" data-step="3">
                                    <div class="step-circle">
                                        <i class="bi bi-file-earmark-arrow-up"></i>
                                    </div>
                                    <small class="step-label"><?php echo __('step_3_title'); ?></small>
                                </div>
                                <div class="step-indicator" data-step="4">
                                    <div class="step-circle">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <small class="step-label"><?php echo __('step_4_title'); ?></small>
                                </div>
                            </div>
                        </div>

                        <form id="multiStepForm" action="#" method="POST" enctype="multipart/form-data">
                            <!-- Step 1: Personal Information -->
                            <div class="form-step active" id="step1">
                                <div class="step-header text-center mb-4">
                                    <h4 class="text-primary"><?php echo __('step_1_title'); ?></h4>
                                    <p class="text-muted"><?php echo __('form_instructions'); ?></p>
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="firstName" class="form-label"><?php echo __('first_name'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lastName" class="form-label"><?php echo __('last_name'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label"><?php echo __('email_address'); ?> <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label"><?php echo __('phone_number'); ?> <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="dateOfBirth" class="form-label"><?php echo __('date_of_birth'); ?> <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nationality" class="form-label"><?php echo __('nationality'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nationality" name="nationality" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Academic Information -->
                            <div class="form-step" id="step2">
                                <div class="step-header text-center mb-4">
                                    <h4 class="text-primary"><?php echo __('step_2_title'); ?></h4>
                                    <p class="text-muted"><?php echo __('select_faculty'); ?></p>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="faculty" class="form-label"><?php echo __('select_faculty'); ?> <span class="text-danger">*</span></label>
                                        <select class="form-select" id="faculty" name="faculty" required>
                                            <option value=""><?php echo __('select_faculty'); ?></option>
                                            <option value="theology"><?php echo __('theology_faculty'); ?></option>
                                            <option value="it_management"><?php echo __('it_management_faculty'); ?></option>
                                            <option value="entrepreneurship"><?php echo __('entrepreneurship_faculty'); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="program" class="form-label"><?php echo __('select_program'); ?> <span class="text-danger">*</span></label>
                                        <select class="form-select" id="program" name="program" required>
                                            <option value=""><?php echo __('select_program'); ?></option>
                                            <option value="bachelor"><?php echo __('bachelor_degree'); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="previousEducation" class="form-label"><?php echo __('previous_education'); ?></label>
                                    <textarea class="form-control" id="previousEducation" name="previousEducation" rows="4" placeholder="<?php echo __('describe_previous_education'); ?>"></textarea>
                                </div>
                            </div>

                            <!-- Step 3: Document Upload -->
                            <div class="form-step" id="step3">
                                <div class="step-header text-center mb-4">
                                    <h4 class="text-primary"><?php echo __('step_3_title'); ?></h4>
                                    <p class="text-muted"><?php echo __('file_upload_note'); ?></p>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="diploma" class="form-label"><?php echo __('upload_diploma'); ?> <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="diploma" name="diploma" accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="transcripts" class="form-label"><?php echo __('upload_transcripts'); ?> <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="transcripts" name="transcripts" accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="idDocument" class="form-label"><?php echo __('upload_id'); ?> <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="idDocument" name="idDocument" accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="photo" class="form-label"><?php echo __('upload_photo'); ?> <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="photo" name="photo" accept=".jpg,.jpeg,.png" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="additionalInfo" class="form-label"><?php echo __('additional_info'); ?></label>
                                    <textarea class="form-control" id="additionalInfo" name="additionalInfo" rows="4" placeholder="<?php echo __('any_additional_information'); ?>"></textarea>
                                </div>
                            </div>

                            <!-- Step 4: Review -->
                            <div class="form-step" id="step4">
                                <div class="step-header text-center mb-4">
                                    <h4 class="text-primary"><?php echo __('step_4_title'); ?></h4>
                                    <p class="text-muted"><?php echo __('review_application'); ?></p>
                                </div>

                                <div class="review-section">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary"><?php echo __('personal_information'); ?></h6>
                                            <div id="reviewPersonal"></div>
                                        </div>
                                    </div>
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary"><?php echo __('academic_information'); ?></h6>
                                            <div id="reviewAcademic"></div>
                                        </div>
                                    </div>
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary"><?php echo __('upload_documents'); ?></h6>
                                            <div id="reviewDocuments"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="form-navigation d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" id="prevBtn" style="display: none;">
                                    <i class="bi bi-arrow-left me-2"></i><?php echo __('previous_step'); ?>
                                </button>
                                <button type="button" class="btn btn-primary" id="nextBtn">
                                    <?php echo __('next_step'); ?><i class="bi bi-arrow-right ms-2"></i>
                                </button>
                                <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                    <i class="bi bi-send me-2"></i><?php echo __('submit_application'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h3 mb-4"><?php echo __('contact_admissions_title'); ?></h2>
                <p class="lead mb-4"><?php echo __('admission_team_support'); ?></p>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 mb-3">
                                <i class="bi bi-telephone fs-4"></i>
                            </div>
                            <div class="fw-bold"><?php echo __('telephone'); ?></div>
                            <small>+257 79 155 869</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 mb-3">
                                <i class="bi bi-envelope fs-4"></i>
                            </div>
                            <div class="fw-bold"><?php echo __('email'); ?></div>
                            <small>admissions@uab.edu.bi</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 mb-3">
                                <i class="bi bi-geo-alt fs-4"></i>
                            </div>
                            <div class="fw-bold"><?php echo __('address'); ?></div>
                            <small><?php echo __('uab_campus_burundi'); ?></small>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="#applicationForm" class="btn btn-light btn-lg">
                        <i class="bi bi-file-earmark-text me-2"></i><?php echo __('apply_now'); ?>
                    </a>
                    <a href="<?php echo BASE_PATH; ?>/modules/faculty/faculties.php" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-building me-2"></i><?php echo __('our_faculties'); ?>
                    </a>
                    <a href="<?php echo BASE_PATH; ?>/modules/contact/contact.php" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-envelope me-2"></i><?php echo __('contact'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.admission-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.admission-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
}

.text-purple {
    color: #6f42c1 !important;
}

.bg-purple {
    background-color: #6f42c1 !important;
}

.process-steps .d-flex:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 15px;
    top: 45px;
    width: 2px;
    height: 20px;
    background-color: #dee2e6;
}

.process-steps {
    position: relative;
}

/* Multi-step form styles */
.step-indicator {
    text-align: center;
    flex: 1;
    position: relative;
}

.step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    border: 3px solid #e9ecef;
}

.step-indicator.active .step-circle {
    background-color: #28a745;
    color: white;
    border-color: #28a745;
    transform: scale(1.1);
}

.step-indicator.completed .step-circle {
    background-color: #20c997;
    color: white;
    border-color: #20c997;
}

.step-label {
    font-weight: 500;
    color: #6c757d;
    transition: color 0.3s ease;
}

.step-indicator.active .step-label {
    color: #28a745;
    font-weight: 600;
}

.form-step {
    display: none;
    opacity: 0;
    transform: translateX(50px);
    transition: all 0.4s ease;
    min-height: 400px;
}

.form-step.active {
    display: block;
    opacity: 1;
    transform: translateX(0);
}

.form-step.slide-out-left {
    transform: translateX(-50px);
    opacity: 0;
}

.form-step.slide-in-right {
    transform: translateX(50px);
    opacity: 0;
}

.step-header h4 {
    margin-bottom: 10px;
}

.form-navigation {
    border-top: 1px solid #e9ecef;
    padding-top: 20px;
}

.review-section .card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.review-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}

.review-item:last-child {
    border-bottom: none;
}

.review-label {
    font-weight: 500;
    color: #495057;
}

.review-value {
    color: #212529;
}

/* Animations */
@keyframes slideInRight {
    from {
        transform: translateX(100px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideInLeft {
    from {
        transform: translateX(-100px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.slide-in-right {
    animation: slideInRight 0.4s ease;
}

.slide-in-left {
    animation: slideInLeft 0.4s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progressBar');

    // Step navigation
    nextBtn.addEventListener('click', function() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                goToStep(currentStep + 1);
            }
        }
    });

    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    });

    function goToStep(step) {
        const currentStepEl = document.getElementById(`step${currentStep}`);
        const nextStepEl = document.getElementById(`step${step}`);
        const direction = step > currentStep ? 'forward' : 'backward';

        // Animate out current step
        currentStepEl.classList.add(direction === 'forward' ? 'slide-out-left' : 'slide-in-right');
        
        setTimeout(() => {
            currentStepEl.classList.remove('active');
            currentStepEl.style.display = 'none';
            
            // Update current step
            currentStep = step;
            
            // Show next step with animation
            nextStepEl.style.display = 'block';
            nextStepEl.classList.add(direction === 'forward' ? 'slide-in-right' : 'slide-in-left');
            
            setTimeout(() => {
                nextStepEl.classList.add('active');
                nextStepEl.classList.remove('slide-in-right', 'slide-in-left');
            }, 50);
            
            // Update UI
            updateStepIndicators();
            updateProgressBar();
            updateNavigationButtons();
            
            if (step === totalSteps) {
                populateReviewSection();
            }
        }, 200);
    }

    function updateStepIndicators() {
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const stepNumber = index + 1;
            indicator.classList.remove('active', 'completed');
            
            if (stepNumber === currentStep) {
                indicator.classList.add('active');
            } else if (stepNumber < currentStep) {
                indicator.classList.add('completed');
            }
        });
    }

    function updateProgressBar() {
        const progress = (currentStep / totalSteps) * 100;
        progressBar.style.width = progress + '%';
    }

    function updateNavigationButtons() {
        prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-block';
        
        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-block';
        } else {
            nextBtn.style.display = 'inline-block';
            submitBtn.style.display = 'none';
        }
    }

    function validateCurrentStep() {
        const currentStepEl = document.getElementById(`step${currentStep}`);
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            // Show error message
            showNotification('Please fill in all required fields', 'error');
        }

        return isValid;
    }

    function populateReviewSection() {
        // Personal Information
        const personalHtml = `
            <div class="review-item">
                <span class="review-label"><?php echo __('first_name'); ?>:</span>
                <span class="review-value">${document.getElementById('firstName').value}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('last_name'); ?>:</span>
                <span class="review-value">${document.getElementById('lastName').value}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('email_address'); ?>:</span>
                <span class="review-value">${document.getElementById('email').value}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('phone_number'); ?>:</span>
                <span class="review-value">${document.getElementById('phone').value}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('date_of_birth'); ?>:</span>
                <span class="review-value">${document.getElementById('dateOfBirth').value}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('nationality'); ?>:</span>
                <span class="review-value">${document.getElementById('nationality').value}</span>
            </div>
        `;
        document.getElementById('reviewPersonal').innerHTML = personalHtml;

        // Academic Information
        const facultySelect = document.getElementById('faculty');
        const programSelect = document.getElementById('program');
        const academicHtml = `
            <div class="review-item">
                <span class="review-label"><?php echo __('select_faculty'); ?>:</span>
                <span class="review-value">${facultySelect.options[facultySelect.selectedIndex].text}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('select_program'); ?>:</span>
                <span class="review-value">${programSelect.options[programSelect.selectedIndex].text}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('previous_education'); ?>:</span>
                <span class="review-value">${document.getElementById('previousEducation').value || 'N/A'}</span>
            </div>
        `;
        document.getElementById('reviewAcademic').innerHTML = academicHtml;

        // Documents
        const documentsHtml = `
            <div class="review-item">
                <span class="review-label"><?php echo __('upload_diploma'); ?>:</span>
                <span class="review-value">${document.getElementById('diploma').files[0]?.name || 'Not uploaded'}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('upload_transcripts'); ?>:</span>
                <span class="review-value">${document.getElementById('transcripts').files[0]?.name || 'Not uploaded'}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('upload_id'); ?>:</span>
                <span class="review-value">${document.getElementById('idDocument').files[0]?.name || 'Not uploaded'}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('upload_photo'); ?>:</span>
                <span class="review-value">${document.getElementById('photo').files[0]?.name || 'Not uploaded'}</span>
            </div>
            <div class="review-item">
                <span class="review-label"><?php echo __('additional_info'); ?>:</span>
                <span class="review-value">${document.getElementById('additionalInfo').value || 'N/A'}</span>
            </div>
        `;
        document.getElementById('reviewDocuments').innerHTML = documentsHtml;
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

    // Form submission
    document.getElementById('multiStepForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateCurrentStep()) {
            // Show success message
            showNotification('<?php echo __('application_success'); ?>', 'success');
            
            // Here you would normally submit the form data to the server
            console.log('Form submitted successfully!');
        }
    });

    // Add custom validation styles
    const style = document.createElement('style');
    style.textContent = `
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
        .is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
    `;
    document.head.appendChild(style);
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>