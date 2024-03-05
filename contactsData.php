<?php
while ($row = $result->fetch_assoc()) {
    $sql2 = "SELECT * FROM aq_messages 
    WHERE (incoming_msg_id = {$row['user_id']} AND outgoing_msg_id = {$outgoing_id}) 
    OR (outgoing_msg_id = {$row['user_id']} AND incoming_msg_id = {$outgoing_id}) 
    ORDER BY msg_id DESC LIMIT 1";
    $stmt2 = $db->prepare($sql2);
    $stmt2->execute(); // Execute the prepared statement
    $result2 = $stmt2->get_result(); // Get the result set from the statement
    if ($result2 && $result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $messageContent = $row2['msg'];
    } else {
        $messageContent = "No message available";
    }

    $messageContent = str_replace("\n", "<br>", $messageContent);
    $msg = (strlen($messageContent) > 20) ? substr($messageContent, 0, 20) . '...' : $messageContent;

    $statusClass = '';
    switch ($row['status']) {
        case 'Online':
            $statusClass = 'status-online';
            break;
        case 'Offline':
            $statusClass = 'status-offline';
            break;
        case 'Do not disturb':
            $statusClass = 'status-dnd';
            break;
        case 'Away':
            $statusClass = 'status-away';
            break;
        default:
            $statusClass = 'status-offline'; // Default case if none of the above
    }
    $output .= '<a href="aqMessengerChatArea.php?id=' . $row['user_id'] . '">
                    <div class="d-flex align-items-center justify-content-between p-3" style="width: 100%;">
                        <div class="d-flex align-items-center">
                            <div class="pe-3">
                                <img src="core/assets/uploads/profile_images/' . htmlspecialchars($row['profile_image']) . '" alt="Profile Image" style="margin-right: 1rem; width:40px; height:40px; border-radius: 50%; object-fit: cover;">
                            </div>
                            <div>
                                ' . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . " - " . $row['status'] . '
                                <div class="status-message text-primary">' . $msg . '</div>
                            </div>
                        </div>
                        <span style="margin-bottom: 0; padding: 0;"><i class="fas fa-circle ' . $statusClass . '"></i></span>
                    </div>
                    <hr>
                </a>';
}
?>
