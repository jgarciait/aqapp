<?php
include_once 'core/config/main_setup.php';

if ($sysRol['sys_group_name'] === 'admin') {
    $isAdmin = true;
}

if ($isAdmin) {
    // Admin-specific content here
} else {
    // User is not an admin, so end the session and redirect to index.php
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle form submission to send invitation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['user_email'];
    $userName = $_POST['user_name'];
    $token = generateToken(); // Generate a unique token for the user

    // Create the registration link with the token
    $registrationLink = "https://www.uccpr.app/aqregistration.php?token=" . $token;

    // Send an email to the user with the registration link
    $subject = "Invitation to Register";
    
    // Create an HTML message with the registration link as a hyperlink
    $message = '<html ><body>';
    $message .= '<p>Hola '. htmlentities($userName, ENT_QUOTES, 'UTF-8') .'!</p>';
    $message .= '<p>Usted fue invitado a registrarse en AQPlatform.</p>';
    $message .= '<a href="' . $registrationLink . '">Register</a>';
    $message .= '</body></html>';

    // Additional headers for HTML email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    // Insert the token into the reset_tokens table
    if (insertResetTokenForNewUser($email, $token, $db)) {
        // Token inserted successfully
        if (sendEmail($email, $subject, $message, $headers)) {
            // Email sent successfully
            echo "Invitation sent successfully.";
        } else {
            // Email sending failed
            echo "Error sending invitation.";
        }
        ?>
        <script type="text/javascript">
        alert("Invitación enviada");
        window.location = "home.php";
        </script>
    <?php
    } else {
        // Token insertion failed
        echo "Error sending invitation. Please try again later.";
    }
}
?>
<script>
    function confirmDelete(userId) {
        if (confirm("¿Estás seguro de que quieres eliminar este usuario?")) {
            window.location.href = 'delUser.php?action=delete&id=' + userId;
        }
    }
</script>

<body>
    <header class="header">
        <div class="header-content">
            <p style="" class="shine">AQ Platform</p>
        </div>
        <nav class="profile"><!-- Navigation Bar Starts Here -->
            <span class="profile-text"><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></span>
            <img class="display-picture" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Windows_10_Default_Profile_Picture.svg/64px-Windows_10_Default_Profile_Picture.svg.png" alt="Profile Image">
            <ul class="shadow border profile-menu"><!-- Profile Menu -->
                <li><a href="profile.php">Profile</a></li>
                <li><a href="#">Account</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav><!-- Navigation Bar Ends Here -->
    </header>
    <div class="container">
        <aside class="sidebar" id="sidebar">
            <div class="toggle-button-container">
                <button type="button" class="icon-sm" id="toggleButton"><i class="fa fa-circle-dot"></i></button>
            </div>
            <nav>
                <ul>
                        <li class="has-subnav">
                            <a href="home.php">
                                <i class="fa fa-home fa-2x"></i>
                                <span class="nav-text">
                                    Inicio
                                </span>
                            </a>
                        </li>
                <?php if ($sysRol['sys_group_name'] == 'admin') { ?>
                    <li class="has-subnav">
                        <a href="#" id="expandButton1">
                            <i class="fa fa-gear fa-2x"></i>
                            <span class="nav-text">
                                Administración
                            </span>
                        </a>
                    </li>
                    <div id="expandContent1" style="display: block;">
                        <li class="has-subnav">
                            <a href="logs.php">
                                <i class="fa fa-clock-rotate-left fa-2x"></i>
                                <span class="nav-text">
                                    Logs
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="audit_trail.php">
                                <i class="fa fa-user-secret fa-2x"></i>
                                <span class="nav-text">
                                    Audit Trail
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="usersAccount.php">
                                <i class="fa fa-users fa-2x"></i>
                                <span class="nav-text">
                                    Usuarios
                                </span>
                            </a>
                        </li>
                        <hr> 
                    </div>
                     
                    <?php } ?>
               
                <li class="has-subnav">
                    <a href="#" id="expandButton">
                        <i class="fa fa-code-commit fa-2x"></i>
                        <span class="nav-text">
                            Módulos Asignados
                        </span>
                    </a>
                </li>

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
                   
                    if ($workflow['workflow_name'] == 'Asistencia') { ?>
                        <li class="has-subnav">
                            <a href="<?php echo "newAttendance.php" . '?workflow_id=' . $wId; ?>">
                                <i class="fa fa-user-clock fa-2x"></i>
                                <span class="nav-text">
                                    <?php echo $workflow['workflow_name']; ?>
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="<?php echo "newRequest.php" . '?workflow_id=' . $wId; ?>">
                                <i class="fa fa-file-invoice fa-2x"></i>
                                <span class="nav-text">
                                    Solicitudes
                                </span>
                            </a>
                        </li>

                    <?php }}
                    if (!empty($sysRol)) { // Check if $user_data is not empty
                    
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
                        
                            echo '<li class="has-subnav">';
                            echo '<a href="newRequest.php?workflow_id=' . $wId . '">';
                            echo '<i class="fa fa-ellipsis fa-2x"></i>';
                            echo '<span class="nav-text">';
                            echo $workflow['workflow_name'];
                            echo '</span>';
                            echo '</a>';
                            echo '</li>';
                        
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
                                    Logout
                                </span>
                            </a>
                        </li>
                    </ul>
                </ul>
            </nav>
        </aside>
    </div>
    <div class="container container-table">
            <!-- Start addUsers Modal --> 
                <div class="container"> <div class="modal fade" id="addUsers" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form style="" class=""
                                    action="transacUser.php?action=addUser.php" 
                                    method="post"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="">Nombre</label>
                                        <input type="text" 
                                            class="form-control"
                                            name="first_name"
                                            id="first_name"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label class="">Apellidos</label>
                                        <input type="text" 
                                            class="form-control"
                                            name="last_name"
                                            id="last_name"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label class="">Correo Electrónico</label>
                                        <input type="email" 
                                            class="form-control"
                                            name="user_email"
                                            id="user_email"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contraseña</label>
                                        <div class="input-group">
                                            <input type="password"
                                            class="form-control"
                                            id="user_pass"
                                            name="user_pass"
                                            placeholder="********">
                                            <button type="button"
                                            class="btn btn-outline-secondary"
                                            id="togglePassword">👁️</button>
                                        </div>
                                    </div>
                        
                                    <div class="row">
                                        <div class="col" >
                                            <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Agregar Usuario</span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- End addUsers Modal -->
            <!-- Start invite Modal -->
                <div class="container"> <div class="modal fade" id="invite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form style="" class=""
                                    action="invite-user.php"
                                    method="post"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label>Nombre:</label>
                                        <input type="text" class="form-control" name="user_name" required>
                                    </div>
                                    <!-- Input field for user's email -->
                                    <div class="mb-3">
                                        <label>Email:</label>
                                        <input type="email" class="form-control" name="user_email" required>
                                    </div>
                                    <!-- Submit button to send invitation -->
                                    <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Enviar Invitación</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- End invite Modal -->

            <main class="container-fluid my-3 p-5 border border-info content-table bg-white shadow rounded table-responsive">
                <div>
                    <a href="#" class="btn-menu btn-1 hover-filled-opacity" id="openModal-1"><span>Registrar Usuarios</span></a>
                </div>
                <div>
                    <a href="#" class="btn-menu btn-1 hover-filled-opacity" id="openModal-2"><span>Invitar a AQPlatform</span></a>
                </div>
                <div class="container-fluid p-3 mr-3 ">        
                    <table style="width: 100%; padding: 3rem;" id="templateTable" class="table table-bordered table-condensed table-hover">
                        <thead >
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Nombre de Usuario</th>
                                <th>Rol de Sistema</th>
                                <th>Opciones</th>
                                <th>Borrar</th>        
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            mysqli_set_charset($db, "utf8");
                            $sql = "SELECT *, users.id AS pid
                            FROM users 
                            INNER JOIN users_by_sysgroup ON users_by_sysgroup.ubs_user_id = users.id
                            LEFT JOIN sys_groups ON sys_groups.id = users_by_sysgroup.ubs_sys_groups_id
                            ORDER BY user_email ASC";

                            $result = mysqli_query($db, $sql); // Execute the query

                            $count = 1;

                            if ($result) {

                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Your table rows here...
                                    ?>
                                    <tr>
                                        <td data-title='#'><?php echo $count; ?></td>
                                        <td data-title='Nombre'><?php echo $row['first_name']?></td>
                                        <td data-title='Apellidos'><?php echo $row['last_name']?></td>
                                        <td data-title='Nombre de Usuario'><?php echo $row['user_email']?></td>
                                        <td data-title='Rol de Sistema'><?php echo $row['sys_group_name']?></td>                 
                                        <td data-title='Opciones'>
                                            <?php echo '<a type="button" class="btn btn-xs" style="color:white; background-color:#215f92;" href="viewAdminProfile.php?action=edit&id=' . $row['pid'] . '">Ver</a>'; ?>
                                        </td>
                                        <td data-title='Borrar'>
                                            <?php echo '<a type="button" class="btn btn-xs" style="color:white; background-color:#215f92;" href="javascript:void(0);" onclick="confirmDelete(' . $row['pid'] . ');">Borrar</a>'; ?>
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
