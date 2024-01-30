<?php

include_once '../../core/config/transac_setup.php';

$userEmail = $sysRol['user_email'];
$workflow_id = $_GET['workflow_id'];


if (isset($_POST['start_time'])) {
    // Get the current time
    $currentDateTime = new DateTime();
    $currentTime = $currentDateTime->format('H:i:s'); // Format to extract time (hours, minutes, seconds)
    $requestTimeStamp = date('Y-m-d H:i:s');

    // Retrieve the most recent shift configuration for the user
    $sql = "SELECT * FROM users_by_shift
            INNER JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
            LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
            WHERE ubs_user_id = :user_id";
    $stmtGetShiftHoursAllowed = $pdo->prepare($sql);
    $stmtGetShiftHoursAllowed->bindParam(':user_id', $session_user);
    $stmtGetShiftHoursAllowed->execute();

    $row = $stmtGetShiftHoursAllowed->fetch(PDO::FETCH_ASSOC);
    $totalAllowedTime = new DateTimeImmutable($row['shift_time_allowed']); // TIME object

    // Convert grace_period from TIME to seconds
    $gracePeriod = $row['grace_period']; // '00:00:00' format

    $shiftStartAllowed = new DateTimeImmutable($row['shift_start_time_allowed']); // TIME object

    // Adjust the grace_period format to create a valid DateInterval
    list($graceHours, $graceMinutes, $graceSeconds) = explode(':', $gracePeriod);
    $gracePeriodFormatted = "PT{$graceHours}H{$graceMinutes}M{$graceSeconds}S";

    // Calculate new times by adding intervals (grace period)
    $totalAllowedTime = $totalAllowedTime->add(new DateInterval($gracePeriodFormatted));
    $shiftStartAllowed1 = $shiftStartAllowed->add(new DateInterval($gracePeriodFormatted));

    // Calculate new times by subtracting intervals (grace period)
    $shiftStartAllowed2 = $shiftStartAllowed->sub(new DateInterval($gracePeriodFormatted));

    // Check if there is another start_time punch for the current user today
    $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM shift_table 
                             WHERE DATE(start_time) = CURDATE() AND sst_user_id = :sst_user_id";
    $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
    $stmtCheckSameDayPunch->bindParam(':sst_user_id', $session_user);
    $stmtCheckSameDayPunch->execute();
    $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

    // Calculate the shift duration in seconds based on totalAllowedTime
    $totalAllowedTime = $totalAllowedTime->format('H:i:s');
    $shiftDurationInSeconds = strtotime($totalAllowedTime) - strtotime('00:00:00');

    // Calculate eight hours ago from totalAllowedTime
    $eightHoursAgo = (new DateTime())->sub(new DateInterval("PT{$shiftDurationInSeconds}S"))->format('Y-m-d H:i:s');

    // Check if there has been a start_time punch in the last 8 hours
    $sqlCheckLast8HoursPunch = "SELECT COUNT(*) FROM shift_table 
                                WHERE start_time >= :eight_hours_ago AND sst_user_id = :sst_user_id";
    $stmtCheckLast8HoursPunch = $pdo->prepare($sqlCheckLast8HoursPunch);
    $stmtCheckLast8HoursPunch->bindParam(':eight_hours_ago', $eightHoursAgo);
    $stmtCheckLast8HoursPunch->bindParam(':sst_user_id', $session_user);
    $stmtCheckLast8HoursPunch->execute();
    $last8HoursPunchCount = $stmtCheckLast8HoursPunch->fetchColumn();

    if ($sameDayPunchCount > 0 || $last8HoursPunchCount > 0) {
        // Redirect to 'newAttendance.php' with the appropriate query parameter
        echo "<script type=\"text/javascript\">
            alert('Usted ya ponchó el inicio de turno.');
            window.location.href = 'checkIn.php?workflow_id={$workflow_id}';
        </script>";
        exit();
    }
            // Check if the last start_time was within the allowed time frame
        if ($currentDateTime > $totalAllowedTime) {
            // User is late
            if (
                $currentDateTime > $totalAllowedTime && // User is late
                (
                    ($currentDateTime >= $shiftStartAllowed1 && $currentDateTime <= $shiftStartAllowed2) ||
                    ($currentDateTime >= $shiftStartAllowed2 && $currentDateTime <= $shiftStartAllowed1)
                )
            ) {
                    // The user can punch in
                // Insert the new start_time into shift_table or perform other actions
                $sqlInsertStartShift = "INSERT INTO shift_table (start_time, sst_user_id, start_time_status) VALUES (:start_time, :sst_user_id, :start_time_status)";
                $stmtInsertStartShift = $pdo->prepare($sqlInsertStartShift);
                $stmtInsertStartShift->bindParam(':sst_user_id', $session_user); // Ensure $users_id is defined
                $stmtInsertStartShift->bindValue(':start_time', $requestTimeStamp);
                $stmtInsertStartShift->bindValue(':start_time_status', 'On Time');

                if ($stmtInsertStartShift->execute()) {
                    // Get the last inserted ID
                    $lastInsertedId = $pdo->lastInsertId();

                    echo "<script type=\"text/javascript\">
                            alert('On Time.');
                            window.location.href = '../../home.php';
                        </script>";
                    exit();
                }
            } else if ($currentDateTime <= $shiftStartAllowed1) {
                    // The user can punch in
                // Insert the new start_time into shift_table or perform other actions
                $sqlInsertStartShift = "INSERT INTO shift_table (start_time, sst_user_id, start_time_status) VALUES (:start_time, :sst_user_id, :start_time_status)";
                $stmtInsertStartShift = $pdo->prepare($sqlInsertStartShift);
                $stmtInsertStartShift->bindParam(':sst_user_id', $session_user); // Ensure $users_id is defined
                $stmtInsertStartShift->bindValue(':start_time', $requestTimeStamp);
                $stmtInsertStartShift->bindValue(':start_time_status', 'Early');

                if ($stmtInsertStartShift->execute()) {
                    // Get the last inserted ID
                    $lastInsertedId = $pdo->lastInsertId();

                    echo "<script type=\"text/javascript\">
                            alert('Early. Su ponche será enviado al personal de recursos humanos.');
                            window.location.href = '../../../../checkIn2.php?workflow_id={$workflow_id}&shift_table_id={$lastInsertedId}';
                        </script>";
                    exit();
                }
            } else if ($currentDateTime >= $shiftStartAllowed1) {
                // The user can punch in
                // Insert the new start_time into shift_table or perform other actions
                $sqlInsertStartShift = "INSERT INTO shift_table (start_time, sst_user_id, start_time_status) VALUES (:start_time, :sst_user_id, :start_time_status)";
                $stmtInsertStartShift = $pdo->prepare($sqlInsertStartShift);
                $stmtInsertStartShift->bindParam(':sst_user_id', $session_user); // Ensure $users_id is defined
                $stmtInsertStartShift->bindValue(':start_time', $requestTimeStamp);
                $stmtInsertStartShift->bindValue(':start_time_status', 'Late Punch');

                if ($stmtInsertStartShift->execute()) {
                    // Get the last inserted ID
                    $lastInsertedId = $pdo->lastInsertId();

                    echo "<script type=\"text/javascript\">
                            alert('Late Punch. Su ponche será enviado al personal de recursos humanos.');
                            window.location.href = '../../checkIn2.php?workflow_id={$workflow_id}&shift_table_id={$lastInsertedId}';
                        </script>";
                    exit();
                }
            }
        }

 else {
        // User has already punched in within the allowed time frame
        // Handle the case where the user cannot punch in
        echo "<script type=\"text/javascript\">
                alert('Early');
                window.location.href = '../../checkIn2.php?workflow_id={$workflow_id}';
            </script>";
    }
}

// Start B1
if (isset($_POST['start_b1_time'])) {

    $currentDate = date('Y-m-d');
    $sqlCheckStartShiftPunch = "SELECT id FROM shift_table 
                                WHERE DATE(start_time) = :current_date AND sst_user_id = :sst_user_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':sst_user_id', $session_user);
    $stmtCheckStartShiftPunch->execute();
    $startShiftPunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($startShiftPunchId === false) {
        // The user hasn't punched the first start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar el inicio de turno.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }

    // Retrieve the most recent shift configuration for the user
    $sql = "SELECT * FROM users_by_shift
        INNER JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
        LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
        WHERE ubs_user_id = :user_id";
    $stmtGetShiftHoursAllowed = $pdo->prepare($sql);
    $stmtGetShiftHoursAllowed->bindParam(':user_id', $session_user);
    $stmtGetShiftHoursAllowed->execute();

    $row = $stmtGetShiftHoursAllowed->fetch(PDO::FETCH_ASSOC);
    $breakTimeAllowed = new DateTimeImmutable($row['m_break_time_allowed']); // TIME object

    // Check if there is another start_b1_time punch for the current user today
    $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM startb1
                             WHERE DATE(start_b1_time) = CURDATE() AND b1_shift_id = :b1_shift_id";
    $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
    $stmtCheckSameDayPunch->bindParam(':b1_shift_id', $startShiftPunchId);
    $stmtCheckSameDayPunch->execute();
    $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

    // Define $currentDateTime and $shiftStartAllowed1 based on your code
    $currentDateTime = new DateTime(); // Define this according to your requirements
    $shiftStartAllowed1 = new DateTimeImmutable($row['shift_start_time_allowed']); // Define this according to your requirements
    
    if ($sameDayPunchCount > 0) {
        echo "<script type=\"text/javascript\">
            alert('Usted ya ponchó el inicio del receso de la mañana.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
        </script>";
    } else {
        // Check if the current time is within the allowed 3-hour range
        if ($currentDateTime > $shiftStartAllowed1) {
            $threeHoursAfterShiftStart = $shiftStartAllowed1->add(new DateInterval("PT24H"));

            if ($currentDateTime > $threeHoursAfterShiftStart) {
                echo "<script type=\"text/javascript\">
                    alert('Solo puede tomar el break de la mañana dentro de las primeras 3 horas del turno.');
                    window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
                </script>";
                exit();
            } else {
                $sqlInsertStartShift = "INSERT INTO startb1 (start_b1_time, b1_shift_id) VALUES (:start_b1_time, :b1_shift_id)";
                $stmtInsertStartShift = $pdo->prepare($sqlInsertStartShift);
                $stmtInsertStartShift->bindParam(':b1_shift_id', $startShiftPunchId); // Ensure $users_id is defined
                $stmtInsertStartShift->bindValue(':start_b1_time', $currentDateTime->format('Y-m-d H:i:s')); // Format as string

                if ($stmtInsertStartShift->execute()) {
                    echo "<script type=\"text/javascript\">
                        alert('Punch Processed');
                        window.location.href = '../../home.php'; // Corrected URL
                    </script>";
                    exit();
                }
            }
        }
    }

}



// End B1
if (isset($_POST['end_b1_time'])) {

    $currentDate = date('Y-m-d');
    $sqlCheckStartShiftPunch = "SELECT id FROM shift_table 
                                WHERE DATE(start_time) = :current_date AND sst_user_id = :sst_user_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':sst_user_id', $session_user);
    $stmtCheckStartShiftPunch->execute();
    $startShiftPunchId = $stmtCheckStartShiftPunch->fetchColumn();

    
    if ($startShiftPunchId === false) {
        // The user hasn't punched the first start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar el inicio de turno.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }
    
    // Retrieve the most recent shift configuration for the user
    $sql = "SELECT * FROM users_by_shift
            INNER JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
            LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
            WHERE ubs_user_id = :user_id";
    $stmtGetShiftHoursAllowed = $pdo->prepare($sql);
    $stmtGetShiftHoursAllowed->bindParam(':user_id', $session_user);
    $stmtGetShiftHoursAllowed->execute();

    $row = $stmtGetShiftHoursAllowed->fetch(PDO::FETCH_ASSOC);
    $breakTimeAllowed = new DateTimeImmutable($row['m_break_time_allowed']); // TIME object

    // Check if there is another start_b1_time punch for the current user today
    $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM endb1
                             WHERE DATE(end_b1_time) = CURDATE() AND eb1_shift_id = :eb1_shift_id";
    $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
    $stmtCheckSameDayPunch->bindParam(':eb1_shift_id', $startShiftPunchId);
    $stmtCheckSameDayPunch->execute();
    $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

    // Define $currentDateTime and $shiftStartAllowed1 based on your code
    $currentDateTime = new DateTime(); // Define this according to your requirements
    $shiftStartAllowed1 = new DateTimeImmutable($row['shift_start_time_allowed']); // Define this according to your requirements

    // First verify if the punch already exist.
    if ($sameDayPunchCount > 0) {
        echo "<script type=\"text/javascript\">
            alert('Usted ya ponchó la salida del receso de la mañana.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
        </script>";
        exit();
    } else {
 

    $currentDate = date('Y-m-d');
    $sqlCheckStartShiftPunch = "SELECT id FROM startb1
                                WHERE DATE(start_b1_time) = :current_date AND b1_shift_id = :b1_shift_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':b1_shift_id', $startShiftPunchId);
    $stmtCheckStartShiftPunch->execute();
    $startBreakAmPunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($startBreakAmPunchId === false) {
        // The user hasn't punched the first start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar el inicio del receso de la mañana.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }

    // Check if the current time is within the allowed 3-hour range
    if ($currentDateTime > $shiftStartAllowed1) {
        $threeHoursAfterShiftStart = $shiftStartAllowed1->add(new DateInterval("PT24H"));

        if ($currentDateTime > $threeHoursAfterShiftStart) {
             echo "<script type=\"text/javascript\">
                alert('Solo puede tomar el break de la mañana dentro de las primeras 3 horas del turno.');
                window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
            </script>";
            exit();
        } else {
            $sqlInsertStartShift = "INSERT INTO endb1 (end_b1_time, eb1_shift_id) VALUES (:end_b1_time, :eb1_shift_id)";
            $stmtInsertStartShift = $pdo->prepare($sqlInsertStartShift);
            $stmtInsertStartShift->bindParam(':eb1_shift_id', $startShiftPunchId); 
            $stmtInsertStartShift->bindValue(':end_b1_time', $currentDateTime->format('Y-m-d H:i:s')); // Format as string

            if ($stmtInsertStartShift->execute()) {
                echo "<script type=\"text/javascript\">
                    alert('Ponche Procesado.');
                    window.location.href = 'home.php'; // Corrected URL
                </script>";
                exit();
            }
        }
    }

    }
    
}
// Start Lunch
if (isset($_POST['start_lunch'])) {
    $currentDate = date('Y-m-d');
    $sqlCheckStartShiftPunch = "SELECT id FROM shift_table 
                                WHERE DATE(start_time) = :current_date AND sst_user_id = :sst_user_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':sst_user_id', $session_user);
    $stmtCheckStartShiftPunch->execute();
    $startShiftPunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($startShiftPunchId === false) {
        // The user hasn't punched the first start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar el inicio de turno.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }

    // Retrieve the most recent shift configuration for the user
    $sql = "SELECT * FROM users_by_shift
            INNER JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
            LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
            WHERE ubs_user_id = :user_id";
    $stmtGetShiftHoursAllowed = $pdo->prepare($sql);
    $stmtGetShiftHoursAllowed->bindParam(':user_id', $session_user);
    $stmtGetShiftHoursAllowed->execute();

    $row = $stmtGetShiftHoursAllowed->fetch(PDO::FETCH_ASSOC);
    $breakTimeAllowed = new DateTimeImmutable($row['l_time_allowed']); // TIME object

    // Check if there is another startlunch punch for the current user today
    $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM startlunch
                             WHERE DATE(start_lunch) = CURDATE() AND sl_shift_id = :sl_shift_id";
    $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
    $stmtCheckSameDayPunch->bindParam(':sl_shift_id', $startShiftPunchId);
    $stmtCheckSameDayPunch->execute();
    $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

    // Define $currentDateTime and $shiftStartAllowed1 based on your code
    $currentDateTime = new DateTime(); // Define this according to your requirements
    $shiftStartAllowed1 = new DateTimeImmutable($row['shift_start_time_allowed']); // Define this according to your requirements

    if ($sameDayPunchCount > 0) {
    echo "<script type=\"text/javascript\">
        alert('Usted ya ponchó el inicio del almuerzo.');
        window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
    </script>";
    exit();
    } else {
        $currentDate = date('Y-m-d');
    $sqlCheckStartShiftPunch = "SELECT id FROM endb1
                                WHERE DATE(end_b1_time) = :current_date AND eb1_shift_id = :eb1_shift_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':eb1_shift_id', $startShiftPunchId);
    $stmtCheckStartShiftPunch->execute();
    $endBreakAmPunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($endBreakAmPunchId === false) {
        // The user hasn't punched the first start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar la salida del receso de la mañana.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }

    // Check if the current time is within the allowed 3-hour range
    if ($currentDateTime > $shiftStartAllowed1) {
        $threeHoursAfterShiftStart = $shiftStartAllowed1->add(new DateInterval("PT24H"));

        if ($currentDateTime > $threeHoursAfterShiftStart) {
             echo "<script type=\"text/javascript\">
                alert('Solo puede tomar el almuerzo dentro de las primeras 6 horas del turno.');
                window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
            </script>";
            exit();
        } else {
            $sqlInsertStartShift = "INSERT INTO startlunch (start_lunch, sl_shift_id) VALUES (:start_lunch, :sl_shift_id)";
            $stmtInsertStartShift = $pdo->prepare($sqlInsertStartShift);
            $stmtInsertStartShift->bindParam(':sl_shift_id', $startShiftPunchId); // Ensure $users_id is defined
            $stmtInsertStartShift->bindValue(':start_lunch', $currentDateTime->format('Y-m-d H:i:s')); // Format as string

            if ($stmtInsertStartShift->execute()) {
                echo "<script type=\"text/javascript\">
                    alert('Ponche Procesado.');
                    window.location.href = 'home.php'; // Corrected URL
                </script>";
                exit();
            }
        }
    }
    }
  
}
// End Lunch
if (isset($_POST['end_lunch'])) {
    $currentDate = date('Y-m-d');
    $sqlCheckStartShiftPunch = "SELECT id FROM shift_table 
                                WHERE DATE(start_time) = :current_date AND sst_user_id = :sst_user_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':sst_user_id', $session_user);
    $stmtCheckStartShiftPunch->execute();
    $startShiftPunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($startShiftPunchId === false) {
        // The user hasn't punched the first start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar el inicio de turno.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }

    // Retrieve the most recent shift configuration for the user
    $sql = "SELECT * FROM users_by_shift
            INNER JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
            LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
            WHERE ubs_user_id = :user_id";
    $stmtGetShiftHoursAllowed = $pdo->prepare($sql);
    $stmtGetShiftHoursAllowed->bindParam(':user_id', $session_user);
    $stmtGetShiftHoursAllowed->execute();

    $row = $stmtGetShiftHoursAllowed->fetch(PDO::FETCH_ASSOC);
    $breakTimeAllowed = new DateTimeImmutable($row['l_time_allowed']); // TIME object

    $currentDate = date('Y-m-d');
    $sqlCheckStartShiftPunch = "SELECT id FROM startlunch
                                WHERE DATE(start_lunch) = :current_date AND sl_shift_id = :sl_shift_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':sl_shift_id', $startShiftPunchId);
    $stmtCheckStartShiftPunch->execute();
    $startLunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($startLunchId === false) {
        // The user hasn't punched the first start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar la entrada al almuerzo.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }
        // Check if there is another start_b1_time punch for the current user today
    $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM endlunch
                             WHERE DATE(end_lunch) = CURDATE() AND el_shift_id = :el_shift_id";
    $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
    $stmtCheckSameDayPunch->bindParam(':el_shift_id', $startShiftPunchId);
    $stmtCheckSameDayPunch->execute();
    $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

    // Define $currentDateTime and $shiftStartAllowed1 based on your code
    $currentDateTime = new DateTime(); // Define this according to your requirements
    $shiftStartAllowed1 = new DateTimeImmutable($row['shift_start_time_allowed']); // Define this according to your requirements
    
    if ($sameDayPunchCount > 0) {
        echo "<script type=\"text/javascript\">
            alert('Usted ya ponchó la salida del almuerzo.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
        </script>";
        exit();
    } else {

    // Check if the current time is within the allowed 3-hour range
    if ($currentDateTime > $shiftStartAllowed1) {
        $threeHoursAfterShiftStart = $shiftStartAllowed1->add(new DateInterval("PT24H"));

        if ($currentDateTime > $threeHoursAfterShiftStart) {
             echo "<script type=\"text/javascript\">
                alert('Solo puede tomar el almuerzo dentro de las primeras 6 horas del turno.');
                window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
            </script>";
            exit();
        } else {
            $sqlInsertStartShift = "INSERT INTO endlunch (end_lunch, el_shift_id) VALUES (:end_lunch, :el_shift_id)";
            $stmtInsertStartShift = $pdo->prepare($sqlInsertStartShift);
            $stmtInsertStartShift->bindParam(':el_shift_id', $startShiftPunchId); // Ensure $users_id is defined
            $stmtInsertStartShift->bindValue(':end_lunch', $currentDateTime->format('Y-m-d H:i:s')); // Format as string

            if ($stmtInsertStartShift->execute()) {
                echo "<script type=\"text/javascript\">
                    alert('Ponche Procesado.');
                    window.location.href = 'home.php'; // Corrected URL
                </script>";
                exit();
            }
        }
    }
    }
 
}
// Start B2
if (isset($_POST['start_b2_time'])) {
    $currentDate = date('Y-m-d');
    $sqlCheckStartShiftPunch = "SELECT id FROM shift_table 
                                WHERE DATE(start_time) = :current_date AND sst_user_id = :sst_user_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':sst_user_id', $session_user);
    $stmtCheckStartShiftPunch->execute();
    $startShiftPunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($startShiftPunchId === false) {
        // The user hasn't punched the first start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar el inicio de turno.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }

    // Retrieve the most recent shift configuration for the user
    $sql = "SELECT * FROM users_by_shift
            INNER JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
            LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
            WHERE ubs_user_id = :user_id";
    $stmtGetShiftHoursAllowed = $pdo->prepare($sql);
    $stmtGetShiftHoursAllowed->bindParam(':user_id', $session_user);
    $stmtGetShiftHoursAllowed->execute();

    $row = $stmtGetShiftHoursAllowed->fetch(PDO::FETCH_ASSOC);
    $breakTimeAllowed = new DateTimeImmutable($row['a_break_time_allowed']); // TIME object

    // Check if there is another start_be_time punch for the current user today
    $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM startb2
                             WHERE DATE(start_b2_time) = CURDATE() AND b2_shift_id = :b2_shift_id";
    $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
    $stmtCheckSameDayPunch->bindParam(':b2_shift_id', $startShiftPunchId);
    $stmtCheckSameDayPunch->execute();
    $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

    // Define $currentDateTime and $shiftStartAllowed1 based on your code
    $currentDateTime = new DateTime(); // Define this according to your requirements
    $shiftStartAllowed1 = new DateTimeImmutable($row['shift_start_time_allowed']); // Define this according to your requirements

    if ($sameDayPunchCount > 0) {
        echo "<script type=\"text/javascript\">
            alert('Usted ya ponchó el inicio del receso de la tarde.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
        </script>";
    exit();

    } else {
    $currentDate = date('Y-m-d');
    $sqlCheckStartShiftPunch = "SELECT id FROM endlunch
                                WHERE DATE(end_lunch) = :current_date AND el_shift_id = :el_shift_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':el_shift_id', $startShiftPunchId);
    $stmtCheckStartShiftPunch->execute();
    $endLunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($endLunchId === false) {
        // The user hasn't punched the first start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar la salida del almuerzo.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }
    // Check if the current time is within the allowed 3-hour range
    if ($currentDateTime > $shiftStartAllowed1) {
        $threeHoursAfterShiftStart = $shiftStartAllowed1->add(new DateInterval("PT24H"));

        if ($currentDateTime > $threeHoursAfterShiftStart) {
             echo "<script type=\"text/javascript\">
                alert('Solo puede tomar el break de la tarde dentro de las primeras 7 horas del turno.');
                window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
            </script>";
            exit();
        } else {
            $sqlInsertStartShift = "INSERT INTO startb2 (start_b2_time, b2_shift_id) VALUES (:start_b2_time, :b2_shift_id)";
            $stmtInsertStartShift = $pdo->prepare($sqlInsertStartShift);
            $stmtInsertStartShift->bindParam(':b2_shift_id', $startShiftPunchId);   
            $stmtInsertStartShift->bindValue(':start_b2_time', $currentDateTime->format('Y-m-d H:i:s')); // Format as string

            if ($stmtInsertStartShift->execute()) {
                echo "<script type=\"text/javascript\">
                    alert('Ponche Procesado.');
                    window.location.href = 'home.php'; // Corrected URL
                </script>";
                exit();
            }
        }
    }
    }
    
}

// End B2
if (isset($_POST['end_b2_time'])) {
    // Assuming you have already defined $pdo, $session_user, and $workflow_id

    // Get the current date
    $currentDate = date('Y-m-d');

    // Check if the user has punched the start shift for today
    $sqlCheckStartShiftPunch = "SELECT id FROM shift_table 
                                WHERE DATE(start_time) = :current_date AND sst_user_id = :sst_user_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':sst_user_id', $session_user);
    $stmtCheckStartShiftPunch->execute();
    $startShiftPunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($startShiftPunchId === false) {
        // The user hasn't punched the start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar el inicio de turno.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }
        
    // Retrieve the most recent shift configuration for the user
    $sql = "SELECT * FROM users_by_shift
            INNER JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
            LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
            WHERE ubs_user_id = :user_id";
    $stmtGetShiftHoursAllowed = $pdo->prepare($sql);
    $stmtGetShiftHoursAllowed->bindParam(':user_id', $session_user);
    $stmtGetShiftHoursAllowed->execute();

    $row = $stmtGetShiftHoursAllowed->fetch(PDO::FETCH_ASSOC);
    $breakTimeAllowed = new DateTimeImmutable($row['a_break_time_allowed']); // TIME object

    // Check if there is another end_b2_time punch for the current user today
    $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM endb2
                             WHERE DATE(end_b2_time) = CURDATE() AND eb2_shift_id = :eb2_shift_id";
    $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
    $stmtCheckSameDayPunch->bindParam(':eb2_shift_id', $startShiftPunchId);
    $stmtCheckSameDayPunch->execute();
    $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

    // Define $currentDateTime and $shiftStartAllowed1 based on your code
    $currentDateTime = new DateTime(); // Define this according to your requirements
    $shiftStartAllowed1 = new DateTimeImmutable($row['shift_start_time_allowed']); // Define this according to your requirements

    if ($sameDayPunchCount > 0) {
        echo "<script type=\"text/javascript\">
            alert('Usted ya ponchó la salida del receso de la tarde.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
        </script>";
        exit();
    } else {

        $currentDate = date('Y-m-d');
        $sqlCheckStartShiftPunch = "SELECT id FROM startb2
                                    WHERE DATE(start_b2_time) = :current_date AND b2_shift_id = :b2_shift_id";
        $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
        $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
        $stmtCheckStartShiftPunch->bindParam(':b2_shift_id', $startShiftPunchId);
        $stmtCheckStartShiftPunch->execute();
        $startBreakPMId = $stmtCheckStartShiftPunch->fetchColumn();

        if ($startBreakPMId === false) {
            // The user hasn't punched the start Break PM for today
            echo "<script type=\"text/javascript\">
                alert('Para continuar debe ponchar el inicio del receso de la tarde.');
                window.location.href = 'checkIn.php?workflow_id=$workflow_id';
            </script>";
            exit();
        }

        // Check if the current time is within the allowed 3-hour range
        if ($currentDateTime > $shiftStartAllowed1) {
            $threeHoursAfterShiftStart = $shiftStartAllowed1->add(new DateInterval("PT24H"));

            if ($currentDateTime > $threeHoursAfterShiftStart) {
                 echo "<script type=\"text/javascript\">
                    alert('Solo puede tomar el break de la tarde dentro de las primeras 7 horas del turno.');
                    window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
                </script>";
                exit();
            } else {
                // Insert the end_b2_time punch into the database
                $sqlInsertEndShift = "INSERT INTO endb2 (end_b2_time, eb2_shift_id) VALUES (:end_b2_time, :eb2_shift_id)";
                $stmtInsertEndShift = $pdo->prepare($sqlInsertEndShift);
                $stmtInsertEndShift->bindParam(':eb2_shift_id', $startShiftPunchId); // Ensure $users_id is defined
                $stmtInsertEndShift->bindValue(':end_b2_time', $currentDateTime->format('Y-m-d H:i:s')); // Format as string

                if ($stmtInsertEndShift->execute()) {
                    echo "<script type=\"text/javascript\">
                        alert('Ponche Procesado.');
                        window.location.href = 'home.php'; // Corrected URL
                    </script>";
                    exit();
                }
            }
        }
    }
}

// End Shift
if (isset($_POST['end_shift_time'])) {

    // Get the current date
    $currentDate = date('Y-m-d');

    // Check if the user has punched the start shift for today
    $sqlCheckStartShiftPunch = "SELECT id FROM shift_table 
                                WHERE DATE(start_time) = :current_date AND sst_user_id = :sst_user_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':sst_user_id', $session_user);
    $stmtCheckStartShiftPunch->execute();
    $startShiftPunchId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($startShiftPunchId === false) {
        // The user hasn't punched the start shift for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar el inicio de turno.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }

    $sqlCheckStartShiftPunch = "SELECT id FROM endb2
                                WHERE DATE(end_b2_time) = :current_date AND eb2_shift_id = :eb2_shift_id";
    $stmtCheckStartShiftPunch = $pdo->prepare($sqlCheckStartShiftPunch);
    $stmtCheckStartShiftPunch->bindParam(':current_date', $currentDate);
    $stmtCheckStartShiftPunch->bindParam(':eb2_shift_id', $startShiftPunchId);
    $stmtCheckStartShiftPunch->execute();
    $startBreakPMId = $stmtCheckStartShiftPunch->fetchColumn();

    if ($startBreakPMId === false) {
        // The user hasn't punched the start Break PM for today
        echo "<script type=\"text/javascript\">
            alert('Para continuar debe ponchar la salida del receso de la tarde.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id';
        </script>";
        exit();
    }

    // Retrieve the most recent shift configuration for the user
    $sql = "SELECT * FROM users_by_shift
            INNER JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_groups_id
            LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
            WHERE ubs_user_id = :user_id";
    $stmtGetShiftHoursAllowed = $pdo->prepare($sql);
    $stmtGetShiftHoursAllowed->bindParam(':user_id', $session_user);
    $stmtGetShiftHoursAllowed->execute();

    $row = $stmtGetShiftHoursAllowed->fetch(PDO::FETCH_ASSOC);
    $shiftStartAllowed1 = new DateTimeImmutable($row['shift_start_time_allowed']); // Define this according to your requirements

    // Check if there is another end_shift_time punch for the current user today
    $sqlCheckSameDayPunch = "SELECT COUNT(*) FROM endshift_time
                             WHERE DATE(end_shift_time) = CURDATE() AND est_shift_id = :est_shift_id";
    $stmtCheckSameDayPunch = $pdo->prepare($sqlCheckSameDayPunch);
    $stmtCheckSameDayPunch->bindParam(':est_shift_id', $startShiftPunchId);
    $stmtCheckSameDayPunch->execute();
    $sameDayPunchCount = $stmtCheckSameDayPunch->fetchColumn();

    if ($sameDayPunchCount > 0) {
        echo "<script type=\"text/javascript\">
            alert('Usted ya ponchó la salida del turno.');
            window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
        </script>";
        exit();
    } else {
        // Define $currentDateTime based on your code
        $currentDateTime = new DateTime(); // Define this according to your requirements

        // Check if the current time is within the allowed 3-hour range
        if ($currentDateTime > $shiftStartAllowed1) {
            $threeHoursAfterShiftStart = $shiftStartAllowed1->add(new DateInterval("PT24H"));

            if ($currentDateTime > $threeHoursAfterShiftStart) {
                echo "<script type=\"text/javascript\">
                    alert('Usted puede ponchar salida dentro de las primeras 8 horas del turno.');
                    window.location.href = 'checkIn.php?workflow_id=$workflow_id'; // Corrected URL
                </script>";
                exit();
            } else {
                // Insert the end_shift_time punch into the database
                $sqlInsertEndShift = "INSERT INTO endshift_time (end_shift_time, est_shift_id) VALUES (:end_shift_time, :est_shift_id)";
                $stmtInsertEndShift = $pdo->prepare($sqlInsertEndShift);
                $stmtInsertEndShift->bindParam(':est_shift_id', $startShiftPunchId); // Ensure $users_id is defined
                $stmtInsertEndShift->bindValue(':end_shift_time', $currentDateTime->format('Y-m-d H:i:s')); // Format as string

                if ($stmtInsertEndShift->execute()) {
                    echo "<script type=\"text/javascript\">
                        alert('Ponche Procesado.');
                        window.location.href = 'home.php'; // Corrected URL
                    </script>";
                    exit();
                }
            }
        }
    }
}

   
?>
