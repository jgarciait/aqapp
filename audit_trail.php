<?php
include_once 'core/config/setting_setup.php';

?>   
    <div class="container container-table">
        <main class="container-fluid my-1 p-4 border border-info content-table bg-white shadow rounded table-responsive">
            <div class="container">
            <a class="title-table"><span>Audit Trail</span></a>
            </div>
                <div class="container-fluid p-3 mr-3 ">        
                    <table style="width: 100%; padding: 2rem;" id="templateTable"  class="table nowrap table-bordered table-condensed table-hover">
                                 <thead  style="white-space: nowrap">
                            <tr>
                                    <th>#</th>
                                    <th>Nombre y Apellido</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Acción</th>
                                    <th>Fecha y Hora</th>          
                                </tr>
                            </thead>
                            <tbody>
        <?php
            mysqli_set_charset($db, "utf8");
            $sql = "SELECT * 
            FROM audit_trails
            INNER JOIN users ON users.id = audit_trails.audit_trail_user_id
            ORDER BY audit_trail_timestamp DESC
            "; 
            $result = mysqli_query($db, $sql); // Execute the query

            $count = 1;
        
            if ($result) {
            
                while ($row = mysqli_fetch_assoc($result)) {
                    // Your table rows here...
                     $timestamp = date('F j, Y h:i A', strtotime($row['audit_trail_timestamp']));
                    ?>
                    <tr>
                        <td data-title='#'><?php echo $count; ?></td>
                        <td data-title='Nombre'><?php echo $row['first_name'] . " " . $row['last_name']?></td>
                        <td data-title='Usuario'><?php echo $row['user_email']?></td>
                        <td data-title='Acción'><?php echo $row['audit_trail_action']?></td>
                        <td data-title='Fecha y Hora'><?php echo $timestamp; ?></td>                  
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
