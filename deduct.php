<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include database connection
include 'connect.php';

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Get the stake amount from the POST request
$stake_amount = isset($_POST['stake_amount']) ? floatval($_POST['stake_amount']) : 0;

if ($stake_amount <= 0) {
    $_SESSION['error'] = "Invalid stake amount.";
    header("Location: stake.php"); // Redirect to stake input page
    exit();
}

// Fetch the user's current balance from the funds table
$query = "SELECT balance FROM funds WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();

// Check if the balance is sufficient for the stake
if ($balance < $stake_amount) {
    $_SESSION['error'] = "Insufficient balance for staking.";
    header("Location: stake.php"); // Redirect back to stake input page
    exit();
}

// Deduct the stake amount from the user's balance
$new_balance = $balance - $stake_amount;
$query = "UPDATE funds SET balance = ? WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("di", $new_balance, $user_id);

if ($stmt->execute()) {
    // Store the stake amount in the session
    $_SESSION['stake_amount'] = $stake_amount;
    
    // Redirect to quiz.php upon successful staking
    header("Location: quiz.php");
} else {
    $_SESSION['error'] = "Error deducting the balance.";
    header("Location: stake.php"); // Redirect back in case of an error
}

$stmt->close();
$conn->close();
exit();
?>

<!-- stake.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stake Page</title>
    <style>
        body {
            background-color: #202221;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .error {
            color: red; /* Error message in red */
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>Stake Your Amount</h1>
    
    <?php
    // Display error messages if any
    if (isset($_SESSION['error'])) {
        echo '<div class="error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']); // Clear error message after displaying
    }
    ?>

    <form action="your_script.php" method="POST">
        <label for="stake_amount">Enter Stake Amount:</label>
        <input type="number" name="stake_amount" id="stake_amount" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
