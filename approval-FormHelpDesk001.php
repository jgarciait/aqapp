<?php
include_once 'core/config/forms_setup.php';

$edit_form = $_GET['id'];
$formData = formDataHelpDesk001($edit_form, $db);
$form_level_id = $formData['process_level_id'];
$formId = $formData['fId'];
$workflow_id = $formData['wId'];
$senderId = $formData['fl_sender_user_id'];

// Fetching form audit trail data
$sql = "SELECT * 
        FROM forms_audit_trail
        INNER JOIN users ON users.id = forms_audit_trail.fl_user_id
        INNER JOIN forms_status ON forms_status.forms_id = forms_audit_trail.fl_forms_id
        INNER JOIN formHelpDesk001 ON formHelpDesk001.id = forms_status.forms_id
        LEFT JOIN form_metadata ON form_metadata.id = formHelpDesk001.metadata_id
      ";
$result = mysqli_query($db, $sql);

$count = 1;
?>
<html>
<head>
    <style>
        body {
            background: #f2ecf9;
        }
        #regForm {
            background-color: #ffffff;
            margin: 100px auto;
            font-family: Raleway;
            padding: 40px;
            width: 70%;
            min-width: 300px;
        }

        h1 {
            text-align: center;
        }

        input {
            padding: 10px;
            width: 100%;
            font-size: 17px;
            font-family: Raleway;
            border: 1px solid #aaaaaa;
        }

        /* Mark input boxes that gets an error on validation: */
        input.invalid {
            background-color: #ffdddd;
        }

        /* Hide all steps by default: */
        .tab {
            display: none;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #04AA6D;
        }

        .boximage {
            width: 100%;
            height: 8rem;
            background-image: url(core/assets/css/src/banner1.jpg);
            background-size: cover;
            border-radius: 5px 5px 0 0;
        }

        /*FieldSet headings*/
        .fs-title {
            font-size: 25px;
            color: #2C3E50;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: left;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            text-align: center;
            align-items: center;
        }

        #progressbar .active {
            color: #000000;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 12px;
            width: 32%;
            float: left;
            position: relative;
        }

        /*Icons in the ProgressBar*/
        #progressbar #personal:before {
            font-family: FontAwesome;
            content: "\f007";
        }

        #progressbar #ticket:before {
            font-family: FontAwesome;
            content: "\f145";
        }

        #progressbar #summary:before {
            font-family: FontAwesome;
            content: "\f00c";
        }

        /*ProgressBar before any progress*/
        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 18px;
            color: #ffffff;
            background: #313a46;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px;
            position: relative;
            /* Add this line */
            z-index: 1;
        }

        /*ProgressBar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: #313a46;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: 0;
            /* Increase this z-index value */
        }

        /*Color number of the step and the connector before it*/
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #007bd2;
        }

        label {
            font-size: 1rem;
            font-weight: 400;
        }

        .scale-container {
            width: 100%;
            /* Adjust the width as needed */
            display: flex;
            justify-content: space-between;
        }

        .scale-label {
            flex: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        .scale-label input[type="checkbox"] {
            margin: 5px auto;
        }

        .restricted-input {
            border: 1px solid #ccc;
            padding: 5px;
        }

        .required-label {
            color: red;
            margin-left: 5px;
        }
    </style>
</head>
<body>
<main class="container-login mt-5" id="chart-container">
    <div class="container-form my-2 mb-5 bg-white shadow rounded">
        <div class="boximage"></div>
        <div style="width: 55em;" class="form-content p-3 m-2" id="regForm">
            <input type="hidden" name="workflow_id" value="<?php echo $workflow_id; ?>">
            <div class="text-center">
                <h2>Helpdesk Ticket</h2>
                <p>Reference Number: <strong><?php echo $formData['ref_number']; ?></strong></p>
            </div>
            <hr>
            <div class="text-center">
                <ul id="progressbar">
                    <li class="active" id="summary"><strong>Summary</strong></li>
                    <li id="personal"><strong>User Info</strong></li>
                    <li id="ticket"><strong>Ticket Details</strong></li>
                </ul>
            </div>
 
            <div class="tab mb-3"><p style="font-size:25px; font-weight: 700;">Summary:</p>
                <div id="summary-section">
                    <!-- Summary will be dynamically filled based on previous steps -->
                </div>
           <div class="text-center">
<button class="btn-menu btn-1 hover-filled-opacity" id="chatBtn" onclick="openChatWindow(<?php echo $senderId; ?>)">Chat with Sender</button>
            </div>
        
            </div>

            <div class="tab mb-3 fs-title">User Info:
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="fullName">Full Name:<span></span></label>
                        <input class="form-control" type="text" id="fullName" placeholder="Full Name" value="<?php echo $formData['fullName']; ?>" name="fullName" readonly>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="email">Email:<span></span></label>
                        <input class="form-control" type="email" id="email" placeholder="Email" name="email" value="<?php echo $formData['email']; ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="tab mb-3 fs-title">Incident Information:
                <div class="mb-3">
                    <label for="issueType">Issue Type:<span class="required-label">*</span></label>
                    <input class="form-control" type="text" id="issueType" placeholder="Issue Type" value="<?php echo $formData['issueType']; ?>" name="issueType" readonly>
                </div>
                <div class="mb-3">
                    <label for="issueDescription">Issue Description:<span class="required-label">*</span></label>
                    <textarea class="form-control" id="issueDescription" placeholder="Please describe the issue in detail" name="issueDescription" rows="5" readonly><?php echo $formData['issueDescription']; ?></textarea>
                </div>

            </div>

            <hr>
            <div class="mb-3 p-2">
                <span id="approvalButtons">
                    <button style="margin-right: 10px;" type="submit" name="action" value="revert" class="btn btn-md btn-outline-warning hover-filled-opacity" id="revertBtn" onclick="revertForm();">Revert</button>
                    <button style="margin-right: 10px;" type="submit" name="action" value="reject" class="btn btn-md btn-outline-danger hover-filled-opacity" id="rejectBtn" onclick="rejectForm();">Reject</button>
                    <button style="margin-right: 10px;" type="submit" name="action" value="approve" class="btn btn-md btn-outline-success hover-filled-opacity" id="approveBtn" onclick="approveForm();">Approve</button>  
                </span>
            
                <div style="float: right;">
                    <button type="button" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="nextBtn" onclick="nextPrev(1); ">Next</button>
                </div>
            </div>
        </div>
    </div>
                <div style="display: none; text-align:center;margin-top:40px;">
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                </div>
</main>
<footer id="myFooter" class="footer">
    <p>
        © 2024 All Rights Reserved - Document Control System.
    </p>
</footer>


<script>
// Initialize the current tab
let currentTab = 0;
showTab(currentTab); // Display the current tab

// Function to handle Next and Previous buttons
function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    currentTab += n; // Increment or decrement currentTab based on the value of n
    if (currentTab < 0) {
        currentTab = 0; // Ensure currentTab does not go below 0
    }
    if (currentTab >= x.length) {
        currentTab = x.length - 1; // Ensure currentTab does not exceed the length of x
    }
    showTab(currentTab);
    if (currentTab === 0) {
        toggleApprovalButtons(true); // Show the approval buttons at the final step
        updateSummary(); // Update the summary when showing the first tab
    }
}

// Function to display the completed tabs
function showTab(n) {
    var x = document.getElementsByClassName("tab");
    var progressBarSteps = document.querySelectorAll("#progressbar li");

    // If you are going back, remove "active" class from all the steps after the current step
    for (var i = n + 1; i < progressBarSteps.length; i++) {
        progressBarSteps[i].classList.remove("active");
    }

    // Set the "active" class on the current step
    progressBarSteps[n].classList.add("active");

    for (var i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }

    x[n].style.display = "block";

    if (n === 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }

    if (n === x.length - 1) {
        document.getElementById("nextBtn").style.display = "none"; // Hide the next button
    } else {
        document.getElementById("nextBtn").style.display = "inline";

    }
}

// Update the summary when the page loads
window.onload = function() {
    updateSummary();
};

</script>

<script>
    function updateSummary() {
        var summarySection = document.getElementById("summary-section");

        // Helper function to get the value of an element by ID
        function getValueById(id) {
            return document.getElementById(id).value;
        }

        // Helper function to get the value of a checked radio button
        function getCheckedRadioValue(name) {
            var selectedRadio = document.querySelector("input[name='" + name + "']:checked");
            return selectedRadio ? selectedRadio.value : "";
        }

        var fullName = getValueById("fullName");
        var email = getValueById("email");
        var issueType = getValueById("issueType");
        var issueDescription = getValueById("issueDescription");
        var summaryHTML = "<div class='row'>";
        summaryHTML += "<div class='col-6' style='font-size:16px;'>";
        summaryHTML += "<p><strong>Name: </strong>" + fullName + "</p>";
        summaryHTML += "<p><strong>Email: </strong>" + email + "</p>";
        summaryHTML += "<p><strong>Issue Type: </strong>" + issueType + "</p>";
        summaryHTML += "<p><strong>Issue Description: </strong>" + issueDescription + "</p>";
        summaryHTML += "</div>";
        summaryHTML += "</div>";

        document.getElementById("summary-section").innerHTML = summaryHTML;
    }
        // Function to handle form approval
        function approveForm() {
            // Add logic here to handle form approval
            // For example, show a confirmation dialog and submit the form with an "approved" action
            if (confirm("Do you want to approve the form?")) {
                document.getElementById("action").value = "approv";
                document.getElementById("regForm").submit();
            }
        }

        // Function to handle form rejection
        function rejectForm() {
            // Add logic here to handle form rejection
            // For example, show a confirmation dialog and submit the form with a "rejected" action
            if (confirm("Do you want to reject the form?")) {
                document.getElementById("action").value = "reject";
                document.getElementById("regForm").submit();
            }
        }

        // Function to handle form reversion
        function revertForm() {
            // Add logic here to handle form reversion
            // For example, show a confirmation dialog and submit the form with a "reverted" action
            if (confirm("Do you want to revert the form?")) {
                document.getElementById("action").value = "revert";
                document.getElementById("regForm").submit();
            }
        }
function openChatWindow(senderId) {
    var url = 'aqMessengerChatArea.php?id=' + senderId;
    var win = window.open(url, 'ChatWindow', 'width=750,height=900');
    win.focus();
}


</script>
</body>
</html>
