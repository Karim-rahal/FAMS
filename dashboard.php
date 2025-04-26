<?php
include 'includes/header.php';
checkLogin();


include 'includes/db.php'; 

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT role, plan FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_role, $user_plan);
$stmt->fetch();
$stmt->close();

$user_role = $user_role ?? 'NONE';

$user_plan = $user_plan ?? 'NONE';
?>

<h2>Dashboard</h2>
<div class="dashboard-container">
<?php if ($user_role === 'Admin'): ?>
    <p>Welcome, <strong>Admin</strong>. You have full access to manage the academy platform.</p>
    <ul>
        <li><a href="Dashboards/manage-users.php">Manage Users</a></li>
        <li><a href="Dashboards/view-payments.php">View Payments</a></li>
        <li><a href="Dashboards/allschedules.php">Schedules</a></li>
        <li><a href="Dashboards/attendance.php">Attendance</a></li>
        <li><a href="Dashboards/updates.php">Updates</a></li>
        <li><a href="Dashboards/scheduleMatches.php">Schedule Matches</a></li>
        <li><a href="Dashboards/scheduleSessions.php">Schedule Sessions</a></li>
        <li><a href="Dashboards/assign-coaches.php">Assign Coaches</a></li>
        <li><a href="Dashboards/skill-progress.php">Track Players Progress</a></li>
        <li><a href="Dashboards/manage-equipment.php">Manage Equipment</a></li>
        <li><a href="Dashboards/notification.php">Notifications</a></li>
    </ul>

<?php elseif ($user_role === 'Coach'): ?>
    <p>Welcome, <strong>Coach</strong>. Here is your control panel.</p>
    <ul>
        <li><a href="Dashboards/scheduleSessions.php">Schedule Sessions</a></li>
        <li><a href="Dashboards/upload-materials.php">Upload Coaching Materials</a></li>
        <li><a href="Dashboards/view-players.php">View Player Progress</a></li>
        <li><a href="Dashboards/update-player-progress.php">Update Player Progress</a></li>
        <li><a href="Dashboards/notification.php">Notifications</a></li>
    </ul>

<?php elseif ($user_role === 'Player'): ?>
    <p>Welcome, <strong>Player</strong>. Here's your personalized training dashboard.</p>
    <ul>
        <li><a href="Dashboards/my-schedule.php">View Schedule</a></li>
        <li><a href="Dashboards/Myprogress.php">Track My Progress</a></li>
        <li><a href="Dashboards/view-player-payments.php">View Payments</a></li>
        <li><a href="Dashboards/download-materials.php">Download Resources</a></li>
        <li><a href="Dashboards/notification.php">Notifications</a></li>
    </ul>

<?php elseif ($user_role === 'NONE' && in_array($user_plan, ['U15', 'U18', 'U21'])): ?>
    <p><strong>Confirming your subscription to access the <?= htmlspecialchars($user_plan) ?> dashboard.</strong></p>
    <p>Please wait 3–5 business days while your plan is being verified by the administration.</p>

<?php else: ?>
    <p><strong>No role or plan assigned.</strong> Please select a plan on the <a href="pricing.php">Pricing</a> page or contact support.</p>
<?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
