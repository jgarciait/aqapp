<?php
header('Content-Type: application/json');
$data = [];

// Check if $_POST with key '123' is set
if (isset($_POST['key']) && ($_POST['key'] === '123')) {
    require 'core/config/config_db.php'; // Assuming correct path

    $sql = "SELECT forms_audit_trail.id AS audit_trail_id, form_001.ref_number, forms_audit_trail.actions, form_001.id
    FROM forms_audit_trail
    INNER JOIN form_001 ON forms_audit_trail.fl_forms_id = form_001.id
    WHERE forms_audit_trail.is_seen = 0;
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $n_numbers = $stmt->rowCount();

    $data[] = ['total' => $n_numbers];

    while ($notification = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $notification;
    }

    echo json_encode($data);
}

?>
