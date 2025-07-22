<?php
require_once '../../includes/header.php';
require_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch about page content
$query = "SELECT p.slug, t.title, t.content 
          FROM static_pages p 
          JOIN page_translations t ON p.id = t.page_id 
          WHERE p.slug = 'about' AND t.language_code = ?";
$stmt = $db->prepare($query);
$stmt->execute([$current_lang]);
$about = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-12">
        <h1><?php echo htmlspecialchars($about['title'] ?? __('about_us')); ?></h1>
        <div class="content">
            <?php echo $about['content'] ?? '<p>' . __('about_description') . '</p>'; ?>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>