<?php
require_once '../../includes/header.php';
require_once '../../includes/functions.php';

// News module logic here
$db = getDbConnection();
$stmt = $db->query("SELECT * FROM news ORDER BY date DESC");
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="news-section">
    <h1><?php echo translate('latest_news'); ?></h1>
    <?php foreach ($news as $item): ?>
        <article class="news-item">
            <h2><?php echo htmlspecialchars($item['title']); ?></h2>
            <div class="date"><?php echo date('F j, Y', strtotime($item['date'])); ?></div>
            <p><?php echo htmlspecialchars($item['summary']); ?></p>
            <a href="view.php?id=<?php echo $item['id']; ?>"><?php echo translate('read_more'); ?></a>
        </article>
    <?php endforeach; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>
