<?php
session_start();
include 'travel_db.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['destination_id'])) {
    $user_id = $_SESSION['user_id'];
    $destination_id = $_POST['destination_id'];

    // Check if destination is already saved
    $check_stmt = $conn->prepare("SELECT * FROM saved_destinations WHERE user_id = ? AND destination_id = ?");
    $check_stmt->bind_param("ii", $user_id, $destination_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows == 0) {
        // Save the destination
        $stmt = $conn->prepare("INSERT INTO saved_destinations (user_id, destination_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $destination_id);
        if ($stmt->execute()) {
            header("Location: destinations.php?success=Saved successfully");
        } else {
            header("Location: destinations.php?error=Failed to save");
        }
    } else {
        header("Location: destinations.php?error=Already saved");
    }
}
?>
