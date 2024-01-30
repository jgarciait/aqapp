<?php
include_once 'core/config/forms_setup.php';

$workflow_id = $_GET['workflow_id'];
$lastInsertedId = isset($_GET['shift_table_id']) ? $_GET['shift_table_id'] : null; // Check if the key exists
$user_data = getUserById2($session_user, $workflow_id, $db);
?>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <form class="p-5 m-2 bg-white shadow rounded" role="form" method="POST" action="core/transactions/transacApprovalRequest2.php?workflow_id=<?php echo $workflow_id; ?>" id="submitReport">
            <div>
                <h2>Justificación</h2>
            </div>

            <p class="dcs">Document Control Systems Inc.</p>

            <hr>
            <div class="row">
                <input type="hidden" id="from_date_hidden" name="request_name" value="Ponche fuera de tiempo">
                <input type="hidden" name="rshift_start_id" value="<?php echo $lastInsertedId; ?>">
                <?php
                ?>
            </div>
            <div class="mb-3">
                <div class="form-control">
                <label>Razón por la cual no ponchó a tiempo.</label>
                    <textarea class="form-control" type="text" name="request_description"></textarea>
                </div>
            </div>
            <input type="hidden" name="r_workflow_id" value="<?php echo $workflow_id; ?>">
            <div class="row">
                <div class="col my-2" >
                    <button type="submit" class="btn" style="color:white; background-color:#215f92;">Enviar Razón</button>
                </div>
        
            </div>
        </form>
    </div>
</body>

</html>