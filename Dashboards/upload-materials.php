
<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';
checkLogin();

$user_id = $_SESSION['user_id'];

// Fetch coach's assigned team
$stmt = $conn->prepare("SELECT role, coach_team FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role, $coach_team);
$stmt->fetch();
$stmt->close();

if ($role !== 'Coach') {
    echo "<p>Access denied. Coaches only.</p>";
    include '../includes/footer.php';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["material"])) {
    $file_name = basename($_FILES["material"]["name"]);
    $target_dir = "../uploads/";
    $target_file = $target_dir . time() . "_" . $file_name;

    if (move_uploaded_file($_FILES["material"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO coaching_materials (coach_id, team, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $coach_team, $target_file);
        $stmt->execute();
        $stmt->close();

        echo "<p>File uploaded successfully to team " . htmlspecialchars($coach_team) . ".</p>";
    } else {
        echo "<p>Error uploading file.</p>";
    }
}
?>

<h2>Upload Coaching Material for <?= htmlspecialchars($coach_team) ?></h2>
<div class="materials-upload-section">
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="material" required />
    <button type="submit">Upload</button>
</form>
</div>

<?php include '../includes/footer.php'; ?>
