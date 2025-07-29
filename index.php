<?php 
ob_start();
require_once 'includes/header.php';
require_once 'config/database.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Get university statistics for Quick Stats section
$universityStats = getUniversityStats();

// Get latest news
$news_query = "SELECT n.id, n.published_at, n.hero_image_url, t.title, t.summary 
              FROM news_articles n 
              JOIN news_translations t ON n.id = t.article_id 
              WHERE n.status = 'published' 
              AND t.language_code = ? 
              ORDER BY n.published_at DESC LIMIT 6";
$news_stmt = $db->prepare($news_query);
$news_stmt->execute([$current_lang]);

// Get upcoming events
$events_query = "SELECT e.id, e.start_datetime, e.location, t.title 
                FROM events e 
                JOIN event_translations t ON e.id = t.event_id 
                WHERE e.start_datetime >= NOW() 
                AND t.language_code = ? 
                ORDER BY e.start_datetime ASC LIMIT 4";
$events_stmt = $db->prepare($events_query);
$events_stmt->execute([$current_lang]);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-galaxy-bullets">
        <?php
        // Create dense galaxy field with multiple layers
        
        // Layer 1: Dense background stars
        for ($i = 0; $i < 80; $i++) {
            $size = rand(1, 3);
            $x = rand(0, 100) . '%';
            $y = rand(0, 100) . '%';
            $tx = (rand(-20, 20) / 10) . 'vw';
            $ty = (rand(-20, 20) / 10) . 'vh';
            $delay = rand(0, 30) . 's';
            $duration = rand(20, 40) . 's';
            $opacity = (rand(2, 5) / 10);
            
            echo '<span class="galaxy-bullet" style="
                width: ' . $size . 'px;
                height: ' . $size . 'px;
                left: ' . $x . ';
                top: ' . $y . ';
                --tx: ' . $tx . ';
                --ty: ' . $ty . ';
                animation-delay: ' . $delay . ';
                animation-duration: ' . $duration . ';
                opacity: ' . $opacity . ';
            "></span>';
        }
        
        // Layer 2: Medium stars
        for ($i = 0; $i < 40; $i++) {
            $size = rand(3, 6);
            $x = rand(0, 100) . '%';
            $y = rand(0, 100) . '%';
            $tx = (rand(-35, 35) / 10) . 'vw';
            $ty = (rand(-35, 35) / 10) . 'vh';
            $delay = rand(0, 25) . 's';
            $duration = rand(15, 30) . 's';
            $opacity = (rand(4, 7) / 10);
            
            echo '<span class="galaxy-bullet" style="
                width: ' . $size . 'px;
                height: ' . $size . 'px;
                left: ' . $x . ';
                top: ' . $y . ';
                --tx: ' . $tx . ';
                --ty: ' . $ty . ';
                animation-delay: ' . $delay . ';
                animation-duration: ' . $duration . ';
                opacity: ' . $opacity . ';
            "></span>';
        }
        
        // Layer 3: Bright focal stars
        for ($i = 0; $i < 20; $i++) {
            $size = rand(5, 10);
            $x = rand(0, 100) . '%';
            $y = rand(0, 100) . '%';
            $tx = (rand(-50, 50) / 10) . 'vw';
            $ty = (rand(-50, 50) / 10) . 'vh';
            $delay = rand(0, 20) . 's';
            $duration = rand(12, 25) . 's';
            $opacity = (rand(6, 9) / 10);
            
            echo '<span class="galaxy-bullet" style="
                width: ' . $size . 'px;
                height: ' . $size . 'px;
                left: ' . $x . ';
                top: ' . $y . ';
                --tx: ' . $tx . ';
                --ty: ' . $ty . ';
                animation-delay: ' . $delay . ';
                animation-duration: ' . $duration . ';
                opacity: ' . $opacity . ';
            "></span>';
        }
        ?>
    </div>
    <div class="hero-content">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-8 col-md-10 mx-auto text-center">
                    <div class="hero-logo-container fade-in-up">
                        <img src="<?php echo BASE_PATH; ?>/assets/images/logo.png" alt="UAB Logo" class="hero-logo mb-4">
                    </div>
                    <h1 class="hero-title fade-in-up delay-1"><?php echo __('welcome_message'); ?></h1>
                    <p class="hero-subtitle fade-in-up delay-2"><?php echo __('hero_subtitle'); ?></p>
                    <div class="hero-tagline fade-in-up delay-3">
                        <span class="tagline-text"><?php echo __('tagline'); ?></span>
                    </div>
                    <div class="hero-buttons fade-in-up delay-4">
                        <a href="<?php echo BASE_PATH; ?>/modules/admission/admissions.php" class="btn btn-primary btn-hero me-3">
                            <i class="bi bi-mortarboard me-2"></i><?php echo __('apply_now'); ?>
                        </a>
                        <a href="<?php echo BASE_PATH; ?>/modules/about/history.php" class="btn btn-outline-light btn-hero">
                            <i class="bi bi-info-circle me-2"></i><?php echo __('learn_more'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="scroll-indicator">
        <div class="scroll-arrow"></div>
    </div>
</section>

<!-- Announcement Banner -->
<section class="announcement-banner">
    <div class="container">
        <div class="alert alert-warning alert-dismissible fade show announcement-alert" role="alert">
            <i class="bi bi-megaphone me-2"></i>
            <strong><?php echo __('announcement'); ?>:</strong> <?php echo __('announcement_text'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</section>

<!-- Quick Stats Section -->
<section class="stats-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-number counter" data-target="<?php echo $universityStats['students']; ?>">0</div>
                    <div class="stat-label"><?php echo __('students'); ?></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div class="stat-number counter" data-target="<?php echo $universityStats['faculty']; ?>">3</div>
                    <div class="stat-label"><?php echo __('faculty'); ?></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <div class="stat-number counter" data-target="<?php echo $universityStats['programs']; ?>">1</div>
                    <div class="stat-label"><?php echo __('programs'); ?></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="stat-number counter" data-target="<?php echo $universityStats['years_established']; ?>">1</div>
                    <div class="stat-label"><?php echo __('years_established'); ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="main-content py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Latest News Section -->
            <div class="col-lg-8">
                <div class="section-header mb-5">
                    <h2 class="section-title"><?php echo __('latest_news'); ?></h2>
                    <p class="section-subtitle"><?php echo __('news_subtitle'); ?></p>
                </div>
                
                <div class="row g-4 mb-4">
                    <?php $news_count = 0; ?>
                    <?php while ($news = $news_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php $news_count++; ?>
                    <div class="col-lg-<?php echo $news_count <= 2 ? '6' : '4'; ?> col-md-6">
                        <article class="news-card h-100">
                            <?php if ($news['hero_image_url']): ?>
                            <div class="news-image">
                                <img src="<?php echo htmlspecialchars($news['hero_image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($news['title']); ?>">
                                <div class="news-overlay">
                                    <a href="<?php echo BASE_PATH; ?>/modules/news/news.php?id=<?php echo $news['id']; ?>" class="news-link">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="news-content">
                                <div class="news-meta">
                                    <time class="news-date">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo date('d M Y', strtotime($news['published_at'])); ?>
                                    </time>
                                </div>
                                <h3 class="news-title">
                                    <a href="<?php echo BASE_PATH; ?>/modules/news/news.php?id=<?php echo $news['id']; ?>"><?php echo htmlspecialchars($news['title']); ?></a>
                                </h3>
                                <p class="news-summary"><?php echo htmlspecialchars($news['summary']); ?></p>
                                <a href="<?php echo BASE_PATH; ?>/modules/news/news.php?id=<?php echo $news['id']; ?>" class="news-read-more">
                                    <?php echo __('read_more'); ?>
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                    <?php if ($news_count >= 6) break; ?>
                    <?php endwhile; ?>
                </div>
                
                <div class="text-center">
                    <a href="<?php echo BASE_PATH; ?>/modules/news/news.php" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-newspaper me-2"></i><?php echo __('all_news'); ?>
                    </a>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Upcoming Events -->
                <div class="sidebar-section mb-5">
                    <div class="section-header mb-4">
                        <h3 class="section-title"><?php echo __('upcoming_events'); ?></h3>
                    </div>
                    
                    <div class="events-list">
                        <?php while ($event = $events_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="event-item">
                            <div class="event-date">
                                <div class="event-day"><?php echo date('d', strtotime($event['start_datetime'])); ?></div>
                                <div class="event-month"><?php echo date('M', strtotime($event['start_datetime'])); ?></div>
                            </div>
                            <div class="event-details">
                                <h4 class="event-title">
                                    <a href="<?php echo BASE_PATH; ?>/modules/news/news.php?type=events&id=<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['title']); ?></a>
                                </h4>
                                <div class="event-meta">
                                    <div class="event-time">
                                        <i class="bi bi-clock me-1"></i>
                                        <?php echo date('H:i', strtotime($event['start_datetime'])); ?>
                                    </div>
                                    <div class="event-location">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?php echo htmlspecialchars($event['location']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="<?php echo BASE_PATH; ?>/modules/news/news.php?type=events" class="btn btn-outline-primary">
                            <i class="bi bi-calendar-event me-2"></i><?php echo __('all_events'); ?>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="sidebar-section">
                    <div class="section-header mb-4">
                        <h3 class="section-title"><?php echo __('sidebar_quick_links'); ?></h3>
                    </div>
                    
                    <div class="quick-links">
                        <a href="<?php echo BASE_PATH; ?>/modules/admission/admissions.php" class="quick-link-item">
                            <div class="quick-link-icon">
                                <i class="bi bi-mortarboard"></i>
                            </div>
                            <div class="quick-link-content">
                                <h4><?php echo __('admissions_title'); ?></h4>
                                <p><?php echo __('admissions_desc'); ?></p>
                            </div>
                            <i class="bi bi-arrow-right quick-link-arrow"></i>
                        </a>
                        
                        <a href="<?php echo BASE_PATH; ?>/modules/faculty/faculties.php" class="quick-link-item">
                            <div class="quick-link-icon">
                                <i class="bi bi-book"></i>
                            </div>
                            <div class="quick-link-content">
                                <h4><?php echo __('academics_title'); ?></h4>
                                <p><?php echo __('academics_desc'); ?></p>
                            </div>
                            <i class="bi bi-arrow-right quick-link-arrow"></i>
                        </a>
                        
                        <a href="#" class="quick-link-item">
                            <div class="quick-link-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="quick-link-content">
                                <h4><?php echo __('student_life_title'); ?></h4>
                                <p><?php echo __('student_life_desc'); ?></p>
                            </div>
                            <i class="bi bi-arrow-right quick-link-arrow"></i>
                        </a>
                        
                        <a href="<?php echo BASE_PATH; ?>/modules/contact/contact.php" class="quick-link-item">
                            <div class="quick-link-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="quick-link-content">
                                <h4><?php echo __('contact_us_title'); ?></h4>
                                <p><?php echo __('contact_us_desc'); ?></p>
                            </div>
                            <i class="bi bi-arrow-right quick-link-arrow"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>