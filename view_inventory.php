<?php
session_start();
include('db_conn.php');

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php"); // Redirect to index if not admin
    exit();
}

// Fetch all products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result) {
    die("Error fetching products: " . mysqli_error($conn));
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory - Shop Billing System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-family: 'Arial', sans-serif; /* Font family */
        }
        .container {
            max-width: 800px; /* Set max width for the inventory view */
            margin-top: 50px; /* Center the container vertically */
            padding: 30px; /* Add padding */
            border-radius: 8px; /* Rounded corners */
            background-color: #ffffff; /* White background for the inventory view */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        h2 {
            text-align: center; /* Centered title */
            color: #343a40; /* Darker title color */
            margin-bottom: 20px; /* Space below title */
        }
        table {
            margin-bottom: 30px; /* Space below table */
        }
        th {
            background-color: #007bff; /* Primary color for headers */
            color: white; /* White text for header */
        }
        .btn {
            margin-right: 5px; /* Space between buttons */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inventory List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($product['product_price'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($product['product_stock']); ?></td>
                        <td>
                            <a href="update_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="add_product.php" class="btn btn-success">Add Product</a>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
