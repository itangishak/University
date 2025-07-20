<?php
require_once '../../includes/header.php';
require_once '../../includes/functions.php';

// Courses module logic here
$db = getDbConnection();
$stmt = $db->query("SELECT * FROM courses ORDER BY department, course_code");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="courses-section">
    <h1><?php echo translate('course_catalog'); ?></h1>
    <?php foreach ($courses as $course): ?>
        <div class="course-item">
            <h2><?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['title']); ?></h2>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($course['department']); ?></p>
            <p><strong>Credits:</strong> <?php echo htmlspecialchars($course['credits']); ?></p>
            <p><?php echo htmlspecialchars($course['description']); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>
