<?php
include_once 'core/config/setting_setup.php';

// Handle form submission to send invitation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['user_email'];
    $userName = $_POST['user_name'];
    $token = generateToken(); // Generate a unique token for the user

    // Create the registration link with the token
    $registrationLink = "https://54.166.125.102/aqregistration.php?token=" . $token;

    // Send an email to the user with the registration link
    $subject = "Invitation to Register";
    
    // Create an HTML message with the registration link as a hyperlink
    $message = '<html ><body>';
    $message .= '<p>Hi '. htmlentities($userName, ENT_QUOTES, 'UTF-8') .'!</p>';
    $message .= '<p>You were invited to AQPlatform.</p>';
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
<script>
    function confirmDelete(userId) {
        if (confirm("Are you sure you want to delete this user account?")) {
            window.location.href = 'core/transactions/transacDelAcc.php?action=delete&id=' + userId;
        }
    }
</script>

    <div class="container container-table">
            <!-- Start addUsers Modal --> 
                <div class="container"> <div class="modal fade" id="addUsers" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form style="" class=""
                                    action="core/transactions/transacUser.php?action=addUser.php" 
                                    method="post"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="">Nombre</label>
                                        <input type="text" 
                                            class="form-control"
                                            name="first_name"
                                            id="first_name"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label class="">Apellidos</label>
                                        <input type="text" 
                                            class="form-control"
                                            name="last_name"
                                            id="last_name"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label class="">Correo Electrónico</label>
                                        <input type="email" 
                                            class="form-control"
                                            name="user_email"
                                            id="user_email"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contraseña</label>
                                        <div class="input-group">
                                            <input type="password"
                                            class="form-control"
                                            id="user_pass"
                                            name="user_pass"
                                            placeholder="********">
                                            <button type="button"
                                            class="btn btn-outline-secondary"
                                            id="togglePassword">👁️</button>
                                        </div>
                                    </div>
                        
                                    <div class="row">
                                        <div class="col" >
                                            <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Agregar Usuario</span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- End addUsers Modal -->
            <!-- Start invite Modal -->
                <div class="container"> <div class="modal fade" id="invite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form style="" class=""
                                    action="core/transactions/transacInviteNewUser.php"
                                    method="post"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label>Name:</label>
                                        <input type="text" class="form-control" name="user_name" required>
                                    </div>
                                    <!-- Input field for user's email -->
                                    <div class="mb-3">
                                        <label>Email:</label>
                                        <input type="email" class="form-control" name="user_email" required>
                                    </div>
                                    <!-- Submit button to send invitation -->
                                    <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Send Invitation</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- End invite Modal -->

            <main class="container-fluid my-1 p-5 border border-info content-table bg-white shadow rounded table-responsive">
                <div>
                    <a href="#" class="btn-menu btn-1 hover-filled-opacity" id="openModal-1"><span>Add New User</span></a>
                </div>
                <div>
                    <a href="#" class="btn-menu btn-1 hover-filled-opacity" id="openModal-2"><span>Invite to AQPlatform</span></a>
                </div>
                <div class="container-fluid p-3 mr-3 ">        
                    <table style="width: 100%; padding: 3rem;" id="templateTable" class="table table-bordered table-condensed table-hover">
                        <thead >
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>System Rol</th>
                                <th>Edit</th>
                                <th>Delete</th>        
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            mysqli_set_charset($db, "utf8");
                            $sql = "SELECT *, users.id AS pid
                            FROM users 
                            INNER JOIN users_by_sysgroup ON users_by_sysgroup.ubs_user_id = users.id
                            LEFT JOIN sys_groups ON sys_groups.id = users_by_sysgroup.ubs_sys_groups_id
                            ORDER BY user_email ASC";

                            $result = mysqli_query($db, $sql); // Execute the query

                            $count = 1;

                            if ($result) {

                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Your table rows here...
                                    ?>
                                    <tr>
                                        <td data-title='#'><?php echo $count; ?></td>
                                        <td data-title='Nombre'><?php echo $row['first_name']?></td>
                                        <td data-title='Apellidos'><?php echo $row['last_name']?></td>
                                        <td data-title='Nombre de Usuario'><?php echo $row['user_email']?></td>
                                        <td data-title='Rol de Sistema'><?php echo $row['sys_group_name']?></td>                 
                                        <td data-title='Opciones'>
                                            <?php echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="viewAdminProfile.php?action=edit&id=' . $row['pid'] . '"><span><i class="fa-solid fa-pen-to-square"></i></span></a>'; ?>
                                        </td>
                                        <td data-title='Borrar'>
                                            <?php echo '<a type="button" class="btn-menu-1 btn-1 hover-filled-opacity" href="javascript:void(0);" onclick="confirmDelete(' . $row['pid'] . ');"><span><i class="fa-regular fa-trash-can"></i></span></a>'; ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $count++;
                                }
                                mysqli_free_result($result); // Free the result set
                            } else {
                                echo "Error executing query: " . mysqli_error($db);
                            }
                            ?>
                            <!-- Display the workflowName as the heading -->
                        </tbody>
                    </table>
                </div>
            </main>
    </div>
    <footer id="myFooter" class="footer">
        <p>Document Control Systems Inc.</p>
    </footer>
</body>
</html>
