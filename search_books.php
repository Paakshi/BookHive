<?php
include('includes/db.php');

if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);

    $query = "SELECT * FROM book WHERE title LIKE '%$search_query%' 
              OR author LIKE '%$search_query%' OR genre LIKE '%$search_query%'";
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f7f7f7;
        }

        h1, h3 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .book {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.05);
        }

        .book h4 {
            margin: 0;
            color: #222;
        }

        .book p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Search for Books</h1>

    <form action="search_books.php" method="POST">
        <input type="text" name="search_query" placeholder="Search by title, author, or genre" required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
        <h3>Search Results:</h3>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="book">
                <h4><?php echo $row['title']; ?></h4>
                <p>Author: <?php echo $row['author']; ?></p>
                <p>Genre: <?php echo $row['genre']; ?></p>
                <form action="borrow_books.php" method="POST">
                    <input type="hidden" name="copyno" value="<?php echo $row['ISBN']; ?>">
                    <button type="submit" name="borrow">Borrow this book</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php elseif (isset($result)): ?>
        <p>No books found!</p>
    <?php endif; ?>
<!-- Floating Book Bot Button -->
<?php include('footer.php'); ?>

    
        <div class="footer">
            <p>&copy; 2025 Book Library. All rights reserved.</p>
        </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
