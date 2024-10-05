<?php
session_start(); // Start the session
include 'connect.php'; // Include your database connection

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if user is not logged in
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Check if the user exists in the funds table
$stmt = $conn->prepare("SELECT claimed, balance FROM funds WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($claimed, $balance);
$stmt->fetch();
$stmt->close();

// Function to add user to funds if they don't exist
function addUserToFunds($conn, $userId) {
    $initialBalance = 0; // or whatever your starting balance should be
    $insert_sql = "INSERT INTO funds (user_id, balance, created_at) VALUES (?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("id", $userId, $initialBalance);
    
    if ($insert_stmt->execute()) {
        return true; // Successfully added
    } else {
        return false; // Failed to add
    }
}

// If user does not exist, add them to funds table
if ($claimed === null) {
    // User does not exist, insert a new record
    if (!addUserToFunds($conn, $userId)) {
        displayMessage("Error creating funds account for user: " . $stmt->error, 'error');
        exit();
    } else {
        // Initialize claimed as false for the new user
        $claimed = 0;
    }
}

// Check if the user has already claimed the deal
if ($claimed) {
    // If already claimed, display message
    displayMessage("You have already claimed the deal. This offer is available once per account.", 'error');
    exit();
}

// Update user's balance in the funds table
$stmt = $conn->prepare("UPDATE funds SET balance = balance + 50, claimed = 1 WHERE user_id = ?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    // Successfully updated the balance
    displayMessage("Congratulations! You've successfully claimed the deal. Your balance has been updated.", 'success');

    // Redirect to dashboard.php after 3 seconds
    header("refresh:3;url=dashboard.php");
    exit();
} else {
    // Error updating balance
    displayMessage("Error claiming deal: " . $stmt->error, 'error');
}

$stmt->close();

// Function to display styled success or error messages
function displayMessage($message, $type = 'success') {
    $backgroundColor = $type === 'error' ? '#f8d7da' : '#22c55e'; // Red for error, green for success
    $textColor = $type === 'error' ? '#721c24' : '#ffffff'; // Dark red for error text, white for success text

    echo '
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #202221;
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-container {
            max-width: 600px;
            background-color: #292929;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .message-header {
            background-color: #202221;
            padding: 15px 0;
            color: #ffffff;
        }
        .message-body {
            padding: 20px;
            background-color: ' . $backgroundColor . ';
            color: ' . $textColor . ';
            border-radius: 8px;
            font-size: 16px;
        }
    </style>
    <div class="message-container">
        <div class="message-header">
            <h1>Quizzy</h1>
        </div>
        <div class="message-body">
            ' . htmlspecialchars($message) . '
        </div>
    </div>';
}
?>
=