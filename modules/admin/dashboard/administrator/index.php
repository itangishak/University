<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../../includes/Auth.php';
require_once __DIR__ . '/../../../../includes/NotificationSystem.php';

$auth = new Auth();
$auth->requireRole('administrator');

$user = $auth->getCurrentUser();
$db = Database::getInstance();

// Get system statistics
$stats = [
    'total_users' => $db->fetch("SELECT COUNT(*) as count FROM users")['count'],
    'total_applications' => $db->fetch("SELECT COUNT(*) as count FROM application_forms")['count'],
    'pending_applications' => $db->fetch("SELECT COUNT(*) as count FROM application_forms WHERE status = 'submitted'")['count'],
    'total_documents' => $db->fetch("SELECT COUNT(*) as count FROM application_documents")['count'],
    'active_sessions' => $db->fetch("SELECT COUNT(*) as count FROM user_sessions WHERE expires_at > NOW()")['count']
];

// Get recent activities
$recentApplications = $db->fetchAll(
    "SELECT af.*, u.first_name, u.last_name, u.email, p.program_code
     FROM application_forms af
     JOIN users u ON af.applicant_user_id = u.id
     JOIN programs p ON af.program_id = p.id
     ORDER BY af.created_at DESC LIMIT 10"
);

$recentUsers = $db->fetchAll(
    "SELECT id, username, email, role, first_name, last_name, created_at
     FROM users ORDER BY created_at DESC LIMIT 10"
);
?>
<!DOCTYPE html>
<html lang="<?php echo $user['preferred_language']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard - BAU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .btn-admin {
            border-radius: 8px;
            font-weight: 500;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 4px 12px;
            border-radius: 20px;
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
                        <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mb-0">Admin Panel</h5>
                    <small class="opacity-75">System Control</small>
                </div>
                
                <div class="text-center mb-4">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2 d-inline-block mb-2">
                        <i class="bi bi-person-gear" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-0"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                    <small class="opacity-75">Administrator</small>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#dashboard" data-section="dashboard">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="#users" data-section="users">
                        <i class="bi bi-people-fill me-2"></i>User Management
                    </a>
                    <a class="nav-link" href="#applications" data-section="applications">
                        <i class="bi bi-file-earmark-text me-2"></i>Applications
                    </a>
                    <a class="nav-link" href="#content" data-section="content">
                        <i class="bi bi-newspaper me-2"></i>Content Management
                    </a>
                    <a class="nav-link" href="#analytics" data-section="analytics">
                        <i class="bi bi-graph-up me-2"></i>Analytics
                    </a>
                    <a class="nav-link" href="#settings" data-section="settings">
                        <i class="bi bi-gear-fill me-2"></i>System Settings
                    </a>
                    <a class="nav-link" href="#reports" data-section="reports">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>Reports
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
                            <h2>System Overview</h2>
                            <p class="text-muted">Monitor and manage the university system.</p>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-success btn-admin" onclick="showSection('users')">
                                <i class="bi bi-plus-lg me-2"></i>Add User
                            </button>
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['total_users']; ?></div>
                                        <div class="opacity-75">Total Users</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['total_applications']; ?></div>
                                        <div class="opacity-75">Applications</div>
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
                                        <div class="stat-number"><?php echo $stats['pending_applications']; ?></div>
                                        <div class="opacity-75">Pending Review</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-wifi" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['active_sessions']; ?></div>
                                        <div class="opacity-75">Active Sessions</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="dashboard-card card">
                                <div class="card-header bg-transparent">
                                    <h6 class="mb-0">Recent Applications</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Applicant</th>
                                                    <th>Program</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentApplications as $app): ?>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></strong>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($app['email']); ?></small>
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($app['program_code']); ?></td>
                                                    <td>
                                                        <span class="status-badge bg-<?php echo $app['status'] === 'submitted' ? 'warning' : ($app['status'] === 'approved' ? 'success' : 'secondary'); ?>">
                                                            <?php echo ucfirst($app['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('M j', strtotime($app['created_at'])); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="dashboard-card card">
                                <div class="card-header bg-transparent">
                                    <h6 class="mb-0">Recent Users</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>User</th>
                                                    <th>Role</th>
                                                    <th>Joined</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentUsers as $recentUser): ?>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($recentUser['first_name'] . ' ' . $recentUser['last_name']); ?></strong>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($recentUser['email']); ?></small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $recentUser['role'] === 'administrator' ? 'danger' : ($recentUser['role'] === 'student' ? 'primary' : 'info'); ?>">
                                                            <?php echo ucfirst(str_replace('_', ' ', $recentUser['role'])); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('M j', strtotime($recentUser['created_at'])); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Management Section -->
                <div id="users-section" class="content-section d-none">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2>User Management</h2>
                            <p class="text-muted">Manage user accounts and roles.</p>
                        </div>
                        <button class="btn btn-success btn-admin" onclick="showAddUserModal()">
                            <i class="bi bi-plus-lg me-2"></i>Add New User
                        </button>
                    </div>
                    
                    <div class="dashboard-card card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Search users..." id="userSearch">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="roleFilter">
                                        <option value="">All Roles</option>
                                        <option value="student">Student</option>
                                        <option value="administrator">Administrator</option>
                                        <option value="communication_officer">Communication Officer</option>
                                        <option value="admission_officer">Admission Officer</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-primary w-100" onclick="loadUsers()">
                                        <i class="bi bi-search me-2"></i>Search
                                    </button>
                                </div>
                            </div>
                            
                            <div id="usersTableContainer">
                                <!-- Users table will be loaded here -->
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
                    <h2>Application Management</h2>
                    <p class="text-muted">Review and manage student applications.</p>
                </div>
                
                <div id="content-section" class="content-section d-none">
                    <h2>Content Management</h2>
                    <p class="text-muted">Manage website content and media.</p>
                </div>
                
                <div id="analytics-section" class="content-section d-none">
                    <h2>System Analytics</h2>
                    <p class="text-muted">View system usage and performance metrics.</p>
                </div>
                
                <div id="settings-section" class="content-section d-none">
                    <h2>System Settings</h2>
                    <p class="text-muted">Configure system preferences and settings.</p>
                </div>
                
                <div id="reports-section" class="content-section d-none">
                    <h2>Reports</h2>
                    <p class="text-muted">Generate and download system reports.</p>
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
                case 'users':
                    loadUsers();
                    break;
                case 'applications':
                    loadApplications();
                    break;
                // Add other cases as needed
            }
        }
        
        function loadUsers() {
            const container = document.getElementById('usersTableContainer');
            container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
            
            // This would load users via AJAX
            setTimeout(() => {
                container.innerHTML = '<p class="text-center text-muted">User management interface will be loaded here.</p>';
            }, 1000);
        }
        
        function loadApplications() {
            console.log('Loading applications...');
        }
        
        function showAddUserModal() {
            // Implementation for add user modal
            alert('Add user functionality will be implemented');
        }
    </script>
</body>
</html>
