<?php
// Database connection
include 'connect.php'; // Ensure this file contains your database connection code

if (isset($_POST['upload'])) {
    // Check if the file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Get file details
        $filename = $_FILES['image']['name'];
        $filetmp = $_FILES['image']['tmp_name'];
        $mime_type = $_FILES['image']['type'];
        $image_data = file_get_contents($filetmp);

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO images (filename, mime_type, image_data) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $filename, $mime_type, $image_data);

        // Execute and check for success
        if ($stmt->execute()) {
            echo "Image uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();
?>
