<?php
session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'travel_db.php'; // Include database connection

// Check if username is sent via POST
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Ensure the username is valid
    if (!empty($username)) {
        // Delete user from database using username
        $delete_query = "DELETE FROM users WHERE username = '$username'";

        if ($conn->query($delete_query)) {
            header("Location: manage_users.php"); // Redirect after successful deletion
            exit();
        } else {
            die("Error deleting user.");
        }
    } else {
        die("Invalid username.");
    }
} else {
    die("No username specified.");
}
?>
