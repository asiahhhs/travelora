<?php
session_start();
include 'travel_db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate destination ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid destination ID.");
}

$destination_id = (int)$_GET['id']; // Ensure it's an integer

// Fetch destination details securely
$query = "SELECT * FROM destinations WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $destination_id);
$stmt->execute();
$result = $stmt->get_result();
$destination = $result->fetch_assoc();

if (!$destination) {
    die("Destination not found.");
}

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['rating']) && !empty($_POST['review'])) {
        $rating = (int)$_POST['rating'];
        $review = trim($_POST['review']);

        // Insert review securely
        $query = "INSERT INTO reviews (user_id, destination_id, rating, review, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiis", $user_id, $destination_id, $rating, $review);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Review submitted successfully!";
        } else {
            $_SESSION['error'] = "Failed to submit review.";
        }

        header("Location: destination_details.php?id=" . $destination_id);
        exit();
    } else {
        $_SESSION['error'] = "Please provide both rating and review.";
    }
}

// Fetch reviews securely
$reviews_query = "SELECT reviews.*, users.username FROM reviews 
                  JOIN users ON reviews.user_id = users.user_id 
                  WHERE destination_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($reviews_query);
$stmt->bind_param("i", $destination_id);
$stmt->execute();
$reviews_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($destination['name']); ?> - Reviews</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    h1 {
        color: black;
        text-shadow: 2px 2px 4px rgb(0, 0, 0);
    }
    body {
        background: url('travel1.jpg');
        background-size: cover;
        background-blend-mode: darken;
        background-color: rgba(0, 0, 0, 0.7);
    }
    h3 {
        color: black;
        text-shadow: 2px 2px 4px rgb(0, 0, 0);  
    }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class= "fw-bold text-white"><?php echo htmlspecialchars($destination['name']); ?></h1>
        <img src="<?php echo htmlspecialchars($destination['image']); ?>" alt="Destination Image" class="img-fluid">
        <br>
        <br>
        <h6 class="text-white"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($destination['description'])); ?></h6>
        <br>
        <h6 class="text-white"><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($destination['address'])); ?></h6>
        <br>
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
    <a href="https://wa.me/60123456789" target="_blank">
    <button style="background-color:rgb(25, 141, 67); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
        Book via WhatsApp
    </button>
    </a>


        <h3 class="mt-4 text-white fw-bold">Submit Your Review</h3>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="rating" class="form-label text-white">Rating (1-5 Stars)</label>
                <select class="form-select" name="rating" required>
                    <option value="5">★★★★★ (5 Stars)</option>
                    <option value="4">★★★★☆ (4 Stars)</option>
                    <option value="3">★★★☆☆ (3 Stars)</option>
                    <option value="2">★★☆☆☆ (2 Stars)</option>
                    <option value="1">★☆☆☆☆ (1 Star)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="review" class="form-label text-white">Your Review</label>
                <textarea class="form-control" name="review" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit Review</button>
        </form>

        <h3 class="mt-5 text-white fw-bold">Reviews</h3>
        <?php if ($reviews_result->num_rows > 0): ?>
            <?php while ($review = $reviews_result->fetch_assoc()): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5><?php echo htmlspecialchars($review['username']); ?></h5>
                        <p>Rating: <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($review['review'])); ?></p>
                        <small class="text-muted">Reviewed on <?php echo $review['created_at']; ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-white">No reviews yet. Be the first to review this destination!</p>
        <?php endif; ?>

        <a href="destinations.php" class="btn btn-success mt-3">Back to Destinations</a>
    </div>
    <br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
