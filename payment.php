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
$message = "";

// Initialize variables
$overdue_books = [];
$total_due_amount = 0;

// Query to get overdue books (if any) for the user
$query = "SELECT b.bid, b.copyno, bk.title, b.duedate, DATEDIFF(CURDATE(), b.duedate) AS overdue_days
          FROM borrowing b
          JOIN bookcopies bc ON b.copyno = bc.copyno
          JOIN book bk ON bc.ISBN = bk.ISBN
          WHERE b.userid = '$user_id' AND b.returndate IS NULL AND b.duedate < CURDATE()";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Calculate overdue payment, 2 Rupees per day
        $overdue_days = $row['overdue_days'];
        $due_amount = $overdue_days * 2;
        $row['due_amount'] = $due_amount;

        $overdue_books[] = $row;
        $total_due_amount += $due_amount;
    }
} else {
    $message = "❌ No overdue books found.";
}

// Handle payment
if (isset($_POST['pay']) && isset($_POST['payment_amount'])) {
    $payment_amount = $_POST['payment_amount'];

    if ($payment_amount == $total_due_amount) {
        $update_query = "UPDATE borrowing SET returndate = CURDATE() WHERE userid = '$user_id' AND returndate IS NULL";
        if (mysqli_query($conn, $update_query)) {
            header("Location: receipt.php?payment_amount=$payment_amount");
            exit;
        
        } else {
            $message = "❌ Error processing payment. Please try again.";
        }
    } else {
        $message = "❌ Payment amount does not match total overdue amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Payment</title>
    <style>
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

        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            font-size: 15px;
        }

        th {
            background-color: #e3f2fd;
            font-weight: 600;
        }

        td {
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        .total-due {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #d32f2f;
            margin: 20px 0;
        }

        input[type="number"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fafafa;
        }

        button {
            width: 100%;
            padding: 14px;
            font-size: 18px;
            background-color: #0f9d58;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #0c7c43;
            transform: scale(1.03);
        }

        .footer {
            text-align: center;
            font-size: 14px;
            margin-top: 30px;
            color: #888;
        }

        label {
            font-size: 16px;
            font-weight: 500;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>📚 Overdue Payment</h1>

    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (!empty($overdue_books)): ?>
        <form action="payment.php" method="POST">
            <table>
                <tr>
                    <th>Book Title</th>
                    <th>Copy No</th>
                    <th>Due Date</th>
                    <th>Days Late</th>
                    <th>Due (₹)</th>
                </tr>
                <?php foreach ($overdue_books as $book): ?>
                    <tr>
                        <td><?php echo $book['title']; ?></td>
                        <td><?php echo $book['copyno']; ?></td>
                        <td><?php echo date('l, F j, Y', strtotime($book['duedate'])); ?></td>
                        <td><?php echo $book['overdue_days']; ?></td>
                        <td>₹<?php echo number_format($book['due_amount'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="payment-section">
                <h3 class="total-due">Total Due: ₹<?php echo number_format($total_due_amount, 2); ?></h3>
                <label for="payment_amount">💸 Enter Payment Amount (₹):</label>
                <input type="number" name="payment_amount" value="<?php echo $total_due_amount; ?>" required>
            </div>

            <button type="submit" name="pay">Pay with GPay 💳</button>
        </form>
        <?php endif; ?>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> BookHive-Library Management System | All Rights Reserved
    </div>
    <!-- Floating Book Bot Button -->
    <?php include('footer.php'); ?>


</body>
</html>

<?php
mysqli_close($conn);
?>
