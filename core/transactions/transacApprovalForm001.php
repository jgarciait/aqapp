<?php
include_once '../../core/config/transac_setup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workflow_id = $_POST['workflow_id'];
    $formLevel_Id = $_POST['form_level_id'];
    $form_id = $_POST['formId'];
    $action = $_POST['action'];
    
    // Initialize a variable to track success
    $isSuccessful = false;

    try {
        // Update process_level_id and process_status based on the action
        if ($action === "approve") {
            // Increase the process level by 1 for approval
            $formLevel_Id++;
            $originatorUser = getOriginatorUser($form_id, $db);
            $workflowData = getLevelWcreateId($formLevel_Id, $workflow_id, $db);
            if ($workflowData === false) {
                // Process is complete, handle accordingly
                $processStatus = "Completed";
                $originatorUserId = $originatorUser['oUserId'];
                $originatorEmail = $originatorUser['user_email'];
                $formLevel_Id = 1;
            } else {
                // Process is ongoing, update status accordingly
                $statusSenderName = getSenderUser($session_user, $workflow_id, $db);
                $receiverDivisionId = $workflowData['wcId'];
                $receiverUserId = $workflowData['userId'];
                $originatorUserId = $originatorUser['oUserId'];
                $originatorEmail = $originatorUser['user_email'];
                $processStatus = "Approved by " . $statusSenderName['sender_division_name'];
            }
        } elseif ($action === "revert") {
            // Decrease the process level by 1 for revert
            $formLevel_Id--;
            $originatorUser = getOriginatorUser($form_id, $db);
            $workflowData = getLevelWcreateId($formLevel_Id, $workflow_id, $db);
            if ($workflowData === false) {
                // Process is complete, handle accordingly
                $processStatus = "Completed";
            } else {
                // Process is ongoing, update status accordingly
                $statusSenderName = getSenderUser($session_user, $workflow_id, $db);
                $receiverDivisionId = $workflowData['wcId'];
                $originatorUserId = $originatorUser['oUserId'];
                $receiverUserId = $workflowData['userId'];
                $originatorEmail = $originatorUser['user_email'];
                $processStatus = "Reverted by " . $statusSenderName['sender_division_name'];
            }
        } elseif ($action === "reject") {
            // Decrease the process level by 1 for rejection
            $formLevel_Id = 1;
            $originatorUser = getOriginatorUser($form_id, $db);
            $workflowData = getLevelWcreateId($formLevel_Id, $workflow_id, $db);
            if ($workflowData === false) {
                // Process is complete, handle accordingly
                $processStatus = "Completed";
            } else {
                // Process is ongoing, update status accordingly
                $statusSenderName = getSenderUser($session_user, $workflow_id, $db);
                $originatorUserId = $originatorUser['oUserId'];
                $originatorEmail = $originatorUser['user_email'];
                $processStatus = "Rejected by " . $statusSenderName['sender_division_name'];
            }
        }
        date_default_timezone_set('America/Puerto_Rico');
        $currentTimestamp = date('Y-m-d H:i:s');

        // Corrected the order of parameters in the execute() method to match the prepared statement
        $stmt = $db->prepare('UPDATE forms_status SET process_level_id = ?, process_status = ?, fl_receiver_user_id = ?, receiver_division_wcid = ?, timestamp = ? WHERE forms_id = ?');
        $stmt->execute([$formLevel_Id, $processStatus, $receiverUserId, $receiverDivisionId, $currentTimestamp, $form_id]);
        
        // Insert record into the forms_audit_trail table
        $stmt = $db->prepare('INSERT INTO forms_audit_trail (actions, fl_user_id, fl_forms_id, fl_timestamp) VALUES (?, ?, ?, ?)');
        $stmt->execute([$processStatus, $session_user, $form_id, $currentTimestamp]);

        // Retrieve the last insert id correctly using mysqli
        $formAuditTrailId = mysqli_insert_id($db);

        // Insert record for the receiver user into the user_notifications table
        $stmt = $db->prepare('INSERT INTO user_notifications (user_id, notification_id, is_seen) VALUES (?, ?, ?)');
        $stmt->execute([$receiverUserId, $formAuditTrailId, 0]);

        // Insert record for the sender user into the user_notifications table
        $stmt = $db->prepare('INSERT INTO user_notifications (user_id, notification_id, is_seen) VALUES (?, ?, ?)');
        $stmt->execute([$session_user, $formAuditTrailId, 0]);

        // Insert record for the originator user into the user_notifications table
        $stmt = $db->prepare('INSERT INTO user_notifications (user_id, notification_id, is_seen) VALUES (?, ?, ?)');
        $stmt->execute([$originatorUserId, $formAuditTrailId, 0]);

        // Example to enqueue an email notification
        $emailSubject = "Form Status Update - AQPlatform Notification System";
        $emailBody = "The status of your workflow is now: " . $processStatus . ".";
        $recipientEmail = $originatorEmail; // Assuming you're notifying the originator

        $stmt = $db->prepare('INSERT INTO email_queue (recipient_email, email_subject, email_body) VALUES (?, ?, ?)');
        $stmt->execute([$recipientEmail, $emailSubject, $emailBody]);


         // Assuming all operations were successful up to this point
        $isSuccessful = true;
        
        $db->commit(); // Commit only if all operations were successful
        
    } catch (PDOException $e) {
        $db->rollBack(); // Rollback transaction on error
        $isSuccessful = false; // Mark as unsuccessful
    }

    $_SESSION['formSubmittedSuccessfully'] = $isSuccessful;

    // Redirect to receiverDataTable.php with the workflow_id as a GET parameter
    header("Location: ../../receiverDataTable.php?workflow_id=$workflow_id");
    exit();
} else {
    // If the request method is not POST, redirect or show an error
    header("Location: ../../home.php"); // Adjust as needed
    exit();
}
?>
