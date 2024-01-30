<?php
include_once '../../core/config/transac_setup.php';

if (isset($_POST['first_name']) || isset($_POST['last_name']) || isset($_POST['user_pass']) || isset($_POST['user_email'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $psswd = $_POST['user_pass'];
    $email = $_POST['user_email'];

    $id = $session_user;

    // Check if any of the fields is empty
    if (empty($fname) && empty($lname) && empty($psswd) && empty($email)) {
        $em = "At least one field must be provided for an update";
        header("Location: profile.php?error=$em");
        exit;
    } else {
        // User updating their own profile
        // Update the Database based on provided fields
        $db->set_charset("utf8");
        // Initialize SQL query and data array
        $sql = "UPDATE users SET ";
        $data = [];

        if (!empty($fname)) {
            $sql .= "first_name=?, ";
            $data[] = $fname;
        }

        if (!empty($lname)) {
            $sql .= "last_name=?, ";
            $data[] = $lname;
        }

        if (!empty($psswd)) {
            // Hash the new password before updating
            $hashed_psswd = password_hash($psswd, PASSWORD_DEFAULT);
            $sql .= "user_pass=?, ";
            $data[] = $hashed_psswd;
        }

        if (!empty($email)) {
            $sql .= "user_email=?, ";
            $data[] = $email;
        }

        // Remove the trailing comma and space from the SQL query
        $sql = rtrim($sql, ", ");

        // Add the WHERE clause to update the specific user's data
        $sql .= " WHERE id=?";
        $data[] = $id;

        $stmt = $db->prepare($sql);
        $stmt->execute($data);

    }
} else {
    echo "Error";
}

// After successfully updating the user's profile, you can check if any rows were affected
if ($stmt->affected_rows > 0) { // Check if any rows were affected (i.e., if the update was successful)
    // Fetch the updated user information
    $sql = "SELECT id, first_name, last_name FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Update the session variables with the new user information
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
    }
}
?>
<script type="text/javascript">
	alert("Profile Successfully Updated.");
	window.location = "../../profile.php";
</script>
