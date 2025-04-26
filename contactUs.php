<?php

require 'includes/db.php';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : trim($_POST['name'] ?? '');
    $email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : trim($_POST['email'] ?? '');
    $subject = isset($_SESSION['user_name']) ? "Message from " . $_SESSION['user_name'] : trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$subject || !$message) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        if ($stmt->execute()) {
            $success = "Message submitted successfully.";
        } else {
            $error = "Error saving your message.";
        }
        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="contact-hero">
    <h1>Contact Us</h1>
    <p>We'd love to hear from you. Reach out for any inquiries, feedback, or support.</p>
</section>

<section class="contact-info-form">
    <div class="contact-info">
        <h2>Get in Touch</h2>
        <p><strong>Phone:</strong> +961 1 234 567</p>
        <p><strong>Email:</strong> support@famsacademy.com</p>
        <p><strong>Location:</strong> Beirut, Lebanon</p>
        <img src="assets/images/contact-us.jpg" alt="Contact Visual">
    </div>

    <div class="contact-form">
        <h2>Send Us a Message</h2>

        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="post" action="contactUs.php">
            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name']) && isset($_SESSION['user_email'])): ?>
                <input type="hidden" name="name" value="<?= htmlspecialchars($_SESSION['user_name']) ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['user_email']) ?>">
                <input type="hidden" name="subject" value="Message from <?= htmlspecialchars($_SESSION['user_name']) ?>">

                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            <?php else: ?>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            <?php endif; ?>
            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

