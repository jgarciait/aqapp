<?php
include_once 'core/config/setting_setup.php';

// Prepare SQL queries
$userQuery = "SELECT * FROM users";
$workflowQuery = "SELECT * FROM workflows";
$wcreatorQuery = "SELECT * FROM workflows_creator";
?>

<script>
    function confirmDelete(workflowId) {
        if (confirm("Delete Confirmation")) {
            window.location.href = 'core/transactions/transacDelWorkflow.php?action=delete&id=' + workflowId;
        }
    }
</script>

<?php if (isset($_GET['modal_message'])): ?>
    <script>
        alert("<?php echo $_GET['modal_message']; ?>");
    </script>
<?php endif; ?>

<body>
    <div class="container container-table">
        <main class="container-fluid my-1 p-4 border border-info content-table bg-white shadow rounded table-responsive">
            <div class="container">
                <a class="title-table"><span>Modules</span></a>
            </div>
            <div>
                <!-- <a href="addWorkflowType.php?" class="btn-menu btn-1 hover-filled-opacity"><span>Crear Module</span></a> -->
                <a href="#" class="btn-menu btn-1 hover-filled-opacity" id="openModal-3"><span>Create a Module</span></a>
            </div>

            <div class="modal fade" id="createModule" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="" role="form" method="POST" action="core/transactions/transacWorkflowType.php?" id="submitReport">
                                <div></div>
                                <div class="mb-3">
                                    <label class="">Add Module</label>
                                    <input type="text"
                                           class="form-control"
                                           name="workflow_name"
                                           id="workflow_name"
                                    >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Select Category</label>
                                    <select class="form-control" name="wsender">
                                        <option>---</option>
                                        <option value="Time Clock">Time Clock</option>
                                        <option value="Create Ticket">Create Ticket</option>
                                        <option value="Send Request">Send Request</option>
                                        <option value="Send Report">Send Report</option>
                                        <option value="Send Message">Send Message</option>
                                        <option value="Add Item">Add Item</option>
                                        <option value="Add Appointment">Add Appointment</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="">Select Module Type</label>
                                    <select class="form-control" id="wtype_id" name="wtype_id">
                                        <option>---</option>
                                        <?php
                                        // Query to select options from the table
                                        $sql = "SELECT * FROM workflow_type";
                                        // Execute the query
                                        $result = $db->query($sql);
                                        // Check if any rows were returned
                                        if ($result->num_rows > 0) {
                                            // Loop through the rows and create an option for each record
                                            while ($row = $result->fetch_assoc()) {
                                                // Output the option HTML
                                                echo '<option value="'. $row["id"] . '">'. $row["wtype_name"] . '</option>';
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
            <div>
                <a href="#" class="btn-menu btn-1 hover-filled-opacity" id="openModal-4"><span>Create Position</span></a>
            </div>
            <div class="modal fade" id="createProcess" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="" role="form" method="POST" action="core/transactions/transacWorkflowByArea.php?action=addWorkflowType.php" id="submitReport">
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

            <div>
                <a href="#" class="btn-menu btn-1 hover-filled-opacity" id="openModal-5"><span>Add User to Position</span></a>
            </div>
            <div class="modal fade" id="addUserProcess" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="" role="form" method="POST" action="core/transactions/transacUserByWcreator.php?action=addWorkflowType.php" id="submitReport">
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

            <div class="container-fluid p-1">
                <table style="width: 100%; padding: 1rem;" id="templateTable" class="table table-bordered table-condensed table-hover">
                    <thead class="align-middle">
                        <tr>
                            <th>#</th>
                            <th class="text-center">Module</th>
                            <th class="text-center">Position</th>
                            <th class="text-center">Type of Module</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Edit Module</th>
                            <th class="text-center">Position/s</th>
                            <th class="text-center">User/s</th>
                            <th class="text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                    <?php
                    mysqli_set_charset($db, "utf8");
                    $sql = "SELECT *, workflows.id AS wId
                    FROM workflows
                    INNER JOIN workflow_type ON workflow_type.id = workflows.wtype_id
                    ORDER BY workflow_timestamp DESC
                    ";
                        $result = mysqli_query($db, $sql); // Execute the query

                        $count = 1;
                    
                        if ($result) {
                        
                            while ($row = mysqli_fetch_assoc($result)) {
                                $workflowDate = date('F j, Y h:i A', strtotime($row['workflow_timestamp']));
                                // Your table rows here...
                                ?>
                                <tr>
                                    <td class="text-center" data-title='#'><?php echo $count; ?></td>
                                    <td class="text-center" data-title='Module'><?php echo $row['workflow_name']?></td>
                                    <td class="text-center" data-title='Position'><?php echo $row['wsender']?></td>
                                    <td class="text-center" data-title='Type of Module'><?php echo $row['wtype_name']?></td>
                                    <td class="text-center" data-title='Date'><?php echo $workflowDate?></td>
                                    <td class="text-center" class="text-center" data-title='Edit Module'>
                                        <?php echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="editModule.php?action=edit&id=' . $row['wId'] . '"><span><i class="fa-solid fa-pen-to-square"></i></span></a>'; ?>
                                    </td>                
                                    <td class="text-center" data-title='Position/s'>
                                        <?php echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="workflowsByType.php?action=edit&id=' . $row['wId'] . '"><span><i class="fa-solid fa-repeat"></i></span></a>'; ?>
                                    </td>
                                    <td class="text-center" data-title='User/s'>
                                        <?php echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="workflowsByUser.php?action=edit&id=' . $row['wId'] . '"><span><i class="fa-regular fa-user"></i></span></a>'; ?>
                                    </td>
                                    <td class="text-center" data-title='Delete'>
                                        <?php echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="javascript:void(0);" onclick="confirmDelete(' . $row['wId'] . ')"><span><i class="fa-regular fa-trash-can"></i></span></a>'; ?>
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
