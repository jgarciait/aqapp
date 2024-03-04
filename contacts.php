<?php
include 'core/config/config_db.php';

session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    echo "<script type=\"text/javascript\">
            alert('To continue you must log in.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

// $_SESSION['id']; // This line seems redundant and has no effect.

$sql = "SELECT *
        FROM users
        WHERE id != ?";

$stmt = $db->prepare($sql);
// Ensure that the $stmt is successfully created.
if (!$stmt) {
    die('Prepare failed: ' . $db->error);
}

// Bind the parameter before executing the statement.
$stmt->bind_param("i", $_SESSION['id']);

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
