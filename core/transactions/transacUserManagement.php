<?php
include_once '../../core/config/transac_setup.php';

if (isset($_POST['first_name']) || isset($_POST['last_name']) || isset($_POST['user_pass']) || isset($_POST['user_email']) || isset($_POST['ubs_sys_groups_id'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $psswd = $_POST['user_pass'];
    $email = $_POST['user_email'];
    $sysGroupName = $_POST['ubs_sys_groups_id'];

    $id = $_POST['id']; // Use POST instead of GET to ensure data is securely passed

    // Check if any of the fields is empty
    if (empty($fname) && empty($lname) && empty($psswd) && empty($email) && empty($sysGroupName)) {
        $em = "At least one field must be provided for an update";
        header("Location: profile.php?error=$em");
        exit;
    } else {
        // User updating their own profile
        // Update the Database based on provided fields
        $db->set_charset("utf8");
        // Initialize SQL query and data array
        $sql = "UPDATE users AS u
                INNER JOIN users_by_sysgroup AS ubs ON u.id = ubs.ubs_user_id
                INNER JOIN sys_groups AS sg ON ubs.ubs_sys_groups_id = sg.id
                SET ";

        $data = [];

        if (!empty($fname)) {
            $sql .= "u.first_name=?, ";
            $data[] = $fname;
        }

        if (!empty($lname)) {
            $sql .= "u.last_name=?, ";
            $data[] = $lname;
        }

        if (!empty($psswd)) {
            // Hash the new password before updating
            $hashed_psswd = password_hash($psswd, PASSWORD_DEFAULT);
            $sql .= "u.user_pass=?, ";
            $data[] = $hashed_psswd;
        }

        if (!empty($email)) {
            $sql .= "u.user_email=?, ";
            $data[] = $email;
        }

        if (!empty($sysGroupName)) {
            $sql .= "ubs.ubs_sys_groups_id=?, ";
            $data[] = $sysGroupName;
        }

        // Remove the trailing comma and space from the SQL query
        $sql = rtrim($sql, ", ");

        // Add the WHERE clause to update the specific user's data
        $sql .= " WHERE u.id=?";
        $data[] = $id;

        $stmt = $db->prepare($sql);
        $stmt->execute($data);

    }
} else {
    echo "Error";
}

?>
<script type="text/javascript">
	alert("Profile Succesfully Updated");
	window.location = "../../user_management.php";
</script>
