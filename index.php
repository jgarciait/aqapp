<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AQPlatform</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    
    <!-- Index (login) app CSS Style -->
    <link rel="stylesheet" type="text/css" href="core/assets/css/indexStyle.css">
</head>

<body style="background: #34354138;" class="body-container">
    <?php
    if (isset($_SESSION['modalMessage'])) {
        $modalMessage = $_SESSION['modalMessage'];
        echo "<script>
                document.getElementById('modal-text').textContent = '$modalMessage';
                document.getElementById('myModal').style.display = 'block';
              </script>";
        unset($_SESSION['modalMessage']); // Clear the session variable
    }
    ?>
    <header class="header">
        <div class="header-content">
            <p style="" class="shine">AQPlatform</p>
        </div>
    </header>
    <div class="container p-3">
        <main class="container-login">
            <form style="width: 26rem;" class="p-5 bg-white shadow rounded" action="core/transactions/login.php"
                method="post" id="loginForm">
                <a href="" class="logo">
                    <div style="margin-top: 0%; text-align: center;">
                        <img src="core/assets/css/src/logo-dcs.png" alt="" style="width: 10rem; margin: 1rem;"
                            class="brand-img pb-2">
                    </div>
                </a>
                <div class="mb-3">
                    <label for="user_email">Correo electrónico:</label>
                    <input class="form-control" id="user_email" autocomplete="on" type="text"
                        placeholder="example@email.com" name="user_email" required="">
                </div>
                <div class="mb-3">
                    <label for="user_pass">Contraseña:</label>
                    <div class="input-group">
                        <input type="password" autocomplete="on" class="form-control" id="user_pass"
                            name="user_pass" placeholder="********">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">👁️</button>
                    </div>
                </div>
                <div class="row" style="text-align: center;">
                    <div class="col">
                        <label style="display: inline-block;">
                            <input type="checkbox" id="iamARobot" name="iamARobot" required> No soy Robot
                        </label>
                    </div>
                    <div class="col">
                        <label style="display: inline-block;">
                            <input type="checkbox" id="iamNotARobot" name="iamNotARobot"> Soy Robot
                        </label>
                    </div>
                </div>
                <div id="robotModal" class="modal">
                    <div class="modal-content">
                        <div>
                            <span class="close" id="closeRobotModal">&times;</span>
                        </div>
                        <i class="fa-solid fa-robot fa-xl"></i>
                        <div class="mb-3 p-3">
                            <p>¿Estas seguro que eres un Robot?</p>
                        </div>
                    </div>
                </div>
                <!-- Add a checkbox to allow the user to remember their email -->
                <div class="mb-3 my-4" style="text-align:center;">
                    <button class="button1" type="submit" style="width: 80%; text-align:center; color: white;
                        background-color: #308ccb; border: 1px solid #c5c5c5; border-radius: .25rem; margin-top: 2%;">
                        Log in
                    </button>
                </div>
                <div class="mb-3 my-4">
                    <a type="button" class="btn forgot-password-link" id="openModal-1" href="#">
                        <small >Olvidé Contraseña</small>
                    </a>
                </div>
            </form>
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeModal">&times;</span>
                    <p id="modal-text"></p>
                </div>
            </div>
            <div class="modal fade" id="forgotPass" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form  class="p-2 m-1 bg-white shadow rounded" action="transacForgotPass.php" method="post">
                                <div class="mb-3">
                                    <label for="user_email">Ingrese su correo electrónico:</label>
                                </div>
                                <div class="mb-3">  
                                    <input class="form-control" placeholder="example@email.com" type="email"
                                        name="user_email" required>
                                </div>
                                <div class="mb-3 justify-content-center align-items-center">
                                    <button class="btn-menu btn-1 hover-filled-opacity" type="submit"><span>Enlace de recuperación</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <footer id="myFooter" class="footer">
        <p>
            <img src="https://documentcontrol.com/wp-content/uploads/2023/04/logo-dcs_clipped_rev_1.png" 
                alt="Document Control System Inc." style="width: 2rem; margin: .2rem;" class="brand-img pb-1">
            © 2023 All Rights Reserved - Document Control Systems Inc.
        </p>
    </footer>
</body>

</html>
