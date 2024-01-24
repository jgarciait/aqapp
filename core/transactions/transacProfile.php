<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    header("Location: index.php");
    exit;
}

include "connection.php";
include 'functions.php';

$session_user = $_SESSION['id'];

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
?>
<script type="text/javascript">
	alert("Perfil Actualizado Exitósamente.");
	window.location = "profile.php";
</script>
