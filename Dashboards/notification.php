<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role, coach_team FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($user_role, $coach_team);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT role, plan, coach_team FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($user_role, $plan, $coach_team);
$stmt->fetch();
$stmt->close();

$user_team = ($user_role === 'Player') ? $plan : (($user_role === 'Coach') ? $coach_team : 'ALL');



// ✅ Send a message (Admin or Coach)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($user_role, ['Admin', 'Coach'])) {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);
    $target_role = $_POST['target_role'];
    $target_team = $_POST['target_team'] ?? 'ALL';

    $stmt = $conn->prepare("INSERT INTO notifications (title, message, sender_id, sender_role, target_role, target_team) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisss", $title, $message, $user_id, $user_role, $target_role, $target_team);
    $stmt->execute();
    $notification_id = $stmt->insert_id;
    $stmt->close();

    // Send to all users of the target role and team
    if ($target_team === 'ALL') {
        $stmt = $conn->prepare("SELECT id FROM users WHERE role = ?");
        $stmt->bind_param("s", $target_role);
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE role = ? AND plan = ?");
        $stmt->bind_param("ss", $target_role, $target_team);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $uid = $row['id'];
        $ins = $conn->prepare("INSERT INTO notification_reads (user_id, notification_id) VALUES (?, ?)");
        $ins->bind_param("ii", $uid, $notification_id);
        $ins->execute();
        $ins->close();
    }

    echo "<script>alert('Notification sent successfully.'); window.location.href='notification.php';</script>";
    exit();
}

// ✅ Mark as read
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $notif_id = (int)$_GET['read'];
    $stmt = $conn->prepare("UPDATE notification_reads SET is_read = 1, read_at = NOW() WHERE user_id = ? AND notification_id = ?");
    $stmt->bind_param("ii", $user_id, $notif_id);
    $stmt->execute();
    $stmt->close();
    header("Location: notification.php");
    exit();
}

// ✅ Fetch notifications filtered by team
$stmt = $conn->prepare("
    SELECT n.id, n.title, n.message, n.sender_role, n.created_at, nr.is_read
    FROM notifications n
    JOIN notification_reads nr ON n.id = nr.notification_id
    WHERE nr.user_id = ? AND (n.target_team = ? OR n.target_team = 'ALL')
    ORDER BY n.created_at DESC
");
$stmt->bind_param("is", $user_id, $user_team);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<h2>Notifications</h2>

<?php if (in_array($user_role, ['Admin', 'Coach'])): ?>
    <h3>Send Notification</h3>
    <form method="POST"  class="notif-form">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Message:</label>
        <textarea name="message" rows="4" required></textarea>

        <label>Send To Role:</label>
        <select name="target_role" required>
            <?php if ($user_role === 'Admin'): ?>
                <option value="Coach">Coaches</option>
                <option value="Player">Players</option>
            <?php else: ?>
                <option value="Player">Players</option>
            <?php endif; ?>
        </select>

        <label>Target Team:</label>
<select name="target_team" required>
    <?php if ($user_role === 'Admin'): ?>
        <option value="ALL">All Teams</option>
        <option value="U15">U15</option>
        <option value="U18">U18</option>
        <option value="U21">U21</option>
    <?php elseif ($user_role === 'Coach' && $user_team): ?>
        <option value="<?= htmlspecialchars($user_team) ?>"><?= htmlspecialchars($user_team) ?></option>
    <?php endif; ?>
</select>



        <button type="submit" class="btn">Send Notification</button>
    </form>
<?php endif; ?>


<h3>Inbox</h3>
<?php if (empty($notifications)): ?>
    <p>No notifications yet.</p>
<?php else: ?>
    <ul class="notif-list">
        <?php foreach ($notifications as $notif): ?>
            <li class="<?= $notif['is_read'] ? 'read' : 'unread' ?>">
                <h4><?= htmlspecialchars($notif['title']) ?> <small>(From <?= $notif['sender_role'] ?>)</small></h4>
                <p><?= nl2br(htmlspecialchars($notif['message'])) ?></p>
                <small><?= date("Y-m-d H:i", strtotime($notif['created_at'])) ?></small>
                <?php if (!$notif['is_read']): ?>
                    <form method="GET" style="margin-top: 8px;">
                        <input type="hidden" name="read" value="<?= $notif['id'] ?>">
                        <button type="submit" class="btn small">Mark as Read</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>



<?php include '../includes/footer.php'; ?>
