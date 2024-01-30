<div>
        <aside class="sidebar" id="sidebar">
            <div class="toggle-button-container">
                <button type="button" class="icon-sm" id="toggleButton"><i class="fa fa-circle-dot"></i></button>
            </div>
            <nav>
                 <ul>
                    <li class="has-subnav">
                        <a href="home.php">
                            <i class="fa fa-home fa-sm"></i>
                            <span class="nav-text">
                                Home
                            </span>
                        </a>
                    </li>
                    <?php if ($sysRol['ubs_sys_groups_id'] == '1') { ?> 
                        <li class="has-subnav">
                            <a href="settings.php">
                                <i class="fa fa-gear fa-2x"></i>
                                <span class="nav-text">
                                    Settings
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
                        <li class="has-subnav">
                            <a href="modulesList.php">
                                <i class="fa fa-circle-nodes fa-2x"></i>
                                <span class="nav-text">
                                    Module Management
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
                  
                    <?php } ?>
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

// Check if the user's ID is found in the query results
if (!empty($user_workflows)) {
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
                        Requests
                    </span>
                </a>
            </li>
        <?php }
        
        if ($workflow['wsender'] == 'Enviar Solicitud') { ?>
            <li class="has-subnav">
                <a href="<?php echo "newRequest.php" . '?workflow_id=' . $wId; ?>">
                    <i class="fa fa-file-invoice fa-2x"></i>
                    <span class="nav-text">
                        Requests
                    </span>
                </a>
            </li>
        <?php }
        
        if ($workflow['wsender'] == 'Solicitar Turno') { ?>
            <li class="has-subnav">
                <a href="<?php echo "newRequest.php" . '?workflow_id=' . $wId; ?>">
                    <i class="fa fa-ticket fa-2x"></i>
                    <span class="nav-text">
                        <?php echo $workflow['workflow_name']; ?>
                    </span>
                </a>
            </li>
        <?php }
    }
} else {
    // Handle the case where the user's ID is not found
    // You can display an alternative message or take other actions here
    echo "---";
}
?>

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