<?php
session_start();

// Check if the user is logged in and is a salesperson (role should be 'user')
if (!isset($_SESSION['user_username']) || $_SESSION['user_role'] !== 'user') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salesperson Dashboard - Shop Billing System</title>
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
        p {
            text-align: center;
            color: #495057;
            margin-bottom: 30px;
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
        <h3>Salesperson Dashboard</h3>

        <p>Hello, <?php echo htmlspecialchars($_SESSION['user_username']); ?>! You can create bills and manage customer transactions here.</p>
        
        <!-- Functional Buttons -->
        <a href="create_bill.php" class="btn btn-success">Create Bill</a>
        <a href="view_sales_salesperson.php" class="btn btn-primary">View Sales</a>

        <!-- Logout Button -->
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
