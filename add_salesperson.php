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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        // Check if username already exists
        $check_query = "SELECT user_id FROM users WHERE user_username = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Username already exists. Please choose another.";
        } else {
            // Insert new salesperson into the database
            $query = "INSERT INTO users (user_username, user_password, user_role) VALUES (?, ?, 'user')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header("Location: salesperson_list.php?message=SalespersonAddedSuccessfully");
                exit();
            } else {
                $error_message = "Error adding salesperson: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Salesperson</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add Salesperson</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Salesperson</button>
        </form>
        
        <a href="salesperson_list.php" class="btn btn-secondary mt-3">Back to Salesperson List</a>
    </div>
</body>
</html>
