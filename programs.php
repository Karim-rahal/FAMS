<?php include "includes/header.php"; ?>



<section class="programs-hero">
    <h1>Explore Our Programs</h1>
    <p>Empowering you with knowledge and skills for a successful future.</p>
</section>

<section class="programs-section container">
    <h2>Available Programs</h2>
    <div class="programs-grid">
    <div class="program-card">
        <img src="assets/images/youth-development.jpg" alt="Youth Development Program">
        <h3>Youth Development</h3>
        <p>Designed for aspiring players aged 6–15. Focuses on core techniques, agility, and tactical awareness.</p>
    </div>
    <div class="program-card">
        <img src="assets/images/elite-training.jpg" alt="Elite Training Program">
        <h3>Elite Training</h3>
        <p>Advanced coaching for athletes targeting professional careers. Emphasizes performance, endurance, and mental strength.</p>
    </div>
    <div class="program-card">
        <img src="assets/images/goalkeeping.png" alt="Goalkeeper Academy">
        <h3>Goalkeeper Academy</h3>
        <p>Specialized program focusing on shot-stopping, distribution, reflexes, and tactical positioning.</p>
    </div>
    <div class="program-card">
        <img src="assets/images/coaching.jpg" alt="Coaching Certification">
        <h3>Coaching Certification</h3>
        <p>Train to become a certified football coach with modules on tactics, player management, and fitness planning.</p>
    </div>
</div>

</section>

<section class="cta-section">
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name']) && isset($_SESSION['user_email'])): ?>
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
        <p>You’re already enrolled. Access your personalized dashboard below.</p>
        <a href="dashboard.php" class="btn">Go to Dashboard</a>
    <?php else: ?>
        <h2>Ready to Begin?</h2>
        <p>Start your journey today by enrolling in one of our programs.</p>
        <a href="register.php" class="btn">Register Now</a>
    <?php endif; ?>
</section

<?php include "includes/footer.php"; ?>
