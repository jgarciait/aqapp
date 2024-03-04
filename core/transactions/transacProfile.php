<?php
include_once '../../core/config/transac_setup.php';

// Function to validate and upload the profile image
function uploadProfileImage($file, $userId) {
    $targetDir = "../../core/assets/uploads/profile_images/";
    $fileName = basename($file['name']);
    $targetFilePath = $targetDir . $userId . '_' . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($fileType, $allowTypes)) {
        if (file_exists($targetFilePath)) {
            unlink($targetFilePath);
        }
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            return $userId . '_' . $fileName;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

$uploadStatus = true;
$profileImageFileName = '';

if(isset($_POST['delete_profile_image'])) {
    // Logic to set profile image to defa
    $profileImageFileName = 'default-profile-image.png';
} else if (!empty($_FILES['profile_image']['name'])) {
    $profileImageFileName = uploadProfileImage($_FILES['profile_image'], $session_user);
    if ($profileImageFileName === false) {
        $em = "Profile image upload failed or file type not allowed.";
        header("Location: ../../profile.php?error=$em");
        exit;
    }
}

if (isset($_POST['first_name'], $_POST['last_name'], $_POST['user_pass'], $_POST['position_title'], $_POST['user_email']) || $profileImageFileName !== '') {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $psswd = $_POST['user_pass'];
    $email = $_POST['user_email'];
    $positionTitle = $_POST['position_title'];

    $id = $session_user;

    $sql = "UPDATE users SET ";
    $data = [];
    $types = "";

    if (!empty($fname)) {
        $sql .= "first_name=?, ";
        $data[] = $fname;
        $types .= "s";
    }

    if (!empty($lname)) {
        $sql .= "last_name=?, ";
        $data[] = $lname;
        $types .= "s";
    }

    if (!empty($psswd)) {
        $hashed_psswd = password_hash($psswd, PASSWORD_DEFAULT);
        $sql .= "user_pass=?, ";
        $data[] = $hashed_psswd;
        $types .= "s";
    }

    if (!empty($positionTitle)) {
        $sql .= "position_title=?, ";
        $data[] = $positionTitle;
        $types .= "s";
    }

    if (!empty($email)) {
        $sql .= "user_email=?, ";
        $data[] = $email;
        $types .= "s";
    }

    if ($profileImageFileName !== '') {
        $sql .= "profile_image=?, ";
        $data[] = $profileImageFileName;
        $types .= "s";
    }

    $sql = rtrim($sql, ", ");
    $sql .= " WHERE id=?";
    $data[] = $id;
    $types .= "i";

    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$data);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $stmt->close();

            // Fetch and update session variables if needed
            // Redirect to profile page with success message
            header("Location: ../../profile.php?success=Profile Successfully Updated.");
            exit;
        } else {
            $stmt->close();
            // Handle no changes made
            $em = "No changes were made to the profile.";
            header("Location: ../../profile.php?error=$em");
            exit;
        }
    } else {
        // Handle prepare statement error
        $em = "An error occurred while preparing the statement.";
        header("Location: ../../profile.php?error=$em");
        exit;
    }
} else {
    // Handle invalid request
    $em = "Invalid request.";
    header("Location: ../../profile.php?error=$em");
    exit;
}
?>
