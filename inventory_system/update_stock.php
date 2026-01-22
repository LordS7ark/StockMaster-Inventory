<?php
// 1. Show me everything that goes wrong
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. The Path: Go UP one level from 'inventory_system', then INTO 'secure_pass'
$config_path = __DIR__ . '/../secure_pass/db_config.php';

if (file_exists($config_path)) {
    require_once($config_path);
} else {
    die("System Error: Cannot find db_config.php at " . $config_path);
}

session_start();

// 3. Security & ID Check
if (!isset($_SESSION['user_id'])) { header("Location: ../secure_pass/login.php"); exit(); }
if (!isset($_GET['id'])) { die("No item ID provided."); }

$id = intval($_GET['id']);

// 4. Fetch Item Details
$stmt = $conn->prepare("SELECT * FROM inventory WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) { die("Item not found."); }

// 5. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_qty = intval($_POST['quantity']);
    $update = $conn->prepare("UPDATE inventory SET quantity = ? WHERE id = ?");
    $update->bind_param("ii", $new_qty, $id);
    
    if ($update->execute()) {
        header("Location: index.php"); // Go back to the list
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adjust Stock | <?php echo $item['item_name']; ?></title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0f111a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .update-card { background: #1a1d2b; padding: 30px; border-radius: 15px; border: 1px solid #00d4ff; width: 350px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        h2 { color: #00d4ff; margin-top: 0; }
        input { width: 100%; padding: 12px; margin: 15px 0; border-radius: 8px; border: 1px solid #2d3142; background: #0f111a; color: white; box-sizing: border-box; font-size: 1rem; }
        button { width: 100%; padding: 12px; background: #00d4ff; color: #0f111a; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        button:hover { background: #00b8e6; transform: scale(1.02); }
        .cancel-link { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="update-card">
    <h2>Adjust Stock</h2>
    <p style="color: #888;">Editing: <strong><?php echo $item['item_name']; ?></strong></p>
    
    <form method="POST">
        <label>New Quantity Level:</label>
        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" required autofocus>
        <button type="submit">Save Changes</button>
    </form>
    
    <a href="index.php" class="cancel-link">← Back to Inventory</a>
</div>

</body>
</html>