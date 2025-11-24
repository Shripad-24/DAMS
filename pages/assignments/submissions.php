<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'faculty']))
    die('Access denied!');

require_once __DIR__ . '/../../config/db.php';
$pageTitle = 'Submissions';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

if (isset($_GET['set']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $set = in_array($_GET['set'], ['Pending', 'Submitted', 'Approved']) ? $_GET['set'] : 'Pending';
    $stmt = $conn->prepare("UPDATE submissions SET status=? WHERE id=?");
    $stmt->bind_param('si', $set, $id);
    $stmt->execute();
    header('Location: /dams/pages/assignments/submissions.php');
    exit;
}

$sql = "SELECT sub.id, a.title, s.name AS student, s.roll, sub.file_path, sub.status, sub.submitted_at
      FROM submissions sub
      JOIN assignments a ON sub.assignment_id=a.id
      JOIN students s ON sub.student_id=s.id";

$res = $conn->query($sql);
?>
<div class="container py-4">
    <h4>Submissions</h4>
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Assignment</th>
                <th>Student</th>
                <th>File</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($r = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['title']) ?></td>
                <td><?= htmlspecialchars($r['student']) ?> (<?= htmlspecialchars($r['roll']) ?>)</td>
                <td><a target="_blank" href="<?= htmlspecialchars($r['file_path']) ?>">Open</a></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($r['status']) ?></span></td>
                <td><?= htmlspecialchars($r['submitted_at']) ?></td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a class="btn btn-outline-secondary" href="?set=Pending&id=<?= $r['id'] ?>">Pending</a>
                        <a class="btn btn-outline-secondary" href="?set=Submitted&id=<?= $r['id'] ?>">Submitted</a>
                        <a class="btn btn-outline-success" href="?set=Approved&id=<?= $r['id'] ?>">Approve</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>