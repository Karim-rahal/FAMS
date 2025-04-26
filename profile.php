<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';
checkLogin();

$user_id = $_SESSION['user_id'] ?? null;

// Fetch basic info
$stmt = $conn->prepare("SELECT full_name, father_name, gender, email, role, plan, total_attended, total_absent, coach_type, coach_team FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $fathername, $gender, $email, $role, $plan, $attended, $absent, $coach_type, $coach_team);
$stmt->fetch();
$stmt->close();
?>

<div class="profile-container">
    <h2>My Profile</h2>

    <div class="profile-card">
        <p><strong>Full Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>Father's Name:</strong> <?= htmlspecialchars($fathername) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($gender) ?></p>
        <p><strong>Email Address:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>

        <?php if ($role === 'Coach'): ?>
            <p><strong>Coach Type:</strong> <?= htmlspecialchars($coach_type ?: 'N/A') ?></p>
            <p><strong>Assigned Team:</strong> <?= htmlspecialchars($coach_team ?: 'N/A') ?></p>

        <?php elseif ($role === 'Player'): ?>
            <p><strong>Team:</strong> <?= htmlspecialchars($plan ?: 'NONE') ?></p>
            <p><strong>Total Sessions Attended:</strong> <?= (int)$attended ?></p>
            <p><strong>Total Sessions Missed:</strong> <?= (int)$absent ?></p>
        <?php endif; ?>
    </div>
    <p class="underbtn">Change Password: <a href="reset.php">Reset</a> </p>

</div>

<?php require_once 'includes/footer.php'; ?>
