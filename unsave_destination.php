<?php
session_start();
include 'travel_db.php';

if (!isset($_SESSION['user_id'])) {
    echo "error";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['destination_id'])) {
    $destination_id = (int)$_POST['destination_id'];

    // Delete from saved destinations
    $delete_query = "DELETE FROM saved_destinations WHERE user_id = ? AND destination_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $user_id, $destination_id);

    if ($stmt->execute()) {
        echo "unsaved";
    } else {
        echo "error";
    }
}
?>
