<?php
include 'connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$stmt = $conn->prepare("SELECT id, email, username, token, attempt_count FROM failed_emails WHERE is_sent = 0 AND attempt_count < 5");
$stmt->execute();
$stmt->bind_result($id, $email, $username, $token, $attempt_count);

while ($stmt->fetch()) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mutitupeter76@gmail.com';
        $mail->Password   = 'fbwj edrn alpz alur';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('mutitupeter76@gmail.com', 'Gainly');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = '<p>Please click <a href="gainly.000.pe/verify.php?token='.$token.'">here</a> to verify your email.</p>';
        $mail->send();

        // Mark email as sent
        $updateStmt = $conn->prepare("UPDATE failed_emails SET is_sent = 1 WHERE id = ?");
        $updateStmt->bind_param("i", $id);
        $updateStmt->execute();
    } catch (Exception $e) {
        // Increment attempt count
        $updateStmt = $conn->prepare("UPDATE failed_emails SET attempt_count = attempt_count + 1 WHERE id = ?");
        $updateStmt->bind_param("i", $id);
        $updateStmt->execute();
    }
}

$stmt->close();
?>
