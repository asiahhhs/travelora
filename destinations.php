<?php
session_start();
include 'travel_db.php'; // Database connection

// Fetch destinations (filtered if search is applied)
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%{$_GET['search']}%";
    $stmt = $conn->prepare("SELECT * FROM destinations WHERE name LIKE ? OR description LIKE ?");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM destinations ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations - Travelora</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body{
            background: url('travel1.jpg');
            background-size: cover;
            background-blend-mode: darken;
            background-color: rgba(0, 0, 0, 0.61);
        }
        .card-body{
            background-color: rgba(16, 151, 41, 0.47);
        }
        h2{
            text-shadow: 3px 3px 4px  rgb(0, 0, 0);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">Travelora</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="destinations.php">Destinations</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="user_settings.php">Settings</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 fw-bold text-white">Travel Packages</h2>
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

        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($destination = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo $destination['image']; ?>" class="card-img-top" alt="Destination Image" style="height:200px; object-fit:cover;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?php echo $destination['name']; ?></h5>
                                <p class="card-text"><?php echo substr($destination['description'], 0, 100) . '...'; ?></p>
                                <a href="destination_details.php?id=<?php echo $destination['id']; ?>" class="btn btn-success">View More</a>

                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <form action="save_destination.php" method="post" class="d-inline">
                                        <input type="hidden" name="destination_id" value="<?php echo $destination['id']; ?>">
                                        <button type="submit" class="btn btn-warning">Save</button>
                                    </form>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-warning">Save</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php else: ?>
            <p class="text-center">No destinations found.</p>
        <?php endif; ?>
    </div>


    <footer class="bg-success text-white text-center py-3 mt-5">
        <p>&copy; <?php echo date('Y'); ?> Travelora. All Rights Reserved.</p>
    </footer>

</body>
</html>