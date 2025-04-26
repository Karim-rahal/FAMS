<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';
checkLogin();

// 🔐 Verify Admin
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coach_type']) && isset($_POST['coach_team'])) {
    foreach ($_POST['coach_type'] as $uid => $type) {
        $team = $_POST['coach_team'][$uid] ?? null;
        if (in_array($type, ['Head Coach', 'Assistant Coach', 'Goalkeeping Coach', 'Fitness Coach']) &&
            in_array($team, ['U15', 'U18', 'U21'])) {
            
            $stmt = $conn->prepare("UPDATE users SET coach_type = ?, coach_team = ? WHERE id = ? AND role = 'Coach'");
            $stmt->bind_param("ssi", $type, $team, $uid);
            $stmt->execute();
            $stmt->close();
        }
    }
    echo "<script>alert('Coach assignments updated.'); window.location.href='assign-coaches.php';</script>";
    exit();
}


$coaches = $conn->query("SELECT id, email, coach_type, coach_team FROM users WHERE role = 'Coach'")
               ->fetch_all(MYSQLI_ASSOC);
?>

<h2>Assign Coaches to Roles and Teams</h2>
<form method="POST" action="assign-coaches.php">
    <table class="user-table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Coach Type</th>
                <th>Team</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($coaches as $coach): ?>
                <tr>
                    <td><?= htmlspecialchars($coach['email']) ?></td>
                    <td>
                        <select name="coach_type[<?= $coach['id'] ?>]" required>
                            <option value="">--Select--</option>
                            <?php
                            $types = ['Head Coach', 'Assistant Coach', 'Goalkeeping Coach', 'Fitness Coach'];
                            foreach ($types as $type) {
                                $selected = $coach['coach_type'] === $type ? 'selected' : '';
                                echo "<option value=\"$type\" $selected>$type</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="coach_team[<?= $coach['id'] ?>]" required>
                            <option value="">--Select--</option>
                            <?php
                            $teams = ['U15', 'U18', 'U21'];
                            foreach ($teams as $team) {
                                $selected = $coach['coach_team'] === $team ? 'selected' : '';
                                echo "<option value=\"$team\" $selected>$team</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button type="submit" class="btn">Update Assignments</button>
</form>

<style>

</style>

<?php include '../includes/footer.php'; ?>
