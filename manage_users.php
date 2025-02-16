<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'travel_db.php'; // Include database connection

// Fetch all users from the database
$query = "SELECT * FROM users";
$result = $conn->query($query);

// Check if there are users in the database
if ($result && $result->num_rows > 0) {
    // If users are found, fetch them into an array
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // If no users are found, initialize $users as an empty array
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('malaysia2.jpg');
            background-size: cover;
            background-blend-mode: darken;
            background-color: rgba(0, 0, 0, 0.5);
        }
        h2 {
            text-shadow: 3px 3px 4px  rgb(0, 0, 0);
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color:rgb(24, 94, 112) !important;
            color: white;
            text-align: center;
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
                        <a class="nav-link active" href="manage_users.php">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_destination.php">Manage Destinations</a>
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

    <!-- Manage Users Content -->
    <div class="container mt-5">
        <h2 class= "text-white">Manage Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class= "text-white">Username</th>
                    <th class= "text-white">Email</th>
                    <th class= "text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php 
    if (!empty($users)): 
        foreach ($users as $user): 
    ?>
        <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            
            <td>
                <!-- Delete User form -->
                <form action="delete_user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" class="d-inline-block">
                    <input type="hidden" name="username" value="<?php echo $user['username']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
    <?php 
        endforeach;
    else:
    ?>
        <tr>
            <td colspan="4" class="text-center">No users found</td>
        </tr>
    <?php endif; ?>
</tbody>


        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
