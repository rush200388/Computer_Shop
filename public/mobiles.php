<?php
session_start();
require_once "../src/db.php";

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
        $error = "Product not found.";
    } elseif($product['stock'] <= 0){
        $error = "Product out of stock!";
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

// Fetch mobile products
$products = $conn->query("SELECT * FROM products WHERE type='Mobile' ORDER BY id DESC");

// Fetch user's cart
$cart_items = $conn->query("SELECT c.id as cart_id, p.name, p.price, c.quantity 
                            FROM cart c JOIN products p ON c.product_id=p.id 
                            WHERE c.user_id=$user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobiles - Computer Shop</title>
<style>
body { margin:0; font-family:Arial, sans-serif; background:#0d0d0d; color:#fff; }

/* Navbar */
.navbar {
  display:flex;
  justify-content:space-between;
  align-items:center;
  background:#000;
  padding:15px 20px;
  font-size:20px;
  position:fixed;
  top:0;
  width:100%;
  z-index:1002;
}
.navbar .logo img { width:100px; }
.hamburger { 
    font-size:26px; 
    cursor:pointer; 
    color:#00bfff; 
    position: relative; /* required for z-index */
    z-index: 9999; /* above everything */
}

/* Side Panel Right */
#sidePanel {
  height:100%;
  width:0;
  position:fixed;
  top:0;
  right:0;
  background-color:#111;
  overflow-x:hidden;
  transition:0.5s;
  padding-top:60px;
  z-index:1000;
}
#sidePanel a {
  padding:10px 30px;
  text-decoration:none;
  font-size:20px;
  color:#fff;
  display:block;
  transition:0.3s;
}
#sidePanel a:hover { background:#333; }
#sidePanel .closebtn { position:absolute; top:15px; right:25px; font-size:36px; cursor:pointer; }

/* Products Grid */
.products { padding:100px 20px 40px 20px; display:grid; grid-template-columns:repeat(auto-fit, minmax(250px,1fr)); gap:20px; max-width:1200px; margin:auto; }
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
.floating-cart, .floating-contact {
  position:fixed;
  bottom:20px;
  width:60px;
  height:60px;
  border-radius:50%;
  display:flex;
  justify-content:center;
  align-items:center;
  z-index:1000; /* lower than hamburger */
  cursor:pointer;
  box-shadow:0 4px 10px rgba(0,0,0,0.3);
  transition: transform 0.2s;
}
.floating-cart { right:20px; background:#00bfff; }
.floating-contact { right:100px; background:#ff6600; }
.floating-cart:hover, .floating-contact:hover { transform:scale(1.1); }
.floating-cart img, .floating-contact img { width:35px; height:35px; }

/* Cart Panel */
.cart { background:#1a1a1a; border-radius:10px; padding:15px; max-width:400px; margin:20px auto; }
.cart h3 { color:#00bfff; }
.cart-item { display:flex; justify-content:space-between; margin-bottom:10px; }

/* Footer */
footer { background:#000; color:#fff; padding:30px 20px; text-align:center; }

/* Messages */
.message { text-align:center; margin:10px; color:yellow; font-weight:bold; }
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div class="logo"><a href="home.php"><img src="src/logo.png" alt="logo"></a></div>
  <span class="hamburger" onclick="openNav()">☰</span>
</div>

<!-- Side Panel Right -->
<div id="sidePanel">
  <span class="closebtn" onclick="closeNav()">×</span>
  <a href="home.php">Home</a>
  <a href="f_products.php">Products</a>
  <a href="mobiles.php">Mobiles</a>
  <a href="laptops.php">Laptops</a>
  <a href="accessories.php">Accessories</a>
  <a href="monitors.php">Monitors</a>
  <a href="graphic.php">Graphics Cards</a>
  <a href="casing.php">Casings</a>
  <a href="cart.php">Cart</a>
  <a href="customer_dashboard.php">Dashboard</a>
  <a href="user_logout.php">Logout</a>
</div>

<?php if(isset($success)) echo "<p class='message'>$success</p>"; ?>
<?php if(isset($error)) echo "<p class='message'>$error</p>"; ?>

<!-- Products Section -->
<section class="products">
<?php while($product = $products->fetch_assoc()): ?>
<div class="product-card">
  <img src="image.php?file=<?php echo urlencode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
  <h3><?php echo htmlspecialchars($product['name']); ?></h3>
  <p><?php echo htmlspecialchars($product['description'] ?: 'No description available.'); ?></p>
  <p class="stock">Available Stock: <?php echo $product['stock']; ?></p>
  <div class="price">Rs.<?php echo number_format($product['price'],2); ?></div>
  <?php if($product['stock'] > 0): ?>
    <form method="post">
      <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
      <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>
  <?php else: ?>
    <button disabled>Out of Stock</button>
  <?php endif; ?>
</div>
<?php endwhile; ?>
</section>

<!-- Floating Buttons -->
<a href="cart.php" class="floating-cart">
  <img src="src/cart.png" alt="Cart">
</a>
<a href="mailto:support@computershop.com" class="floating-contact" title="Contact Us">
  <img src="src/contact.png" alt="Contact">
</a>

<!-- Cart Panel -->
<div class="cart">
<h3>Your Cart</h3>
<?php if($cart_items->num_rows > 0): ?>
  <?php while($item = $cart_items->fetch_assoc()): ?>
    <div class="cart-item">
      <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
      <span>Rs.<?php echo number_format($item['price']*$item['quantity'],2); ?></span>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>Cart is empty.</p>
<?php endif; ?>
</div>

<!-- Footer -->
<footer>
  <h3>Contact Us</h3>
  <p>Email: support@computershop.com</p>
  <p>Phone: 071-123-4567</p>
  <p>© 2025 Computer Shop. All Rights Reserved.</p>
</footer>

<script>
function openNav() { document.getElementById("sidePanel").style.width = "250px"; }
function closeNav() { document.getElementById("sidePanel").style.width = "0"; }
</script>

</body>
</html>
