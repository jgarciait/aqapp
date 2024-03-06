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

$session_user = $_SESSION['id'];
$outgoing_id = $session_user;

$searchTerm = $_POST['searchTerm'];


$sql = "SELECT *
        FROM users
        Where (first_name LIKE ? 
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
$stmt->bind_param("sssss", $likePattern, $likePattern, $likePattern, $likePattern, $likePattern);

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