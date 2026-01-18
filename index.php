<?php
// 1. Establish the connection FIRST
$conn = new mysqli("localhost", "root", "", "inventory_db");

// 2. Check if the connection worked
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
}

// 3. NOW you can run your queries
$today = date('Y-m-d');

// Total Revenue Query
$revenue_query = $conn->query("SELECT SUM(price_at_sale) as daily_total FROM sales_history WHERE DATE(sale_date) = '$today'");
$revenue_data = $revenue_query->fetch_assoc();
$daily_total = $revenue_data['daily_total'] ?? 0;

// Items Count Query
$count_query = $conn->query("SELECT COUNT(*) as total_count FROM sales_history WHERE DATE(sale_date) = '$today'");
$count_data = $count_query->fetch_assoc();

// Products Table Query
$result = $conn->query("SELECT * FROM products ORDER BY item_name ASC");

// History Table Query
$history = $conn->query("SELECT * FROM sales_history ORDER BY sale_date DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockMaster | Inventory Tracker</title>
    <style>
        :root { --primary: #2c3e50; --danger: #e74c3c; --success: #27ae60; --warning: #f39c12; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        h1 { color: var(--primary); margin: 0; font-size: 24px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: var(--primary); color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        
        /* Stock Status Badges */
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-ok { background: #d4edda; color: var(--success); }
        .status-low { background: #fff3cd; color: var(--warning); border: 1px solid var(--warning); }
        .status-empty { background: #f8d7da; color: var(--danger); animation: blink 1s infinite; }

        @keyframes blink { 50% { opacity: 0.5; } }

        .add-btn { background: var(--success); color: white; text-decoration: none; padding: 10px 15px; border-radius: 5px; font-weight: bold; }
    
    .report-btn { 
    background: #3498db; 
    color: white; 
    text-decoration: none; 
    padding: 10px 15px; 
    border-radius: 5px; 
    font-weight: bold; 
    margin-right: 10px;
    display: inline-block;
}
.report-btn:hover { background: #2980b9; }
    </style>
</head>
<body>
    

<div class="container">
    <header>
    <h1>📦 StockMaster Inventory</h1>
    <div>
        <a href="report.php" class="report-btn">📊 Daily Report</a>
        <a href="add_product.php" class="add-btn">+ New Item</a>
    </div>
</header>

    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
    <div style="flex: 1; background: #27ae60; color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <span style="font-size: 14px; opacity: 0.9;">Today's Revenue</span>
        <h2 style="margin: 5px 0 0 0;">₦<?php echo number_format($daily_total, 2); ?></h2>
    </div>
    
    <div style="flex: 1; background: #2c3e50; color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <span style="font-size: 14px; opacity: 0.9;">Total Sales Today</span>
        <?php 
            $count_query = $conn->query("SELECT COUNT(*) as total_count FROM sales_history WHERE DATE(sale_date) = '$today'");
            $count_data = $count_query->fetch_assoc();
        ?>
        <h2 style="margin: 5px 0 0 0;"><?php echo $count_data['total_count']; ?> Items</h2>
    </div>
</div>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>In Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): 
                // Logic to determine status
                $status_class = "status-ok";
                $status_text = "Good";

                if ($row['stock_qty'] <= 0) {
                    $status_class = "status-empty";
                    $status_text = "OUT OF STOCK";
                } elseif ($row['stock_qty'] <= $row['alert_level']) {
                    $status_class = "status-low";
                    $status_text = "Low Stock";
                }
            ?>
            <tr>
                <td><strong><?php echo $row['item_name']; ?></strong></td>
                <td><?php echo $row['category']; ?></td>
                <td>₦<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['stock_qty']; ?></td>
                <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                <td>
    <a href="update_stock.php?id=<?php echo $row['id']; ?>&type=add" title="Add 1" style="text-decoration:none;">➕</a>
    <a href="update_stock.php?id=<?php echo $row['id']; ?>&type=sub" title="Sell 1" style="text-decoration:none; margin: 0 10px;">➖</a>
    
    <a href="edit_stock.php?id=<?php echo $row['id']; ?>" title="Edit Manually" style="text-decoration:none;">📝</a>
</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php $history = $conn->query("SELECT * FROM sales_history ORDER BY sale_date DESC"); ?>

<hr style="margin-top: 40px; border: 1px solid #eee;">
<h3>🕒 Activity Log (Live Feed)</h3>

<div style="max-height: 300px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead style="position: sticky; top: 0; background: #2c3e50; color: white;">
            <tr>
                <th style="padding: 10px;">Time</th>
                <th style="padding: 10px;">Item</th>
                <th style="padding: 10px;">Type</th>
                <th style="padding: 10px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php while($h = $history->fetch_assoc()): 
                $color = ($h['activity_type'] == 'Sale') ? '#e74c3c' : '#27ae60';
            ?>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                    <?php echo date('M d, h:i A', strtotime($h['sale_date'])); ?>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                    <strong><?php echo $h['product_name']; ?></strong>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #eee; color: <?php echo $color; ?>; font-weight: bold;">
                    <?php echo $h['activity_type']; ?>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                    <?php echo ($h['price_at_sale'] > 0) ? "₦".number_format($h['price_at_sale'], 2) : "-"; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>