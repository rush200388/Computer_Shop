<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Computer Parts - Computer Shop</title>
<style>
  body { margin: 0; font-family: Arial, sans-serif; background: #0d0d0d; color: #fff; }
  /* Navbar */
  .navbar { display: flex; justify-content: space-between; align-items: center; background: #000; padding: 15px 20px; flex-wrap: wrap; font-size: 20px; }
  .navbar .logo img { width: 100px; }
  .menu { display: flex; flex-wrap: wrap; }
  .menu a { color: #00bfff; margin: 0 10px; text-decoration: none; font-weight: bold; }
  .menu a:hover { color: #ffcc00; }
  .hamburger { display: none; font-size: 26px; cursor: pointer; }

  /* Product Section */
  .products { padding: 40px 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; max-width: 1200px; margin: auto; }
  .product-card { background: #1a1a1a; border-radius: 10px; padding: 15px; box-shadow: 0 4px 12px rgba(0,191,255,0.5); text-align: center; transition: transform 0.2s; }
  .product-card:hover { transform: scale(1.05); }
  .product-card img { width: 100%; height: 300px; object-fit: contain; border-radius: 10px; }
  .product-card h3 { margin: 15px 0 10px; font-size: 18px; color: #00bfff; }
  .product-card p { font-size: 14px; margin-bottom: 10px; color: #ccc; }
  .product-card .price { font-size: 18px; color: #ff6600; margin-bottom: 10px; }
  .product-card .stock { font-size: 14px; color: #00ff99; margin-bottom: 10px; }
  .product-card button { background: #00bfff; color: #000; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; }
  .product-card button:hover { background: #0095d1; color: #fff; }

  /* Footer */
  footer { background: #000; color: #fff; padding: 30px 20px; display: flex; justify-content: space-around; flex-wrap: wrap; text-align: left; }
  footer div { margin: 10px; }
  footer a { color: #00bfff; display: block; text-decoration: none; margin: 5px 0; font-weight: bold; }
  footer a:hover { color: #ffcc00; }

  /* Responsive */
  @media (max-width: 768px) { 
    .menu { display: none; width: 100%; flex-direction: column; text-align: center; margin-top: 10px; } 
    .menu a { padding: 10px; border-top: 1px solid #333; } 
    .hamburger { display: block; } 
  }
  @media (max-width: 500px) { 
    .navbar { flex-direction: column; align-items: flex-start; } 
    footer { flex-direction: column; text-align: center; } 
  }
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div class="logo"><a href="home.php"><img src="src/logo.png" alt="logo"></a></div>
  <span class="hamburger" onclick="toggleMenu()">☰</span>
  <div class="menu" id="menu">
    <a href="home.php">Home</a>
    <a href="products.php">Products</a>
    <a href="mobiles.php">Mobiles</a>
    <a href="laptops.php">Laptops</a>
    <a href="accessories.php">Accessories</a>
    <a href="monitors.php">Monitors</a>
    <a href="graphic.php">Graphic Cards</a>
    <a href="casing.php">Casings</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
  </div>
</div>

<!-- Product Grid -->
<section class="products" id="parts-container"></section>

<!-- Footer -->
<footer>
  <div>
    <h3>COMPANY</h3>
    <a href="#">About</a>
    <a href="#">Contact</a>
    <a href="#">Invest With Us</a>
    <a href="#">Wholesale</a>
  </div>
  <div>
    <h3>SUPPORT</h3>
    <a href="#">FAQ</a>
    <a href="#">Shipping</a>
    <a href="#">Build My PC</a>
    <a href="#">Warranty</a>
  </div>
</footer>

<script>
// Navbar toggle
function toggleMenu() {
  const menu = document.getElementById("menu");
  menu.style.display = menu.style.display === "flex" ? "none" : "flex";
  menu.style.flexDirection = "column";
}

// Fetch Computer Parts dynamically
function loadComputerParts() {
  fetch('get_products.php?type=ComputerPart')
    .then(res => res.json())
    .then(products => {
      const container = document.getElementById('parts-container');
      container.innerHTML = '';
      products.forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.innerHTML = `
          <img src="${product.image}" alt="${product.name}">
          <h3>${product.name}</h3>
          <p>${product.description || 'No description available.'}</p>
          <p class="stock">Available Stock: ${product.stock}</p>
          <div class="price">Rs.${product.price}</div>
          <button onclick="addToCart(${product.id}, '${product.name}', ${product.price})">Add to Cart</button>
        `;
        container.appendChild(card);
      });
    })
    .catch(err => console.error('Error fetching computer parts:', err));
}

// Add to Cart
function addToCart(id, name, price) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  const index = cart.findIndex(item => item.id === id);
  if(index !== -1) cart[index].quantity += 1;
  else cart.push({ id, name, price, quantity: 1 });
  localStorage.setItem('cart', JSON.stringify(cart));
  alert(`Added "${name}" to cart!`);
}

// Load products on page load
loadComputerParts();
</script>

</body>
</html>
