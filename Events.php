<?php

include "includes/header.php";
?>


<section class="events-hero">
    <h1>Academy Events</h1>
    <p>Stay updated with our upcoming matches, training camps, and workshops.</p>
</section>

<section class="events-section container">
    <h2>Upcoming Events</h2>
    <div class="events-grid">

        <div class="event-card">
            <img src="assets/images/summer-camp.jpg" alt="Summer Camp">
            <div class="event-info">
                <h3>Summer Training Camp 2025</h3>
                <p class="event-date">July 1 – July 15, 2025</p>
                <p>Intensive two-week program focusing on fitness, technique, and game strategy. Open to players aged 10–18.</p>
            </div>
        </div>

        <div class="event-card">
            <img src="assets/images/goalkeeper-workshop.jpeg" alt="Goalkeeper Workshop">
            <div class="event-info">
                <h3>Goalkeeper Workshop</h3>
                <p class="event-date">August 10, 2025</p>
                <p>One-day workshop led by pro keepers. Covers reflex drills, positioning, and leadership from the back line.</p>
            </div>
        </div>

        <div class="event-card">
            <img src="assets/images/showcase-match.jpg" alt="Showcase Match">
            <div class="event-info">
                <h3>Showcase Match: U18 vs. Regional All-Stars</h3>
                <p class="event-date">September 5, 2025</p>
                <p>Our academy U18 squad faces top regional talent. Scouts and agents will be attending. Free entry for families.</p>
            </div>
        </div>
     
    </div>
    <div class="video-section">
    <button onclick="toggleVideo()" class="btn watch-btn">Watch Live Stream</button>

    <div id="liveStreamContainer" class="video-container" style="display: none;">
        <iframe width="100%" height="400" src="https://www.youtube.com/embed/5mdSkENlcR0" 
                title="Live Stream"
                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
    </div>
</div>
</section>
<section class="cta-section">
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name']) && isset($_SESSION['user_email'])): ?>
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
        <p>You’re already registered. View and manage your event participation below.</p>
        <a href="dashboard.php" class="btn">Go to Dashboard</a>
    <?php else: ?>
        <h2>Want to Join an Event?</h2>
        <p>Register now to reserve your spot for upcoming sessions and workshops.</p>
        <a href="register.php" class="btn">Register Now</a>
    <?php endif; ?>
</section>
<script>
function toggleVideo() {
    const video = document.getElementById("liveStreamContainer");
    video.style.display = video.style.display === "none" ? "block" : "none";
}
</script>

<?php include "includes/footer.php"; ?>
