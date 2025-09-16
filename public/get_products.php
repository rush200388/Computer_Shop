<?php
require_once "../src/db.php";

header('Content-Type: application/json');

// Read GET parameters
$brand = isset($_GET['brand']) ? $conn->real_escape_string($_GET['brand']) : '';
$type  = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : '';

$sql = "SELECT * FROM products WHERE 1"; // base query

if ($brand !== '') {
    $sql .= " AND brand='$brand'";
}

if ($type !== '') {
    $sql .= " AND type='$type'"; // use "type" column
}

$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = "image.php?file=" . $row['image'];
    $products[] = $row;
}

echo json_encode($products);
$conn->close();
?>
