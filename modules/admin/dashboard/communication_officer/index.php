<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../../includes/Auth.php';
require_once __DIR__ . '/../../../../includes/NotificationSystem.php';

$auth = new Auth();
$auth->requireRole('communication_officer');

$user = $auth->getCurrentUser();
$db = Database::getInstance();

// Get content statistics
$stats = [
    'total_articles' => $db->fetch("SELECT COUNT(*) as count FROM news_articles")['count'],
    'total_events' => $db->fetch("SELECT COUNT(*) as count FROM events")['count'],
    'total_media' => $db->fetch("SELECT COUNT(*) as count FROM media_gallery")['count'],
    'pending_content' => $db->fetch("SELECT COUNT(*) as count FROM news_articles WHERE status = 'draft'")['count']
];

// Get recent articles
$recentArticles = $db->fetchAll(
    "SELECT na.*, nat.title, nat.content_preview
     FROM news_articles na
     JOIN news_article_translations nat ON na.id = nat.article_id AND nat.language_code = ?
     ORDER BY na.created_at DESC LIMIT 8",
    [$user['preferred_language']]
);

// Get recent events
$recentEvents = $db->fetchAll(
    "SELECT e.*, et.title, et.description
     FROM events e
     JOIN event_translations et ON e.id = et.event_id AND et.language_code = ?
     ORDER BY e.created_at DESC LIMIT 8",
    [$user['preferred_language']]
);
?>
<!DOCTYPE html>
<html lang="<?php echo $user['preferred_language']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication Officer Dashboard - BAU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
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
        .content-item {
            border-left: 4px solid #6f42c1;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .content-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-comm {
            border-radius: 8px;
            font-weight: 500;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 4px 12px;
            border-radius: 20px;
        }
        .editor-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        #editor {
            height: 300px;
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
                        <i class="bi bi-megaphone text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mb-0">Content Hub</h5>
                    <small class="opacity-75">Communication Center</small>
                </div>
                
                <div class="text-center mb-4">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2 d-inline-block mb-2">
                        <i class="bi bi-person-video3" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-0"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                    <small class="opacity-75">Communication Officer</small>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#dashboard" data-section="dashboard">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="#articles" data-section="articles">
                        <i class="bi bi-newspaper me-2"></i>News Articles
                    </a>
                    <a class="nav-link" href="#events" data-section="events">
                        <i class="bi bi-calendar-event me-2"></i>Events
                    </a>
                    <a class="nav-link" href="#media" data-section="media">
                        <i class="bi bi-images me-2"></i>Media Gallery
                    </a>
                    <a class="nav-link" href="#pages" data-section="pages">
                        <i class="bi bi-file-earmark-text me-2"></i>Static Pages
                    </a>
                    <a class="nav-link" href="#analytics" data-section="analytics">
                        <i class="bi bi-graph-up me-2"></i>Content Analytics
                    </a>
                    <a class="nav-link" href="#schedule" data-section="schedule">
                        <i class="bi bi-clock-history me-2"></i>Scheduling
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
                            <h2>Content Dashboard</h2>
                            <p class="text-muted">Manage university communications and content.</p>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary btn-comm me-2" onclick="showSection('articles')">
                                <i class="bi bi-plus-lg me-2"></i>New Article
                            </button>
                            <button class="btn btn-success btn-comm" onclick="showSection('events')">
                                <i class="bi bi-calendar-plus me-2"></i>New Event
                            </button>
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-newspaper" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['total_articles']; ?></div>
                                        <div class="opacity-75">Articles</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-calendar-event" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['total_events']; ?></div>
                                        <div class="opacity-75">Events</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-images" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['total_media']; ?></div>
                                        <div class="opacity-75">Media Files</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number"><?php echo $stats['pending_content']; ?></div>
                                        <div class="opacity-75">Pending</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Content -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="dashboard-card card">
                                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Recent Articles</h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="showSection('articles')">
                                        View All
                                    </button>
                                </div>
                                <div class="card-body">
                                    <?php foreach (array_slice($recentArticles, 0, 4) as $article): ?>
                                    <div class="content-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($article['title']); ?></h6>
                                                <p class="text-muted small mb-2"><?php echo htmlspecialchars(substr($article['content_preview'] ?? '', 0, 100)); ?>...</p>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    <?php echo date('M j, Y', strtotime($article['created_at'])); ?>
                                                </small>
                                            </div>
                                            <span class="status-badge bg-<?php echo $article['status'] === 'published' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($article['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="dashboard-card card">
                                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Recent Events</h6>
                                    <button class="btn btn-sm btn-outline-success" onclick="showSection('events')">
                                        View All
                                    </button>
                                </div>
                                <div class="card-body">
                                    <?php foreach (array_slice($recentEvents, 0, 4) as $event): ?>
                                    <div class="content-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                                <p class="text-muted small mb-2"><?php echo htmlspecialchars(substr($event['description'] ?? '', 0, 100)); ?>...</p>
                                                <small class="text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    <?php echo htmlspecialchars($event['location'] ?? 'TBA'); ?>
                                                    <span class="ms-2">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        <?php echo date('M j', strtotime($event['event_date'])); ?>
                                                    </span>
                                                </small>
                                            </div>
                                            <span class="status-badge bg-<?php echo $event['status'] === 'published' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($event['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Articles Section -->
                <div id="articles-section" class="content-section d-none">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2>News Articles</h2>
                            <p class="text-muted">Create and manage news articles.</p>
                        </div>
                        <button class="btn btn-primary btn-comm" onclick="showArticleEditor()">
                            <i class="bi bi-plus-lg me-2"></i>New Article
                        </button>
                    </div>
                    
                    <!-- Article Editor -->
                    <div id="articleEditor" class="d-none mb-4">
                        <div class="dashboard-card card">
                            <div class="card-header bg-transparent">
                                <h6 class="mb-0">Article Editor</h6>
                            </div>
                            <div class="card-body">
                                <form id="articleForm">
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <label for="articleTitle" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="articleTitle" name="title" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="articleCategory" class="form-label">Category</label>
                                            <select class="form-select" id="articleCategory" name="category_id">
                                                <option value="">Select category...</option>
                                                <option value="1">General News</option>
                                                <option value="2">Academic</option>
                                                <option value="3">Campus Life</option>
                                                <option value="4">Research</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="articleSummary" class="form-label">Summary</label>
                                        <textarea class="form-control" id="articleSummary" name="summary" rows="2" 
                                                  placeholder="Brief summary for article preview..."></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Content</label>
                                        <div class="editor-container">
                                            <div id="editor"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="articleTags" class="form-label">Tags</label>
                                            <input type="text" class="form-control" id="articleTags" name="tags" 
                                                   placeholder="Separate tags with commas...">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="articleStatus" class="form-label">Status</label>
                                            <select class="form-select" id="articleStatus" name="status">
                                                <option value="draft">Draft</option>
                                                <option value="published">Published</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="publishDate" class="form-label">Publish Date</label>
                                            <input type="datetime-local" class="form-control" id="publishDate" name="publish_date">
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary" onclick="hideArticleEditor()">
                                            Cancel
                                        </button>
                                        <div>
                                            <button type="button" class="btn btn-outline-primary me-2" onclick="saveArticleDraft()">
                                                Save Draft
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                Publish Article
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Articles List -->
                    <div id="articlesList">
                        <div class="dashboard-card card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Search articles..." id="articleSearch">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" id="statusFilter">
                                            <option value="">All Status</option>
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-outline-primary w-100" onclick="loadArticles()">
                                            <i class="bi bi-search me-2"></i>Search
                                        </button>
                                    </div>
                                </div>
                                
                                <div id="articlesTableContainer">
                                    <!-- Articles table will be loaded here -->
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Other sections placeholders -->
                <div id="events-section" class="content-section d-none">
                    <h2>Events Management</h2>
                    <p class="text-muted">Create and manage university events.</p>
                </div>
                
                <div id="media-section" class="content-section d-none">
                    <h2>Media Gallery</h2>
                    <p class="text-muted">Manage images, videos, and other media files.</p>
                </div>
                
                <div id="pages-section" class="content-section d-none">
                    <h2>Static Pages</h2>
                    <p class="text-muted">Manage static website pages.</p>
                </div>
                
                <div id="analytics-section" class="content-section d-none">
                    <h2>Content Analytics</h2>
                    <p class="text-muted">View content performance and engagement metrics.</p>
                </div>
                
                <div id="schedule-section" class="content-section d-none">
                    <h2>Content Scheduling</h2>
                    <p class="text-muted">Schedule content publication and manage editorial calendar.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        const authToken = sessionStorage.getItem('auth_token');
        let quill;
        
        // Initialize Quill editor
        document.addEventListener('DOMContentLoaded', function() {
            quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });
        });
        
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
                case 'articles':
                    loadArticles();
                    break;
                case 'events':
                    loadEvents();
                    break;
                // Add other cases as needed
            }
        }
        
        function showArticleEditor() {
            document.getElementById('articleEditor').classList.remove('d-none');
            document.getElementById('articlesList').classList.add('d-none');
        }
        
        function hideArticleEditor() {
            document.getElementById('articleEditor').classList.add('d-none');
            document.getElementById('articlesList').classList.remove('d-none');
        }
        
        function loadArticles() {
            const container = document.getElementById('articlesTableContainer');
            container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
            
            // This would load articles via AJAX
            setTimeout(() => {
                container.innerHTML = '<p class="text-center text-muted">Articles list will be loaded here.</p>';
            }, 1000);
        }
        
        function loadEvents() {
            console.log('Loading events...');
        }
        
        function saveArticleDraft() {
            // Implementation for saving article draft
            alert('Article draft saved');
        }
        
        // Article form submission
        document.getElementById('articleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('content', quill.root.innerHTML);
            
            // This would submit the article via AJAX
            alert('Article published successfully');
            hideArticleEditor();
        });
    </script>
</body>
</html>
