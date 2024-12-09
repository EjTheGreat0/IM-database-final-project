<?php
// Create a connection to the MySQL database
$conn = new mysqli('127.0.0.1', 'root', '', 'inventory_management'); // Use your database credentials

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
