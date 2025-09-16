<?php
require_once "../src/auth.php";
require_once "../src/db.php";

// Delete user
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: users.php");
    exit;
}

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$query = "SELECT * FROM users";
if($search){
    $query .= " WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR mobile LIKE '%$search%' OR address LIKE '%$search%'";
}
$query .= " ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="../assets/favicon.png" type="image/png">
    <title>Manage Users</title>
    <style>
        body { font-family: Arial; background: #121212; color: #fff; margin: 0; padding: 20px; }
        h1 { text-align: center; color: #ff0000; margin-bottom: 20px; }
        a { text-decoration: none; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #262626; color: #ff0000; border-radius: 8px; font-weight: bold; transition:0.3s;}
        .back-btn:hover { background:#ff0000; color:#fff; box-shadow:0 0 10px #ff0000; }
        .search-box { text-align:center; margin-bottom:20px; }
        .search-box input[type="text"] { padding:10px; width:250px; border-radius:8px; border:none; }
        .search-box input[type="submit"] { padding:10px 15px; border-radius:8px; border:none; background:#ff0000; color:#fff; cursor:pointer;}
        .search-box input[type="submit"]:hover { background:#d40000; }
        table { width:100%; border-collapse: collapse; background: #1e1e1e; border-radius:10px; overflow:hidden; margin-bottom:20px;}
        th, td { padding:12px; text-align:center; }
        th { background:#ff0000; color:#fff; }
        tr:nth-child(even) { background:#2a2a2a; }
        tr:hover { background:#333; }
        .btn { padding:6px 12px; border-radius:6px; font-weight:bold; margin:0 3px; display:inline-block; }
        .edit { background:#007BFF; color:#fff; }
        .edit:hover { background:#0056b3; }
        .delete { background:#ff0000; color:#fff; }
        .delete:hover { background:#b30000; }
        .orders-btn { background:#28a745; color:#fff; }
        .orders-btn:hover { background:#1e7e34; }

        /* Orders table inside user row */
        .user-orders { display:none; margin-top:10px; background:#262626; border-radius:8px; }
        .user-orders table { width:90%; margin:auto; }
        .user-orders th { background:#007BFF; }
        .toggle-btn { cursor:pointer; padding:5px 10px; background:#00bfff; color:#000; border-radius:5px; font-weight:bold; }
        .toggle-btn:hover { background:#0095d1; color:#fff; }
        .status-pending { color:#ff4d4d; font-weight:bold; }
        .status-processing { color:#ffcc00; font-weight:bold; }
        .status-completed { color:#28a745; font-weight:bold; }
    </style>
    <script>
        function toggleOrders(id){
            const ordersDiv = document.getElementById('orders-' + id);
            if(ordersDiv.style.display === 'block') ordersDiv.style.display = 'none';
            else ordersDiv.style.display = 'block';
        }
    </script>
</head>
<body>
<h1>Manage Users</h1>
<div style="text-align:center; margin-bottom:15px;">
    <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Search by name, email, mobile, or address" value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Created At</th>
        <th>Orders</th>
        <th>Action</th>
    </tr>
    <?php while($user = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo htmlspecialchars($user['name']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td><?php echo htmlspecialchars($user['mobile']); ?></td>
        <td><?php echo htmlspecialchars($user['address']); ?></td>
        <td><?php echo $user['created_at']; ?></td>
        <td>
            <span class="toggle-btn" onclick="toggleOrders(<?php echo $user['id']; ?>)">View Orders</span>
            <div class="user-orders" id="orders-<?php echo $user['id']; ?>">
                <?php
                $orders = $conn->query("SELECT * FROM orders WHERE user_id=".$user['id']." ORDER BY created_at DESC");
                if($orders->num_rows > 0){
                    echo '<table><tr><th>Order ID</th><th>Total</th><th>Status</th><th>Created At</th></tr>';
                    while($o = $orders->fetch_assoc()){
                        $status_class = '';
                        if($o['status']=='Pending') $status_class='status-pending';
                        elseif($o['status']=='Processing') $status_class='status-processing';
                        elseif($o['status']=='Completed') $status_class='status-completed';
                        echo '<tr>';
                        echo '<td>'.$o['id'].'</td>';
                        echo '<td>Rs.'.number_format($o['total'],2).'</td>';
                        echo '<td class="'.$status_class.'">'.$o['status'].'</td>';
                        echo '<td>'.$o['created_at'].'</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>No orders.</p>';
                }
                ?>
            </div>
        </td>
        <td>
            <a class="btn edit" href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
            <a class="btn delete" href="users.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Delete user?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
