<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAMS</title>
    <link rel="stylesheet" href="/FAMS/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Oswald:wght@200..700&family=Phudu:wght@300..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
    <div class="logo">
    <img src="/FAMS/assets/images/famsLogo.PNG" alt="FAMS Logo" style="height:40px; vertical-align:middle; margin-right:10px;">
    <span style="vertical-align:middle;">FAMS</span>
    </div>
        
        <div class="nav-links">
            <a href="/FAMS/index.php">Home</a>
            <a href="/FAMS/contactUs.php">Contact Us</a>
            <a href="/FAMS/aboutUs.php">About Us</a>
            <a href="/FAMS/programs.php">Programs</a>
            <a href="/FAMS/Pricing.php">Pricing</a>
            <a href="/FAMS/Events.php">Events</a>
            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                <a href="/FAMS/dashboard.php">My Dashboard</a>
                <div class="user-dropdown">
                    <button class="user-button"><?= htmlspecialchars($_SESSION['user_name']) ?> ▼</button>
                    <div class="dropdown-menu">
                        <a href="/FAMS/profile.php">Profile</a>
                        <a href="/FAMS/Logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/FAMS/login.php">Login</a>
                <a href="/FAMS/register.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">

       