<?php
// PDO Connection
$dbHostPDO = 'aqdb.cn2yi20ywt02.us-east-1.rds.amazonaws.com';
$dbNamePDO = 'AQDB';
$dbUserPDO = 'admin';
$dbPassPDO = 'Punset!22Wictaaf!96';

try {
    $pdo = new PDO("mysql:host=$dbHostPDO;dbname=$dbNamePDO;charset=utf8mb4", $dbUserPDO, $dbPassPDO);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO Database connection failed: " . $e->getMessage());
}

// MySQLi Connection
$dbHostMySQLi = 'aqdb.cn2yi20ywt02.us-east-1.rds.amazonaws.com';
$dbNameMySQLi = 'AQDB';
$dbUserMySQLi = 'admin';
$dbPassMySQLi = 'Punset!22Wictaaf!96';

$db = mysqli_connect($dbHostMySQLi, $dbUserMySQLi, $dbPassMySQLi, $dbNameMySQLi) or
    die ('MySQLi Unable to connect. Check your connection parameters.');
mysqli_set_charset($db, "utf8mb4");

?>
