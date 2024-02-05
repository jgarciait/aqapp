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
                $processStatus = "Approved";
            } elseif ($action === "revert") {
                // Decrease the process level by 1 for revert
                $formLevel_Id--;
                $processStatus = "Reverted";
            } elseif ($action === "reject") {
                // Decrease the process level by 1 for rejection
                $formLevel_Id--;
                $processStatus = "Rejected";
            }

            // Update process_level_id and process_status in the forms_log table
            $stmt = $db->prepare('UPDATE forms_log SET process_level_id = ?, process_status = ? WHERE forms_id = ?');
            $stmt->execute([$formLevel_Id, $processStatus, $form_id]);

            // Commit the transaction
            $db->commit();

            // Close the database connection
            mysqli_close($db);

            // Redirect to a success page or perform other actions as needed
            header('Location: ../../socialHome.php');
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