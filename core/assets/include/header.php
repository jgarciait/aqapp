<body>
<header class="header">
    <div class="nav-container">
        <!-- First Column (Empty for now) -->
        <div class="nav-column first-column">
            <!-- This column is intentionally left blank -->
        </div>

        <!-- Second Column (Header Content) -->
        <div class="nav-column second-column">
            <p class="shine">AQPLATFORM</p>
        </div>

        <!-- Third Column (Contains two sub-columns: Profile and Notification Area) -->
        <div class="nav-column third-column">
            <nav class="profile">
                <!-- Profile Section -->
                <div class="profile-text px-2">
                    <?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] . " - " . $_SESSION['sys_group_name']; ?>
                </div>
                <img class="display-picture" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Windows_10_Default_Profile_Picture.svg/64px-Windows_10_Default_Profile_Picture.svg.png" alt="Profile Image" width="40">
                <ul class="shadow border profile-menu">
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
            </nav>
            <div class="nf-all px-3">
                <!-- Notification Area -->
                <div class="nf-area py-2">
                    <a class="btn-noti" id="notification-btn">
                    <i class="fas fa-bell fa-lg"></i></a>
                    <span id="nf-n">...</span>
                </div>
                <div class="nf-message" id="notifications"></div>
            </div>
        </div>
    </div>
</header>
