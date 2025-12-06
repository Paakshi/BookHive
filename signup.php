<?php
session_start();  // Start the session at the beginning

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('includes/db.php');
    
    // Get form data
    $fullname = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);
    
    // Check if the passwords match
    if ($password != $password_confirm) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit;
    }
    
    // Check if the email already exists
    $email_check_sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $email_check_sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email is already registered.');</script>";
        exit;
    }
    
    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert the user into the database
    $sql = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$password_hash')";
    if (mysqli_query($conn, $sql)) {
        // Get the user ID of the newly inserted user
        $user_id = mysqli_insert_id($conn);
        
        // Set session variables
        $_SESSION['userid'] = $user_id;
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        
        // Redirect to dashboard after successful sign-up
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - BookHive</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff; /* Entire background is now white */
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 100px; /* Added padding to the top */
            position: relative;
            overflow: hidden;
        }

        /* Full-Screen Spline 3D Character */
        iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 0;
            z-index: -1; /* Ensure the iframe stays behind the form */
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 50px;
            padding: 20px;
            max-width: 700px;
            width: 100%;
            z-index: 1;
        }

        .form-container {
            background-color: rgb(247, 248, 248); /* Transparent blue */
            backdrop-filter: blur(10px); /* Blur effect */
            -webkit-backdrop-filter: blur(10px); /* For Safari */
            padding: 40px;
            margin-top: 150px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 300px;
            height: 380px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 5px;
            margin-top: -25px;
            color: #333;
            font-size: 20px;
        }

        .form-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .form-container p {
            margin-top: 15px;
            font-size: 14px;
            color: white;
        }

        .form-container a {
            color: #4CAF50;
            text-decoration: none;
        }

        .form-container a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 800px) {
            .container {
                flex-direction: column;
                gap: 30px;
            }

            iframe {
                width: 100%;
                height: 100%;
            }
        }
    </style>
</head>
<body class="signup-page">

    <iframe src='https://my.spline.design/nexbotrobotcharacterconcept-1f6mJ78WtEWnH5X6XifmyoGi/' frameborder='0' width='100%' height='100%'></iframe>

    <div class="container">
        <!-- Sign Up Form -->
        <div class="form-container">
            <h2>Create Account</h2>
            <form action="signup.php" method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirm" placeholder="Confirm Password" required>
                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

</body>
</html>
