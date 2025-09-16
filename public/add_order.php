<?php
require_once "../src/auth.php";
require_once "../src/db.php";

$message = "";

// Fetch users and products for dropdowns
$users = $conn->query("SELECT id, name FROM users ORDER BY name ASC");
$products = $conn->query("SELECT id, name, price, stock FROM products ORDER BY name ASC");

// Handle form submission
if(isset($_POST['submit'])){
    $user_id = intval($_POST['user_id']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Get product price and stock
    $product = $conn->query("SELECT price, stock FROM products WHERE id=$product_id")->fetch_assoc();
    $price = $product['price'];
    $stock = $product['stock'];

    if($quantity > $stock){
        $message = "Not enough stock available!";
    } else {
        $total = $price * $quantity;
        $conn->query("INSERT INTO orders (user_id, product_id, quantity, total) VALUES ($user_id, $product_id, $quantity, $total)");
        // Reduce product stock
        $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");
        $message = "Order added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/favicon.png" type="image/png">
    <title>Add New Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0d0d0d; /* Black background */
            color: #f2f2f2;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #00bfff; /* Blue title */
            text-shadow: 0 0 8px #00bfff;
            margin-bottom: 20px;
        }
        a.back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            background: #111;
            color: #00bfff;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }
        a.back-btn:hover {
            background: #00bfff;
            color: #000;
            box-shadow: 0 0 10px #00bfff;
        }
        form {
            max-width: 500px;
            margin: auto;
            background: #1a1a1a;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,191,255,0.5);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #00bfff;
        }
        select, input[type=number], input[type=submit] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
        }
        select, input[type=number] {
            background: #262626;
            color: #fff;
        }
        input[type=submit] {
            background: #00bfff;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        input[type=submit]:hover {
            background: #0095d1;
            box-shadow: 0 0 12px #00bfff;
        }
        p.message {
            text-align: center;
            font-weight: bold;
            color: #00ffea;
        }
    </style>
</head>
<body>
    <h1>Add New Order</h1>
    <div style="text-align:center;">
        <a href="orders.php" class="back-btn">⬅ Back to Orders</a>
    </div>

    <?php if($message) echo "<p class='message'>$message</p>"; ?>

    <form method="POST">
        <p>
            <label>Select User:</label>
            <select name="user_id" required>
                <option value="">--Select User--</option>
                <?php while($user = $users->fetch_assoc()): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </p>
        <p>
            <label>Select Product:</label>
            <select name="product_id" required>
                <option value="">--Select Product--</option>
                <?php while($product = $products->fetch_assoc()): ?>
                    <option value="<?php echo $product['id']; ?>">
                        <?php echo $product['name'] . " (Price: ".$product['price'].", Stock: ".$product['stock'].")"; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </p>
        <p>
            <label>Quantity:</label>
            <input type="number" name="quantity" min="1" value="1" required>
        </p>
        <p>
            <input type="submit" name="submit" value="Add Order">
        </p>
    </form>
</body>
</html>
