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

// Handle Returning a Book
if (isset($_POST['return'])) {
    $copyno = mysqli_real_escape_string($conn, $_POST['copyno']);
    $return_date = date('Y-m-d');

    // Check if the book is actually borrowed by the user
    $check_query = "SELECT * FROM borrowing WHERE copyno = '$copyno' AND userid = '$user_id' AND returndate IS NULL";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Fetch the due date
        $row = mysqli_fetch_assoc($check_result);
        $due_date = $row['duedate'];

        // Check if the return date exceeds the 14-day limit
        $due_date = new DateTime($due_date);
        $current_date = new DateTime($return_date);
        $interval = $due_date->diff($current_date);
        $days_late = $interval->days;

        // If days late, calculate fine
        if ($current_date > $due_date) {
            $fine = ($days_late) * 2; // Fine is ₹2 per day after 14 days
            echo "<p class='fine'>You are returning the book late. Fine: ₹" . $fine . "</p>";

            // Redirect to payment page with the fine as a parameter
            header("Location: payment.php?fine=" . $fine);
            exit;
        }

        // Update the return_date in the borrowing table
        $return_query = "UPDATE borrowing 
                         SET returndate = '$return_date' 
                         WHERE userid = '$user_id' AND copyno = '$copyno' AND returndate IS NULL";

        if (mysqli_query($conn, $return_query)) {
            echo "<p class='success'>Book returned successfully!</p>";
        } else {
            echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p class='error'>Error: This book is not currently borrowed by you, or it has already been returned.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input[type="text"] {
            padding: 10px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success {
            color: green;
            margin-top: 15px;
        }

        .error {
            color: red;
            margin-top: 15px;
        }

        .fine {
            color: #d35400;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Return a Book</h1>
    <form action="return_books.php" method="POST">
        <input type="text" name="copyno" placeholder="Enter Book Copy Number" required><br>
        <button type="submit" name="return">Return Book</button>
    </form>
    <!-- Floating Book Bot Button -->
    <?php include('footer.php'); ?>

    
        <div class="footer">
            <p>&copy; 2025 BookHive. All rights reserved.</p>
        </div>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
