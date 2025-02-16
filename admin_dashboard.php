<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Display the admin dashboard
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('malaysia2.jpg');
            background-size: cover;
            background-blend-mode: darken;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .card-header {
            background-color:rgb(24, 94, 112)
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
                    <!-- Home Link -->
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_dashboard.php">Home</a>
                    </li>
                    <!-- Manage Users Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Manage Users</a>
                    </li>
                    <!-- Add Content Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="manage_destination.php">Manage Destinations</a>
                    </li>
                    <!-- Settings Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php">Settings</a>
                    </li>
                    <!-- Logout Link -->
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center text-white">
                        <h4>Welcome to Admin Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <h5>Welcome, <?php echo $_SESSION['username']; ?>!</h5>
                        <p>Role: <?php echo $_SESSION['role']; ?></p>
                        <p>You can navigate to different sections of the dashboard using the navigation bar above.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
