<?php
include_once 'core/config/main_setup.php';

$workflow_id = $_GET['workflow_id'];
$user_data2 = getUserById2($session_user, $workflow_id, $db);

$workflowLevelId = $workflow['wlevelId'];

?> 
    <div class="container container-table">
        <main class="container-fluid my-1 p-4 border border-info content-table bg-white shadow rounded table-responsive">
            <div class="container">
            <a class="title-table"><span><?php echo $user_data2['workflow_name'] . " - " . $user_data2['wcreator_name'];  ?></span></a>
            <p>Rejected Request</p>            
            </div>
                <div class="container-fluid p-3 mr-2 ">        
                    <table style="width: 100%; padding: 1rem;" id="senderTable" class="table table-bordered table-condensed table-hover">
                       <thead>
                            <tr>
                                    <th>#</th>
                                    <th>Núm. Caso</th>
                                    <th>Remitente</th>
                                    <th>Servicio</th>
                                    <th>Estatus</th>
                                    <th>Fecha</th> 
                                    <th>Form Options</th>         
                                </tr>
                            </thead>
                            <tbody>
        <?php
            mysqli_set_charset($db, "utf8");
            $sql = "SELECT form_001.id AS fId, receiver_division.wcreator_name AS receiver_division_name, users_by_wcreator.ubw_user_id, form_name, ref_number, process_status, service_request, sender.first_name AS sender_name, sender.last_name AS sender_lname, receiver.first_name AS receiver_name, timestamp
            FROM workflows
            LEFT JOIN form_metadata ON form_metadata.fm_workflows_id = workflows.id
            LEFT JOIN form_001 ON form_001.form_metadata_id = form_metadata.id
            LEFT JOIN forms_status ON forms_status.forms_id = form_001.id
            LEFT JOIN forms_audit_trail ON forms_audit_trail.fl_forms_id = form_001.id
            LEFT JOIN users AS sender ON sender.id = forms_status.fl_sender_user_id
            LEFT JOIN users AS receiver ON receiver.id = forms_status.fl_receiver_user_id
            LEFT JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = fl_sender_user_id
            LEFT JOIN workflows_creator AS sender_division ON sender_division.id = users_by_wcreator.wcreator_id
            LEFT JOIN workflows_creator AS receiver_division ON receiver_division.id = forms_status.receiver_division_wcid
            WHERE forms_status.process_level_id = 1
            AND forms_status.fl_sender_user_id = $session_user
            AND forms_status.process_status = 'Rejected by Dependencia A'
            GROUP BY forms_status.forms_id"; 

            $result = mysqli_query($db, $sql); // Execute the query

            $count = 1;
        
            if ($result) {
            
                while ($row = mysqli_fetch_assoc($result)) {
                    // Your table rows here...
                     $timestamp = date('F j, Y h:i A', strtotime($row['timestamp']));
                    ?>
                    <tr>
                        <td data-title='#'><?php echo $count; ?></td>
                        <td data-title='Núm. Caso'><?php echo $row['ref_number']?></td>
                        <td data-title='Remitente'><?php echo $row['sender_name'] . " " . $row['sender_lname']?></td>
                        <td data-title='Nombre del Form'><?php echo $row['service_request']?></td>
                        <td data-title='Estatus'><?php echo $row['process_status']; ?></td>
                        <td data-title='Fecha'><?php echo $timestamp; ?></td>
                       <td data-title='Ver Formulario'>
                            <?php
                            echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="readOnlyForm_001.php?action=edit&id=' . $row['fId'] . '"><span><i class="fa-solid fa-eye"></i></span></a>';
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
