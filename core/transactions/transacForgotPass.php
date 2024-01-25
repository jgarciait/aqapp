<?php
session_start();
include_once 'core/config/config_db.php';
include_once 'core/assets/util/functions.php';

$modalMessage = ''; // Initialize a message variable to hold the modal message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize user input
    $email = $_POST['user_email'];

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $modalMessage = "The email entered is not valid.";
    } else {
        // Check if the email exists in the users table
        $query = "SELECT COUNT(*) as count FROM users WHERE user_email = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        if ($row['count'] > 0) {
            // Email exists, retrieve the user_id from the database
            $query = "SELECT id FROM users WHERE user_email = ?";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $userRow = mysqli_fetch_assoc($result);
            
            if ($userRow) {
                $userId = $userRow['id']; // Set $userId to the retrieved user_id
                // Generate a unique token
                $token = generateToken();
                
                // Insert the token into the reset_tokens table
                if (insertResetToken($userId, $token, $db)) {
                    // Construct the reset link with the token
                    $resetLink = "http://http://54.166.125.102//reset-password.php?token=" . $token;
                    
                    // Send the reset link email
                    if (sendResetLinkEmail($email, $resetLink)) {
                        $modalMessage = "Enviamos un enlace de recuperación a su correo electrónico.";
                    } else {
                        $modalMessage = "Error al enviar el correo electrónico. Por favor, inténtelo de nuevo más tarde.";
                    }
                } else {
                    $modalMessage = "Error al generar el token. Por favor, inténtelo de nuevo más tarde.";
                }
            } else {
                // Handle the case where user_id is not found
                $modalMessage = "Error al obtener la información del usuario. Por favor, inténtelo de nuevo más tarde.";
            }
        } else {
            // Email doesn't exist, set error message
            $modalMessage = "El correo electrónico ingresado no existe o es incorrecto.";
        }
    }    
    // Set the appropriate modal message based on the verification steps
    $_SESSION['modalMessage'] = $modalMessage;
    header("Location: ../../login.php"); // Redirect to index.php with the appropriate modal message
    exit();
}
?>