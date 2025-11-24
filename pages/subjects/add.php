<?php
session_start(); if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','faculty'])) die('Access denied!');
require_once __DIR__ . '/../../config/db.php';
$pageTitle='Add Subject';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name=trim($_POST['subject_name']??''); $fid=(int)($_POST['faculty_id']??0);
  if ($name) {
    $stmt=$conn->prepare("INSERT INTO subjects(subject_name,faculty_id) VALUES(?,?)");
    $stmt->bind_param('si',$name,$fid);
    $msg = $stmt->execute() ? 'Subject added' : 'Failed: '.$conn->error;
  } else $msg='Subject name required';
}
$fac=$conn->query("SELECT id,name FROM faculty ORDER BY name");
?>
<div class="container py-4">
  <h4>Add Subject</h4>
  <?php if($msg): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <form method="post" class="row g-3">
    <div class="col-md-6"><label class="form-label">Subject</label><input name="subject_name" class="form-control" required></div>
    <div class="col-md-6">
      <label class="form-label">Faculty (optional)</label>
      <select name="faculty_id" class="form-select">
        <option value="0">-- Not Assigned --</option>
        <?php while($f=$fac->fetch_assoc()): ?>
          <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-12"><button class="btn btn-primary">Save</button></div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
