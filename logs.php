<?php
include_once 'core/config/setting_setup.php';

?> 
    <div class="container container-table">
        <main class="container-fluid my-1 p-4 border border-info content-table bg-white shadow rounded table-responsive">
            <div class="container">
            <a class="title-table"><span>Logs</span></a>
            </div>    
                <div class="container-fluid p-3 mr-2 ">        
                    <table style="width: 100%; padding: 1rem;" id="templateTable" class="table table-bordered table-condensed table-hover">
                       <thead>
                            <tr>
                                    <th>#</th>
                                    <th>Nombre y Apellido</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Log In / Log Out</th>
                                    <th>Fecha y Hora</th>          
                                </tr>
                            </thead>
                            <tbody>
        <?php
            mysqli_set_charset($db, "utf8");
            $sql = "SELECT * 
            FROM logs
            INNER JOIN users ON users.id = logs.logs_user_id
            ORDER BY logs_timestamp DESC
            "; 
            $result = mysqli_query($db, $sql); // Execute the query

            $count = 1;
        
            if ($result) {
            
                while ($row = mysqli_fetch_assoc($result)) {
                    // Your table rows here...
                     $timestamp = date('F j, Y h:i A', strtotime($row['logs_timestamp']));
                    ?>
                    <tr>
                        <td data-title='#'><?php echo $count; ?></td>
                        <td data-title='Nombre y Apellido'><?php echo $row['first_name'] . " " . $row['last_name']?></td>
                        <td data-title='Nombre de Usuario'><?php echo $row['user_email']?></td>
                        <td data-title='Log In / Log Out'><?php echo $row['logs_action']?></td>
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
