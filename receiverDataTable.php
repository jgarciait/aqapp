<?php
include_once 'core/config/main_setup.php';

// Ensure session_start() is called within main_setup.php or here if not already included
if (isset($_SESSION['formSubmittedSuccessfully']) && $_SESSION['formSubmittedSuccessfully']) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
            $.ajax({
                url: 'process_queue.php',
                type: 'GET',
                dataType: 'json', // Expecting JSON response
                success: function(response) {
                    if (response.success) {
                        console.log(response.message);
                    } else {
                        console.error(response.message);
                    }
                },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle JSON parsing error
                        if (jqXHR.responseText) {
                            try {
                                var response = JSON.parse(jqXHR.responseText);
                                console.error('Detailed error: ' + response.message);
                                if (response.errors.length > 0) {
                                    response.errors.forEach(function(error) {
                                        console.error('Error detail: ' + error);
                                    });
                                }
                            } catch (e) {
                                console.error('AJAX error: ' + textStatus + ', ' + errorThrown);
                            }
                        } else {
                            console.error('AJAX error: ' + textStatus + ', ' + errorThrown);
                        }
                    }
            });
            });
          </script>";
    unset($_SESSION['formSubmittedSuccessfully']); // Clean up
}

$workflow_id = $_GET['workflow_id'];
$user_data2 = getUserById2($session_user, $workflow_id, $db);
$table_data = getTableNameByWorkflowId($workflow_id, $db); // Retrieve the dynamic table name
$table_name = $table_data['table_name']; // Correctly accessing the table name
$fmId = $table_data['id'];
$workflowLevelId = $workflow['wlevelId'];
$wcId = $user_data2['workflows_creator_id'];


?>

<!-- Update the CSS -->
<style>
.priority-low {
    color: green; /* Change to desired color */
}

.priority-medium {
    color: orange; /* Change to desired color */
}

.priority-high {
    color: red; /* Change to desired color */
}
</style>
<div class="container container-table">
    <main class="container-fluid my-4 p-4 border border-info content-table bg-white shadow rounded table-responsive">
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
                        <th>Ticket Number</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned to:</th>
                        <th>Date</th> 
                        <th>Options</th>         
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($table_name) { // Check if table name was successfully retrieved
                        mysqli_set_charset($db, "utf8");
                        $sql = "SELECT DISTINCT {$table_name}.id AS fId, 
                                    receiver_division.wcreator_name AS receiver_division_name, 
                                    '{$table_name}' AS table_name, 
                                    forms_status.ref_number,
                                    forms_status.fs_priority,
                                    forms_status.fl_receiver_user_id,
                                    process_status, 
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
                                    <td data-title='Case Number'><?php echo $row['ref_number']?></td>
                                    <td data-title='Priority'>
                                        <select class="form-select priority-select text-center" data-form-id="<?php echo $row['fId']; ?>">
                                            <option value="Low" <?php if ($row['fs_priority'] == 'Low') echo 'selected'; ?>>Low</option>
                                            <option value="Medium" <?php if ($row['fs_priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
                                            <option value="High" <?php if ($row['fs_priority'] == 'High') echo 'selected'; ?>>High</option>
                                        </select>
                                    </td>

                                    <td data-title='Status'><?php echo $row['process_status']; ?></td>
                                    <?php
                                    // Fetch technicians with process_level > 1
                                    $techniciansQuery = "SELECT users.id, first_name FROM users 
                                                        INNER JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = users.id 
                                                        INNER JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
                                                        WHERE workflows_creator.id = $wcId AND workflows_creator.wlevel_id > 1";
                                    $techniciansResult = mysqli_query($db, $techniciansQuery);

                                    if ($techniciansResult) {
                                        $selectOptions = '';
                                        while ($technician = mysqli_fetch_assoc($techniciansResult)) {
                                            $userId = $technician['id'];
                                            $userName = $technician['first_name'];
                                            // Check if the user is currently assigned to the ticket
                                            $selected = ($row['fl_receiver_user_id'] == $userId) ? 'selected' : '';
                                            $selectOptions .= "<option value='$userId' $selected>$userName</option>";
                                        }
                                        mysqli_free_result($techniciansResult); // Free the result set

                                        // Append the select element to the row
                                        echo "<td data-title='Assigned to:'>
                                                <select class='form-select receiver-select technician-select' data-form-id='{$row['fId']}'>
                                                    <option value=''>Select Technician</option>
                                                    $selectOptions
                                                </select>
                                            </td>";
                                    } else {
                                        echo "<td data-title='Assigned to:'>Error fetching technicians</td>";
                                    }
                                ?>


                                    <td data-title='Date'><?php echo $timestamp; ?></td>
                                    <td data-title='View Form'>
                                        <?php
                                        echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="approval-'.$table_name.'.php?action=edit&id=' . $row['fId'] . '"><span><i class="fa-solid fa-eye"></i></span></a>';
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
<script>
$(document).ready(function() {
    var formSubmittedSuccessfully = localStorage.getItem('formSubmittedSuccessfully');
    if (formSubmittedSuccessfully === 'true') {
        // Trigger the email queue processing script here
        localStorage.removeItem('formSubmittedSuccessfully'); // Clean up after triggering
    }
});
</script>
<!-- Update your JavaScript section -->
<script>
$(document).ready(function() {
    // Event listener for priority change
    $('.priority-select').change(function() {
        var formId = $(this).data('form-id');
        var priority = $(this).val();
        updatePriority(formId, priority);
    });

    // Function to update priority via AJAX
    function updatePriority(formId, priority) {
        $.ajax({
            url: 'update_priority.php', // Create this file
            type: 'POST',
            data: { formId: formId, priority: priority },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    console.log('Priority updated successfully');
                } else {
                    console.error('Error updating priority: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error: ' + textStatus + ', ' + errorThrown);
            }
        });
    }
});
</script>
<script>
$(document).ready(function() {
    // Event listener for priority change
    $('.technician-select').change(function() {
        var formId = $(this).data('form-id');
        var technician = $(this).val();
        updateTechnician(formId, technician);
    });

    // Function to update priority via AJAX
    function updateTechnician(formId, technician) {
        $.ajax({
            url: 'update_technician.php', // Create this file
            type: 'POST',
            data: { formId: formId, technician: technician },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    console.log('Priority updated successfully');
                } else {
                    console.error('Error updating technician: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error: ' + textStatus + ', ' + errorThrown);
            }
        });
    }
});
</script>
<script>
$(document).ready(function() {
    // Function to update priority classes
    function updatePriorityClasses() {
        $('.priority-select').each(function() {
            var selectedOption = $(this).find('option:selected');
            var priority = selectedOption.val();

            // Remove existing classes
            $(this).removeClass('priority-low priority-medium priority-high');

            // Add class based on selected priority
            if (priority === 'Low') {
                $(this).addClass('priority-low');
            } else if (priority === 'Medium') {
                $(this).addClass('priority-medium');
            } else if (priority === 'High') {
                $(this).addClass('priority-high');
            }
        });
    }

    // Call the function initially
    updatePriorityClasses();

    // Event listener for priority change
    $('.priority-select').change(function() {
        updatePriorityClasses();
    });
});
</script>


</body>
</html>
