<?php
function getWorkflowIdByUserId($session_user, $db) {
    // Validate $userId as an integer
    if (!filter_var($session_user, FILTER_VALIDATE_INT)) {
        return false;
    }

    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT workflows_creator.wcreator_workflows_id AS workflows_id
            FROM users
            INNER JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = users.id
            INNER JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id 
            WHERE users.id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $session_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $workflow = $result->fetch_assoc();
        return $workflow;
    } else {
        return false;
    }
}

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
        users_by_wcreator.ubw_user_id,
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

function getTableNameByWorkflowId($workflow_id, $db) {
    mysqli_set_charset($db, "utf8mb4");

    // Retrieve the table_name based on the workflow_id
    $sql = "SELECT fm.table_name, fm.id
            FROM form_metadata AS fm
            WHERE fm.fm_workflows_id = ?";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        // Handle error, e.g., log or throw an exception
        // Return a default structure with null values
        return ['id' => null, 'table_name' => null];
    }
    $stmt->bind_param('i', $workflow_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        // No form associated with this workflow_id
        // Return a default structure with null values
        return ['id' => null, 'table_name' => null];
    }
    $metadata = $result->fetch_assoc();
    $table_name = $metadata['table_name'];

    $sql = "SELECT id FROM " . $table_name . " LIMIT 1"; // Example: Getting the first 'id' for demonstration
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        // Handle error
        // Return a default structure with null values if the table name is valid but the query fails
        return ['id' => null, 'table_name' => $table_name];
    }
    // No parameters to bind in this case
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $formData = $result->fetch_assoc();
        // Return both the ID and table_name as an associative array
        return ['id' => $formData['id'], 'table_name' => $table_name];
    } else {
        // No data found, but table_name is valid
        return ['id' => null, 'table_name' => $table_name];
    }
}


function formDataF001($id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT form_001.id AS fId, workflows.id AS wId, process_level_id, firstName, lastName, age, gender, physical_address, postal_address, sector, phone, email, receiver_division.wcreator_name AS receiver_division_name, table_name, signature, ref_number, process_status, service_request, sender.first_name AS sender_name, receiver.first_name AS receiver_name, timestamp
            FROM workflows
            LEFT JOIN form_metadata ON form_metadata.fm_workflows_id = workflows.id
            LEFT JOIN form_001 ON form_001.form_metadata_id = form_metadata.id
            LEFT JOIN forms_status ON forms_status.forms_id = form_001.id
            LEFT JOIN users AS sender ON sender.id = forms_status.fl_sender_user_id
            LEFT JOIN users AS receiver ON receiver.id = forms_status.fl_receiver_user_id
            LEFT JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = fl_sender_user_id
            LEFT JOIN workflows_creator AS sender_division ON sender_division.id = users_by_wcreator.wcreator_id
            LEFT JOIN workflows_creator AS receiver_division ON receiver_division.id = forms_status.receiver_division_wcid
            WHERE form_001.id  = ?";
    
$stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $formData = $result->fetch_assoc();
        return $formData;
    } else {
        return false;
    }
}

function formDataHelpDesk001($id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT *, formHelpDesk001.id AS fid, workflows.id AS wId, forms_status.ref_number, process_level_id, fullName, email, issueType, issueDescription, receiver_division.wcreator_name AS receiver_division_name, table_name, signature, ref_number, process_status, sender.first_name AS sender_name, receiver.first_name AS receiver_name, timestamp
            FROM workflows
            LEFT JOIN form_metadata ON form_metadata.fm_workflows_id = workflows.id
            LEFT JOIN formHelpDesk001 ON formHelpDesk001.metadata_id = form_metadata.id
            LEFT JOIN forms_status ON forms_status.forms_id = formHelpDesk001.id
            LEFT JOIN users AS sender ON sender.id = forms_status.fl_sender_user_id
            LEFT JOIN users AS receiver ON receiver.id = forms_status.fl_receiver_user_id
            LEFT JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = fl_sender_user_id
            LEFT JOIN workflows_creator AS sender_division ON sender_division.id = users_by_wcreator.wcreator_id
            LEFT JOIN workflows_creator AS receiver_division ON receiver_division.id = forms_status.receiver_division_wcid
            WHERE formHelpDesk001.id  = ?";
    
$stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $formData = $result->fetch_assoc();
        return $formData;
    } else {
        return false;
    }
}

function getFormData2($id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT form_001.id AS fId, firstName, lastName, receiver_division.wcreator_name AS receiver_division_name, table_name, signature, ref_number, process_status, service_request, sender.first_name AS sender_name, receiver.first_name AS receiver_name, timestamp
            FROM workflows
            LEFT JOIN form_metadata ON form_metadata.fm_workflows_id = workflows.id
            LEFT JOIN form_001 ON form_001.form_metadata_id = form_metadata.id
            LEFT JOIN forms_status ON forms_status.forms_id = form_001.id
            LEFT JOIN users AS sender ON sender.id = forms_status.fl_sender_user_id
            LEFT JOIN users AS receiver ON receiver.id = forms_status.fl_receiver_user_id
            LEFT JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = fl_sender_user_id
            LEFT JOIN workflows_creator AS sender_division ON sender_division.id = users_by_wcreator.wcreator_id
            LEFT JOIN workflows_creator AS receiver_division ON receiver_division.id = forms_status.receiver_division_wcid
            WHERE workflows.id  = ?";
    
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

function getWCreatorAndMetadataId($id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = " SELECT workflows_creator.id AS wcId, form_metadata.id AS fmId
    FROM workflows
    LEFT JOIN workflows_creator ON workflows_creator.wcreator_workflows_id = workflows.id
    LEFT JOIN form_metadata ON form_metadata.fm_workflows_id = workflows.id
    WHERE wcreator_workflows_id = ? 
    AND wlevel_id = 2
    AND form_metadata.fm_workflows_id = workflows.id";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $workflowData = $result->fetch_assoc();
        return $workflowData;
    } else {
        return false;
    }
}

function getLevelWcreateId($formLevel_Id, $workflow_id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT workflows_creator.id AS wcId, users_by_wcreator.ubw_user_id AS userId 
    FROM workflows_creator
    INNER JOIN workflows ON workflows.id = workflows_creator.wcreator_workflows_id
    INNER JOIN users_by_wcreator ON users_by_wcreator.wcreator_id = workflows_creator.id
    WHERE workflows_creator.wlevel_id = ?
    AND workflows.id = ?";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('ii', $formLevel_Id, $workflow_id); // Use 'ii' for two integer parameters
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $workflowData = $result->fetch_assoc();
        return $workflowData;
    } else {
        return false;
    }
}

function getOriginatorUser($form_id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT forms_status.fl_sender_user_id AS oUserId, users.first_name, users.last_name, users.user_email
    FROM form_001
    INNER JOIN forms_status ON forms_status.forms_id = form_001.id
    LEFT JOIN users ON users.id = forms_status.fl_sender_user_id
    WHERE form_001.id = ?";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $form_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $originatorUser = $result->fetch_assoc();
        return $originatorUser;
    } else {
        return false;
    }
}

function getSenderUser($session_user, $workflow_id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT workflows_creator.wcreator_name AS sender_division_name 
        FROM users
        LEFT JOIN users_by_wcreator ON users_by_wcreator.ubw_user_id = users.id
        LEFT JOIN workflows_creator ON workflows_creator.id = users_by_wcreator.wcreator_id
        WHERE users.id = ?
        AND wcreator_workflows_id = ?"; 

    $stmt = $db->prepare($sql);
    $stmt->bind_param('ii', $session_user, $workflow_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $senderUser = $result->fetch_assoc();
        return $senderUser;
    } else {
        return false;
    }
}

function getReceiverUser($id, $db) {
    mysqli_set_charset($db, "utf8mb4");
    $sql = "SELECT ubw_user_id, workflow_name FROM AQDB.workflows
    LEFT JOIN AQDB.workflows_creator ON workflows_creator.wcreator_workflows_id = workflows.id
    LEFT JOIN AQDB.users_by_wcreator ON users_by_wcreator.wcreator_id = workflows_creator.id
    LEFT JOIN AQDB.users ON users.id = users_by_wcreator.ubw_user_id
    WHERE workflows_creator.wlevel_id = 2
    AND workflows.id = ?";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $receiverUser = $result->fetch_assoc();
        return $receiverUser;
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
function generateNewReferenceNumber($prefix, $session_user, $pdo) {
    // Check if there is a previous reference number
    $sql = "SELECT MAX(ref_number) AS last_reference_number FROM form_001
    INNER JOIN forms_status ON forms_status.forms_id = form_001.id
    WHERE fl_sender_user_id = :session_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':session_user', $session_user, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['last_reference_number']) {
        // Extract the year and numeric part of the last reference number
        preg_match('/(\d{5})-(\d+)/', $row['last_reference_number'], $matches);

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

            // Generate a unique combination of letters and numbers for the last 4 digits
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            $length = 5;
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }

            return "$prefix-$currentYear-$randomString";
        }
    }

    // If no last reference number found or error occurred, use a default value
    return $prefix ."-" . date('Y') . "-00001";
}
  
function getLastReferenceNumber($prefix, $session_user, $pdo) {
    $sql = "SELECT MAX(ref_number) AS last_reference_number FROM form_001
    INNER JOIN forms_status ON forms_status.forms_id = form_001.id
    WHERE fl_sender_user_id = :session_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':session_user', $session_user, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['last_reference_number']) {
        // Extract the year and numeric part of the last reference number
        preg_match('/(\d{5})-(\d+)/', $row['last_reference_number'], $matches);

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

            // Generate a unique combination of letters and numbers for the last 4 digits
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            $length = 5;
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }

            return "$prefix-$currentYear-$randomString";
        }
    }

    // If no last reference number found or error occurred, use a default value
    return "$prefix-" . date('Y') . "-00001";
}

function generateNewReferenceNumber1($prefix, $session_user, $pdo) {
    $currentYear = date('Y');

    // Generate a unique combination of letters and numbers for the last 4 digits
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $length = 5;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    // Generate the new reference number
    $newRefNumber = "$prefix-$currentYear-$randomString";

    // Check if the generated reference number already exists in the database
    $sql = "SELECT COUNT(*) AS count FROM forms_status WHERE ref_number = :ref_number";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ref_number', $newRefNumber, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['count'] > 0) {
        // If the generated reference number already exists, recursively call the function to generate a new one
        return generateNewReferenceNumber1($prefix, $session_user, $pdo);
    }

    // If the reference number is unique, return it
    return $newRefNumber;
}

  
function getLastReferenceNumber1($prefix, $session_user, $pdo) {
    // Retrieve the last reference number from the database
    $sql = "SELECT MAX(forms_status.ref_number) AS last_reference_number FROM formHelpDesk001
    INNER JOIN forms_status ON forms_status.forms_id = formHelpDesk001.id
    WHERE fl_sender_user_id = :session_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':session_user', $session_user, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Extract the year and numeric part of the last reference number
    if ($row['last_reference_number']) {
        preg_match('/(\d{5})-(\d+)/', $row['last_reference_number'], $matches);
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

            // Generate a unique combination of letters and numbers for the last 4 digits
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            $length = 5;
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Generate the new reference number
            $newRefNumber = "$prefix-$currentYear-$randomString";

            // Check if the generated reference number already exists in the database
            $sqlCheck = "SELECT COUNT(*) AS count FROM formHelpDesk001 WHERE ref_number = :ref_number";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':ref_number', $newRefNumber, PDO::PARAM_STR);
            $stmtCheck->execute();
            $rowCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($rowCheck['count'] > 0) {
                // If the generated reference number already exists, recursively call the function again to generate a new one
                return getLastReferenceNumber1($prefix, $session_user, $pdo);
            }

            // If the reference number is unique, return it
            return $newRefNumber;
        }
    }

    // If no last reference number found or error occurred, use a default value
    return "$prefix-" . date('Y') . "-0001";
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

