
<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';
checkLogin();

$user_id = $_SESSION['user_id'];

// Fetch player's team
$stmt = $conn->prepare("SELECT role, plan FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role, $team);
$stmt->fetch();
$stmt->close();

if ($role !== 'Player') {
    echo "<p>Access denied. Players only.</p>";
    include '../includes/footer.php';
    exit();
}

// Fetch coaching materials for the player's team
$stmt = $conn->prepare("SELECT file_path, uploaded_at FROM coaching_materials WHERE team = ?");
$stmt->bind_param("s", $team);
$stmt->execute();
$stmt->bind_result($file_path, $uploaded_at);

echo "<h2>Download Coaching Materials for Team " . htmlspecialchars($team) . "</h2>";
echo "<div class='materials-download-section'> <ul>";

while ($stmt->fetch()) {
    $filename = basename($file_path);
    echo "
    
    <li><a href='" . htmlspecialchars($file_path) . "' download>" . htmlspecialchars($filename) . "</a> (Uploaded: " . $uploaded_at . ")</li>";
}
echo "</ul> </div>";

$stmt->close();
include '../includes/footer.php';
?>
