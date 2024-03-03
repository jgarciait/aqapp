<?php

include_once 'core/config/main_setup.php';

$sql = "SELECT in_app_noti, email_noti FROM noti_preference WHERE np_user_id = :userId";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':userId' => $session_user));
$userPreferences = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the query returned any results
if (!$userPreferences || !is_array($userPreferences)) {
    // Set default values if no preferences found
    $userPreferences = array('in_app_noti' => 0, 'email_noti' => 0);
}

?>

<style>
.preferenceCard {
    flex: 1;
    background: #fff;
    align-items: center;
    height: 9rem;
    text-align: center;
    justify-content: center; /* Center-align the cards within the container */
    border: 1px solid #909eff; /* Border */
    padding: 10px; /* Padding inside the data card */
    margin: 10px; /* Margin around the data card */
    border-radius: 4px;
    min-width: 15rem;
    max-width: 15rem;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1), 0 3px 15px 0 rgba(0, 0, 0, 0.10);
}
.custom-switch-order {
    display: flex;
    flex-direction: row;
    align-items: center;
    flex-wrap: nowrap;
}

.custom-switch-order .form-check-label {
    margin-left: .5rem; /* Adjust based on your preference */
}

</style>

    <div class="container container-data-card">
        <main class="content-data-card border border-info bg-white shadow my-3 row">
            <div class="row text-center">
                <h4>User Preferences</h4>
            <hr>
            </div>
                <div class="preferenceCard row">
                    <p>Notifications</p>
                    <div class="form-check form-switch custom-switch-order">
                        <input name="in_app_noti" type="checkbox" class="form-check-input" id="inAppNotiCheckbox" <?php echo $userPreferences['in_app_noti'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="inAppNotiCheckbox">In App Notifications</label>
                    </div>
                    <div class="form-check form-switch custom-switch-order">
                        <input name="email_noti" type="checkbox" class="form-check-input" id="emailNotiCheckbox" <?php echo $userPreferences['email_noti'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="emailNotiCheckbox">Email Notifications</label>
                    </div>
                    <div>
                        <span id="alertMessage"></span>
                    </div>
                </div>
            </main>
    </div>
        
    </div>
    <footer id="myFooter" class="footer">
        <p>Document Control Systems Inc.</p>
    </footer>

<script>
    $(document).ready(function () {
        $('input[type="checkbox"]').change(function () {
            var inAppNoti = $('#inAppNotiCheckbox').prop('checked') ? 1 : 0;
            var emailNoti = $('#emailNotiCheckbox').prop('checked') ? 1 : 0;

            $.ajax({
                url: 'updatePreferences.php',
                method: 'POST',
                data: {
                    in_app_noti: inAppNoti,
                    email_noti: emailNoti
                },
                success: function (response) {
                    // Clear previous content
                    $('#alertMessage').empty();
                    // Append new message
                    if (response.success) {
                        $('#alertMessage').append('<div><p style="color: green; font-size: 16px;">' + response.message + '</p></div>');
                    } else {
                        $('#alertMessage').append('<div><p style="color: red; font-size: 16px;">' + response.message + '</p></div>');
                    }
                    // Clear the message after 5 seconds
                    setTimeout(function() {
                        $('#alertMessage').empty();
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        });
    });
</script>



</body>

</html>
