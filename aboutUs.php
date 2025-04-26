

<?php include 'includes/header.php'; ?>
    <section class="hero-section about-hero">
        <h1>About Us</h1>
        <p>Who we are, what we do, and why it matters.</p>
        
    </section>

    <section class="about-section container" id="mission">
        <img src="assets/images/team.jpg" alt="Our Team">
        <div class="text">
            <h2>Our Mission</h2>
            <p>
                We aim to deliver innovative and scalable digital solutions to solve real-world problems. 
                Our team is committed to quality, transparency, and long-term impact through technology.
            </p>
        </div>
    </section>

    <section class="features-section">
        <h2>What Sets Us Apart</h2>
        <div class="features">
            <div class="feature">
                <img src="assets/images/innovation.jpg" alt="Innovation">
                <h3>Innovation</h3>
                <p>We use the latest technologies to stay ahead of the curve.</p>
            </div>
            <div class="feature">
                <img src="assets/images/quality.jpg" alt="Quality">
                <h3>Quality</h3>
                <p>Our solutions undergo rigorous testing and peer review.</p>
            </div>
            <div class="feature">
                <img src="assets/images/support.jpg" alt="Support">
                <h3>Support</h3>
                <p>We provide continuous support and improvement plans.</p>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <h2>Join Our Journey</h2>
        <p>Become part of a forward-thinking, innovative team shaping the future.</p>
        <a href="contactUs.php" class="btn">Contact Us</a>
    </section>

    
    <script>
       
       document.addEventListener("DOMContentLoaded", function() {
           const sections = document.querySelectorAll("section");
           const observer = new IntersectionObserver(entries => {
               entries.forEach(entry => {
                   if (entry.isIntersecting) {
                       entry.target.classList.add("visible");
                   }
               });
           }, { threshold: 0.2 });

           sections.forEach(section => observer.observe(section));
       });
   </script>
   <?php include 'includes/footer.php'; ?>
