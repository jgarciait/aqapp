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

$outgoing_id = $_POST['outgoing_id'];
$incoming_id = $_POST['incoming_id'];
$output = '';

$sql = "SELECT *, users.profile_image FROM aq_messages 
        LEFT JOIN users ON users.id = aq_messages.outgoing_msg_id
        WHERE (incoming_msg_id = ? AND outgoing_msg_id = ?) 
        OR (outgoing_msg_id = ? AND incoming_msg_id = ?)
        ORDER BY msg_timestamp ASC";

$stmt = $db->prepare($sql);
if (!$stmt) {
    die('Prepare failed: ' . $db->error);
}

// Correctly bind four integer parameters
$stmt->bind_param("iiii", $incoming_id, $outgoing_id, $incoming_id, $outgoing_id);
if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

$result = $stmt->get_result();


if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $dateTime = $row['msg_timestamp']; // Assuming this field exists in your rows
    $readableDateTime = date('M d, Y h:i A', strtotime($dateTime));
    
if ($row['outgoing_msg_id'] == $outgoing_id) {
    $output .= '<div class="d-flex justify-content-end">
                    <div class="chat-box-message outgoing ">
                        <div class="chat-box-message-content">
                            <div class="chat-box-message-text">
                                <p>'.nl2br($row['msg']).'</p>
                            </div>
                            <div class="chat-box-message-time">
                                <p>'.$readableDateTime.'</p>
                            </div>
                        </div>
                    </div>
                    <div class="ms-2">
                        <img src="core/assets/uploads/profile_images/'.$row['profile_image'].'" alt="Profile Image" style="width:40px; height:40px; border-radius: 50%; object-fit: cover;">
                    </div>
                </div>';
} else {
    $output .= '<div class="d-flex">
                    <div class="me-2">
                        <img src="core/assets/uploads/profile_images/'.$row['profile_image'].'" alt="Profile Image" style="width:40px; height:40px; border-radius: 50%; object-fit: cover;">
                    </div>
                    <div class="chat-box-message">
                        <div class="chat-box-message-content">
                            <div class="chat-box-message-text">
                                <p>'.nl2br($row['msg']).'</p>
                            </div>
                            <div class="chat-box-message-time">
                                <p>'.$readableDateTime.'</p>
                            </div>
                        </div>
                    </div>
                </div>';
}
  }
}

echo $output; // Display messages

?>
