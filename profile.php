<?php

include_once 'core/config/forms_setup.php';

?>     
        <main class="container-login">
            <form style="width: 30rem;" class="p-4 m-1 bg-white shadow rounded"
                action="core/transactions/transacProfile.php" 
                method="post"
                enctype="multipart/form-data">

                <h4><span>My Profile</span></h4>
                <hr>
                <!-- Display profile image if it exists -->
                <?php if (!empty($sysRol['profile_image']) && ($sysRol['profile_image'] != 'default-profile-image.png')): ?>
                    <div class="profile-image-container text-center mb-3">
                        <img src="core/assets/uploads/profile_images/<?php echo htmlspecialchars($sysRol['profile_image']); ?>" width="150px" height="100px" alt="Profile Image" style="border-radius: 7%; object-fit: cover;" id="profile-image" class="profile-image">
                        <!-- Delete Profile Image Button -->
                        <details>
                        <summary>Remove Profile Image</summary>
                        <button type="submit" name="delete_profile_image" class="btn btn-sm btn-outline-danger delete-profile-image">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
                <!-- Modal Structure -->
                <div id="profileModal" class="profile-modal">
                    <div class="profile-modal-content">
                        <div class="profile-modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6 modal-profile-info">
                                        <h4><?php echo $sysRol['first_name'] . " " . $sysRol['last_name']; ?></h4>
                                        <p><?php echo $sysRol['user_email']; ?></p>
                                        <p><?php echo $sysRol['sys_group_name']; ?></p>
                                        <p><?php echo $sysRol['position_title']; ?></p>
                                    </div>
                                    <div class="col-6 text-center modal-profile-info">
                                        <img src="core/assets/uploads/profile_images/<?php echo htmlspecialchars($sysRol['profile_image']); ?>" alt="Profile Image" class="modal-profile-image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($sysRol['first_name']); ?>">
                    <label for="first_name">First Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($sysRol['last_name']); ?>">
                    <label for="last_name">Last Name</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="user_email" name="user_email" placeholder="name@example.com" value="<?php echo htmlspecialchars($sysRol['user_email']); ?>">
                    <label for="user_email">Email</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="position_title" name="position_title" placeholder="position_title" value="<?php echo htmlspecialchars($sysRol['position_title']); ?>">
                    <label for="position_title">Position Title:</label>
                </div>

                <div class="form-floating mb-3">
                    <div class="input-group">
                        <input type="password" class="form-control" id="user_pass" name="user_pass" placeholder="Password">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">👁️</button>
                </div>

                <div class="mb-3">
                    <label for="profile_image" class="form-label">Select Profile Image</label>
                    <input class="form-control" type="file" id="profile_image" name="profile_image">
                </div>
                    <br>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Update</span></button>
                    </div>
                </div>
                    <input type="hidden" name="id" value="<?php echo $user_data; ?>">
            </form>
        </main>

    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>
</html>
