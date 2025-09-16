<?php
require_once "../src/auth.php";
require_once "../src/db.php";

$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
$message = "";

if(isset($_POST['submit'])){
    $name  = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $stock = $conn->real_escape_string($_POST['stock']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $brand = $conn->real_escape_string($_POST['brand']);
    $type = $conn->real_escape_string($_POST['item_type']);

    // Handle image upload
    $image = $product['image']; // keep old image if none uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $targetDir = "../uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $image);
    }

    $conn->query("UPDATE products SET name='$name', price='$price', stock='$stock', category='$category', brand='$brand', image='$image', type='$type' WHERE id=$id");
    $message = "Product updated successfully!";
    $product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc(); // refresh
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="icon" href="../assets/favicon.png" type="image/png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0d0d0d;
            color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #111;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,191,255,0.5);
        }
        h1 {
            text-align: center;
            color: #00bfff;
            text-shadow: 0 0 10px #00bfff;
            margin-bottom: 25px;
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
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #00bfff;
        }
        input[type=text], input[type=number], input[type=file], select, textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            background: #262626;
            color: #fff;
            font-size: 1rem;
            width: 100%;
        }
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        input[type=submit] {
            background: #00bfff;
            color: #000;
            font-weight: bold;
            padding: 12px;
            border: none;
            border-radius: 8px;
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
            margin-bottom: 20px;
        }
        img.preview {
            display: block;
            margin-bottom: 15px;
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,191,255,0.5);
        }
        /* Fix for select dropdowns to match inputs */
        select {
            background: #262626;
            color: #fff;
            border-radius: 8px;
            padding: 10px;
            font-size: 1rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        <a href="products.php" class="back-btn">⬅ Back to Products</a>

        <?php if($message) echo "<p class='message'>$message</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $product['name']; ?>" required>

            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>

            <label>Stock:</label>
            <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

            <label>Category:</label>
            <input type="text" name="category" value="<?php echo $product['category']; ?>">

            <label>Description:</label>
            <textarea name="description"><?php echo $product['description']; ?></textarea>

            <label>Brand:</label>
            <select name="brand" required>
                <option value="">--Select Brand--</option>
                <option value="MSI" <?php if($product['brand']=='MSI') echo 'selected'; ?>>MSI</option>
                <option value="Asus" <?php if($product['brand']=='Asus') echo 'selected'; ?>>Asus</option>
                <option value="Corsair" <?php if($product['brand']=='Corsair') echo 'selected'; ?>>Corsair</option>
                <option value="Thermaltake" <?php if($product['brand']=='Thermaltake') echo 'selected'; ?>>Thermaltake</option>
                <option value="Apple" <?php if($product['brand']=='Apple') echo 'selected'; ?>>Apple</option>
            </select>

            <label>Item Type:</label>
            <select name="item_type" required>
                <option value="">--Select Item Type--</option>
                <?php
                $types = ["Laptop","Mobile","Casing","Accessories","Monitors","Graphics Card","Computer Part"];
                foreach($types as $t){
                    $selected = ($product['type'] == $t) ? 'selected' : '';
                    echo "<option value='$t' $selected>$t</option>";
                }
                ?>
            </select>

            <label>Image:</label>
            <?php if($product['image']) echo "<img src='../uploads/".$product['image']."' class='preview'>"; ?>
            <input type="file" name="image">

            <input type="submit" name="submit" value="Update Product">
        </form>
    </div>
</body>
</html>
