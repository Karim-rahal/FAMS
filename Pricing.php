
<?php
session_start();
include 'includes/db.php';
include "includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plan'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $plan = $_POST['plan'];
    $validPlans = ['U15', 'U18', 'U21'];
    $planPrices = [
        'U15' => 30.00,
        'U18' => 45.00,
        'U21' => 60.00
    ];

    if (!in_array($plan, $validPlans)) {
        die("Invalid plan.");
    }

    // Check current plan
    $stmt = $conn->prepare("SELECT plan FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($current_plan);
    $stmt->fetch();
    $stmt->close();

    if ($current_plan !== 'NONE') {
        echo "<script>alert('You already have a plan. Contact admin to change it.'); window.location.href='pricing.php';</script>";
        exit();
    }

    // Update plan
    $stmt = $conn->prepare("UPDATE users SET plan = ? WHERE id = ?");
    $stmt->bind_param("si", $plan, $user_id);
    if (!$stmt->execute()) {
        die("Failed to update plan.");
    }
    $stmt->close();

    // Insert payment
    $due = $planPrices[$plan];
    $stmt = $conn->prepare("INSERT INTO payments (user_id, due, status) VALUES (?, ?, 'Pending')");
    $stmt->bind_param("id", $user_id, $due);
    $stmt->execute();
    $stmt->close();

    $_SESSION['user_plan'] = $plan;

    echo "<script>alert('You have successfully joined the {$plan} plan. Payment marked as pending.'); window.location.href='dashboard.php';</script>";
    exit();
}
?>




<section class="pricing-hero">
    <h1>Academy Membership Plans</h1>
    <p>Choose a plan that fits your goals and age group. All plans are billed monthly.</p>
</section>
<section class="pricing-section container">
    <h2>Our Pricing</h2>
    <div class="pricing-grid">

       
        <div class="pricing-card">
            <img src="assets/images/u15.jpg" alt="U15 Player">
            <h3>U15 Player</h3>
            <p class="price">$30 / month</p>
            <ul>
                <li>3 training sessions/week</li>
                <li>Weekend matches</li>
                <li>Personal skill tracking</li>
            </ul>
            <button onclick="openConfirmModal('U15')" class="btn">Join Now</button>

            <div class="confirm-modal" id="confirmModal-U15">
                <div class="modal-content">
                    <p>Are you sure you want to join the <strong>U15</strong> plan?</p>
                    <form method="POST" action="Pricing.php">
                        <input type="hidden" name="plan" value="U15">
                        <button type="submit" class="btn">Yes, Confirm</button>
                        <button type="button" class="btn btn-secondary" onclick="closeConfirmModal('U15')">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

       
        <div class="pricing-card highlight">
            <img src="assets/images/u18.jpg" alt="U18 Player">
            <h3>U18 Player</h3>
            <p class="price">$45 / month</p>
            <ul>
                <li>5 training sessions/week</li>
                <li>Advanced tactics & analytics</li>
                <li>Team tournaments & exposure</li>
            </ul>
            <button onclick="openConfirmModal('U18')" class="btn">Join Now</button>

            <div class="confirm-modal" id="confirmModal-U18">
                <div class="modal-content">
                    <p>Are you sure you want to join the <strong>U18</strong> plan?</p>
                    <form method="POST" action="Pricing.php">
                        <input type="hidden" name="plan" value="U18">
                        <button type="submit" class="btn">Yes, Confirm</button>
                        <button type="button" class="btn btn-secondary" onclick="closeConfirmModal('U18')">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

      
        <div class="pricing-card">
            <img src="assets/images/coach.jpg" alt="U21 Plan">
            <h3>U21 Plan</h3>
            <p class="price">$60 / month</p>
            <ul>
                <li>Advanced tactical training</li>
                <li>Fitness and conditioning programs</li>
                <li>Leadership & game analysis workshops</li>
            </ul>
            <button onclick="openConfirmModal('U21')" class="btn">Join Now</button>

            <div class="confirm-modal" id="confirmModal-U21">
                <div class="modal-content">
                    <p>Are you sure you want to join the <strong>U21</strong> plan?</p>
                    <form method="POST" action="Pricing.php">
                        <input type="hidden" name="plan" value="U21">
                        <button type="submit" class="btn">Yes, Confirm</button>
                        <button type="button" class="btn btn-secondary" onclick="closeConfirmModal('Coach')">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>


<section class="cta-section">
    <h2>Need Help Choosing?</h2>
    <p>Contact us to discuss the best plan for your level and goals.</p>
    <a href="contactUs.php" class="btn">Contact Support</a>
</section>
<script>
function openConfirmModal(planId) {
    const modal = document.getElementById('confirmModal-' + planId);
    if (modal) modal.style.display = 'flex';
}

function closeConfirmModal(planId) {
    const modal = document.getElementById('confirmModal-' + planId);
    if (modal) modal.style.display = 'none';
}
</script>


<?php include "includes/footer.php"; ?>
