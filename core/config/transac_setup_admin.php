<?php
//Time Zone
date_default_timezone_set('America/Puerto_Rico');

//Database Connection
include_once '../../core/config/config_db.php';

//Main functions
include_once '../../core/assets/util/functions.php';

//Main functions
include_once '../../core/vendor/autoload.php';

// Verify Session
session_start();

$session_user = $_SESSION['id'];

// Verify and Get User Information
$user_data = getUserById($session_user, $db);

// Verify and Get User Permissions
$sysRol = getSysRol($session_user, $db);

$isAdmin = false; // Initialize isAdmin variable

if ($sysRol['ubs_sys_groups_id'] == '1') {
    $isAdmin = true;
}

if (!$isAdmin) {
    // User is not an admin, so end the session and redirect to login.php
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

