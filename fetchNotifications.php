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

// Fetch user's workflow level
$sql = "SELECT workflows_creator.id AS wcreator_id, workflows.workflow_name, workflows.wsender, workflows_creator.wcreator_name, workflows_creator.wlevel_id AS wlevelId, workflows.id AS workflow_id
        FROM users_by_wcreator
        INNER JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
        LEFT JOIN workflows ON workflows.id = workflows_creator.wcreator_workflows_id
        WHERE ubw_user_id = :session_user";

$stmt = $pdo->prepare($sql);
$stmt->execute([':session_user' => $session_user]);
$userWorkflows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Assuming $userWorkflows contains all related workflow info for the user
foreach ($userWorkflows as $workflow) {
    if ($workflow['wlevelId'] > 1) {
        $data = handleReceiverNotifications($pdo, $workflow['wcreator_id']);
    } else {
        $data = handleSenderNotifications($pdo, $session_user);
    }
}

echo json_encode($data);

// Function to handle notifications for users that send requests
function handleSenderNotifications($pdo, $session_user) {
    $sql = "SELECT fl_timestamp, workflows.id AS workflow_id, forms_audit_trail.id AS audit_trail_id, form_001.ref_number, forms_audit_trail.actions, form_001.id
                FROM forms_audit_trail
                INNER JOIN form_001 ON form_001.id = forms_audit_trail.fl_forms_id
                LEFT JOIN form_metadata ON form_metadata.id = form_001.form_metadata_id
                INNER JOIN forms_status ON forms_status.forms_id = form_001.id
                INNER JOIN workflows ON workflows.id = form_metadata.fm_workflows_id
                WHERE forms_audit_trail.is_seen = 0
            AND forms_status.fl_sender_user_id = :session_user";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':session_user' => $session_user]);
    $n_numbers = $stmt->rowCount();

    $data[] = ['total' => $n_numbers];

    while ($notification = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $notification;
        return $data;
};
}

// Function to handle notifications for users that receive requests
function handleReceiverNotifications($pdo, $wcId) {
    $sql = "SELECT fl_timestamp, workflows.id AS workflow_id, forms_audit_trail.id AS audit_trail_id, form_001.ref_number, forms_audit_trail.actions, form_001.id
            FROM forms_audit_trail
            INNER JOIN form_001 ON form_001.id = forms_audit_trail.fl_forms_id
            LEFT JOIN form_metadata ON form_metadata.id = form_001.form_metadata_id
            INNER JOIN forms_status ON forms_status.forms_id = form_001.id
            INNER JOIN workflows ON workflows.id = form_metadata.fm_workflows_id
            WHERE forms_audit_trail.is_seen = 0
            AND forms_status.receiver_division_wcid = :wcId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':wcId' => $wcId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
