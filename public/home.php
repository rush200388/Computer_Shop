<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Computer Shop</title>
<style>
  body { margin:0; font-family:Arial,sans-serif; background:#0d0d0d; color:#fff; }

  /* Navbar */
  .navbar {
    display:flex; justify-content:space-between; align-items:center;
    background:#000; padding:15px 20px; font-size:20px;
  }
  .navbar .logo img { width:100px; }
  .hamburger { font-size:28px; color:#fff; cursor:pointer; display:block; }

  /* Side Panel */
  #sidePanel {
    height:100%; width:0; position:fixed; top:0; right:0; background-color:#111;
    overflow-x:hidden; transition:0.5s; padding-top:60px; z-index:1000;
  }
  #sidePanel a {
    padding:10px 30px; text-decoration:none; font-size:20px; color:#fff; display:block; transition:0.3s;
  }
  #sidePanel a:hover { background:#333; }
  #sidePanel .closebtn { position:absolute; top:15px; right:25px; font-size:36px; color:#fff; }

  /* Slideshow */
  .slideshow-container { position: relative; max-width:100%; overflow:hidden; margin-top:20px; }
  .slides { display:none; width:100%; }
  .slideshow-container img { width:100%; height:auto; }

  /* Video */
  .video-section { text-align:center; margin:30px 0; }
  .video-section video { width:100%; max-width:100%; height:auto; }

  /* Image Slider */
  .image-slider { display:flex; width:100%; overflow-x:auto; margin:20px 0; }
  .image-slider img { flex:0 0 auto; width:300px; margin:10px; border-radius:10px; }

  /* Footer */
  footer {
    background:#000; color:#fff; padding:30px 20px; display:flex; justify-content:space-around; flex-wrap:wrap; text-align:left;
  }
  footer div { margin:10px; }
  footer a, footer p { color:#fff; display:block; text-decoration:none; margin:5px 0; }
  footer a:hover { color:#ffcc00; }

  /* Floating Cart */
  .floating-cart, .floating-contact {
    position:fixed; bottom:20px; width:60px; height:60px;
    border-radius:50%; display:flex; justify-content:center; align-items:center;
    box-shadow:0 4px 10px rgba(0,0,0,0.3); z-index:1000; cursor:pointer; transition: transform 0.2s;
  }
  .floating-cart { right:20px; background:#00bfff; }
  .floating-contact { right:90px; background:#28a745; }
  .floating-cart img, .floating-contact img { width:35px; height:35px; }
  .floating-cart:hover, .floating-contact:hover { transform:scale(1.1); }

  /* Responsive */
  @media(max-width:768px){
    .image-slider { flex-direction:column; align-items:center; }
    .hamburger{ display:block; }
  }
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

  <!-- Slideshow -->
  <div class="slideshow-container">
    <a href="monitors.php"><img class="slides" src="src/slide1.jpg" style="display:block;"></a>
    <a href="laptops.php"><img class="slides" src="src/slide2.jpg"></a>
    <a href="casing.php"><img class="slides" src="src/slide3.jpg"></a>
  </div>

  <!-- Video Sections -->
  <div class="video-section">
    <h2>Watch Our Promo</h2>
    <a href="laptops.php">
      <video autoplay muted loop playsinline>
        <source src="src/vedio1.mp4" type="video/mp4">
      </video>
    </a>
  </div>

  <div class="image-slider">
    <a href="computer_parts.php"><img src="src/img1.jpg"></a>
    <a href="accessories.php"><img src="src/img2.jpg"></a>
    <a href="graphic.php"><img src="src/img3.jpg"></a>
  </div>

  <div class="video-section">
    <h2>New Release MSI Casing</h2>
    <a href="casing.php">
      <video autoplay muted loop playsinline>
        <source src="src/promo.mp4" type="video/mp4">
      </video>
    </a>
  </div>

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
    <div>
      <h3>CONTACT US</h3>
      <p>Phone: +94 123 456 789</p>
      <p>Email: info@grizmo.lk</p>
      <p>Address: Colombo, Sri Lanka</p>
      <p>&copy; 2025 Grizmo.lk</p>
    </div>
  </footer>

<script>
  // Slideshow
  let slideIndex = 0;
  showSlides();
  function showSlides() {
    let slides = document.getElementsByClassName("slides");
    for (let i = 0; i < slides.length; i++) slides[i].style.display="none";
    slideIndex++;
    if(slideIndex>slides.length) slideIndex=1;
    slides[slideIndex-1].style.display="block";
    setTimeout(showSlides,3000);
  }

  // Side Panel
  function openNav(){ document.getElementById("sidePanel").style.width="250px"; }
  function closeNav(){ document.getElementById("sidePanel").style.width="0"; }
</script>

</body>
</html>
