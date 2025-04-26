<?php
session_start();
include '../includes/db.php';
include  '../includes/header.php';
checkLogin();

// 🛡 Verify Coach Role & Assigned Team
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role, coach_team FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role, $coach_team);
$stmt->fetch();
$stmt->close();

if ($role !== 'Coach' || !$coach_team) {
    echo "<p>Access denied. Only assigned coaches can access this page.</p>";
    include '../includes/footer.php';
    exit();
}

// ✅ Process Submitted Progress
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['progress'])) {
    foreach ($_POST['progress'] as $entry) {
        $user_id = (int)$entry['user_id'];
        $session_id = (int)$entry['session_id'];
        $skill = $entry['skill_area'];
        $rating = (int)$entry['rating'];
        $notes = trim($entry['notes']);

        // Validate session ownership & get its date
        $vstmt = $conn->prepare("SELECT session_date, team FROM training_sessions WHERE id = ?");
        $vstmt->bind_param("i", $session_id);
        $vstmt->execute();
        $vstmt->bind_result($session_date, $team);
        $valid = $vstmt->fetch();
        $vstmt->close();

        if ($valid && $team === $coach_team) {
            $stmt = $conn->prepare("INSERT INTO player_progress (user_id, session_id, date, skill_area, rating, notes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissis", $user_id, $session_id, $session_date, $skill, $rating, $notes);
            $stmt->execute();
            $stmt->close();
        }
    }

    echo "<script>alert('Progress successfully submitted.'); window.location.href='update-player-progress.php';</script>";
    exit();
}

// 🔽 Fetch players in coach's team
$stmt = $conn->prepare("SELECT id, full_name, email FROM users WHERE role = 'Player' AND plan = ?");
$stmt->bind_param("s", $coach_team);
$stmt->execute();
$players = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 📅 Fetch training sessions for that team
$stmt = $conn->prepare("SELECT id, session_type, session_date FROM training_sessions WHERE team = ? ORDER BY session_date DESC");
$stmt->bind_param("s", $coach_team);
$stmt->execute();
$sessions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<h2>Update Player Progress – <?= htmlspecialchars($coach_team) ?> Team</h2>

<form method="POST" action="update-player-progress.php">
    <?php foreach ($players as $index => $player): ?>
        <div class="accordion-group">
            <button type="button" class="accordion-toggle" onclick="toggleForm('form<?= $index ?>')">
                <?= htmlspecialchars($player['full_name']) ?> (<?= htmlspecialchars($player['email']) ?>)
            </button>

            <div class="accordion-content" id="form<?= $index ?>">
                <fieldset>
                    <input type="hidden" name="progress[<?= $player['id'] ?>][user_id]" value="<?= $player['id'] ?>">

                    <label>Session:</label>
                    <select name="progress[<?= $player['id'] ?>][session_id]" required>
                        <option value="">-- Select Session --</option>
                        <?php foreach ($sessions as $session): ?>
                            <option value="<?= $session['id'] ?>">
                                <?= htmlspecialchars($session['session_type']) ?> (<?= $session['session_date'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Skill Area:</label>
                    <select name="progress[<?= $player['id'] ?>][skill_area]" required>
                        <option value="">-- Select Skill --</option>
                        <option value="Dribbling">Dribbling</option>
                        <option value="Passing">Passing</option>
                        <option value="Shooting">Shooting</option>
                        <option value="Stamina">Stamina</option>
                        <option value="Tactical">Tactical</option>
                        <option value="Discipline">Discipline</option>
                    </select>

                    <label>Rating (1–10):</label>
                    <input type="number" name="progress[<?= $player['id'] ?>][rating]" min="1" max="10" required>

                    <label>Coach Comment:</label>
                <select name="progress[<?= $player['id'] ?>][notes]" required>
                    <option value="">-- Select Feedback --</option>
                    <option value="Excellent effort and discipline">Excellent effort and discipline</option>
                    <option value="Strong performance with leadership">Strong performance with leadership</option>
                    <option value="Solid work but needs improvement in focus">Solid work but needs improvement in focus</option>
                    <option value="Passive involvement, needs more intensity">Passive involvement, needs more intensity</option>
                    <option value="Lack of focus and discipline observed">Lack of focus and discipline observed</option>
                </select>

                </fieldset>
            </div>
        </div>
    <?php endforeach; ?>

    <button type="submit" class="btn">Submit Progress</button>
</form>

<script>
function toggleForm(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === "block" ? "none" : "block";
}
</script>



<?php include '../includes/footer.php'; ?>
