<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';
checkLogin();


$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role !== 'Admin') {
    echo "<p>Access denied. Only Admins can schedule matches.</p>";
    include '../includes/footer.php';
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $match_date = $_POST['match_date'];
    $opponent = trim($_POST['opponent']);
    $location = trim($_POST['location']);
    $team = $_POST['team'];

    if ($match_date && $opponent && $location ) {
        $stmt = $conn->prepare("INSERT INTO matches (match_date, opponent, location, team) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $match_date, $opponent, $location, $team);
        if ($stmt->execute()) {
            echo "<script>alert('Match scheduled successfully.'); window.location.href='scheduleMatches.php';</script>";
        } else {
            echo "<script>alert('Failed to schedule match.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all required fields correctly.');</script>";
    }
}
?>

<div class="dashboard-container">
    <h2>Schedule a Match</h2>
    <form method="POST" action="scheduleMatches.php" class="schedule-form">
        <label for="match_date">Match Date:</label>
        <input type="date" name="match_date" id="match_date" required>

        <label for="opponent">Opponent Name:</label>
        <input type="text" name="opponent" id="opponent" placeholder="e.g., Tigers FC" required>

        <label for="location">Match Location:</label>
        <input type="text" name="location" id="location" placeholder="e.g., Main Stadium" required>

        <label for="team">Team:</label>
        <select name="team" id="team" required>
            <option value="">Select Team</option>
            <option value="U15">U15</option>
            <option value="U18">U18</option>
            <option value="U21">U21</option>
            
        </select>

        <button type="submit" class="btn">Schedule Match</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
