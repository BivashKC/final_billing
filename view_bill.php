<?php
session_start();
include('db_conn.php'); // Include database connection

// Ensure the user is logged in and has the correct role (admin or user)
if (!isset($_SESSION['user_username']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'user')) {
    header("Location: index.php"); // Redirect to login if not logged in or authorized
    exit();
}

$bill_id = isset($_GET['bill_id']) ? (int)$_GET['bill_id'] : 0;
$bill = null;
$bill_details = [];

// Fetch bill data based on bill_id
if ($bill_id > 0) {
    $query = "SELECT b.bill_id, c.customer_name, c.customer_phone, u.user_username, 
                     b.total_amount, b.discount, b.final_amount, b.created_at
              FROM bills b
              JOIN customers c ON b.customer_id = c.customer_id
              JOIN users u ON b.user_id = u.user_id
              WHERE b.bill_id = $bill_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $bill = mysqli_fetch_assoc($result);

        // Fetch bill details (product details)
        $query_details = "SELECT p.product_name, bd.product_quantity, bd.product_price, bd.product_total
                          FROM bill_details bd
                          JOIN products p ON bd.product_id = p.product_id
                          WHERE bd.bill_id = $bill_id";
        $result_details = mysqli_query($conn, $query_details);

        if ($result_details && mysqli_num_rows($result_details) > 0) {
            while ($row = mysqli_fetch_assoc($result_details)) {
                $bill_details[] = $row;
            }
        }
    }
}

if (!$bill) {
    echo "Bill not found.";
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bill - <?php echo $bill['bill_number']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            background-image: url('shop.png');
        }
        .container {
            margin-top: 30px;
            max-width: 900px;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .bill-header, .bill-details, .bill-summary {
            margin-bottom: 30px;
        }
        .bill-summary .total {
            font-weight: bold;
        }
        .bill-footer {
            margin-top: 20px;
            text-align: right;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bill #<?php echo $bill['bill_id']; ?></h2>

        <div class="bill-header">
            <p><strong>Customer Name:</strong> <?php echo $bill['customer_name']; ?></p>
            <p><strong>Customer Phone:</strong> <?php echo $bill['customer_phone']; ?></p>
            <p><strong>Salesperson:</strong> <?php echo $bill['user_username']; ?></p>
            <p><strong>Bill Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($bill['created_at'])); ?></p>
        </div>

        <div class="bill-details">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bill_details as $detail): ?>
                        <tr>
                            <td><?php echo $detail['product_name']; ?></td>
                            <td><?php echo $detail['product_quantity']; ?></td>
                            <td>$<?php echo number_format($detail['product_price'], 2); ?></td>
                            <td>$<?php echo number_format($detail['product_total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="bill-summary">
            <p><strong>Total Amount:</strong> $<?php echo number_format($bill['total_amount'], 2); ?></p>
            <p><strong>Discount:</strong> $<?php echo number_format($bill['discount'], 2); ?></p>
            <p><strong>Final Amount:</strong> $<?php echo number_format($bill['final_amount'], 2); ?></p>
        </div>

        <div class="bill-footer">
            <a href="javascript:window.print();" class="btn btn-secondary">Print Bill</a>
        </div>
    </div>
</body>

</html>
