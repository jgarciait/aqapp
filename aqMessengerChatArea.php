<?php
include_once 'core/config/forms_setup.php';
// Check if the 'id' GET parameter is set
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

      $sql = "SELECT *
            FROM users
            WHERE id = ?";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: ' . $db->error);
    }

    // Bind the session user ID to exclude the current user from the list
    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
} else {
    // Redirect to the contacts page if the 'id' GET parameter is not set
    header("Location: contacts.php");
    exit;

}

?>     
<main class="container-login">
    <div style="width: 30rem;" class="py-3 m-1 bg-white shadow rounded-3">
        <div class="d-flex align-items-center chat-area" > <!-- Set a specific height for the container -->
            <div class="pe-1"> <!-- Adds some spacing to the right of the image -->
                <a class="" href="aqMessenger.php">
                    <i class="back-icon fas fa-arrow-left fa-xl"></i>
                </a>
                <img src="core/assets/uploads/profile_images/<?php echo htmlspecialchars($row['profile_image']); ?>" alt="Profile Image" style="width:40px; height:40px; border-radius: 50%; object-fit: cover;">
            </div>
            <div>
                <?php echo $row['first_name'] . " " . $row['last_name']; ?>
                <p style="color:green; margin-bottom: 0; padding: 0;">Online</p>
            </div>
        </div>
        <div class="chat-box">
            <div class="chat-box-content px-4">

            </div>
        </div>
        <div class="chat-box-input py-3">
            <form class="typing-area" action="#" autocomplete="off">
                <div class="mb-3 px-3">
                    <div class="d-flex form-floating">
                        <input type="text" name="outgoing_id" value="<?php echo $_SESSION['id']; ?>" hidden>
                        <input type="text" name="incoming_id" value="<?php echo $userId; ?>" hidden>
                        <textarea type="text" class="form-control input-field" placeholder="Type a message..." id="chat-message" name="msg" style="height: auto;"></textarea>
                        <label for="chat-message">Type a message...</label>
                        <button type="submit" class="btn btn p-2"><i class="fa-brands fa-telegram fa-2x chat-input-icon"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>
</html>
