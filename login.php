<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "librarydb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];  
    $password = $_POST['password'];  

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Use password_verify to match hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['userid'] = $user['userid'];
            $_SESSION['fullname'] = $user['fullname'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found with that email!";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BookHive</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <iframe src='https://my.spline.design/nexbotrobotcharacterconcept-1f6mJ78WtEWnH5X6XifmyoGi/' frameborder='0' width='100%' height='100%'></iframe>

    <div class="login-container">
        <h2>Login to BookHive</h2>

        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

        <form method="POST">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="submit" class="login-btn">Login</button>

            <p class="signup-link">Don't have an account? <a href="signup.php">Sign Up</a></p>
        </form>
    </div>
</body>
</html>
