<?php

include_once 'core/config/main_setup.php';

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


<body>
    <header class="header">
         <div class="header-content">
            <p style="" class="shine">AQPlatform</p>
        </div>
        <nav class="profile"><!-- Navigation Bar Starts Here -->
                <span class="profile-text"><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></span>
                <img class="display-picture" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Windows_10_Default_Profile_Picture.svg/64px-Windows_10_Default_Profile_Picture.svg.png" alt="Profile Image">
            <ul class="shadow border profile-menu"><!-- Profile Menu -->
                <li><a href="profile.php">Profile</a></li>
               <!-- <li><a href="#">Account</a></li> -->
               <!-- <li><a href="#">Settings</a></li> -->
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav><!-- Navigation Bar Ends Here -->
    </header>
    <div class="container">
    <aside class="sidebar expanded" id="sidebar">
            <div class="toggle-button-container">
                <button type="button" class="icon-sm" id="toggleButton"><i class="fa fa-circle-dot"></i></button>
            </div>
            <nav>
             <ul>
                     <li class="has-subnav">
                        <a href="home.php">
                            <i class="fa fa-home fa-2x"></i>
                            <span class="nav-text">
                                Home
                            </span>
                        </a>
                    </li>
                    <?php if ($sysRol['sys_group_name'] == 'admin') { ?>
                    <li class="has-subnav">
                        <a href="#" id="expandButton1">
                            <i class="fa fa-gear fa-2x"></i>
                            <span class="nav-text">
                                Settings
                            </span>
                        </a>
                    </li>
                    <div id="expandContent1" style="display: block;">
                
                        <li class="has-subnav">
                            <a href="workflowsList.php">
                                <i class="fa fa-sliders fa-2x"></i>
                                <span class="nav-text">
                                    Modules Management
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="logs.php">
                                <i class="fa fa-clock-rotate-left fa-2x"></i>
                                <span class="nav-text">
                                    Logs
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="audit_trail.php">
                                <i class="fa fa-user-secret fa-2x"></i>
                                <span class="nav-text">
                                    Audit Trail
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="user_management.php">
                                <i class="fa fa-users fa-2x"></i>
                                <span class="nav-text">
                                    User Management
                                </span>
                            </a>
                        </li>
                        <hr> 
                    </div>
                     
                    <?php } ?>
               
                <li class="has-subnav">
                    <a href="#" id="expandButton">
                        <i class="fa fa-code-commit fa-2x"></i>
                        <span class="nav-text">
                            My Modules
                        </span>
                    </a>
                </li>

                <div id="expandContent" style="display: block;">
                   
                    <?php   
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
                    // Loop through the user's workflows
                    foreach ($user_workflows as $workflow) {
                
                    $wId = $workflow['workflow_id'];
                   
                    if ($workflow['workflow_name'] == 'Asistencia') { ?>
                        <li class="has-subnav">
                            <a href="<?php echo "newAttendance.php" . '?workflow_id=' . $wId; ?>">
                                <i class="fa fa-user-clock fa-2x"></i>
                                <span class="nav-text">
                                    <?php echo $workflow['workflow_name']; ?>
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="<?php echo "newRequest.php" . '?workflow_id=' . $wId; ?>">
                                <i class="fa fa-file-invoice fa-2x"></i>
                                <span class="nav-text">
                                    Request
                                </span>
                            </a>
                        </li>
         <form class="row g-3" action="transacTasksForm.php" method="post">

                    <?php if (isset($_GET['submitted'])) { ?>
                    <div id="successAlert" class="alert alert-success alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>Success!</strong> Task Added
                    </div>
                    <?php } ?>

                    <?php
                    if (isset($_GET['deleted'])) {
                    ?>
                    <div id="successAlert" class="alert alert-danger alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Success!</strong> Task Deleted

                    </div>
                    <?php
                    }

                    ?>
                <div class="col-auto">
                    <div class="form-floating mb-3">
                        <input name="task_name" type="text" class="form-control" id="floatingInput" placeholder="Task">
                        <label for="floatingInput">Nombre del Input</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating mb-3">
                        <textarea name="task_description" type="text" class="form-control" id="floatingInput" placeholder="Description"></textarea>
                        <label for="floatingInput">Decripción del Input</label>
                    </div>
                </div>
                  <div class="col">
                    <div class="form-floating mb-3">
                        <textarea name="task_description" type="text" class="form-control" id="floatingInput" placeholder="Description"></textarea>
                        <label for="floatingInput">Nombre de Variable</label>
                    </div>
                </div>
                <div class="col-auto">       
                    <button type="submit" class="btn btn-outline-primary mb-3">Submit</button>
                </div>
                
            </form>
                    <?php }}
                    if (!empty($user_data)) { // Check if $user_data is not empty
                    
                        // SQL query to fetch user workflows and check if the user is associated with each workflow
                        $sql = "SELECT workflows.workflow_name, workflows.wsender, workflows_creator.wcreator_name, workflows_creator.wlevel_id AS wlevelId, workflows.id AS workflow_id
                            from users_by_wcreator
                            INNER JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
                            left JOIN workflows ON workflows.id = workflows_creator.wcreator_workflows_id
                            WHERE ubw_user_id = ?
                            ORDER BY workflow_name ASC
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
                            }  
                        
                        if ($workflow['workflow_id'] != 384)
                        {
                           $href1 = "newRequest.php";
                            
                            echo '<li class="has-subnav">';
                            echo '<a href="'. $href1 .'?workflow_id=' . $wId . '">';
                            echo '<i class="fa fa-ellipsis fa-2x"></i>';
                            echo '<span class="nav-text">';
                            echo $workflow['workflow_name'];
                            echo '</span>';
                            echo '</a>';
                            echo '</li>';
                        
                        }
                        
                        if ($workflow['workflow_id'] == 384)
                                {
                                $href1 = "medicoTable.php";
                                $href2 = "medicoChart.php";
                                    
                                    echo '<li class="has-subnav">';
                                    echo '<a href="'. $href1 .'?workflow_id=' . $wId . '">';
                                    echo '<i class="fa fa-file-waveform fa-2x"></i>';
                                    echo '<span class="nav-text">';
                                    echo $workflow['workflow_name'];
                                    echo '</span>';
                                    echo '</a>';
                                    echo '</li>';

                                    echo '<li class="has-subnav">';
                                    echo '<a href="'. $href2 .'?workflow_id=' . $wId . '">';
                                    echo '<i class="fa fa-chart-column fa-2x"></i>';
                                    echo '<span class="nav-text">';
                                    echo 'Dashboard';
                                    echo '</span>';
                                    echo '</a>';
                                    echo '</li>';
                                }

                        $cardCount++;
                        }
                        }
                  
                     ?>  
                    <hr>
                    </div>
                    <ul class="logout">
                        <li>
                            <a href="logout.php">
                                <i style="color:Tomato;"  class="fa fa-power-off fa-2x"></i>
                                <span  style="font-weight: bold; color:Tomato;" class="nav-text">
                                Log Out
                                </span>
                            </a>
                        </li>
                    </ul>
                </ul>
            </nav>
        </aside>
    </div>
    <div class="container container-data-card">
 
        <main class="content-data-card border border-info bg-white shadow my-3">
            <?php if (!empty($sysRol)) { // Check if $user_data is not empty ?>
                <!-- Admin Verification -->
                <?php if ($sysRol['sys_group_name'] == 'admin') { ?>
                     
                            <a class="data-card" href="user_management.php">
                                <div><i class="fas fa-users fa-lg" style="color: #11538d;"></i></div>
                                <p>User Management</p>
                            </a>
                 
                            <a class="data-card" href="workflowsList.php">
                                <div><i class="fas fa-network-wired fa-lg" style="color: #11538d;"></i></div>
                                <p>Module Management</p>
                            </a>
                 
                            <a class="data-card" href="settingsShift.php">
                                <div><i class="fas fa-business-time fa-lg" style="color: #11538d;"></i></div>
                                <p>Shift Settings</p>
                            </a>

                            <a class="data-card" href="invite-user.php">
                                <div><i class="fas fa-share" style="color: #11538d;"></i></div>
                                <p>Invite to AQPlatform</p>
                            </a>
             
                <?php } ?>
            <?php } ?>
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
                     
                            if (($workflow['workflow_name'] == 'Asistencia') && ($workflow['wlevelId'] == '2')) {
                                $buttonHref = 'checkIn.php';
                                $dataCardClass = 'data-card'; // Add a class for the third data card style
                            }
                            if (($workflow['wsender'] == 'Solicitar Turno') && ($workflow['wlevelId'] == '2')) {
                                $buttonHref = 'addAppointment.php';
                                $dataCardClass = 'data-card'; // Add a class for the third data card style
                            }
                            if (($workflow['workflow_name'] == 'Asistencia') && ($workflow['wlevelId'] >= '3')) {
                                $buttonHref = 'attendanceApproval.php';
                                $dataCardClass = 'data-card'; // Add a class for the third data card style
                            }
                            if (($workflow['workflow_name'] != 'Asistencia') && ($workflow['wlevelId'] >= '3')) {
                                $buttonHref = 'newApproval.php';
                          
                            }
                            if (($workflow['wsender'] != 'Enviar Reporte') && ($workflow['wlevelId'] >= '2')) {
                                $buttonHref = 'addServiceRequest.php';
                          
                            }

                            // Generate the button HTML with both workflow_id and original buttonHref as query parameters
                            
                            if ($workflow['workflow_name'] != 'Asistencia' && $workflow['workflow_id'] != '384') {
                                echo '<a class="data-card" href="' . $buttonHref . '?workflow_id=' . $workflow['workflow_id'] . '">';
                                echo '<i class="fas fa-share-nodes" style="color: #11538d;"></i>';
                                echo '<p>' . $workflow['wsender'] . '</p>';
                                echo '</a>';
                            } 
                            if ($workflow['workflow_name'] == 'Asistencia') {
                                echo '<a class="data-card" id="openModal-1">';
                                echo '<i class="fas fa-share-nodes" style="color: #11538d;"></i>';
                                echo '<p>' . $workflow['wsender'] . '</p>';
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
                      <form class="shadow bg-white p-1" role="form" method="POST" action="transacAttendance.php?workflow_id=<?php echo $workflow_id; ?>" id="submitReport">
                        
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
