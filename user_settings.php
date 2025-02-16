<?php
session_start();
include 'travel_db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Use user_id instead of id

// Fetch user details
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Check if the user wants to change the password
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $update_query = "UPDATE users SET name = ?, email = ?, password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $name, $email, $password, $user_id);
    } else {
        $update_query = "UPDATE users SET name = ?, email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: user_settings.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings - Travelora</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body{
            background: url('travel1.jpg');
            background-size: cover;
            background-blend-mode: darken;
            background-color: rgba(0, 0, 0, 0.66);
        }
        h2{
            text-shadow: 3px 3px 4px  rgb(0, 0, 0);
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Travelora</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="destinations.php">Destinations</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="user_settings.php">Settings</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- User Settings Form -->
    <div class="container mt-5">
        <h2 class= "text-white fw-bold">User Settings</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="user_settings.php" method="POST">
        <div class="mb-3">
    <label for="name" class="form-label text-white">Full Name</label>
    <input type="text" class="form-control" name="name" 
           value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?>" required>
</div>

<div class="mb-3">
    <label for="email" class="form-label text-white">Email</label>
    <input type="email" class="form-control" name="email" 
           value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
</div>


            <div class="mb-3">
                <label for="password" class="form-label text-white">New Password (Optional)</label>
                <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
            </div>

            <button type="submit" class="btn btn-success">Update Settings</button>
        </form>
    </div>

    <!-- Footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
