<?php

include_once '../../core/config/transac_setup.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $workflow_name = $_POST['workflow_name'];
    $wsender = $_POST['wsender'];
    $wtype = $_POST['wtype_id'];
     
    // Validate and sanitize the inputs if needed

    // Perform the database insertion
    mysqli_set_charset($db, "utf8");
    $insert_query = "INSERT INTO workflows (workflow_name, wsender, wtype_id)
                     VALUES ('$workflow_name', '$wsender', '$wtype')";
    
    if ($db->query($insert_query) === TRUE) {
        $userId = $session_user;
        $logAction = "$workflow_name - workflow added";
        $timestamp = date('Y-m-d H:i:s');

        $insertLogSql = "INSERT INTO audit_trails (audit_trail_user_id, audit_trail_action, audit_trail_timestamp)
                            VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($db, $insertLogSql);
        mysqli_stmt_bind_param($stmt, "iss", $userId, $logAction, $timestamp);
        mysqli_stmt_execute($stmt);

         echo "<script type=\"text/javascript\">
            alert('Module Successfully Created');
            window.location.href = '../../modulesList.php';
        </script>";
        exit();
    } else {
        // Handle database insertion error
        $error_message = "Error: " . $db->error;
    }
}

?>
