<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'travel_db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    

    // Ensure the uploads folder exists
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create the folder if it doesn't exist
    }

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = $upload_dir . basename($image_name);
        
        // Move the uploaded file to the desired directory
        if (move_uploaded_file($image_tmp, $image_path)) {
            // Insert data into the database
            $query = "INSERT INTO destinations (name, description, address, image) VALUES ('$name', '$description', '$address', '$image_path')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "Destination added successfully!";
                header("Location: manage_destination.php"); // Redirect to manage destinations page
                exit();
            } else {
                $_SESSION['error'] = "Failed to add destination. Please try again.";
            }
        } else {
            $_SESSION['error'] = "Failed to upload image. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Please upload an image.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Destination</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('malaysia2.jpg');
            background-size: cover;
            background-blend-mode: darken;
            background-color: rgba(0, 0, 0, 0.5);
        }
        h2{
            text-shadow: 3px 3px 4px  rgb(0, 0, 0);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Travelora</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_destination.php">Manage Destinations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add Destination Form -->
    <div class="container mt-5">
        <h2 class="text-white fw-bold">Add New Destination</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="add_destination.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label text-white">Destination Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label text-white">Description</label>
                <textarea class="form-control" name="description" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label text-white">Address</label>
                <textarea class="form-control" name="address" rows="3" required></textarea>
            </div>       

            <div class="mb-3">
                <label for="image" class="form-label text-white">Destination Image</label>
                <input type="file" class="form-control" name="image" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Destination</button>
        </form>
    </div>
            <br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
