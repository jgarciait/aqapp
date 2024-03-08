<?php
include_once 'core/config/config_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formId']) && isset($_POST['priority'])) {
    $formId = $_POST['formId'];
    $priority = $_POST['priority'];

    // Update priority in the database
    $updateQuery = "UPDATE forms_status SET fs_priority = ? WHERE forms_id = ?";
    $stmt = mysqli_prepare($db, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'si', $priority, $formId);
    
    if (mysqli_stmt_execute($stmt)) {
        $response = array('success' => true);
        echo json_encode($response);
    } else {
        $response = array('success' => false, 'message' => 'Error updating priority in database');
        echo json_encode($response);
    }
} else {
    $response = array('success' => false, 'message' => 'Invalid request');
    echo json_encode($response);
}
?>
