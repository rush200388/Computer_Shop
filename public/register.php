<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Computer Shop</title>
<style>
  body { 
    font-family: Arial; 
    background: #0d0d0d; 
    color: #fff; 
    display: flex; 
    justify-content: center; 
    align-items: center; 
    height: 100vh; 
  }
  form { 
    background: #1a1a1a; 
    padding: 30px; 
    border-radius: 10px; 
    width: 300px; 
  }
  input { 
    width: 100%; 
    padding: 10px; 
    margin: 10px 0; 
    border-radius: 5px; 
    border: 1px solid #ccc; 
  }
  button { 
    width: 100%; 
    padding: 10px; 
    background: #00bfff; 
    border: none; 
    border-radius: 5px; 
    font-weight: bold; 
    cursor: pointer; 
  }
  button:hover { 
    background: #0095d1; 
    color: #fff; 
  }
  a { 
    color: #00bfff; 
    text-decoration: none; 
  }
</style>
</head>
<body>

<form action="register.php" method="POST">
  <h2>Register</h2>
  <input type="text" name="name" placeholder="Full Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="text" name="mobile" placeholder="Mobile Number" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit" name="register">Register</button>
  <p>Already have an account? <a href="login.php">Login here</a></p>
</form>

<?php
require_once '../src/db.php'; // Your DB connection file

if(isset($_POST['register'])){
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']); // mobile number
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($check->num_rows > 0){
        echo "<p style='color:red; text-align:center;'>Email already exists!</p>";
    } else {
        $conn->query("INSERT INTO users (name, email, mobile, password) VALUES ('$name', '$email', '$mobile', '$password')");
        echo "<p style='color:green; text-align:center;'>Registration successful! <a href='login.php'>Login</a></p>";
    }
}
?>
</body>
</html>
