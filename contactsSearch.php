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

$searchTerm = $_POST['searchTerm'];

$userData = getWorkflowIdByUserId($session_user, $db);

$wId = $userData['workflows_id'];

$sql = "SELECT *, users.id AS user_id
        FROM users
        INNER JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = users.id
        LEFT JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
        WHERE users.id != ?
        AND workflows_creator.wcreator_workflows_id = ?
        AND (first_name LIKE ? 
        OR last_name LIKE ? 
        OR user_email LIKE ? 
        OR status LIKE ? 
        OR position_title LIKE ?)";

$stmt = $db->prepare($sql);
// Ensure that the $stmt is successfully created.
if (!$stmt) {
    die('Prepare failed: ' . $db->error);
}

// Prepare the LIKE pattern
$likePattern = '%' . $searchTerm . '%';

// Bind the parameter before executing the statement.
$stmt->bind_param("iisssss", $_SESSION['id'], $wId, $likePattern, $likePattern, $likePattern, $likePattern, $likePattern);

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

$result = $stmt->get_result();

$output = '';
// Check if any users were found
if ($result->num_rows > 0) {
        include "contactsData.php";
} else {
    // No users found related to the search term
    $output .= "No users found related to your search.";
}
echo $output;
?>