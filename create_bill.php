<?php
session_start();
include('db_conn.php'); // Include database connection

// Ensure the user is logged in and has the correct role
if (!isset($_SESSION['user_username']) || $_SESSION['user_role'] !== 'user') {
    header("Location: index.php");
    exit();
}

// Initialize variables
$products = [];
$customer_name = "";
$customer_phone = "";
$salesperson_username = $_SESSION['user_username']; // Get salesperson's username from session
$grand_total = 0;
$items = [];
$alert_message = ""; // Variable to hold alert message
$discount_percentage = 0; // Initialize discount percentage

// Fetch products from the database (only price will be shown)
$query = "SELECT product_id, product_name, product_price, product_stock FROM products";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']); // Get customer phone
    $product_ids = $_POST['product_id']; // Array of selected products
    $quantities = $_POST['quantity']; // Array of selected quantities
    $discount_percentage = isset($_POST['discount']) ? (int)$_POST['discount'] : 0; // Get discount percentage
    
    if (!preg_match('/^\d{10}$/', $customer_phone)){
        $alert_message = "Please add a valid phone number.";
    }

    // Loop through each selected product
    foreach ($product_ids as $index => $product_id) {
        $quantity = (int)$quantities[$index];
        
        // Fetch product selling price and quantity
        $query = "SELECT product_name, product_price, product_stock FROM products WHERE product_id = '$product_id'";
        $result = mysqli_query($conn, $query);
        $product = mysqli_fetch_assoc($result);

        if ($product) {
            $price = $product['product_price'];
            $available_quantity = $product['product_stock'];
            
            // Check if the requested quantity is available
            if ($quantity <= $available_quantity) {
                $total = $price * $quantity; // Calculate total for this product
                $grand_total += $total; // Add to the grand total

                // Reduce product quantity in inventory
                $new_quantity = $available_quantity - $quantity;
                $query = "UPDATE products SET product_stock = '$new_quantity' WHERE product_id = '$product_id'";
                mysqli_query($conn, $query);

                // Add product details to items array for display in the bill
                $items[] = [
                    'product_id' => $product_id, // Include product_id
                    'name' => $product['product_name'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total
                ];
            } else {
                // Set the alert message if the product is out of stock
                $alert_message = "Requested quantity for {$product['product_name']} exceeds available stock.";
                break;
            }
        }
    }

    // Insert the sale into the bills table if all products are in stock
    if ($alert_message === "") {
    // Calculate discount amount
    $discount_amount = ($grand_total * $discount_percentage) / 100;
    $payable_amount = $grand_total - $discount_amount;

    // Insert or fetch the customer_id from customers table
    $query = "SELECT customer_id FROM customers WHERE customer_name = '$customer_name' AND customer_phone = '$customer_phone'";
    $result = mysqli_query($conn, $query);
    $customer = mysqli_fetch_assoc($result);

    if ($customer) {
        $customer_id = $customer['customer_id'];
    } else {
        // Insert the customer into the database if not found
        $query = "INSERT INTO customers (customer_name, customer_phone) VALUES ('$customer_name', '$customer_phone')";
        mysqli_query($conn, $query);
        $customer_id = mysqli_insert_id($conn);
    }

    // Insert the bill into the bills table, including salesperson username (using user_id)
    $query = "SELECT user_id FROM users WHERE user_username = '$salesperson_username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    // Generate unique bill number
    $bill_number = 'BILL' . strtoupper(bin2hex(random_bytes(4))); // Generate a unique bill number

    $query = "INSERT INTO bills (bill_number, customer_id, user_id, total_amount, discount, final_amount) 
              VALUES ('$bill_number', '$customer_id', '$user_id', '$grand_total', '$discount_amount', '$payable_amount')";

    if (mysqli_query($conn, $query)) {
        // Get the last inserted ID (bill_id)
        $bill_id = mysqli_insert_id($conn);

        // Insert each product into the bill_details table
        foreach ($items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $total = $item['total'];

            $insert_item_query = "INSERT INTO bill_details (bill_id, product_id, product_quantity, product_price, product_total) 
                                  VALUES ('$bill_id', '$product_id', '$quantity', '$price', '$total')";
            mysqli_query($conn, $insert_item_query);
        }

        // Open the view_bill.php page in a new tab
        echo "<script>window.open('view_bill.php?bill_id=$bill_id', '_blank');</script>";

        // Redirect the current page (create_bill.php) to itself to clear the form
        echo "<script>window.location.href = 'create_bill.php';</script>";
        exit();
    } else {
        // Handle SQL error
        $alert_message = "Error: " . mysqli_error($conn);
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
    <title>Create Bill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            background-image: url('shop.png');
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
        .form-group {
            margin-bottom: 20px;
        }
        .btn-secondary, .btn-primary {
            width: 100%;
            margin-top: 10px;
        }
        .alert-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        .bill-container {
            margin-top: 40px;
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
        }
        .bill-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .bill-container th, .bill-container td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .bill-summary {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
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
        <h2>Create Bill</h2>
        
        <?php if (!empty($alert_message)): ?>
            <div class="alert-message">
                <script>
                    window.onload = function() {
                        alert("<?php echo addslashes($alert_message); ?>");
                    };
                </script>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="customer_name">Customer Name:</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="customer_phone">Customer Phone:</label>
                <input type="text" name="customer_phone" class="form-control" required>
            </div>
            <div id="product-list">
                <div class="form-group" id="product-field">
                    <label for="product_id">Select Product:</label>
                    <select name="product_id[]" class="form-control" required>
                        <option value="">Select a product</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['product_id']; ?>">
                                <?php echo $product['product_name']; ?> - $<?php echo $product['product_price']; ?> (Available: <?php echo $product['product_stock']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity[]" class="form-control" min="1" required>
                </div>
            </div>
            <div class="form-group">
                <label for="discount">Discount (%):</label>
                <input type="number" name="discount" class="form-control" min="0" value="0">
            </div>
            <button type="button" class="btn btn-secondary" onclick="addProductField()">Add Another Product</button>
            <button type="submit" class="btn btn-primary">Create Bill</button>
            <a href="salesperson_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </form>
    </div>

    <script>
        function addProductField() {
            const newProductField = document.getElementById('product-field').cloneNode(true);
            document.getElementById('product-list').appendChild(newProductField);
        }
    </script>
</body>
</html>