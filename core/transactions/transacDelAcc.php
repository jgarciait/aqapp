<?php

include_once '../../core/config/transac_setup_admin.php';

?>

<?php

// Prepare the SQL statement
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to retrieve user email
    $sql2 = "SELECT user_email FROM users WHERE id = :id";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':id', $id);
    $stmt2->execute();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $uName = $row2['user_email'];

    // Prepare the SQL statement to delete user
    $deleteSql = "DELETE FROM users WHERE id = :id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindParam(':id', $id);

    if ($deleteStmt->execute()) {
        $userId = $session_user;
        $logAction = "$uName - user deleted";
        $timestamp = date('Y-m-d H:i:s');

        // Prepare the SQL statement to insert into audit trails
        $insertLogSql = "INSERT INTO audit_trails (audit_trail_user_id, audit_trail_action, audit_trail_timestamp)
                        VALUES (:userId, :logAction, :timestamp)";
        $logStmt = $pdo->prepare($insertLogSql);
        $logStmt->bindParam(':userId', $userId);
        $logStmt->bindParam(':logAction', $logAction);
        $logStmt->bindParam(':timestamp', $timestamp);
        $logStmt->execute();
    } else {
        echo "Error deleting data.";
    }
}					
?>

<script type="text/javascript">
	alert("User Account Succesfuly Deleted");
	window.location = "../../user_management.php";
</script>				