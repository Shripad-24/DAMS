<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') die('Access denied!');
require_once __DIR__ . '/../config/db.php';
$pageTitle='Student Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

$uname = $_SESSION['user']['username']; // we will use username == roll
$stu = $conn->prepare("SELECT id, name, roll, course FROM students WHERE roll=? LIMIT 1");
$stu->bind_param('s',$uname);
$stu->execute();
$student = $stu->get_result()->fetch_assoc();

$assignments = $conn->query("SELECT a.title, s.subject_name, a.due_date FROM assignments a LEFT JOIN subjects s ON a.subject_id=s.id ORDER BY a.id DESC LIMIT 6");

$marksSQL = "SELECT sub.subject_name, r.marks, r.total_marks, r.semester
             FROM results r JOIN subjects sub ON r.subject_id=sub.id
             WHERE r.student_id=? ORDER BY r.id DESC LIMIT 6";
$marks = $conn->prepare($marksSQL);
$stuId = (int)($student['id'] ?? 0);
$marks->bind_param('i',$stuId);
$marks->execute();
$recentMarks = $marks->get_result();
?>
<div class="container py-4">
  <h4 class="mb-3">Welcome, <?= htmlspecialchars($student['name'] ?? $uname) ?></h4>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header fw-bold">Latest Assignments</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <?php if($assignments && $assignments->num_rows): while($a=$assignments->fetch_assoc()): ?>
              <li class="list-group-item">
                <strong><?= htmlspecialchars($a['title']) ?></strong>
                <?php if($a['subject_name']): ?> – <?= htmlspecialchars($a['subject_name']) ?><?php endif; ?>
                <span class="small text-muted"> (Due: <?= htmlspecialchars($a['due_date'] ?? '-') ?>)</span>
              </li>
            <?php endwhile; else: ?>
              <li class="list-group-item">No assignments yet.</li>
            <?php endif; ?>
          </ul>
          <div class="mt-3">
            <a href="/dams/pages/assignments/student_view.php" class="btn btn-sm btn-primary">See All</a>
            <a href="/dams/pages/assignments/submit.php" class="btn btn-sm btn-outline-primary">Submit</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header fw-bold">Recent Marks</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <?php if($recentMarks && $recentMarks->num_rows): while($m=$recentMarks->fetch_assoc()): ?>
              <li class="list-group-item">
                <?= htmlspecialchars($m['subject_name']) ?> – <strong><?= (int)$m['marks'] ?>/<?= (int)$m['total_marks'] ?></strong>
                <span class="small text-muted"> (Sem <?= htmlspecialchars($m['semester']) ?>)</span>
              </li>
            <?php endwhile; else: ?>
              <li class="list-group-item">No marks published yet.</li>
            <?php endif; ?>
          </ul>
          <div class="mt-3">
            <a href="/dams/pages/results/student_result.php" class="btn btn-sm btn-outline-secondary">View Full Result</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
