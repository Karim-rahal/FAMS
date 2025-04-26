
<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';


$user_id = $_SESSION['user_id'];
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPass = $_POST['old-password'];
    $newPass = $_POST['new-password'];
    $confPass = $_POST['confirm-password'];

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($currentHash);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($oldPass, $currentHash)) {
        $errors[] = "Old password is incorrect.";
    } elseif ($newPass !== $confPass) {
        $errors[] = "New password and confirmation do not match.";
    } else {
        $newHash = password_hash($newPass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->bind_param("si", $newHash, $user_id);
        if ($stmt->execute()) {
            $success = "Password has been successfully updated.";
        } else {
            $errors[] = "An error occurred while updating the password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-section">
    <h2>Reset Password</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert-box">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php elseif ($success): ?>
        <div class="success-box"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <label for="old-password">Old Password</label>
        <input type="password" name="old-password" id="old-password" required>

        <label for="new-password">New Password</label>
        <input type="password" name="new-password" id="new-password" required>

        <label for="confirm-password">Confirm New Password</label>
        <input type="password" name="confirm-password" id="confirm-password" required>

        <button type="submit" class="btn">Update Password</button>
    </form>
</div>

</body>
</html>
