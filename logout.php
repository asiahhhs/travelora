<?php
session_start();
session_destroy(); // Destroy all sessions
header("Location: home.php"); // Redirect to login page
exit();
?>
