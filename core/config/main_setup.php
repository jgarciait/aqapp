<?php

//Database Connection
include_once 'core/config/config_db.php';

//functions
include_once 'core/assets/util/functions.php';

//JS Scripts
include_once 'core/assets/script/main.js';

//Verify Session
session_start();

// Check if the user is not logged in and redirect to the login page
if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    echo "<script type=\"text/javascript\">
        alert('Para continuar debes iniciar sesión.');
        window.location.href = 'index.php';
    </script>";
    exit();
}

$session_user = $_SESSION['id'];

//Verify and Get User Information
$user_data = getUserById($session_user, $db);

//Verify and Get User Permissions
$sysRol = getSysRol($session_user, $db);

//Time Zone
date_default_timezone_set('America/Puerto_Rico');
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Demo</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

    <!-- Index (login) app CSS Style -->
    <link rel="stylesheet" type="text/css" href="core/assets/css/homeStyle.css">

    <!-- Embeded Map for GPS Features. Need to be removed. -->
    <script type="text/javascript" src="https://www.bing.com/api/maps/mapcontrol?key=AkdPaHyrL21_U0E9Gdvkl74fuoBSnbl6QyWT6WuLUrT0COJryzTbcprcqceoP9Pc&callback=loadMapScenario" async defer></script>
</head>