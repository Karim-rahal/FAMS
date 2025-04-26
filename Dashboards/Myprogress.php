<?php
session_start();
include '../includes/db.php';
include  '../includes/header.php';;
checkLogin();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<p>Access Denied. Not logged in.</p>";
    include 'includes/footer.php';
    exit();
}

// ✅ Check if user is Player
$stmt = $conn->prepare("SELECT role, full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role, $name);
$stmt->fetch();
$stmt->close();

if ($role !== 'Player') {
    echo "<p>Access Denied. Only players can view their progress.</p>";
    include 'includes/footer.php';
    exit();
}

// ✅ Fetch Player Progress
$stmt = $conn->prepare("
    SELECT ts.session_type, pp.date, pp.skill_area, pp.rating, pp.notes
    FROM player_progress pp
    JOIN training_sessions ts ON pp.session_id = ts.id
    WHERE pp.user_id = ?
    ORDER BY pp.date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$progress = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<style>
h2 {
    text-align: center;
    color: #004d40;
    margin-bottom: 10px;
}
h3 {
    text-align: center;
    color: #00796b;
}
.progress-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.progress-table th,
.progress-table td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
}
.progress-table th {
    background-color: #004d40;
    color: white;
}
.no-progress {
    text-align: center;
    color: #777;
    font-style: italic;
    margin-top: 30px;
}
</style>
<h2>My Skill Progress</h2>
<h3>Welcome, <?= htmlspecialchars($name) ?></h3>

<?php if (empty($progress)): ?>
    <p class="no-progress">No progress records yet. Your coach will update them after training.</p>
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
            <?php foreach ($progress as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['session_type']) ?></td>
                    <td><?= htmlspecialchars($p['date']) ?></td>
                    <td><?= htmlspecialchars($p['skill_area']) ?></td>
                    <td><?= htmlspecialchars($p['rating']) ?></td>
                    <td><?= htmlspecialchars($p['notes']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>



<?php include '../includes/footer.php'; ?>
