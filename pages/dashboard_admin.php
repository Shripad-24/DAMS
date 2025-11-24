<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') die('Access denied!');
require_once __DIR__ . '/../config/db.php';
$pageTitle='Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

function countTbl($conn,$tbl){ $r=$conn->query("SELECT COUNT(*) c FROM $tbl"); $x=$r?$r->fetch_assoc()['c']:0; return (int)$x; }
$counts = [
  'students' => countTbl($conn,'students'),
  'faculty' => countTbl($conn,'faculty'),
  'subjects' => countTbl($conn,'subjects'),
  'assignments' => countTbl($conn,'assignments'),
];
$recentStudents = $conn->query("SELECT name, roll, course FROM students ORDER BY id DESC LIMIT 5");
$recentAssignments = $conn->query("SELECT title, due_date FROM assignments ORDER BY id DESC LIMIT 5");
?>
<div class="container py-4">
  <h4 class="mb-3">Admin Dashboard</h4>
  <div class="row g-3">
    <?php foreach($counts as $k=>$v): ?>
      <div class="col-md-3">
        <div class="card shadow-sm"><div class="card-body text-center">
          <div class="display-6"><?= $v ?></div>
          <div class="text-uppercase small"><?= htmlspecialchars($k) ?></div>
        </div></div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="row g-3 mt-3">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header fw-bold">Recent Students</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <?php while($s=$recentStudents->fetch_assoc()): ?>
              <li class="list-group-item"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['roll']) ?>) – <?= htmlspecialchars($s['course']) ?></li>
            <?php endwhile; ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header fw-bold">Recent Assignments</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <?php while($a=$recentAssignments->fetch_assoc()): ?>
              <li class="list-group-item"><?= htmlspecialchars($a['title']) ?> (Due: <?= htmlspecialchars($a['due_date']??'-') ?>)</li>
            <?php endwhile; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
