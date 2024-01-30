<?php

include_once '../../core/config/transac_setup_admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $workflow_id = $_POST['wcreator_workflows_id'];
    $area_name = $_POST['wcreator_name'];
    $description = $_POST['wcreator_description'];
    $approval_level = $_POST['wlevel_id'];

    // Validate and sanitize the inputs if needed

    // Perform the database insertion
    mysqli_set_charset($db, "utf8");
    $insert_query = "INSERT INTO workflows_creator (wcreator_name, wcreator_description, wlevel_id, wcreator_workflows_id)
                     VALUES ('$area_name', '$description', '$approval_level', '$workflow_id')";

    if ($db->query($insert_query) === TRUE) {
        // Redirect to success page or show a success message
        $userId = $session_user;
        $logAction = "$area_name - position added";
        $timestamp = date('Y-m-d H:i:s');

        $insertLogSql = "INSERT INTO audit_trails (audit_trail_user_id, audit_trail_action, audit_trail_timestamp)
                            VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($db, $insertLogSql);
        mysqli_stmt_bind_param($stmt, "iss", $userId, $logAction, $timestamp);
        mysqli_stmt_execute($stmt);

        echo "<script type=\"text/javascript\">
            alert('Position Successfully Created');
            window.location.href = '../../workflowsByType.php?id={$workflow_id}';
        </script>";
        exit();
    } else {
        // Handle database insertion error
        $error_message = "Error: " . $db->error;
    }
}

?>
