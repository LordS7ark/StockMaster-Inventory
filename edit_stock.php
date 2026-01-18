<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_qty = (int)$_POST['new_qty'];
    
    // Update the database with the manually entered number
    $sql = "UPDATE products SET stock_qty = $new_qty WHERE id = $id";
    
    if ($conn->query($sql)) {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Stock Level</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 50px; text-align: center; }
        .card { background: white; padding: 30px; border-radius: 10px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input { padding: 10px; width: 80px; font-size: 18px; text-align: center; margin: 10px; }
        button { padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Update Stock: <?php echo $product['item_name']; ?></h2>
        <p>Current Quantity: <strong><?php echo $product['stock_qty']; ?></strong></p>
        
        <form method="POST">
            <label>Enter New Total Quantity:</label><br>
            <input type="number" name="new_qty" value="<?php echo $product['stock_qty']; ?>" required>
            <br>
            <button type="submit">Update Stock</button>
        </form>
        <br>
        <a href="index.php" style="color: #666; text-decoration: none;">Cancel</a>
    </div>
</body>
</html>