<?php
include 'core/config/config_db.php'; // Your database connection file
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    echo "<script type=\"text/javascript\">
            alert('To continue you must log in.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

$session_user = $_SESSION['id'];

$notificationId = $_POST['notification_id']; // Validate and sanitize this

// Mark the notification as seen
$sql = "UPDATE user_notifications SET is_seen = 1 WHERE notification_id = :notificationId AND user_id = :session_user";
$stmt = $pdo->prepare($sql);
$stmt->execute(['notificationId' => $notificationId, 'session_user' => $session_user]);

echo json_encode(['success' => true]);
?>