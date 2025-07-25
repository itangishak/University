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
        $input = json_decode(file_get_contents('php://input'), true);
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
        
        switch ($action) {
            case 'create':
                $applicationId = createNewApplication($input, $user['id'], $db);
                break;
            case 'save':
                $applicationId = $application['id'];
                saveApplicationStep($applicationId, $step, $input, $db);
                break;
            case 'submit':
                $applicationId = $application['id'];
                submitApplication($applicationId, $db);
                break;
        }
        
        $db->commit();
        
        echo json_encode([
            'success' => true,
            'application_id' => $applicationId,
            'message' => 'Application saved successfully'
        ]);
        
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
    $sql = "UPDATE application_forms SET status = 'submitted', submitted_at = NOW() WHERE id = ?";
    $db->execute($sql, [$applicationId]);
    
    // Create notification
    $notificationSystem = new NotificationSystem();
    $application = $db->fetch("SELECT applicant_user_id FROM application_forms WHERE id = ?", [$applicationId]);
    
    $notificationSystem->notify(
        $application['applicant_user_id'],
        'application_submitted',
        'Application Submitted Successfully',
        'Your application has been submitted and is now under review. You will be notified of any updates.',
        null,
        [],
        true // Send email
    );
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
            
            <!-- Additional steps would be implemented similarly -->
            <!-- Step 2: Academic History -->
            <!-- Step 3: Work Experience -->
            <!-- Step 4: Additional Information -->
            <!-- Step 5: Review & Submit -->
        </div>
    <?php endif; ?>
</div>

<script>
// Application form JavaScript
document.addEventListener('DOMContentLoaded', function() {
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
});

async function handleNewApplication(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        action: 'create',
        program_id: formData.get('program_id')
    };
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + sessionStorage.getItem('auth_token')
            },
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
        const response = await fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + sessionStorage.getItem('auth_token')
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success message and move to next step
            showAlert('Step saved successfully!', 'success');
            // Implement step navigation
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
</script>
