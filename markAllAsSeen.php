<?php
include 'core/config/config_db.php'; // Your database connection file
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$session_user = $_SESSION['id'];

// Mark all notifications as seen for the user
$sql = "UPDATE user_notifications SET is_seen = 1 WHERE user_id = :session_user AND is_seen = 0";
$stmt = $pdo->prepare($sql);
$executed = $stmt->execute(['session_user' => $session_user]);

if ($executed) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update notifications']);
}
?>
