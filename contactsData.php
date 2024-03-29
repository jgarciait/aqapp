<?php
$output = ''; // Define $output before appending content
while ($row = $result->fetch_assoc()) {
    $sql2 = "SELECT * FROM aq_messages 
    WHERE (incoming_msg_id = {$row['user_id']} OR outgoing_msg_id = {$row['user_id']}) 
    AND (outgoing_msg_id = {$outgoing_id} OR incoming_msg_id = {$outgoing_id}) 
    ORDER BY msg_timestamp DESC LIMIT 1";

    $stmt2 = $db->prepare($sql2);
    $stmt2->execute(); // Execute the prepared statement
    $result2 = $stmt2->get_result(); // Get the result set from the statement
    $messageContent = "No message available"; // Default message
    if ($result2 && $result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $messageContent = $row2['msg'];
        $messageContent = str_replace("\n", "<br>", $messageContent);
        $msg = (strlen($messageContent) > 20) ? substr($messageContent, 0, 20) . '...' : $messageContent;

        // Determine the message sender
        if ($outgoing_id == $row2['outgoing_msg_id']) {
            $msgWho = "You: " . $msg;
        } else {
            $msgWho = "From: " . $msg;
        }
    } else {
        // If no message is available, handle accordingly
        $msgWho = ""; // Adjust this as needed
    }
    
    ($row['status'] == 'Online') ? $status = "status-online" : (($row['status'] == 'Do not disturb') ? $status = "status-dnd" : $status = "status-offline" );
    // Note: Ensure $msgWho is safely handled when no message is found

    $output .= '<a href="aqMessengerChatArea.php?id=' . $row['user_id'] . '">
                    <div class="d-flex align-items-center justify-content-between p-3" style="width: 100%;">
                        <div class="d-flex align-items-center">
                            <div class="pe-3">
                                <img src="core/assets/uploads/profile_images/' . htmlspecialchars($row['profile_image']) . '" alt="Profile Image" style="margin-right: 1rem; width:40px; height:40px; border-radius: 50%; object-fit: cover;">
                            </div>
                            <div>
                                ' . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . " - " . $row['status'] . '
                                <div class="status-message">' . htmlspecialchars($msgWho) . '</div>
                            </div>
                        </div>
                        <span style="margin-bottom: 0; padding: 0;"><i class="fas fa-circle '.$status.'"></i></span>
                    </div>
                    <hr>
                </a>';
}
?>
