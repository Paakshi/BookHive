<?php
include('includes/db.php'); // Include the db.php file

if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Failed to connect to database!";
}

// Close the connection (optional, but good practice)
$conn->close();
?>
