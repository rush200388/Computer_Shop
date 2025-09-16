<?php
session_start();
require_once '../src/db.php';

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/PHPMailer-6.10.0/src/Exception.php';
require 'src/PHPMailer-6.10.0/src/PHPMailer.php';
require 'src/PHPMailer-6.10.0/src/SMTP.php';

$message = "";

if(isset($_POST['reset'])){
    $email = $conn->real_escape_string($_POST['email']);
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    
    if($result->num_rows == 1){
        $token = bin2hex(random_bytes(50));
        $conn->query("UPDATE users SET reset_token='$token', reset_expires=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email='$email'");

        // Send reset email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com"; 
            $mail->SMTPAuth = true;
            $mail->Username = "ravindusandeepa000@gmail.com"; // your Gmail
            $mail->Password = "wlbpqbayvmmbzqcj";  // Gmail App Password
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;

            $mail->setFrom("ravindusandeepa000@gmail.com", "Grizmo.lk");
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Password Reset Request";
            $mail->Body = "Click here to reset your password: 
              <a href='http://localhost/Computer_Shop/public/reset_password.php?token=$token'>Reset Password</a>";

            $mail->send();
            $message = "Password reset link sent to your email.";
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password - Grizmo.lk</title>
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
  input[type="email"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #00bfff;
      background: #0d0d0d;
      color: #fff;
  }
  input[type="email"]::placeholder {
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
    <h2>Forgot Password</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" name="reset">Send Reset Link</button>
    </form>

    <a href="login.php" class="back-btn">Back to Login</a>

    <?php if($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
</div>
</body>
</html>
