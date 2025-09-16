<?php
session_start();
require_once '../src/db.php';

// Redirect if not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch user orders along with order items and product images
$orders_result = $conn->query("SELECT * FROM orders WHERE user_id=$user_id ORDER BY created_at DESC");
$orders = [];
while($order = $orders_result->fetch_assoc()){
    $order_id = $order['id'];
    $items_result = $conn->query("SELECT oi.quantity, oi.price, p.name, p.image 
                                  FROM order_items oi 
                                  JOIN products p ON oi.product_id = p.id 
                                  WHERE oi.order_id=$order_id");
    $items = [];
    while($item = $items_result->fetch_assoc()){
        $items[] = $item;
    }
    $order['items'] = $items;
    $orders[] = $order;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard - Computer Shop</title>
<style>
body { font-family: Arial; background: #0d0d0d; color: #fff; margin: 0; }
.navbar {
  background: #000;
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}
.navbar a { color: #00bfff; text-decoration: none; margin: 5px 10px; font-weight: bold; }
.navbar a:hover { color: #ffcc00; }
.navbar .logo img { width: 100px; }
.dashboard {
  max-width: 900px;
  margin: 50px auto;
  background: #1a1a1a;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
}
h2 { color: #00bfff; }
.info { margin: 20px 0; }
.btn {
  display: inline-block;
  padding: 10px 15px;
  margin: 10px;
  background: #00bfff;
  color: #fff;
  border-radius: 5px;
  text-decoration: none;
  font-weight: bold;
}
.btn:hover { background: #0095d1; }

.orders {
  margin-top: 30px;
  text-align: left;
}
.orders h3 { color: #00bfff; margin-bottom: 15px; }
.orders table { width: 100%; border-collapse: collapse; }
.orders th, .orders td { padding: 10px; border: 1px solid #333; text-align: center; vertical-align: middle; }
.orders th { background: #00bfff; color: #000; }
.orders tr:nth-child(even) { background: #262626; }
.orders tr:hover { background: #333; }
.status-pending { color: #ff4d4d; font-weight: bold; }
.status-processing { color: #ffcc00; font-weight: bold; }
.status-completed { color: #28a745; font-weight: bold; }
.product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div class="logo"><a href="home.php"><img src="src/logo.png" alt="logo"></a></div>
  <div>
    <a href="home.php">Home</a>
    <a href="f_products.php">Products</a>
    <a href="cart.php">Cart</a>
    <a href="edit_profile.php">Edit Profile</a>
    <a href="user_logout.php">Logout</a>
  </div>
</div>

<!-- Dashboard -->
<div class="dashboard">
  <h2>Welcome, <?= htmlspecialchars($user['name']); ?> 👋</h2>
  <div class="info">
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
  </div>
  <a class="btn" href="cart.php">View Cart</a>
  <a class="btn" href="edit_profile.php">Edit Profile</a>

  <!-- User Orders -->
  <div class="orders">
    <h3>My Orders</h3>
    <?php if(count($orders) > 0): ?>
    <?php foreach($orders as $o): ?>
    <table style="margin-bottom:20px;">
      <tr>
        <th>Order ID</th>
        <th>Product</th>
        <th>Image</th>
        <th>Quantity</th>
        <th>Price (Rs.)</th>
        <th>Status</th>
        <th>Created At</th>
      </tr>
      <?php foreach($o['items'] as $item):
          $status_class = '';
          if($o['status']=='Pending') $status_class='status-pending';
          elseif($o['status']=='Processing') $status_class='status-processing';
          elseif($o['status']=='Completed') $status_class='status-completed';
      ?>
      <tr>
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td>
            <?php if($item['image']): ?>
                <img src="../uploads/<?= $item['image'] ?>" class="product-img" alt="Product">
            <?php else: ?>
                No Image
            <?php endif; ?>
        </td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($item['price'],2) ?></td>
        <td class="<?= $status_class ?>"><?= $o['status'] ?></td>
        <td><?= $o['created_at'] ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php endforeach; ?>
    <?php else: ?>
      <p>No orders yet.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
