<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['item_name'];
    $cat = $_POST['category'];
    $price = $_POST['price'];
    $qty = $_POST['stock_qty'];
    $alert = $_POST['alert_level'];

    $sql = "INSERT INTO products (item_name, category, price, stock_qty, alert_level) 
            VALUES ('$name', '$cat', $price, $qty, $alert)";
    
    if ($conn->query($sql)) {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Item</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 40px; }
        .form-box { background: white; padding: 20px; max-width: 400px; margin: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>✨ Register New Stock</h2>
        <form method="POST">
            <input type="text" name="item_name" placeholder="Product Name (e.g. Eva Water)" required>
            <input type="text" name="category" placeholder="Category (e.g. Drinks)" required>
            <input type="number" step="0.01" name="price" placeholder="Price (₦)" required>
            <input type="number" name="stock_qty" placeholder="Starting Quantity" required>
            <input type="number" name="alert_level" placeholder="Low Stock Alert Level (e.g. 5)" required>
            <button type="submit">Add to Inventory</button>
            <a href="index.php" style="display:block; text-align:center; margin-top:10px; color:#666; text-decoration:none;">Cancel</a>
        </form>
    </div>
</body>
</html>