<?php
session_start();
include 'connect.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$userAnswers = $data['userAnswers'];

// Correct answers for the quiz
$correctAnswers = [
    'q1' => 'B', 'q2' => 'A', 'q3' => 'B', 'q4' => 'B', 'q5' => 'C',
    'q6' => 'C', 'q7' => 'C', 'q8' => 'B', 'q9' => 'A', 'q10' => 'B'
];

$score = 0;

// Calculate the user's score
foreach ($correctAnswers as $question => $correctAnswer) {
    if (isset($userAnswers[$question]) && $userAnswers[$question] === $correctAnswer) {
        $score++;
    }
}

// Insert or update quiz results in the database
$sql = "INSERT INTO quiz_results (user_id, completed, score) VALUES (?, 1, ?) ON DUPLICATE KEY UPDATE completed = 1, score = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $score, $score);
$stmt->execute();
$stmt->close();

// Check if the user scored 9 or 10
if ($score >= 9) {
    // Credit 15 shs to the user's funds
    $credit_sql = "UPDATE users SET funds = funds + 15 WHERE id = ?";
    $credit_stmt = $conn->prepare($credit_sql);
    $credit_stmt->bind_param("i", $user_id);
    $credit_stmt->execute();
    $credit_stmt->close();

    // Optionally, you can also notify the user or log this action
    // e.g., inserting a record in a 'transactions' table if you have one
}

// Optionally, return the score or a success message
echo json_encode(['success' => true, 'score' => $score]);
