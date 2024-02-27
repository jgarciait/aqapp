<?php
include_once 'core/config/main_setup.php';

$workflow_id = $_GET['workflow_id'];
$user_data2 = getUserById2($session_user, $workflow_id, $db);
$table_data = getTableNameByWorkflowId($workflow_id, $db); // Retrieve the dynamic table name
$table_name = $table_data['table_name']; // Correctly accessing the table name
$fmId = $table_data['form_metadata.id'];
$workflowLevelId = $workflow['wlevelId'];
$wcId = $user_data2['workflows_creator_id'];

?> 
    <div class="container container-table">
        <main class="container-fluid my-1 p-4 border border-info content-table bg-white shadow rounded table-responsive">
            <div class="container">
            <a class="title-table"><span><?php echo $user_data2['workflow_name'] . " - " . $user_data2['wcreator_name'];  ?></span></a>
            <div class="col-sm-2 mb-3 pt-2">
                    <select class="form-select" id="filterFormStatus">
                        <?php
                        // Fetch unique statuses from the shift_table
                        $statusQuery = "SELECT DISTINCT process_status AS filter_status FROM forms_status
                        WHERE forms_status.receiver_division_wcid = $wcId";
                        $formStatus = mysqli_query($db, $statusQuery);

                        if ($formStatus) {
                            echo '<option value="">Filter by Status</option>';
                            while ($row = mysqli_fetch_assoc($formStatus)) {
                                $filterStatus = $row['filter_status'];
                                echo "<option value='$filterStatus'>$filterStatus</option>";
                            }
                            mysqli_free_result($formStatus); // Correct variable name here
                        } else {
                            echo "Error fetching status options: " . mysqli_error($db);
                        }
                        ?>
                    </select>
                </div>
            </div>
                <div class="container-fluid p-3 mr-2 ">        
                    <table style="width: 100%; padding: 1rem;" id="senderTable" class="table table-bordered table-condensed table-hover">
                       <thead>
                            <tr>
                                    <th>#</th>
                                    <th>Núm. Caso</th>
                                    <th>Servicio</th>
                                    <th>Estatus</th>
                                    <th>Asignado a:</th>
                                    <th>Fecha</th> 
                                    <th>Form Options</th>         
                                </tr>
                            </thead>
                            <tbody>
        <?php
         if ($table_name) { // Check if table name was successfully retrieved
                mysqli_set_charset($db, "utf8");
                $sql = "SELECT {$table_name}.id AS fId, 
                            receiver_division.wcreator_name AS receiver_division_name, 
                            '{$table_name}' AS table_name, 
                            ref_number, 
                            process_status, 
                            service_request, 
                            sender.first_name AS sender_name, 
                            receiver.first_name AS receiver_name, 
                            timestamp
                        FROM {$table_name}
                        INNER JOIN forms_status ON forms_status.forms_id = {$table_name}.id
                        LEFT JOIN users AS sender ON sender.id = forms_status.fl_sender_user_id
                        LEFT JOIN users AS receiver ON receiver.id = forms_status.fl_receiver_user_id
                        LEFT JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = forms_status.fl_sender_user_id
                        LEFT JOIN workflows_creator AS sender_division ON sender_division.id = users_by_wcreator.wcreator_id
                        LEFT JOIN workflows_creator AS receiver_division ON receiver_division.id = forms_status.receiver_division_wcid
                        WHERE forms_status.process_level_id = ?
                        AND forms_status.receiver_division_wcid = $wcId
                        AND forms_status.process_status != 'Rejected'
                        AND forms_status.process_status != 'Completed'
                        ORDER BY forms_status.timestamp DESC";

            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $workflowLevelId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $count = 1;
        
            if ($result) {
            
                while ($row = mysqli_fetch_assoc($result)) {
                    // Your table rows here...
                     $timestamp = date('F j, Y h:i A', strtotime($row['timestamp']));
                    ?>
                    <tr>
                        <td data-title='#'><?php echo $count; ?></td>
                        <td data-title='Núm. Caso'><?php echo $row['ref_number']?></td>
                        <td data-title='Nombre del Form'><?php echo $row['service_request']?></td>
                        <td data-title='Estatus'><?php echo $row['process_status']; ?></td>
                        <td data-title='Atendido por:'><?php echo $row['receiver_division_name']?></td>
                        <td data-title='Fecha'><?php echo $timestamp; ?></td>
                       <td data-title='Ver Formulario'>
                            <?php
                                echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="approvalForm_001.php?action=edit&id=' . $row['fId'] . '"><span><i class="fa-solid fa-eye"></i></span></a>';
                            ?>
                        </td>           
                    </tr>
                    <?php
                    $count++;
                }
                mysqli_free_result($result); // Free the result set
            } else {
                echo "Error executing query: " . mysqli_error($db);
            }
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
