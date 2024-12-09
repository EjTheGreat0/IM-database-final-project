<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to MySQL Database
$conn = new mysqli('localhost', 'root', '', 'inventory_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = '';

// Check if item_id is passed
if (isset($_GET['item_id'])) {
    $item_id = (int)$_GET['item_id'];

    // Fetch the item's current details
    $sql = "SELECT * FROM items WHERE item_id = $item_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    } else {
        $message = "Item not found!";
    }
}

// Handle update logic
if (isset($_POST['update_item'])) {
    $item_code = $conn->real_escape_string($_POST['item_code']);
    $description = $conn->real_escape_string($_POST['description']);
    $vendor_id = (int)$_POST['vendor_id'];
    $location = $conn->real_escape_string($_POST['location']);
    $stock_quantity = (int)$_POST['stock_quantity'];
    $price_per_unit = (float)$_POST['price_per_unit'];
    $total_value = $stock_quantity * $price_per_unit;

    // Update the item in the database
    $sql = "UPDATE items 
            SET item_code = '$item_code', 
                description = '$description',
                vendor_id = $vendor_id,
                location = '$location',
                stock_quantity = $stock_quantity,
                price_per_unit = $price_per_unit,
                total_value = $total_value
            WHERE item_id = $item_id";

    if ($conn->query($sql) === TRUE) {
        $message = "Item updated successfully!";
        // Optionally, redirect back to the dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Error updating item: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Item</h2>
        <div class="message"><?php echo $message; ?></div>

        <?php if (isset($item)) { ?>
        <form action="edit_item.php?item_id=<?php echo $item_id; ?>" method="POST">
            Item Code: <input type="text" name="item_code" value="<?php echo $item['item_code']; ?>" required><br>
            Description: <input type="text" name="description" value="<?php echo $item['description']; ?>" required><br>
            Vendor ID: <input type="number" name="vendor_id" value="<?php echo $item['vendor_id']; ?>" required><br>
            Location: <input type="text" name="location" value="<?php echo $item['location']; ?>" required><br>
            Stock Quantity: <input type="number" name="stock_quantity" value="<?php echo $item['stock_quantity']; ?>" required><br>
            Price per Unit: <input type="number" step="0.01" name="price_per_unit" value="<?php echo $item['price_per_unit']; ?>" required><br>
            Total Value: <input type="number" step="0.01" name="total_value" value="<?php echo $item['total_value']; ?>" readonly><br>
            <input type="submit" name="update_item" value="Update Item">
        </form>
        <?php } else { ?>
        <p>Item not found or invalid ID.</p>
        <?php } ?>
        <a href="dashboard.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
