<?php

date_default_timezone_set('America/Puerto_Rico');
session_start();

include_once "core/config/config_db.php";

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $logAction = "Log Out";
    $timestamp = date('Y-m-d H:i:s');

    // Insert log entry
    $insertLogSql = "INSERT INTO logs (logs_user_id, logs_action, logs_timestamp)
                     VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $insertLogSql);
    mysqli_stmt_bind_param($stmt, "iss", $userId, $logAction, $timestamp);
    mysqli_stmt_execute($stmt);
}

session_unset();
session_destroy();

header("Location: ../../index.php");
?>
