<?php
// Include the database connection file
require_once 'db_conn.php';

// Start session to check admin login
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Access denied.");
}

// Check if the user_id parameter is set
if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);

    // Validate the user_id to ensure it exists and belongs to a salesperson
    $query = "SELECT user_role FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['user_role'] !== 'user') {
            die("You can only delete salespersons.");
        }

        // Delete related bills first
        $deleteBillsQuery = "DELETE FROM bills WHERE user_id = ?";
        $deleteBillsStmt = $conn->prepare($deleteBillsQuery);
        $deleteBillsStmt->bind_param('i', $userId);
        $deleteBillsStmt->execute();

        // Now delete the user
        $deleteQuery = "DELETE FROM users WHERE user_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $userId);

        if ($deleteStmt->execute()) {
            // Redirect to the admin dashboard with a success message
            header("Location: admin_dashboard.php?message=Salesperson+deleted+successfully");
            exit();
        } else {
            echo "Error deleting salesperson.";
        }
    } else {
        echo "Salesperson not found.";
    }
} else {
    echo "Invalid request.";
}
?>
