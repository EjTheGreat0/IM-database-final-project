<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'inventory_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = '';
$error = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_code = trim($_POST['item_code']);
    $description = trim($_POST['description']);
    $vendor_id = (int)$_POST['vendor_id'];
    $location = trim($_POST['location']);
    $stock_quantity = (int)$_POST['stock_quantity'];
    $price_per_unit = (float)$_POST['price_per_unit'];

    if ($item_code && $description && $vendor_id && $location && $stock_quantity >= 0 && $price_per_unit >= 0) {
        $total_value = $stock_quantity * $price_per_unit;

        $stmt = $conn->prepare("INSERT INTO items (item_code, description, vendor_id, location, stock_quantity, price_per_unit, total_value) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisdid", $item_code, $description, $vendor_id, $location, $stock_quantity, $price_per_unit, $total_value);

        if ($stmt->execute()) {
            // Redirect to the dashboard after successful insertion
            header("Location: dashboard.php");
            exit();
        } else {
            $error = true;
            $message = "Error adding item: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = true;
        $message = "All fields are required and must have valid values.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Item</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid transparent;
            border-radius: 5px;
        }
        .message.success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .message.error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 14px;
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group select {
            background-color: #f9f9f9;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            text-decoration: none;
            color: #007bff;
            font-size: 14px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Item</h1>
        <?php if ($message): ?>
            <div class="message <?php echo $error ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="item_code">Item Code</label>
                <input type="text" name="item_code" id="item_code" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description" id="description" required>
            </div>

            <div class="form-group">
                <label for="vendor_id">Vendor ID</label>
                <input type="number" name="vendor_id" id="vendor_id" required>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" required>
            </div>

            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="number" name="stock_quantity" id="stock_quantity" min="0" required>
            </div>

            <div class="form-group">
                <label for="price_per_unit">Price Per Unit</label>
                <input type="number" step="0.01" name="price_per_unit" id="price_per_unit" min="0" required>
            </div>

            <button type="submit">Add Item</button>
        </form>
        <div class="back-link">
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
