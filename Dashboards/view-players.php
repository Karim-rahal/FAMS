<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';
checkLogin();

$user_id = $_SESSION['user_id'];

// ✅ Check if current user is a Coach
$stmt = $conn->prepare("SELECT role, coach_team FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role, $coach_team);
$stmt->fetch();
$stmt->close();

if ($role !== 'Coach') {
    echo "<p>Access Denied. Coaches only.</p>";
    include '../includes/footer.php';
    exit();
}

// ✅ Fetch players assigned to this coach by team
$stmt = $conn->prepare("SELECT id, full_name FROM users WHERE role = 'Player' AND plan = ?");
$stmt->bind_param("s", $coach_team);
$stmt->execute();
$players = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ✅ Fetch selected player's progress if ID is passed
$selected_progress = [];
if (isset($_GET['player_id'])) {
    $pid = (int)$_GET['player_id'];
    $stmt = $conn->prepare("
    SELECT ts.session_type, pp.date, pp.skill_area, pp.rating, pp.notes
    FROM player_progress pp
    JOIN training_sessions ts ON pp.session_id = ts.id
    WHERE pp.user_id = ?
");

    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $selected_progress = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<div class="container coach-progress-section">
    <h2>My Players' Progress (<?= htmlspecialchars($coach_team) ?> Team)</h2>

    <div class="player-list">
        <h3>Players</h3>
        <ul>
            <?php foreach ($players as $p): ?>
                <li>
                    <a href="?player_id=<?= $p['id'] ?>" class="btn"><?= htmlspecialchars($p['full_name']) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if (!empty($selected_progress)): ?>
        <div class="progress-details">
            <h3>Progress for <?= htmlspecialchars($p['full_name']) ?></h3>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Session Type</th>
                        <th>Skill Area</th>
                        <th>Rating</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selected_progress as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['session_type']) ?></td>
                            <td><?= htmlspecialchars($row['skill_area']) ?></td>
                            <td><?= htmlspecialchars($row['rating']) ?></td>
                            <td><?= htmlspecialchars($row['notes']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
