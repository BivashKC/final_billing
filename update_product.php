<?php
session_start();
include('db_conn.php');

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Initialize variables
$product_name = "";
$product_price = "";
$product_stock = "";
$error_message = "";
$success_message = "";

// Check if a product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_inventory.php");
    exit();
}

$product_id = intval($_GET['id']); // Sanitize input

// Fetch product details
$query = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $product_name = $product['product_name'];
    $product_price = $product['product_price'];
    $product_stock = $product['product_stock'];
} else {
    $error_message = "Product not found.";
    $stmt->close();
    $conn->close();
    header("Location: view_inventory.php?error=ProductNotFound");
    exit();
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_stock = $_POST['product_stock'];

    // Basic validation
    if (empty($product_name) || empty($product_price) || empty($product_stock)) {
        $error_message = "All fields are required.";
    } else {
        // Update product in the database
        $query = "UPDATE products SET 
                  product_name = ?, 
                  product_price = ?, 
                  product_stock = ? 
                  WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdii", $product_name, $product_price, $product_stock, $product_id);

        if ($stmt->execute()) {
            $success_message = "Product updated successfully.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="product_price">Price:</label>
                <input type="number" step="0.01" min="1" name="product_price" class="form-control" value="<?php echo htmlspecialchars($product_price); ?>" required>
            </div>
            <div class="form-group">
                <label for="product_stock">Stock:</label>
                <input type="number" name="product_stock" min="1" class="form-control" value="<?php echo htmlspecialchars($product_stock); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>

        <a href="view_inventory.php" class="btn btn-secondary mt-3">Back to Inventory</a>
    </div>
</body>
</html>
