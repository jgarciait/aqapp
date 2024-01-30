<?php

include_once '../../core/config/transac_setup_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uId = isset($_POST['ubw_user_id']) ? $_POST['ubw_user_id'] : '';
    $wId = isset($_POST['wcreator_id']) ? $_POST['wcreator_id'] : '';
    $wsId = isset($_POST['ubw_workflow_id']) ? $_POST['ubw_workflow_id'] : '';
    
    // Validate if $uId and $wId have values, then proceed with insertion
    if (!empty($uId) && !empty($wId) && !empty($wsId)) {
        try {
            // Use prepared statements to insert data
            $stmt1 = $db->prepare("INSERT INTO users_by_wcreator (ubw_user_id, wcreator_id) VALUES (?, ?)");
            $stmt1->bind_param("ii", $uId, $wId);
            $stmt1->execute();

            $stmt2 = $db->prepare("INSERT INTO users_by_workflows (workflow_user_id, ubw_workflow_id) VALUES (?, ?)");
            $stmt2->bind_param("ii", $uId, $wsId);
            $stmt2->execute();
            
            // Your audit trail and other logic
            echo "<script type=\"text/javascript\">
                alert('User Successfully Added to Position');
                window.location.href = '../../workflowsByUser.php?id={$wsId}';
            </script>";
            exit();
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        header("Location: ../../modulesList.php?error=empty");
        exit();
    }
}
?>
