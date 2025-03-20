<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_username']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Shop Billing System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('shop.png');
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 30px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h1, h3 {
            text-align: center;
            color: #343a40;
        }
        .greeting {
            text-align: center;
            color: #495057;
            margin-bottom: 20px;
        }
        .btn {
            width: 100%;
            margin-bottom: 15px;
        }
        @media (max-width: 768px) {
            .container {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Shop Billing System</h1>
        <h3>Admin Dashboard</h3>

        <p class="greeting">Hello, <?php echo htmlspecialchars($_SESSION['user_username']); ?>! Here you can manage your inventory and oversee salesperson activities.</p>

        <a href="add_product.php" class="btn btn-success">Add Product</a>
        <a href="view_inventory.php" class="btn btn-primary">View Inventory</a>
        <a href="salesperson_list.php" class="btn btn-info">Salesperson List</a>
        <a href="view_sales_admin.php" class="btn btn-warning">View Sales</a>
        <a href="view_customers.php" class="btn btn-info">View Customers</a>
        <a href="view_bills_admin.php" class="btn btn-primary">View Bills</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
