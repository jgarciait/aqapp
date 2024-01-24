<?php

include_once 'core/config/forms_setup.php';

?>
         
<body>
    <header class="header">
        <div class="header-content">
            <p style="" class="shine">AQ Platform</p>
        </div>
        <nav class="profile"><!-- Navigation Bar Starts Here -->
            <a href="#" class="display-picture">
                <span class="profile-text"><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></span>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Windows_10_Default_Profile_Picture.svg/64px-Windows_10_Default_Profile_Picture.svg.png" alt="Profile Image">
            </a>
            <ul class="shadow border profile-menu"><!-- Profile Menu -->
                <li><a href="profile.php">Profile</a></li>
                <li><a href="#">Account</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav><!-- Navigation Bar Ends Here -->
    </header>
    <div>
        <aside class="sidebar" id="sidebar">
            <div class="toggle-button-container">
                <button type="button" class="icon-sm" id="toggleButton"><i class="fa fa-circle-dot"></i></button>
            </div>
            <nav>
                 <ul>
                    <li class="has-subnav">
                        <a href="home.php">
                            <i class="fa fa-home fa-sm"></i>
                            <span class="nav-text">
                                Inicio
                            </span>
                        </a>
                    </li>
                    <?php if ($sysRol['sys_group_name'] == 'admin') { ?> 
                        <li class="has-subnav">
                            <a href="workflowsList.php">
                                <i class="fa fa-gear fa-2x"></i>
                                <span class="nav-text">
                                    Módulos
                                </span>
                            </a>
                        </li>
            
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
                    <?php } ?>
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

// Check if the user's ID is found in the query results
if (!empty($user_workflows)) {
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
        <?php }
        
        if ($workflow['wsender'] == 'Enviar Solicitud') { ?>
            <li class="has-subnav">
                <a href="<?php echo "newRequest.php" . '?workflow_id=' . $wId; ?>">
                    <i class="fa fa-file-invoice fa-2x"></i>
                    <span class="nav-text">
                        Solicitudes
                    </span>
                </a>
            </li>
        <?php }
        
        if ($workflow['wsender'] == 'Solicitar Turno') { ?>
            <li class="has-subnav">
                <a href="<?php echo "newRequest.php" . '?workflow_id=' . $wId; ?>">
                    <i class="fa fa-ticket fa-2x"></i>
                    <span class="nav-text">
                        <?php echo $workflow['workflow_name']; ?>
                    </span>
                </a>
            </li>
        <?php }
    }
} else {
    // Handle the case where the user's ID is not found
    // You can display an alternative message or take other actions here
    echo "---";
}
?>

                    <ul class="logout">
                        <li>
                            <a href="logout.php">
                                <i style="color:Tomato;"  class="fa fa-power-off fa-2x"></i>
                                <span  style="font-weight: bold; color:Tomato;" class="nav-text">
                                Salir
                                </span>
                            </a>
                        </li>
                    </ul>
                </ul>
            </nav>
        </aside>
    </div>

        <main class="container-login">
            <form style="width: 26rem;" class="p-5 m-2 bg-white shadow rounded"
                action="transacProfile.php" 
                method="post"
                enctype="multipart/form-data">

                <h4><span>Actualizar Perfil</span></h4>
                <hr>
                <!-- error -->
                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php } ?>
                <!-- success -->
                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_GET['success']; ?>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" 
                    class="form-control"
                    name="first_name"
                    value="<?php echo $sysRol['first_name']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellidos</label>
                    <input type="text" 
                    class="form-control"
                    name="last_name"
                    value="<?php echo $sysRol['last_name']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de Usuario</label>
                    <input type="text" 
                    class="form-control"
                    name="user_email"
                    value="<?php echo $sysRol['user_email']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña</label>
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
                    <br>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Actualizar</span></button>
                    </div>
                </div>
                <br><br>
                    <input type="hidden" name="id" value="<?php echo $user_data; ?>">
            </form>
        </main>

    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>
</html>
