<?php
header('Content-Type: application/json');
$data = [];
//Verify Session
session_start();

// Check if the user is not logged in and redirect to the login page
if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    echo "<script type=\"text/javascript\">
        alert('To continue you must log in.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

$session_user = $_SESSION['id'];

// Check if $_POST with key '123' is set
if (isset($_POST['key']) && ($_POST['key'] === '123')) {
    require 'core/config/config_db.php'; // Assuming correct path

$sql = "SELECT MAX(forms_audit_trail.fl_timestamp) AS last_update, workflows.id AS workflow_id, forms_audit_trail.id AS audit_trail_id, form_001.ref_number, forms_audit_trail.actions, form_001.id
FROM forms_audit_trail
INNER JOIN form_001 ON form_001.id = forms_audit_trail.fl_forms_id
LEFT JOIN form_metadata ON form_metadata.id = form_001.form_metadata_id
INNER JOIN forms_status ON forms_status.forms_id = form_001.id
INNER JOIN workflows ON workflows.id = form_metadata.fm_workflows_id
WHERE forms_audit_trail.is_seen = 0
AND forms_status.fl_sender_user_id = :session_user
GROUP BY form_001.id
ORDER BY last_update DESC;
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':session_user', $session_user, PDO::PARAM_INT);
$stmt->execute();
$n_numbers = $stmt->rowCount();

$data[] = ['total' => $n_numbers];

while ($notification = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $notification;
}

echo json_encode($data);

}

?>
