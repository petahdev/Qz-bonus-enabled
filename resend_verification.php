<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php'; // Include your database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resend_verification'])) {
    $email = $_POST['email'];

    // Prepare a statement to fetch the user's token and username
    $stmt = $conn->prepare("SELECT token, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($verificationToken, $username);
    $stmt->fetch();

    if ($verificationToken) {
        // Resend the verification email
        sendVerificationEmail($email, $verificationToken, $username);
        displayMessage("A new verification email has been sent to your email address.");
    } else {
        displayMessage("Error: Could not resend verification email.", 'error');
    }

    $stmt->close();
}

function displayMessage($message, $type = 'success') {
    $backgroundColor = $type === 'error' ? '#f8d7da' : '#22c55e';
    $textColor = $type === 'error' ? '#721c24' : '#ffffff';

    echo '
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #202221;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-container {
            max-width: 600px;
            background-color: #202221;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
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
        <div class="message-body">
            ' . htmlspecialchars($message) . '
        </div>
    </div>';
}

// Function to send the verification email (same as in the register.php file)
function sendVerificationEmail($email, $verificationToken, $username) {
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mutitupeter76@gmail.com';
        $mail->Password   = 'fbwj edrn alpz alur';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email settings
        $mail->setFrom('mutitupeter76@gmail.com', 'Gainly');
        $mail->addAddress($email);

        // Email body
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = '
        <html>
        <body>
            <h2>Thank You for Registering!</h2>
            <p>Dear ' . htmlspecialchars($username) . ',</p>
            <p>Please click the button below to verify your email.</p>
            <a href="gainly.000.pe/verify.php?token=' . $verificationToken . '">Verify Email</a>
        </body>
        </html>';

        $mail->send();
    } catch (Exception $e) {
        displayMessage("Email could not be sent. Error: {$mail->ErrorInfo}", 'error');
    }
}
?>
