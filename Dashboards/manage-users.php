<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';
checkLogin();

// ✅ Admin restriction
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

// ✅ Role update handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roles'])) {
    foreach ($_POST['roles'] as $uid => $newRole) {
        if (in_array($newRole, ['Admin', 'Coach', 'Player', 'NONE'])) {
            $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
            $stmt->bind_param("si", $newRole, $uid);
            $stmt->execute();
            $stmt->close();
        }
    }
    echo "<script>alert('Roles updated successfully.'); window.location.href='manage-users.php';</script>";
    exit();
}

// ✅ Users grouped by plan
$plans = ['U15', 'U18', 'U21'];
$users_by_plan = [];
foreach ($plans as $plan) {
    $stmt = $conn->prepare("SELECT id, email, role, plan FROM users WHERE plan = ?");
    $stmt->bind_param("s", $plan);
    $stmt->execute();
    $result = $stmt->get_result();
    $users_by_plan[$plan] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// ✅ Users with no role and no plan
$stmt = $conn->prepare("SELECT id, email, role, plan FROM users WHERE role = 'NONE' AND plan = 'NONE'");
$stmt->execute();
$result = $stmt->get_result();
$unassigned_users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ✅ All coaches
$stmt = $conn->prepare("SELECT id, email, coach_type, coach_team FROM users WHERE role = 'Coach'");
$stmt->execute();
$result = $stmt->get_result();
$coaches = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="dashboard-container">
    <h2>Manage Users</h2>

    <form method="POST" action="manage-users.php">
        <?php foreach ($users_by_plan as $plan => $users): ?>
            <h3>Plan: <?= $plan ?></h3>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Change Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td>
                                <select name="roles[<?= $user['id'] ?>]">
                                    <option value="Player" <?= $user['role'] === 'Player' ? 'selected' : '' ?>>Player</option>
                                    <option value="Coach" <?= $user['role'] === 'Coach' ? 'selected' : '' ?>>Coach</option>
                                    <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="NONE" <?= $user['role'] === 'NONE' ? 'selected' : '' ?>>NONE</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>

        <h3>Unassigned Users (No Role & No Plan)</h3>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Current Plan</th>
                    <th>Change Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($unassigned_users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['plan']) ?></td>
                        <td>
                            <select name="roles[<?= $user['id'] ?>]">
                                <option value="Player">Player</option>
                                <option value="Coach">Coach</option>
                                <option value="Admin">Admin</option>
                                <option value="NONE" selected>NONE</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" class="btn">Update Roles</button>
    </form>

    <h3>All Coaches</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Coach Type</th>
                <th>Team</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($coaches as $coach): ?>
                <tr>
                    <td><?= htmlspecialchars($coach['email']) ?></td>
                    <td><?= htmlspecialchars($coach['coach_type'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($coach['coach_team'] ?? 'N/A') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>



<?php include '../includes/footer.php'; ?>
