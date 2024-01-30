<?php

function getUserById($id, $db) {
    // Validate $id as an integer
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        return false;
    }

    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT 
        users.id AS userId, 
        users.first_name, 
        users.last_name, 
        users.user_email,
        workflows.id,
        workflows_creator.wlevel_id, 
        workflows.workflow_name,
        workflows.wsender, 
        workflows.requester_href,
        workflows.evaluator_href,
        workflows_creator.wcreator_name,
        workflows_creator.id AS workflows_creator_id  -- Include workflows_creator.id alias
    FROM users 
    INNER JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = users.id
    LEFT JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
    INNER JOIN users_by_workflows ON users_by_workflows.workflow_user_id = users.id
    LEFT JOIN workflows ON workflows.id = users_by_workflows.ubw_workflow_id
    WHERE ubw_user_id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);

        // Use error suppression (@) to suppress warnings
        @ $stmt->execute(); // Suppress any warnings here
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $session_user = $result->fetch_assoc();
            return $session_user;
        } else {
            return false;
        }
    } catch (Exception $e) {
        // Handle database error, e.g., log it or return an error message
        return false;
    }
}
function getUserById2($id, $workflow_id, $db) {
    // Validate $id as an integer
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        return false;
    }

    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT 
        users.id AS userId, 
        users.first_name, 
        users.last_name, 
        users.user_email, 
        workflows_creator.wlevel_id, 
        workflows.workflow_name,
        workflows.wsender,
        workflows.wtype_id,
        workflows.requester_href,
        workflows.evaluator_href,
        workflows_creator.wcreator_name,
        workflows_creator.wcreator_workflows_id,
        workflows_creator.id AS workflows_creator_id  -- Include workflows_creator.id alias
    FROM users 
    INNER JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = users.id
    LEFT JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
    LEFT JOIN workflows ON workflows.id = workflows_creator.wcreator_workflows_id 
    WHERE ubw_user_id = ?
    AND wcreator_workflows_id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ii', $id, $workflow_id);

        // Use error suppression (@) to suppress warnings
        @ $stmt->execute(); // Suppress any warnings here
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $session_user = $result->fetch_assoc();
            return $session_user;
        } else {
            return false;
        }
    } catch (Exception $e) {
        // Handle database error, e.g., log it or return an error message
        return false;
    }
}

function getPreferenceById($id, $db) {
    // Validate $id as an integer
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        return false;
    }

    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT 
        preference_config.header,
        preference_config.footer
    FROM users
    INNER JOIN users_by_preference ON users_by_preference.ubp_user_id = users.id
    LEFT JOIN preference_config ON preference_config.id = users_by_preference.ubp_preference_config_id
    WHERE users.id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);

        // Use error suppression (@) to suppress warnings
        @ $stmt->execute(); // Suppress any warnings here
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $preference_user = $result->fetch_assoc();
            return $preference_user;
        } else {
            return false;
        }
    } catch (Exception $e) {
        // Handle database error, e.g., log it or return an error message
        return false;
    }
}



function getSysRol($id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT *
            FROM users 
            INNER JOIN users_by_sysgroup ON users_by_sysgroup.ubs_user_id = users.id
            LEFT JOIN sys_groups ON sys_groups.id = users_by_sysgroup.ubs_sys_groups_id
            WHERE users.id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $edit_profile = $result->fetch_assoc();
        return $edit_profile;
    } else {
        return false;
    }
}

function getModuleById($id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT workflows.wsender, workflows.id, workflows.workflow_name
            FROM workflows 
            WHERE workflows.id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $module_data = $result->fetch_assoc();
        return $module_data;
    } else {
        return false;
    }
}
function getWorkflowsById($id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT *, users.id AS userid
        FROM workflows
        INNER JOIN sys_groups ON sys_groups.id = users.user_sys_group_id
        INNER JOIN workflows ON workflows.id = users.user_workflows_id
        INNER JOIN workflows_level ON workflows_level.id = users.user_workflows_level_id
        WHERE users.id = ?";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $edit_workflow = $result->fetch_assoc();
        return $edit_workflow;
    } else {
        return false;
    }
}
// functions.php

function getWorkflowDataByArea($area, $db) {
    $query = "SELECT workflows.workflow_type, workflows.workflow_level FROM users_area
              LEFT JOIN workflows ON workflows.workflows_area_id = users_area.id
              WHERE users_area_name = :area";
    $statement = $db->prepare($query);
    $statement->bindParam(':area', $area);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
}

// Function to generate a unique token

function generateToken() {
    return bin2hex(random_bytes(32)); // Generates a 64-character hexadecimal token
}


function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendResetLinkEmail($recipientEmail, $resetLink) {
    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aqnotification@gmail.com';
        $mail->Password   = 'axzroicoizegvydn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('aqnotification@gmail.com', 'Document Control Systems Inc.');
        $mail->addAddress($recipientEmail); // Use $recipientEmail instead of $email

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "Click the following link to reset your password: <a href=\"$resetLink\">Reset Password</a>";

        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        return false; // Email sending failed
    }
}
function insertResetToken($userId, $token, $db) {
    // Calculate the expiration time (e.g., 1 hour from now)
    date_default_timezone_set('America/Puerto_Rico');
    $created_at = date('Y-m-d H:i:s'); // Get the current timestamp

    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Insert the token into the reset_tokens table
    $query = "INSERT INTO reset_tokens (user_id, token, expires_at, created_at)
              VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "isss", $userId, $token, $expiresAt, $created_at);
    
    return mysqli_stmt_execute($stmt);
}

// ... Your other functions ...



function getWcreatorId($userId, $pdo) {
    try {
        $sql = "SELECT wcreator_id FROM users_by_wcreator WHERE ubw_user_id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['wcreator_id'];
        } else {
            return false; // User not found in users_by_wcreator table
        }
    } catch (PDOException $e) {
        // Handle any database errors here
        return false;
    }
}

function getCurrentWlevelId($wcreatorId, $pdo) {
    try {
        $sql = "SELECT wlevel_id FROM workflows_creator WHERE id = :wcreatorId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':wcreatorId', $wcreatorId, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['wlevel_id'];
        } else {
            return false; // Wcreator not found
        }
    } catch (PDOException $e) {
        // Handle any database errors here
        return false;
    }
}

function getMatchingId($newWlevelId, $pdo) {
    try {
        $sql = "SELECT id FROM workflows_creator WHERE wlevel_id = :newWlevelId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newWlevelId', $newWlevelId, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['id'];
        } else {
            return false; // Matching ID not found
        }
    } catch (PDOException $e) {
        // Handle any database errors here
        return false;
    }
}
function insertRequest($referenceNumber, $requestName, $requestDescription, $requestStatus, $workflowsId, $matchingId, $requestUsers, $requestTimeStamp, $pdo) {
    try {
        $sql = "INSERT INTO requests (id, ref_request_num, request_name, request_description, request_status, r_workflow_id, r_wcreator_id, requester_user_id, request_timestamp) VALUES (NULL, :ref_request_num, :request_name, :request_description, :request_status, :r_workflow_id, :r_wcreator_id, :requester_user_id, :request_timestamp)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ref_request_num', $referenceNumber);
        $stmt->bindParam(':request_name', $requestName);
        $stmt->bindParam(':request_description', $requestDescription);
        $stmt->bindParam(':request_status', $requestStatus);
        $stmt->bindParam(':r_workflow_id', $workflowsId);
        $stmt->bindParam(':r_wcreator_id', $matchingId);
        $stmt->bindParam(':requester_user_id', $requestUsers);
        $stmt->bindParam(':request_timestamp', $requestTimeStamp);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle any database errors here
        return false;
    }
}
   
function getLastReferenceNumber($pdo) {
    $sql = "SELECT MAX(ref_request_num) AS last_reference_number FROM requests";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['last_reference_number']) {
        // Extract the year and numeric part of the last reference number
        preg_match('/(\d{4})-(\d+)/', $row['last_reference_number'], $matches);

        if (count($matches) === 3) {
            $lastYear = $matches[1];
            $lastNumber = intval($matches[2]);

            // Check if the current year matches the last year
            $currentYear = date('Y');
            if ($currentYear == $lastYear) {
                // Increment the last number by 1
                $newNumber = $lastNumber + 1;
            } else {
                // Start a new numbering for the current year
                $newNumber = 1;
            }

            // Format the new reference number with leading zeros
            $formattedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            return "DCS-$currentYear-$formattedNumber";
        }
    }

    // If no last reference number found or error occurred, use a default value
    return "DCS-" . date('Y') . "-0001";
}

// functions.php
// ... Your other functions ...
function insertResetTokenForNewUser($email, $token, $db) {
    // Calculate the expiration time (e.g., 1 hour from now)
    date_default_timezone_set('America/Puerto_Rico');
    $created_at = date('Y-m-d H:i:s'); // Get the current timestamp

    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Insert the token into the reset_tokens table
    $query = "INSERT INTO reset_tokens (email, token, expires_at, created_at)
              VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $email, $token, $expiresAt, $created_at);
    return mysqli_stmt_execute($stmt);
}

function validateToken($token, $db) {
    date_default_timezone_set('America/Puerto_Rico');
    // Check if the token exists in the reset_tokens table and is not expired
    $query = "SELECT user_id FROM reset_tokens WHERE token = ? AND expires_at > NOW()";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Token is valid
        return true;
    } else {
        // Token is not valid
        return false;
    }
}

function validateToken2($token, $db) {
    // Check if the token exists in the reset_tokens table and is not expired
    date_default_timezone_set('America/Puerto_Rico');
    $currentTimestamp = date('Y-m-d H:i:s');
    $query = "SELECT token FROM reset_tokens WHERE token = ? AND expires_at > ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ss", $token, $currentTimestamp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Token is valid
        return true;
    } else {
        // Token is not valid
        return false;
    }
}
// Function to check if a user is associated with a workflow
function isUserInWorkflow($userId, $workflowName, $pdo) {
    $sql = "SELECT COUNT(*) FROM users_by_workflows
            INNER JOIN workflows ON workflows.id = users_by_workflows.ubw_workflow_id
            WHERE users_by_workflows.workflow_user_id = ? AND workflows.workflow_name = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $workflowName]);
    
    $count = $stmt->fetchColumn();
    
    return $count > 0;
}

function displayPunchButtons($pdo, $session_user) {
    $timestamp = date('Y-m-d H:i:s');
    $currentDate = date('Y-m-d');
    $user = $session_user;

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
                echo '<div class="text-center col">';
                echo '<input type="hidden" name="start_time" value="' . $timestamp . '">';
                echo '<button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Entrada Turno</span></button>';
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
                echo '<div class="text-center col">';
                echo '<input type="hidden" name="start_b1_time" value="' . $timestamp . '">';
                echo '<button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Entrada Break AM</span></button>';
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
                echo '<div class="text-center col">';
                echo '<input type="hidden" name="end_b1_time" value="' . $timestamp . '">';
                echo '<button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Salida Break AM</span></button>';
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
                echo '<div class="text-center col">';
                echo '<input type="hidden" name="start_lunch" value="' . $timestamp . '">';
                echo '<button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Entrada Almuerzo</span></button>';
                echo '</div> <div';
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
                echo '<div class="text-center col">';
                echo '<input type="hidden" name="end_lunch" value="' . $timestamp . '">';
                echo '<button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Salida Almuerzo</span></button>';
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
                echo '<div class="text-center col">';
                echo '<input type="hidden" name="start_b2_time" value="' . $timestamp . '">';
                echo '<button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Entrada Break PM</span></button>';
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
                echo '<div class="text-center col">';
                echo '<input type="hidden" name="end_b2_time" value="' . $timestamp . '">';
                echo '<button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Salida Break PM</span></button>';
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
                echo '<div class="text-center col">';
                echo '<input type="hidden" name="end_shift_time" value="' . $timestamp . '">';
                echo '<button type="submit" class="btn-menu btn-1 hover-filled-opacity" ><span>Salida Turno</span></button>';
                echo '</div>';
                echo '</div>';
                
                } else {
                echo '<br><div style="text-align:center; class="row"><br>';
                echo '<div class="text-center col">';
                echo '<a style="color:white; font-size: 14px; background-color:#215f92;" href="home.php" class="btn">
                <span>Has completado los ponches de hoy.</span></a>';
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
        }
        
?>

