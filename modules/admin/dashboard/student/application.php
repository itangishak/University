<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../../includes/Auth.php';
require_once __DIR__ . '/../../../../includes/NotificationSystem.php';

$auth = new Auth();
$auth->requireRole('student');

$user = $auth->getCurrentUser();
$db = Database::getInstance();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
        } else {
            $input = $_POST;
        }
        $step = $input['step'] ?? 1;
        $action = $input['action'] ?? 'save';

        // Get or create application
        $application = $db->fetch(
            "SELECT * FROM application_forms WHERE applicant_user_id = ? ORDER BY created_at DESC LIMIT 1",
            [$user['id']]
        );

        if (!$application && $action !== 'create') {
            throw new Exception('No application found. Please create a new application first.');
        }

        $db->beginTransaction();
        $responseExtra = [];

        switch ($action) {
            case 'create':
                $applicationId = createNewApplication($input, $user['id'], $db);
                break;
            case 'save':
                $applicationId = $application['id'];
                saveApplicationStep($applicationId, $step, $input, $db);
                break;
            case 'upload_document':
                $applicationId = $application['id'];
                if (!isset($_FILES['file'])) {
                    throw new Exception('No file uploaded');
                }
                $documentCode = $input['document_code'] ?? 'application_fee_receipt';
                // Implemented below in helper function
                $docInfo = uploadApplicationDocument($applicationId, $documentCode, $_FILES['file'], $db, $user['preferred_language'] ?? 'en');
                $responseExtra['document'] = $docInfo;
                break;
            case 'list_documents':
                $applicationId = $application['id'];
                $docs = listApplicationDocuments($applicationId, $db, $user['preferred_language'] ?? 'en');
                $responseExtra['documents'] = $docs;
                $responseExtra['count'] = count($docs);
                break;
            case 'submit':
                $applicationId = $application['id'];
                submitApplication($applicationId, $db);
                break;
            default:
                throw new Exception('Unsupported action');
        }

        $db->commit();

        echo json_encode(array_merge([
            'success' => true,
            'application_id' => $applicationId,
            'message' => 'OK'
        ], $responseExtra));
    } catch (Exception $e) {
        $db->rollback();
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

// Get current application and data
$application = $db->fetch(
    "SELECT af.*, p.program_code, pt.name as program_name 
     FROM application_forms af
     LEFT JOIN programs p ON af.program_id = p.id
     LEFT JOIN program_translations pt ON p.id = pt.program_id AND pt.language_code = ?
     WHERE af.applicant_user_id = ? 
     ORDER BY af.created_at DESC LIMIT 1",
    [$user['preferred_language'], $user['id']]
);

// Get available programs
$programs = $db->fetchAll(
    "SELECT p.id, p.program_code, pt.name, pt.description 
     FROM programs p
     JOIN program_translations pt ON p.id = pt.program_id AND pt.language_code = ?
     ORDER BY pt.name",
    [$user['preferred_language']]
);

// Get application data for each step
$personalInfo = null;
$academicHistory = [];
$workExperience = [];
$additionalInfo = null;

if ($application) {
    $personalInfo = $db->fetch(
        "SELECT * FROM application_personal_info WHERE application_id = ?",
        [$application['id']]
    );
    
    $academicHistory = $db->fetchAll(
        "SELECT * FROM application_academic_history WHERE application_id = ? ORDER BY start_date DESC",
        [$application['id']]
    );
    
    $workExperience = $db->fetchAll(
        "SELECT * FROM application_work_experience WHERE application_id = ? ORDER BY start_date DESC",
        [$application['id']]
    );
    
    $additionalInfo = $db->fetch(
        "SELECT * FROM application_additional_info WHERE application_id = ?",
        [$application['id']]
    );
}

function createNewApplication($data, $userId, $db) {
    $sql = "INSERT INTO application_forms (applicant_user_id, program_id, application_year, status) 
            VALUES (?, ?, ?, 'draft')";
    
    $db->execute($sql, [
        $userId,
        $data['program_id'],
        date('Y')
    ]);
    
    return $db->lastInsertId();
}

function saveApplicationStep($applicationId, $step, $data, $db) {
    switch ($step) {
        case 1:
            savePersonalInfo($applicationId, $data, $db);
            break;
        case 2:
            saveAcademicHistory($applicationId, $data, $db);
            break;
        case 3:
            saveWorkExperience($applicationId, $data, $db);
            break;
        case 4:
            saveAdditionalInfo($applicationId, $data, $db);
            break;
    }
}

function savePersonalInfo($applicationId, $data, $db) {
    $sql = "INSERT INTO application_personal_info (
                application_id, date_of_birth, gender, nationality, passport_number,
                phone_number, emergency_contact_name, emergency_contact_phone,
                address_line1, address_line2, city, state_province, postal_code, country
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                date_of_birth = VALUES(date_of_birth),
                gender = VALUES(gender),
                nationality = VALUES(nationality),
                passport_number = VALUES(passport_number),
                phone_number = VALUES(phone_number),
                emergency_contact_name = VALUES(emergency_contact_name),
                emergency_contact_phone = VALUES(emergency_contact_phone),
                address_line1 = VALUES(address_line1),
                address_line2 = VALUES(address_line2),
                city = VALUES(city),
                state_province = VALUES(state_province),
                postal_code = VALUES(postal_code),
                country = VALUES(country)";
    
    $db->execute($sql, [
        $applicationId,
        $data['date_of_birth'] ?? null,
        $data['gender'] ?? null,
        $data['nationality'] ?? null,
        $data['passport_number'] ?? null,
        $data['phone_number'] ?? null,
        $data['emergency_contact_name'] ?? null,
        $data['emergency_contact_phone'] ?? null,
        $data['address_line1'] ?? null,
        $data['address_line2'] ?? null,
        $data['city'] ?? null,
        $data['state_province'] ?? null,
        $data['postal_code'] ?? null,
        $data['country'] ?? null
    ]);
}

function saveAcademicHistory($applicationId, $data, $db) {
    // Delete existing records
    $db->execute("DELETE FROM application_academic_history WHERE application_id = ?", [$applicationId]);
    
    // Insert new records
    if (isset($data['academic_records']) && is_array($data['academic_records'])) {
        foreach ($data['academic_records'] as $record) {
            $sql = "INSERT INTO application_academic_history (
                        application_id, institution_name, degree_type, field_of_study,
                        start_date, end_date, gpa, gpa_scale, is_current
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $db->execute($sql, [
                $applicationId,
                $record['institution_name'] ?? null,
                $record['degree_type'] ?? null,
                $record['field_of_study'] ?? null,
                $record['start_date'] ?? null,
                $record['end_date'] ?? null,
                $record['gpa'] ?? null,
                $record['gpa_scale'] ?? 4.0,
                $record['is_current'] ?? false
            ]);
        }
    }
}

function saveWorkExperience($applicationId, $data, $db) {
    // Delete existing records
    $db->execute("DELETE FROM application_work_experience WHERE application_id = ?", [$applicationId]);
    
    // Insert new records
    if (isset($data['work_records']) && is_array($data['work_records'])) {
        foreach ($data['work_records'] as $record) {
            $sql = "INSERT INTO application_work_experience (
                        application_id, company_name, position_title, start_date,
                        end_date, is_current, description
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $db->execute($sql, [
                $applicationId,
                $record['company_name'] ?? null,
                $record['position_title'] ?? null,
                $record['start_date'] ?? null,
                $record['end_date'] ?? null,
                $record['is_current'] ?? false,
                $record['description'] ?? null
            ]);
        }
    }
}

function saveAdditionalInfo($applicationId, $data, $db) {
    $sql = "INSERT INTO application_additional_info (
                application_id, personal_statement, why_this_program, career_goals,
                extracurricular_activities, awards_honors, languages_spoken, special_needs
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                personal_statement = VALUES(personal_statement),
                why_this_program = VALUES(why_this_program),
                career_goals = VALUES(career_goals),
                extracurricular_activities = VALUES(extracurricular_activities),
                awards_honors = VALUES(awards_honors),
                languages_spoken = VALUES(languages_spoken),
                special_needs = VALUES(special_needs)";
    
    $db->execute($sql, [
        $applicationId,
        $data['personal_statement'] ?? null,
        $data['why_this_program'] ?? null,
        $data['career_goals'] ?? null,
        $data['extracurricular_activities'] ?? null,
        $data['awards_honors'] ?? null,
        $data['languages_spoken'] ?? null,
        $data['special_needs'] ?? null
    ]);
}

function submitApplication($applicationId, $db) {
    $hasPersonal = $db->fetch("SELECT 1 FROM application_personal_info WHERE application_id = ?", [$applicationId]);
    if (!$hasPersonal) {
        throw new Exception('Please complete Personal Information before submitting.');
    }

    $hasReceipt = $db->fetch(
        "SELECT 1 FROM application_documents d JOIN document_types t ON d.document_type_id = t.id WHERE d.application_id = ? AND t.code = 'application_fee_receipt' LIMIT 1",
        [$applicationId]
    );
    if (!$hasReceipt) {
        throw new Exception('Payment proof (application fee receipt) is required before submitting.');
    }

    $sql = "UPDATE application_forms SET status = 'submitted', submitted_at = NOW() WHERE id = ?";
    $db->execute($sql, [$applicationId]);

    $notificationSystem = new NotificationSystem();
    $application = $db->fetch("SELECT applicant_user_id FROM application_forms WHERE id = ?", [$applicationId]);

    $notificationSystem->notify(
        $application['applicant_user_id'],
        'application_submitted',
        'Application Submitted Successfully',
        'Your application has been submitted and is now under review. You will be notified of any updates.',
        null,
        [],
        true
    );
}

function getOrCreateDocumentType($code, $db) {
    $t = $db->fetch("SELECT * FROM document_types WHERE code = ?", [$code]);
    if ($t) return $t;
    $db->execute("INSERT INTO document_types (code, is_required, max_size_mb, allowed_extensions) VALUES (?, TRUE, 10, 'pdf,jpg,jpeg,png')", [$code]);
    return $db->fetch("SELECT * FROM document_types WHERE code = ?", [$code]);
}

function uploadApplicationDocument($applicationId, $documentCode, $file, $db, $language = 'en') {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error');
    }
    $docType = getOrCreateDocumentType($documentCode, $db);
    $maxSize = ((int)$docType['max_size_mb']) * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        throw new Exception('File exceeds maximum size');
    }
    $allowed = array_map('trim', explode(',', $docType['allowed_extensions']));
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        throw new Exception('Invalid file type');
    }

    $root = realpath(__DIR__ . '/../../../../');
    $rel = 'uploads/applications/' . $applicationId;
    $dir = $root . '/' . $rel;
    if (!is_dir($dir)) { mkdir($dir, 0775, true); }
    $safe = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($file['name']));
    $stored = uniqid('doc_', true) . '_' . $safe;
    $full = $dir . '/' . $stored;
    if (!move_uploaded_file($file['tmp_name'], $full)) {
        throw new Exception('Failed to save uploaded file');
    }

    $db->execute(
        "INSERT INTO application_documents (application_id, document_type_id, original_filename, stored_filename, file_path, file_size_bytes, mime_type) VALUES (?, ?, ?, ?, ?, ?, ?)",
        [$applicationId, $docType['id'], $file['name'], $stored, $rel . '/' . $stored, (int)$file['size'], $file['type'] ?? null]
    );

    return [
        'id' => $db->lastInsertId(),
        'code' => $documentCode,
        'original' => $file['name'],
        'url' => rtrim(BASE_PATH, '/') . '/' . $rel . '/' . $stored,
        'size' => (int)$file['size']
    ];
}

function listApplicationDocuments($applicationId, $db, $language = 'en') {
    $rows = $db->fetchAll(
        "SELECT d.id, d.original_filename, d.file_path, d.file_size_bytes, t.code FROM application_documents d JOIN document_types t ON d.document_type_id = t.id WHERE d.application_id = ? ORDER BY d.uploaded_at DESC",
        [$applicationId]
    );
    foreach ($rows as &$r) {
        $r['url'] = rtrim(BASE_PATH, '/') . '/' . $r['file_path'];
    }
    return $rows;
}
?>

<!-- Application Form Interface -->
<div class="application-form">
    <?php if (!$application): ?>
        <!-- Create New Application -->
        <div id="createApplicationForm">
            <div class="text-center mb-4">
                <i class="bi bi-file-earmark-plus text-primary" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Start Your Application</h4>
                <p class="text-muted">Choose a program and begin your admission application.</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form id="newApplicationForm">
                                <div class="mb-3">
                                    <label for="program_id" class="form-label">Select Program</label>
                                    <select class="form-select" id="program_id" name="program_id" required>
                                        <option value="">Choose a program...</option>
                                        <?php foreach ($programs as $program): ?>
                                            <option value="<?php echo $program['id']; ?>">
                                                <?php echo htmlspecialchars($program['name']); ?>
                                                (<?php echo htmlspecialchars($program['program_code']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <div id="programDescription" class="alert alert-info d-none">
                                        <!-- Program description will be shown here -->
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Create Application
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Multi-Step Application Form -->
        <div id="applicationSteps">
            <!-- Step 1: Personal Information -->
            <div class="application-step" data-step="1">
                <h4 class="mb-4">Personal Information</h4>
                <form id="personalInfoForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                   value="<?php echo $personalInfo['date_of_birth'] ?? ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select gender...</option>
                                <option value="male" <?php echo ($personalInfo['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($personalInfo['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo ($personalInfo['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                <option value="prefer_not_to_say" <?php echo ($personalInfo['gender'] ?? '') === 'prefer_not_to_say' ? 'selected' : ''; ?>>Prefer not to say</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control" id="nationality" name="nationality" 
                                   value="<?php echo htmlspecialchars($personalInfo['nationality'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="passport_number" class="form-label">Passport Number</label>
                            <input type="text" class="form-control" id="passport_number" name="passport_number" 
                                   value="<?php echo htmlspecialchars($personalInfo['passport_number'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                   value="<?php echo htmlspecialchars($personalInfo['phone_number'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                   value="<?php echo htmlspecialchars($personalInfo['emergency_contact_name'] ?? ''); ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                        <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                               value="<?php echo htmlspecialchars($personalInfo['emergency_contact_phone'] ?? ''); ?>" required>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Address Information</h5>
                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" 
                               value="<?php echo htmlspecialchars($personalInfo['address_line1'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Address Line 2 (Optional)</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2" 
                               value="<?php echo htmlspecialchars($personalInfo['address_line2'] ?? ''); ?>">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" 
                                   value="<?php echo htmlspecialchars($personalInfo['city'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state_province" class="form-label">State/Province</label>
                            <input type="text" class="form-control" id="state_province" name="state_province" 
                                   value="<?php echo htmlspecialchars($personalInfo['state_province'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                   value="<?php echo htmlspecialchars($personalInfo['postal_code'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" 
                               value="<?php echo htmlspecialchars($personalInfo['country'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" disabled>Previous</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </form>
            </div>
            
            <!-- Step 2: Academic History -->
            <div class="application-step d-none" data-step="2">
                <h4 class="mb-4">Academic History</h4>
                <form id="academicHistoryForm">
                    <div id="academicRecordsContainer">
                        <?php if (!empty($academicHistory)): ?>
                            <?php foreach ($academicHistory as $rec): ?>
                                <div class="card mb-3 academic-record">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">School/Institution Name</label>
                                                <input type="text" class="form-control" name="institution_name[]" value="<?php echo htmlspecialchars($rec['institution_name']); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Degree/Certificate</label>
                                                <input type="text" class="form-control" name="degree_type[]" value="<?php echo htmlspecialchars($rec['degree_type']); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Field of Study</label>
                                                <input type="text" class="form-control" name="field_of_study[]" value="<?php echo htmlspecialchars($rec['field_of_study']); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">From</label>
                                                <input type="date" class="form-control" name="start_date[]" value="<?php echo htmlspecialchars($rec['start_date']); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">To</label>
                                                <input type="date" class="form-control" name="end_date[]" value="<?php echo htmlspecialchars($rec['end_date']); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">GPA</label>
                                                <input type="number" step="0.01" class="form-control" name="gpa[]" value="<?php echo htmlspecialchars($rec['gpa']); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Scale</label>
                                                <input type="number" step="0.01" class="form-control" name="gpa_scale[]" value="<?php echo htmlspecialchars($rec['gpa_scale']); ?>">
                                            </div>
                                            <div class="col-md-3 d-flex align-items-end">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_current[]" value="1" <?php echo !empty($rec['is_current']) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label">Current</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-secondary mb-3" id="addAcademicRecordBtn"><i class="bi bi-plus-lg me-2"></i>Add Record</button>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-nav="prev">Previous</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Work Experience -->
            <div class="application-step d-none" data-step="3">
                <h4 class="mb-4">Work Experience</h4>
                <form id="workExperienceForm">
                    <div id="workRecordsContainer">
                        <?php if (!empty($workExperience)): ?>
                            <?php foreach ($workExperience as $w): ?>
                                <div class="card mb-3 work-record">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Company</label>
                                                <input type="text" class="form-control" name="company_name[]" value="<?php echo htmlspecialchars($w['company_name']); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Position</label>
                                                <input type="text" class="form-control" name="position_title[]" value="<?php echo htmlspecialchars($w['position_title']); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" class="form-control" name="start_date[]" value="<?php echo htmlspecialchars($w['start_date']); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">End Date</label>
                                                <input type="date" class="form-control" name="end_date[]" value="<?php echo htmlspecialchars($w['end_date']); ?>">
                                            </div>
                                            <div class="col-md-3 d-flex align-items-end">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_current[]" value="1" <?php echo !empty($w['is_current']) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label">Current</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="description[]" rows="2"><?php echo htmlspecialchars($w['description']); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-secondary mb-3" id="addWorkRecordBtn"><i class="bi bi-plus-lg me-2"></i>Add Work</button>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-nav="prev">Previous</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </form>
            </div>

            <!-- Step 4: Additional Information -->
            <div class="application-step d-none" data-step="4">
                <h4 class="mb-4">Additional Information</h4>
                <form id="additionalInfoForm">
                    <div class="mb-3">
                        <label class="form-label">Statement of Intent</label>
                        <textarea class="form-control" name="personal_statement" rows="4"><?php echo htmlspecialchars($additionalInfo['personal_statement'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Why do you want this program?</label>
                        <textarea class="form-control" name="why_this_program" rows="3"><?php echo htmlspecialchars($additionalInfo['why_this_program'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Career goals</label>
                        <textarea class="form-control" name="career_goals" rows="3"><?php echo htmlspecialchars($additionalInfo['career_goals'] ?? ''); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Languages spoken</label>
                            <input type="text" class="form-control" name="languages_spoken" value="<?php echo htmlspecialchars($additionalInfo['languages_spoken'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Special needs (optional)</label>
                            <input type="text" class="form-control" name="special_needs" value="<?php echo htmlspecialchars($additionalInfo['special_needs'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Extracurricular activities</label>
                        <textarea class="form-control" name="extracurricular_activities" rows="2"><?php echo htmlspecialchars($additionalInfo['extracurricular_activities'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Awards & honors</label>
                        <textarea class="form-control" name="awards_honors" rows="2"><?php echo htmlspecialchars($additionalInfo['awards_honors'] ?? ''); ?></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-nav="prev">Previous</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </form>
            </div>

            <!-- Step 5: Review & Submit -->
            <div class="application-step d-none" data-step="5">
                <h4 class="mb-3">Review & Submit</h4>
                <div class="alert alert-info">Upload your payment proof (application fee receipt) to finalize your application.</div>
                <div class="mb-3">
                    <label class="form-label">Payment Proof (PDF/JPG/PNG, max 10MB)</label>
                    <input class="form-control" type="file" id="paymentProofInput" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="mb-3">
                    <button class="btn btn-outline-success" id="uploadReceiptBtn"><i class="bi bi-cloud-upload me-2"></i>Upload Receipt</button>
                </div>
                <div class="mb-4">
                    <h6>Uploaded Documents</h6>
                    <div id="uploadedDocumentsList" class="small text-muted">No documents uploaded yet.</div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-nav="prev">Previous</button>
                    <button type="button" class="btn btn-success" id="submitApplicationBtn"><i class="bi bi-send me-2"></i>Submit Application</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Application form JavaScript
// Global helpers for API calls
const APP_ENDPOINT = '<?php echo rtrim(BASE_PATH, '/'); ?>/modules/admin/dashboard/student/application.php';
function apiUrl() {
    const t = sessionStorage.getItem('auth_token');
    return APP_ENDPOINT + (t ? ('?token=' + encodeURIComponent(t)) : '');
}
function apiHeaders(json) {
    const h = {};
    if (json) h['Content-Type'] = 'application/json';
    const t = sessionStorage.getItem('auth_token');
    if (t) h['Authorization'] = 'Bearer ' + t;
    return h;
}
(function(){
function initAppForm(){
    // Handle new application creation
    const newAppForm = document.getElementById('newApplicationForm');
    if (newAppForm) {
        newAppForm.addEventListener('submit', handleNewApplication);
        
        // Show program description on selection
        document.getElementById('program_id').addEventListener('change', function() {
            const programId = this.value;
            if (programId) {
                showProgramDescription(programId);
            }
        });
    }
    
    // Handle step forms
    const personalInfoForm = document.getElementById('personalInfoForm');
    if (personalInfoForm) {
        personalInfoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveApplicationStep(1, new FormData(this));
        });
    }

    const academicHistoryForm = document.getElementById('academicHistoryForm');
    if (academicHistoryForm) {
        academicHistoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const payload = collectAcademicHistory();
            postSaveStep(2, payload);
        });
        const addAcademic = document.getElementById('addAcademicRecordBtn');
        if (addAcademic) addAcademic.addEventListener('click', addAcademicRecord);
    }

    const workExperienceForm = document.getElementById('workExperienceForm');
    if (workExperienceForm) {
        workExperienceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const payload = collectWorkExperience();
            postSaveStep(3, payload);
        });
        const addWork = document.getElementById('addWorkRecordBtn');
        if (addWork) addWork.addEventListener('click', addWorkRecord);
    }

    const additionalInfoForm = document.getElementById('additionalInfoForm');
    if (additionalInfoForm) {
        additionalInfoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveApplicationStep(4, new FormData(this));
        });
    }

    const uploadBtn = document.getElementById('uploadReceiptBtn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const f = document.getElementById('paymentProofInput');
            if (!f || !f.files || !f.files[0]) { return showAlert('Please select a receipt file first', 'warning'); }
            uploadDocument('application_fee_receipt', f.files[0]);
        });
        refreshDocumentsList();
    }

    const submitBtn = document.getElementById('submitApplicationBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', async function() {
            try {
                const res = await fetch(apiUrl(), {
                    method: 'POST',
                    headers: apiHeaders(true),
                    body: JSON.stringify({ action: 'submit' })
                });
                const out = await res.json();
                if (out.success) {
                    showAlert('Application submitted successfully', 'success');
                } else {
                    showAlert(out.error || 'Submission failed', 'danger');
                }
            } catch (e) {
                showAlert('Network error. Please try again.', 'danger');
            }
        });
    }

    // Prev buttons inside steps
    document.querySelectorAll('[data-nav="prev"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const cur = getCurrentStep();
            if (cur > 1) navigateToStep(cur - 1);
        });
    });

    // Step navigation list on the left
    document.querySelectorAll('.list-group-item[data-step]').forEach(a => {
        a.addEventListener('click', function(ev) {
            ev.preventDefault();
            const s = parseInt(this.getAttribute('data-step'), 10);
            navigateToStep(s);
        });
    });
    // Ensure we start at step 1 by default
    if (document.querySelector('.application-step[data-step="1"]')) navigateToStep(1);
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAppForm, { once: true });
} else {
    initAppForm();
}
})();

async function handleNewApplication(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        action: 'create',
        program_id: formData.get('program_id')
    };
    
    try {
        const response = await fetch(apiUrl(), {
            method: 'POST',
            headers: apiHeaders(true),
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Reload the page to show the application form
            location.reload();
        } else {
            alert('Error: ' + result.error);
        }
    } catch (error) {
        alert('Network error. Please try again.');
    }
}

async function saveApplicationStep(step, formData) {
    const data = {
        action: 'save',
        step: step
    };
    
    // Convert FormData to object
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    try {
        const response = await fetch(apiUrl(), {
            method: 'POST',
            headers: apiHeaders(true),
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success message and move to next step
            showAlert('Step saved successfully!', 'success');
            navigateToStep(step + 1);
        } else {
            showAlert('Error: ' + result.error, 'danger');
        }
    } catch (error) {
        showAlert('Network error. Please try again.', 'danger');
    }
}

function showProgramDescription(programId) {
    // This would fetch and display program description
    // Implementation depends on available program data
}

function showAlert(message, type) {
    // Implementation for showing alerts
    console.log(type + ': ' + message);
}

function getCurrentStep() {
    const vis = document.querySelector('.application-step:not(.d-none)');
    return vis ? parseInt(vis.getAttribute('data-step'), 10) : 1;
}

function navigateToStep(step) {
    document.querySelectorAll('.application-step').forEach(div => {
        const s = parseInt(div.getAttribute('data-step'), 10);
        if (s === step) div.classList.remove('d-none'); else div.classList.add('d-none');
    });
    document.querySelectorAll('.list-group-item[data-step]').forEach(a => {
        const s = parseInt(a.getAttribute('data-step'), 10);
        if (s === step) a.classList.add('active'); else a.classList.remove('active');
    });
}

function addAcademicRecord() {
    const container = document.getElementById('academicRecordsContainer');
    const el = document.createElement('div');
    el.className = 'card mb-3 academic-record';
    el.innerHTML = `
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">School/Institution Name</label>
                    <input type="text" class="form-control" name="institution_name[]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Degree/Certificate</label>
                    <input type="text" class="form-control" name="degree_type[]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Field of Study</label>
                    <input type="text" class="form-control" name="field_of_study[]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control" name="start_date[]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control" name="end_date[]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">GPA</label>
                    <input type="number" step="0.01" class="form-control" name="gpa[]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Scale</label>
                    <input type="number" step="0.01" class="form-control" name="gpa_scale[]" value="4.0">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_current[]" value="1">
                        <label class="form-check-label">Current</label>
                    </div>
                </div>
            </div>
        </div>`;
    container.appendChild(el);
}

function collectAcademicHistory() {
    const records = [];
    document.querySelectorAll('#academicRecordsContainer .academic-record').forEach(card => {
        records.push({
            institution_name: card.querySelector('[name="institution_name[]"]').value,
            degree_type: card.querySelector('[name="degree_type[]"]').value,
            field_of_study: card.querySelector('[name="field_of_study[]"]').value,
            start_date: card.querySelector('[name="start_date[]"]').value,
            end_date: card.querySelector('[name="end_date[]"]').value,
            gpa: card.querySelector('[name="gpa[]"]').value,
            gpa_scale: card.querySelector('[name="gpa_scale[]"]').value || 4.0,
            is_current: card.querySelector('[name="is_current[]"]').checked ? 1 : 0
        });
    });
    return { academic_records: records, action: 'save' };
}

function addWorkRecord() {
    const container = document.getElementById('workRecordsContainer');
    const el = document.createElement('div');
    el.className = 'card mb-3 work-record';
    el.innerHTML = `
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Company</label>
                    <input type="text" class="form-control" name="company_name[]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Position</label>
                    <input type="text" class="form-control" name="position_title[]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="start_date[]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="end_date[]">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_current[]" value="1">
                        <label class="form-check-label">Current</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description[]" rows="2"></textarea>
                </div>
            </div>
        </div>`;
    container.appendChild(el);
}

function collectWorkExperience() {
    const records = [];
    document.querySelectorAll('#workRecordsContainer .work-record').forEach(card => {
        records.push({
            company_name: card.querySelector('[name="company_name[]"]').value,
            position_title: card.querySelector('[name="position_title[]"]').value,
            start_date: card.querySelector('[name="start_date[]"]').value,
            end_date: card.querySelector('[name="end_date[]"]').value,
            is_current: card.querySelector('[name="is_current[]"]').checked ? 1 : 0,
            description: card.querySelector('[name="description[]"]').value
        });
    });
    return { work_records: records, action: 'save' };
}

async function postSaveStep(step, payload) {
    try {
        const res = await fetch(apiUrl(), {
            method: 'POST',
            headers: apiHeaders(true),
            body: JSON.stringify(Object.assign({ step }, payload))
        });
        const out = await res.json();
        if (out.success) {
            showAlert('Step saved successfully!', 'success');
            navigateToStep(step + 1);
        } else {
            showAlert(out.error || 'Save failed', 'danger');
        }
    } catch (e) {
        showAlert('Network error. Please try again.', 'danger');
    }
}

async function uploadDocument(code, file) {
    const fd = new FormData();
    fd.append('action', 'upload_document');
    fd.append('document_code', code);
    fd.append('file', file);
    try {
        const res = await fetch(apiUrl(), {
            method: 'POST',
            headers: apiHeaders(false),
            body: fd
        });
        const out = await res.json();
        if (out.success) {
            showAlert('Document uploaded', 'success');
            refreshDocumentsList();
            if (typeof window.loadDocuments === 'function') window.loadDocuments();
        } else {
            showAlert(out.error || 'Upload failed', 'danger');
        }
    } catch (e) {
        showAlert('Network error. Please try again.', 'danger');
    }
}

async function refreshDocumentsList() {
    try {
        const res = await fetch(apiUrl(), {
            method: 'POST',
            headers: apiHeaders(true),
            body: JSON.stringify({ action: 'list_documents' })
        });
        const out = await res.json();
        const el = document.getElementById('uploadedDocumentsList');
        if (!el) return;
        if (!out.success || !out.documents || out.documents.length === 0) {
            el.textContent = 'No documents uploaded yet.';
            return;
        }
        el.innerHTML = out.documents.map(d => `<div><a href="${d.url}" target="_blank">${d.original_filename}</a> <span class="text-muted">(${Math.round((d.file_size_bytes||d.size||0)/1024)} KB)</span></div>`).join('');
    } catch (e) {
        // silent
    }
}
</script>
