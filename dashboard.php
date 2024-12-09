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

// Search Functionality
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $conn->real_escape_string($_POST['search']);
}

// Handle item deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $sql = "DELETE FROM items WHERE item_id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        $message = "Item deleted successfully.";
    } else {
        $message = "Error deleting item: " . $conn->error;
    }
}

// Pagination Logic
$items_per_page = 10; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Build the search query and pagination
$sql = "SELECT * FROM items WHERE description LIKE '%$search_query%' LIMIT $items_per_page OFFSET $offset";
$result = $conn->query($sql);

// Fetch total number of items for pagination
$sql_total = "SELECT COUNT(*) AS total FROM items WHERE description LIKE '%$search_query%'";
$total_result = $conn->query($sql_total);
$total_items = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Minimalist logout button styles */
        .logout-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
        
        .search-bar {
        position: absolute;
        top: 50px;
        right: 50px;
        display: flex;
        align-items: center;
        }


        .pagination {
            display: inline-block;
            margin-top: 20px;
        }
        .pagination a {
            color: #007bff;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #007bff;
            margin: 0 4px;
        }
        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }

        /* Add New Item button styling */
        .add-new-item-btn {
            position: absolute;
            bottom: 20px;
            right: 100px; /* Adjust position to be beside the pagination */
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-new-item-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logout Button -->
        <a href="logout.php" class="logout-btn">Logout</a>

        <h1>Dashboard</h1>

        <!-- Search Bar -->
        <form method="POST" class="search-bar">
            <input type="text" name="search" placeholder="Search items..." value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="submit" value="Search">
        </form>

        <?php if (isset($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>

        <a href="add_item.php" class="btn">Add New Item</a>

        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Code</th>
                    <th>Description</th>
                    <th>Vendor ID</th>
                    <th>Location</th>
                    <th>Stock Quantity</th>
                    <th>Price per Unit</th>
                    <th>Total Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['item_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['item_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo $row['vendor_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo $row['stock_quantity']; ?></td>
                            <td><?php echo number_format($row['price_per_unit'], 2); ?></td>
                            <td><?php echo number_format($row['total_value'], 2); ?></td>
                            <td>
                                <a href="edit_item.php?item_id=<?php echo $row['item_id']; ?>" class="btn">Edit</a>
                                <a href="dashboard.php?delete_id=<?php echo $row['item_id']; ?>" class="btn delete" 
                                   onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="9">No items found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <a href="dashboard.php?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search_query); ?>"><?php echo $i; ?></a>
            <?php } ?>
        </div>
    </div>

</body>
</html>
