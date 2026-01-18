<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
$today = date('Y-m-d');

// Fetch today's sales
$sales = $conn->query("SELECT * FROM sales_history WHERE DATE(sale_date) = '$today' AND activity_type = 'Sale'");
$totals = $conn->query("SELECT SUM(price_at_sale) as grand_total, COUNT(*) as count FROM sales_history WHERE DATE(sale_date) = '$today' AND activity_type = 'Sale'")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report - <?php echo $today; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-section { margin-top: 30px; text-align: right; font-size: 1.2em; }
        
        /* This hides the print button when the actual PDF is generated */
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #27ae60; color: white; border: none; cursor: pointer; border-radius: 5px;">
            📥 Download as PDF / Print
        </button>
        <a href="index.php" style="margin-left: 10px; text-decoration: none; color: #666;">Back to Dashboard</a>
    </div>

    <div class="header">
        <h1>NAIJACLEAN STOCKMASTER</h1>
        <p>Daily Sales Report: <strong><?php echo date('D, M d, Y'); ?></strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Item Name</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $sales->fetch_assoc()): ?>
            <tr>
                <td><?php echo date('h:i A', strtotime($row['sale_date'])); ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td>₦<?php echo number_format($row['price_at_sale'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="total-section">
        <p>Total Items Sold: <strong><?php echo $totals['count']; ?></strong></p>
        <p>Total Revenue: <strong style="color: #27ae60;">₦<?php echo number_format($totals['grand_total'], 2); ?></strong></p>
    </div>

</body>
</html>