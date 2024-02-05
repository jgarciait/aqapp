<?php

include_once 'core/config/forms_setup.php';

$edit_form = $_GET['id'];
$formData = getFormData($edit_form, $db);

// Fetch comments from the database
$commentsQuery = "SELECT users.first_name AS commentFirstName, users.last_name AS commentLastName, comment_user_id, comment_text, comment_timestamp 
FROM form_comments
INNER JOIN users ON users.id = form_comments.comment_user_id 
WHERE form_comments.fc_form_id = $edit_form";
$commentsResult = mysqli_query($db, $commentsQuery);
?>

<main class="container-login">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#form1">Form 001</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#form2">Comments</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="form1">
            <form style="width: 26rem;" class="p-4 bg-white shadow rounded" action="core/transactions/transacUserManagement.php" method="post" enctype="multipart/form-data">
                <!-- Form 1 content -->
                <h4><span><?php echo $formData['form_name']; ?> (Draft)</span></h4>
              
                <hr>
                <!-- error -->
                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php } ?>
                <!-- success -->
                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_GET['success']; ?>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label class="form-label">Case Number</label>
                    <input type="text" 
                    class="form-control"
                    name="ref_number"
                    value="<?php echo $formData['ref_number']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" 
                    class="form-control"
                    name="firstName"
                    value="<?php echo $formData['firstName']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" 
                    class="form-control"
                    name="lastName"
                    value="<?php echo $formData['lastName']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Service</label>
                    <input type="text" 
                    class="form-control"
                    name="service_request"
                    value="<?php echo $formData['service_request']; ?>">
                </div>
                <br>
                <div class="row" >
                    <div class="col" style="text-align: start;">
                        <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Save as Draft</span></button>
                    </div>
                    <div class="col" style="text-align: end;">
                        <button type="submit" class="btn-menu btn-1 hover-filled-opacity" style="border: 1px solid tomato;"><span>Submit Form</span></button>
                    </div>
                </div>
                <br><br>
                <input type="hidden" name="id" value="<?php echo $edit_profile; ?>">
                <!-- ... -->
            </form>
        </div>
        <div class="tab-pane fade" id="form2">
            <form style="width: 26rem;" class="p-4 bg-white shadow rounded" action="#" method="post" enctype="multipart/form-data">
                <!-- Form 2 content -->
                <!-- error -->
                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php } ?>
                <!-- success -->
                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_GET['success']; ?>
                    </div>
                <?php } ?>
                <!-- Comments section -->
                <div class="bg-white">
                    <!-- Comments display -->
                    <div class="chat-box">
                        <?php
                        while ($commentRow = mysqli_fetch_assoc($commentsResult)) {
                            $commentText = $commentRow['comment_text'];
                            $commentUser = $commentRow['commentFirstName'] . " " . $commentRow['commentLastName'];
                            $commentTimestamp = $commentRow['comment_timestamp'];
                            
                            ?>
                            <div class="chat-message">
                                <strong><?php echo $commentUser; ?>:</strong>
                                <p><?php echo $commentText; ?></p>
                                <span class="timestamp"><?php echo $commentTimestamp; ?></span>
                            <hr>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

                    <!-- Add new comment -->
                    <div class="mb-3">
                        <label class="form-label">Add Comment</label>
                        <input type="text" class="form-control" name="new_comment" placeholder="Type your comment here">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <footer id="myFooter" class="footer">
        <p>Document Control Systems Inc.</p>
    </footer>
</main>

<script>
    $(document).ready(function() {
        $('.nav-tabs a').click(function() {
            $(this).tab('show');
        });
    });
</script>
</body>
</html>
