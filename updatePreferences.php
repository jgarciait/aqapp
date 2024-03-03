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

// Check if the request is an AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['in_app_noti']) && isset($_POST['email_noti'])) {
    // Assuming you have session management in place

    // Sanitize and prepare the data
    $inAppNoti = $_POST['in_app_noti'] ? 1 : 0;
    $emailNoti = $_POST['email_noti'] ? 1 : 0;

    // Update the database
    $sql = "UPDATE noti_preference SET in_app_noti = :inAppNoti, email_noti = :emailNoti WHERE np_user_id = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':inAppNoti' => $inAppNoti, ':emailNoti' => $emailNoti, ':userId' => $session_user));

    // You can send back a response if needed
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Change Saved']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update notification preferences.']);
    }
    exit; // Terminate the script after handling the AJAX request
}
?>
