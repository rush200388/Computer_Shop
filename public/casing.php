<?php
session_start();
require_once '../src/db.php';

// Redirect if not logged in
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle Add to Cart
if(isset($_POST['add_to_cart'])){
    $product_id = intval($_POST['product_id']);

    $product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();
    if(!$product){
        die("Product not found.");
    }

    if($product['stock'] <= 0){
        $error = "Sorry, product out of stock!";
    } else {
        $existing = $conn->query("SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id")->fetch_assoc();
        if($existing){
            $new_qty = $existing['quantity'] + 1;
            $conn->query("UPDATE cart SET quantity=$new_qty WHERE id=".$existing['id']);
        } else {
            $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
        }
        $conn->query("UPDATE products SET stock = stock - 1 WHERE id=$product_id");
        $success = "Product added to cart!";
    }
}

// Fetch Casing products
$products = $conn->query("SELECT * FROM products WHERE type='Casing' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Casing - Computer Shop</title>
<style>
body { margin:0; font-family:Arial,sans-serif; background:#0d0d0d; color:#fff; }

/* Navbar */
.navbar { display:flex; justify-content:space-between; align-items:center; background:#000; padding:15px 20px; font-size:20px; }
.navbar .logo img { width:100px; }
.hamburger { font-size:26px; cursor:pointer; color:#00bfff; }

/* Side Panel */
#sidePanel { height:100%; width:0; position:fixed; top:0; right:0; background-color:#111; overflow-x:hidden; transition:0.5s; padding-top:60px; z-index:1000; }
#sidePanel a { padding:10px 30px; text-decoration:none; font-size:20px; color:#fff; display:block; transition:0.3s; }
#sidePanel a:hover { background:#333; }
#sidePanel .closebtn { position:absolute; top:15px; right:25px; font-size:36px; }

/* Products Grid */
.products { padding:40px 20px; display:grid; grid-template-columns:repeat(auto-fit, minmax(250px,1fr)); gap:20px; max-width:1200px; margin:auto; }
.product-card { background:#1a1a1a; border-radius:10px; padding:15px; box-shadow:0 4px 12px rgba(0,191,255,0.5); text-align:center; transition: transform 0.2s; }
.product-card:hover { transform:scale(1.05); }
.product-card img { width:100%; height:300px; object-fit:contain; border-radius:10px; }
.product-card h3 { margin:15px 0 10px; font-size:18px; color:#00bfff; }
.product-card p { font-size:14px; margin-bottom:10px; color:#ccc; }
.product-card .price { font-size:18px; color:#ff6600; margin-bottom:10px; }
.product-card .stock { font-size:14px; color:#00ff99; margin-bottom:10px; }
.product-card button { background:#00bfff; color:#000; border:none; padding:10px 15px; border-radius:5px; cursor:pointer; font-weight:bold; }
.product-card button:hover { background:#0095d1; color:#fff; }

/* Floating Buttons */
.floating-cart, .floating-contact { position:fixed; bottom:20px; width:60px; height:60px; border-radius:50%; display:flex; justify-content:center; align-items:center; box-shadow:0 4px 10px rgba(0,0,0,0.3); z-index:1000; cursor:pointer; transition: transform 0.2s; }
.floating-cart { right:20px; background:#00bfff; }
.floating-contact { right:90px; background:#28a745; }
.floating-cart img, .floating-contact img { width:35px; height:35px; }
.floating-cart:hover, .floating-contact:hover { transform:scale(1.1); }

/* Footer */
footer { background:#000; color:#fff; padding:30px 20px; text-align:center; }
footer p { margin:5px 0; color:#fff; }

/* Responsive */
@media(max-width:768px){ .hamburger{display:block;} }
@media(max-width:500px){ .navbar{flex-direction:column;align-items:flex-start;} footer{flex-direction:column;text-align:center;} }
.message { text-align:center; margin:10px; color:yellow; font-weight:bold; }
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div class="logo"><a href="home.php"><img src="src/logo.png" alt="logo"></a></div>
  <span class="hamburger" onclick="openNav()">☰</span>
</div>

<!-- Side Panel -->
<div id="sidePanel">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <a href="home.php">Home</a>
  <a href="f_products.php">Products</a>
  <a href="accessories.php">Accessories</a>
  <a href="laptops.php">Laptops</a>
  <a href="mobiles.php">Mobiles</a>
  <a href="monitors.php">Monitors</a>
  <a href="graphic.php">Graphic Cards</a>
  <a href="casing.php">Casings</a>
  <a href="computer_parts.php">Computer Parts</a>
  <?php if(isset($_SESSION['user_id'])): ?>
    <a href="customer_dashboard.php">Dashboard</a>
    <a href="user_logout.php">Logout</a>
  <?php else: ?>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
  <?php endif; ?>
</div>

<?php if(isset($success)) echo "<p class='message'>$success</p>"; ?>
<?php if(isset($error)) echo "<p class='message'>$error</p>"; ?>

<!-- Product Grid -->
<section class="products">
<?php
if($products->num_rows > 0){
    while($product = $products->fetch_assoc()){ ?>
        <div class="product-card">
            <img src="image.php?file=<?= urlencode($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <p><?= htmlspecialchars($product['description'] ?: 'No description available.') ?></p>
            <p class="stock">Available Stock: <?= $product['stock'] ?></p>
            <div class="price">Rs.<?= number_format($product['price'],2) ?></div>
            <?php if($product['stock'] > 0): ?>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            <?php else: ?>
                <button disabled>Out of Stock</button>
            <?php endif; ?>
        </div>
    <?php }
}else{
    echo "<p style='text-align:center; width:100%;'>No casing products available.</p>";
}
?>
</section>

<!-- Floating Cart -->
<a href="cart.php" class="floating-cart">
  <img src="src/cart.png" alt="Cart">
</a>

<!-- Floating Contact -->
<a href="tel:+94123456789" class="floating-contact">
  <img src="src/contact.png" alt="Contact">
</a>

<!-- Footer -->
<footer>
  <p>Phone: +94 123 456 789</p>
  <p>Email: info@grizmo.lk</p>
  <p>Address: Colombo, Sri Lanka</p>
  <p>&copy; 2025 Grizmo.lk</p>
</footer>

<script>
function openNav(){ document.getElementById("sidePanel").style.width="250px"; }
function closeNav(){ document.getElementById("sidePanel").style.width="0"; }
</script>

</body>
</html>
