<?php
include_once '../../core/config/transac_setup.php';
$originatorEmail = $sysRol['user_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workflow_id = $_POST['workflow_id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $service_request = $_POST['service_request'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $physical_address = $_POST['physical_address'];
    $postal_address = $_POST['postal_address'];
    $sector = $_POST['sector'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Get the workflows_creator.id and form_metadata.id
    $workflowData = getWCreatorAndMetadataId($workflow_id, $db);

    $receiverUser = getReceiverUser($workflow_id, $db);

    if ($workflowData !== false) {
        $workflows_creator_id = $workflowData['wcId'];
        $formMetadataId = $workflowData['fmId'];
        $receiverUserId = $receiverUser['ubw_user_id'];
        $processStatus = "Sent";

        // Generate a new reference number
        $refNumber = generateNewReferenceNumber($session_user, $pdo);

        try {
            // Insert data into form_001 table
            $stmt = $db->prepare('INSERT INTO form_001 (firstName, lastName, age, gender, service_request, form_metadata_id, ref_number, physical_address, postal_address, sector, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$firstName, $lastName, $age, $gender, $service_request, $formMetadataId, $refNumber, $physical_address, $postal_address, $sector, $phone, $email]);

            // Get the last inserted ID from form_001 using mysqli_insert_id()
            $form_id = mysqli_insert_id($db);
            
            $currentTimestamp = date('Y-m-d H:i:s');
            // Insert data into forms_status table
            $stmt = $db->prepare('INSERT INTO forms_status (fl_sender_user_id, fl_receiver_user_id, process_status, timestamp, forms_id, process_level_id, receiver_division_wcid) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$session_user, $receiverUserId, $processStatus, $currentTimestamp, $form_id, 2, $workflows_creator_id]);

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
