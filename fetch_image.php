<?php
// Database connection
include 'connect.php'; 

// Start the session
session_start();

// Get user ID from session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($user_id) {
    // Prepare and execute the SQL query to fetch the image
    $stmt = $conn->prepare("SELECT image_data, mime_type FROM images WHERE user_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($imageData, $imageType);
        $stmt->fetch();
        
        // Set the correct content type header
        header("Content-Type: " . $imageType);
        
        // Output the image data
        echo $imageData;
    } else {
        // Display default image if no image found
        header("Content-Type: image/jpeg");
        readfile('assets/images/users/iconuser.png');
    }

    $stmt->close();
}

$conn->close();
?>
