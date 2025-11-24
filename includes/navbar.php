<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$loggedIn = isset($_SESSION['user']);
$role = $loggedIn ? $_SESSION['user']['role'] : null;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="<?=
      !$loggedIn ? '/dams/pages/login.php' :
      ($role==='admin' ? '/dams/pages/dashboard_admin.php' :
      ($role==='faculty' ? '/dams/pages/dashboard_faculty.php' :
      '/dams/pages/dashboard_student.php')) ?>">DAMS</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <?php if ($loggedIn && $role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="/dams/pages/dashboard_admin.php">Dashboard</a></li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Students</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/dams/pages/students/add.php">Add</a></li>
              <li><a class="dropdown-item" href="/dams/pages/students/view.php">View</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Faculty</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/dams/pages/faculty/add.php">Add</a></li>
              <li><a class="dropdown-item" href="/dams/pages/faculty/view.php">View</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Subjects</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/dams/pages/subjects/add.php">Add</a></li>
              <li><a class="dropdown-item" href="/dams/pages/subjects/view.php">View</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Results</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/dams/pages/results/add.php">Add</a></li>
              <li><a class="dropdown-item" href="/dams/pages/results/view.php">View</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Assignments</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/dams/pages/assignments/add.php">Add</a></li>
              <li><a class="dropdown-item" href="/dams/pages/assignments/view.php">View</a></li>
              <li><a class="dropdown-item" href="/dams/pages/assignments/submissions.php">Submissions</a></li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($loggedIn && $role === 'faculty'): ?>
          <li class="nav-item"><a class="nav-link" href="/dams/pages/dashboard_faculty.php">Dashboard</a></li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Students</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/dams/pages/students/add.php">Add</a></li>
              <li><a class="dropdown-item" href="/dams/pages/students/view.php">View</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Subjects</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/dams/pages/subjects/view.php">View</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Results</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/dams/pages/results/add.php">Add</a></li>
              <li><a class="dropdown-item" href="/dams/pages/results/view.php">View</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Assignments</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/dams/pages/assignments/add.php">Add</a></li>
              <li><a class="dropdown-item" href="/dams/pages/assignments/view.php">View</a></li>
              <li><a class="dropdown-item" href="/dams/pages/assignments/submissions.php">Submissions</a></li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($loggedIn && $role === 'student'): ?>
          <li class="nav-item"><a class="nav-link" href="/dams/pages/dashboard_student.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/dams/pages/assignments/student_view.php">Assignments</a></li>
          <li class="nav-item"><a class="nav-link" href="/dams/pages/assignments/submit.php">Submit</a></li>
          <li class="nav-item"><a class="nav-link" href="/dams/pages/results/student_result.php">My Result</a></li>
        <?php endif; ?>
      </ul>

      <div class="ms-auto">
        <?php if ($loggedIn): ?>
          <span class="navbar-text me-2 small">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?> (<?= htmlspecialchars($role) ?>)</span>
          <a href="/dams/pages/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        <?php else: ?>
          <a href="/dams/pages/login.php" class="btn btn-light btn-sm">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
