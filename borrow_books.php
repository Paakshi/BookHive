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
$form_submitted = false;
$due_date_display = ""; // Variable to hold the due date for display

if (isset($_POST['borrow'])) {
    $form_submitted = true;
    $copyno = mysqli_real_escape_string($conn, $_POST['copyno']);
    $borrow_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime('+14 days'));  // 14 days borrowing period

    // Insert a new record in the borrowing table
    $borrow_query = "INSERT INTO borrowing (borrowdate, copyno, userid, duedate) 
                     VALUES ('$borrow_date', '$copyno', '$user_id', '$due_date')";

    if (mysqli_query($conn, $borrow_query)) {
        $message = "✅ Book borrowed successfully!";
        $due_date_display = "Due Date: " . date('l, F j, Y', strtotime($due_date)); // Format due date
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f4f4f4;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        form {
            margin-top: 20px;
            display: inline-block;
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 250px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 25px;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Borrow a Book</h1>
    <form action="borrow_books.php" method="POST">
        <input type="text" name="copyno" placeholder="Enter Book Copy Number" required>
        <button type="submit" name="borrow">Borrow Book</button>
    </form>

    <?php if ($form_submitted && !empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
        <?php if ($due_date_display): ?>
            <div class="message"><?php echo $due_date_display; ?></div>
        <?php endif; ?>
    <?php endif; ?>
    <!-- Floating Book Bot Button -->
    <?php include('footer.php'); ?>


</body>
</html>

<?php
mysqli_close($conn);
?>
