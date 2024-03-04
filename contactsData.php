<?php
   while ($row = $result->fetch_assoc()) {
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
        $output .= '<a href="aqMessengerChatArea.php?id='. $row['id'].'">
                        <div class="d-flex align-items-center justify-content-between p-3" style="width: 100%;">
                            <div class="d-flex align-items-center">
                                <div class="pe-3">
                                    <img src="core/assets/uploads/profile_images/' . htmlspecialchars($row['profile_image']) . '" alt="Profile Image" style="margin-right: 1rem; width:40px; height:40px; border-radius: 50%; object-fit: cover;">
                                </div>
                                <div>
                                    ' . $row['first_name'] . " " . $row['last_name'] . " - " . $row['status'] . '
                                    <div class="status-message text-primary"><span>You have a new message</span></div>
                                </div>
                            </div>
                            <span style="margin-bottom: 0; padding: 0;"><i class="fas fa-circle ' . $statusClass . '"></i></span>
                        </div>
                        <hr>
                    </a>';
    }
?>