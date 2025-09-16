<?php
require_once "../src/auth.php";
require_once "../src/db.php"; 

// Fetch counts from the database
$productCount = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$userCount    = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$orderCount   = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];

// Calculate total sales income from order_items
$salesIncomeResult = $conn->query("SELECT SUM(quantity * price) as total_sales FROM order_items")->fetch_assoc();
$salesIncome = $salesIncomeResult['total_sales'] ? $salesIncomeResult['total_sales'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/favicon.png" type="image/png">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #0d0d0d;
            color: #f2f2f2;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #ff0000;
            text-shadow: 0 0 8px #ff0000;
        }
        p {
            text-align: center;
            color: #aaa;
        }
        .dashboard-cards {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .card {
            background: linear-gradient(145deg, #1a1a1a, #000000);
            padding: 20px;
            width: 220px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(255,0,0,0.6);
            margin: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 0px 25px rgba(255,0,0,0.9);
        }
        .card h2 {
            margin-bottom: 10px;
            color: #ff4d4d;
        }
        .card p {
            font-size: 32px;
            margin-bottom: 15px;
            color: #fff;
            font-weight: bold;
        }
        .card a {
            text-decoration: none;
            color: #fff;
            background: #ff0000;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        .card a:hover {
            background: #b30000;
            box-shadow: 0px 0px 12px #ff0000;
        }
        .logout {
            text-align: center;
            margin-top: 40px;
        }
        .logout a {
            text-decoration: none;
            color: #fff;
            background: #ff0000;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        .logout a:hover {
            background: #b30000;
            box-shadow: 0px 0px 12px #ff0000;
        }
    </style>
</head>
<body>
    <h1>Welcome Admin!</h1>
    <p>You are logged in successfully.</p>

    <div class="dashboard-cards">
        <div class="card">
            <h2>Products</h2>
            <p><?php echo $productCount; ?></p>
            <a href="products.php">Manage Products</a>
        </div>
        <div class="card">
            <h2>Users</h2>
            <p><?php echo $userCount; ?></p>
            <a href="users.php">Manage Users</a>
        </div>
        <div class="card">
            <h2>Orders</h2>
            <p><?php echo $orderCount; ?></p>
            <a href="orders.php">Manage Orders</a>
        </div>
        <div class="card">
            <h2>Sales Income</h2>
            <p>Rs. <?php echo number_format($salesIncome, 2); ?></p>
            <a href="orders.php">View Orders</a>
        </div>
    </div>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
