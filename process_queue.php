<?php
header('Content-Type: application/json');

include_once 'core/config/config_db.php'; // Ensure no output or errors from this file
include_once 'core/vendor/PHPMailerFunction.php'; // Ensure no output or errors from this file

$response = ['success' => true, 'message' => ''];

try {
    $query = "SELECT * FROM email_queue WHERE status = 'pending'";
    $result = $db->query($query);

    if ($result->num_rows === 0) {
        $response['message'] = 'No pending emails found to process.';
        error_log($response['message']);
    } else {
        while ($email = $result->fetch_assoc()) {
            // Sanitize email data
            $recipientEmail = filter_var($email['recipient_email'], FILTER_SANITIZE_EMAIL);
            $emailSubject = filter_var($email['email_subject'], FILTER_SANITIZE_STRING);
            $emailBody = filter_var($email['email_body'], FILTER_SANITIZE_STRING);

            // Validate sanitized email
            if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                error_log("Invalid email format: " . $email['recipient_email']);
                continue; // Skip this email
            }

            error_log("Processing email to: " . $recipientEmail);
            $isSent = sendEmail($recipientEmail, $emailSubject, $emailBody);

            if ($isSent) {
                error_log("Email sent successfully to: " . $recipientEmail);
                $updateQuery = "UPDATE email_queue SET status = 'sent' WHERE id = ?";
                $updateStmt = $db->prepare($updateQuery);
            } else {
                error_log("Failed to send email to: " . $recipientEmail);
                $updateQuery = "UPDATE email_queue SET status = 'failed' WHERE id = ?";
                $updateStmt = $db->prepare($updateQuery);
            }
            $updateStmt->bind_param("i", $email['id']);
            $updateStmt->execute();
        }
        $response['message'] = 'Email queue processed.';
    }
 // After processing emails, purge 'sent' emails
    $purgeQuery = "DELETE FROM email_queue WHERE status = 'sent'";
    $purgeResult = $db->query($purgeQuery);

    if ($db->affected_rows > 0) {
        $response['message'] .= ' Sent emails purged successfully.';
        error_log('Sent emails purged successfully.');
    } else {
        $response['message'] .= ' No sent emails to purge.';
        error_log('No sent emails to purge.');
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = "An error occurred: " . $e->getMessage();
    error_log($response['message']);
}

echo json_encode($response);
?>
