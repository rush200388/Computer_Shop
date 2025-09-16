<?php
require_once "../src/auth.php";
require_once "../src/db.php";

$id = intval($_GET['id']);
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
$message = "";

if(isset($_POST['submit'])){
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);

    $conn->query("UPDATE users SET name='$name', email='$email', mobile='$mobile' WHERE id=$id");
    $message = "User updated successfully!";
    $user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="../assets/favicon.png" type="image/png">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0d1117;
            color: #e6edf3;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            margin-top: 30px;
            color: #1f6feb;
        }

        a {
            color: #58a6ff;
            text-decoration: none;
            margin-bottom: 20px;
        }
        a:hover {
            text-decoration: underline;
        }

        form {
            background: #161b22;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(31,111,235,0.4);
            width: 300px;
        }

        label {
            font-weight: bold;
            color: #58a6ff;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #30363d;
            border-radius: 5px;
            background: #0d1117;
            color: #e6edf3;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #1f6feb;
            box-shadow: 0 0 5px #1f6feb;
        }

        input[type="submit"] {
            background: #1f6feb;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background: #1158c7;
        }

        p {
            margin: 10px 0;
        }

        .success {
            color: #3fb950;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Edit User</h1>
    <a href="users.php">⬅ Back to Users</a>
    <?php if($message) echo "<p class='success'>$message</p>"; ?>
    <form method="POST">
        <p>
            <label>Name:</label><br>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
        </p>
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        </p>
        <p>
            <label>Phone:</label><br>
            <input type="text" name="mobile" value="<?php echo $user['mobile']; ?>" required>
        </p>
        <p>
            <input type="submit" name="submit" value="Update User">
        </p>
    </form>
</body>
</html>
