<?php 
session_start();
date_default_timezone_set('America/Puerto_Rico');
if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    header("Location: login.php");
    exit;
}

include "connection.php";
include 'functions.php';

$edit_profile = $_GET['id'];
$edit_profile_data = getUserById2($edit_profile, $db);

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/profileStyle.css">
</head>
<body>
    <?php if ($edit_profile_data) { ?>

    <div class="d-flex justify-content-center align-items-center vh-100">
        
        <form class="shadow w-450 p-3"
            action="transacAdminProfile.php"
            method="post"
            enctype="multipart/form-data"
            >

            <h4 class="display-4 fs-1">Editar Perfil</h4><br>
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
               
                <input type="text" 
                       class="form-control"
                       name="id"
                       value="<?php echo $edit_profile_data['userid']; ?>"
                       readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" 
                       class="form-control"
                       name="first_name"
                       value="<?php echo $edit_profile_data['first_name']; ?>"
                       readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellidos</label>
                <input type="text" 
                       class="form-control"
                       name="last_name"
                       value="<?php echo $edit_profile_data['last_name']; ?>"
                       readonly>
            </div>
    
      <div class="mb-3">
        <label class="user_area_id">Área</label>
        <select class="form-control" id="id" name="user_area_id">
         <option value="<?php echo $edit_profile_data['user_area_id']; ?>"><?php echo $edit_profile_data['user_area_name']; ?></option>
        <?php

            // Query to select options from the table
            $sql = "SELECT * FROM user_area";

             // Execute the query
            $result = $db->query($sql);

            // Check if any rows were returned
             if ($result->num_rows > 0) {
                // Loop through the rows and create an option for each record
                while ($row = $result->fetch_assoc()) {
                    // Output the option HTML
                    echo '<option value="'. $row["id"] . '">'. $row["user_area_name"] . '</option>';
                }
            }

        ?>      
        </select>
    </div>
   
         <div class="mb-3">
        <label class="user_sys_group_id">Rol de Sistema</label>
        <select class="form-control" id="user_sys_group_id" name="user_sys_group_id">
        <option value="<?php echo $edit_profile_data['user_sys_group_id']; ?>"><?php echo $edit_profile_data['sys_group_name']; ?> (Valor Actual)</option>
        <?php

            // Query to select options from the table
            $sql = "SELECT * FROM sys_groups";

             // Execute the query
            $result = $db->query($sql);

            // Check if any rows were returned
             if ($result->num_rows > 0) {
                // Loop through the rows and create an option for each record
                while ($row = $result->fetch_assoc()) {
                    // Output the option HTML
                    echo '<option value="'. $row["id"] . '">'. $row["sys_group_name"] . '</option>';
                }
            }

        ?>      
        </select>
    </div>
     <div class="mb-3">
        <label class="user_workflows_type_id">Rol de Aprobaciones</label>
        <select class="form-control" id="user_workflows_type_id" name="user_workflows_type_id">
        <option value="<?php echo $edit_profile_data['user_workflows_type_id']; ?>"><?php echo $edit_profile_data['workflows_type_name']; ?> (Valor Actual)</option>
        <?php

            // Query to select options from the table
            $sql = "SELECT * FROM workflows_type";

             // Execute the query
            $result = $db->query($sql);

            // Check if any rows were returned
             if ($result->num_rows > 0) {
                // Loop through the rows and create an option for each record
                while ($row = $result->fetch_assoc()) {
                    // Output the option HTML
                    echo '<option value="'. $row["id"] . '">'. $row["workflows_type_name"] . '</option>';
                }
            }

        ?>      
        </select>
    </div>

<div class="mb-3">
    <label class="user_workflows_id">Seleccione Flujo de Aprobaciones</label>
<select class="form-control" id="user_workflows_id" name="user_workflows_id">
    <option value="<?php echo $edit_profile_data['user_workflows_id']; ?>"><?php echo $edit_profile_data['workflow_name']; ?> (Valor Actual)</option>
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
        <label class="user_workflows_level_id">Nivel de Aprobación</label>
        <select class="form-control" id="user_workflows_level_id" name="user_workflows_level_id">
        <option value="<?php echo $edit_profile_data['user_workflows_level_id']; ?>"><?php echo $edit_profile_data['workflows_level_name']; ?> (Valor Actual)</option>
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

    <div class="mb-3">
        <label class="form-label">Nombre de Usuario</label>
            <input type="text" 
            class="form-control"
            name="user_email"
            value="<?php echo $edit_profile_data['user_email']; ?>"
            readonly>
    </div>
    <div class="row">
        <div class="col">
            <button type="submit" class="btn"  style="font-size: 14px; color:white; background-color:#4f1964;">Actualizar</button>
        </div>
        <div class="col">
            <a href="home.php" class="btn"  style="font-size: 14px; color:white; background-color:#4f1964;" >Ir a Menu</a>
        </div>
        <div class="col">
            <a href="usersAccount.php" class="btn"  style="font-size: 14px; color:white; background-color:#4f1964;" >Ver Usuarios</a>
        </div>
    </div>
        <input type="hidden" name="id" value="<?php echo $edit_profile; ?>">
    </form>

    <?php } else { 
        header("Location: home.php");
        exit;
    } ?>
</body>
</html>


<script>


