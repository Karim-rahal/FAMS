<?php
session_start();
include '../includes/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($current_user_role);
$stmt->fetch();
$stmt->close();

if ($current_user_role !== 'Admin') {
    die("Unauthorized: Only admins can update user roles.");
}

// === 2. Validate and process role updates ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roles']) && is_array($_POST['roles'])) {
    foreach ($_POST['roles'] as $user_id => $new_role) {
        // Validate each role
        if (!in_array($new_role, ['Admin', 'Coach', 'Player', 'NONE'])) {
            continue; // Skip invalid values
        }

        // Update role in database
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Optional: add flash messaging here
    header("Location: manage-users.php?success=1");
    exit();
} else {
    // Bad request
    header("Location: manage-users.php?error=1");
    exit();
}
