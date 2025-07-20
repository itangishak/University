<?php
require_once 'includes/header.php';
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch all news
$query = "SELECT n.id, n.published_at, t.title, t.summary 
          FROM news_articles n 
          JOIN news_translations t ON n.id = t.article_id 
          WHERE n.status = 'published' AND t.language_code = ? 
          ORDER BY n.published_at DESC";
$stmt = $db->prepare($query);
$stmt->execute([$current_lang]);
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1><?php echo __('news_events'); ?></h1>

<?php foreach ($news as $item): ?>
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
        <p class="card-text"><?php echo htmlspecialchars($item['summary']); ?></p>
        <a href="/news/<?php echo $item['id']; ?>" class="btn btn-outline-primary"><?php echo __('read_more'); ?></a>
    </div>
</div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>