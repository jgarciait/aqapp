<?php

$wtypeId = $_GET['id'];
include_once 'core/config/setting_setup.php';

// Prepare SQL queries
$userQuery = "SELECT * FROM users";
$workflowQuery = "SELECT * FROM workflows";
$wcreatorQuery = "SELECT * FROM workflows_creator";
?>

<script>
    function confirmDelete(wcreatorId) {
        if (confirm("Delete Confirmation")) {
            window.location.href = 'core/transactions/transacDelWByType.php?action=delete&id=' + wcreatorId;
        }
    }
</script>
    <div class="container container-table">
        <main class="container-fluid my-1 border border-info content-table bg-white shadow rounded table-responsive">
            <div class="title-table">
            <?php
            $sql = "SELECT workflows.workflow_name, workflows_creator.id AS wcid
                    FROM workflows_creator
                    INNER JOIN workflows_level ON workflows_level.id = workflows_creator.wlevel_id
                    INNER JOIN workflows ON workflows_creator.wcreator_workflows_id = workflows.id
                    WHERE wcreator_workflows_id = $wtypeId";

            $result = mysqli_query($db, $sql); // Execute the query

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $workflowName = "Procesos en " . $row['workflow_name'];
                } else {
                    // Handle the case when there are no results (e.g., set $workflowName to a default value)
                    $workflowName = "";
                }
            } else {
                // Handle the case when the query execution fails
                echo "Error: " . mysqli_error($db);
            }
            ?>

            <a href="modulesList.php"><span style="color: darkblue; display: inline;">Modules > </span></a>
            <span style="display: inline;"><?php echo $workflowName; ?></span>
            </div>        
                <div >
                    <a href="#" class="btn-menu btn-1 hover-filled-opacity" id="openModal-4"><span>Create Position in Module</span></a>
                    
            <div class="modal fade" id="createProcess" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="shadow p-3" role="form" method="POST" action="core/transactions/transacWorkflowByArea.php?action=addWorkflowType.php" id="submitReport">
                                <div class="mb-3">
                                    <label class="">Select Module (Group)</label>
                                    <select class="form-control" id="wcreator_workflows_id" name="wcreator_workflows_id">
                                        <option>---</option>
                                        <?php
                                        // Query to select options from the table
                                        $sql = "SELECT * FROM workflows";
                                        // Execute the query
                                        $result = $db->query($sql);
                                        // Check if any rows were returned
                                        if ($result->num_rows > 0) {
                                            // Loop through the rows and create an option for each record
                                            while ($row = $result->fetch_assoc()) {
                                                // Output the option HTML
                                                echo '<option value="'. $row["id"] . '">'. $row["workflow_name"] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="">Create Position (Sub-Group)</label>
                                    <input type="text"
                                           class="form-control"
                                           name="wcreator_name"
                                           id="wcreator_name"
                                    >
                                </div>
                                <div class="mb-3">
                                    <label class="">Description</label>
                                    <input type="text"
                                           class="form-control"
                                           name="wcreator_description"
                                           placeholder="Opcional"
                                           id="wcreator_description"
                                    >
                                </div>
                                <div class="mb-3">
                                    <label class="">Select Position Level</label>
                                    <select class="form-control" id="workflows_level" name="wlevel_id">
                                        <?php
                                        // Query to select options from the table
                                        $sql = "SELECT * FROM workflows_level";
                                        // Execute the query
                                        $result = $db->query($sql);
                                        // Check if any rows were returned
                                        if ($result->num_rows > 0) {
                                            // Loop through the rows and create an option for each record
                                            while ($row = $result->fetch_assoc()) {
                                                // Output the option HTML
                                                echo '<option value="'. $row["id"] . '">'. $row["workflows_level_name"] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Save</span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                </div>
                <div >
                    <a href="#" class="btn-menu btn-1 hover-filled-opacity" id="openModal-5"><span>Add User to Position</span></a>
                </div>
                <div class="modal fade" id="addUserProcess" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="shadow p-3" role="form" method="POST" action="core/transactions/transacUserByWcreator.php?action=addWorkflowType.php" id="submitReport">
                                <div class="mb-3">
                                    <select class="form-control" id="userSelect" name="ubw_user_id" onchange="populateWCreator()">
                                        <option>---</option>
                                        <?php
                                        $result = $db->query($userQuery);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="'. $row["id"] . '">' . $row["first_name"] . ' '. $row["last_name"] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="users">Select Module</label>
                                    <select class="form-control" id="workflowSelect" name="ubw_workflow_id" onchange="populateWCreator()">
                                        <option>---</option>
                                        <?php
                                        $result = $db->query($workflowQuery);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="'. $row["id"] . '">'. $row["workflow_name"] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="users">Select Position</label>
                                    <select class="form-control" id="wcreatorSelect" name="wcreator_id">
                                        <option>---</option>
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                </div>
                                <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Save</span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

                <div class="container-fluid p-1 ">        
               <table id="templateTable" class="table table-hover table-striped">
                    <thead >
                        <tr>
                            <th>#</th>
                            <th>Module Position</th>
                            <th>Description</th>
                            <th>Position Level</th>
                            <th>Module</th>
                            <th class="text-center">Delete</th>          
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                    <?php
                    
                    mysqli_set_charset($db, "utf8");
                        $sql = "SELECT *, workflows_creator.id AS wcid
                                FROM workflows_creator
                                INNER JOIN workflows_level ON workflows_level.id = workflows_creator.wlevel_id
                                INNER JOIN workflows ON workflows_creator.wcreator_workflows_id = workflows.id
                                WHERE wcreator_workflows_id = $wtypeId
                                ";
                        
                        $result = mysqli_query($db, $sql); // Execute the query

                        $count = 1;
                    
                        if ($result) {
                        
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Your table rows here...
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row['wcreator_name']?></td>
                                    <td><?php echo $row['wcreator_description']?></td>
                                    <td><?php echo $row['workflows_level_name']?></td>
                                    <td><?php echo $row['workflow_name']?></td>                      
                                
                                    <td class="text-center">
                                        <?php echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="javascript:void(0);" onclick="confirmDelete(' . $row['wcid'] . ')"><span><i class="fa-regular fa-trash-can"></i></span></a>'; ?>
                                    </td>
                                </tr>
                                <?php
                                $count++;
                            }
                            mysqli_free_result($result); // Free the result set
                        } else {
                            echo "Error executing query: " . mysqli_error($db);
                        }
                        
                        ?>
                        <!-- Display the workflowName as the heading -->
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
