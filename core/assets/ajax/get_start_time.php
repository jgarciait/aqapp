<?php
// Initialize your PDO connection here (if not already done)
// Include necessary files or configurations
//Time Zone
date_default_timezone_set('America/Puerto_Rico');
setlocale(LC_TIME, 'es_ES.UTF-8');

include_once '../../../core/config/config_db.php';

// SQL query to fetch all user workflows for the current date
$sql = "SELECT first_name, last_name, start_time FROM users
        INNER JOIN shift_table ON shift_table.sst_user_id = users.id
        WHERE DATE(start_time) = CURDATE() 
        ORDER BY start_time DESC";

// Prepare and execute the SQL query
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all the users' workflows into an array
$workers_time_clock = $stmt->fetchAll(PDO::FETCH_ASSOC);    

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($workers_time_clock);
?>
