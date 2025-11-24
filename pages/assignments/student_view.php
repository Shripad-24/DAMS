<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student')
    die('Access denied!');

require_once __DIR__ . '/../../config/db.php';
$pageTitle = 'Assignments (Students)';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$res = $conn->query("SELECT a.id,a.title,a.description,a.due_date,s.subject_name
                   FROM assignments a LEFT JOIN subjects s ON a.subject_id=s.id");
?>
<div class="container py-4">
    <h4>Assignments</h4>
    <div class="row g-3">
        <?php while ($a = $res->fetch_assoc()): ?>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($a['title']) ?></h5>
                    <div class="text-muted mb-2"><?= htmlspecialchars($a['subject_name'] ?? '-') ?></div>
                    <p class="card-text"><?= nl2br(htmlspecialchars($a['description'])) ?></p>
                    <div class="small text-muted">Due: <?= htmlspecialchars($a['due_date'] ?? '-') ?></div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>