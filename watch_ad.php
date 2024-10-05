<?php
// Start session
session_start();

// Connect to the database
include 'connect.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Get the ad link URL from the request
if (isset($_GET['ad_link'])) {
    $ad_link = $_GET['ad_link'];
} else {
    die("Invalid ad link.");
}

// Check if the user has clicked this ad link in the last 24 hours
$query = "SELECT last_click_time FROM link_clicks WHERE user_id = ? AND link_url = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $userId, $ad_link);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Fetch the last click time
    $stmt->bind_result($last_click_time);
    $stmt->fetch();

    // Check if 24 hours have passed since the last click
    $time_diff = time() - strtotime($last_click_time);
    if ($time_diff < 86400) { // 86400 seconds in 24 hours
        // Redirect to dashboard and deny access
        header("Location: dashboard.php?message=You have already watched this ad in the last 24 hours.");
        exit();
    }
}

// Update the click record or insert a new one
if ($stmt->num_rows > 0) {
    // Update last click time if record exists
    $query = "UPDATE link_clicks SET last_click_time = NOW(), click_count = click_count + 1 WHERE user_id = ? AND link_url = ?";
} else {
    // Insert new record if no prior clicks
    $query = "INSERT INTO link_clicks (user_id, link_url, last_click_time, click_count) VALUES (?, ?, NOW(), 1)";
}
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $userId, $ad_link);
$stmt->execute();

// Redirect to the ad link
header("Location: $ad_link");
exit();
?>
