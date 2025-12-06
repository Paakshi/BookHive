<?php
$servername = "localhost";
$username = "root"; // default for XAMPP
$password = ""; // default for XAMPP
$dbname = "librarydb"; // database name

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Error connecting to the database: " . $e->getMessage());
}
?>
