<?php
require_once 'includes/header.php';
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch alumni profiles (example)
$query = "SELECT u.first_name, u.last_name, a.graduation_year, a.current_position 
          FROM alumni_profiles a 
          JOIN users u ON a.user_id = u.id";
$stmt = $db->prepare($query);
$stmt->execute();
$alumni = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1><?php echo __('alumni'); ?></h1>

<?php foreach ($alumni as $alum): ?>
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($alum['first_name'] . ' ' . $alum['last_name']); ?></h5>
        <p><?php echo __('graduated'); ?>: <?php echo $alum['graduation_year']; ?></p>
        <p><?php echo __('position'); ?>: <?php echo htmlspecialchars($alum['current_position']); ?></p>
    </div>
</div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>