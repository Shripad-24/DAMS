<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student')
    die('Access denied!');

require_once __DIR__ . '/../../config/db.php';
$pageTitle = 'Submit Assignment';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assignment_id = (int) ($_POST['assignment_id'] ?? 0);
    $roll = trim($_POST['roll'] ?? '');
    if ($assignment_id && $roll && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Find student by roll
        $st = $conn->prepare("SELECT id FROM students WHERE roll=?");
        $st->bind_param('s', $roll);
        $st->execute();
        $stu = $st->get_result()->fetch_assoc();
        if (!$stu) {
            $msg = 'Invalid Roll Number';
        } else {
            $allowed = ['pdf', 'zip', 'doc', 'docx', 'ppt', 'pptx'];
            $name = $_FILES['file']['name'];
            $tmp = $_FILES['file']['tmp_name'];
            $size = $_FILES['file']['size'];
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed))
                $msg = 'Invalid file type';
            elseif ($size > 10 * 1024 * 1024)
                $msg = 'File too large (max 10MB)';
            else {
                $safe = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $name);
                $dest = __DIR__ . '/../../uploads/assignments/' . $safe;
                if (move_uploaded_file($tmp, $dest)) {
                    $rel = '/dams/uploads/assignments/' . $safe;
                    $stmt = $conn->prepare("INSERT INTO submissions(assignment_id,student_id,file_path,status) VALUES(?,?,?,'Pending')");
                    $stmt->bind_param('iis', $assignment_id, $stu['id'], $rel);
                    $msg = $stmt->execute() ? 'Uploaded successfully' : 'DB save failed';
                } else
                    $msg = 'Failed to move upload';
            }
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $msg = 'All fields required';
    }
}
$assign = $conn->query("SELECT id,title FROM assignments ORDER BY id DESC");
?>
<div class="container py-4">
    <h4>Submit Assignment</h4>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Assignment</label>
            <select name="assignment_id" class="form-select" required>
                <option value="">-- Select --</option>
                <?php while ($a = $assign->fetch_assoc()): ?>
                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['title']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Your Roll Number</label>
            <input name="roll" class="form-control" required>
        </div>
        <div class="col-12">
            <label class="form-label">File (PDF/ZIP/DOC/DOCX/PPT/PPTX, max 10MB)</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Upload</button>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>