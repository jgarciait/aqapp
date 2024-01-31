<body>
    <header class="header">

         <div class="header-content">
            <p style="" class="shine">CANOVANS ONLINE</p>
        </div>
        <div class="notification-bar">
            <div class="notification-content">
                <!-- Notification messages will be displayed here -->
            </div>
        </div>
        <nav class="profile"><!-- Navigation Bar Starts Here -->
                <span class="profile-text">
                    <?php echo $_SESSION['first_name'] . " " .
                    $_SESSION['last_name'] . " - " . 
                    $_SESSION['sys_group_name']; ?>
                </span>
                <img class="display-picture" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Windows_10_Default_Profile_Picture.svg/64px-Windows_10_Default_Profile_Picture.svg.png" alt="Profile Image" width="40">
            <ul class="shadow border profile-menu"><!-- Profile Menu -->
                <li><a href="profile.php">Profile</a></li>
               <!-- <li><a href="#">Account</a></li> -->
               <!-- <li><a href="#">Settings</a></li> -->
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav>
    </header>
