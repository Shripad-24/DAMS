<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') die('Access denied!');
require_once __DIR__ . '/../../config/db.php';
$pageTitle='My Result';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$uname = $_SESSION['user']['username']; // username must equal roll (recommended)
$st=$conn->prepare("SELECT id,name,roll,course FROM students WHERE roll=?");
$st->bind_param('s',$uname);
$st->execute(); $stu=$st->get_result()->fetch_assoc();

$data = null;
if ($stu) {
  $sql="SELECT sub.subject_name, r.marks, r.total_marks, r.semester
        FROM results r JOIN subjects sub ON r.subject_id=sub.id
        WHERE r.student_id=? ORDER BY r.semester, sub.subject_name";
  $st2=$conn->prepare($sql); $sid=$stu['id']; $st2->bind_param('i',$sid); $st2->execute();
  $data = ['student'=>$stu,'rows'=>$st2->get_result()];
}
?>
<div class="container py-4">
  <h4>My Result</h4>
  <?php if (!$data): ?>
    <div class="alert alert-warning">We could not find your student record. Ask admin/faculty to ensure your <strong>username equals your Roll</strong>, or link your account.</div>
  <?php else: ?>
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="mb-1"><?= htmlspecialchars($data['student']['name']) ?></h5>
        <div class="text-muted mb-3">Roll: <?= htmlspecialchars($data['student']['roll']) ?> · Course: <?= htmlspecialchars($data['student']['course']) ?></div>
        <table class="table table-bordered">
          <thead><tr><th>Semester</th><th>Subject</th><th>Marks</th></tr></thead>
          <tbody>
            <?php while($row=$data['rows']->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['semester']) ?></td>
                <td><?= htmlspecialchars($row['subject_name']) ?></td>
                <td><?= (int)$row['marks'] ?>/<?= (int)$row['total_marks'] ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
