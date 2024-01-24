<?php
//Time Zone
date_default_timezone_set('America/Puerto_Rico');

//Database Connection
include_once 'core/config/config_db.php';

//Main functions
include_once 'core/assets/util/functions.php';

//Verify Session
session_start();

// Check if the user is not logged in and redirect to the login page
if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    echo "<script type=\"text/javascript\">
        alert('Para continuar debes iniciar sesión.');
        window.location.href = '../../login.php';
    </script>";
    exit();
}

$session_user = $_SESSION['id'];

//Verify and Get User Information
$user_data = getUserById($session_user, $db);

//Verify and Get User Permissions
$sysRol = getSysRol($session_user, $db);


//Main  Head
include_once 'core/assets/include/forms_head.php';

//Main  Header
include_once 'core/assets/include/header.php';

//Main Sidebar
include_once 'core/assets/include/sidebar.php';
?>

