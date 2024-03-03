<?php
header('Content-Type: application/json');
$data = [];
require 'core/config/config_db.php'; // Include this once at the top

session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    echo "<script type=\"text/javascript\">
            alert('To continue you must log in.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

$session_user = $_SESSION['id'];

// First, check the user's notification preference
$prefSql = "SELECT in_app_noti FROM noti_preference WHERE np_user_id = :session_user";
$prefStmt = $pdo->prepare($prefSql);
$prefStmt->execute([':session_user' => $session_user]);
$prefResult = $prefStmt->fetch(PDO::FETCH_ASSOC);

if ($prefResult && $prefResult['in_app_noti'] == 1) {
    // Fetch user's workflow level if in-app notifications are enabled
    $sql = "SELECT workflows_creator.id AS wcreator_id, workflows.workflow_name, workflows.wsender, workflows_creator.wcreator_name, workflows_creator.wlevel_id AS wlevelId, workflows.id AS workflow_id
            FROM users_by_wcreator
            INNER JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
            LEFT JOIN workflows ON workflows.id = workflows_creator.wcreator_workflows_id
            WHERE ubw_user_id = :session_user";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':session_user' => $session_user]);
    $userWorkflows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($userWorkflows as $workflow) {
        // Determine if the user is a receiver or sender based on their workflow level
        $is_receiver = $workflow['wlevelId'] > 1;
        $data = handleNotifications($pdo, $session_user, $is_receiver);
    }
} else {
    // In-app notifications are disabled, so we do not fetch notifications
    $data = ['message' => 'In-app notifications are disabled.'];
}

echo json_encode($data);

function handleNotifications($pdo, $session_user, $is_receiver) {
    $sql = "SELECT fl_timestamp, workflows.id AS workflow_id, forms_audit_trail.id AS audit_trail_id, form_001.ref_number, forms_audit_trail.actions, form_001.id
        FROM forms_audit_trail
        INNER JOIN form_001 ON form_001.id = forms_audit_trail.fl_forms_id
        LEFT JOIN form_metadata ON form_metadata.id = form_001.form_metadata_id
        INNER JOIN forms_status ON forms_status.forms_id = form_001.id
        INNER JOIN workflows ON workflows.id = form_metadata.fm_workflows_id
        INNER JOIN user_notifications ON user_notifications.notification_id = forms_audit_trail.id
        WHERE user_notifications.is_seen = 0 
        AND user_notifications.user_id = :session_user";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':session_user' => $session_user]);
    $n_numbers = $stmt->rowCount();

    $data = [['total' => $n_numbers]]; // Initialize with total notifications count

    while ($notification = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $notification['is_receiver'] = $is_receiver; // Set based on function parameter
        $data[] = $notification;
    }
    return $data;
}
?>
