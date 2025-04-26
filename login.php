<?php

require 'includes/db.php';
 require 'includes/header.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "Both fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, email, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<section class="login-section">
  <h2>Login to Your Account</h2>

  <?php if ($error): ?>
    <p style="color: red; text-align:center; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form action="login.php" method="post" class="login-form">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit" class="btn">Login</button>
    <p class="underbtn">Don't have an account? <a href="register.php">Register</a> </p>
  </form>
</section>
<script>
document.querySelectorAll('section').forEach(section => {
  section.classList.add('visible');
});
</script>
<?php require 'includes/footer.php';?>


