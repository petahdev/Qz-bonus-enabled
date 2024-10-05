<?php
// word.php
require 'connect.php'; // Make sure to include your database connection

// Start session
session_start();

// Load words from a file
$words = file('words.txt', FILE_IGNORE_NEW_LINES);

// Initialize puzzle counter if not set
if (!isset($_SESSION['puzzle_count'])) {
    $_SESSION['puzzle_count'] = 0; // Count of completed puzzles
    $_SESSION['total_score'] = 0; // User's total score
}

// Initialize feedback variable
$feedback = "";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $userAnswer = implode('', $_POST['user_answer']); // Get user's answer as a string
    $correctWord = $_POST['correct_word']; // Get the correct word

    // Increment puzzle count
    $_SESSION['puzzle_count']++;

    // Check the answer
    if (strtoupper($userAnswer) === strtoupper($correctWord)) {
        // User answered correctly
        $feedback = "<div class='feedback correct'>Correct!</div>";
    } else {
        $feedback = "<div class='feedback incorrect'>Incorrect! The correct word was: " . $correctWord . "</div>";
    }

    // Check if 10 puzzles have been completed
    if ($_SESSION['puzzle_count'] % 10 == 0) {
        // User completed 10 puzzles
        $_SESSION['total_score'] += 4; // Add 4 shs to total score

        // Check if user_id is set in session before updating
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("UPDATE users SET balance = balance + 4 WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
            
            $feedback .= "<div class='feedback bonus'>Congratulations! You've completed 10 puzzles. You earned 4 shs.</div>";
        } else {
            $feedback .= "<div class='feedback incorrect'>Error: User is not logged in.</div>";
        }
    }
}

// Select a random word
$randomWord = $words[array_rand($words)];

// Create incomplete word
$lettersToRemove = 2; // Number of letters to remove
$positions = array_rand(range(0, strlen($randomWord) - 1), $lettersToRemove);
$displayWord = []; // Prepare the display array

// Create display word with input fields
for ($i = 0; $i < strlen($randomWord); $i++) {
    if (in_array($i, $positions)) {
        // If it's a missing letter, add an input box without hyphen
        $displayWord[] = "<input type='text' name='user_answer[]' maxlength='1' class='letter-input' required>";
    } else {
        // Otherwise, show the actual letter
        $displayWord[] = "<span class='revealed'>" . $randomWord[$i] . "</span>";
    }
}

// Store the correct answer in session as a string
$_SESSION['correct_answer'] = $randomWord;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Word Puzzle</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #202221; /* Dark background */
            color: white; /* Default text color */
            padding: 20px; /* Padding for body */
        }

        h1 {
            color: #22c55e; /* Header color */
        }

        .word-display {
            font-size: 30px;
            margin: 20px 0;
            letter-spacing: 5px; /* Add letter spacing */
        }

        .revealed {
            color: #22c55e; /* Color for already shown letters */
        }

        .letter-input {
            width: 30px; /* Fixed width for input */
            height: 40px; /* Height for better appearance */
            text-align: center; /* Center the letter */
            font-size: 30px; /* Match font size */
            background-color: #202221; /* Input background color */
            color: white; /* Input text color */
            border: 2px solid #22c55e; /* Border color */
            border-radius: 5px; /* Rounded corners */
        }

        .letter-input:focus {
            border-color: #00ff00; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #22c55e; /* Button color */
            color: white; /* Button text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            margin: 10px; /* Margin for buttons */
        }

        button:hover {
            background-color: #1da34c; /* Darker green on hover */
        }

        .feedback {
            margin-top: 15px;
        }

        .correct {
            color: #22c55e; /* Green for correct */
        }

        .incorrect {
            color: red; /* Red for incorrect */
        }

        .bonus {
            color: #22c55e; /* Bonus color */
        }
    </style>
</head>
<body>
    <h1>Complete the Word</h1>
    <form method="POST">
        <div class="word-display"><?php echo implode('', $displayWord); ?></div>
        <input type="hidden" name="correct_word" value="<?php echo $randomWord; ?>">
        <button type="submit" name="submit">Submit</button>
    </form>
    <form method="POST" action="word.php">
        <button type="submit">Next Word</button>
    </form>
    <?php if (!empty($feedback)) echo $feedback; ?>
</body>
</html>
