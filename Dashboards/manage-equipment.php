<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';
checkLogin();

// ✅ Only Admins Allowed
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role !== 'Admin') {
    echo "<p>Access denied. Admins only.</p>";
    include '../includes/footer.php';
    exit();
}

// ✅ Handle Add Equipment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_equipment'])) {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $quantity = (int)$_POST['quantity'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO equipment (name, type, quantity, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $type, $quantity, $status);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Equipment added successfully.'); window.location.href='manage-equipment.php';</script>";
    exit();
}

// ✅ Handle Update Quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_equipment'])) {
    foreach ($_POST['equipment'] as $id => $data) {
        $qty = (int)$data['quantity'];
        $status = $data['status'];
        $stmt = $conn->prepare("UPDATE equipment SET quantity = ?, status = ? WHERE id = ?");
        $stmt->bind_param("isi", $qty, $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>alert('Equipment updated successfully.'); window.location.href='manage-equipment.php';</script>";
    exit();
}

// ✅ Fetch All Equipment
$equipment = $conn->query("SELECT * FROM equipment ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>

<h2>Manage Equipment</h2>

<h3>Add New Equipment</h3>
<form method="POST" class="equipment-form">
    <input type="hidden" name="add_equipment" value="1">
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Type:</label>
    <input type="text" name="type">

    <label>Quantity:</label>
    <input type="number" name="quantity" min="0" required>

    <label>Status:</label>
    <select name="status">
        <option value="Available">Available</option>
        <option value="In Use">In Use</option>
        <option value="Damaged">Damaged</option>
        <option value="Unavailable">Unavailable</option>
    </select>

    <button type="submit" class="btn">Add Equipment</button>
</form>

<h3>All Equipment</h3>
<form method="POST">
    <input type="hidden" name="update_equipment" value="1">
    <table class="equipment-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($equipment as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['type']) ?></td>
                <td>
                    <input type="number" name="equipment[<?= $item['id'] ?>][quantity]" value="<?= $item['quantity'] ?>" min="0">
                </td>
                <td>
                    <select name="equipment[<?= $item['id'] ?>][status]">
                        <option value="Available" <?= $item['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="In Use" <?= $item['status'] === 'In Use' ? 'selected' : '' ?>>In Use</option>
                        <option value="Damaged" <?= $item['status'] === 'Damaged' ? 'selected' : '' ?>>Damaged</option>
                        <option value="Unavailable" <?= $item['status'] === 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                    </select>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <button type="submit" class="btn">Update Equipment</button>
</form>



<?php include '../includes/footer.php'; ?>
