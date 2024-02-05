<?php
//Time Zone
date_default_timezone_set('America/Puerto_Rico');
setlocale(LC_TIME, 'es_ES.UTF-8');

//Database Connection
include_once 'core/config/config_db.php';

//functions
include_once 'core/assets/util/functions.php';

//Verify Session
session_start();

// Check if the user is not logged in and redirect to the login page
if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    echo "<script type=\"text/javascript\">
        alert('To continue you must log in.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

$session_user = $_SESSION['id'];

//Verify and Get User Information
$user_data = getUserById($session_user, $db);

//Verify and Get User Permissions
$sysRol = getSysRol($session_user, $db);

//Main  header
include_once 'core/assets/include/main_head.php';

//Main  Header
include_once 'core/assets/include/header.php';

//Main Sidebar
include_once 'core/assets/include/sidebar.php';
//include_once 'core/assets/include/socialSidebar.php';
?>

