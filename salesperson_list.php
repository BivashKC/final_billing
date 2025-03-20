<?php
session_start();
include('db_conn.php'); // Include database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_username']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Initialize feedback messages
$feedback = "";

// Handle delete request securely using prepared statements
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND user_role = 'user'");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $feedback = "Salesperson deleted successfully.";
    } else {
        $feedback = "Error deleting salesperson: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch salespersons from the database using prepared statements
$salespersons = [];
$stmt = $conn->prepare("SELECT user_id, user_username, created_at FROM users WHERE user_role = 'user'");
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $salespersons[] = $row;
    }
} else {
    $feedback = "Error fetching salespersons: " . $conn->error;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salesperson List - Offline Shop Billing System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-family: 'Arial', sans-serif; /* Font family */
        }
        .container {
            max-width: 900px; /* Set max width for the salesperson list */
            margin-top: 50px; /* Center the container vertically */
            padding: 30px; /* Add padding */
            border-radius: 8px; /* Rounded corners */
            background-color: #ffffff; /* White background for the list */
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
        <h2>Salesperson List</h2>

        <?php if (!empty($feedback)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($feedback); ?></div>
        <?php endif; ?>

        <a href="add_salesperson.php" class="btn btn-success mb-3">Add Salesperson</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($salespersons)): ?>
                    <?php foreach ($salespersons as $salesperson): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($salesperson['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($salesperson['user_username']); ?></td>
                            <td><?php echo htmlspecialchars($salesperson['created_at']); ?></td>
                            <td>
                                <a href="edit_salesperson.php?id=<?php echo htmlspecialchars($salesperson['user_id']); ?>" class="btn btn-warning">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No salespersons found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
