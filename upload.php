<?php
// Database connection
include 'connect.php'; // Ensure this file contains your database connection code

// Define the maximum file size in bytes (e.g., 10MB)
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10 MB

if (isset($_POST['upload'])) {
    // Check if the file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $fileSize = $_FILES['image']['size'];

        // Check if file size is within the allowed limit
        if ($fileSize > MAX_FILE_SIZE) {
            $message = "File is too large. Please choose a file up to " . (MAX_FILE_SIZE / (1024 * 1024)) . " MB.";
            $button = "";
        } else {
            // Get file details
            $filename = $_FILES['image']['name'];
            $filetmp = $_FILES['image']['tmp_name'];
            $mime_type = $_FILES['image']['type'];
            $image_data = file_get_contents($filetmp);

            // Prepare SQL statement
            $stmt = $conn->prepare("INSERT INTO images (filename, mime_type, image_data, user_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $filename, $mime_type, $image_data, $_SESSION['user_id']);

            // Execute and check for success
            if ($stmt->execute()) {
                $message = "Image uploaded successfully!";
                $button = "<a href='dashboard.php' class='go-back-button'>Go Back to Home</a>";
            } else {
                $message = "Error: " . $stmt->error;
                $button = "";
            }

            $stmt->close();
        }
    } else {
        $message = "Error uploading file.";
        $button = "";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Status</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            position: relative;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }
        .message-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .message {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .go-back-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }
        .go-back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php echo $button; ?>
    </div>
</body>
</html>
