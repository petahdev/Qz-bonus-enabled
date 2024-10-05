<?php
session_start();
include 'connect.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all relevant user data
// Fetch user's username and funds amount from the database
$sql = "
    SELECT 
        u.username, 
        f.amount AS funds
    FROM 
        users u 
    LEFT JOIN 
        funds f ON u.id = f.user_id 
    WHERE 
        u.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $funds); // Update here to match the selected fields
$stmt->fetch();
$stmt->close();

// You can then use $username and $funds as needed in your code


// Check if the user has already completed the quiz

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #202221;
            color: white;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #22c55e;
            margin-bottom: 20px;
        }
        .question {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .answers label {
            display: block;
            margin: 10px 0;
            cursor: pointer;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #22c55e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #1ab244;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Good luck, <?php echo htmlspecialchars($username); ?>!</h2>
    <div class="countdown">Time remaining: <span id="time">2:00</span></div>

    <!-- Question 1 -->
    <div id="question-1" class="question-page">
        <div class="question">1. What is the most common cause of preventable death worldwide?</div>
        <div class="answers">
            <label><input type="radio" name="q1" value="A"> Smoking</label>
            <label><input type="radio" name="q1" value="B"> Car accidents</label>
            <label><input type="radio" name="q1" value="C"> Poor diet</label>
            <label><input type="radio" name="q1" value="D"> Alcohol</label>
        </div>
        <div class="buttons">
            <button class="btn" disabled id="prev-1">Back</button>
            <button class="btn" id="next-1">Next</button>
        </div>
    </div>

    <!-- Question 2 -->
    <div id="question-2" class="question-page hidden">
        <div class="question">2. How many liters of water should an average adult drink daily?</div>
        <div class="answers">
            <label><input type="radio" name="q2" value="A"> 1-2 liters</label>
            <label><input type="radio" name="q2" value="B"> 2-3 liters</label>
            <label><input type="radio" name="q2" value="C"> 3-4 liters</label>
            <label><input type="radio" name="q2" value="D"> 4-5 liters</label>
        </div>
        <div class="buttons">
            <button class="btn" id="prev-2">Back</button>
            <button class="btn" id="next-2">Next</button>
        </div>
    </div>

    <!-- Question 3 -->
    <div id="question-3" class="question-page hidden">
        <div class="question">3. What is the body's largest organ?</div>
        <div class="answers">
            <label><input type="radio" name="q3" value="A"> Brain</label>
            <label><input type="radio" name="q3" value="B"> Skin</label>
            <label><input type="radio" name="q3" value="C"> Liver</label>
            <label><input type="radio" name="q3" value="D"> Lungs</label>
        </div>
        <div class="buttons">
            <button class="btn" id="prev-3">Back</button>
            <button class="btn" id="next-3">Next</button>
        </div>
    </div>

    <!-- Question 4 -->
    <div id="question-4" class="question-page hidden">
        <div class="question">4. What vitamin is produced when skin is exposed to sunlight?</div>
        <div class="answers">
            <label><input type="radio" name="q4" value="A"> Vitamin B12</label>
            <label><input type="radio" name="q4" value="B"> Vitamin D</label>
            <label><input type="radio" name="q4" value="C"> Vitamin A</label>
            <label><input type="radio" name="q4" value="D"> Vitamin C</label>
        </div>
        <div class="buttons">
            <button class="btn" id="prev-4">Back</button>
            <button class="btn" id="next-4">Next</button>
        </div>
    </div>

    <!-- Question 5 -->
    <div id="question-5" class="question-page hidden">
        <div class="question">5. How many hours of sleep should an adult aim for each night?</div>
        <div class="answers">
            <label><input type="radio" name="q5" value="A"> 4-5 hours</label>
            <label><input type="radio" name="q5" value="B"> 6-7 hours</label>
            <label><input type="radio" name="q5" value="C"> 7-9 hours</label>
            <label><input type="radio" name="q5" value="D"> 9-10 hours</label>
        </div>
        <div class="buttons">
            <button class="btn" id="prev-5">Back</button>
            <button class="btn" id="next-5">Next</button>
        </div>
    </div>

    <!-- Question 6 -->
    <div id="question-6" class="question-page hidden">
        <div class="question">6. Which organ is responsible for filtering toxins from the blood?</div>
        <div class="answers">
            <label><input type="radio" name="q6" value="A"> Liver</label>
            <label><input type="radio" name="q6" value="B"> Lungs</label>
            <label><input type="radio" name="q6" value="C"> Kidneys</label>
            <label><input type="radio" name="q6" value="D"> Skin</label>
        </div>
        <div class="buttons">
            <button class="btn" id="prev-6">Back</button>
            <button class="btn" id="next-6">Next</button>
        </div>
    </div>

    <!-- Question 7 -->
    <div id="question-7" class="question-page hidden">
        <div class="question">7. What is the most common mental health disorder worldwide?</div>
        <div class="answers">
            <label><input type="radio" name="q7" value="A"> Schizophrenia</label>
            <label><input type="radio" name="q7" value="B"> Anxiety</label>
            <label><input type="radio" name="q7" value="C"> Depression</label>
            <label><input type="radio" name="q7" value="D"> Bipolar disorder</label>
        </div>
        <div class="buttons">
            <button class="btn" id="prev-7">Back</button>
            <button class="btn" id="next-7">Next</button>
        </div>
    </div>

    <!-- Question 8 -->
    <div id="question-8" class="question-page hidden">
        <div class="question">8. Which of the following is NOT a benefit of exercise?</div>
        <div class="answers">
            <label><input type="radio" name="q8" value="A"> Improves mood</label>
            <label><input type="radio" name="q8" value="B"> Reduces stress</label>
            <label><input type="radio" name="q8" value="C"> Increases risk of injury</label>
            <label><input type="radio" name="q8" value="D"> Increases longevity</label>
        </div>
        <div class="buttons">
            <button class="btn" id="prev-8">Back</button>
            <button class="btn" id="next-8">Next</button>
        </div>
    </div>

    <!-- Question 9 -->
    <div id="question-9" class="question-page hidden">
        <div class="question">9. What is the recommended amount of physical activity for adults each week?</div>
        <div class="answers">
            <label><input type="radio" name="q9" value="A"> 30 minutes</label>
            <label><input type="radio" name="q9" value="B"> 60 minutes</label>
            <label><input type="radio" name="q9" value="C"> 150 minutes</label>
            <label><input type="radio" name="q9" value="D"> 300 minutes</label>
        </div>
        <div class="buttons">
            <button class="btn" id="prev-9">Back</button>
            <button class="btn" id="next-9">Next</button>
        </div>
    </div>

    <!-- Question 10 -->
     <div id="question-10" class="question-page hidden">
        <div class="question">10. What is the leading cause of heart disease?</div>
        <div class="answers">
            <label><input type="radio" name="q10" value="A"> High cholesterol</label>
            <label><input type="radio" name="q10" value="B"> High blood pressure</label>
            <label><input type="radio" name="q10" value="C"> Smoking</label>
            <label><input type="radio" name="q10" value="D"> All of the above</label>
        </div>
        <div class="buttons">
            <button class="btn" id="prev-10">Back</button>
            <button class="btn" id="submit-quiz">Submit Quiz</button>
        </div>
    </div>
</div>

<script>
    let currentQuestion = 1;
    const totalQuestions = 10;

    document.getElementById('next-1').addEventListener('click', () => {
        if (document.querySelector('input[name="q1"]:checked')) {
            showQuestion(2);
        }
    });

    // Attach event listeners for next and previous buttons
    for (let i = 2; i <= totalQuestions; i++) {
        document.getElementById(`prev-${i}`).addEventListener('click', () => showQuestion(i - 1));
        document.getElementById(`next-${i}`).addEventListener('click', () => {
            if (document.querySelector(`input[name="q${i}"]:checked`)) {
                showQuestion(i + 1);
            }
        });
    }

    document.getElementById('prev-10').addEventListener('click', () => showQuestion(9));
    document.getElementById('submit-quiz').addEventListener('click', submitQuiz);

    function showQuestion(questionNumber) {
        for (let i = 1; i <= totalQuestions; i++) {
            document.getElementById(`question-${i}`).classList.add('hidden');
        }
        document.getElementById(`question-${questionNumber}`).classList.remove('hidden');
        currentQuestion = questionNumber;
    }

    function submitQuiz() {
        const answers = {};
        for (let i = 1; i <= totalQuestions; i++) {
            const selected = document.querySelector(`input[name="q${i}"]:checked`);
            if (selected) {
                answers[`q${i}`] = selected.value;
            }
        }

        // Perform the AJAX request to save results
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'submit_quiz.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Quiz submitted successfully!');
                window.location.href = 'results.php'; // Redirect to results page
            }
        };
        xhr.send(JSON.stringify(answers));
    }

    // Countdown timer
    let timeRemaining = 120; // 2 minutes in seconds
    const timerElement = document.getElementById('time');

    const timer = setInterval(() => {
        if (timeRemaining <= 0) {
            clearInterval(timer);
            alert('Time is up! Submitting your answers...');
            submitQuiz();
        } else {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            timeRemaining--;
        }
    }, 1000);
</script>

</body>
</html>