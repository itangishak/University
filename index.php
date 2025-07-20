<?php 
require_once 'includes/header.php';
require_once 'config/database.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Get latest news
$news_query = "SELECT n.id, n.published_at, n.hero_image_url, t.title, t.summary 
              FROM news_articles n 
              JOIN news_translations t ON n.id = t.article_id 
              WHERE n.status = 'published' 
              AND t.language_code = ? 
              ORDER BY n.published_at DESC LIMIT 5";
$news_stmt = $db->prepare($news_query);
$news_stmt->execute([$current_lang]);

// Get upcoming events
$events_query = "SELECT e.id, e.start_datetime, e.location, t.title 
                FROM events e 
                JOIN event_translations t ON e.id = t.event_id 
                WHERE e.start_datetime >= NOW() 
                AND t.language_code = ? 
                ORDER BY e.start_datetime ASC LIMIT 3";
$events_stmt = $db->prepare($events_query);
$events_stmt->execute([$current_lang]);
?>

<!-- Hero Banner -->
<div class="hero-banner mb-5">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/assets/images/campus-main.jpg" class="d-block w-100" alt="<?php echo __('campus_view'); ?>">
                <div class="carousel-caption">
                    <h1><?php echo __('welcome_message'); ?></h1>
                    <p><?php echo __('hero_subtitle'); ?></p>
                    <a href="/admissions" class="btn btn-primary btn-lg"><?php echo __('apply_now'); ?></a>
                </div>
            </div>
            <!-- Additional carousel items -->
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo __('previous'); ?></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo __('next'); ?></span>
        </button>
    </div>
</div>

<!-- Announcement Banner (if active) -->
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <div class="container">
        <strong><?php echo __('announcement'); ?>:</strong> <?php echo __('announcement_text'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<div class="row">
    <!-- Latest News Section -->
    <div class="col-md-8">
        <h2><?php echo __('latest_news'); ?></h2>
        <div class="row">
            <?php while ($news = $news_stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <?php if ($news['hero_image_url']): ?>
                    <img src="<?php echo htmlspecialchars($news['hero_image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($news['title']); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($news['summary']); ?></p>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted"><?php echo date('d M Y', strtotime($news['published_at'])); ?></small>
                        <a href="/news/<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-primary float-end"><?php echo __('read_more'); ?></a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <a href="/news" class="btn btn-outline-primary"><?php echo __('all_news'); ?></a>
    </div>
    
    <!-- Upcoming Events Section -->
    <div class="col-md-4">
        <h2><?php echo __('upcoming_events'); ?></h2>
        <div class="list-group mb-4">
            <?php while ($event = $events_stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <a href="/events/<?php echo $event['id']; ?>" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h5>
                </div>
                <p class="mb-1">
                    <i class="bi bi-calendar"></i> <?php echo date('d M Y H:i', strtotime($event['start_datetime'])); ?><br>
                    <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                </p>
            </a>
            <?php endwhile; ?>
        </div>
        <a href="/events" class="btn btn-outline-primary"><?php echo __('all_events'); ?></a>
        
        <!-- Key Stats Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h3><?php echo __('key_stats'); ?></h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="display-4 counter">3500+</div>
                        <div><?php echo __('students'); ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="display-4 counter">150+</div>
                        <div><?php echo __('faculty'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="display-4 counter">25+</div>
                        <div><?php echo __('programs'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="display-4 counter">95%</div>
                        <div><?php echo __('employment_rate'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>