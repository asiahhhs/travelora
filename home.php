<?php
session_start();

// Redirect logged-in users to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Travelora</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .hero-section {
            height: 100vh;
            background: url('1431622.jpg');
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            background-blend-mode: darken;
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">Travelora</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link btn btn-light" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-light" href="signup.php">Sign Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">Discover Amazing Destinations</h1>
            <p class="lead">Join Travelora today and explore beautiful places around the world.</p>
            <a href="signup.php" class="btn btn-success btn-lg">Get Started</a>
        </div>
    </div>

    <!-- About Section -->
    <div class="container text-center my-5">
        <h2>Why Choose Travelora?</h2>
        <p class="lead">Plan your dream vacation with ease. Save destinations, explore beautiful locations, and connect with other travelers.</p>
    </div>

    <!-- Footer -->
    <footer class="bg-success text-white text-center p-3">
        <p>&copy; 2024 Travelora. All rights reserved.</p>
        <p>📞 Phone: <a href="tel:+60123456789" class="text-white">+603-3335 9534</a></p>
        <p>📧 Email: <a href="mailto:contact@travelora.com" class="text-white">contact@travelora.com</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
