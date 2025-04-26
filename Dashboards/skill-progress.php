<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';
checkLogin();

// ✅ Restrict to Admin or Coach
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if (!in_array($role, ['Admin', 'Coach'])) {
    echo "<p>Access Denied.</p>";
    include '../includes/footer.php';
    exit();
}

// ✅ Fetch all players with progress
$players_stmt = $conn->prepare("SELECT id, full_name, email FROM users WHERE role = 'Player'");
$players_stmt->execute();
$players = $players_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$players_stmt->close();

// ✅ Load progress per player
$progress_data = [];
foreach ($players as $player) {
    $pid = $player['id'];
    $stmt = $conn->prepare("
        SELECT ts.session_type, pp.date, pp.skill_area, pp.rating, pp.notes
        FROM player_progress pp
        JOIN training_sessions ts ON pp.session_id = ts.id
        WHERE pp.user_id = ?
        ORDER BY pp.date DESC
    ");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $progress_data[$pid] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<h2>All Player Skill Progress Reports</h2>

<?php foreach ($players as $player): ?>
    <div class="player-section">
        <h3><?= htmlspecialchars($player['full_name']) ?> (<?= htmlspecialchars($player['email']) ?>)</h3>

        <?php if (empty($progress_data[$player['id']])): ?>
            <p class="no-progress">No progress records yet.</p>
        <?php else: ?>
            <table class="progress-table">
                <thead>
                    <tr>
                        <th>Session</th>
                        <th>Date</th>
                        <th>Skill</th>
                        <th>Rating</th>
                        <th>Coach Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($progress_data[$player['id']] as $progress): ?>
                        <tr>
                            <td><?= htmlspecialchars($progress['session_type']) ?></td>
                            <td><?= htmlspecialchars($progress['date']) ?></td>
                            <td><?= htmlspecialchars($progress['skill_area']) ?></td>
                            <td><?= htmlspecialchars($progress['rating']) ?></td>
                            <td><?= htmlspecialchars($progress['notes']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
<?php endforeach; ?>


<?php include '../includes/footer.php'; ?>
