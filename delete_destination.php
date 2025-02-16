<?php
session_start();
include 'travel_db.php'; // Include database connection

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Check if destination name is passed via GET request
if (!isset($_GET['name']) || empty($_GET['name'])) {
    die("Error: No destination name specified.");
}

// Get and sanitize the destination name
$destination_name = $_GET['name'];

// Check if the destination exists in the database
$stmt = $conn->prepare("SELECT * FROM destinations WHERE name = ?");
$stmt->bind_param("s", $destination_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Destination not found.");
}

// If the destination exists, proceed with deletion
$stmt = $conn->prepare("DELETE FROM destinations WHERE name = ?");
$stmt->bind_param("s", $destination_name);

if ($stmt->execute()) {
    header("Location: manage_destination.php?success=deleted");
    exit();
} else {
    echo "Error deleting destination: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
