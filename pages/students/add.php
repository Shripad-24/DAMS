<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','faculty'])) die('Access denied!');
require_once __DIR__ . '/../../config/db.php';
$pageTitle='Add Student';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name=trim($_POST['name']??''); 
  $roll=trim($_POST['roll']??''); 
  $course=trim($_POST['course']??'');
  $username=trim($_POST['username']??''); // student portal username (recommend same as roll)
  $password=trim($_POST['password']??'');

  if ($name && $roll && $course && $username && $password) {
    // 1) Create/ensure user (role student)
    $stmt=$conn->prepare("INSERT INTO users(username,password,role) VALUES(?,?, 'student')");
    $stmt->bind_param('ss',$username,$password);
    $ok1=$stmt->execute();

    // 2) Create student record
    $stmt2=$conn->prepare("INSERT INTO students(name,roll,course) VALUES(?,?,?)");
    $stmt2->bind_param('sss',$name,$roll,$course);
    $ok2=$stmt2->execute();

    $msg = ($ok1 && $ok2) ? 'Student + login created' : 'Failed: '.$conn->error;
  } else $msg='All fields are required';
}
?>
<div class="container py-4">
  <h4>Add Student</h4>
  <?php if($msg): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <form method="post" class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Name</label>
      <input name="name" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Roll</label>
      <input name="roll" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Course</label>
      <input name="course" class="form-control" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Portal Username</label>
      <input name="username" class="form-control" placeholder="e.g., same as Roll (recommended)" required>
      <div class="form-text">If you keep username = roll, student dashboard will auto-link.</div>
    </div>
    <div class="col-md-4">
      <label class="form-label">Portal Password</label>
      <input name="password" class="form-control" required>
    </div>

    <div class="col-12"><button class="btn btn-primary">Save</button></div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
