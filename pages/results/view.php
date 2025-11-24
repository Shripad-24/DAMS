<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'faculty']))
  die('Access denied!');
require_once __DIR__ . '/../../config/db.php';
$pageTitle = 'All Results';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$sql = "SELECT r.id, s.name AS student, s.roll, sub.subject_name, r.marks, r.total_marks, r.semester, r.created_at
      FROM results r
      JOIN students s ON r.student_id=s.id
      JOIN subjects sub ON r.subject_id=sub.id";
$res = $conn->query($sql);
?>
<div class="container py-4">
    <h4>Results</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Roll</th>
                <th>Subject</th>
                <th>Marks</th>
                <th>Sem</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($r = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['student']) ?></td>
                <td><?= htmlspecialchars($r['roll']) ?></td>
                <td><?= htmlspecialchars($r['subject_name']) ?></td>
                <td><?= (int) $r['marks'] ?>/<?= (int) $r['total_marks'] ?></td>
                <td><?= htmlspecialchars($r['semester']) ?></td>
                <td><?= htmlspecialchars($r['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>