<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

// Connect to the database
include 'connect.php';

// Get the user ID from the request
$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

// Check if user ID is valid
if ($userId <= 0) {
    header("HTTP/1.1 400 Bad Request");
    exit();
}

// Reset clicks in the link_clicks table
$query = "UPDATE link_clicks SET click_count = 0 WHERE user_id = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
        header("HTTP/1.1 500 Internal Server Error");
    }
    $stmt->close();
} else {
    echo "Error preparing query: " . $conn->error;
    header("HTTP/1.1 500 Internal Server Error");
}

$conn->close();
?>
