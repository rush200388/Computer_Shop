<?php
require_once "../src/auth.php";
require_once "../src/db.php";

$message = "";

if(isset($_POST['submit'])){
    $name        = $conn->real_escape_string($_POST['name']);
    $price       = $conn->real_escape_string($_POST['price']);
    $stock       = $conn->real_escape_string($_POST['stock']);
    $category    = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $brand       = $conn->real_escape_string($_POST['brand']);
    $type        = $conn->real_escape_string($_POST['item_type']); // from form

    $image = "";
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $targetDir = "../uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $image);
    }

    // Insert query using correct column name 'type'
    $conn->query("INSERT INTO products (name, price, stock, category, brand, type, description, image) 
                  VALUES ('$name','$price','$stock','$category','$brand','$type','$description','$image')");

    $message = "Product added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/favicon.png" type="image/png">
    <title>Add New Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0d0d0d; 
            color: #f2f2f2;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #00bfff; 
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
        input[type=text], input[type=number], input[type=file], select, textarea, input[type=submit] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
        }
        input[type=text], input[type=number], input[type=file], select, textarea {
            background: #262626;
            color: #fff;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
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
    <h1>Add New Product</h1>
    <div style="text-align:center;">
        <a href="products.php" class="back-btn">⬅ Back to Products</a>
    </div>

    <?php if($message) echo "<p class='message'>$message</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <p>
            <label>Name:</label>
            <input type="text" name="name" required>
        </p>
        <p>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>
        </p>
        <p>
            <label>Stock:</label>
            <input type="number" name="stock" required>
        </p>
        <p>
            <label>Category:</label>
            <select name="category" required>
                <option value="">--Select Category--</option>
                <option value="Brand New">Brand New</option>
                <option value="Used">Used</option>
                <option value="Refurbished">Refurbished</option>
            </select>
        </p>
        <p>
            <label>Brand:</label>
            <select name="brand" required>
                <option value="">--Select Brand--</option>
                <option value="MSI">MSI</option>
                <option value="Asus">Asus</option>
                <option value="Corsair">Corsair</option>
                <option value="Thermaltake">Thermaltake</option>
                <option value="Apple">Apple</option>
            </select>
        </p>
        <p>
            <label>Item Type:</label>
            <select name="item_type" required>
                <option value="">--Select Item Type--</option>
                <option value="Laptop">Laptop</option>
                <option value="Mobile">Mobile</option>\
                <option value="Accessories">Accessories</option>
                <option value="Graphics Card">Graphics Card</option>
                <option value="ComputerPart">Computer Part</option>
                <option value="Casing">Casing</option>
                
                <option value="Monitors">Monitors</option>
            </select>
        </p>
        <p>
            <label>Description:</label>
            <textarea name="description" required></textarea>
        </p>
        <p>
            <label>Image:</label>
            <input type="file" name="image">
        </p>
        <p>
            <input type="submit" name="submit" value="Add Product">
        </p>
    </form>
</body>
</html>
