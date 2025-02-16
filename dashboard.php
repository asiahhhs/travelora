<?php
session_start();
include 'travel_db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch saved destinations by the user
$saved_query = "
    SELECT d.* FROM destinations d
    INNER JOIN saved_destinations s ON d.id = s.destination_id
    WHERE s.user_id = ?";
$saved_stmt = $conn->prepare($saved_query);
$saved_stmt->bind_param("i", $user_id);
$saved_stmt->execute();
$saved_result = $saved_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Travelora</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body{
            background: url('travel1.jpg');
            background-size: cover;
            background-blend-mode: darken;
            background-color: rgba(0, 0, 0, 0.61);
        }
        h2{
            text-shadow: 3px 3px 4px  rgb(0, 0, 0);
        }
        .card-body {
            background-color: rgba(16, 151, 41, 0.47);
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
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="user_settings.php">Settings</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-5">
        <h2 class= "text-white fw-bold">Your Saved Destinations</h2>

        <a href="https://wa.me/60123456789" target="_blank" style="
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #25D366;
    padding: 15px;
    border-radius: 50%;
    box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
    text-align: center;">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" width="40">
    </a>

        <?php if ($saved_result->num_rows > 0): ?>
            <div class="row">
                <?php while ($destination = $saved_result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($destination['image']); ?>" class="card-img-top" alt="Destination Image">
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($destination['name']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($destination['description'])); ?></p>
                                <a href="destination_details.php?id=<?php echo $destination['id']; ?>" class="btn btn-primary">View Details</a>
                                <button class="btn btn-danger unsave-btn" data-id="<?php echo $destination['id']; ?>">Unsave</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class= "text-white">You haven't saved any destinations yet.</p>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function() {
            $(".unsave-btn").click(function() {
                var button = $(this);
                var destinationId = button.data("id");

                $.ajax({
                    url: "unsave_destination.php",
                    type: "POST",
                    data: { destination_id: destinationId },
                    success: function(response) {
                        if (response === "unsaved") {
                            button.closest(".col-md-4").fadeOut();
                        } else {
                            alert("Error removing saved destination.");
                        }
                    }
                });
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
