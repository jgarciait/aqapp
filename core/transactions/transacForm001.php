<?php
include_once '../../core/config/transac_setup.php';

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

    if ($workflowData !== false) {
        $workflows_creator_id = $workflowData['wcId'];
        $formMetadataId = $workflowData['fmId'];
        $processStatus = "Sent";

        // Generate a new reference number
        $refNumber = generateNewReferenceNumber($session_user, $pdo);

        try {
            // Insert data into form_001 table
            $stmt = $db->prepare('INSERT INTO form_001 (firstName, lastName, age, gender, service_request, form_metadata_id, ref_number, physical_address, postal_address, sector, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$firstName, $lastName, $age, $gender, $service_request, $formMetadataId, $refNumber, $physical_address, $postal_address, $sector, $phone, $email]);

            // Get the last inserted ID from form_001 using mysqli_insert_id()
            $form_001_id = mysqli_insert_id($db);

            // Insert data into forms_log table
            $stmt = $db->prepare('INSERT INTO forms_log (fl_sender_user_id, process_status, forms_id, process_level_id, receiver_division_wcid) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$session_user, $processStatus, $form_001_id, 2, $workflows_creator_id]);

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
}
?>
