<div>
    <aside class="socialSidebar expanded" id="sidebar2">
        <div class="toggle-button-container2">
            <button type="button" class="icon-sm" id="toggleButton2"><i class="fa fa-circle-dot"></i></button>
        </div>
        <nav>

<p>DCS Team</p>

        <?php

        // SQL query to fetch all user workflows for the current date
        $sql = "SELECT first_name, last_name, start_time FROM users
                INNER JOIN shift_table ON shift_table.sst_user_id = users.id
                WHERE DATE(start_time) = CURDATE() 
                ORDER BY start_time ASC";

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Fetch all the users' workflows into an array
        $workers_time_clock = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if workers time clock data is found
if (!empty($workers_time_clock)) {
    echo '<div class="scroll-container" style="height: 200px; overflow-y: auto;">'; // Start the scrollable container with styles
    echo '<ul>'; // Start the list here

    foreach ($workers_time_clock as $wtc) {
        echo '<li class="has-subnav">';
        echo '<i class="fa fa-user fa-1x"></i>';
        echo '<span class="nav-text">';
        echo $wtc['first_name'] . " " . $wtc['last_name'] . " start at:";
    
        echo '</span>';
        // Format and display the date and time
        $timestamp = strtotime($wtc['start_time']);
        $formattedDate = date('F j, Y', $timestamp); // Date in words
        $formattedTime = date('h:i A', $timestamp);   // Time in AM/PM format

        echo $formattedDate . " " . $formattedTime;
        echo '<hr>';
        echo '</li>';
    }

    echo '</ul>'; // End the list here
    echo '</div>'; // End the scrollable container
}
?>
        </nav>
    </aside>
</div>