<?php
// Set database connection details
$host = "localhost";
$username = "root"; // Change this as per your database configuration
$password = ""; // Set your database password
$dbname = "travel_db"; // Your database name

// Create a connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
