<?php
require_once 'includes/header.php';
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch programs
$query = "SELECT p.id, p.program_code, p.level, t.name, t.description 
          FROM programs p 
          JOIN program_translations t ON p.id = t.program_id 
          WHERE t.language_code = ?";
$stmt = $db->prepare($query);
$stmt->execute([$current_lang]);
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1><?php echo __('programs'); ?></h1>

<!-- Filters -->
<form class="mb-4">
    <select class="form-select d-inline-block w-auto" name="level">
        <option><?php echo __('all_levels'); ?></option>
        <option value="Bachelor"><?php echo __('bachelor'); ?></option>
        <option value="Master"><?php echo __('master'); ?></option>
    </select>
    <button class="btn btn-primary"><?php echo __('filter'); ?></button>
</form>

<div class="row">
    <?php foreach ($programs as $program): ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($program['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($program['description']); ?></p>
                <p><strong><?php echo __('level'); ?>:</strong> <?php echo $program['level']; ?></p>
                <a href="/programs/<?php echo $program['id']; ?>" class="btn btn-outline-primary"><?php echo __('details'); ?></a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>