<?php
session_start();
require_once '../src/db.php';

// Redirect if not logged in
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$cart_items = $conn->query("SELECT c.id as cart_id, c.quantity, p.* 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id=$user_id");

$total_amount = 0;
$items = [];
while($row = $cart_items->fetch_assoc()){
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $total_amount += $row['subtotal'];
    $items[] = $row;
}

// Check if cart is empty
$cart_empty = empty($items);

// Initialize success and error messages
$order_success = false;
$order_error = '';
$order_details = [];

if(isset($_POST['place_order'])){
    if($cart_empty){
        $order_error = "Your cart is empty. Please add items before placing an order.";
    } else {
        $name = $conn->real_escape_string($_POST['name']);
        $address = $conn->real_escape_string($_POST['address']);
        $phone = $conn->real_escape_string($_POST['phone']);

        // Insert order
        $conn->query("INSERT INTO orders (user_id, customer_name, address, phone, total, status, created_at) 
                      VALUES ($user_id, '$name', '$address', '$phone', $total_amount, 'Pending', NOW())");
        $order_id = $conn->insert_id;

        // Insert order items
        foreach($items as $it){
            $pid = $it['id'];
            $qty = $it['quantity'];
            $price = $it['price'];
            $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                          VALUES ($order_id, $pid, $qty, $price)");
        }

        // Clear cart
        $conn->query("DELETE FROM cart WHERE user_id=$user_id");

        // Prepare data for modal
        $order_success = true;
        $order_details = [
            'id' => $order_id,
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'total' => $total_amount,
            'items' => $items
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Computer Shop</title>
    <style>
        body{font-family:Arial,sans-serif;background:#0d0d0d;color:#fff;margin:0;padding:0;}
        table{width:90%;margin:20px auto;border-collapse:collapse;}
        th,td{padding:10px;border:1px solid #ddd;text-align:center;}
        th{background:#007BFF;color:#fff;}
        form{width:60%;margin:20px auto;}
        input,textarea{width:100%;padding:8px;margin:5px 0;}
        .btn{background:#00bfff;color:#000;border:none;padding:10px 20px;border-radius:5px;font-weight:bold;cursor:pointer;margin-right:10px;}
        .btn:hover{background:#0095d1;color:#fff;}
        /* Modal styles */
        .modal{display:none;position:fixed;z-index:999;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:rgba(0,0,0,0.8);}
        .modal-content{background:#1a1a1a;margin:10% auto;padding:20px;border-radius:10px;width:80%;max-width:600px;text-align:center;}
        .close{color:#fff;float:right;font-size:28px;font-weight:bold;cursor:pointer;}
        .close:hover{color:#ff0000;}
        .success{color:#28a745;font-size:20px;margin-bottom:10px;}
        .error{color:red;text-align:center;margin-top:10px;}
        .button-group{text-align:center;margin-bottom:20px;}
    </style>
</head>
<body>
<h1 style="text-align:center;">Checkout</h1>

<?php if($order_error): ?>
    <div class="error"><?= $order_error ?></div>
<?php endif; ?>

<!-- Back to Cart button -->
<div class="button-group">
    <button class="btn" onclick="window.location.href='cart.php'">← Back to Cart</button>
</div>

<table>
<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Subtotal</th>
</tr>
<?php foreach($items as $it): ?>
<tr>
    <td><?= htmlspecialchars($it['name']) ?></td>
    <td>Rs.<?= number_format($it['price'],2) ?></td>
    <td><?= $it['quantity'] ?></td>
    <td>Rs.<?= number_format($it['subtotal'],2) ?></td>
</tr>
<?php endforeach; ?>
<tr>
    <th colspan="3">Total</th>
    <th>Rs.<?= number_format($total_amount,2) ?></th>
</tr>
</table>

<form method="POST">
    <label>Name</label>
    <input type="text" name="name" required <?= $cart_empty ? 'disabled' : '' ?>>

    <label>Address</label>
    <textarea name="address" required <?= $cart_empty ? 'disabled' : '' ?>></textarea>

    <label>Phone</label>
    <input type="text" name="phone" required <?= $cart_empty ? 'disabled' : '' ?>>

    <button type="submit" name="place_order" class="btn" <?= $cart_empty ? 'disabled' : '' ?>>
        Place Order (Cash on Delivery)
    </button>

    <!-- Back to Cart button inside form -->
    <button type="button" class="btn" onclick="window.location.href='cart.php'">Back to Cart</button>
</form>

<!-- Modal -->
<?php if($order_success): ?>
<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('successModal').style.display='none'">&times;</span>
        <div class="success">✅ Your Order Has Been Placed Successfully!</div>
        <p>Order ID: <strong><?= $order_details['id'] ?></strong></p>
        <p>Name: <?= htmlspecialchars($order_details['name']) ?></p>
        <p>Address: <?= htmlspecialchars($order_details['address']) ?></p>
        <p>Phone: <?= htmlspecialchars($order_details['phone']) ?></p>
        <p>Total Amount: Rs.<?= number_format($order_details['total'],2) ?></p>
        <h3>Order Items</h3>
        <table style="margin:auto;width:90%;">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach($order_details['items'] as $it): ?>
            <tr>
                <td><?= htmlspecialchars($it['name']) ?></td>
                <td>Rs.<?= number_format($it['price'],2) ?></td>
                <td><?= $it['quantity'] ?></td>
                <td>Rs.<?= number_format($it['subtotal'],2) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="button-group">
            <button class="btn" onclick="window.location.href='home.php'">Back to Home</button>
            <button class="btn" onclick="window.location.href='cart.php'">Back to Cart</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('successModal').style.display = 'block';
</script>
<?php endif; ?>

</body>
</html>
