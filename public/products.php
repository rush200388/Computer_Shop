<?php
require_once "../src/auth.php";
require_once "../src/db.php";

// Delete product
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: products.php");
    exit;
}

// Fetch all products including new fields
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../assets/favicon.png" type="image/png">
    <title>Manage Products</title>
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
            margin-bottom: 20px;
        }
        a.nav-link {
            display: inline-block;
            margin: 10px;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            background: #ff0000;
            color: #fff;
            font-weight: bold;
            transition: 0.3s;
        }
        a.nav-link:hover {
            background: #b30000;
            box-shadow: 0px 0px 12px #ff0000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 0px 15px rgba(255,0,0,0.5);
        }
        th, td {
            border: 1px solid #333;
            padding: 12px;
            text-align: center;
            vertical-align: top;
        }
        th {
            background: #ff0000;
            color: #fff;
            font-size: 1rem;
        }
        tr:nth-child(even) {
            background: #262626;
        }
        tr:hover {
            background: #333;
        }
        img {
            width: 80px;
            height: auto;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(255,0,0,0.5);
        }
        .action-links a {
            color: #ff4d4d;
            font-weight: bold;
            margin: 0 5px;
            transition: 0.3s;
        }
        .action-links a:hover {
            color: #fff;
            text-shadow: 0 0 8px #ff0000;
        }
        /* Highlight low stock */
        .low-stock {
            background-color: #ff4d4d;
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Manage Products</h1>

    <div style="text-align:center;">
        <a href="dashboard.php" class="nav-link">⬅ Back to Dashboard</a>
        <a href="add_product.php" class="nav-link">➕ Add New Product</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price (Rs.)</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Type</th>
            <th>Description</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        <?php while($product = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $product['id']; ?></td>
            <td><?= $product['name']; ?></td>
            <td><?= number_format($product['price'], 2); ?></td>
            <!-- Highlight stock if low -->
            <td class="<?= $product['stock'] <= 5 ? 'low-stock' : '' ?>">
                <?= $product['stock']; ?>
            </td>
            <td><?= $product['category']; ?></td>
            <td><?= $product['brand']; ?></td>
            <td><?= $product['type']; ?></td>
            <td><?= nl2br($product['description']); ?></td>
            <td>
                <?php if($product['image']) : ?>
                    <img src="../uploads/<?= $product['image']; ?>" alt="Product Image">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td><?= $product['created_at']; ?></td>
            <td class="action-links">
                <a href="edit_product.php?id=<?= $product['id']; ?>">Edit</a> |
                <a href="products.php?delete=<?= $product['id']; ?>" onclick="return confirm('Delete product?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
