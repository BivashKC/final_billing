<?php
session_start();
include('db_conn.php'); // Include the database connection file

// Ensure the user is logged in and has the correct role (admin or user)
if (!isset($_SESSION['user_username']) || $_SESSION['user_role'] !== 'user') {
    header("Location: index.php");
    exit();
}

// Fetch sales data (bills) with customer and salesperson information
$query = "SELECT 
            b.bill_id, 
            b.bill_number, 
            c.customer_name, 
            u.user_username AS salesperson_name, 
            b.total_amount, 
            b.discount, 
            b.final_amount
          FROM 
            bills b
          JOIN 
            customers c ON b.customer_id = c.customer_id
          JOIN 
            users u ON b.user_id = u.user_id
          ORDER BY 
            b.created_at DESC"; // You can modify the order as needed

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 30px;
            max-width: 1000px;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>View Sales</h2>

        <!-- Sales data table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Customer Name</th>
                    <th>Salesperson Name</th>
                    <th>Total Amount</th>
                    <th>Discount</th>
                    <th>Final Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['bill_id']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['salesperson_name']; ?></td>
                        <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                        <td>$<?php echo number_format($row['discount'], 2); ?></td>
                        <td>$<?php echo number_format($row['final_amount'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="salesperson_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>

    <!-- Optionally, add some JS (Bootstrap dependencies) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
