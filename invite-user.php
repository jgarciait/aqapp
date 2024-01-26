<?php
include_once 'core/config/forms_settings_setup.php';

// Handle form submission to send invitation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['user_email'];
    $userName = $_POST['user_name'];
    $token = generateToken(); // Generate a unique token for the user

    // Create the registration link with the token
    $registrationLink = "https://www.uccpr.app/aqregistration.php?token=" . $token;

    // Send an email to the user with the registration link
    $subject = "Invitation to Register";
    
    // Create an HTML message with the registration link as a hyperlink
    $message = '<html ><body>';
    $message .= '<p>Hola '. htmlentities($userName, ENT_QUOTES, 'UTF-8') .'!</p>';
    $message .= '<p>Usted fue invitado a registrarse en AQPlatform.</p>';
    $message .= '<a href="' . $registrationLink . '">Register</a>';
    $message .= '</body></html>';

    // Additional headers for HTML email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    // Insert the token into the reset_tokens table
    if (insertResetTokenForNewUser($email, $token, $db)) {
        // Token inserted successfully
        if (sendEmail($email, $subject, $message, $headers)) {
            // Email sent successfully
            echo "Invitation sent successfully.";
        } else {
            // Email sending failed
            echo "Error sending invitation.";
        }
        ?>
        <script type="text/javascript">
        alert("Invitación enviada");
        window.location = "home.php";
        </script>
    <?php
    } else {
        // Token insertion failed
        echo "Error sending invitation. Please try again later.";
    }
}

?>
        <main class="container-login">
            <form style="width: 26rem;" class="p-5 m-2 bg-white shadow rounded"
                action="invite-user.php"
                method="post"
                enctype="multipart/form-data">
           
        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" class="form-control" name="user_name" required>
        </div>
        <!-- Input field for user's email -->
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" class="form-control" name="user_email" required>
        </div>
        <!-- Submit button to send invitation -->
         <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Enviar Invitación</span></button>
            </form>
        </main>

    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>
</html>
