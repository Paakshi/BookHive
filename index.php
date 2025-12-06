<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>BookHive - A Library Management System</title>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="#" class="bookhive">BookHive</a>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#book">Books</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
           
        </ul>
    </nav>

    <!-- Home Section -->
    <div class="home" id="home">
        <div class="home-content">
            <h1>BOOKHIVE<br>LIBRARY</h1>
            <a href="login.php" class="login-btn">Login</a>
            <a href="signup.php" class="signup-btn">Sign Up</a>
        </div>
        <iframe src='https://my.spline.design/sketchbookcopy-dUeJm6ggeo2rNZ2ewHtEJ2f6/' frameborder='0' width='100%' height='100%'></iframe>        <!--<img src="https://img.freepik.com/premium-photo/cartoon-illustration-children-reading-books-library-with-man-reading-book_905510-11360.jpg">-->
    </div>

   
    <!-- Books Section -->
    <section id="book" class="books-section">
    <h2>Library Books</h2>
    <div class="book-container">
        <div class="book">
            <img src="assests/book1.jpeg" alt="Book Image">
            <h3>Atomic Habits</h3>
            <p>James Clear</p>
            <div class="btn-container">
                <form action="login.php" method="GET" style="display:inline;">
                    <input type="hidden" name="action" value="borrow">
                    <input type="hidden" name="book" value="Atomic Habits">
                    <button type="submit" class="borrow-btn">Borrow</button>
                </form>
                <form action="login.php" method="GET" style="display:inline;">
                    <input type="hidden" name="action" value="return">
                    <input type="hidden" name="book" value="Atomic Habits">
                    <button type="submit" class="return-btn">Return</button>
                </form>
            </div>
        </div>

        <div class="book">
            <img src="assests/book2.png" alt="Book Image">
            <h3>The Power of Subconsious mind</h3>
            <p>Dr. Joseph Murphy</p>
            <div class="btn-container">
                <form action="login.php" method="GET" style="display:inline;">
                    <input type="hidden" name="action" value="borrow">
                    <input type="hidden" name="book" value="The Power of Subconsious mind">
                    <button type="submit" class="borrow-btn">Borrow</button>
                </form>
                <form action="login.php" method="GET" style="display:inline;">
                    <input type="hidden" name="action" value="return">
                    <input type="hidden" name="book" value="The Power of Subconsious mind">
                    <button type="submit" class="return-btn">Return</button>
                </form>
            </div>
        </div>
    </div>
</section>


   <!-- About Section -->
   <div class="about" id="about">
    <img src="assests/about.jpeg">
    <div class="about-text">
        <h1>About Us</h1>
        <h2>BookHive is a modern library management system that helps you keep track of books, manage members, and facilitate a seamless borrowing and returning process.</h2>
    </div>
</div>


    <!-- Contact Section -->
    <div class="contact" id="contact">
        <h2>Contact Us</h2>
        <p>Email: support@bookhive.com</p>
        <p>Phone: +123 456 7890</p>
        <p>Location: 123 Library Street, BookTown</p>
    </div>
<!-- Floating Book Bot Button -->
<?php include('footer.php'); ?>

    
        <!-- Footer -->
        <footer>
            <p>&copy; 2025 BookHive. All rights reserved.</p>
        </footer>
    
        <!-- JavaScript for smooth scrolling -->
        <script>
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        </script>
</body>
</html>
