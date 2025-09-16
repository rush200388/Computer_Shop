<?php
require_once '../src/db.php';

$message = "";

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $result = $conn->query("SELECT * FROM users WHERE reset_token='$token' AND reset_expires > NOW()");
    
    if($result->num_rows == 1){
        if(isset($_POST['update'])){
            $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$new_pass', reset_token=NULL, reset_expires=NULL WHERE reset_token='$token'");
            $message = "Password updated successfully. <a href='login.php'>Login</a>";
        }
    } else {
        $message = "Invalid or expired token.";
    }
} else {
    $message = "No token provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password - Grizmo.lk</title>
<style>
  body {
      font-family: Arial, sans-serif;
      background-color: #0d0d0d;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
  }
  .container {
      background: #1a1a1a;
      padding: 40px 30px;
      border-radius: 12px;
      width: 350px;
      box-shadow: 0 0 15px rgba(0,191,255,0.5);
  }
  h2 {
      text-align: center;
      color: #00bfff;
      margin-bottom: 20px;
  }
  input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #00bfff;
      background: #0d0d0d;
      color: #fff;
  }
  input[type="password"]::placeholder {
      color: #bbb;
  }
  button, .back-btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 6px;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 10px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
  }
  button {
      background: #00bfff;
  }
  button:hover {
      background: #0095d1;
  }
  .back-btn {
      background: #333;
      color: #00bfff;
      border: 1px solid #00bfff;
  }
  .back-btn:hover {
      background: #00bfff;
      color: #fff;
  }
  .message {
      margin-top: 15px;
      text-align: center;
      color: yellow;
  }
</style>
</head>
<body>
<div class="container">
    <h2>Reset Password</h2>
    <?php if($result->num_rows == 1): ?>
    <form method="POST">
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit" name="update">Update Password</button>
    </form>
    <?php endif; ?>
    <a href="login.php" class="back-btn">Back to Login</a>
    <?php if($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
</div>
</body>
</html>
