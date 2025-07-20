<?php
require_once 'includes/header.php';
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch faculties
$query = "SELECT f.id, f.code, t.name, t.description 
          FROM faculties f 
          JOIN faculty_translations t ON f.id = t.faculty_id 
          WHERE t.language_code = ?";
$stmt = $db->prepare($query);
$stmt->execute([$current_lang]);
$faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1><?php echo __('faculties_departments'); ?></h1>

<?php foreach ($faculties as $faculty): ?>
<div class="card mb-4">
    <div class="card-header">
        <h2><?php echo htmlspecialchars($faculty['name']); ?> (<?php echo $faculty['code']; ?>)</h2>
    </div>
    <div class="card-body">
        <p><?php echo htmlspecialchars($faculty['description']); ?></p>
        <!-- Departments list would go here, querying departments table -->
    </div>
</div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>