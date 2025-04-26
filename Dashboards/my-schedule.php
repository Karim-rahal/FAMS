<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';
checkLogin();


$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role, plan FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role, $plan);
$stmt->fetch();
$stmt->close();



$stmt = $conn->prepare("SELECT session_date, session_type, status FROM training_sessions WHERE team = ? ORDER BY session_date ASC");
$stmt->bind_param("s", $plan);
$stmt->execute();
$trainings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();


$stmt = $conn->prepare("SELECT match_date, opponent, location, status FROM matches WHERE team = ? ORDER BY match_date ASC");
$stmt->bind_param("s", $plan);
$stmt->execute();
$matches = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="dashboard-container">
    <h2>My Schedule (<?= htmlspecialchars($plan) ?> Team)</h2>

    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <button class="toggle-btn" onclick="toggleSection('training-schedule')">View Training Sessions</button>
        <button class="toggle-btn" onclick="toggleSection('match-schedule')">View Matches</button>
    </div>

    
    <div id="training-schedule" class="schedule-block" style="display: none;">
        <h3>Training Sessions</h3>
        <?php if (count($trainings)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trainings as $t): ?>
                        <tr>
                            <td><?= $t['session_date'] ?></td>
                            <td><?= date('l', strtotime($t['session_date'])) ?></td>
                            <td><?= htmlspecialchars($t['session_type']) ?></td>
                            <td><?= htmlspecialchars($t['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No training sessions scheduled.</p>
        <?php endif; ?>
    </div>

  
    <div id="match-schedule" class="schedule-block" style="display: none;">
        <h3>Matches</h3>
        <?php if (count($matches)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Opponent</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matches as $m): ?>
                        <tr>
                            <td><?= $m['match_date'] ?></td>
                            <td><?= date('l', strtotime($m['match_date'])) ?></td>
                            <td><?= htmlspecialchars($m['opponent']) ?></td>
                            <td><?= htmlspecialchars($m['location']) ?></td>
                            <td><?= htmlspecialchars($m['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No matches scheduled.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleSection(id) {
    const block = document.getElementById(id);
    block.style.display = (block.style.display === "none" || block.style.display === "") ? "block" : "none";
}
</script>

<?php require_once '../includes/footer.php'; ?>
