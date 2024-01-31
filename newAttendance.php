<?php
include_once 'core/config/main_setup.php';
include_once 'core/assets/util/phpMailerFunction.php';

$workflow_id = isset($_GET['workflow_id']) ? $_GET['workflow_id'] : null;
$user_data2 = getUserById2($session_user, $workflow_id, $db);
$sysrol = getSysRol($session_user, $db);
$wcreator_id = $user_data2['wcreator_workflows_id'];


// Check if the "success" parameter is set
if (isset($_GET['success']) && $_GET['success'] === 'true') {
    $confirmationLink = "https://localhost/aqapp/"; 
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
            <div class="container">
            <a class="title-table"><span>Asistencia</span></a>
            </div>    
                <div>
                    <a href="checkIn.php?action=add&workflow_id=<?php echo $workflow_id; ?>" class="btn-menu btn-1 hover-filled-opacity"><span><?php echo $user_data2['wsender']; ?></span></a>
                </div>
                <div class="table-responsive container-fluid">        
                    <table style="width: 100%; min-height: 60vh;" id="templateTable" cellspacing="0" class="table table-bordered nowrap table-condensed table-hover">
                        <thead>
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
                                <th>Solicitar Ajuste</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                            shift_table.id AS stid,
                            first_name, 
                            last_name, 
                            start_time,
                            start_b1_time,
                            end_b1_time,
                            start_lunch,
                            end_lunch,
                            start_b2_time,
                            end_b2_time,
                            end_shift_time,
                            salary_ph,
                            start_time_status
                            FROM users
                            LEFT JOIN shift_table ON shift_table.sst_user_id = users.id
                            LEFT JOIN users_by_shift ON users_by_shift.ubs_user_id = users.id
                            LEFT JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
                            LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
                            LEFT JOIN startb1 ON startb1.b1_shift_id= shift_table.id
                            LEFT JOIN endb1 ON endb1.eb1_shift_id= shift_table.id
                            LEFT JOIN startlunch ON startlunch.sl_shift_id= shift_table.id
                            LEFT JOIN endlunch ON endlunch.el_shift_id= shift_table.id
                            LEFT JOIN startb2 ON startb2.b2_shift_id= shift_table.id
                            LEFT JOIN endb2 ON endb2.eb2_shift_id= shift_table.id
                            LEFT JOIN endshift_time ON endshift_time.est_shift_id= shift_table.id
                            WHERE users.id = $session_user
                            ORDER BY shift_table.start_time DESC";

                            $result = mysqli_query($db, $sql); // Execute the query
                            
                            $count = 1;
                            $totalsHorasTrabajadas = 0;
                            $totalsSalary = 0;

                            if ($result) {
                                $currentTstId = null; 

                            while ($row = mysqli_fetch_assoc($result)) {


                                $startDate = date('F j, Y h:i A', strtotime($row['start_time']));
                              
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

                            echo "<tr>";
                            echo "<td data-title='#'>" . $count . "</td>";
                            echo "<td data-title='Nombre y Apellido'>" . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
                            echo "<td data-title='Entrada al Turno' style='white-space: normal; color: #467aaa;'>" . $startDate . "</td>";

                            echo "<td data-title='Entrada Break AM'>". $startB1 ."</td>"; 
                            echo "<td data-title='Salida Break AM'>". $endB1 ."</td>"; //
                            echo "<td data-title='Entrada Almuerzo'>". $start_lunch ."</td>";
                            echo "<td data-title='Salida Almuerzo'>". $end_lunch ."</td>";
                            echo "<td data-title='Entrada Break PM'>". $startB2 ."</td>";
                            echo "<td data-title='Salida Break PM'>". $endB2 ."</td>";
                            echo "<td data-title='Salida del Turno' style='white-space: normal; color: #467aaa;'>". $endDate ."</td>";

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

                            // Calculate the time difference for start_time and end_shift_timefffffffffffffffffffffffffff
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

                                echo "<td data-title='Horas Trabajadas'>$hours:$minutes Hrs </td>"; //

                                // Calculate salary
                                $salaryPh = $row['salary_ph'];

                                // Calculate the total salary based on hours and minutes
                                $totalSalary = ($hours * $salaryPh) + (($minutes / 60) * $salaryPh);

                                // Format the total salary as dollars and cents
                                $formattedSalary = number_format($totalSalary, 2);

                                echo "<td data-title='Salario'>$$formattedSalary - $salaryPh/PH</td>";

                                // Accumulate the values
                                $totalsHorasTrabajadas += $totalWorkedSeconds;
                                $totalsSalary += $totalSalary;
                            } else {
                                echo "<td>---</td>";
                                echo "<td>---</td>";
                            }

                            // Estatus
                            if ($row['start_time_status'] === 'On Time') {
                                echo "<td data-title='Estatus' style='white-space: normal; font-weight: 600; color: #5cba46;'>" . $row['start_time_status'] . "</td>";
                            } else if ($row['start_time_status'] === 'Ponche Ajustado') {
                                echo "<td data-title='Estatus' style='white-space: normal; font-weight: 600; color: #ba8c46;'>" . $row['start_time_status'] . "</td>";
                            } else if ($row['start_time_status'] === 'Late Punch') {
                                echo "<td data-title='Estatus' style='white-space: normal; font-weight: 600; color: #ba4646'>" . $row['start_time_status'] . "</td>";
                            }
                         echo "<td  data-title='Solicitar Ajuste'> <div class='dropdown'>
                                <a style='font-weight: 500; text-decoration: none; z-index: 100;' class='dropdown-toggle'data-bs-toggle='dropdown' aria-expanded='false'>
                                    Ponches
                                </a>
                                <ul style='z-index: 100;' class='dropdown-menu' aria-labelledby='dropdownMenuLink'>
                                    <li><a class='dropdown-item' href='checkIn2.php?workflow_id=$workflow_id&shift_table_id={$row['stid']}'>Entrada Turno</a></li>
                                    <li><a class='dropdown-item' href='checkIn3.php?workflow_id=$workflow_id&shift_table_id={$row['stid']}'>Entrada Receso AM</a></li>
                                    <li><a class='dropdown-item' href='checkIn4.php?workflow_id=$workflow_id&shift_table_id={$row['stid']}'>Salida Receso AM</a></li>
                                    <li><a class='dropdown-item' href='checkIn5.php?workflow_id=$workflow_id&shift_table_id={$row['stid']}'>Entrada Almuerzo</a></li>
                                    <li><a class='dropdown-item' href='checkIn6.php?workflow_id=$workflow_id&shift_table_id={$row['stid']}'>Salida Almuerzo</a></li>
                                    <li><a class='dropdown-item' href='checkIn7.php?workflow_id=$workflow_id&shift_table_id={$row['stid']}'>Entrada Receso PM</a></li>
                                    <li><a class='dropdown-item' href='checkIn8.php?workflow_id=$workflow_id&shift_table_id={$row['stid']}'>Salida Receso PM</a></li>
                                    <li><a class='dropdown-item' href='checkIn9.php?workflow_id=$workflow_id&shift_table_id={$row['stid']}'>Salida Turno</a></li>
                                </ul>
                            </div></td>";


                            echo "</tr>";

          
                            $count++;

      
                            }
                                    mysqli_free_result($result); // Free the result set
                                } else {
                                    echo "Error executing query: " . mysqli_error($db);
                            }

                                // Function to format total worked hours
                                function formatTotalHorasTrabajadas($totalSeconds) {
                                    $hours = floor($totalSeconds / 3600);
                                    $minutes = floor(($totalSeconds % 3600) / 60);
                                    return "$hours:$minutes Hrs";
                                }
                            ?>
                                
                        </tbody>
                    </table>
                </div>
        </main>
    </div>
    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>

</html>