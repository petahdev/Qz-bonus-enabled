<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard.php"); // Redirect to login if not logged in
    exit();
}

// Check if the user has a valid stake amount
if (!isset($_SESSION['stake_amount']) || $_SESSION['stake_amount'] <= 0) {
    $_SESSION['error'] = "You must stake before accessing the quiz.";
    header("Location: stake.php"); // Redirect to the stake input page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzy Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            color: black;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        h3 {
            margin-bottom: 10px;
            color: #444;
        }
        .question-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 600px;
            text-align: left;
        }
        label {
            display: block;
            margin: 10px 0;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        .score-container {
            margin-top: 20px;
            font-size: 18px;
        }
        #timer {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>"Good Luck"-Qizzy Quiz!</h1>
    <div id="timer">Time Remaining: <span id="countdown"></span> seconds</div>
    
    <?php
    function loadQuestions() {
        $json = file_get_contents('questions.json');
        return json_decode($json, true);
    }

    // Initialize quiz questions
    if (!isset($_SESSION['questions']) || isset($_POST['reset'])) {
        $questions = loadQuestions();
        
        $easyQuestions = [];
        $hardQuestions = [];
        
        foreach ($questions as $question) {
            if ($question['difficulty'] === 'easy') {
                $easyQuestions[] = $question;
            } else if ($question['difficulty'] === 'hard') {
                $hardQuestions[] = $question;
            }
        }

        shuffle($easyQuestions);
        shuffle($hardQuestions);
        
        $_SESSION['questions'] = array_merge(array_slice($easyQuestions, 0, 6), array_slice($hardQuestions, 0, 4));
        shuffle($_SESSION['questions']);
        $_SESSION['current_question'] = 0;
        $_SESSION['score'] = 0;
        $_SESSION['start_time'] = time();
        $_SESSION['quiz_duration'] = 60;
        $_SESSION['end_time'] = $_SESSION['start_time'] + $_SESSION['quiz_duration'];
    } else {
        $questions = $_SESSION['questions'];
    }

    // Handle quiz answer submissions
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['answer'])) {
            $selectedAnswer = $_POST['answer'];
            $currentQuestion = $_SESSION['current_question'];
            if ($selectedAnswer == $questions[$currentQuestion]['answer']) {
                $_SESSION['score']++;
            }
            $_SESSION['current_question']++;
        }

        // Check if the quiz is completed
        $quizCompleted = ($_SESSION['current_question'] >= count($_SESSION['questions'])) || (time() >= $_SESSION['end_time']);
        if ($quizCompleted) {
            $score = $_SESSION['score'];
            $stake_amount = $_SESSION['stake_amount'];
            $profit_amount = 0;

            if ($score >= 9) {
                $profit_amount = $stake_amount * 2.5; // 100% profit + stake back
            } elseif ($score == 8) {
                $profit_amount = $stake_amount * 2.5; // 50% profit + stake back
            } else {
                $profit_amount = 0; // Lose only the stake
            }

            include 'connect.php';
            $user_id = $_SESSION['user_id'];
            $sql = "UPDATE funds SET balance = balance + ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $profit_amount, $user_id);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            echo "<div class='score-container'><h2>Your score: $score/10</h2>";
            echo "<h3>Your new balance: Ksh " . ($profit_amount) . "</h3>";
            if ($score >= 9) {
                echo "<h3>Congratulations, you made it!</h3>";
            } elseif ($score >= 6 && $score < 9) {
                echo "<h3>Good trial, keep trying!</h3>";
            } elseif (time() >= $_SESSION['end_time']) {
                echo "<h3>Time is up! The quiz has been automatically submitted.</h3>";
            } else {
                echo "<h3>Sorry, but your quiz answers don't meet the passing limit. Keep trying!</h3>";
            }

            echo '<form method="POST">
                    <button name="reset">Retake Quiz</button>
                    <a href="dashboard.php"><button type="button">Go to Dashboard</button></a>
                  </form></div>';

            // Clean up session after completing the quiz
            session_unset();
            session_destroy();
            exit;
        }
    }

    $currentQuestion = $_SESSION['current_question'];
    echo "<div class='question-container'>";
    echo "<h3>" . $_SESSION['questions'][$currentQuestion]['question'] . "</h3>";
    echo '<form method="POST">';
    foreach ($_SESSION['questions'][$currentQuestion]['options'] as $option) {
        echo '<label><input type="radio" name="answer" value="' . $option . '"> ' . $option . '</label>';
    }
    echo '<br><button type="submit">Next Question</button>';
    echo '</form>';
    echo '</div>';
    ?>

    <script>
        let quizDuration = <?php echo $_SESSION['quiz_duration']; ?>;
        let endTime = <?php echo $_SESSION['end_time']; ?>;
        let timerElement = document.getElementById('countdown');
        let timerColor = document.getElementById('timer');

        function updateTimer() {
            let currentTime = Math.floor(Date.now() / 1000);
            let remainingTime = endTime - currentTime;

            if (remainingTime <= 0) {
                clearInterval(timerInterval);
                alert('Time is up!');
                document.querySelector('form').submit();
            } else {
                timerElement.innerHTML = remainingTime;
                if (remainingTime > (quizDuration * 0.5)) {
                    timerColor.style.color = 'green';
                } else if (remainingTime > (quizDuration * 0.25)) {
                    timerColor.style.color = 'orange';
                } else {
                    timerColor.style.color = 'red';
                }
            }
        }

        let timerInterval = setInterval(updateTimer, 1000);
    </script>
</body>
</html>
