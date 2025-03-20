<?php
session_start();
include('db_conn.php'); // Include database connection

$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password are required.";
    } else {
        // Query to check user credentials using prepared statements
        $query = "SELECT * FROM users WHERE user_username = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['user_password'])) {
                // Regenerate session ID for security
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_username'] = $user['user_username'];
                $_SESSION['user_role'] = $user['user_role'];

                // Redirect based on role
                if ($user['user_role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($user['user_role'] === 'user') {
                    header("Location: salesperson_dashboard.php");
                } else {
                    $error_message = "Invalid user role.";
                }
                exit();
            } else {
                $error_message = "Invalid Username or Password.";
            }
        } else {
            $error_message = "Invalid Username or Password.";
        }

        $stmt->close();
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Billing System - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('shop.png');
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 400px;
            padding: 20px;
            border-radius: 8px;
            background-color: #d9e0de;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #c4ccd4;
            background-color: #50545e;
            border-radius: 5%;
            padding: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Welcome to Shop Billing System</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
</body>

</html>
