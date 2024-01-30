<?php
include_once 'core/config/main_setup.php';

$workflow_id = isset($_GET['workflow_id']) ? $_GET['workflow_id'] : null;
$user_data2 = getUserById2($session_user, $workflow_id, $db);
$wcreator_id = $user_data2['wcId'];

// Check if the "success" parameter is set
if (isset($_GET['success']) && $_GET['success'] === 'true') {
    $confirmationLink = "http://localhost/ireport"; 
    $subject = "AQPlatform: Solicitud Enviada";
    $message = "Le notificaremos cuando su solicitud sea atendida. <a href=\"$confirmationLink\">www.aqplatform.com</a>";
    
    if (isset($_GET['user_email'])) {
    $user_email = urldecode($_GET['user_email']);
    // Send an email notification here using the sendEmail function
    sendEmail($user_email, $subject, $message);
    // You can customize the email content as needed
    } else {
        echo "Error with the email";
    }
}


?>

<div class="container container-table">
    <main class="container-fluid my-1 p-4 border border-info content-table bg-white shadow rounded table-responsive">
                <div class="row">
                        <div class="col-sm-3 mb-3">
                                <select class="form-select" id="filterDate">
                               <?php
                                    // Fetch unique dates (date part only) from your data
                                    $dateQuery = "SELECT DISTINCT DATE(start_time) AS date_part FROM shift_table";
                                    $dateResult = mysqli_query($db, $dateQuery);

                                    if ($dateResult) {
                                        echo '<option value="hoy">Hoy</option>';  
                                      
                                        while ($row = mysqli_fetch_assoc($dateResult)) {
                                            $selectedDate = date('F j, Y', strtotime($row['date_part']));
                                            echo '<option value="' . $selectedDate . '">' . $selectedDate . '</option>';        
                                        }
                                        mysqli_free_result($dateResult);
                                    } else {
                                        echo "Error fetching date options: " . mysqli_error($db);
                                    }
                                      echo '<option value="todo">Mostrar Todo</option>';
                                    ?>
                                    <!-- Add date filter options here -->
                                </select>
                            </div>

                            <div class="col-sm-3 mb-3">
                                <select class="form-select" id="filterFirstName">
                                       <?php
                                        // Fetch unique dates from your data
                                        $users = "SELECT * FROM users
                                        ";
                                        $usersResult = mysqli_query($db, $users);

                                        if ($usersResult) {

                                        echo '<option value="">Seleccione Empleado</option>';
                                            while ($row = mysqli_fetch_assoc($usersResult)) {
                                                $firstName = $row['first_name'];
                                                $lastName = $row['last_name'];
                                                echo '<option value="' . $firstName . '">' . $firstName . ' ' . $lastName .'</option>';
                                            }
                                            mysqli_free_result($usersResult);
                                        } else {
                                            echo "Error fetching date options: " . mysqli_error($db);
                                        }
                                    ?>
                                    <!-- Add user first name filter options here -->
                                </select>
                            </div>
                    <?php if ($user_data2['wtype_id'] == 1) { ?>  
                    <div>
                        <a href="addApprovalRequest.php?action=add&workflow_id=<?php echo $workflow_id ?>" type="button" class="btn2 btn-xs"><h5 class="nreq"><?php echo $user_data2['wsender']; ?></h5></a>
                    </div>
                    <?php } ?>
                    <?php if ($user_data2['wtype_id'] == 2) { ?> 
                        <?php if ($user_data2['workflow_name'] == 'Asistencia') { ?> 
                            <div>
                                <a href="checkIn.php?action=add&workflow_id=<?php echo $workflow_id; ?>" type="button" class="btn2 btn-xs"><h5 class="nreq"><?php echo $user_data2['wsender']; ?></h5></a>
                            </div>
                        <?php } else {?>
                            <div>
                                <a href="addMessage.php?action=add&workflow_id=<?php echo $workflow_id; ?>" type="button" class="btn2 btn-xs"><h5 class="nreq"><?php echo $user_data2['wsender']; ?></h5></a>
                            </div> 
                        <?php }  ?> 
                    <?php } ?>  
                    <?php if ($user_data2['wtype_id'] == 3) { ?>  
                    <div>
                        <a href="addServiceRequest.php?action=add&workflow_id=<?php echo $workflow_id; ?>" type="button" class="btn2 btn-xs"><h5 class="nreq"><?php echo $user_data2['wsender']; ?></h5></a>
                    </div>
                    <?php } ?>
                
            <div class="container-fluid p-1">
                <table style="width: 100%; padding: 1rem;" id="templateTable" class="table table-bordered table-condensed table-hover">
                    <thead class="align-middle">
                        <tr>
                            <th>#</th>
                            <th>Nombre y Apellido</th>
                            <th>Entrada al Turno</th>
                            <th>Entrada Break AM</th>
                            <th>Salida Break AM</th>
                            <th>Entrada Almuerzo</th>
                            <th>Salida Almuerzo</th>
                            <th>Entrada Break PM</th>
                            <th>Salida Break PM</th>
                            <th>Salida del Turno</th>
                            <th>Horas Trabajadas</th>
                            <th>Salario</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                        first_name, 
                        last_name, 
                        start_time,
                        /*start_b1_time,
                        end_b1_time,
                        start_lunch,
                        end_lunch,
                        start_b2_time,
                        end_b2_time,
                        end_shift_time,
                        salary_ph,*/
                        start_time_status
                        FROM users
                        LEFT JOIN shift_table ON shift_table.sst_user_id = users.id
                         LEFT JOIN users_by_shift ON users_by_shift.ubs_user_id = users.id
                        LEFT JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
                        LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
                       /* LEFT JOIN startb1 ON startb1.b1_shift_id= shift_table.id
                        LEFT JOIN endb1 ON endb1.eb1_shift_id= shift_table.id
                        LEFT JOIN startlunch ON startlunch.sl_shift_id= shift_table.id
                        LEFT JOIN endlunch ON endlunch.el_shift_id= shift_table.id
                        LEFT JOIN startb2 ON startb2.b2_shift_id= shift_table.id
                        LEFT JOIN endb2 ON endb2.eb2_shift_id= shift_table.id
                        LEFT JOIN endshift_time ON endshift_time.est_shift_id= shift_table.id*/
                        ORDER BY start_time DESC";

                        $result = mysqli_query($db, $sql); // Execute the query
                        
                        $count = 1;

                        if ($result) {
                            $currentTstId = null; 

                        while ($row = mysqli_fetch_assoc($result)) {

                         if ($row['start_time'] != 0) {
                            $firstName = $row['first_name'];
                            $lastName = $row['last_name'];
                            $startDate = date('F j, Y h:i A', strtotime($row['start_time']));
                        } else {
                            $firstName = '---';
                            $lastName = '---';
                            $startDate = '---'; // Empty string if start_b1_time is 0
                        }
                        
                        if ($row['start_time'] != 0) {
                            $startDate = date('F j, Y h:i A', strtotime($row['start_time']));
                        } else {
                            $startDate = '---'; // Empty string if start_b1_time is 0
                        }
                        /*
                        
                        if ($row['start_b1_time'] != 0) {
                            $startB1 = date('F j, Y h:i A', strtotime($row['start_b1_time']));
                        } else {
                            $startB1 = '---'; // Empty string if start_b1_time is 0
                        }

                        if ($row['end_b1_time'] != 0) {
                            $endB1 = date('F j, Y h:i A', strtotime($row['end_b1_time']));
                        } else {
                            $endB1 = '---'; // Empty string if start_b1_time is 0
                        }

                        if ($row['start_lunch'] != 0) {
                            $start_lunch = date('F j, Y h:i A', strtotime($row['start_lunch']));
                        } else {
                            $start_lunch = '---'; // Empty string if start_b1_time is 0
                        }

                        if ($row['end_lunch'] != 0) {
                            $end_lunch = date('F j, Y h:i A', strtotime($row['end_lunch']));
                        } else {
                            $end_lunch = '---'; // Empty string if start_b1_time is 0
                        }

                        if ($row['start_b2_time'] != 0) {
                            $startB2 = date('F j, Y h:i A', strtotime($row['start_b2_time']));
                        } else {
                            $startB2 = '---'; // Empty string if start_b1_time is 0
                        }

                        if ($row['end_b2_time'] != 0) {
                            $endB2 = date('F j, Y h:i A', strtotime($row['end_b2_time']));
                        } else {
                            $endB2 = '---'; // Empty string if start_b1_time is 0
                        }

                         if ($row['end_shift_time'] != 0) {
                            $endDate = date('F j, Y h:i A', strtotime($row['end_shift_time']));
                        } else {
                            $endDate = '---'; // Empty string if start_b1_time is 0
                        }
*/
                        
                    
                echo "<tr>";
echo "<td>" . $count . "</td>";
echo '<td>' . $firstName . ' ' . $lastName . '</td>';
echo '<td style="white-space: normal; color: #467aaa;">' . $startDate . "</td>";
/*
echo "<td>" . $startB1 . "</td>";
echo "<td>" . $endB1 . "</td>";
echo "<td>" . $start_lunch . "</td>";
echo "<td>" . $end_lunch . "</td>";
echo "<td>" . $startB2 . "</td>";
echo "<td>" . $endB2 . "</td>";
echo '<td style="white-space: normal; color: #467aaa;">' . $endDate . "</td>";

// Calculate the time difference for start_b1_time and end_b1_time
$timeDifferenceB1 = 0;
if ($row['start_b1_time'] != 0 && $row['end_b1_time'] != 0) {
    $startB1Time = strtotime($row['start_b1_time']);
    $endB1Time = strtotime($row['end_b1_time']);
    $timeDifferenceB1 = $endB1Time - $startB1Time;
}

// Calculate the time difference for start_lunch and end_lunch
$timeDifferenceLunch = 0;
if ($row['start_lunch'] != 0 && $row['end_lunch'] != 0) {
    $startLunchTime = strtotime($row['start_lunch']);
    $endLunchTime = strtotime($row['end_lunch']);
    $timeDifferenceLunch = $endLunchTime - $startLunchTime;
}

// Calculate the time difference for start_b2_time and end_b2_time
$timeDifferenceB2 = 0;
if ($row['start_b2_time'] != 0 && $row['end_b2_time'] != 0) {
    $startB2Time = strtotime($row['start_b2_time']);
    $endB2Time = strtotime($row['end_b2_time']);
    $timeDifferenceB2 = $endB2Time - $startB2Time;
}

// Calculate the time difference for start_time and end_shift_time
if ($row['start_time'] != 0 && $row['end_shift_time'] != 0) {
    $startTime = strtotime($row['start_time']);
    $endTime = strtotime($row['end_shift_time']);

    // Calculate the total breaks time
    $totalBreaks = $timeDifferenceB1 + $timeDifferenceLunch + $timeDifferenceB2;

    // Calculate the total worked time, excluding breaks
    $totalWorkedSeconds = $endTime - $startTime - $totalBreaks;

    // Format the total worked time
    $hours = floor($totalWorkedSeconds / 3600);
    $minutes = floor(($totalWorkedSeconds % 3600) / 60);

    echo "<td>$hours:$minutes Hrs</td>";

    // Calculate salary
    $salaryPh = $row['salary_ph'];

    // Calculate the total salary based on hours and minutes
    $totalSalary = ($hours * $salaryPh) + (($minutes / 60) * $salaryPh);

    // Format the total salary as dollars and cents
    $formattedSalary = number_format($totalSalary, 2);

    echo "<td>$$formattedSalary - $salaryPh/ph</td>";
} else {
    echo "<td>---</td>";
    echo "<td>---</td>";
}
*/
// Estatus
if ($row['start_time_status'] === 'On Time') {
    echo '<td style="white-space: normal; font-weight: 600; color: #5cba46;">' . $row['start_time_status'] . "</td>";
} else if ($row['start_time_status'] === 'Ponche Ajustado') {
    echo '<td style="white-space: normal; font-weight: 600; color: #ba8c46;">' . $row['start_time_status'] . "</td>";
} else if ($row['start_time_status'] === 'Late Punch') {
    echo '<td style="white-space: normal; font-weight: 600; color: #ba4646">' . $row['start_time_status'] . "</td>";
} else {
    echo '<td style="white-space: normal; font-weight: 600; color: #ba4646">' . "---" . "</td>";
}

echo "</tr>";

$count++;
                    }
                                mysqli_free_result($result); // Free the result set
                            } else {
                                echo "Error executing query: " . mysqli_error($db);
                        }
                        ?>
                        
                    </tbody>
                    </table>
                    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
</body>

</html>