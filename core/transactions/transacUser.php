<?php

include_once '../../core/config/transacLoginSetup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $userEmail = isset($_POST['user_email']) ? $_POST['user_email'] : '';
    $userPass = isset($_POST['user_pass']) ? $_POST['user_pass'] : '';

    if (!empty($firstName)) {
        // Hash the password
        $hashedPass = password_hash($userPass, PASSWORD_DEFAULT);

        // Use prepared statement to insert data
        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, user_email, user_pass) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $userEmail, $hashedPass);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id; // Get the ID of the inserted user

            // Insert into users_by_sysgroup
            $stmt = $db->prepare("INSERT INTO users_by_sysgroup (ubs_user_id, ubs_sys_groups_id) VALUES (?, 2)");
            $stmt->bind_param("i", $userId);
            $stmt->execute();

            $stmt->close();


            $user_Id = $session_user;
            $logAction = "$userEmail - user added";
            $timestamp = date('Y-m-d H:i:s');

            $insertLogSql = "INSERT INTO audit_trails (audit_trail_user_id, audit_trail_action, audit_trail_timestamp)
                                VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $insertLogSql);
            mysqli_stmt_bind_param($stmt, "iss", $user_Id, $logAction, $timestamp);
            mysqli_stmt_execute($stmt);

            header("Location: ../../user_management.php?submitted=successfully");
            exit();
        } else {
            echo "Error: " . $stmt->error;
            header("Location: ../../login.php?error=database");
            exit();
        }
    } else {
        header("Location: ../../login.php?error=empty");
        exit();
    }
}
?>
