<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/inventory/secure_pass/db_config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../secure_pass/login.php");
    exit();
}

// Optional: Log that someone accessed the vault
// logActivity($conn, $_SESSION['username'], "Accessed Inventory Vault", "INVENTORY");

$result = $conn->query("SELECT * FROM inventory ORDER BY item_name ASC");
?>

<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ../secure_pass/login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "secure_db");

// Fetch all items
$result = $conn->query("SELECT * FROM inventory");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory | Stock Control</title>
    <style>
        body { font-family: sans-serif; background: #0f111a; color: white; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #2d3142; text-align: left; }
        th { background: #1a1d2b; color: #00ff88; }
        .low-stock { color: #ff4d4d; font-weight: bold; background: rgba(255, 77, 77, 0.1); }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; }
        .badge-ok { background: #00ff88; color: #000; }
        .badge-low { background: #ff4d4d; color: #fff; }
    </style>
</head>
<body>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h1>📦 Inventory Management</h1>
    <a href="../secure_pass/portal.php" style="color: #888;">Back to Hub</a>
</div>

<table>
    <tr>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): 
        $isLow = $row['quantity'] <= $row['min_stock'];
    ?>
    <tr class="<?php echo $isLow ? 'low-stock' : ''; ?>">
        <td><?php echo $row['item_name']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td>
            <?php if($isLow): ?>
                <span class="badge badge-low">LOW STOCK</span>
            <?php else: ?>
                <span class="badge badge-ok">HEALTHY</span>
            <?php endif; ?>
        </td>
        <td>
           <a href="update_stock.php?id=<?php echo $row['id']; ?>" style="color: #00d4ff;">Adjust Stock</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>