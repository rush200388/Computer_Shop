<?php
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
    <link rel="icon" href="../assets/favicon.png" type="image/png">
    <link rel="shortcut icon" href="../assets/favicon.png" type="image/png">
    <title>Computer Shop</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #0d0d0d; /* Black background */
            color: #f2f2f2; /* Light text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            background: linear-gradient(135deg, #1a1a1a, #000000);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 0px 20px rgba(255, 0, 0, 0.7);
        }
        h1 {
            color: #ff0000;
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-shadow: 0 0 10px #ff0000;
        }
        a {
            display: inline-block;
            text-decoration: none;
            background: #ff0000;
            color: #fff;
            padding: 12px 25px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }
        a:hover {
            background: #b30000;
            box-shadow: 0px 0px 15px #ff0000;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to GizmoLK</h1>
        <a href="admin_auth.php">Go to Admin Panel</a>
    </div>
</body>
</html>
';
?>
