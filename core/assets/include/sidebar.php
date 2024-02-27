<div>
    <aside class="sidebar expanded" id="sidebar">
        <div class="toggle-button-container">
            <button type="button" class="icon-sm" id="toggleButton"><i class="fa fa-circle-dot"></i></button>
        </div>
        <nav>
            <ul>
                    <li class="has-subnav">
                    <a href="socialHome.php">
                        <i class="fa fa-home fa-2x"></i>
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
                <?php } ?>

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
                if ($workflow['wlevelId'] > 1) { ?>
                    
                        <a href="<?php echo "receiverDataTable.php" . '?workflow_id=' . $wId; ?>">
                            <i class="fa fa-file-invoice fa-2x"></i>
                            <span class="nav-text">
                                Requests
                            </span>
                        </a>
                    </li>
                <?php } if ($workflow['wlevelId'] = 1) { ?>
                    <li class="has-subnav">
                        <a href="<?php echo "senderDataTable.php" . '?workflow_id=' . $wId; ?>">
                            <i class="fa fa-file-import fa-2x"></i>
                            <span class="nav-text">
                                Active Requests
                            </span>
                        </a>
                    </li>
                <details style="">
                    <summary>Archive</summary>
                    <li class="has-subnav">
                        <a href="<?php echo "rejectedDataTable.php" . '?workflow_id=' . $wId; ?>">
                            <i class="fa fa-box-archive fa-2x"></i>
                            <span class="nav-text">
                                Rejected
                            </span>
                        </a>
                    </li>
                    <li class="has-subnav">
                        <a href="<?php echo "completedDataTable.php" . '?workflow_id=' . $wId; ?>">
                            <i class="fa fa-box-archive fa-2x"></i>
                            <span class="nav-text">
                                Completed
                            </span>
                        </a>
                    </li>
                </details>
                <?php } if ($workflow['workflow_name'] == 'Asistencia') { ?>
                    <li class="has-subnav">
                        <a href="<?php echo "newAttendance.php" . '?workflow_id=' . $wId; ?>">
                            <i class="fa fa-user-clock fa-2x"></i>
                            <span class="nav-text">
                                <?php echo $workflow['workflow_name']; ?>
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