<?php
require_once "../src/auth.php";
require_once "../src/db.php";

// Update order status
if(isset($_POST['order_id']) && isset($_POST['status'])){
    $order_id = intval($_POST['order_id']);
    $new_status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE orders SET status='$new_status' WHERE id=$order_id");
}

// Fetch all orders with products
$orders_result = $conn->query("
    SELECT o.id AS order_id, o.customer_name, o.address, o.phone, o.total, o.status, o.created_at,
           oi.product_id, oi.quantity, oi.price AS item_price,
           p.name AS product_name
    FROM orders o
    JOIN order_items oi ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
    ORDER BY o.id DESC, oi.id ASC
");

// Organize orders into array
$orders = [];
while($row = $orders_result->fetch_assoc()){
    $oid = $row['order_id'];
    if(!isset($orders[$oid])){
        $orders[$oid] = [
            'order_id' => $oid,
            'customer_name' => $row['customer_name'],
            'address' => $row['address'],
            'phone' => $row['phone'],
            'total' => $row['total'],
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'items' => []
        ];
    }
    $orders[$oid]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'price' => $row['item_price'],
        'subtotal' => $row['quantity'] * $row['item_price']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders - Computer Shop</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #0b0c10; color: #fff; padding: 20px; }
        h1 { text-align: center; color: #00bfff; margin-bottom: 20px; }
        a.back-btn { display: inline-block; padding: 8px 14px; background-color: #00bfff; color: #000; text-decoration: none; border-radius: 5px; margin-bottom: 20px; font-weight: bold; }
        a.back-btn:hover { background-color: #0095d1; color: #fff; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; background-color: #1f2833; border-radius: 8px; overflow: hidden; }
        th, td { border: 1px solid #45a29e; padding: 10px; text-align: center; }
        th { background-color: #0b0c10; color: #00bfff; }
        tr:nth-child(even) { background-color: #1c1f26; }
        tr:hover { background-color: #323c46; }

        select.status-select { padding: 4px 6px; border-radius: 4px; border: 1px solid #45a29e; font-weight: bold; }
        .status-Pending { color: #ff4d4d; font-weight: bold; }
        .status-Processing { color: #ffd700; font-weight: bold; }
        .status-Completed { color: #28a745; font-weight: bold; }

        .order-Pending { background-color: #330000; }
        .order-Processing { background-color: #333300; }
        .order-Completed { background-color: #003300; }

        .order-header { background-color: #0b0c10; color: #00bfff; font-weight: bold; }
        form { display:inline; margin:0; }
        button.update-btn { padding: 3px 6px; margin-left: 5px; border-radius: 4px; border:none; cursor:pointer; font-weight:bold; }
        button.update-btn:hover { opacity:0.8; }
    </style>
</head>
<body>

<h1>All Orders</h1>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

<?php if(empty($orders)): ?>
    <p>No orders found.</p>
<?php else: ?>
    <?php foreach($orders as $order): ?>
        <table class="order-<?= $order['status'] ?>">
            <tr class="order-header">
                <td colspan="5">
                    <strong>Order ID:</strong> <?= $order['order_id'] ?> |
                    <strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?> |
                    <strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?> |
                    <strong>Date:</strong> <?= $order['created_at'] ?> |
                    <strong>Status:</strong>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                        <select name="status" class="status-select status-<?= $order['status'] ?>">
                            <option value="Pending" <?= $order['status']=='Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Processing" <?= $order['status']=='Processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="Completed" <?= $order['status']=='Completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                        <button type="submit" class="update-btn">Update</button>
                    </form>
                </td>
            </tr>
            <tr>
                <th>Product</th>
                <th>Price (Rs.)</th>
                <th>Quantity</th>
                <th>Subtotal (Rs.)</th>
            </tr>
            <?php foreach($order['items'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= number_format($item['price'],2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['subtotal'],2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="3">Order Total</th>
                <th>Rs.<?= number_format($order['total'],2) ?></th>
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
