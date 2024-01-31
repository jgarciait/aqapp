<?php 
include_once 'core/config/main_setup.php';
include_once 'core/assets/util/phpMailerFunction.php';

$requestName = '';
$requestDescription = '';
$requestStatus = '';
$workflowsId = '';
$requestUsers = '';
$approvalUsers = '';
$id = isset($_GET['id']) ? $_GET['id'] : null;

$query = "SELECT *, rshift_start_id,
   workflows_creator.wlevel_id, workflows.id AS wId,
   requests.id AS rqid, users.first_name AS request_first_name,
   requests.id AS rqid, users.last_name AS request_last_name,
   GROUP_CONCAT(request_files.file_path) AS file_paths
   FROM requests
   INNER JOIN users ON users.id = requests.requester_user_id
   INNER JOIN workflows_creator ON workflows_creator.id = requests.r_wcreator_id
   INNER JOIN workflows ON workflows.id = requests.r_workflow_id
   LEFT JOIN request_files ON request_files.request_id = requests.id
   WHERE requests.id = " . intval($id);

mysqli_set_charset($db, "utf8");
$sql = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_array($sql)) {
    $rrn = $row['ref_request_num'];
    $zz = $id;
    $za= $row['workflow_name'];
    $workflowsId= $row['wId'];
    $requestName = $row['request_name'];
    $requestDescription = $row['request_description'];
    $requestStatus = $row['request_status'];
    $wlevel_id = $row['wlevel_id'];
    $rFirstName= $row['request_first_name'];
    $rLastName = $row['request_last_name'];
    $wcreatorName = $row['wcreator_name'];
    $file_paths = $row['file_paths'];
    $shiftId = $row['rshift_start_id'];
    $requesterId = $row['requester_user_id'];
    $requestDate = date('F j, Y h:i A', strtotime($row['request_timestamp']));
}
    $sql = "SELECT * FROM users_by_shift
    INNER JOIN shift_groups ON shift_groups.id = users_by_shift.ubs_shift_groups_id
    LEFT JOIN shift_config ON shift_config.id = shift_groups.sg_shift_config_id
    WHERE userByShift_user_id = :requester_user_id";
    $stmtGetShiftConfig = $pdo->prepare($sql);
    $stmtGetShiftConfig->bindParam(':requester_user_id', $requesterId );
    $stmtGetShiftConfig->execute();

    if ($row = $stmtGetShiftConfig->fetch(PDO::FETCH_ASSOC)){
        $shiftApproved = date('h:i A', strtotime($row['shift_start_time_allowed']));
    }

    $sql = "SELECT start_time, start_time_status, shift_table.id AS shiftId FROM shift_table 
    WHERE id = :id";
    $stmtGetShiftHoursAllowed = $pdo->prepare($sql);
    $stmtGetShiftHoursAllowed->bindParam(':id', $shiftId);
    $stmtGetShiftHoursAllowed->execute();

    if ($row = $stmtGetShiftHoursAllowed->fetch(PDO::FETCH_ASSOC)) {
        $startTime = date('F j, Y h:i A', strtotime($row['start_time']));
        $startStatusTime = $row['start_time_status'];
    } else {
        $startTime = 'N/A'; // Set a default value if no start time is found
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AQPlatform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/profileStyle.css">
    
    <style>
        /* Add your tab styling here */
        .tab {
            display: none;
        }
    </style>
</head>
<body>
<div class="d-flex justify-content-center py-5 mt-5 vh-100"> 
    <div class="tab" id="tab1">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary" onclick="showTab('tab1')">Ponche</button>
            <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab2')">Archivos</button>
            <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab3')">AQChat</button>
            <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab4')">Aprobaciones</button>
        </div>
            <form class="shadow w-450 p-3"
                >      
                <?php
                    // Get the current timestamp
                    $timestamp = date('Y-m-d H:i:s');
                    $requestTimestamp = date('Y-m-d H:i:s');
                ?>
                <h4 class="display-4 fs-4">Solicitud de Aprobación de Ponche</h4><br>
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
                    <label>Intento de Ponche en: </label>
                    <input type="text" class="form-control" name="start_time" value="<?php echo $startTime; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label>Corresponde ponchar: </label>
                    <input type="text" class="form-control" name="start_time" value="<?php echo $shiftApproved; ?>" readonly>
                </div>
                  <div class="mb-3">
                    <label class="form-label">Nombre y Apellido:</label>
                    <input type="text" 
                    class="form-control"
                    value="<?php echo $rFirstName; ?> <?php echo $rLastName; ?>"readonly>
                </div>
                <div class="mb-3">
                <label class="form-label">Razón:</label>
                <input type="text" 
                class="form-control"
                name="request_name"
                value="<?php echo $requestName; ?>"readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Justificación de Empleado:</label>
                <input type="text" 
                class="form-control"
                name="request_description"
                value="<?php echo $requestDescription; ?>"readonly>
            </div>
  
            <div class="mb-3">
            <hr>
                <details>
                    <summary>Más información</summary><br>
                    <div class="mb-3">
                        <label class="form-label">Número de Referencia:</label>
                        <input type="text" 
                        class="form-control"
                        name="ref_request_num"
                        value="<?php echo $rrn; ?>"readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estatus de Solicitud</label>
                        <input type="text" 
                        class="form-control"
                        name="request_status"
                        value="<?php echo $requestStatus; ?>"readonly>
                    </div>
                    <div class="mb-3">
                     <!-- <label class="form-label">Workflow id</label> -->
                        <input type="hidden" 
                        class="form-control"
                        name="r_workflow_id"
                        value="<?php echo $workflowsId; ?>"readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Módulo</label>
                        <input type="text" 
                        class="form-control"
                        name="workflow_name"
                        value="<?php echo $za; ?>"readonly>
                    </div>
                
                    <div class="mb-3">
                        <input type="hidden" 
                        name="wlevel_id"
                        value="<?php echo $wlevel_id; ?>"readonly>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" 
                        name="request_users"
                        value="<?php echo $requestUsers; ?>"readonly>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" 
                        name="wcreator_name"
                        value="<?php echo $wcreatorName; ?>"readonly>
                    </div>
                </details>
            </div>
        </form>
    </div>
    <div class="tab" id="tab2">
        <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab1')">Info</button>
            <button type="button" class="btn btn-secondary" onclick="showTab('tab2')">Archivos</button>
            <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab3')">AQChat</button>
            <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab 4')">Aprobaciones</button>
        </div>
        <form class="shadow w-450 p-3" 
            >
            <div id="fileTableContainer">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Archivo</th>
                            <th>Ver</th>
                        </tr>
                    </thead>
                        <tbody id="fileTableBody">
                            <?php
                                $filePaths = explode(",", $file_paths); // Split file paths into an array
                                    foreach ($filePaths as $filePath) {
                                    // Extract file name and size from the file path
                                    $fileName = basename($filePath);
                                    $fileSize = filesize($filePath);
                                    $fileSizeFormatted = "<script>document.write(formatBytes($fileSize));</script>";

                                    echo "<tr>";
                                    echo "<td>$fileName</td>";

                                    echo "<td>";
                                    $decodedFilePath = urldecode($filePath); // Decode the file path
                                    // Display the link with the decoded file path
                                    echo '<a href="http://localhost/ireport/' . $decodedFilePath . '" target="_blank">Link</a><br>';
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                </table>
            </div>
        </form>
    </div>
<div class="tab" id="tab3">

    <div class="mb-3">
        <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab1')">Info</button>
        <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab2')">Archivos</button>
        <button type="button" class="btn btn-secondary" onclick="showTab('tab3')">AQChat</button>
        <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab4')">Aprobaciones</button>
    </div>

    <div class="chat-container">
        <div class="chat-input">
            <input type="text" id="message-input" placeholder="Type your message">
            <button type="button" onclick="sendMessage()">Send</button>
        </div>
        </br>
        <p>Comentarios</p>
        <div class="card-body rounded shadow chat-messages" id="chat-messages">
        <hr>
            <!-- Chat messages will be displayed here -->
        </div>
    </div>
</div>

    <div class="tab" id="tab4">
        <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab1')">Info</button>
            <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab2')">Archivos</button>
            <button type="button" class="btn btn-outline-secondary" onclick="showTab('tab3')">AQChat</button>
            <button type="button" class="btn btn-secondary" onclick="showTab('tab4')">Aprobaciones</button>
        </div>


        <form class="shadow w-450 p-3"
            method="post"
            enctype="multipart/form-data"
            action="transacApprovalAttendance.php"
            >
            <p>Sección de Aprobaciones</p>
            <div class="row">
            <input name="r_workflow_id" type="hidden" value="<?php echo $workflowsId; ?>" />
            <input name="id" type="hidden" value="<?php echo $zz; ?>" />      
                <div class="mb-3">
                    <label class="form-label">Turno Aprobado</label>
                    <input type="text" class="form-control" value="<?php echo $shiftApproved; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estatus del Ponche</label>
                    <select type="text" class="form-control" name="start_time_status" id="start_time_status">
                        <option>Estatus Actual: <?php echo $startStatusTime; ?></option>
                        <option type="option" value="Ponche Ajustado">Ponche Ajustado</option>
                        <option type="option" value="Ponche Devuelto">Ponche Devuelto</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ajustes de Ponche: </label>
                    <input type="datetime-local" class="form-control" name="start_time_display" id="start_time" value="<?php echo $startTime; ?>" readonly>
                    <input type="hidden" class="form-control" name="shiftId" id="shiftId" value="<?php echo $shiftId; ?>" readonly>
                </div>
                <div class="mb-3">
                    <details>
                        <summary>Intento de Ponche:</summary>
                        <input type="text" class="form-control" value="<?php echo $startTime; ?>" readonly>
                    </details>
                </div>
                    <button type="button" id="editButton" class="btn btn-primary">Editar</button>
                    <button type="button" id="saveButton" class="btn btn-success" style="display: none;">Guardar</button>
                    <button type="button" id="cancelButton" class="btn btn-danger" style="display: none;">Cancelar</button>
                <div class="col p-3">
                    <button type="submit" name="approved" class="btn" style="font-size: 14px; color:white; background-color:#215f92;">Aprobar sin Ajuste</button>
                </div>
                <div class="col p-3">
                    <button type="submit" name="adjust_approved" class="btn" style="font-size: 14px; color:white; background-color:#215f92;">Aprobar con Ajuste</button>
                </div>
                <div class="col p-3">
                    <button type="submit" name="reverted" class="btn" style="font-size: 14px; color:white; background-color:#215f92;">Requerir mas Información</button>
                </div>
                <div class="col p-3">
                    <a type="button" class="btn btn-xs" style="color:white; background-color:#215f92;" href="attendanceApproval.php?workflow_id=<?php echo $workflowsId; ?>">Regresar</a>
                </div>
            </div>
        </form>
</div>
<script>
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
</script>

    <script>
    // Function to show a specific tab
    function showTab(tabId) {
        // Hide all tabs
        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => tab.style.display = 'none');

        // Show the selected tab
        document.getElementById(tabId).style.display = 'block';
    }

    // Show the default tab 'tab1' when the page loads
    window.addEventListener('load', function () {
        showTab('tab1');
    });
    </script>
    <script>
    // Variables to store the canvas and signature table
    const signatureCanvas = document.getElementById('signatureCanvas');
    const signatureTableBody = document.getElementById('signatureTableBody');
    
    // Initialize the drawing context
    const ctx = signatureCanvas.getContext('2d');
    let isDrawing = false;
    
    // Event listener to start drawing when mouse is pressed
    signatureCanvas.addEventListener('mousedown', () => {
        isDrawing = true;
        ctx.beginPath();
    });
    
    // Event listener to continue drawing while mouse is moved
    signatureCanvas.addEventListener('mousemove', (e) => {
        if (!isDrawing) return;
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = 'black';
        ctx.lineTo(e.clientX - signatureCanvas.getBoundingClientRect().left, e.clientY - signatureCanvas.getBoundingClientRect().top);
        ctx.stroke();
    });
    
    // Event listener to stop drawing when mouse is released
    signatureCanvas.addEventListener('mouseup', () => {
        isDrawing = false;
    });
    
    // Function to clear the signature canvas
    function clearSignature() {
        ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
    }
    
    // Function to save the signature and display it in the table
    function saveSignature() {
        const signatureDataUrl = signatureCanvas.toDataURL();
        const signatureImage = document.createElement('img');
        signatureImage.src = signatureDataUrl;
        
        const row = signatureTableBody.insertRow();
        const cell = row.insertCell(0);
        cell.appendChild(signatureImage);
    }
</script>
<script>
// Get references to HTML elements
const startStatusInput = document.getElementById('start_time_status');
const startTimeInput = document.getElementById('start_time');
const editButton = document.getElementById('editButton');
const saveButton = document.getElementById('saveButton');
const cancelButton = document.getElementById('cancelButton');

// Store the original values
let originalStartStatusValue = startStatusInput.value;
let originalStartTimeValue = startTimeInput.value;

// Function to enable editing mode
function enableEditMode() {
    startStatusInput.removeAttribute('readonly');
    startTimeInput.removeAttribute('readonly');
    editButton.style.display = 'none';
    saveButton.style.display = 'inline';
    cancelButton.style.display = 'inline';
}

// Function to cancel editing and revert to viewing mode
function cancelEditMode() {
    startStatusInput.setAttribute('readonly', true);
    startTimeInput.setAttribute('readonly', true);
    editButton.style.display = 'inline';
    saveButton.style.display = 'none';
    cancelButton.style.display = 'none';
    
    // Reset input values to their original values
    startStatusInput.value = originalStartStatusValue;
    startTimeInput.value = originalStartTimeValue;
}

// Function to update the value in the database
function updateValueInDatabase() {
    // Get the new values
    const newStartStatusValue = startStatusInput.value;
    const newStartTimeValue = startTimeInput.value;

    // Perform an AJAX request to update the value in the database
    // Replace this with your actual update logic using AJAX

    // After the update is successful, you can optionally update the original values
    originalStartStatusValue = newStartStatusValue;
    originalStartTimeValue = newStartTimeValue;

    // Disable editing mode
    cancelEditMode();
}

// Add click event listeners to the buttons
editButton.addEventListener('click', enableEditMode);
cancelButton.addEventListener('click', cancelEditMode);
saveButton.addEventListener('click', updateValueInDatabase); // Call updateValueInDatabase on Save button click
</script>

<script>

function sendMessage() {
    // Get the message input
    var messageInput = document.getElementById("message-input");
    var messageText = messageInput.value;

    // Check if the message is not empty
    if (messageText.trim() !== "") {
        // Create a new message element
        var messageElement = document.createElement("div");
        messageElement.classList.add("message");
        messageElement.textContent = messageText;

        // Append the message to the chat container
        var chatMessages = document.getElementById("chat-messages");
        chatMessages.appendChild(messageElement);

        // Clear the message input
        messageInput.value = "";

        // Scroll to the bottom of the chat container
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Send the message to the server
        sendToServer(messageText);
    }
}

function sendToServer(messageText) {
    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define the PHP script URL where you'll handle the message insertion
    var url = "transacChatForm.php"; // Replace with the actual URL

    // Prepare the POST request
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Define the data to be sent to the server (message and user ID)
    var data = "message=" + encodeURIComponent(messageText) +
               "&recipient_user_id=" + encodeURIComponent(<?php echo $requesterId; ?>) +
               "&user_id=" + encodeURIComponent(<?php echo $_SESSION['id']; ?>);

    // Set up a callback function to handle the response from the server
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the server's response (if needed)
            console.log(xhr.responseText); // Log the response for debugging
        }
    };

    // Send the POST request with the data
    xhr.send(data);
}


</script>


</body>
</html>

