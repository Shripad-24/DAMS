<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'faculty']))
  die('Access denied!');
require_once __DIR__ . '/../../config/db.php';
$pageTitle = 'Subjects';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$sql = "SELECT sub.id, sub.subject_name, f.name AS faculty 
        FROM subjects sub
        LEFT JOIN faculty f ON sub.faculty_id = f.id";
$res = $conn->query($sql);
?>
<div class="container py-4">
    <h4>Subjects</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Faculty</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($s = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['subject_name']) ?></td>
                <td><?= htmlspecialchars($s['faculty'] ?? '—') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>