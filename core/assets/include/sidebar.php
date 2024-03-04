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
                <?php } if ($workflow['wlevelId'] < 2) { ?>
                    <li class="has-subnav">
                        <a href="<?php echo "senderDataTable.php" . '?workflow_id=' . $wId; ?>">
                            <i class="fa fa-file-import fa-2x"></i>
                            <span class="nav-text">
                                Active Requests
                            </span>
                        </a>
                    </li>
                <?php } ?>
                <details style="">
                    <summary>Archives</summary>
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
                <?php if ($workflow['workflow_name'] == 'Asistencia') { ?>
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
                <?php }} ?>
                
                </div>
                <ul  class="logout">
                <hr >
                    <li >
                        <a href="logout.php" >
                            <i style="color:white;"  class="fa fa-power-off fa-2x"></i>
                            <span  style="font-weight: bold; color:white;" class="nav-text">
                            Log Out
                            </span>
                        </a>
                    </li>
                </ul>
            </ul>
        </nav>
    </aside>
</div>