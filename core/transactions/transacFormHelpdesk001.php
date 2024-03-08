<?php
include_once '../../core/config/transac_setup.php';
$originatorEmail = $sysRol['user_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workflow_id = $_POST['workflow_id'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $issueType = $_POST['issueType'];
    $issueDescription = $_POST['issueDescription'];

    // Get the workflows_creator.id and form_metadata.id
    $workflowData = getWCreatorAndMetadataId($workflow_id, $db);

    $receiverUser = getReceiverUser($workflow_id, $db);

    if ($workflowData !== false) {
        $workflows_creator_id = $workflowData['wcId'];
        $formMetadataId = $workflowData['fmId'];
        $receiverUserId = $receiverUser['ubw_user_id'];
        $processStatus = "Sent";

        $prefix = "HD";
        // Generate a new reference number
        $refNumber = generateNewReferenceNumber1($prefix, $session_user, $pdo);

        try {
            // Insert data into formHelpDesk001 table
            $stmt = $db->prepare('INSERT INTO formHelpDesk001 (fullName, email, issueType, issueDescription, metadata_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$fullName, $email, $issueType, $issueDescription, $formMetadataId]);

            // Get the last inserted ID from formHelpDesk001
            $form_data_id = mysqli_insert_id($db);

            $currentTimestamp = date('Y-m-d H:i:s');
            // Insert data into forms_status table
            $stmt = $db->prepare('INSERT INTO forms_status (ref_number, fl_sender_user_id, fl_receiver_user_id, process_status, timestamp, forms_id, fs_metadata_id, process_level_id, receiver_division_wcid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$refNumber, $session_user, $receiverUserId, $processStatus, $currentTimestamp, $form_data_id, $formMetadataId, 2, $workflows_creator_id]);

            // Insert record into the forms_audit_trail table
            $stmt = $db->prepare('INSERT INTO forms_audit_trail (actions, fl_user_id, fl_forms_id, fl_timestamp, fl_metadata_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$processStatus, $session_user, $form_id, $currentTimestamp, $formMetadataId]);

            // Retrieve the last insert id correctly using mysqli
            $formAuditTrailId = mysqli_insert_id($db);

            // Insert record for the receiver user into the user_notifications table
            $stmt = $db->prepare('INSERT INTO user_notifications (user_id, notification_id, is_seen) VALUES (?, ?, ?)');
            $stmt->execute([$receiverUserId, $formAuditTrailId, 0]);

            // Insert record for the sender user into the user_notifications table
            $stmt = $db->prepare('INSERT INTO user_notifications (user_id, notification_id, is_seen) VALUES (?, ?, ?)');
            $stmt->execute([$session_user, $formAuditTrailId, 0]);

            // Example to enqueue an email notification
            $emailSubject = "Workflow Notification";
            $emailBody = "The status of your workflow is now: " . $processStatus . ".";
            $recipientEmail = $originatorEmail; // Assuming you're notifying the originator

            $stmt = $db->prepare('INSERT INTO email_queue (recipient_email, email_subject, email_body) VALUES (?, ?, ?)');
            $stmt->execute([$recipientEmail, $emailSubject, $emailBody]);

            // Commit the transaction
            $db->commit();
            
            // Close the database connection
            mysqli_close($db);

            // Redirect to a success page or perform other actions as needed
            header("Location: ../../senderDataTable.php?workflow_id=$workflow_id"); 
            exit();
        } catch (PDOException $e) {
            // Handle database errors or exceptions here
            // You can choose to roll back the transaction if an error occurs
            // ...
            echo "An error occurred: " . $e->getMessage();
        }
    } else {
        // Handle the case when workflows_creator.id is not found
        // You can choose to roll back the transaction or handle it differently
        // For example, you can throw an exception or log an error
        // ...
        echo "Error in getWCreatorAndMetadataId function";
    }
}
?>
