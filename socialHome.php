<?php

include_once 'core/config/main_social_setup.php';

// An array to translate English day names to Spanish
$dayTranslations = array(
    'Monday' => 'lunes',
    'Tuesday' => 'martes',
    'Wednesday' => 'miércoles',
    'Thursday' => 'jueves',
    'Friday' => 'viernes',
    'Saturday' => 'sábado',
    'Sunday' => 'domingo',
);

// Get the current date and time
$currentTime = new DateTime('now', new DateTimeZone('America/Puerto_Rico'));

// Format the date in Spanish, including the day of the week
$currentDate = $currentTime->format('l, d \d\e F \d\e Y');

// Replace English day and month names with Spanish equivalents
$currentDate = strtr($currentDate, $dayTranslations);
$monthTranslations = array(
    'January' => 'enero',
    'February' => 'febrero',
    'March' => 'marzo',
    'April' => 'abril',
    'May' => 'mayo',
    'June' => 'junio',
    'July' => 'julio',
    'August' => 'agosto',
    'September' => 'septiembre',
    'October' => 'octubre',
    'November' => 'noviembre',
    'December' => 'diciembre',
);
$currentDate = strtr($currentDate, $monthTranslations);

?>
    <div class="container container-data-card">
        <main class="content-data-card border border-info bg-white shadow my-3">
                <!-- Adjust vh-100 for the desired height -->
                <?php if (!empty($sysRol)) { // Check if $user_data is not empty ?>
                    <?php
                    // SQL query to fetch user workflows and check if the user is associated with each workflow
                    $sql = "SELECT workflows.workflow_name, workflows.wsender, workflows_creator.wcreator_name, workflows_creator.wlevel_id AS wlevelId, workflows.id AS workflow_id
                        from users_by_wcreator
                        INNER JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
                        left JOIN workflows ON workflows.id = workflows_creator.wcreator_workflows_id
                        WHERE ubw_user_id = ?
                        ";

                    // Prepare and execute the SQL query
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$session_user]);

                    // Fetch all the user's workflows into an array
                    $user_workflows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Check if user_workflows are found
                    if (!empty($user_workflows)) {
                       

                        $cardCount = 0; // Initialize card count

                        foreach ($user_workflows as $workflow) {

                            $workflowName = $workflow['wcreator_name'];
                            if ($workflowName === 'Monitor') {
                                echo '  
                                <a class="data-card" href="aqMonitor.php?workflow_id=' . $workflow['workflow_id'] . '">
                                    <div><i class="fas fa-clock" style="color: #11538d;"></i></div>
                                    <p class="button2">Monitor</p>
                                </a>';
                            }
                     
                            if (($workflow['workflow_name'] == 'Check In') && ($workflow['wlevelId'] == '2')) {
                                $buttonHref = 'checkIn.php';
                                $dataCardClass = 'data-card'; // Add a class for the third data card style
                            }
                            if (($workflow['wsender'] == 'Solicitar Turno') && ($workflow['wlevelId'] == '2')) {
                                $buttonHref = 'addAppointment.php';
                                $dataCardClass = 'data-card'; // Add a class for the third data card style
                            }
                            if (($workflow['workflow_name'] == 'Check In') && ($workflow['wlevelId'] >= '3')) {
                                $buttonHref = 'attendanceApproval.php';
                                $dataCardClass = 'data-card'; // Add a class for the third data card style
                            }
                            if (($workflow['workflow_name'] != 'Check In') && ($workflow['wlevelId'] >= '3')) {
                                $buttonHref = 'newApproval.php';
                          
                            }
                            if (($workflow['wsender'] != 'Enviar Reporte') && ($workflow['wlevelId'] >= '2')) {
                                $buttonHref = 'addServiceRequest.php';
                          
                            }

                            // Generate the button HTML with both workflow_id and original buttonHref as query parameters
                            
                            if ($workflow['workflow_name'] != 'Check In' && $workflow['workflow_id'] != '384') {
                                echo '<a class="data-card" href="' . $buttonHref . '?workflow_id=' . $workflow['workflow_id'] . '">';
                                echo '<i class="fas fa-share-nodes" style="color: #11538d;"></i>';
                                echo '<p>' . $workflow['wsender'] . '</p>';
                                echo '</a>';
                            } 
                            if ($workflow['workflow_name'] == 'Check In' && $workflow['wlevelId'] < '2') {
                                echo '<a class="data-card" id="openModal-6">';
                                echo '<i class="fas fa-share-nodes" style="color: #11538d;"></i>';
                                echo '<p>' . $workflow['wsender'] . '</p>';
                                echo '</a>';
                                $workflow_id = $workflow['workflow_id'];
                            }
                            
                            if ($workflow['workflow_name'] == 'Check In' && $workflow['wlevelId'] < '1') {
                                echo '<a class="data-card" href="newAttendance.php?workflow_id=' . $workflow['workflow_id'] . '" id="attendance">';
                                echo '<i class="fas fa-share-nodes" style="color: #11538d;"></i>';
                                echo '<p> Attendance </p>';
                                echo '</a>';
                                $workflow_id = $workflow['workflow_id'];
                            }

                            if ($workflow['workflow_name'] == 'Check In' && $workflow['wlevelId'] <= '2') {
                                echo '<a class="data-card" href="generalAttendance.php?workflow_id=' . $workflow['workflow_id'] . '" id="attendance">';
                                echo '<i class="fas fa-share-nodes" style="color: #11538d;"></i>';
                                echo '<p> General Attendance </p>';
                                echo '</a>';

                                echo '<a class="data-card" href="approvalAttendance.php?workflow_id=' . $workflow['workflow_id'] . '" id="attendance">';
                                echo '<i class="fas fa-share-nodes" style="color: #11538d;"></i>';
                                echo '<p> Attendance Requests </p>';
                                echo '</a>';
                                $workflow_id = $workflow['workflow_id'];
                            }
                            
                            if ($workflow['workflow_id'] == '384') {
                                echo '<a class="data-card" href="checkInMedico.php?workflow_id=' . $workflow['workflow_id'] . '">';
                                echo '<i class="fas fa-share-nodes" style="color: #11538d;"></i>';
                                echo '<p>' . $workflow['wsender'] . '</p>';
                                echo '</a>';
                            }
                            $cardCount++;

                        }

                    }
                    if (empty($user_workflows)) {
                        // Display a message when $user_workflows is empty
                        echo '<div class="data-card">';
                        echo '    <p>There are no modules assigned to you at this moment.</p>';
                        echo '</div>';
                    }
                    ?>
                <?php } ?>
             <!-- Start CheckIn Modal -->
                <div class="container"> <div class="modal fade" id="checkIn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                        
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                           
                            </div>
                            <div class="modal-body">
                      <form class="" role="form" method="POST" action="core/transactions/transacAttendance.php?workflow_id=<?php echo $workflow_id; ?>" id="submitReport">
                        
                        <div class="tick-container">
                        <span><?php echo $currentDate; ?></span>
                        <hr>
                            <div class="tick" data-did-init="setupFlip">
                                <!-- Hide visual content from screen readers with `aria-hidden` -->
                                <div data-repeat="true" aria-hidden="true">
                                    <span data-view="flip"></span>
                                </div>
                            </div>
                        </div>
                        <div id="reader"></div>
                        <div id="result"></div>
                        <div class="row">
                            <input type="hidden" id="from_date_hidden" name="from_date">
                            <input type="hidden" id="to_date_hidden" name="to_date">
                            <div>
                                <input type="hidden" name="result" id="resultInput">
                            </div>
                            <?php
                            $i = $_SESSION['id'];
                            ?>
                            <input type="hidden" name="users_id" id="users_id" value="<?php echo $i; ?>">

                        </div>
                        <?php displayPunchButtons($pdo, $session_user); ?>
                                    <div class="row">
                                        <input type="hidden" name="r_workflow_id" value="<?php echo $workflow_id; ?>">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- End CheckIn Modal -->
        </main>
    </div>
    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
    </footer>
</body>
</html>
