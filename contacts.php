<?php
include 'core/config/config_db.php';
include 'core/assets/util/functions.php';

session_start();



if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    echo "<script type=\"text/javascript\">
            alert('To continue you must log in.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

$session_user = $_SESSION['id'];
$outgoing_id = $session_user;

$userData = getWorkflowIdByUserId($session_user, $db);

$wId = $userData['workflows_id'];
;
// $_SESSION['id']; // This line seems redundant and has no effect.

$sql = "SELECT *, users.id AS user_id
        FROM users
        INNER JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = users.id
        LEFT JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
        WHERE users.id != ?
        AND workflows_creator.wcreator_workflows_id = ?";

$stmt = $db->prepare($sql);
// Ensure that the $stmt is successfully created.
if (!$stmt) {
    die('Prepare failed: ' . $db->error);
}

// Bind the parameter before executing the statement.
$stmt->bind_param("ii", $session_user, $wId);

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

$result = $stmt->get_result();
$output = '';

if ($result->num_rows == 0) {
    $output .= "No users are available to chat with.";
} else {
    include "contactsData.php";
}
echo $output;
?>
