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
$books = [];
$message = "";

// Query to get the borrowed books and their due dates
$query = "SELECT b.copyno, b.borrowdate, b.duedate, bk.title AS book_title
          FROM borrowing b
          JOIN bookcopies bc ON b.copyno = bc.copyno
          JOIN book bk ON bc.ISBN = bk.ISBN
          WHERE b.userid = '$user_id' AND b.returndate IS NULL"; // assuming NULL means not returned yet

$result = mysqli_query($conn, $query);

// Check if the query is successful and fetch the results
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row; // Store each borrowed book record
    }
} else {
    $message = "❌ No borrowed books found.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>
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

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .message {
            margin-top: 25px;
            font-size: 18px;
            color: #333;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Your Borrowed Books</h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!empty($books)): ?>
        <table>
            <tr>
                <th>Book Title</th>
                <th>Book Copy Number</th>
                <th>Borrowed On</th>
                <th>Due Date</th>
                <th>Return</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo $book['book_title']; ?></td>
                    <td><?php echo $book['copyno']; ?></td>
                    <td><?php echo date('l, F j, Y', strtotime($book['borrowdate'])); ?></td>
                    <td><?php echo date('l, F j, Y', strtotime($book['duedate'])); ?></td>
                    <td>
                        <form action="return_books.php" method="POST">
                            <input type="hidden" name="copyno" value="<?php echo $book['copyno']; ?>">
                            <button type="submit" name="return">Return Book</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
<!-- Floating Book Bot Button -->
<?php include('footer.php'); ?>

    <footer class="footer">
        <p>&copy; 2025 BookHive. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
mysqli_close($conn);
?>
