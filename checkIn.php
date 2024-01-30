<?php

include_once 'core/config/forms_setup.php';

$user = $_SESSION['id'];
$workflow_id = $_GET['workflow_id'];
$user_data2 = getUserById2($user, $workflow_id, $db);

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




<body>
    <div class="d-flex justify-content-center vh-100">
    
        <form class="shadow w-450 p-3" role="form" method="POST" action="transacAttendance.php?workflow_id=<?php echo $workflow_id; ?>" id="submitReport">
        
            <div class="header">
                <h2>Ponchador</h2>
                <p><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] . " - " . $user_data2['wcreator_name']; ?></p>
                <small><?php echo $currentDate; ?></small>
            </div>

            <div class="tick-container">
                <div class="tick" data-did-init="setupFlip">
                    <!-- Hide visual content from screen readers with `aria-hidden` -->
                    <div data-repeat="true" aria-hidden="true">
                        <span data-view="flip"></span>
                    </div>
                </div>
            </div>

            <hr>

            <div id="reader"></div>
            <div id="result"></div>
            <div class="row">
                <input type="hidden" id="from_date_hidden" name="from_date">
                <input type="hidden" id="to_date_hidden" name="to_date">
                <div>
                    <input type="hidden" name="result" id="resultInput">
                </div>
                <?php
                $i = $_SESSION['id'];
                ?>
                <input type="hidden" name="users_id" id="users_id" value="<?php echo $i; ?>">

            </div>
            <?php
                // Check if the user has punched the start shift for today
                $timestamp = date('Y-m-d H:i:s');
                $currentDate = date('Y-m-d');
                $sqlCheckStartShiftPunch = "SELECT id FROM shift_table 
                                            WHERE DATE(start_time) = :current_date AND sst_user_id = :sst_user_id";
                $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
                $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
                $stmtCheckStartShiftPunch->bindParam(':sst_user_id', $user);
                $stmtCheckStartShiftPunch->execute();
                $startShiftPunchId = $stmtCheckStartShiftPunch->fetchColumn();

                if ($startShiftPunchId === false) {
                    // The user hasn't punched the start shift for today
                           echo '<br><div class="row"><br>';
                                echo '<div class="col">';
                                echo '<input type="hidden" name="start_time" value="' . $timestamp . '">';
                                echo '<button style="color:white; font-size: 14px; background-color:#215f92;" type="submit" class="btn">Entrada Turno</button>';
                                echo '</div> <div class="col">';
                                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">Menu Principal</a>';
                                echo '</div>';
                                echo '</div>';
                } else {
                            // Check if there is another start_b1_time punch for the current user today
                            $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM startb1
                                                WHERE DATE(start_b1_time) = :current_date AND b1_shift_id = :b1_shift_id";

                            $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
                            $stmtCheckSameDayPunch->bindParam(':current_date', $currentDate);
                            $stmtCheckSameDayPunch->bindParam(':b1_shift_id', $startShiftPunchId);
                            $stmtCheckSameDayPunch->execute();
                            $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

                            // If the user hasn't punched the end break AM punch for today show the checkbox to punch
                            if ($sameDayPunchCount === 0) {
                                      echo '<br><div class="row"><br>';
                                echo '<div class="col">';
                                echo '<input type="hidden" name="start_b1_time" value="' . $timestamp . '">';
                                echo '<button style="color:white; font-size: 14px; background-color:#215f92;" type="submit" class="btn">Entrada Break AM</button>';
                                echo '</div> <div class="col">';
                                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">Menu Principal</a>';
                                echo '</div>';
                                echo '</div>';

                            } else {
                                $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM endb1
                                                    WHERE DATE(end_b1_time) = :current_date AND eb1_shift_id = :eb1_shift_id";

                                $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
                                $stmtCheckSameDayPunch->bindParam(':current_date', $currentDate);
                                $stmtCheckSameDayPunch->bindParam(':eb1_shift_id', $startShiftPunchId);
                                $stmtCheckSameDayPunch->execute();
                                $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

                                // If the user hasn't punched the end break M punch for today show the checkbox to punch
                                if ($sameDayPunchCount === 0) {
                                   echo '<br><div class="row"><br>';
                                echo '<div class="col">';
                                echo '<input type="hidden" name="end_b1_time" value="' . $timestamp . '">';
                                echo '<button style="color:white; font-size: 14px; background-color:#215f92;" type="submit" class="btn">Salida Break AM</button>';
                                echo '</div> <div class="col">';
                                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">Menu Principal</a>';
                                echo '</div>';
                                echo '</div>';
             

                            } else {
                                $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM startlunch
                                                    WHERE DATE(start_lunch) = :current_date AND sl_shift_id = :sl_shift_id";

                                $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
                                $stmtCheckSameDayPunch->bindParam(':current_date', $currentDate);
                                $stmtCheckSameDayPunch->bindParam(':sl_shift_id', $startShiftPunchId);
                                $stmtCheckSameDayPunch->execute();
                                $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

                                // If the user hasn't punched the start lunch punch for today show the checkbox to punch
                                if ($sameDayPunchCount === 0) {
                                echo '<br><div class="row"><br>';
                                echo '<div class="col">';
                                echo '<input type="hidden" name="start_lunch" value="' . $timestamp . '">';
                                echo '<button style="color:white; font-size: 14px; background-color:#215f92;" type="submit" class="btn">Entrada Almuerzo</button>';
                                echo '</div> <div class="col">';
                                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">Menu Principal</a>';
                                echo '</div>';
                                echo '</div>';
                  

                            } else {
                                $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM endlunch
                                                    WHERE DATE(end_lunch) = :current_date AND el_shift_id = :el_shift_id";

                                $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
                                $stmtCheckSameDayPunch->bindParam(':current_date', $currentDate);
                                $stmtCheckSameDayPunch->bindParam(':el_shift_id', $startShiftPunchId);
                                $stmtCheckSameDayPunch->execute();
                                $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

                                // If the user hasn't punched the end lunch punch for today show the checkbox to punch
                                if ($sameDayPunchCount === 0) {
                                    echo '<br><div class="row"><br>';
                                echo '<div class="col">';
                                echo '<input type="hidden" name="end_lunch" value="' . $timestamp . '">';
                                echo '<button style="color:white; font-size: 14px; background-color:#215f92;" type="submit" class="btn">Salida Almuerzo</button>';
                                echo '</div> <div class="col">';
                                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">Menu Principal</a>';
                                echo '</div>';
                                echo '</div>';
                 

                            } else {
                                $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM startb2
                                                    WHERE DATE(start_b2_time) = :current_date AND b2_shift_id = :b2_shift_id";

                                $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
                                $stmtCheckSameDayPunch->bindParam(':current_date', $currentDate);
                                $stmtCheckSameDayPunch->bindParam(':b2_shift_id', $startShiftPunchId);
                                $stmtCheckSameDayPunch->execute();
                                $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

                                // If the user hasn't punched the start break PM punch for today show the checkbox to punch
                                if ($sameDayPunchCount === 0) {
                                      echo '<br><div class="row"><br>';
                                echo '<div class="col">';
                                echo '<input type="hidden" name="start_b2_time" value="' . $timestamp . '">';
                                echo '<button style="color:white; font-size: 14px; background-color:#215f92;" type="submit" class="btn">Entrada Break PM</button>';
                                echo '</div> <div class="col">';
                                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">Menu Principal</a>';
                                echo '</div>';
                                echo '</div>';
                

                            } else {
                                $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM endb2
                                                    WHERE DATE(end_b2_time) = :current_date AND eb2_shift_id = :eb2_shift_id";

                                $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
                                $stmtCheckSameDayPunch->bindParam(':current_date', $currentDate);
                                $stmtCheckSameDayPunch->bindParam(':eb2_shift_id', $startShiftPunchId);
                                $stmtCheckSameDayPunch->execute();
                                $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

                                // If the user hasn't punched the end break PM punch for today show the checkbox to punch
                                if ($sameDayPunchCount === 0) {
                                     echo '<br><div class="row"><br>';
                                echo '<div class="col">';
                                echo '<input type="hidden" name="end_b2_time" value="' . $timestamp . '">';
                                echo '<button style="color:white; font-size: 14px; background-color:#215f92;" type="submit" class="btn">Salida Break PM</button>';
                                echo '</div> <div class="col">';
                                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">Menu Principal</a>';
                                echo '</div>';
                                echo '</div>';
                       

                            } else {
                                $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM endshift_time
                                                        WHERE DATE(end_shift_time) = :current_date AND est_shift_id = :est_shift_id";

                                $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
                                $stmtCheckSameDayPunch->bindParam(':current_date', $currentDate);
                                $stmtCheckSameDayPunch->bindParam(':est_shift_id', $startShiftPunchId);
                                $stmtCheckSameDayPunch->execute();
                                $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

                                // If the user hasn't punched the end shift punch for today, show the checkbox to punch
                                if ($sameDayPunchCount === 0) {
                                      echo '<br><div class="row"><br>';
                                echo '<div class="col">';
                                echo '<input type="hidden" name="end_shift_time" value="' . $timestamp . '">';
                                echo '<button style="color:white; font-size: 14px; background-color:#215f92;" type="submit" class="btn">Salida Turno</button>';
                                echo '</div> <div class="col">';
                                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">Menu Principal</a>';
                                echo '</div>';
                                echo '</div>';
                                
                                } else {
                                echo '<br><div style="text-align:center; class="row"><br>';
                                echo '<div class="col">';
                                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">Menu Principal</a>';
                                echo '</div>';
                                echo '</div>';
                                }
                            }
                            }
                            }
                            }
                            }
                            }
                }
            ?>
            <div class="row">
                <input type="hidden" name="r_workflow_id" value="<?php echo $workflow_id; ?>">
            </div>
        </form>
    </div>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const scanner = new Html5QrcodeScanner('reader', {
            qrbox: {
                width: 300,
                height: 300,
            },
            fps: 20,
        });
        scanner.render(success, error);

        function success(result) {
            if (result.trim() !== "") {
                document.getElementById('result').innerHTML = `
                    <h2>Success!</h2>
                    <p><a href="${result}">${result}</a></p>
                `;
                document.getElementById('resultInput').value = result; // Set the result as the value of the hidden input field
                scanner.clear();
                document.getElementById('reader').remove();
            } else {
                alert("QR code is empty. Please scan a valid QR code.");
            }
        }

        function error(err) {
            console.error(err);
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
function setupFlip(tick) {
    // Function to update the Flip.js clock with the current time
    function updateClock() {
        const currentTime = new Date();
        let hours = currentTime.getHours();
        const minutes = currentTime.getMinutes();
        const seconds = currentTime.getSeconds();
        const amPm = hours >= 12 ? 'PM' : 'AM';

        // Convert hours to 12-hour format
        if (hours > 12) {
            hours -= 12;
        }

        // Update the Flip.js clock with the current time
        const timeString = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')} ${amPm}`;
        tick.value = timeString;

        // Set `aria-label` attribute for screen readers
        tick.root.setAttribute('aria-label', timeString);
    }

    // Initialize the Flip.js clock
    updateClock();

    // Set the interval to update the clock every second
    setInterval(updateClock, 1000);
}
</script>
</body>

</html>