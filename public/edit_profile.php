<?php
session_start();
require_once '../src/db.php';

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch current user details
$result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

if(isset($_POST['update'])){
    $name = $conn->real_escape_string($_POST['name']);
    $address = $conn->real_escape_string($_POST['address']);
    $mobile = $conn->real_escape_string($_POST['mobile']);

    // Update basic info
    $conn->query("UPDATE users SET name='$name', address='$address', mobile='$mobile' WHERE id='$user_id'");

    // Check if user wants to change password
    if(!empty($_POST['current_password']) && !empty($_POST['new_password'])){
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        if(password_verify($current_password, $user['password'])){
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$hashed' WHERE id='$user_id'");
            $message = "Profile and password updated successfully!";
        } else {
            $message = "Current password is incorrect!";
        }
    } else {
        if(!$message) $message = "Profile updated successfully!";
    }

    // Refresh user data
    $result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
    $user = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profile - Grizmo.lk</title>
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
    width: 400px;
    box-shadow: 0 0 15px rgba(0,191,255,0.5);
}
h2 {
    text-align: center;
    color: #00bfff;
    margin-bottom: 20px;
}
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #00bfff;
    background: #0d0d0d;
    color: #fff;
}
input::placeholder {
    color: #bbb;
}
button, .forgot-btn, .dashboard-btn {
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
.forgot-btn {
    background: #333;
    color: #00bfff;
    border: 1px solid #00bfff;
}
.forgot-btn:hover {
    background: #00bfff;
    color: #fff;
}
.dashboard-btn {
    background: #222;
    color: #00bfff;
    border: 1px solid #00bfff;
}
.dashboard-btn:hover {
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
    <h2>Edit Profile</h2>
    <form method="POST">
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" placeholder="Name" required>
        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" placeholder="Address" required>
        <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile']) ?>" placeholder="Mobile" required>

        <hr style="border-color:#00bfff; margin:15px 0;">

        <input type="password" name="current_password" placeholder="Current Password">
        <input type="password" name="new_password" placeholder="New Password">
        <p style="font-size:0.9em; color:#bbb; margin:5px 0;">If you forgot your password, <a href="forgot_password.php" style="color:#00bfff;">click here</a></p>

        <button type="submit" name="update">Update Profile</button>
    </form>

    <a href="customer_dashboard.php" class="dashboard-btn">Back to Dashboard</a>

    <?php if($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
</div>
</body>
</html>
