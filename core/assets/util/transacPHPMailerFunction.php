<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include_once '../../core/vendor/autoload.php';

function sendEmail($recipientEmail, $subject, $message) {
    // Create a single PHPMailer instance
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'aqnotification@gmail.com';
    $mail->Password   = 'axzroicoizegvydn';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    
    try {
        $mail->clearAddresses(); // Clear previous recipient addresses
        $mail->addAddress($recipientEmail);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        // Log success or handle errors
        return true; // Email sent successfully
    } catch (Exception $e) {
        // Capture and log any errors
        error_log("Email sending failed: " . $mail->ErrorInfo);
        // Handle errors or continue sending other emails
        return false; // Email sending failed
    }
}
?>