<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_performed = !empty($query);

// Pagination settings
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Initialize results arrays
$news_results = [];
$faculty_results = [];
$program_results = [];
$total_results = 0;

if ($search_performed) {
    $search_term = '%' . $query . '%';
    
    // Search News Articles
    try {
        $news_query = "SELECT n.id, n.slug, n.hero_image_url, n.published_at, t.title, t.summary,
                              u.first_name, u.last_name, 'news' as result_type
                       FROM news_articles n 
                       JOIN news_translations t ON n.id = t.article_id 
                       LEFT JOIN users u ON n.author_user_id = u.id
                       WHERE n.status = 'published' AND t.language_code = ? 
                       AND (t.title LIKE ? OR t.summary LIKE ? OR t.body LIKE ?)
                       ORDER BY n.published_at DESC 
                       LIMIT 5";
        $news_stmt = $db->prepare($news_query);
        $news_stmt->execute([$current_lang, $search_term, $search_term, $search_term]);
        $news_results = $news_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle gracefully if news tables don't exist
        $news_results = [];
    }
    
    // Search Faculty (assuming faculty table exists)
    try {
        $faculty_query = "SELECT f.id, ft.name, ft.title, ft.bio, f.image_url, 'faculty' as result_type
                         FROM faculty f 
                         JOIN faculty_translations ft ON f.id = ft.faculty_id
                         WHERE ft.language_code = ? 
                         AND (ft.name LIKE ? OR ft.title LIKE ? OR ft.bio LIKE ?)
                         ORDER BY ft.name ASC 
                         LIMIT 5";
        $faculty_stmt = $db->prepare($faculty_query);
        $faculty_stmt->execute([$current_lang, $search_term, $search_term, $search_term]);
        $faculty_results = $faculty_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle gracefully if faculty tables don't exist
        $faculty_results = [];
    }
    
    // Search Programs (assuming program table exists)
    try {
        $program_query = "SELECT p.id, pt.name, pt.description, p.image_url, 'program' as result_type
                         FROM programs p 
                         JOIN program_translations pt ON p.id = pt.program_id
                         WHERE pt.language_code = ? 
                         AND (pt.name LIKE ? OR pt.description LIKE ?)
                         ORDER BY pt.name ASC 
                         LIMIT 5";
        $program_stmt = $db->prepare($program_query);
        $program_stmt->execute([$current_lang, $search_term, $search_term]);
        $program_results = $program_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle gracefully if program tables don't exist
        $program_results = [];
    }
    
    $total_results = count($news_results) + count($faculty_results) + count($program_results);
}

// Helper function to highlight search terms
function highlightSearchTerm($text, $term) {
    if (empty($term)) return $text;
    return preg_replace('/(' . preg_quote($term, '/') . ')/i', '<mark>$1</mark>', $text);
}

// Helper function to truncate text
function truncateText($text, $length = 150) {
    return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
}
?>

<!-- Search Form Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <form method="GET" class="search-form-main">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0 ps-0" 
                               name="q" 
                               value="<?php echo htmlspecialchars($query); ?>"
                               placeholder="<?php echo __('search_placeholder'); ?>"
                               autofocus>
                        <button type="submit" class="btn btn-primary px-4">
                            <?php echo __('search'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php if ($search_performed): ?>
<!-- Search Results Header -->
<section class="py-3 bg-white border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="mb-0">
                        <i class="bi bi-search me-2"></i>
                        <?php echo sprintf(__('search_results_for'), '<strong>' . htmlspecialchars($query) . '</strong>'); ?>
                    </h5>
                    <span class="badge bg-primary fs-6">
                        <?php echo sprintf(__('results_found'), $total_results); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($total_results > 0): ?>
<!-- Search Results -->
<section class="py-5">
    <div class="container">
        
        <?php if (!empty($news_results)): ?>
        <!-- News Results -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4">
                <h3 class="h4 mb-0 me-3">
                    <i class="bi bi-newspaper text-primary me-2"></i>
                    <?php echo __('news_articles'); ?>
                </h3>
                <span class="badge bg-light text-dark"><?php echo count($news_results); ?></span>
                <a href="<?php echo BASE_PATH; ?>/university/modules/news/news.php?search=<?php echo urlencode($query); ?>" 
                   class="btn btn-sm btn-outline-primary ms-auto">
                    <?php echo __('view_all_news'); ?>
                </a>
            </div>
            <div class="row g-4">
                <?php foreach ($news_results as $article): ?>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <?php if (!empty($article['hero_image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($article['hero_image_url']); ?>" 
                             class="card-img-top" style="height: 200px; object-fit: cover;" 
                             alt="<?php echo htmlspecialchars($article['title']); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo highlightSearchTerm(htmlspecialchars($article['title']), $query); ?>
                            </h5>
                            <p class="card-text text-muted">
                                <?php echo highlightSearchTerm(truncateText(htmlspecialchars($article['summary'])), $query); ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    <?php echo date('M j, Y', strtotime($article['published_at'])); ?>
                                </small>
                                <a href="<?php echo BASE_PATH; ?>/university/modules/news/article.php?slug=<?php echo $article['slug']; ?>" 
                                   class="btn btn-sm btn-primary">
                                    <?php echo __('read_more'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($faculty_results)): ?>
        <!-- Faculty Results -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4">
                <h3 class="h4 mb-0 me-3">
                    <i class="bi bi-people text-success me-2"></i>
                    <?php echo __('faculty_members'); ?>
                </h3>
                <span class="badge bg-light text-dark"><?php echo count($faculty_results); ?></span>
                <a href="<?php echo BASE_PATH; ?>/university/modules/faculty/faculties.php?search=<?php echo urlencode($query); ?>" 
                   class="btn btn-sm btn-outline-success ms-auto">
                    <?php echo __('view_all_faculty'); ?>
                </a>
            </div>
            <div class="row g-4">
                <?php foreach ($faculty_results as $faculty): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <?php if (!empty($faculty['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($faculty['image_url']); ?>" 
                             class="card-img-top rounded-circle mx-auto mt-3" 
                             style="width: 100px; height: 100px; object-fit: cover;" 
                             alt="<?php echo htmlspecialchars($faculty['name']); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo highlightSearchTerm(htmlspecialchars($faculty['name']), $query); ?>
                            </h5>
                            <p class="card-text text-muted">
                                <?php echo highlightSearchTerm(htmlspecialchars($faculty['title']), $query); ?>
                            </p>
                            <p class="card-text small">
                                <?php echo highlightSearchTerm(truncateText(htmlspecialchars($faculty['bio'])), $query); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($program_results)): ?>
        <!-- Program Results -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4">
                <h3 class="h4 mb-0 me-3">
                    <i class="bi bi-mortarboard text-warning me-2"></i>
                    <?php echo __('academic_programs'); ?>
                </h3>
                <span class="badge bg-light text-dark"><?php echo count($program_results); ?></span>
                <a href="<?php echo BASE_PATH; ?>/university/modules/program/programs.php?search=<?php echo urlencode($query); ?>" 
                   class="btn btn-sm btn-outline-warning ms-auto">
                    <?php echo __('view_all_programs'); ?>
                </a>
            </div>
            <div class="row g-4">
                <?php foreach ($program_results as $program): ?>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <?php if (!empty($program['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($program['image_url']); ?>" 
                             class="card-img-top" style="height: 200px; object-fit: cover;" 
                             alt="<?php echo htmlspecialchars($program['name']); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo highlightSearchTerm(htmlspecialchars($program['name']), $query); ?>
                            </h5>
                            <p class="card-text">
                                <?php echo highlightSearchTerm(truncateText(htmlspecialchars($program['description'])), $query); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<?php else: ?>
<!-- No Results -->
<section class="py-5">
    <div class="container">
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-search" style="font-size: 4rem; color: #dee2e6;"></i>
            </div>
            <h3 class="h4 mb-3"><?php echo __('no_results_found'); ?></h3>
            <p class="text-muted mb-4">
                <?php echo sprintf(__('no_results_message'), '<strong>' . htmlspecialchars($query) . '</strong>'); ?>
            </p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title"><?php echo __('search_suggestions'); ?></h6>
                            <ul class="list-unstyled mb-0 text-start">
                                <li><i class="bi bi-check text-success me-2"></i><?php echo __('check_spelling'); ?></li>
                                <li><i class="bi bi-check text-success me-2"></i><?php echo __('try_different_keywords'); ?></li>
                                <li><i class="bi bi-check text-success me-2"></i><?php echo __('use_more_general_terms'); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php else: ?>
<!-- Search Instructions -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="mb-5">
                    <i class="bi bi-search" style="font-size: 4rem; color: #dee2e6;"></i>
                </div>
                <h3 class="h4 mb-4"><?php echo __('search_university_content'); ?></h3>
                <p class="text-muted mb-5"><?php echo __('search_instructions'); ?></p>
                
                <!-- Search Categories -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-newspaper text-primary mb-3" style="font-size: 2rem;"></i>
                                <h5 class="card-title"><?php echo __('news_articles'); ?></h5>
                                <p class="card-text text-muted"><?php echo __('search_news_desc'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-people text-success mb-3" style="font-size: 2rem;"></i>
                                <h5 class="card-title"><?php echo __('faculty_members'); ?></h5>
                                <p class="card-text text-muted"><?php echo __('search_faculty_desc'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-mortarboard text-warning mb-3" style="font-size: 2rem;"></i>
                                <h5 class="card-title"><?php echo __('academic_programs'); ?></h5>
                                <p class="card-text text-muted"><?php echo __('search_programs_desc'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
.search-form-main .input-group-text {
    border-right: none;
}

.search-form-main .form-control {
    border-left: none;
    box-shadow: none;
}

.search-form-main .form-control:focus {
    border-color: #ced4da;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.search-form-main .input-group:focus-within .input-group-text {
    border-color: #86b7fe;
}

mark {
    background-color: #fff3cd;
    padding: 0.1em 0.2em;
    border-radius: 0.2em;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}

.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
