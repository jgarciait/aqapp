<?php
include_once 'core/config/main_setup.php';

?> 

    <div class="container container-table">
        <main class="container-fluid my-1 p-4 border border-info content-table bg-white shadow rounded table-responsive">
            <div class="container">
            <a class="title-table"><span>Forms Audit Trail</span></a>
            </div>    
                <div class="container-fluid p-3 mr-2 ">        
                    <table style="width: 100%; padding: 1rem;" id="templateTable" class="table table-bordered table-condensed table-hover">
                       <thead>
                            <tr>
                                    <th>#</th>
                                    <th>Form Name</th>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Timestamp</th>          
                                </tr>
                            </thead>
                            <tbody>
        <?php
            mysqli_set_charset($db, "utf8");
            $sql = "SELECT * 
            FROM forms_audit_trail
            INNER JOIN users ON users.id = forms_audit_trail.fl_user_id
            INNER JOIN forms_status ON forms_status.forms_id = forms_audit_trail.fl_forms_id
            INNER JOIN form_001 ON form_001.id = forms_status.forms_id
            LEFT JOIN form_metadata ON form_metadata.id = form_001.form_metadata_id 
            ORDER BY forms_audit_trail.fl_timestamp DESC
            "; 
            $result = mysqli_query($db, $sql); // Execute the query

            $count = 1;
        
            if ($result) {
            
                while ($row = mysqli_fetch_assoc($result)) {
                    // Your table rows here...
                     $timestamp = date('F j, Y h:i A', strtotime($row['fl_timestamp']));
                    ?>
                    <tr>
                        <td data-title='#'><?php echo $count; ?></td>
                        <td data-title='Form Name'><?php echo $row['form_name']; ?></td>
                        <td data-title='Action'><?php echo $row['actions']; ?></td>
                        <td data-title='User'><?php echo $row['user_email']; ?></td>
                        <td data-title='Timestamp'><?php echo $timestamp; ?></td>                  
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
