<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') die('Access denied!');
require_once __DIR__ . '/../config/db.php';
$pageTitle='Faculty Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

$uname = $_SESSION['user']['username'];
$fac = $conn->prepare("SELECT id, name FROM faculty WHERE username=? LIMIT 1");
$fac->bind_param('s',$uname);
$fac->execute();
$faculty = $fac->get_result()->fetch_assoc();

$subjects = $conn->query("SELECT id, subject_name FROM subjects WHERE faculty_id=". (int)($faculty['id']??0) ." ORDER BY subject_name");

$pendingSQL = "SELECT sub.id, a.title, s.name AS student, sub.status
               FROM submissions sub
               JOIN assignments a ON sub.assignment_id=a.id
               JOIN students s ON sub.student_id=s.id
               WHERE sub.status <> 'Approved'
               ORDER BY sub.id DESC LIMIT 10";
$pending = $conn->query($pendingSQL);
?>
<div class="container py-4">
  <h4 class="mb-3">Welcome, <?= htmlspecialchars($faculty['name'] ?? $uname) ?></h4>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header fw-bold">Your Subjects</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <?php if($subjects && $subjects->num_rows): while($s=$subjects->fetch_assoc()): ?>
              <li class="list-group-item"><?= htmlspecialchars($s['subject_name']) ?></li>
            <?php endwhile; else: ?>
              <li class="list-group-item">No subjects assigned yet.</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header fw-bold">Pending/Recent Submissions</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <?php if($pending && $pending->num_rows): while($p=$pending->fetch_assoc()): ?>
              <li class="list-group-item">
                <strong><?= htmlspecialchars($p['title']) ?></strong>
                — <?= htmlspecialchars($p['student']) ?>
                <span class="badge bg-secondary ms-2"><?= htmlspecialchars($p['status']) ?></span>
              </li>
            <?php endwhile; else: ?>
              <li class="list-group-item">No submissions pending.</li>
            <?php endif; ?>
          </ul>
          <div class="mt-3">
            <a href="/dams/pages/results/add.php" class="btn btn-sm btn-primary">Add Result</a>
            <a href="/dams/pages/assignments/add.php" class="btn btn-sm btn-outline-primary">Add Assignment</a>
            <a href="/dams/pages/assignments/submissions.php" class="btn btn-sm btn-outline-secondary">Manage Submissions</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
