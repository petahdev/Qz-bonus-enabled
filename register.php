<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1); // Enable PHP error display for debugging

include 'connect.php'; // Ensure this contains your database connection code

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer's autoloader

// Function to display styled success or error messages
function displayMessage($message, $type = 'success') {
    $backgroundColor = '#202221'; // Background color for messages
    $textColor = '#ffffff'; // Text color for messages

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
            background-color: #202221;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .message-header {
            font-size: 24px;
            margin-bottom: 15px;
        }
        .message-body {
            padding: 20px;
            background-color: ' . $backgroundColor . ';
            color: ' . $textColor . ';
            border-radius: 8px;
            font-size: 24px; /* Default font size */
        }
        @media (max-width: 600px) {
            .message-body {
                font-size: 36px; /* Increased font size for mobile view */
            }
        }
    </style>
    <div class="message-container">
        <div class="message-header">Quizzy says...</div>
        <div class="message-body">
            ' . htmlspecialchars($message) . '
        </div>
    </div>';
}

function sendWelcomeEmail($email, $username) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mutitupeter76@gmail.com'; // Your email address
        $mail->Password   = 'fbwj edrn alpz alur'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('mutitupeter76@gmail.com', 'Quizzy');
        $mail->addAddress($email); // Send to the user's email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Quizzy!';

        // Email body
        $mail->Body = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    background-color: #ffffff;
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    color: #333333;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
                .email-container {
                    max-width: 600px;
                    background-color: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    text-align: left; /* Align text to the left */
                }
                .email-header {
                    background-color: #202221;
                    padding: 15px 0;
                    color: #ffffff;
                    text-align: center; /* Center the header */
                }
                .email-body h2 {
                    margin-bottom: 10px;
                    color: #202221;
                    margin-left: 0; /* Align heading to the left */
                }
                .email-footer {
                    margin-top: 30px;
                    font-size: 12px;
                    color: #666666;
                }
                .login-button {
                    background-color: #22c55e;
                    color: #ffffff;
                    text-decoration: none;
                    padding: 12px 25px;
                    font-size: 16px;
                    border-radius: 4px;
                    display: inline-block;
                    margin-top: 20px;
                }
                .login-button:hover {
                    background-color: #1ba34c;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <h1>Quizzy</h1>
                </div>
                <div class="email-body">
                    <h2>Welcome to Gainly, ' . htmlspecialchars($username) . '!</h2>
                    <p>We’re excited to have you join the Gainly community! Get ready to test your knowledge with our engaging quizzes that not only entertain and educate but also offer you the chance to earn rewards!</p>
        
                    <p>Please note the following important terms:</p>
                    <ul>
                        <li><strong>Account Limitation:</strong> Each user is allowed to create only one account. Multiple accounts are strictly prohibited and may result in suspension.</li>
                        <li><strong>Participation Fee:</strong> A fee is required for quiz participation, but there is no initial account creation fee or minimum balance required.</li>
                        <li><strong>Account Security:</strong> Keep your login credentials confidential; you are responsible for all activities under your account.</li>
                    </ul>
        
                    <p>Ready to jump in? Click the button below to log in and start your rewarding journey with Quizzy!</p>
                    <a href="gainly.000.pe" class="login-button">Go to Login</a>
                </div>
        
                <div class="email-footer">
                    <p>If you didn\'t sign up for this account, please ignore this email.</p>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        // Registration
        $username = $_POST['username'];
        $email = $_POST['useremail'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirmpassword'];
        $mobilenumber = $_POST['mobilenumber'];

        // Check if passwords match
        if ($password !== $confirmpassword) {
            displayMessage("Passwords do not match.", 'error');
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            displayMessage("Email already registered.", 'error');
            exit();
        }

        $stmt->close();

        // Insert user details into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, mobilenumber) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $mobilenumber);

        if ($stmt->execute()) {
            // Send welcome email
            $error = sendWelcomeEmail($email, $username);
            if ($error === true) {
                displayMessage("Welcome to Quizzy! A Welcome email has been sent to your email address. Check it out to login and get started");
            }else {
                displayMessage("Welcome! However, there was an issue sending the welcome email. Please try logging in.", 'error');
             
                echo '<a href="login.php" style="color: #22c55e; text-decoration: underline;">Click here to login</a>';
            }
            
        } else {
            displayMessage("Error: " . $stmt->error, 'error');
        }

        $stmt->close();
    } elseif (isset($_POST['login'])) {
        // Login logic
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            displayMessage("Please fill in all fields.", 'error');
            exit();
        }

        // Check if user exists
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        if (!$stmt) {
            displayMessage("Prepare statement failed: " . $conn->error, 'error');
            exit();
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashedPassword);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $id;
                header("Location: dashboard.php");
                exit();
            } else {
                displayMessage("Incorrect email or password.", 'error');
            }
        } else {
            displayMessage("User not found.", 'error');
        }

        $stmt->close();
    }
}
?>

