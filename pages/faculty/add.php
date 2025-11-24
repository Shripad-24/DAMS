<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') die('Access denied!');
require_once __DIR__ . '/../../config/db.php';
$pageTitle = 'Add Faculty';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$msg='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $department = trim($_POST['department'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($name && $department && $username && $password) {
    // 1) Create user (role faculty)
    $u=$conn->prepare("INSERT INTO users(username,password,role) VALUES(?,?,'faculty')");
    $u->bind_param('ss',$username,$password);
    $ok1=$u->execute();

    // 2) Create faculty record and link by username
    $f=$conn->prepare("INSERT INTO faculty(name,department,username) VALUES(?,?,?)");
    $f->bind_param('sss',$name,$department,$username);
    $ok2=$f->execute();

    $msg = ($ok1 && $ok2) ? 'Faculty + login created' : 'Failed: '.$conn->error;
  } else $msg='All fields required';
}
?>
<div class="container py-4">
  <h4>Add Faculty</h4>
  <?php if($msg): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <form method="post" class="row g-3">
    <div class="col-md-4"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Department</label><input name="department" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Portal Username</label><input name="username" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Portal Password</label><input name="password" class="form-control" required></div>
    <div class="col-12"><button class="btn btn-primary">Save</button></div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
