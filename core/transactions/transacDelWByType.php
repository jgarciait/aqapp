<?php
include_once '../../core/config/transac_setup_admin.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $session_user = $_SESSION['id'];

    // Retrieve the wcreator_workflows_id from the database
    $query = "SELECT wcreator_workflows_id, wcreator_name FROM workflows_creator WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $wcreator_workflows_id = $result['wcreator_workflows_id'];
        $wcreatorName = $result['wcreator_name'];

        // Prepare the SQL statement
        $sql = "DELETE FROM workflows_creator WHERE id = :id";

        // Bind the value to the parameter in the SQL statement
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        // Execute the SQL statement
        if ($stmt->execute()) {
            $userId = $session_user;
            $logAction = "$wcreatorName - process deleted";
            $timestamp = date('Y-m-d H:i:s');

            $insertLogSql = "INSERT INTO audit_trails (audit_trail_user_id, audit_trail_action, audit_trail_timestamp)
                                VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $insertLogSql);
            mysqli_stmt_bind_param($stmt, "iss", $userId, $logAction, $timestamp);
            mysqli_stmt_execute($stmt);

           
        } else {
            echo "Error deleting data.";
        }
    } else {
        echo "Invalid workflow ID.";
    }
}
?>
<script type="text/javascript">
   
    var  $wcreator_workflows_id = <?php echo  $wcreator_workflows_id; ?>;
    alert("Position Successfully Deleted");
    window.location = "../../workflowsByType.php?id=" +  $wcreator_workflows_id;
</script>
