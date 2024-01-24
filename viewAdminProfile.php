<?php 
include_once 'core/config/forms_settings_setup.php';

$edit_profile = $_GET['id'];
$sysRol = getSysRol($edit_profile, $db);

?>

        <main class="container-login">
            <form style="width: 26rem;" class="p-5 m-2 bg-white shadow rounded"
                action="core/transactions/transacUserManagement.php" 
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
                    <label class="form-label">First Name</label>
                    <input type="text" 
                    class="form-control"
                    name="first_name"
                    value="<?php echo $sysRol['first_name']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" 
                    class="form-control"
                    name="last_name"
                    value="<?php echo $sysRol['last_name']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" 
                    class="form-control"
                    name="user_email"
                    value="<?php echo $sysRol['user_email']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">User Rol</label>
                    <select class="form-select" name="ubs_sys_groups_id">
                        <option value="3"<?php if ($sysRol['ubs_sys_groups_id'] == '3') echo ' selected'; ?>>Guest</option>
                        <option value="2"<?php if ($sysRol['ubs_sys_groups_id'] == '2') echo ' selected'; ?>>Standard User</option>
                        <option value="1"<?php if ($sysRol['ubs_sys_groups_id'] == '1') echo ' selected'; ?>>Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
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
                    <input type="hidden" name="id" value="<?php echo $edit_profile; ?>">
            </form>
        </main>

    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>
</html>


