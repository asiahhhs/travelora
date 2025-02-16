<?php
session_start();
include 'travel_db.php'; // Database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Check if ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: manage_destination.php");
    exit();
}

$destination_id = $_GET['id'];

// Fetch destination details
$query = "SELECT * FROM destinations WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $destination_id);
$stmt->execute();
$result = $stmt->get_result();
$destination = $result->fetch_assoc();

if (!$destination) {
    $_SESSION['error'] = "Destination not found.";
    header("Location: manage_destination.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, str_replace("\r", "", $_POST['description']));
    $address = mysqli_real_escape_string($conn, $_POST['address']);    

    // Handle image upload
    $image_path = $destination['image']; // Default to existing image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_name = basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = $upload_dir . $image_name;

        // Move new image to the folder
        if (!move_uploaded_file($image_tmp, $image_path)) {
            $_SESSION['error'] = "Failed to upload image.";
            header("Location: edit_destination.php?id=" . $destination_id);
            exit();
        }
    }

    // Update destination in the database
    $update_query = "UPDATE destinations SET name=?, description=?, address=?, image=? WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $name, $description, $address, $image_path, $destination_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Destination updated successfully!";
        header("Location: manage_destination.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update destination.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destination</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        background: url('malaysia2.jpg');
        background-size: cover;
        background-blend-mode: darken;
        background-color: rgba(0, 0, 0, 0.7);
    }
    h2 {
        color: black;
        text-shadow: 2px 2px 4px rgb(0, 0, 0);  
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

    <!-- Edit Destination Form -->
    <div class="container mt-5">
        <h2 class="fw-bold text-white">Edit Destination</h2>

        <!-- Display Error/Success Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="edit_destination.php?id=<?php echo $destination_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label text-white">Destination Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($destination['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label text-white">Description</label>
                <textarea name="description" class="form-control" name="description" row="5" required><?php echo htmlspecialchars($destination['description']); ?></textarea>
                </div>

            <div class="mb-3">
                <label for="address" class="form-label text-white">Address</label>
                <textarea class="form-control" name="address" rows="3" required><?php echo htmlspecialchars($destination['address']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label text-white">Current Image</label><br>
                <img src="<?php echo $destination['image']; ?>" alt="Destination Image" width="150">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label text-white">Upload New Image (Optional)</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Update Destination</button>
            <a href="manage_destination.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
