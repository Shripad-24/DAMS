<?php
session_start(); if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','faculty'])) die('Access denied!');
require_once __DIR__ . '/../../config/db.php';
$pageTitle='Add Result';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $student_id=(int)($_POST['student_id']??0);
  $subject_id=(int)($_POST['subject_id']??0);
  $marks=(int)($_POST['marks']??0);
  $total=(int)($_POST['total_marks']??100);
  $semester=trim($_POST['semester']??'');
  if ($student_id && $subject_id && $semester !== '') {
    $stmt=$conn->prepare("INSERT INTO results(student_id,subject_id,marks,total_marks,semester) VALUES(?,?,?,?,?)");
    $stmt->bind_param('iiiis',$student_id,$subject_id,$marks,$total,$semester);
    $msg=$stmt->execute()?'Result saved':'Failed: '.$conn->error;
  } else $msg='All fields required';
}
$students=$conn->query("SELECT id,name,roll FROM students ORDER BY name");
$subjects=$conn->query("SELECT id,subject_name FROM subjects ORDER BY subject_name");
?>
<div class="container py-4">
  <h4>Add Result</h4>
  <?php if($msg): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <form method="post" class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Student</label>
      <select name="student_id" class="form-select" required>
        <option value="">-- Select --</option>
        <?php while($s=$students->fetch_assoc()): ?>
        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['roll']) ?>)</option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Subject</label>
      <select name="subject_id" class="form-select" required>
        <option value="">-- Select --</option>
        <?php while($sub=$subjects->fetch_assoc()): ?>
        <option value="<?= $sub['id'] ?>"><?= htmlspecialchars($sub['subject_name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-2"><label class="form-label">Marks</label><input type="number" name="marks" class="form-control" min="0" required></div>
    <div class="col-md-2"><label class="form-label">Out Of</label><input type="number" name="total_marks" class="form-control" min="1" value="100" required></div>
    <div class="col-md-2"><label class="form-label">Semester</label><input name="semester" class="form-control" placeholder="e.g. 3" required></div>
    <div class="col-12"><button class="btn btn-primary">Save</button></div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
