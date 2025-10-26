<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../../includes/Auth.php';
require_once __DIR__ . '/../../../../includes/NotificationSystem.php';

$auth = new Auth();
$auth->requireRole('student');

$user = $auth->getCurrentUser();
$notificationSystem = new NotificationSystem();

// Get user notifications
$notifications = $notificationSystem->getUserNotifications($user['id'], 5);
$unreadCount = $notificationSystem->getUnreadCount($user['id']);

// Get current application status
$db = Database::getInstance();
$currentApplication = $db->fetch(
    "SELECT af.*, p.program_code, pt.name as program_name 
     FROM application_forms af
     JOIN programs p ON af.program_id = p.id
     JOIN program_translations pt ON p.id = pt.program_id AND pt.language_code = ?
     WHERE af.applicant_user_id = ? 
     ORDER BY af.created_at DESC LIMIT 1",
    [$user['preferred_language'], $user['id']]
);

// Get application progress
$applicationProgress = 0;
if ($currentApplication) {
    $sections = ['personal_info', 'academic_history', 'work_experience', 'additional_info'];
    $completedSections = 0;
    
    foreach ($sections as $section) {
        $tableName = 'application_' . $section;
        $exists = $db->fetch("SELECT 1 FROM $tableName WHERE application_id = ?", [$currentApplication['id']]);
        if ($exists) $completedSections++;
    }
    
    $applicationProgress = ($completedSections / count($sections)) * 100;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $user['preferred_language']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - BAU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .progress-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 8px 16px;
            border-radius: 20px;
        }
        .notification-item {
            border-left: 4px solid #007bff;
            background: #f8f9fa;
            border-radius: 0 8px 8px 0;
        }
        .quick-action-btn {
            border-radius: 12px;
            padding: 15px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }
        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            color: inherit;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="text-center mb-4">
                    <div class="bg-white rounded-circle p-3 d-inline-block mb-3">
                        <i class="bi bi-mortarboard-fill text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mb-0">BAU Portal</h5>
                    <small class="opacity-75">Student Dashboard</small>
                </div>
                
                <div class="text-center mb-4">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2 d-inline-block mb-2">
                        <i class="bi bi-person-fill" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-0"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                    <small class="opacity-75"><?php echo htmlspecialchars($user['email']); ?></small>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#dashboard" data-section="dashboard">
                        <i class="bi bi-house-fill me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="#application" data-section="application">
                        <i class="bi bi-file-earmark-text-fill me-2"></i>Application
                        <?php if ($currentApplication && $currentApplication['status'] === 'draft'): ?>
                            <span class="badge bg-warning ms-2">Draft</span>
                        <?php endif; ?>
                    </a>
                    <a class="nav-link" href="#documents" data-section="documents">
                        <i class="bi bi-file-earmark-arrow-up-fill me-2"></i>Documents
                    </a>
                    <a class="nav-link" href="#status" data-section="status">
                        <i class="bi bi-clock-history me-2"></i>Application Status
                    </a>
                    <a class="nav-link" href="#resources" data-section="resources">
                        <i class="bi bi-book-fill me-2"></i>Resources
                    </a>
                    <a class="nav-link" href="#notifications" data-section="notifications">
                        <i class="bi bi-bell-fill me-2"></i>Notifications
                        <?php if ($unreadCount > 0): ?>
                            <span class="badge bg-danger ms-2"><?php echo $unreadCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <a class="nav-link" href="#profile" data-section="profile">
                        <i class="bi bi-person-gear me-2"></i>Profile
                    </a>
                </nav>
                
                <div class="mt-auto pt-4">
                    <a href="<?php echo BASE_PATH; ?>/modules/admin/login/login.php?action=logout" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Dashboard Section -->
                <div id="dashboard-section" class="content-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2>Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>
                            <p class="text-muted">Here's an overview of your application progress and important updates.</p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Last login: <?php echo date('M j, Y g:i A'); ?></small>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card card h-100 text-center p-4">
                                <div class="progress-circle mx-auto mb-3" style="background: conic-gradient(#28a745 <?php echo $applicationProgress; ?>%, #e9ecef 0);">
                                    <?php echo round($applicationProgress); ?>%
                                </div>
                                <h6>Application Progress</h6>
                                <small class="text-muted">Complete your application</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card card h-100 text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-file-earmark-check-fill text-primary" style="font-size: 3rem;"></i>
                                </div>
                                <h6>Application Status</h6>
                                <?php if ($currentApplication): ?>
                                    <span class="status-badge bg-<?php echo $currentApplication['status'] === 'draft' ? 'warning' : ($currentApplication['status'] === 'approved' ? 'success' : 'info'); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $currentApplication['status'])); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge bg-secondary">Not Started</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card card h-100 text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-bell-fill text-info" style="font-size: 3rem;"></i>
                                </div>
                                <h6>Notifications</h6>
                                <h4 class="text-info"><?php echo $unreadCount; ?></h4>
                                <small class="text-muted">Unread messages</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-card card h-100 text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-file-earmark-arrow-up-fill text-success" style="font-size: 3rem;"></i>
                                </div>
                                <h6>Documents</h6>
                                <h4 class="text-success" id="documentCount">0</h4>
                                <small class="text-muted">Uploaded files</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Quick Actions</h5>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="quick-action-btn card p-3 d-block" onclick="showSection('application')">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                                        <i class="bi bi-plus-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Start Application</h6>
                                        <small class="text-muted">Begin your admission process</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="quick-action-btn card p-3 d-block" onclick="showSection('documents')">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle p-3 me-3">
                                        <i class="bi bi-cloud-upload"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Upload Documents</h6>
                                        <small class="text-muted">Submit required files</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="quick-action-btn card p-3 d-block" onclick="showSection('status')">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info text-white rounded-circle p-3 me-3">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Check Status</h6>
                                        <small class="text-muted">View application progress</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Recent Notifications -->
                    <div class="row">
                        <div class="col-12">
                            <div class="dashboard-card card">
                                <div class="card-header bg-transparent">
                                    <h6 class="mb-0">Recent Notifications</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($notifications)): ?>
                                        <div class="text-center py-4">
                                            <i class="bi bi-bell text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">No notifications yet</p>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($notifications as $notification): ?>
                                            <div class="notification-item p-3 mb-2 <?php echo $notification['is_read'] ? '' : 'bg-light'; ?>">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-<?php echo $notification['icon']; ?> text-primary me-3 mt-1"></i>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1"><?php echo htmlspecialchars($notification['title']); ?></h6>
                                                        <p class="mb-1 text-muted"><?php echo htmlspecialchars($notification['message']); ?></p>
                                                        <small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($notification['created_at'])); ?></small>
                                                    </div>
                                                    <?php if (!$notification['is_read']): ?>
                                                        <span class="badge bg-primary">New</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Application Section -->
                <div id="application-section" class="content-section d-none">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2>Application Form</h2>
                            <p class="text-muted">Complete your admission application step by step.</p>
                        </div>
                        <?php if ($currentApplication && $currentApplication['status'] === 'draft'): ?>
                            <button class="btn btn-outline-secondary" onclick="saveDraft()">
                                <i class="bi bi-save me-2"></i>Save Draft
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Application Steps -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Application Steps</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action active" data-step="1">
                                            <i class="bi bi-person-fill me-2"></i>Personal Information
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action" data-step="2">
                                            <i class="bi bi-mortarboard me-2"></i>Academic History
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action" data-step="3">
                                            <i class="bi bi-briefcase me-2"></i>Work Experience
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action" data-step="4">
                                            <i class="bi bi-file-text me-2"></i>Additional Information
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action" data-step="5">
                                            <i class="bi bi-check-circle me-2"></i>Review & Submit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <div id="applicationFormContainer">
                                        <!-- Application form steps will be loaded here -->
                                        <div class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-3">Loading application form...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Other sections will be loaded dynamically -->
                <div id="documents-section" class="content-section d-none">
                    <h2>Document Upload</h2>
                    <p class="text-muted">Upload required documents for your application.</p>
                    <!-- Document upload interface will be implemented -->
                </div>
                
                <div id="status-section" class="content-section d-none">
                    <h2>Application Status</h2>
                    <p class="text-muted">Track the progress of your application.</p>
                    <!-- Status tracking interface will be implemented -->
                </div>
                
                <div id="resources-section" class="content-section d-none">
                    <h2>Resources</h2>
                    <p class="text-muted">Helpful resources and guides for your application.</p>
                    <!-- Resources section will be implemented -->
                </div>
                
                <div id="notifications-section" class="content-section d-none">
                    <h2>Notifications</h2>
                    <p class="text-muted">View all your notifications and updates.</p>
                    <!-- Notifications interface will be implemented -->
                </div>
                
                <div id="profile-section" class="content-section d-none">
                    <h2>Profile Settings</h2>
                    <p class="text-muted">Manage your account settings and preferences.</p>
                    <!-- Profile settings will be implemented -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function getAuthToken() {
            return sessionStorage.getItem('auth_token');
        }
        (function() {
            var params = new URLSearchParams(window.location.search);
            var t = params.get('token');
            if (t) {
                sessionStorage.setItem('auth_token', t);
                params.delete('token');
                var q = params.toString();
                var newUrl = window.location.pathname + (q ? '?' + q : '');
                window.history.replaceState({}, '', newUrl);
            }
        })();
        
        // Navigation handling
        document.querySelectorAll('.nav-link[data-section]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.getAttribute('data-section');
                showSection(section);
                
                // Update active nav link
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('d-none');
            });
            
            // Show target section
            const targetSection = document.getElementById(sectionName + '-section');
            if (targetSection) {
                targetSection.classList.remove('d-none');
            }
            
            // Load section-specific content
            loadSectionContent(sectionName);
        }
        
        function loadSectionContent(sectionName) {
            switch(sectionName) {
                case 'application':
                    loadApplicationForm();
                    break;
                case 'documents':
                    loadDocuments();
                    break;
                case 'status':
                    loadApplicationStatus();
                    break;
                // Add other cases as needed
            }
        }
        
        async function loadApplicationForm() {
            const token = getAuthToken();
            const url = '<?php echo BASE_PATH; ?>/modules/admin/dashboard/student/application.php' + (token ? ('?token=' + encodeURIComponent(token)) : '');
            try {
                const response = await fetch(url, {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });
                const html = await response.text();
                document.getElementById('applicationFormContainer').innerHTML = html;
            } catch (error) {
                console.error('Error loading application form:', error);
            }
        }
        
        function loadDocuments() {
            // Implementation for document loading
            console.log('Loading documents...');
        }
        
        function loadApplicationStatus() {
            // Implementation for status loading
            console.log('Loading application status...');
        }
        
        function saveDraft() {
            // Implementation for saving draft
            console.log('Saving draft...');
        }
        
        // Load document count on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch document count and update display
            // This would be implemented with an AJAX call
        });
    </script>
</body>
</html>
