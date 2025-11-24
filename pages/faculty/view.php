<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin')
  die('Access denied!');
require_once __DIR__ . '/../../config/db.php';
$pageTitle = 'Faculty List';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
$res = $conn->query("SELECT id, name, department, username FROM faculty");
?>
<div class="container py-4">
    <h4>Faculty Members</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($r = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['name']) ?></td>
                <td><?= htmlspecialchars($r['department']) ?></td>
                <td><?= htmlspecialchars($r['username'] ?? '-') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>