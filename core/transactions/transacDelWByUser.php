<?php
include_once '../../core/config/transac_setup_admin.php';
?>

<body>

<?php

// Create a PDO instance to connect to the database

// Prepare the SQL statement
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to retrieve user email and workflow ID
    $sql2 = "SELECT user_email, wcreator_name, wcreator_workflows_id FROM users_by_wcreator
    INNER JOIN users ON users.id = users_by_wcreator.ubw_user_id
    INNER JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
    WHERE users_by_wcreator.id = :id";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':id', $id);
    $stmt2->execute();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    
    // Define $uName, $uProcess, and $workflowId here
    $uName = $row2['user_email'];
    $uProcess = $row2['wcreator_name'];
    $workflowId = $row2['wcreator_workflows_id'];

    // Prepare the SQL statement to retrieve related records in users_by_workflows
    $selectRelatedSql = "SELECT id FROM users_by_workflows WHERE ubw_workflow_id = :workflowId";
    $selectRelatedStmt = $pdo->prepare($selectRelatedSql);
    $selectRelatedStmt->bindParam(':workflowId', $workflowId);
    $selectRelatedStmt->execute();
    $relatedRecords = $selectRelatedStmt->fetchAll(PDO::FETCH_ASSOC);

    // ... Other code ...

    try {
        $pdo->beginTransaction();
        
        // Delete related records in users_by_workflows first
        foreach ($relatedRecords as $relatedRecord) {
            $deleteRelatedSql = "DELETE FROM users_by_workflows WHERE id = :relatedId";
            $deleteRelatedStmt = $pdo->prepare($deleteRelatedSql);
            $deleteRelatedStmt->bindParam(':relatedId', $relatedRecord['id']);
            $deleteRelatedStmt->execute();
        }
        
        // Now, delete the user from users_by_wcreator
        $deleteUserSql = "DELETE FROM users_by_wcreator WHERE id = :id";
        $deleteUserStmt = $pdo->prepare($deleteUserSql);
        $deleteUserStmt->bindParam(':id', $id);

        if ($deleteUserStmt->execute()) {
            $userId = $session_user;
            $logAction = "Connection between $uName user and $uProcess";
            $timestamp = date('Y-m-d H:i:s');

            // Prepare the SQL statement to insert into audit trails
            $insertLogSql = "INSERT INTO audit_trails (audit_trail_user_id, audit_trail_action, audit_trail_timestamp)
                            VALUES (:userId, :logAction, :timestamp)";
            $logStmt = $pdo->prepare($insertLogSql);
            $logStmt->bindParam(':userId', $userId);
            $logStmt->bindParam(':logAction', $logAction);
            $logStmt->bindParam(':timestamp', $timestamp);
            $logStmt->execute();
            
            $pdo->commit();
        } else {
            echo "Error deleting data.";
        }
    } catch (PDOException $e) {
        // Handle any database errors and roll back the transaction on error
        $pdo->rollBack();
        echo "Database error: " . $e->getMessage();
    }
}
?>

<script type="text/javascript">
    var workflowId = <?php echo $workflowId; ?>;
    alert("User Succesfully Removed From Module");
    window.location = "../../workflowsByUser.php?id=" + workflowId;
</script>
</body>
</html>
