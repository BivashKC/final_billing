<?php
session_start();
include('db_conn.php'); // Include the database connection file

// Ensure the user is logged in and has the correct role (admin or user)
if (!isset($_SESSION['user_username'])) {
    header("Location: index.php");
    exit();
}

// Fetch all bills from the database
$query = "SELECT bill_id, bill_number FROM bills ORDER BY created_at DESC"; // You can adjust the query as per your need
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
    <title>View Bills</title>
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
        .view-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
        }
        .view-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>View Bills</h2>

        <!-- Bills data table -->
        <table class="table table-bordered">
    <thead>
        <tr>
            <th>Bill ID</th>
            <th>View Bill</th>
            <th>Delete Bills</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['bill_id']; ?></td>
                <td>
                    <!-- Button styled as a link -->
                    <a href="http://localhost/project_confirmed/view_bill.php?bill_id=<?php echo $row['bill_id']; ?>" class="btn btn-primary">
                        View Bill
                    </a>
                </td>
                <td>
                    <a href="delete_bill.php?delete_id=<?php echo $row['bill_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this bill?');">
                        Delete Bill
                    </a>
                </td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>

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
