<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';
checkLogin();


$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role !== 'Admin') {
    echo "<p>You do not have permission to view this page.</p>";
    include '../includes/footer.php';
    exit();
}


$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE plan != 'NONE' AND role = 'NONE'");
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
?>

<div class="dashboard-container">
    <h2>Pending Admin Actions</h2>

    <?php if ($count > 0): ?>
        <div class="alert-box">
            ⚠️ There are <strong><?= $count ?></strong> user(s) who have subscribed to a plan but have no role assigned.<br>
            <a href="manage-users.php" class="alert-link">Assign roles now &raquo;</a>
        </div>
    <?php else: ?>
        <p>No pending user role updates.</p>
    <?php endif; ?>
</div>



<?php include '../includes/footer.php'; ?>
