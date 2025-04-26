<?php
session_start();
include  '../includes/db.php';
include '../includes/header.php';
checkLogin();


$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role !== 'Admin') {
    echo "<p>Access Denied. Admins Only.</p>";
    include '../includes/footer.php';
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_user_id'])) {
    $cancel_user_id = (int)$_POST['cancel_user_id'];

    $stmt = $conn->prepare("DELETE FROM payments WHERE user_id = ?");
    $stmt->bind_param("i", $cancel_user_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE users SET plan = 'NONE', role = 'NONE' WHERE id = ?");
    $stmt->bind_param("i", $cancel_user_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Plan cancelled and payment deleted.'); window.location.href='view-payments.php';</script>";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['statuses'])) {
    foreach ($_POST['statuses'] as $user_id => $status) {
        $stmt = $conn->prepare("UPDATE payments SET status = ? WHERE user_id = ?");
        $stmt->bind_param("si", $status, $user_id);
        $stmt->execute();
        $stmt->close();

        if ($status === 'Paid') {
            $stmt = $conn->prepare("UPDATE users SET role = 'Player' WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    echo "<script>alert('Statuses updated.'); window.location.href='view-payments.php';</script>";
    exit();
}




$query = "
    SELECT users.id, users.email, users.role, users.plan, payments.due, payments.status
    FROM payments
    JOIN users ON payments.user_id = users.id
    WHERE users.plan != 'NONE'
    ORDER BY payments.status, users.plan
";
$result = $conn->query($query);
$payments = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="dashboard-container">
    <h2>View Payments</h2>

   
    <form method="POST" action="view-payments.php">
        <table class="user-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Plan</th>
                    <th>Due ($)</th>
                    <th>Status</th>
                    <th>Change Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['plan']) ?></td>
                        <td>
                        <?= $user['status'] === 'Paid' ? 'N/A' : htmlspecialchars($user['due']) ?>
                        </td>
                        <td><?= htmlspecialchars($user['status']) ?></td>
                        <td>
                            <select name="statuses[<?= $user['id'] ?>]" class="status-select">
                                <option value="Pending" <?= $user['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Paid" <?= $user['status'] === 'Paid' ? 'selected' : '' ?>>Paid</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn">Update Statuses</button>
    </form>

    <h3 style="margin-top: 40px;">Cancel Plans</h3>
    <?php foreach ($payments as $user): ?>
        <form method="POST" action="view-payments.php" onsubmit="return confirm('Cancel plan for <?= $user['email'] ?>?');" style="margin-bottom: 10px;">
            <input type="hidden" name="cancel_user_id" value="<?= $user['id'] ?>">
            <button type="submit" class="btn-cancel">
                Cancel <?= htmlspecialchars($user['email']) ?>'s Plan (<?= $user['plan'] ?>)
            </button>
        </form>
    <?php endforeach; ?>
</div>



<?php include '../includes/footer.php'; ?>
