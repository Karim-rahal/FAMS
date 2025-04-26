<<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';
checkLogin();


$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role !== 'Admin') {
    echo "<p>Access denied. Admin only.</p>";
    include '../includes/footer.php';
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_session_id'])) {
    $sid = (int)$_POST['complete_session_id'];
    $stmt = $conn->prepare("UPDATE training_sessions SET status = 'Completed' WHERE id = ?");
    $stmt->bind_param("i", $sid);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Session marked as completed.'); window.location.href='attendance.php';</script>";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_id']) && isset($_POST['attendance'])) {
    $session_id = (int)$_POST['session_id'];
    foreach ($_POST['attendance'] as $user_id => $status) {
        if (!in_array($status, ['Present', 'Absent'])) continue;

        $field = $status === 'Present' ? 'present_count' : 'absent_count';

        $stmt = $conn->prepare("
            INSERT INTO attendance (user_id, session_id, $field) 
            VALUES (?, ?, 1) 
            ON DUPLICATE KEY UPDATE $field = $field + 1
        ");
        $stmt->bind_param("ii", $user_id, $session_id);
        $stmt->execute();
        $stmt->close();

        $userField = $status === 'Present' ? 'total_attended' : 'total_absent';
        $stmt = $conn->prepare("UPDATE users SET $userField = $userField + 1 WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>alert('Attendance updated.'); window.location.href='attendance.php';</script>";
    exit();
}


$teams = ['U15', 'U18', 'U21'];
$sessions_by_team = [];
foreach ($teams as $team) {
    $stmt = $conn->prepare("SELECT id, session_date, session_type, status FROM training_sessions WHERE team = ? ORDER BY session_date ASC");
    $stmt->bind_param("s", $team);
    $stmt->execute();
    $result = $stmt->get_result();
    $sessions_by_team[$team] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}


$users = $conn->query("SELECT id, full_name, plan FROM users WHERE role = 'Player'")->fetch_all(MYSQLI_ASSOC);
?>



<div class="dashboard-container">
    <h2>Mark Attendance</h2>
    <div class="team-nav">
    <a href="#U15">U15</a>
    <a href="#U18">U18</a>
    <a href="#U21">U21</a>
</div>
    <?php foreach ($sessions_by_team as $team => $sessions): ?>
        <div class="schedule-section">
            <h3>
                <button class="toggle-team" onclick="toggleSection('<?= strtolower($team) ?>-container')">
                    <?= htmlspecialchars($team) ?> Sessions
                </button>
            </h3>

            <div class="team-content" id="<?= strtolower($team) ?>-container" style="display: none;">
                <?php foreach ($sessions as $session): ?>
                    <?php
                        $session_id = $session['id'];
                        $date = htmlspecialchars($session['session_date']);
                        $day = date('l', strtotime($date));
                        $type = htmlspecialchars($session['session_type']);
                        $status = htmlspecialchars($session['status']);

                        if ($status === 'Completed') continue;
                    ?>
                    <div class="session-block">
                        <button class="toggle-session" onclick="toggleSection('session-<?= $session_id ?>')">
                            <?= $date ?> (<?= $day ?>) — <?= $type ?> [<?= $status ?>]
                        </button>

                        <div class="session-content" id="session-<?= $session_id ?>" style="display: none;">
                            
                            <form method="POST" action="attendance.php" style="margin-bottom: 10px;">
                                <input type="hidden" name="session_id" value="<?= $session_id ?>">
                                <table class="user-table">
                                    <thead>
                                        <tr>
                                            <th>Player</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): 
                                            if($user['plan']===$team){
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($user['full_name']) ?></td>
                                                <td>
                                                    <select name="attendance[<?= $user['id'] ?>]" class="role-select">
                                                        <option value="Present">Present</option>
                                                        <option value="Absent">Absent</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php }endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn">Update Attendance</button>
                            </form>

                            
                            <form method="POST" action="attendance.php">
                                <input type="hidden" name="complete_session_id" value="<?= $session_id ?>">
                                <button type="submit" class="btn btn-secondary">Mark Session as Completed</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<script>
function toggleSection(id) {
    const section = document.getElementById(id);
    if (section.style.display === "none" || section.style.display === "") {
        section.style.display = "block";
    } else {
        section.style.display = "none";
    }
}
</script>

<?php include '../includes/footer.php'; ?>

