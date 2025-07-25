<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Pagination settings
$items_per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_condition = '';
$search_params = [$current_lang];

if (!empty($search)) {
    $search_condition = 'AND (t.title LIKE ? OR t.summary LIKE ? OR t.body LIKE ?)';
    $search_term = '%' . $search . '%';
    $search_params = array_merge($search_params, [$search_term, $search_term, $search_term]);
}

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total 
                FROM news_articles n 
                JOIN news_translations t ON n.id = t.article_id 
                WHERE n.status = 'published' AND t.language_code = ? $search_condition";
$count_stmt = $db->prepare($count_query);
$count_stmt->execute($search_params);
$total_items = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_items / $items_per_page);

// Fetch featured news (latest 3)
$featured_query = "SELECT n.id, n.slug, n.hero_image_url, n.published_at, t.title, t.summary, t.body,
                          u.first_name, u.last_name
                   FROM news_articles n 
                   JOIN news_translations t ON n.id = t.article_id 
                   LEFT JOIN users u ON n.author_user_id = u.id
                   WHERE n.status = 'published' AND t.language_code = ? $search_condition
                   ORDER BY n.published_at DESC 
                   LIMIT 3";
$featured_stmt = $db->prepare($featured_query);
$featured_stmt->execute($search_params);
$featured_news = $featured_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all news with pagination
$news_query = "SELECT n.id, n.slug, n.hero_image_url, n.published_at, t.title, t.summary,
                      u.first_name, u.last_name
               FROM news_articles n 
               JOIN news_translations t ON n.id = t.article_id 
               LEFT JOIN users u ON n.author_user_id = u.id
               WHERE n.status = 'published' AND t.language_code = ? $search_condition
               ORDER BY n.published_at DESC 
               LIMIT " . (int)$items_per_page . " OFFSET " . (int)$offset;
$news_stmt = $db->prepare($news_query);
$news_stmt->execute($search_params);
$news_articles = $news_stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper function to format date
function formatDate($date, $lang) {
    $timestamp = strtotime($date);
    if ($lang === 'fr') {
        return strftime('%d %B %Y', $timestamp);
    } else {
        return date('F j, Y', $timestamp);
    }
}

// Helper function to truncate text
function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}
?>

<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="bi bi-newspaper me-3"></i>
                    <?php echo __('news_events'); ?>
                </h1>
                <p class="lead mb-4"><?php echo __('news_intro'); ?></p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <span class="badge bg-light text-primary px-3 py-2 fs-6">
                        <i class="bi bi-clock me-1"></i>
                        <?php echo __('latest_updates'); ?>
                    </span>
                    <span class="badge bg-light text-primary px-3 py-2 fs-6">
                        <i class="bi bi-globe me-1"></i>
                        <?php echo __('university_life'); ?>
                    </span>
                    <span class="badge bg-light text-primary px-3 py-2 fs-6">
                        <i class="bi bi-mortarboard me-1"></i>
                        <?php echo __('academic_news'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <form method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="<?php echo htmlspecialchars($search); ?>"
                                   placeholder="<?php echo __('search_news'); ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <?php echo __('search'); ?>
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="?" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($search)): ?>
<!-- Search Results Header -->
<section class="py-3 bg-white border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="bi bi-search me-2"></i>
                        <?php echo sprintf(__('search_results_for'), htmlspecialchars($search)); ?>
                    </h5>
                    <span class="badge bg-primary">
                        <?php echo sprintf(__('results_found'), $total_items); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($featured_news) && empty($search)): ?>
<!-- Featured News Section -->
<div class="container-fluid" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem 0;">
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h3 mb-3"><?php echo __('featured_news'); ?></h2>
            <p class="text-muted"><?php echo __('featured_news_desc'); ?></p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($featured_news as $index => $article): ?>
                <?php if ($index === 0): ?>
                    <!-- Main Featured Article -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-lg h-100 featured-card">
                            <?php if (!empty($article['hero_image_url'])): ?>
                                <div class="position-relative overflow-hidden" style="height: 300px;">
                                    <img src="<?php echo BASE_PATH . $article['hero_image_url']; ?>" 
                                         class="card-img-top h-100 w-100" 
                                         style="object-fit: cover;"
                                         alt="<?php echo htmlspecialchars($article['title']); ?>">
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25"></div>
                                    <div class="position-absolute bottom-0 start-0 p-4 text-white">
                                        <span class="badge bg-primary mb-2"><?php echo __('featured'); ?></span>
                                        <h3 class="h4 mb-2"><?php echo htmlspecialchars($article['title']); ?></h3>
                                        <p class="mb-0 opacity-75"><?php echo truncateText($article['summary'], 100); ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card-header bg-primary text-white py-4">
                                    <span class="badge bg-light text-primary mb-2"><?php echo __('featured'); ?></span>
                                    <h3 class="h4 mb-2"><?php echo htmlspecialchars($article['title']); ?></h3>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body p-4">
                                <?php if (empty($article['hero_image_url'])): ?>
                                    <p class="text-muted mb-3"><?php echo htmlspecialchars($article['summary']); ?></p>
                                <?php endif; ?>
                                
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo formatDate($article['published_at'], $current_lang); ?>
                                        <?php if (!empty($article['first_name'])): ?>
                                            <span class="mx-2">•</span>
                                            <i class="bi bi-person me-1"></i>
                                            <?php echo htmlspecialchars($article['first_name'] . ' ' . $article['last_name']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo BASE_PATH; ?>/modules/news/article.php?slug=<?php echo $article['slug']; ?>" 
                                       class="btn btn-primary">
                                        <?php echo __('read_more'); ?>
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if ($index === 1): ?>
                        <div class="col-lg-4">
                            <div class="row g-4">
                    <?php endif; ?>
                    
                    <!-- Secondary Featured Articles -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm h-100">
                            <?php if (!empty($article['hero_image_url'])): ?>
                                <div class="position-relative overflow-hidden" style="height: 150px;">
                                    <img src="<?php echo BASE_PATH . $article['hero_image_url']; ?>" 
                                         class="card-img-top h-100 w-100" 
                                         style="object-fit: cover;"
                                         alt="<?php echo htmlspecialchars($article['title']); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body p-3">
                                <h5 class="card-title h6 mb-2">
                                    <a href="<?php echo BASE_PATH; ?>/modules/news/article.php?slug=<?php echo $article['slug']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </a>
                                </h5>
                                <p class="card-text small text-muted mb-2">
                                    <?php echo truncateText($article['summary'], 80); ?>
                                </p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo formatDate($article['published_at'], $current_lang); ?>
                                    </small>
                                    <a href="<?php echo BASE_PATH; ?>/modules/news/article.php?slug=<?php echo $article['slug']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <?php echo __('read_more'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($index === count($featured_news) - 1): ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
</div>
<?php endif; ?>

<!-- All News Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h3 mb-3">
                <?php echo empty($search) ? __('all_news') : __('search_results'); ?>
            </h2>
            <?php if (empty($search)): ?>
                <p class="text-muted"><?php echo __('all_news_desc'); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($news_articles)): ?>
            <div class="row g-4 mb-5">
                <?php foreach ($news_articles as $article): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100 news-card">
                            <?php if (!empty($article['hero_image_url'])): ?>
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    <img src="<?php echo BASE_PATH . $article['hero_image_url']; ?>" 
                                         class="card-img-top h-100 w-100" 
                                         style="object-fit: cover;"
                                         alt="<?php echo htmlspecialchars($article['title']); ?>">
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <span class="badge bg-primary"><?php echo __('news'); ?></span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card-header bg-primary text-white text-center py-4">
                                    <i class="bi bi-newspaper" style="font-size: 2rem;"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3">
                                    <a href="<?php echo BASE_PATH; ?>/modules/news/article.php?slug=<?php echo $article['slug']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted mb-3">
                                    <?php echo truncateText($article['summary']); ?>
                                </p>
                                
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo formatDate($article['published_at'], $current_lang); ?>
                                    </div>
                                    <a href="<?php echo BASE_PATH; ?>/modules/news/article.php?slug=<?php echo $article['slug']; ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        <?php echo __('read_more'); ?>
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                                
                                <?php if (!empty($article['first_name'])): ?>
                                    <div class="mt-3 pt-3 border-top">
                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>
                                            <?php echo __('by'); ?> <?php echo htmlspecialchars($article['first_name'] . ' ' . $article['last_name']); ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="d-flex justify-content-center">
                    <nav aria-label="<?php echo __('news_pagination'); ?>">
                        <ul class="pagination pagination-lg">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                        <i class="bi bi-chevron-left"></i>
                                        <?php echo __('previous'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                        <?php echo __('next'); ?>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- No News Found -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-newspaper" style="font-size: 4rem; color: #dee2e6;"></i>
                </div>
                <h4 class="text-muted mb-3">
                    <?php echo empty($search) ? __('no_news_available') : __('no_search_results'); ?>
                </h4>
                <p class="text-muted mb-4">
                    <?php echo empty($search) ? __('no_news_desc') : __('no_search_results_desc'); ?>
                </p>
                <?php if (!empty($search)): ?>
                    <a href="?" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-1"></i>
                        <?php echo __('view_all_news'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Newsletter Subscription Section -->
<section class="newsletter-section py-5 position-relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="newsletter-bg position-absolute top-0 start-0 w-100 h-100"></div>
    
    <!-- Floating Elements -->
    <div class="floating-elements position-absolute top-0 start-0 w-100 h-100">
        <div class="floating-icon floating-icon-1">
            <i class="bi bi-envelope-heart"></i>
        </div>
        <div class="floating-icon floating-icon-2">
            <i class="bi bi-newspaper"></i>
        </div>
        <div class="floating-icon floating-icon-3">
            <i class="bi bi-bell"></i>
        </div>
        <div class="floating-icon floating-icon-4">
            <i class="bi bi-mortarboard"></i>
        </div>
    </div>
    
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="newsletter-card text-center">
                    <!-- Icon -->
                    <div class="newsletter-icon mb-4">
                        <div class="icon-wrapper">
                            <i class="bi bi-envelope-paper-heart"></i>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <h3 class="newsletter-title mb-3">
                        <i class="bi bi-sparkles me-2"></i>
                        <?php echo __('newsletter_signup'); ?>
                    </h3>
                    <p class="newsletter-desc mb-4">
                        <?php echo __('newsletter_desc'); ?>
                    </p>
                    
                    <!-- Subscription Form -->
                    <form class="newsletter-form" method="POST" action="<?php echo BASE_PATH; ?>/modules/newsletter/subscribe.php">
                        <div class="input-group newsletter-input-group">
                        
                            <input type="email" 
                                   class="form-control newsletter-input" 
                                   name="email" 
                                   placeholder="<?php echo __('enter_email'); ?>"
                                   required>
                            <button type="submit" class="btn newsletter-btn">
                                <span class="btn-text"><?php echo __('subscribe'); ?></span>
                                <span class="btn-icon">
                                    <i class="bi bi-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                        
                        <!-- Privacy Notice -->
                        <small class="newsletter-privacy mt-3 d-block">
                            <i class="bi bi-shield-lock me-1"></i>
                            <?php echo __('privacy_notice'); ?>
                        </small>
                    </form>
                    
                    <!-- Success Message (Hidden by default) -->
                    <div class="newsletter-success d-none mt-3">
                        <div class="alert alert-success border-0 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>
                            <?php echo __('subscription_success'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.featured-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}

.news-card:hover {
    transform: translateY(-3px);
    transition: transform 0.3s ease;
}

.news-card .card-title a:hover {
    color: var(--bs-primary) !important;
}

.hero-section {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark, #0056b3) 100%);
}

.pagination .page-link {
    border-radius: 0.5rem;
    margin: 0 0.25rem;
    border: none;
    color: var(--bs-primary);
}

.pagination .page-item.active .page-link {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.pagination .page-link:hover {
    background-color: var(--bs-primary);
    color: white;
}

/* Newsletter Section Styles */
.newsletter-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 500px;
    display: flex;
    align-items: center;
}

.newsletter-bg {
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    opacity: 0.3;
}

.floating-elements {
    pointer-events: none;
}

.floating-icon {
    position: absolute;
    color: rgba(255, 255, 255, 0.1);
    font-size: 2rem;
    animation: float 6s ease-in-out infinite;
}

.floating-icon-1 {
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.floating-icon-2 {
    top: 20%;
    right: 15%;
    animation-delay: 1.5s;
}

.floating-icon-3 {
    bottom: 30%;
    left: 15%;
    animation-delay: 3s;
}

.floating-icon-4 {
    bottom: 15%;
    right: 10%;
    animation-delay: 4.5s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(5deg);
    }
}

.newsletter-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 3rem 2rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.newsletter-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
}

.newsletter-icon {
    position: relative;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--bs-primary), #667eea);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    position: relative;
    overflow: hidden;
}

.icon-wrapper::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
    }
    100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
    }
}

.icon-wrapper i {
    font-size: 2rem;
    color: white;
    z-index: 1;
}

.newsletter-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.newsletter-desc {
    color: #4a5568;
    font-size: 1.1rem;
    line-height: 1.6;
}

.newsletter-features {
    background: rgba(var(--bs-primary-rgb), 0.05);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
}

.feature-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 0.5rem;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: rgba(var(--bs-primary-rgb), 0.1);
    transform: translateY(-2px);
}

.feature-item i {
    font-size: 1.5rem;
    color: var(--bs-primary);
    margin-bottom: 0.5rem;
}

.feature-item span {
    font-size: 0.9rem;
    font-weight: 600;
    color: #4a5568;
    text-align: center;
}

.newsletter-form {
    max-width: 400px;
    margin: 0 auto;
}

.newsletter-input-group {
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.newsletter-input-group:focus-within {
    border-color: var(--bs-primary);
    box-shadow: 0 8px 25px rgba(var(--bs-primary-rgb), 0.2);
    transform: translateY(-2px);
}

.newsletter-input-group .input-group-text {
    background: white;
    border: none;
    padding: 1rem 1.25rem;
    color: var(--bs-primary);
}

.newsletter-input {
    border: none;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    background: white;
}

.newsletter-input:focus {
    box-shadow: none;
    outline: none;
}

.newsletter-btn {
    background: linear-gradient(135deg, var(--bs-primary), #667eea);
    border: none;
    padding: 1rem 2rem;
    color: white;
    font-weight: 600;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.newsletter-btn:hover {
    background: linear-gradient(135deg, #667eea, var(--bs-primary));
    transform: translateX(2px);
    color: white;
}

.newsletter-btn .btn-text {
    transition: transform 0.3s ease;
}

.newsletter-btn .btn-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%) translateX(20px);
    opacity: 0;
    transition: all 0.3s ease;
}

.newsletter-btn:hover .btn-text {
    transform: translateX(-10px);
}

.newsletter-btn:hover .btn-icon {
    transform: translateY(-50%) translateX(0);
    opacity: 1;
}

.newsletter-privacy {
    color: #718096;
    font-size: 0.85rem;
    opacity: 0.8;
}

.newsletter-success {
    animation: slideInUp 0.5s ease;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .newsletter-card {
        padding: 2rem 1.5rem;
        margin: 1rem;
    }
    
    .newsletter-title {
        font-size: 1.5rem;
    }
    
    .newsletter-features {
        padding: 1rem;
    }
    
    .feature-item {
        padding: 0.75rem 0.25rem;
    }
    
    .feature-item span {
        font-size: 0.8rem;
    }
    
    .newsletter-input-group {
        flex-direction: column;
        border-radius: 16px;
    }
    
    .newsletter-input-group .input-group-text,
    .newsletter-input,
    .newsletter-btn {
        border-radius: 12px !important;
        margin: 2px;
    }
}
</style>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>