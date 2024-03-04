<?php
date_default_timezone_set('America/Puerto_Rico');
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

$outgoing_id = $_POST['outgoing_id'];
$incoming_id = $_POST['incoming_id'];
$message = $_POST['msg'];

if(!empty($message)){
        $sql = "INSERT INTO  aq_messages (incoming_msg_id, outgoing_msg_id, msg, msg_timestamp)
            VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: ' . $db->error);
    }
    $stmt->bind_param("iiss", $incoming_id, $outgoing_id, $message, date('Y-m-d H:i:s'));
    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }
}

?>