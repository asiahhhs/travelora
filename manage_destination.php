<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'travel_db.php'; // Include database connection

// Fetch all destinations from the database
$query = "SELECT * FROM destinations";
$result = $conn->query($query);

// Check if there are destinations in the database
if ($result->num_rows > 0) {
    $destinations = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $destinations = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Destinations</title>
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
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color:rgb(24, 94, 112) !important; /* Dark Green */
            color: white;
            text-align: center;
        }

        td, th {
            text-align: center;
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
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

    <!-- Manage Destinations Content -->
    <div class="container mt-5">
        <h2 class="text-white">Manage Destinations</h2>
        <a href="add_destination.php" class="btn btn-primary mb-3">Add New Destination</a>

        <!-- Display Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table class="table table-bordered table-striped text-white">
            <thead>
                <tr>
                    <th class="text-white">Name</th>
                    <th class="text-white">Description</th>
                    <th class="text-white">Address</th>
                    <th class="text-white">Image</th>
                    <th class="text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $colors = ['table-primary', 'table-success', 'table-warning', 'table-info', 'table-danger'];
                $index = 0;
                foreach ($destinations as $destination): 
            ?>
            
            <tr class="<?php echo $colors[$index % count($colors)]; ?>">
                    <tr>
                        <td class="fw-bold"><?php echo $destination['name']; ?></td>
                        <td><?php echo $destination['description']; ?></td>
                        <td><?php echo $destination['address']; ?></td>
                        <td>
                            <img src="<?php echo !empty($destination['image']) ? $destination['image'] : 'uploads/gunung.jpg'; ?>" 
                                 alt="Destination Image" width="100">
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <a href="edit_destination.php?id=<?php echo $destination['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            
                            <!-- Delete Button -->
                            <a href="delete_destination.php?name=<?php echo urlencode($destination['name']); ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this destination?');">
                            Delete
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
