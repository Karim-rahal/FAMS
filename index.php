<?php include 'includes/header.php'; ?>


<section class="hero-section">
  <div class="hero-content">
    <h1>Welcome to FAMS</h1>
    <p>Where Football Meets Precision – Manage, Train, Dominate!</p>
    <a href="register.php" class="btn">Join the Academy</a>
  </div>
</section>

<section class="features-section">
  <h2>Our Key Features</h2>
  <div class="features">
    <div class="feature">
      <img src="assets/images/player-management.png" alt="Player Management">
      <h3>Player Management</h3>
      <p>Track registrations, personal info, and development history all in one place.</p>
    </div>
    <div class="feature">
      <img src="assets/images/training-schedules.png" alt="Training Schedules">
      <h3>Training & Matches</h3>
      <p>Effortlessly organize team sessions and competitive matches without conflicts.</p>
    </div>
    <div class="feature">
      <img src="assets/images/performance-analysis.png" alt="Performance Analysis">
      <h3>Performance Analysis</h3>
      <p>Access real-time metrics to assess and improve players’ tactical performance.</p>
    </div>
  </div>
</section>

<section class="about-section">
  <div class="about-content">
    <img src="assets/images/about-us.jpg" alt="About Us">
    <div class="text">
      <h2>About FAMS</h2>
      <p>FAMS is a next-gen football academy management system designed for clubs and academies to optimize operations, performance, and communication. Built with precision, it serves coaches, staff, players, and administrators with ease.</p>
    </div>
  </div>
</section>

<section class="cta-section">
  <h2>Get in Touch</h2>
  <p>Have questions or want to learn more? Reach out to us directly.</p>
  <a href="contactUs.php" class="btn">Contact Us</a>
</section>

<section class="gallery-section">
  <h2>Academy Highlights</h2>
  <div class="gallery">
    <img src="assets/images/training-session.jpg" alt="Training Session">
    <img src="assets/images/team-formation.jpg" alt="Team Formation">
    <img src="assets/images/victory-moments.jpg" alt="Victory Moments">
  </div>
</section>

<script>
// Basic fade-in effect
window.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('section').forEach(section => {
    section.style.opacity = 0;
    section.style.transition = 'opacity 1.2s ease';
    setTimeout(() => section.style.opacity = 1, 300);
  });
});
window.addEventListener('scroll', () => {
  document.querySelectorAll('section').forEach(sec => {
    const top = sec.getBoundingClientRect().top;
    if (top < window.innerHeight - 100) sec.classList.add('visible');
  });
});
</script>
     <?php include 'includes/footer.php'; ?>