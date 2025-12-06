<?php
// Include database connection
include('includes/db.php');

// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

// Get user ID from session
$user_id = $_SESSION['userid'];

// Get payment amount from URL
$payment_amount = isset($_GET['payment_amount']) ? $_GET['payment_amount'] : 0;

$message = "Thank you for your payment!";

// Fetch user details for the receipt
$query = "SELECT * FROM users WHERE userid = '$user_id'";
$result = mysqli_query($conn, $query);

// Check if query was successful and user exists
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    // Handle case where user is not found
    $user = ['fullname' => 'Unknown User'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        /* Add your styles here */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px 20px;
            background: linear-gradient(to bottom right, #e3f2fd, #ffffff);
            color: #333;
        }

        h1 {
            text-align: center;
            color: #0d47a1;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .container {
            max-width: 450px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px 25px;
        }

        .message {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #0d47a1;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            margin-top: 30px;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Payment Receipt</h1>

    <div class="container">
        <div class="message">
            <p>✔️ Payment Successful!</p>
            <p>Amount Paid: ₹<?php echo number_format($payment_amount, 2); ?></p>
            <p>User: <?php echo isset($user['fullname']) ? $user['fullname'] : 'Unknown User'; ?></p>
            <p>Payment Date: <?php echo date('l, F j, Y'); ?></p>
        </div>

        <div>
            <p>Thank you for your payment. Your books have been successfully returned. Enjoy reading!</p>
        </div>
        
        <a href="index.php" style="text-decoration: none; color: #0f9d58; font-weight: bold;">Go Back to Home</a>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> BookHive-Library Management System | All Rights Reserved
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
