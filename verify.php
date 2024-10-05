<?php
include 'connect.php'; // Include your database connection code

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Token is valid, update the user's verification status
        $stmt->close();
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1, token = NULL WHERE token = ?");
        $stmt->bind_param("s", $token);

        if ($stmt->execute()) {
            // Redirect to index.php with a success message
            header('Location: index.php?message=Your email has been verified. You can now login.');
            exit();
        } else {
            // Redirect to index.php with an error message
            header('Location: index.php?message=Failed to verify email. Please try again.');
            exit();
        }
    } else {
        // Redirect to index.php with an invalid token message
        header('Location: index.php?message=Invalid verification token.');
        exit();
    }

    $stmt->close();
} else {
    // Redirect to index.php with a no token message
    header('Location: index.php?message=No token provided.');
    exit();
}

$conn->close();
?>
