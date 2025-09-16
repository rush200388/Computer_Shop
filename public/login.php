<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Computer Shop</title>
<style>
  body { font-family: Arial; background: #0d0d0d; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; }
  form { background: #1a1a1a; padding: 30px; border-radius: 10px; width: 300px; }
  input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
  button { width: 100%; padding: 10px; background: #00bfff; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
  button:hover { background: #0095d1; color: #fff; }
  a { color: #00bfff; text-decoration: none; }
</style>
</head>
<body>

<form action="login.php" method="POST">
  <h2>Login</h2>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit" name="login">Login</button>
  <p>Don't have an account? <a href="register.php">Register</a></p>
  <p><a href="forgot_password.php">Forgot Password?</a></p>

</form>

<?php
session_start();
require_once '../src/db.php';

if(isset($_POST['login'])){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: customer_dashboard.php'); // Redirect to dashboard page
            exit;
        } else {
            echo "<p style='color:red;'>Incorrect password!</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found!</p>";
    }
}
?>
</body>
</html>
