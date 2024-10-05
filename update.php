<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

include 'connect.php';

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['stake_amount'])) {
    $_SESSION['error'] = "Stake amount not set.";
    header("Location: dashboard.php");
    exit();
}

$stake_amount = $_SESSION['stake_amount'];
$score = $_SESSION['score'];

if ($score >= 9) {
    $profit_amount = $stake_amount; // 100% profit
} elseif ($score == 8) {
    $profit_amount = $stake_amount * 0.5; // 50% profit
} else {
    $profit_amount = -$stake_amount; // Loss of the stake
}

$sql = "UPDATE funds SET balance = balance + ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $profit_amount, $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Your quiz results have been updated successfully.";
    header("Location: dashboard.php");
} else {
    $_SESSION['error'] = "Error updating balance: " . $stmt->error;
    header("Location: dashboard.php");
}

$stmt->close();
$conn->close();
?>
