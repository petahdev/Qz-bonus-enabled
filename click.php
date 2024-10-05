<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

// Include the database connection
include 'connect.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Get the ad link from the query parameter
$adLink = isset($_GET['link']) ? $_GET['link'] : '';

// Update the click count for the user in the link_clicks table
$query = "INSERT INTO link_clicks (user_id, click_count) VALUES (?, 1)
          ON DUPLICATE KEY UPDATE click_count = click_count + 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->close();

// Redirect to the actual ad
header("Location: " . $adLink);
exit();
?>
