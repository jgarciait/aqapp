<?php
include 'core/config/config_db.php'; // Your database connection file

$notificationId = $_POST['notification_id']; // Validate and sanitize this

// Mark the notification as seen
$sql = "UPDATE forms_audit_trail SET is_seen = 1 WHERE id = :notificationId";
$stmt = $pdo->prepare($sql);
$stmt->execute(['notificationId' => $notificationId]);

echo json_encode(['success' => true]);
?>