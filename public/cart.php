<?php
session_start();
require_once '../src/db.php';

// Redirect if not logged in
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle update quantity
if(isset($_POST['update_qty'])){
    $cart_id = intval($_POST['cart_id']);
    $new_qty = intval($_POST['quantity']);

    $cart_item = $conn->query("SELECT * FROM cart WHERE id=$cart_id AND user_id=$user_id")->fetch_assoc();
    if($cart_item){
        $product_id = $cart_item['product_id'];
        $old_qty = $cart_item['quantity'];

        $product = $conn->query("SELECT stock FROM products WHERE id=$product_id")->fetch_assoc();

        if($new_qty > $old_qty){ // Increase quantity
            $diff = $new_qty - $old_qty;
            if($product['stock'] >= $diff){
                $conn->query("UPDATE cart SET quantity=$new_qty WHERE id=$cart_id");
                $conn->query("UPDATE products SET stock = stock - $diff WHERE id=$product_id");
            } else {
                $error = "Not enough stock for ".$cart_item['product_id'];
            }
        } elseif($new_qty < $old_qty){ // Decrease quantity
            $diff = $old_qty - $new_qty;
            $conn->query("UPDATE cart SET quantity=$new_qty WHERE id=$cart_id");
            $conn->query("UPDATE products SET stock = stock + $diff WHERE id=$product_id");
        }
    }
}

// Handle remove item
if(isset($_GET['remove'])){
    $cart_id = intval($_GET['remove']);
    $cart_item = $conn->query("SELECT * FROM cart WHERE id=$cart_id AND user_id=$user_id")->fetch_assoc();
    if($cart_item){
        $product_id = $cart_item['product_id'];
        $qty = $cart_item['quantity'];
        $conn->query("DELETE FROM cart WHERE id=$cart_id");
        $conn->query("UPDATE products SET stock = stock + $qty WHERE id=$product_id");
    }
}

// Fetch cart items
$cart_items = $conn->query("SELECT c.id as cart_id, c.quantity, p.* 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id=$user_id");

$total_amount = 0;
while($item = $cart_items->fetch_assoc()){
    $total_amount += $item['price'] * $item['quantity'];
}
$cart_items->data_seek(0); // reset pointer
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart - Computer Shop</title>
<style>
body{font-family:Arial,sans-serif;background:#0d0d0d;color:#fff;margin:0;padding:0;}
table{width:90%;margin:20px auto;border-collapse:collapse;}
th,td{padding:10px;border:1px solid #ddd;text-align:center;}
th{background:#007BFF;color:#fff;}
button,input[type=number]{padding:5px;}
a, .btn-link{text-decoration:none;color:#000;background:#00bfff;padding:10px 20px;border-radius:5px;font-weight:bold;display:inline-block;margin:5px;}
a:hover, .btn-link:hover{background:#0095d1;color:#fff;}
.message{color:yellow;text-align:center;margin:10px;}
.checkout-btn{background:#00bfff;color:#000;border:none;padding:10px 20px;border-radius:5px;font-weight:bold;cursor:pointer;}
.checkout-btn:hover{background:#0095d1;color:#fff;}
.product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
</style>
</head>
<body>

<h1 style="text-align:center;">Your Cart</h1>

<?php if(isset($error)) echo "<p class='message'>$error</p>"; ?>

<table>
<tr>
    <th>Image</th>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
    <th>Action</th>
</tr>

<?php while($item = $cart_items->fetch_assoc()): 
    $subtotal = $item['price'] * $item['quantity']; ?>
<tr>
    <td>
        <?php if($item['image']): ?>
            <img src="../uploads/<?= $item['image'] ?>" class="product-img" alt="<?= htmlspecialchars($item['name']) ?>">
        <?php else: ?>
            No Image
        <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($item['name']) ?></td>
    <td>Rs.<?= number_format($item['price'],2) ?></td>
    <td>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['quantity'] + $item['stock'] ?>">
            <button type="submit" name="update_qty">Update</button>
        </form>
    </td>
    <td>Rs.<?= number_format($subtotal,2) ?></td>
    <td><a href="?remove=<?= $item['cart_id'] ?>">Remove</a></td>
</tr>
<?php endwhile; ?>

<tr>
    <th colspan="4">Total</th>
    <th colspan="2">Rs.<?= number_format($total_amount,2) ?></th>
</tr>
</table>

<!-- Back to Products button -->
<div style="text-align:center;margin:20px;">
    <a href="f_products.php" class="btn-link">← Back to Products</a>
</div>

<?php if($total_amount > 0): ?>
<div style="text-align:center;margin:30px;">
    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
</div>
<?php endif; ?>

</body>
</html>
