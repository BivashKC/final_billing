<?php
session_start();
include('db_conn.php'); // Include the database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_username'])) {
    header("Location: index.php");
    exit();
}

// Check if the delete_id parameter is present
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Delete the bill details first to maintain database integrity
    $delete_details_query = "DELETE FROM bill_details WHERE bill_id = $delete_id";
    $delete_details_result = mysqli_query($conn, $delete_details_query);

    if ($delete_details_result) {
        // Delete the bill from the bills table
        $delete_bill_query = "DELETE FROM bills WHERE bill_id = $delete_id";
        $delete_bill_result = mysqli_query($conn, $delete_bill_query);

        if ($delete_bill_result) {
            echo "<script>alert('Bill deleted successfully!'); window.location.href = 'view_bills_admin.php';</script>";
        } else {
            echo "<script>alert('Failed to delete bill: " . mysqli_error($conn) . "'); window.location.href = 'view_bills_admin.php';</script>";
        }
    } else {
        echo "<script>alert('Failed to delete bill details: " . mysqli_error($conn) . "'); window.location.href = 'view_bills_admin.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'view_bills_admin.php';</script>";
}

// Close the database connection
mysqli_close($conn);
?>
