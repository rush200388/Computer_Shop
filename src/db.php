<?php
$host = "localhost";
$user = "root";   
$pass = "Rush@2003";       
$db   = "computer_store";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
