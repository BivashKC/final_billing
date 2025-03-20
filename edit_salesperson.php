<?php
session_start();
include('db_conn.php'); // Include database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_username']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Initialize error message
$error_message = "";

// Fetch salesperson details if the ID is set
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT user_username FROM users WHERE user_id = '$id' AND user_role = 'user'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $salesperson = mysqli_fetch_assoc($result);
    } else {
        // Redirect if the salesperson does not exist
        header("Location: salesperson_list.php");
        exit();
    }
} else {
    // Redirect if no ID is provided
    header("Location: salesperson_list.php");
    exit();
}

// Handle form submission for updating salesperson
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $user_username = mysqli_real_escape_string($conn, trim($_POST['user_username']));
    $user_password = !empty(trim($_POST['user_password'])) 
        ? password_hash(mysqli_real_escape_string($conn, trim($_POST['user_password'])), PASSWORD_DEFAULT) 
        : null;

    // Validate input
    if (empty($user_username)) {
        $error_message = "Username cannot be empty.";
    } elseif (strlen($user_username) < 3) {
        $error_message = "Username must be at least 3 characters long.";
    } else {
        // Update the salesperson in the database
        if ($user_password) {
            // Update both username and password
            $update_query = "UPDATE users 
                             SET user_username = '$user_username', user_password = '$user_password' 
                             WHERE user_id = '$id' AND user_role = 'user'";
        } else {
            // Update only the username
            $update_query = "UPDATE users 
                             SET user_username = '$user_username' 
                             WHERE user_id = '$id' AND user_role = 'user'";
        }

        if (mysqli_query($conn, $update_query)) {
            header("Location: salesperson_list.php"); // Redirect to salesperson list after editing
            exit();
        } else {
            $error_message = "Error updating salesperson: " . mysqli_error($conn);
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Salesperson</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Salesperson</h2>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <div class="form-group">
                <label for="user_username">Username:</label>
                <input type="text" name="user_username" class="form-control" value="<?php echo htmlspecialchars($salesperson['user_username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="user_password">Password (leave blank to keep current password):</label>
                <input type="password" name="user_password" class="form-control" placeholder="Enter a new password">
            </div>
            <button type="submit" class="btn btn-primary">Update Salesperson</button>
        </form>

        <a href="salesperson_list.php" class="btn btn-secondary mt-3">Back to Salesperson List</a>
    </div>
</body>
</html>
