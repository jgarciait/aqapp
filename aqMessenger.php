<?php

include_once 'core/config/forms_setup.php';


?>     
        <main class="container-login users">
        <div style="width: 30rem;" class="p-4 m-1 bg-white shadow rounded">
                <h4 class="text-center py-2"><span>AQMessenger</span></h4>
            <div class="d-flex align-items-center" > <!-- Set a specific height for the container -->
                <div class="pe-3"> <!-- Adds some spacing to the right of the image -->
                    <img src="core/assets/uploads/profile_images/<?php echo htmlspecialchars($sysRol['profile_image']); ?>" alt="Profile Image" style="width:40px; height:40px; border-radius: 50%; object-fit: cover;">
                </div>
                <div>
                    <?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?>
                    <?php if ($sysRol['status'] == 'Online') {
                        echo '<p style="color:green; margin-bottom: 0; padding: 0;">Online</p>';
                    } else if ($sysRol['status'] == 'Do not disturb') {
                        echo '<p style="color:red; margin-bottom: 0; padding: 0;">Do not disturb</p>';
                    } else if ($sysRol['status'] == 'Offline'){
                        echo '<p style="color:grey; margin-bottom: 0; padding: 0;">Offline</p>';
                    }
                    ?>
                </div>
            </div>
            <hr>
            <div class="mb-3">
                <div class="d-flex form-floating contact-search-1">
                    <input type="text" class="form-control" id="contact-user" name="contact-user" placeholder="Search">
                    <label for="contact-user" class="form-label">Enter name to search contact...</label>
                </div>
            </div>
            <div class="contacts-list" style="width: 28rem; height: 500px; overflow-y: auto;">
            </div>
        </main>

    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>
</html>
