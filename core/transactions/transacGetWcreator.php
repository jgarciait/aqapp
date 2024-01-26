<?php

include_once '../../core/config/transac_setup_admin.php';

// Check if the workflow_id parameter is provided in the query string
if (isset($_GET['userId']) && isset($_GET['workflowId'])) {
    $userId = $_GET['userId'];
    $wid = $_GET['workflowId'];

    mysqli_set_charset($db, "utf8");
    $sql = "SELECT * FROM workflows_creator
            LEFT JOIN users_by_wcreator ON users_by_wcreator.wcreator_id = workflows_creator.id
            WHERE wcreator_workflows_id = ? 
            AND users_by_wcreator.ubw_user_id = ?";
    $stmt = mysqli_prepare($db, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $wid, $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0) {
            // First query result is empty
            // Run another query without considering userId
            $sql = "SELECT * FROM workflows_creator
                    WHERE wcreator_workflows_id = ?";
            $stmt = mysqli_prepare($db, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $wid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
            } else {
                // Handle statement creation error
                echo json_encode([]);
                exit;
            }
        }

        $wcreatorData = array(); // Initialize as an array
        while ($row = mysqli_fetch_assoc($result)) {
            // Append data to the array as an associative array
            $wcreatorData[] = array(
                "id" => $row["id"],
                "wcreator_name" => $row["wcreator_name"]
            );
        }

        // Return JSON response
        echo json_encode($wcreatorData);
    } else {
        // Handle statement creation error
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

?>
