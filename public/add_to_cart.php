<?php
session_start();
require_once '../src/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

if(isset($_POST['add_to_cart'])){
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);

    // Fetch product info
    $product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();
    if(!$product){
        die("Product not found.");
    }

    // Check if stock is available
    if($product['stock'] <= 0){
        die("Sorry, product out of stock!");
    }

    // Add to user's cart (assuming a cart table exists)
    $existing = $conn->query("SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id")->fetch_assoc();

    if($existing){
        // Update quantity
        $new_qty = $existing['quantity'] + 1;
        $conn->query("UPDATE cart SET quantity=$new_qty WHERE id=".$existing['id']);
    } else {
        // Insert new row
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }

    // Reduce stock by 1
    $conn->query("UPDATE products SET stock = stock - 1 WHERE id=$product_id");

    header("Location: products.php?added=1");
}
?>
