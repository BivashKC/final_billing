<?php
session_start();
include('db_conn.php');

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Check if a product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_inventory.php?error=NoProductID");
    exit();
}

$product_id = intval($_GET['id']); // Sanitize the product ID

// Delete product from the database
$query = "DELETE FROM products WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: view_inventory.php?message=ProductDeletedSuccessfully");
    exit();
} else {
    $error_message = "Error deleting product: " . $stmt->error;
    $stmt->close();
    $conn->close();
    header("Location: view_inventory.php?error=" . urlencode($error_message));
    exit();
}
?>
