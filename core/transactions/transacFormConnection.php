<?php

include_once '../../core/config/transacLoginSetup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workflow_id = isset($_POST['fm_workflows_id']) ? $_POST['fm_workflows_id'] : '';
    $tableName = isset($_POST['table_name']) ? $_POST['table_name'] : '';
    $formName = isset($_POST['form_name']) ? $_POST['form_name'] : '';

    if (!empty($workflow_id) && !empty($tableName) && !empty($formName)) {

        // Use prepared statement to insert data
        $stmt = $db->prepare("INSERT INTO form_metadata (fm_workflows_id, table_name, form_name) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $workflow_id, $tableName, $formName);

        if ($stmt->execute()) {
            $user_Id = $session_user;
            $logAction = "$tableName - table name added";
            $timestamp = date('Y-m-d H:i:s');

            $insertLogSql = "INSERT INTO audit_trails (audit_trail_user_id, audit_trail_action, audit_trail_timestamp)
                                VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $insertLogSql);
            mysqli_stmt_bind_param($stmt, "iss", $user_Id, $logAction, $timestamp);
            mysqli_stmt_execute($stmt);

            header("Location: ../../form_management.php?submitted=successfully");
            exit();
        } else {
            echo "Error: " . $stmt->error;
            header("Location: ../../login.php?error=database");
            exit();
        }
    } else {
        header("Location: ../../login.php?error=empty");
        exit();
    }
}
?>
