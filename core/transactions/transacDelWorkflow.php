<?php
include_once '../../core/config/transac_setup_admin.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    
    $id = $_GET['id'];

    // Check if workflows_creator table has related entries
    $check_query = "SELECT COUNT(*) as count, wcreator_name FROM workflows_creator WHERE wcreator_workflows_id = :id";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->bindParam(':id', $id);
    $check_stmt->execute();
    $row = $check_stmt->fetch(PDO::FETCH_ASSOC);
    $creator_count = $row['count'];
    
    
    if ($creator_count > 0) {
        // Workflows Creator Table is not empty
        // Redirect to workflowsList.php with modal message
        header("Location: workflowsList.php?modal_message=" . urlencode("El módulo contiene procesos y/o usuarios. En este momento no puede eliminar el mismo."));
        exit;
    } else {
        // Workflows Creator Table is empty, proceed with deletion
        
        // Prepare the SQL statement
        $delete_sql = "DELETE FROM workflows WHERE id = :id";

        // Bind the value to the parameter in the SQL statement
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->bindParam(':id', $id);
         
        $sql2 = "SELECT workflow_name FROM workflows WHERE id = :id";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindParam(':id', $id);
        $stmt2->execute();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $wName = $row2['workflow_name'];

        // Execute the SQL statement
        if ($delete_stmt->execute()) {

            $userId = $session_user;
            $logAction = "$wName - workflow deleted";
            $timestamp = date('Y-m-d H:i:s');

            $insertLogSql = "INSERT INTO audit_trails (audit_trail_user_id, audit_trail_action, audit_trail_timestamp)
                            VALUES (:userId, :logAction, :timestamp)";
            $stmt = $pdo->prepare($insertLogSql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':logAction', $logAction);
            $stmt->bindParam(':timestamp', $timestamp);
            $stmt->execute();

        } else {
            echo "Error deleting data.";
        }
    }
} else {
    echo "Invalid workflow ID.";
}

?>
<script type="text/javascript">
	alert("Module Successfully Deleted");
	window.location = "../../modulesList.php?success=true";
</script>			
