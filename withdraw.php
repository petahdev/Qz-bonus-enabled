<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Ensure PHPMailer is loaded correctly

// Database connection
$conn = new mysqli("localhost", "root", "", "gainly");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

// Check if the withdrawal amount is set in the POST request
if (isset($_POST['withdraw_amount'])) {
    $withdraw_amount = $_POST['withdraw_amount']; // Get the withdrawal amount from the form

    // Validate the withdrawal amount
    if ($withdraw_amount < 20) {
        $_SESSION['error'] = "Withdrawal amount must be at least Ksh 20.";
        header('Location: withdrawal_form.php');
        exit;
    }

    // Fetch the user's details from the database using the user_id stored in session
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT email, username, mobilenumber FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_email = $row['email'];
        $username = $row['username'];
        $mobile_number = $row['mobilenumber'];

        // Process the withdrawal
        // TODO: Implement your withdrawal logic here

        // Reset click count in link_clicks table (assuming there's a column 'click_count' to reset)
        $reset_sql = "UPDATE link_clicks SET click_count = 0 WHERE user_id = ?";
        $reset_stmt = $conn->prepare($reset_sql);
        $reset_stmt->bind_param("i", $user_id);
        $reset_stmt->execute();

        // Send the email using PHPMailer
        $admin_email = "mutitupeter76@gmail.com";
        $mail = new PHPMailer(true);

        try {
            // Server settings for SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $admin_email;
            $mail->Password   = 'fbwj edrn alpz alur'; // Admin email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email to the user
            $mail->setFrom($admin_email, 'Gainly Admin');
            $mail->addAddress($user_email, $username);

            $mail->isHTML(true);
            $mail->Subject = 'Your Withdrawal Request on Gainly';
            $mail->Body = "
            <div style='background-color: white; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='background-color: black; padding: 20px; color: white; text-align: center;'>
                    <h1 style='margin: 0;'>Gainly</h1>
                </div>
                <div style='padding: 20px;'>
                    <h2 style='color: #333;'>Hello {$username},</h2>
                    <p style='font-size: 18px;'>We have successfully processed your withdrawal request of <strong>Ksh. {$withdraw_amount}</strong>.</p>
                    <p style='font-size: 16px;'>Thank you for being a part of Gainly. Should you have any questions or concerns, feel free to contact us.</p>
                </div>
                <div style='background-color: #f0f0f0; padding: 15px; text-align: center;'>
                    <p style='font-size: 14px; color: #333;'>Thank you for using Gainly!</p>
                </div>
                <div style='background-color: black; padding: 15px; color: white; text-align: center;'>
                    <p style='font-size: 12px;'>Gainly &copy; 2024 | All Rights Reserved</p>
                </div>
            </div>";
            $mail->send();
            echo "Email has been sent to user.";

            // Email to the admin with user details
            $mail->clearAddresses();
            $mail->addAddress($admin_email);
            $mail->Subject = 'Withdrawal Alert - User Details';
            $mail->Body = "
            <div style='background-color: white; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='background-color: black; padding: 20px; color: white; text-align: center;'>
                    <h1 style='margin: 0;'>Gainly</h1>
                </div>
                <div style='padding: 20px;'>
                    <h2 style='color: #333;'>Withdrawal Request Alert</h2>
                    <p style='font-size: 18px;'>A user has submitted a withdrawal request with the following details:</p>
                    <p style='font-size: 16px;'><strong>Username:</strong> {$username}</p>
                    <p style='font-size: 16px;'><strong>Email:</strong> {$user_email}</p>
                    <p style='font-size: 16px;'><strong>Mobile Number:</strong> {$mobile_number}</p>
                    <p style='font-size: 16px;'><strong>Requested Amount:</strong> Ksh. {$withdraw_amount}</p>
                </div>
                <div style='background-color: #f0f0f0; padding: 15px; text-align: center;'>
                    <p style='font-size: 14px; color: #333;'>Please review and confirm this transaction.</p>
                </div>
                <div style='background-color: black; padding: 15px; color: white; text-align: center;'>
                    <p style='font-size: 12px;'>Gainly &copy; 2024 | All Rights Reserved</p>
                </div>
            </div>";
            $mail->send();
            echo "Admin has been notified.";

            // Success message and redirection
            echo "<div style='color: #22c55e;'>Withdrawal successful! Redirecting to dashboard...</div>";
            header("refresh:3;url=dashboard.php");
            exit();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "User not found in the database.";
    }
} else {
    echo "Withdrawal amount not set. Please submit the form.";
}
?>
