<?php
include_once '../../core/config/transacLoginSetup.php';

$modalMessage = ""; // Initialize modal message

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Prohibited strings verification
    $prohibitedStrings = ["administrator'--", "\$username = 1' or '1' = '1", "admin' --", "admin' #", "admin'/*", "' or 1=1--", "' or 1=1#", "' or 1=1/*", "') or '1'='1--", "') or ('1'='1--", '" or ""=""', "; DROP TABLE users; --", "; SELECT * FROM users WHERE username = 'admin' AND password = '123456'; --", "' UNION SELECT username, password FROM users WHERE 'x' = 'x", "; INSERT INTO users (username, password) VALUES ('hacker', 'password'); --", "' OR '1'='1", "' OR 'a'='a", "1'; UPDATE users SET password = 'hacked' WHERE username = 'admin'; --", "; DELETE FROM users WHERE 'x' = 'x'; --"];
    $prohibitedStringFound = false;
    $details = ""; // Initialize details

    foreach ($prohibitedStrings as $string) {
        if ($_POST['user_email'] === $string || $_POST['user_pass'] === $string) {
            $prohibitedStringFound = true;
            $details = "SQL Injection Attempt: $string";
            break;
        }
    }
    
    // Check if the "iamNotARobot" checkbox is checked
    if (!$prohibitedStringFound && !isset($_POST['iamNotARobot'])) {
        $details = "Login Failed";
    }

    // Get the user's IP address
    $userIP = getUserPublicIP(); 
    
    if ($prohibitedStringFound || !checkUserCredentials($_POST['user_email'], $_POST['user_pass'])) {
        // Insert into login_attempts table
        $currentTimestamp = date('Y-m-d H:i:s');
        $insertAttemptSql = "INSERT INTO login_attempts (ip_address, timestamp, details) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($db, $insertAttemptSql);
        mysqli_stmt_bind_param($stmt, "sss", $userIP, $currentTimestamp, $details);
        mysqli_stmt_execute($stmt);
        
        // Set session message for user
        $_SESSION['modalMessage'] = "Failed login attempt.";
        
        // Redirect user to login page
        header("Location: ../../login.php");
        exit();
    } else {
        // User authenticated, proceed with login
        // Clear any previous modal message
        $_SESSION['modalMessage'] = "";
        
        // Validate user input
        $email = validate1($_POST['user_email']);
        $password = validate1($_POST['user_pass']);

        // User and password validation using prepared statements
        $user = getUserByEmail($email);
        
        if ($user && password_verify($password, $user['user_pass'])) {
            // Fetch sys_group_name
            $sysGroupName = getSysRol($user['id'], $db);

            $_SESSION['id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['sys_group_name'] = $sysGroupName['sys_group_name']; // Store sys_group_name in the session

            // Insert log entry
            $userId = $user['id'];
            $logAction = "Log In";
            $timestamp = date('Y-m-d H:i:s');

            $insertLogSql = "INSERT INTO logs (logs_user_id, logs_action, logs_timestamp) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $insertLogSql);
            mysqli_stmt_bind_param($stmt, "iss", $userId, $logAction, $timestamp);
            mysqli_stmt_execute($stmt);

            // Store the exact timestamp in the session
            $_SESSION['logs_timestamp'] = $timestamp;

            // Redirect user to socialHome.php
            header('Content-Type: text/html; charset=utf-8');
            header("Location: ../../socialHome.php");
            exit();
        } else {
            // Incorrect email or password
            $_SESSION['modalMessage'] = "Your email or password is incorrect";
            header("Location: ../../login.php");
            exit();
        }
    }
}

function checkUserCredentials($email, $password) {
    // User and password validation using prepared statements
    global $db;
    
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT * FROM users WHERE user_email=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['user_pass'])) {
            return true; // User credentials are correct
        }
    }
    
    return false; // User credentials are incorrect
}

function getUserByEmail($email) {
    // Fetch user by email
    global $db;
    
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT * FROM users WHERE user_email=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return mysqli_fetch_assoc($result);
}

function getUserPublicIP() {
    // Get the user's public IP address using ipinfo.io
    $response = file_get_contents('https://ipinfo.io');
    $data = json_decode($response);

    // Extract the IP address from the response
    $ip = isset($data->ip) ? $data->ip : '';

    return $ip;
}

function validate1($data) {
    // User input validation
    return htmlspecialchars(stripslashes(trim($data)));
}

?>
