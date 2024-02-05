<?php
include_once 'core/config/forms_setup.php';

$edit_form = $_GET['id'];
$formData = getFormData($edit_form, $db);
?>

<main class="container-login">
    <form style="width: 26rem;" class="p-5 m-2 bg-white shadow rounded"
        action="core/transactions/transacUserManagement.php" 
        method="post"
        enctype="multipart/form-data">

        <h4><span><?php echo $formData['form_name']; ?></span></h4>
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
                value="<?php echo $formData['ref_number']; ?>"
                readonly> <!-- Add readonly attribute here -->
        </div>
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" 
                class="form-control"
                name="firstName"
                value="<?php echo $formData['firstName']; ?>"
                readonly> <!-- Add readonly attribute here -->
        </div>
        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" 
                class="form-control"
                name="lastName"
                value="<?php echo $formData['lastName']; ?>"
                readonly> <!-- Add readonly attribute here -->
        </div>
        <div class="mb-3">
            <label class="form-label">Service</label>
            <input type="text" 
                class="form-control"
                name="service_request"
                value="<?php echo $formData['service_request']; ?>"
                readonly> <!-- Add readonly attribute here -->
        </div>
        <br>
        <br><br>
        <input type="hidden" name="id" value="<?php echo $edit_profile; ?>">
    </form>
</main>

<footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>
</html>
