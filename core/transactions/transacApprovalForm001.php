<?php
include_once '../../core/config/transac_setup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workflow_id = $_POST['workflow_id'];
    $formLevel_Id = $_POST['form_level_id'];
    $form_id = $_POST['formId'];
    $action = $_POST['action'];

    try {
        // Update process_level_id and process_status based on the action
        if ($action === "approve") {
            // Increase the process level by 1 for approval
            $formLevel_Id++;
            $workflowData = getLevelWcreateId($formLevel_Id, $workflow_id, $db);
            if ($workflowData === false) {
                // Process is complete, handle accordingly
                $processStatus = "Completed";
                $formLevel_Id = 1;
            } else {
                // Process is ongoing, update status accordingly
                $statusSenderName = getSenderUser($session_user, $workflow_id, $db);
                $receiverDivisionId = $workflowData['wcId'];
                $processStatus = "Approved by " . $statusSenderName['sender_division_name'];
            }
        } elseif ($action === "revert") {
            // Decrease the process level by 1 for revert
            $formLevel_Id--;
            $workflowData = getLevelWcreateId($formLevel_Id, $workflow_id, $db);
            if ($workflowData === false) {
                // Process is complete, handle accordingly
                $processStatus = "Completed";
            } else {
                // Process is ongoing, update status accordingly
                $statusSenderName = getSenderUser($session_user, $workflow_id, $db);
                $receiverDivisionId = $workflowData['wcId'];
                $processStatus = "Reverted by " . $statusSenderName['sender_division_name'];
            }
        } elseif ($action === "reject") {
            // Decrease the process level by 1 for rejection
            $formLevel_Id--;
            $workflowData = getLevelWcreateId($formLevel_Id, $workflow_id, $db);
            if ($workflowData === false) {
                // Process is complete, handle accordingly
                $processStatus = "Completed";
            } else {
                // Process is ongoing, update status accordingly
                $statusSenderName = getSenderUser($session_user, $workflow_id, $db);
                $receiverDivisionId = $workflowData['wcId'];
                $processStatus = "Rejected by " . $statusSenderName['sender_division_name'];
            }
        }
        date_default_timezone_set('America/Puerto_Rico');
        $currentTimestamp = date('Y-m-d H:i:s');

        // Corrected the order of parameters in the execute() method to match the prepared statement
        $stmt = $db->prepare('UPDATE forms_status SET process_level_id = ?, process_status = ?, receiver_division_wcid = ?, timestamp = ? WHERE forms_id = ?');
        $stmt->execute([$formLevel_Id, $processStatus, $receiverDivisionId, $currentTimestamp, $form_id]);


        // Insert record into the forms_audit_trail table
        
        $stmt = $db->prepare('INSERT INTO forms_audit_trail (actions, fl_user_id, fl_forms_id, fl_timestamp) VALUES (?, ?, ?, ?)');
        $stmt->execute([$processStatus, $session_user, $form_id, $currentTimestamp]);

        // Commit the transaction
        $db->commit();

        // Close the database connection
        mysqli_close($db);

        // Redirect to a success page or perform other actions as needed
        header('Location: ../../receiverDataTable.php?workflow_id=' . $workflow_id);
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

?>