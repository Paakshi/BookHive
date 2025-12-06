<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION['userid'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "librarydb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$faq = [
    "How many books can I borrow?" => "You can borrow up to 3 books at a time.",
    "For how long can I keep the book?" => "The standard borrowing period is 14 days.",
    "What if I return late?" => "A fine of ₹2 per day will be charged after the due date.",
    "Can I extend my borrowing?" => "Yes, request an extension before the due date at the library desk.",
    "How do I suggest a book to the library?" => "Visit the library's suggestion box or email the librarian.",
    "How can I view my borrowed books?" => "Go to your Profile ➔ Borrowed Books section."
];

$faqAnswer = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);
        
        // Check if the entered question matches any FAQ question
        if (array_key_exists($keyword, $faq)) {
            $faqAnswer = $faq[$keyword];
        } else {
            // Search books in the database if not an FAQ
            $sql = "SELECT b.title, b.author, bc.copyno
            FROM book b
            JOIN bookcopies bc ON b.ISBN = bc.ISBN
            WHERE LOWER(b.title) LIKE LOWER('%$keyword%') OR LOWER(b.author) LIKE LOWER('%$keyword%')";
    
            $result = $conn->query($sql);
        }
    } elseif (isset($_POST['borrow'])) {
        $copyno = $_POST['copyno'];
        $borrow_date = date("Y-m-d");
        $due_date = date('Y-m-d', strtotime('+14 days'));

        $sql_borrow = "INSERT INTO borrowing (borrowdate, copyno, userid, duedate) 
                       VALUES ('$borrow_date', '$copyno', '$userid', '$due_date')";
        if ($conn->query($sql_borrow) === TRUE) {
            echo "Book borrowed successfully!";
        } else {
            echo "Error: " . $sql_borrow . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Bot</title>
    <style>
        /* Previous styles... (same as you wrote) */
        .welcome-message {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 40vh;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .book-bot-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            z-index: 1000;
        }
        .book-bot-btn:hover {
            background-color: #45a049;
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }

        /* Popup (Book Bot) */
        .bot-popup {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 20px;
            background-color: #fff;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            padding: 15px;
            z-index: 1001;
        }

        .bot-popup h4 {
            margin-top: 0;
            color: #4CAF50;
        }

        .faq-item {
            margin-bottom: 15px;
        }

        .faq-item p {
            margin: 5px 0;
        }

        /* Search form and cards (same as you wrote) */
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .search-results {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .book-card {
            border: 1px solid #ccc;
            padding: 20px;
            width: 250px;
            border-radius: 10px;
            box-shadow: 2px 2px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        .book-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .book-card p {
            font-size: 14px;
        }

        .book-card button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .book-card button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="welcome-message">
        <h2>Welcome to Book Bot!</h2>
    </div>

    <!-- Search Form -->
    <form method="POST">
        <input type="text" name="keyword" placeholder="Search for books or ask a question..." required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php
    if ($faqAnswer != '') {
        echo "<div class='faq-item'><p><strong>Answer:</strong> $faqAnswer</p></div>";
    }

    if (isset($result)) {
        if ($result->num_rows > 0) {
            echo "<h3 style='text-align:center;'>Search Results:</h3>";
            echo "<div class='search-results'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='book-card'>
                        <h3>" . htmlspecialchars($row['title']) . "</h3>
                        <p><strong>Author:</strong> " . htmlspecialchars($row['author']) . "</p>
                        <p><strong>Copy No:</strong> " . htmlspecialchars($row['copyno']) . "</p>
                        <form method='POST'>
                            <input type='hidden' name='copyno' value='" . htmlspecialchars($row['copyno']) . "'>
                            <button type='submit' name='borrow'>Borrow Now</button>
                        </form>
                      </div>";
            }
            echo "</div>";
        } else {
            echo "<p style='text-align:center;'>No books found matching your search.</p>";
        }
    }
    ?>

    <!-- Book Bot Button -->
    <div class="book-bot-btn" onclick="toggleBot()">📚 Book Bot</div>

    <!-- Book Bot Popup -->
    <div class="bot-popup" id="botPopup">
        <h4>Hi! How can I help you? 📖</h4>
        <div class="faq-item">
            <p><strong>Q:</strong> How many books can I borrow?</p>
            <p><strong>A:</strong> You can borrow up to 3 books at a time.</p>
        </div>
        <div class="faq-item">
            <p><strong>Q:</strong> For how long can I keep the book?</p>
            <p><strong>A:</strong> The standard borrowing period is 14 days.</p>
        </div>
        <div class="faq-item">
            <p><strong>Q:</strong> What if I return late?</p>
            <p><strong>A:</strong> A fine of ₹2 per day will be charged after the due date.</p>
        </div>
        <div class="faq-item">
            <p><strong>Q:</strong> Can I extend my borrowing?</p>
            <p><strong>A:</strong> Yes, request an extension before the due date at the library desk.</p>
        </div>
        <div class="faq-item">
            <p><strong>Q:</strong> How do I suggest a book to the library?</p>
            <p><strong>A:</strong> Visit the library's suggestion box or email the librarian.</p>
        </div>
        <div class="faq-item">
            <p><strong>Q:</strong> How can I view my borrowed books?</p>
            <p><strong>A:</strong> Go to your Profile ➔ Borrowed Books section.</p>
        </div>
    </div>

    <script>
        function toggleBot() {
            var bot = document.getElementById("botPopup");
            if (bot.style.display === "none" || bot.style.display === "") {
                bot.style.display = "block";
            } else {
                bot.style.display = "none";
            }
        }
    </script>

</body>
</html>

<?php $conn->close(); ?>
