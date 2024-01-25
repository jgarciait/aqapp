<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/profileStyle.css">
    <title>AQPlatform</title>
</head>
<body>

<!-- Create a form to reset user password -->
<div class="login-box d-flex justify-content-center align-items-center vh-100">
 <form class="shadow2 w-450 p-3" method="POST" action="">
    <?php

    // Include necessary libraries and setup database connection
    require 'vendor/autoload.php'; // Composer autoloader
    require 'connection.php'; // Database connection setup

    // Check if the token is provided in the URL
 // ...

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Query the database to check if the token exists and is valid
    date_default_timezone_set('America/Puerto_Rico');
    $query = "SELECT * FROM reset_tokens WHERE token = ? AND expires_at <= NOW()";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Token is valid, allow the user to reset their password
        $userId = $row['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $newPassword = $_POST['new_password'];

            // Implement password complexity rules here
            if (strlen($newPassword) < 8) {
                echo "Password must be at least 8 characters long.";
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the user's password in the database
                $updateQuery = "UPDATE users SET user_pass = ? WHERE id = ?";
                $stmt = mysqli_prepare($db, $updateQuery);
                mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $userId);
                mysqli_stmt_execute($stmt);

                // Delete the used token from the reset_tokens table
                $deleteQuery = "DELETE FROM reset_tokens WHERE token = ?";
                $stmt = mysqli_prepare($db, $deleteQuery);
                mysqli_stmt_bind_param($stmt, "s", $token);
                mysqli_stmt_execute($stmt);

                echo "Password reset successfully. You can now <a href='index.php'>login</a> with your new password.";
            }
        }
      
        ?>
        <!-- Reset Password Form -->
        <h2>Reset Password</h2>
        <label for="new_password">New Password:</label>
        <div class="mb-3">
            <input class="form-control" type="password" name="new_password" required>
            
        </div>
        <div class="mb-3">
            <button class="form-control btn btn-sm" type="submit">Reset Password</button>
        </div>
        <?php
    } else {
      echo "Enlace expirado. Solicite nuevamente un nuevo enlace de recuperación."; 
        // Display input and submit button area only if the token is valid
    }
} else {
    // Token is not provided in the URL
    echo "Token not provided. Please use the link from your email to reset your password.";
}
    ?><a type="button" class="btn" style="color: #2194a5; font-weight: 500;" href="forgot-password.php"><small>Forgot Password</small></a>
    </form>
</div>

</body>
</html>
