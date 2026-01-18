<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['type'];

    // First, get the product details for our history log
    $product_query = $conn->query("SELECT item_name, price FROM products WHERE id = $id");
    $product = $product_query->fetch_assoc();

    if ($type == 'add') {
        $sql = "UPDATE products SET stock_qty = stock_qty + 1 WHERE id = $id";
        $conn->query($sql);
    } elseif ($type == 'sub') {
        // Only log a sale if we actually have stock to sell
        $check_stock = $conn->query("SELECT stock_qty FROM products WHERE id = $id");
        $stock = $check_stock->fetch_assoc();

        if ($stock['stock_qty'] > 0) {
            // 1. Subtract the stock
            $conn->query("UPDATE products SET stock_qty = stock_qty - 1 WHERE id = $id");

            // 2. Record the sale in History
            $name = $product['item_name'];
            $price = $product['price'];
            $conn->query("INSERT INTO sales_history (product_id, product_name, quantity, price_at_sale) 
                          VALUES ($id, '$name', 1, $price)");
        }
    }
    if ($type == 'add') {
    $conn->query("UPDATE products SET stock_qty = stock_qty + 1 WHERE id = $id");
    // Add this line to log the addition
    $conn->query("INSERT INTO sales_history (product_id, product_name, quantity, price_at_sale, activity_type) 
                  VALUES ($id, '{$product['item_name']}', 1, 0, 'Restock')");
}

    header("Location: index.php");
    exit();
}
?>