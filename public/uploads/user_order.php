<?php
require_once "../src/auth.php";
require_once "../src/db.php";

$user_id = intval($_GET['id']);
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

$orders = $conn->query("SELECT o.*, p.name as product_name 
                        FROM orders o 
                        JOIN products p ON o.product_id = p.id
                        WHERE o.user_id=$user_id
                        ORDER BY o.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="../assets/favicon.png" type="image/png">
    <title><?php echo $user['name']; ?> Orders</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center;}
        th { background: #007BFF; color: white; }
    </style>
</head>
<body>
    <h1>Orders of <?php echo $user['name']; ?></h1>
    <a href="users.php">Back to Users</a>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
        <?php while($order = $orders->fetch_assoc()): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo $order['product_name']; ?></td>
            <td><?php echo $order['quantity']; ?></td>
            <td><?php echo $order['total']; ?></td>
            <td><?php echo $order['status']; ?></td>
            <td><?php echo $order['created_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
