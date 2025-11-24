<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'faculty']))
    die('Access denied!');

require_once __DIR__ . '/../../config/db.php';
$pageTitle = 'Assignments';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$sql = "SELECT a.id,a.title,a.due_date, s.subject_name, a.created_at
      FROM assignments a LEFT JOIN subjects s ON a.subject_id=s.id";
$res = $conn->query($sql);
?>
<div class="container py-4">
    <h4>Assignments</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Subject</th>
                <th>Due</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($r = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['title']) ?></td>
                <td><?= htmlspecialchars($r['subject_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($r['due_date'] ?? '-') ?></td>
                <td><?= htmlspecialchars($r['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>