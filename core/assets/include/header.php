<?php

$sql = "SELECT in_app_noti, email_noti FROM noti_preference WHERE np_user_id = :userId";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':userId' => $session_user));
$userPreferences = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the query returned any results
if (!$userPreferences || !is_array($userPreferences)) {
    // Set default values if no preferences found
    $userPreferences = array('in_app_noti' => 0, 'email_noti' => 0);
}

echo "<script>
var userInAppNotiEnabled = " . (int)$userPreferences['in_app_noti'] . ";
</script>";
?>

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
                <div class="header-profile-image-wrapper">
                    <img src="core/assets/uploads/profile_images/<?php echo htmlspecialchars($sysRol['profile_image']); ?>" alt="Profile Image" class="header-profile-image">
                </div>
                <ul class="shadow border profile-menu">
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="aqMessenger.php">AQMessenger</a></li>
                    <li><a href="userPreferences.php">Preferences</a></li>  
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
