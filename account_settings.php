<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "librarydb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userid = $_SESSION['userid'];
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];

    if (!empty($new_email)) {
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE userid = ?");
        $stmt->bind_param("si", $new_email, $userid);
        $stmt->execute();
    }

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE userid = ?");
        $stmt->bind_param("si", $hashed_password, $userid);
        $stmt->execute();
    }

    $message = "Account settings updated successfully!";
}

// Fetch current user details
$stmt = $conn->prepare("SELECT * FROM users WHERE userid = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Settings - BookHive</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
        }
        input[type=text], input[type=password], input[type=email] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        input[type=submit] {
            padding: 10px 20px;
            background: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
        .message {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Account Settings</h2>
        <form method="post">
            <label>Full Name:</label>
            <input type="text" value="<?php echo htmlspecialchars($user['fullname']); ?>" disabled>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

            <label>New Password:</label>
            <input type="password" name="password" placeholder="Enter new password">

            <input type="submit" value="Update Settings">
        </form>

        <?php if ($message): ?>
            <p class='message'><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
