<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../../includes/Auth.php';
require_once __DIR__ . '/../../../../includes/NotificationSystem.php';

$auth = new Auth();
$auth->requireRole('admission_officer');

$user = $auth->getCurrentUser();
$db = Database::getInstance();

// Get admission statistics
$stats = [
    'total_applications' => $db->fetch("SELECT COUNT(*) as count FROM application_forms")['count'],
    'pending_review' => $db->fetch("SELECT COUNT(*) as count FROM application_forms WHERE status = 'submitted'")['count'],
    'approved_applications' => $db->fetch("SELECT COUNT(*) as count FROM application_forms WHERE status = 'approved'")['count'],
    'rejected_applications' => $db->fetch("SELECT COUNT(*) as count FROM application_forms WHERE status = 'rejected'")['count']
];

// Get applications for review
$applicationsForReview = $db->fetchAll(
    "SELECT af.*, u.first_name, u.last_name, u.email, p.program_code, pt.name as program_name
     FROM application_forms af
     JOIN users u ON af.applicant_user_id = u.id
     JOIN programs p ON af.program_id = p.id
     JOIN program_translations pt ON p.id = pt.program_id AND pt.language_code = ?
     WHERE af.status IN ('submitted', 'under_review')
     ORDER BY af.submitted_at ASC LIMIT 20",
    [$user['preferred_language']]
);

// Get recent admission activities
$recentActivities = $db->fetchAll(
    "SELECT af.*, u.first_name, u.last_name, p.program_code
     FROM application_forms af
     JOIN users u ON af.applicant_user_id = u.id
     JOIN programs p ON af.program_id = p.id
     WHERE af.status IN ('approved', 'rejected', 'waitlisted')
     ORDER BY af.updated_at DESC LIMIT 10"
);
?>
<!DOCTYPE html>
<html lang="<?php echo $user['preferred_language']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Officer Dashboard - BAU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
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
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .application-item {
            border-left: 4px solid #17a2b8;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .application-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-admission {
            border-radius: 8px;
            font-weight: 500;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 4px 12px;
            border-radius: 20px;
        }
        .priority-high {
            border-left-color: #dc3545;
        }
        .priority-medium {
            border-left-color: #ffc107;
        }
        .priority-low {
            border-left-color: #28a745;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
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
                        <i class="bi bi-mortarboard text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mb-0">Admissions</h5>
                    <small class="opacity-75">Review Center</small>
                </div>
                
                <div class="text-center mb-4">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2 d-inline-block mb-2">
                        <i class="bi bi-person-check" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-0"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                    <small class="opacity-75">Admission Officer</small>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#dashboard" data-section="dashboard">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="#review" data-section="review">
                        <i class="bi bi-clipboard-check me-2"></i>Review Queue
                    </a>
                    <a class="nav-link" href="#applications" data-section="applications">
                        <i class="bi bi-file-earmark-text me-2"></i>All Applications
                    </a>
                    <a class="nav-link" href="#documents" data-section="documents">
                        <i class="bi bi-folder2-open me-2"></i>Documents
                    </a>
                    <a class="nav-link" href="#reports" data-section="reports">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>Reports
                    </a>
                    <a class="nav-link" href="#analytics" data-section="analytics">
                        <i class="bi bi-graph-up me-2"></i>Analytics
                    </a>
                    <a class="nav-link" href="#bulk" data-section="bulk">
                        <i class="bi bi-list-check me-2"></i>Bulk Actions
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
                <!-- Dashboard Overview -->
                <div id="dashboard-section" class="content-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2>Admission Dashboard</h2>
                            <p class="text-muted">Review applications and manage admissions process.</p>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary btn-admission me-2" onclick="showSection('review')">
                                <i class="bi bi-clipboard-check me-2"></i>Review Queue
                            </button>
                            <button class="btn btn-success btn-admission" onclick="showSection('reports')">
                                <i class="bi bi-download me-2"></i>Export Data
                            </button>
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['total_applications']; ?></div>
                                        <div class="opacity-75">Total Applications</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['pending_review']; ?></div>
                                        <div class="opacity-75">Pending Review</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['approved_applications']; ?></div>
                                        <div class="opacity-75">Approved</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-x-circle" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['rejected_applications']; ?></div>
                                        <div class="opacity-75">Rejected</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Review Queue and Recent Activities -->
                    <div class="row">
                        <div class="col-md-8 mb-4">
                            <div class="dashboard-card card">
                                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Applications Awaiting Review</h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="showSection('review')">
                                        View All
                                    </button>
                                </div>
                                <div class="card-body">
                                    <?php foreach (array_slice($applicationsForReview, 0, 5) as $app): ?>
                                    <div class="application-item priority-<?php echo rand(1,3) == 1 ? 'high' : (rand(1,2) == 1 ? 'medium' : 'low'); ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></h6>
                                                <p class="text-muted small mb-2">
                                                    <?php echo htmlspecialchars($app['program_name']); ?> (<?php echo htmlspecialchars($app['program_code']); ?>)
                                                </p>
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope me-1"></i>
                                                    <?php echo htmlspecialchars($app['email']); ?>
                                                    <span class="ms-3">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Submitted: <?php echo date('M j, Y', strtotime($app['submitted_at'])); ?>
                                                    </span>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="status-badge bg-warning">
                                                    <?php echo ucfirst($app['status']); ?>
                                                </span>
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-primary me-1" onclick="reviewApplication(<?php echo $app['id']; ?>)">
                                                        Review
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="viewDocuments(<?php echo $app['id']; ?>)">
                                                        Documents
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="dashboard-card card">
                                <div class="card-header bg-transparent">
                                    <h6 class="mb-0">Recent Decisions</h6>
                                </div>
                                <div class="card-body">
                                    <?php foreach (array_slice($recentActivities, 0, 6) as $activity): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($activity['program_code']); ?></small>
                                        </div>
                                        <div class="text-end">
                                            <span class="status-badge bg-<?php echo $activity['status'] === 'approved' ? 'success' : ($activity['status'] === 'rejected' ? 'danger' : 'warning'); ?>">
                                                <?php echo ucfirst($activity['status']); ?>
                                            </span>
                                            <br><small class="text-muted"><?php echo date('M j', strtotime($activity['updated_at'])); ?></small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Review Queue Section -->
                <div id="review-section" class="content-section d-none">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2>Application Review Queue</h2>
                            <p class="text-muted">Review and process student applications.</p>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-outline-primary btn-admission me-2" onclick="showBulkActions()">
                                <i class="bi bi-list-check me-2"></i>Bulk Actions
                            </button>
                            <button class="btn btn-success btn-admission" onclick="exportApplications()">
                                <i class="bi bi-download me-2"></i>Export
                            </button>
                        </div>
                    </div>
                    
                    <div class="dashboard-card card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Search applicants..." id="applicantSearch">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="submitted">Submitted</option>
                                        <option value="under_review">Under Review</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="waitlisted">Waitlisted</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" id="programFilter">
                                        <option value="">All Programs</option>
                                        <option value="CS">Computer Science</option>
                                        <option value="BUS">Business</option>
                                        <option value="ENG">Engineering</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" id="sortBy">
                                        <option value="submitted_date">Submit Date</option>
                                        <option value="name">Name</option>
                                        <option value="program">Program</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-primary w-100" onclick="loadApplications()">
                                        <i class="bi bi-search me-2"></i>Filter & Search
                                    </button>
                                </div>
                            </div>
                            
                            <div id="applicationsTableContainer">
                                <!-- Applications table will be loaded here -->
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Other sections placeholders -->
                <div id="applications-section" class="content-section d-none">
                    <h2>All Applications</h2>
                    <p class="text-muted">View and manage all student applications.</p>
                </div>
                
                <div id="documents-section" class="content-section d-none">
                    <h2>Document Management</h2>
                    <p class="text-muted">Access and manage application documents.</p>
                </div>
                
                <div id="reports-section" class="content-section d-none">
                    <h2>Reports & Export</h2>
                    <p class="text-muted">Generate reports and export application data.</p>
                </div>
                
                <div id="analytics-section" class="content-section d-none">
                    <h2>Admission Analytics</h2>
                    <p class="text-muted">View admission statistics and trends.</p>
                </div>
                
                <div id="bulk-section" class="content-section d-none">
                    <h2>Bulk Actions</h2>
                    <p class="text-muted">Perform bulk operations on multiple applications.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const authToken = sessionStorage.getItem('auth_token');
        
        // Navigation handling
        document.querySelectorAll('.nav-link[data-section]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.getAttribute('data-section');
                showSection(section);
                
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        function showSection(sectionName) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('d-none');
            });
            
            const targetSection = document.getElementById(sectionName + '-section');
            if (targetSection) {
                targetSection.classList.remove('d-none');
            }
            
            loadSectionContent(sectionName);
        }
        
        function loadSectionContent(sectionName) {
            switch(sectionName) {
                case 'review':
                    loadApplications();
                    break;
                case 'applications':
                    loadAllApplications();
                    break;
                case 'documents':
                    loadDocuments();
                    break;
                // Add other cases as needed
            }
        }
        
        function loadApplications() {
            const container = document.getElementById('applicationsTableContainer');
            container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
            
            // This would load applications via AJAX
            setTimeout(() => {
                container.innerHTML = '<p class="text-center text-muted">Applications review interface will be loaded here.</p>';
            }, 1000);
        }
        
        function loadAllApplications() {
            console.log('Loading all applications...');
        }
        
        function loadDocuments() {
            console.log('Loading documents...');
        }
        
        function reviewApplication(applicationId) {
            // Implementation for reviewing specific application
            alert('Opening application review for ID: ' + applicationId);
        }
        
        function viewDocuments(applicationId) {
            // Implementation for viewing application documents
            alert('Opening documents for application ID: ' + applicationId);
        }
        
        function showBulkActions() {
            showSection('bulk');
        }
        
        function exportApplications() {
            // Implementation for exporting applications
            alert('Export functionality will be implemented');
        }
    </script>
</body>
</html>
