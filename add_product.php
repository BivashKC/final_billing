<?php
session_start();
include('db_conn.php');

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php"); // Redirect to index if not admin
    exit();
}

// Initialize variables
$product_name = "";
$product_price = "";
$product_quantity = "";
$error_message = "";
$success_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);
    $product_price = trim($_POST['product_price']);
    $product_quantity = trim($_POST['product_quantity']);

    // Basic validation
    if (empty($product_name) || empty($product_price) || empty($product_quantity)) {
        $error_message = "All fields are required.";
    } elseif (!is_numeric($product_price) || !is_numeric($product_quantity)) {
        $error_message = "Price and quantity must be numeric values.";
    } else {
        // Escape inputs to prevent SQL injection
        $product_name = mysqli_real_escape_string($conn, $product_name);
        $product_price = mysqli_real_escape_string($conn, $product_price);
        $product_quantity = mysqli_real_escape_string($conn, $product_quantity);

        // Insert product into the database
        $query = "INSERT INTO products (product_name, product_price, product_stock) VALUES ('$product_name', '$product_price', '$product_quantity')";
        if (mysqli_query($conn, $query)) {
            $success_message = "Product added successfully.";
            $product_name = $product_price = $product_quantity = ""; // Clear inputs
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add New Product</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="product_price">Price:</label>
                <input type="number" step="0.01" name="product_price" min="1" class="form-control" value="<?php echo htmlspecialchars($product_price); ?>" required>
            </div>
            <div class="form-group">
                <label for="product_quantity">Quantity:</label>
                <input type="number" name="product_quantity" class="form-control" min="1" value="<?php echo htmlspecialchars($product_quantity); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>

        <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
