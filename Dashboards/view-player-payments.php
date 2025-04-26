
<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';
checkLogin();

$user_id = $_SESSION['user_id'];

// Ensure only players can access
$stmt = $conn->prepare("SELECT role, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role, $email);
$stmt->fetch();
$stmt->close();

if ($role !== 'Player') {
    echo "<p>Access Denied. Players Only.</p>";
    include '../includes/footer.php';
    exit();
}

// Fetch payment info from payments table
$stmt = $conn->prepare("SELECT due, status FROM payments WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($due, $status);
$stmt->fetch();
$stmt->close();

echo '<h2>My Payment Details</h2>';
echo '<table border="1">';
echo '<tr><th>Email</th><th>Due ($)</th><th>Status</th></tr>';

echo '<tr>';
echo '<td>' . htmlspecialchars($email) . '</td>';
echo '<td>' . ($status === 'Paid' ? 'N/A' : htmlspecialchars($due)) . '</td>';
echo '<td>' . htmlspecialchars($status) . '</td>';
echo '</tr>';

echo '</table>';
include '../includes/footer.php';
?>
