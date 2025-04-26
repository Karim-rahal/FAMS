<?php
session_start();
require '../includes/db.php';
include '../includes/header.php';
checkLogin();


$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role ,coach_team FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role,$coach_team);
$stmt->fetch();
$stmt->close();

if (!in_array($role, ['Admin', 'Coach'])) {
    echo "<p>Access denied. Only Admins and Coaches can schedule sessions.</p>";
    include '../includes/footer.php';
    exit();
}


$teams = ['U15', 'U18', 'U21'];
$types = [];

foreach ($teams as $team) {
    $stmt = $conn->prepare("SELECT id, name FROM session_types WHERE team = ?");
    $stmt->bind_param("s", $team);
    $stmt->execute();
    $result = $stmt->get_result();
    $types[$team] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['team'], $_POST['session_date'], $_POST['session_type'])) {
    $team = $_POST['team'];
    $session_date = $_POST['session_date'];
    $session_type_id = (int)$_POST['session_type'];

    if (in_array($team, $teams) && $session_date && $session_type_id > 0) {
      
        $stmt = $conn->prepare("SELECT name FROM session_types WHERE id = ? AND team = ?");
        $stmt->bind_param("is", $session_type_id, $team);
        $stmt->execute();
        $stmt->bind_result($type_name);
        $stmt->fetch();
        $stmt->close();

        if ($type_name) {
            $stmt = $conn->prepare("INSERT INTO training_sessions (session_date, session_type, team, status) VALUES (?, ?, ?, 'Scheduled')");
            $stmt->bind_param("sss", $session_date, $type_name, $team);
            $stmt->execute();
            $stmt->close();

            echo "<script>alert('Session scheduled for $team.'); window.location.href='scheduleSessions.php';</script>";
            exit();
        }
    }
}
?>

<div class="dashboard-container">
    <h2>Schedule Training Sessions</h2>
    <?php if($coach_team!=='NULL'){ ?>
        <div class="team-form-block">
            <h3><?= $coach_team?> Team</h3>
            <form method="POST" action="scheduleSessions.php" class="schedule-form">
                <input type="hidden" name="team" value="<?= $coach_team ?>">

                <label for="session_date_<?= $coach_team ?>">Session Date:</label>
                <input type="date" name="session_date" id="session_date_<?= $coach_team ?>" required>

                <label for="session_type_<?= $coach_team ?>">Session Type:</label>
                <select name="session_type" id="session_type_<?= $coach_team ?>" required>
                    <option value="">-- Select Type --</option>
                    <?php foreach ($types[$coach_team] as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn">Create Session</button>
            </form>
        </div>
    <?php }else {?>
</div>
 <?php foreach ($teams as $team): ?>
        <div class="team-form-block">
            <h3><?= $team ?> Team</h3>
            <form method="POST" action="scheduleSessions.php" class="schedule-form">
                <input type="hidden" name="team" value="<?= $team ?>">

                <label for="session_date_<?= $team ?>">Session Date:</label>
                <input type="date" name="session_date" id="session_date_<?= $team ?>" required>

                <label for="session_type_<?= $team ?>">Session Type:</label>
                <select name="session_type" id="session_type_<?= $team ?>" required>
                    <option value="">-- Select Type --</option>
                    <?php foreach ($types[$team] as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn">Create Session</button>
            </form>
        </div>
    <?php endforeach; }?>
</div>

<?php include '../includes/footer.php'; ?>
