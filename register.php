<?php

require 'includes/db.php';
require 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $fathername = trim($_POST['fathername'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $age = intval($_POST['age'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$fullname || !$fathername || !$gender || !$age || !$email || !$password || !$confirm_password) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, father_name, gender, age, email, password_hash) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiss", $fullname, $fathername, $gender, $age, $email, $password_hash);
        if ($stmt->execute()) {
            $success = "Registration successful. You may now <a href='login.php'>login</a>.";
        } else {
            $error = "Email already exists or database error.";
        }
        $stmt->close();
    }
}
?>

<section class="register-section">
  <h2>Create Your Account</h2>

  <?php if ($error): ?>
    <p style="color: red; text-align:center;"><?= htmlspecialchars($error) ?></p>
  <?php elseif ($success): ?>
    <p style="color: green; text-align:center;"><?= $success ?></p>
  <?php endif; ?>

  <form action="register.php" method="post" class="register-form">
    <label for="fullname">Full Name:</label>
    <input type="text" id="fullname" name="fullname" required>

    <label for="fathername">Father's Name:</label>
    <input type="text" id="fathername" name="fathername" required>

    <label for="gender">Gender:</label>
    <select id="gender" name="gender" required>
      <option value="">Select Gender</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
      
    </select>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" min="5" max="100" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">New Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <button type="submit" class="btn">Register</button>
    <p class="underbtn">Already have an account? <a href="login.php">Log in</a> </p>

  </form>
</section>


<script>
document.querySelectorAll('section').forEach(section => {
  section.classList.add('visible');
});
</script>
<?php include 'includes/footer.php'; ?>