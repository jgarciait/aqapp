<?php

include_once '../../core/config/transacLoginSetup.php';

$modalMessage = ""; // Initialize modal message


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Prohibited strings verification
    $prohibitedStrings = ["administrator'--", "\$username = 1' or '1' = '1", "admin' --", "admin' #", "admin'/*", "' or 1=1--", "' or 1=1#", "' or 1=1/*", "') or '1'='1--", "') or ('1'='1--", '" or ""=""'];
    $prohibitedStringFound = false;

    foreach ($prohibitedStrings as $string) {
        if ($_POST['user_email'] === $string || $_POST['user_pass'] === $string) {
            $prohibitedStringFound = true;
            break;
        }
    }

    if ($prohibitedStringFound) {
        $userIP = getUserPublicIP(); // Get the user's IP address
        $_SESSION['modalMessage'] = "SQL Injection attempt detected. Intento de Injección SQL detectado del siguiente IP: $userIP. Your IP will be registered. Su IP será registrado.";
        header("Location: index.php");
        exit();
    } else {
        // User input validation
        function validate1($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        $email = validate1($_POST['user_email']); // Validate user input
        $password = validate1($_POST['user_pass']); // Validate user input

        // Step 2: User and password validation using prepared statements
        mysqli_set_charset($db, "utf8mb4");
        $sql = "SELECT * FROM users WHERE user_email=?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row['user_pass'])) {
                // Step 3: Captcha validation (if the checkbox is checked)
                if (isset($_POST['iamARobot'])) {
                     // Fetch sys_group_name
                    $sysGroupName = getSysRol($row['id'], $db);
                    
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];
                    $_SESSION['sys_group_name'] = $sysGroupName['sys_group_name']; // Store sys_group_name in the session


                    // Insert log entry
                    $userId = $row['id'];
                    $logAction = "Log In";
                    $timestamp = date('Y-m-d H:i:s');

                    $insertLogSql = "INSERT INTO logs (logs_user_id, logs_action, logs_timestamp) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($db, $insertLogSql);
                    mysqli_stmt_bind_param($stmt, "iss", $userId, $logAction, $timestamp);
                    mysqli_stmt_execute($stmt);

                    // Store the exact timestamp in the session
                    $_SESSION['logs_timestamp'] = $timestamp;
                    header('Content-Type: text/html; charset=utf-8');
                    header("Location: ../../home.php");
                    exit();
                } else {
                    // If the checkbox is not checked, show a message or take appropriate action
                    $modalMessage = "Por favor, confirme que no eres un robot.";
                }
            } else {
                $modalMessage = "La contraseña o nombre de usuario ingresado es incorrecto.";
            }
        } else {
            $modalMessage = "La contraseña o nombre de usuario ingresado es incorrecto.";
        }
    }

    // Set the appropriate modal message based on the verification steps
    $_SESSION['modalMessage'] = $modalMessage;
    header("Location: login.php"); // Redirect to index.php with the appropriate modal message
    exit();
}

function getUserPublicIP() {
    // Get the user's public IP address using ipinfo.io
    $response = file_get_contents('https://ipinfo.io');
    $data = json_decode($response);

    // Extract the IP address from the response
    $ip = isset($data->ip) ? $data->ip : '';

    return $ip;
}
?>
