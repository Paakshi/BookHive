<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

// Get the user name and user ID from the session
$user_name = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BookHive</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>BookHive</h2>
        <ul>
            <li><a href="borrowed_books.php">Borrowed Books</a></li>
            <li><a href="account_settings.php">Account Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="search_books.php">Search Books</a></li>
            <li><a href="payment.php">Payment</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Welcome, <?php echo $user_name; ?>!</h1>
        <p>Your User ID: <?php echo $_SESSION['userid']; ?></p>
        <p>Here you can manage your borrowed books, view your profile, and more!</p>

        <div class="actions">
            <h3>Quick Actions:</h3>
            <button><a href="borrow_books.php">Borrow a Book</a></button>
            <button><a href="return_books.php">Return a Book</a></button>
            <button><a href="search_books.php">Search for Books</a></button>
        </div>
    </div>
</div>
<!-- Floating Book Bot Button -->
<?php include('footer.php'); ?>


</body>
</html>
