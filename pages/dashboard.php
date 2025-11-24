<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: /dams/pages/login.php'); exit; }
require_once __DIR__ . '/../config/db.php';
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

$counts = [
  'students'=>0,'faculty'=>0,'subjects'=>0,'assignments'=>0
];
foreach ($counts as $k=>$_) {
  $res = $conn->query("SELECT COUNT(*) c FROM $k");
  $counts[$k] = ($res && $row=$res->fetch_assoc()) ? (int)$row['c'] : 0;
}
?>
<div class="container py-4">
  <div class="row g-3">
    <?php foreach ($counts as $label=>$val): ?>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <div class="display-6"><?= $val ?></div>
          <div class="text-uppercase small"><?= htmlspecialchars($label) ?></div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
