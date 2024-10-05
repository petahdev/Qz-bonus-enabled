<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$amountToAdd = 0;

if (isset($_POST['update_funds']) && $_POST['update_funds'] == '1') {
    // Amount to add if the form is submitted
    $amountToAdd = 0.4; // Fixed amount for demonstration, adjust as needed
} elseif (isset($_GET['amount'])) {
    // Amount to add if redirected from track_click.php
    $amountToAdd = (float)$_GET['amount'];
}

if ($amountToAdd > 0) {
    // Prepare SQL to update balance
    $stmt = $conn->prepare("UPDATE funds SET balance = balance + ? WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("di", $amountToAdd, $user_id);
        if ($stmt->execute()) {
            $successMessage = "Funds updated successfully!";
        } else {
            $successMessage = "Error updating funds: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $successMessage = "Error preparing statement: " . $conn->error;
    }
} else {
    $successMessage = "No funds to update.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funds Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #202221;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .message-box {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 50%;
        }
        .message-box h2 {
            color: #22c55e;
            margin-bottom: 15px;
        }
        .back-button {
            background-color: #22c55e;
            border: none;
            padding: 10px 20px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: #202221;
            color: #22c55e;
            border: 1px solid #22c55e;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2><?php echo $successMessage; ?></h2>
        <a href="dashboard.php" class="back-button">Go Back to Dashboard</a>
    </div>
</body>
</html>
