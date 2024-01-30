<?php
// Include necessary files and configurations
include_once '../../core/config/transac_setup.php';

// Get the user email from the $sysRol array
$userEmail = $sysRol['user_email'];

// Check if the HTTP request is a POST request and if required POST parameters are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_name'], $_POST['request_description'], $_POST['r_workflow_id'])) {
    // Retrieve POST data
    $requestName = $_POST['request_name'];
    $requestDescription = $_POST['request_description'];
    $workflowsId = $_POST['r_workflow_id'];
    $requestUsers = $_SESSION['id'];
    $shiftId = $_POST['rshift_start_id'];

    // Define the initial request status and generate a reference number
    $requestStatus = "Pendiente a Procesar";
    $referenceNumber = getLastReferenceNumber($pdo); // Instead of generating a random number
    $requestTimeStamp = date('Y-m-d H:i:s');

    // Step 1: Check if the user is associated with the specified workflow
    $sqlCheckUser = "SELECT wcreator_id
    FROM users_by_wcreator
    INNER JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
    WHERE ubw_user_id = :ubw_user_id AND wcreator_workflows_id = :wcreator_workflows_id";
    $stmtCheckUser = $pdo->prepare($sqlCheckUser);
    $stmtCheckUser->bindParam(':ubw_user_id', $requestUsers);
    $stmtCheckUser->bindParam(':wcreator_workflows_id', $workflowsId);

    if ($stmtCheckUser->execute() && $row = $stmtCheckUser->fetch(PDO::FETCH_ASSOC)) {
        // Step 2: Get the current wlevel_id associated with the user
        $wcreatorId = $row['wcreator_id'];
        $sqlGetWlevel = "SELECT wlevel_id, id FROM workflows_creator WHERE id = :id";
        $stmtGetWlevel = $pdo->prepare($sqlGetWlevel);
        $stmtGetWlevel->bindParam(':id', $wcreatorId);

        if ($stmtGetWlevel->execute() && $row = $stmtGetWlevel->fetch(PDO::FETCH_ASSOC)) {
            // Step 3: Calculate the new wlevel_id
            $currentWlevelId = $row['wlevel_id'];
            $newWlevelId = $currentWlevelId + 1;

            // Step 4: Find the matching ID for the new wlevel_id
            $sqlFindId = "SELECT id FROM workflows_creator WHERE wcreator_workflows_id = :wcreator_workflows_id AND wlevel_id = :new_wlevel_id";
            $stmtFindId = $pdo->prepare($sqlFindId);
            $stmtFindId->bindParam(':new_wlevel_id', $newWlevelId);
            $stmtFindId->bindParam(':wcreator_workflows_id', $workflowsId);

            if ($stmtFindId->execute() && $row = $stmtFindId->fetch(PDO::FETCH_ASSOC)) {
                // Step 5: Insert request information into the 'requests' table
                $matchingId = $row['id'];
                $sqlRequests = "INSERT INTO requests (id, ref_request_num, request_name, request_description, request_status, r_workflow_id, r_wcreator_id, requester_user_id, request_timestamp, rshift_start_id) VALUES (NULL, :ref_request_num, :request_name, :request_description, :request_status, :r_workflow_id, :r_wcreator_id, :requester_user_id, :request_timestamp, :rshift_start_id)";
                $stmtRequests = $pdo->prepare($sqlRequests);
                $stmtRequests->bindParam(':ref_request_num', $referenceNumber);
                $stmtRequests->bindParam(':request_name', $requestName);
                $stmtRequests->bindParam(':request_description', $requestDescription);
                $stmtRequests->bindParam(':request_status', $requestStatus);
                $stmtRequests->bindParam(':r_workflow_id', $workflowsId);
                $stmtRequests->bindParam(':r_wcreator_id', $matchingId);
                $stmtRequests->bindParam(':requester_user_id', $requestUsers);
                $stmtRequests->bindParam(':request_timestamp', $requestTimeStamp);
                $stmtRequests->bindParam(':rshift_start_id', $shiftId);

                if ($stmtRequests->execute()) {
                    // Step 6: Insert uploaded files into the 'request_files' table (if any)
                    $lastRequestId = $pdo->lastInsertId();
                    $workflow_id = $_POST['r_workflow_id'];
                    $success = true;

                    if (!empty($_FILES['file_upload']['name'][0])) {
                        $fileUploads = $_FILES['file_upload'];
                        $uploadDirectory = 'uploads/';

                        foreach ($fileUploads['name'] as $key => $fileName) {
                            $fileSize = $fileUploads['size'][$key];
                            $filePath = $uploadDirectory . $fileName;

                            if (move_uploaded_file($fileUploads['tmp_name'][$key], $filePath)) {
                                $sqlInsertFile = "INSERT INTO request_files (filesname, file_size, file_path, request_id) VALUES (:filesname, :file_size, :file_path, :request_id)";
                                $stmtInsertFile = $pdo->prepare($sqlInsertFile);
                                $stmtInsertFile->bindParam(':filesname', $fileName);
                                $stmtInsertFile->bindParam(':file_size', $fileSize);
                                $stmtInsertFile->bindParam(':file_path', $filePath);
                                $stmtInsertFile->bindParam(':request_id', $lastRequestId);

                                if (!$stmtInsertFile->execute()) {
                                    echo "Error inserting file information into the database.";
                                }
                            } else {
                                echo "Error moving the uploaded file to the destination folder.";
                            }
                        }
                    }

                    // Step 7: Redirect to a new page if successful
                    if ($success) {
                        header("Location: ../../newAttendance.php?workflow_id=$workflow_id&success=true&user_email=" . urlencode($userEmail)); 
                    }
                } else {
                    echo "Error inserting data into requests table.";
                }
            } else {
                echo "Error finding matching id for new wlevel_id.";
                // Add debugging output to see the values of $newWlevelId and $workflowsId
                echo "newWlevelId: $newWlevelId<br>";
                echo "workflowsId: $workflowsId<br>";
            }
        } else {
            echo "Error retrieving wlevel_id.";
        }
    } else {
        echo "User not found in users_by_wcreator table.";
    }
}
?>
