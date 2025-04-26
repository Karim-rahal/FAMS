<?php
include '../includes/db.php';

$teams = ['U15', 'U18', 'U21'];
$sessions_by_team = [];
$matches_by_team = [];

foreach ($teams as $team) {
   
    $stmt = $conn->prepare("SELECT session_date, session_type, status FROM training_sessions WHERE team = ? ORDER BY session_date ASC");
    $stmt->bind_param("s", $team);
    $stmt->execute();
    $result = $stmt->get_result();
    $sessions_by_team[$team] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    
        $stmt = $conn->prepare("SELECT match_date, opponent, location, status FROM matches WHERE team = ? ORDER BY match_date ASC");
        $stmt->bind_param("s", $team);
        $stmt->execute();
        $result = $stmt->get_result();
        $matches_by_team[$team] = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
   
}
?>

<?php include  '../includes/header.php'; ?>
<div class="dashboard-container">
    <h2>All Team Schedules</h2>

    <?php foreach ($teams as $team): ?>
        <div class="team-section">
            <h3><?= htmlspecialchars($team) ?> Team</h3>

            <!-- Training Sessions -->
            <button class="toggle-btn" onclick="toggleSection('sessions-<?= $team ?>')">View Training Sessions</button>
            <div class="section-content" id="sessions-<?= $team ?>" style="display: none;">
                <?php if (count($sessions_by_team[$team])): ?>
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
                            <?php foreach ($sessions_by_team[$team] as $session): ?>
                                <tr>
                                    <td><?= $session['session_date'] ?></td>
                                    <td><?= date('l', strtotime($session['session_date'])) ?></td>
                                    <td><?= htmlspecialchars($session['session_type']) ?></td>
                                    <td><?= htmlspecialchars($session['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No training sessions available.</p>
                <?php endif; ?>
            </div>

            <!-- Matches (U15 & U18 only) -->
            <?php  ?>
                <button class="toggle-btn" onclick="toggleSection('matches-<?= $team ?>')">View Matches</button>
                <div class="section-content" id="matches-<?= $team ?>" style="display: none;">
                    <?php if (count($matches_by_team[$team])): ?>
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
                                <?php foreach ($matches_by_team[$team] as $match): ?>
                                    <tr>
                                        <td><?= $match['match_date'] ?></td>
                                        <td><?= date('l', strtotime($match['match_date'])) ?></td>
                                        <td><?= htmlspecialchars($match['opponent']) ?></td>
                                        <td><?= htmlspecialchars($match['location']) ?></td>
                                        <td><?= htmlspecialchars($match['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No matches scheduled.</p>
                    <?php endif; ?>
                </div>
         
        </div>
    <?php endforeach; ?>
</div>
<script>
function toggleSection(id) {
    const el = document.getElementById(id);
    el.style.display = (el.style.display === "none" || el.style.display === "") ? "block" : "none";
}
</script>

    <?php include  '../includes/footer.php'; ?>
