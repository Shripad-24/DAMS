<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','faculty'])) die('Access denied!');

require_once __DIR__ . '/../../config/db.php';
$pageTitle = 'Add Assignment';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $subject_id = (int) ($_POST['subject_id'] ?? 0);
    $due_date = $_POST['due_date'] ?? null;
    if ($title) {
        $stmt = $conn->prepare("INSERT INTO assignments(title,description,subject_id,due_date) VALUES(?,?,?,?)");
        $stmt->bind_param('ssis', $title, $desc, $subject_id, $due_date);
        $msg = $stmt->execute() ? 'Assignment added' : 'Failed: ' . $conn->error;
    } else
        $msg = 'Title required';
}
$subjects = $conn->query("SELECT id,subject_name FROM subjects ORDER BY subject_name");
?>
<div class="container py-4">
    <h4>Add Assignment</h4>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <form method="post" class="row g-3">
        <div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Subject</label>
            <select name="subject_id" class="form-select">
                <?php while ($s = $subjects->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['subject_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-12"><label class="form-label">Description</label><textarea name="description"
                class="form-control" rows="3"></textarea></div>
        <div class="col-md-4"><label class="form-label">Due Date</label><input type="date" name="due_date"
                class="form-control"></div>
        <div class="col-12"><button class="btn btn-primary">Save</button></div>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>