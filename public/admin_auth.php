<?php
require_once "../src/db.php";
session_start();

$message = "";

if(isset($_POST['register'])){
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM admins WHERE email='$email'");
    if($check->num_rows > 0){
        $message = "Email already exists!";
    } else {
        $conn->query("INSERT INTO admins (name,email,password) VALUES ('$name','$email','$password')");
        $message = "Admin registered successfully! You can now login.";
    }
}

if(isset($_POST['login'])){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM admins WHERE email='$email'");
    $admin = $result->fetch_assoc();

    if($admin && password_verify($password, $admin['password'])){
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
    <link rel="icon" href="../assets/favicon.png" type="image/png">

    <title>Computer Shop</title>
    <title>Admin Login / Register</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #0d0d0d; /* black */
            color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: linear-gradient(135deg, #1a1a1a, #000000);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 0px 20px rgba(255,0,0,0.7);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #ff0000;
            text-shadow: 0 0 8px #ff0000;
        }
        h3 {
            color: #ff4d4d;
            margin-bottom: 10px;
        }
        input[type=text],
        input[type=email],
        input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            border: none;
            border-radius: 8px;
            background: #262626;
            color: #fff;
        }
        input[type=text]::placeholder,
        input[type=email]::placeholder,
        input[type=password]::placeholder {
            color: #aaa;
        }
        input[type=submit] {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: none;
            border-radius: 8px;
            background: #ff0000;
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        input[type=submit]:hover {
            background: #b30000;
            box-shadow: 0px 0px 12px #ff0000;
            transform: scale(1.05);
        }
        .message, .error {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .message { color: #00ff99; }
        .error { color: #ff4d4d; }
        hr {
            border: 1px solid #333;
            margin: 20px 0;
        }
        .back-btn {
    display: block;
    text-align: center;
    margin-top: 15px;
    padding: 10px;
    border-radius: 8px;
    background: #262626;
    color: #ff0000;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}
.back-btn:hover {
    background: #ff0000;
    color: #fff;
    box-shadow: 0px 0px 10px #ff0000;
    transform: scale(1.05);
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>

        <?php if($message) echo "<p class='error'>$message</p>"; ?>

        <form method="POST">
            <h3>Login</h3>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login">
        </form>

        <hr>

        <form method="POST">
            <h3>Register</h3>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="register" value="Register">
        </form>
        <a href="index.php" class="back-btn">⬅ Back to Home</a>

    </div>
</body>
</html>
