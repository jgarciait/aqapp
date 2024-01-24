<?php

include_once 'core/config/forms_setup.php';

?>     
        <main class="container-login">
            <form style="width: 26rem;" class="p-5 m-2 bg-white shadow rounded"
                action="core/transactions/transacProfile.php" 
                method="post"
                enctype="multipart/form-data">

                <h4><span>Actualizar Perfil</span></h4>
                <hr>
                <!-- error -->
                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php } ?>
                <!-- success -->
                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_GET['success']; ?>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" 
                    class="form-control"
                    name="first_name"
                    value="<?php echo $sysRol['first_name']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellidos</label>
                    <input type="text" 
                    class="form-control"
                    name="last_name"
                    value="<?php echo $sysRol['last_name']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de Usuario</label>
                    <input type="text" 
                    class="form-control"
                    name="user_email"
                    value="<?php echo $sysRol['user_email']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña</label>
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
                    <br>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Actualizar</span></button>
                    </div>
                </div>
                <br><br>
                    <input type="hidden" name="id" value="<?php echo $user_data; ?>">
            </form>
        </main>

    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>
</html>
