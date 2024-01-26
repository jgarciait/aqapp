<?php

include_once 'core/config/setting_setup.php';

// An array to translate English day names to Spanish
$dayTranslations = array(
    'Monday' => 'lunes',
    'Tuesday' => 'martes',
    'Wednesday' => 'miércoles',
    'Thursday' => 'jueves',
    'Friday' => 'viernes',
    'Saturday' => 'sábado',
    'Sunday' => 'domingo',
);

// Get the current date and time
$currentTime = new DateTime('now', new DateTimeZone('America/Puerto_Rico'));

// Format the date in Spanish, including the day of the week
$currentDate = $currentTime->format('l, d \d\e F \d\e Y');

// Replace English day and month names with Spanish equivalents
$currentDate = strtr($currentDate, $dayTranslations);
$monthTranslations = array(
    'January' => 'enero',
    'February' => 'febrero',
    'March' => 'marzo',
    'April' => 'abril',
    'May' => 'mayo',
    'June' => 'junio',
    'July' => 'julio',
    'August' => 'agosto',
    'September' => 'septiembre',
    'October' => 'octubre',
    'November' => 'noviembre',
    'December' => 'diciembre',
);
$currentDate = strtr($currentDate, $monthTranslations);

?>

    <div class="container container-data-card">
 
        <main class="content-data-card border border-info bg-white shadow my-3">
            <?php if (!empty($sysRol)) { // Check if $user_data is not empty ?>
                <!-- Admin Verification -->
                <?php if ($sysRol['ubs_sys_groups_id'] == '1') { ?>
                     
                            <a class="data-card" href="user_management.php">
                                <div><i class="fas fa-users fa-lg" style="color: #11538d;"></i></div>
                                <p>User Management</p>
                            </a>
                 
                            <a class="data-card" href="modulesList.php">
                                <div><i class="fas fa-network-wired fa-lg" style="color: #11538d;"></i></div>
                                <p>Module Management</p>
                            </a>
                 
                            <a class="data-card" href="settingsShift.php">
                                <div><i class="fas fa-business-time fa-lg" style="color: #11538d;"></i></div>
                                <p>Shift Settings</p>
                            </a>

                            <a class="data-card" href="invite-user.php">
                                <div><i class="fas fa-share" style="color: #11538d;"></i></div>
                                <p>Invite to AQPlatform</p>
                            </a>
                            <a class="data-card" href="logs.php">
                                <div><i class="fas fa-clock-rotate-left" style="color: #11538d;"></i></div>
                                <p>Logs Monitor</p>
                            </a>
                            <a class="data-card" href="audit_trail.php">
                                <div><i class="fas fa-user-secret" style="color: #11538d;"></i></div>
                                <p>Audit Trail Monitor</p>
                            </a>
             
            <?php }} ?>
        </main>
        
    </div>
    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
    </footer>
</body>

</html>
