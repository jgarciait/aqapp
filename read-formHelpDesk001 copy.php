<?php
include_once 'core/config/forms_setup.php';

$edit_form = $_GET['id'];
$formData = formDataHelpDesk001($edit_form, $db);
$form_level_id = $formData['process_level_id'];
$formId = $formData['fId'];
$workflow_id = $formData['wId'];

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

        #progressbar #confirm:before {
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
                <h2>Helpdesk Ticket Form</h2>
                <p>Fill out the form to submit a support ticket</p>
            </div>
            <hr>
            <div class="text-center">
                <ul id="progressbar">
                    <li class="active" id="personal"><strong>User Info</strong></li>
                    <li id="ticket"><strong>Ticket Details</strong></li>
                    <li id="confirm"><strong>Confirm & Submit</strong></li>
                </ul>
            </div>
            <!-- One "tab" for each step in the form: -->
            <div class="tab mb-3 fs-title">User Info
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

            <div class="tab mb-3 fs-title">Ticket Details:
                <div class="mb-3">
                    <label for="issueType">Issue Type:<span class="required-label">*</span></label>
                    <input class="form-control" type="text" id="issueType" placeholder="Issue Type" value="<?php echo $formData['issueType']; ?>" name="issueType" readonly>
                </div>
                <div class="mb-3">
                    <label for="issueDescription">Issue Description:<span class="required-label">*</span></label>
                    <textarea class="form-control" id="issueDescription" placeholder="Please describe the issue in detail" value="<?php echo $formData['issueDescription']; ?>" name="issueDescription" rows="5" required readonly></textarea>
                </div>
            </div>

            <div class="tab mb-3">Summary:
                <p>Please review your ticket details before submission.</p>
                <div id="summary-section">
                    <!-- Summary will be dynamically filled based on previous steps -->
                </div>
            </div>

            <hr>
            <div class="mb-3 p-2">
                <div style="float: right;">
                    <button type="button" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="nextBtn" onclick="nextPrev(1); updateSummary();">Next</button>
                </div>
            </div>
        </div>
    </div>
</main>
<footer id="myFooter" class="footer">
    <p>
        © 2024 All Rights Reserved - Document Control System.
    </p>
</footer>

<script>
    // Initialize the current tab
    let currentTab = 1;
    showTab(currentTab); // Display the current tab

function showTab(n) {
    // This function will display the specified tab of the form ...
    var x = document.getElementsByClassName("tab");
    x[n - 1].style.display = "block";
    // ... and fix the Previous/Next buttons:
    if (n === 1) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n === x.length) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    // ... and run a function that displays the correct step indicator:
    fixStepIndicator(n);
}

// Event listener for the "Next" button
document.getElementById("nextBtn").addEventListener("click", function() {
    console.log("Next button clicked"); // Add this line for debugging
    nextPrev(1);
});

</script>
<script>
/*

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    var progressBarSteps = document.querySelectorAll("#progressbar li");

    // Hide all tabs
    for (let i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    // Show the current tab
    x[n - 1].style.display = "block"; // Adjust index by subtracting 1
    // Adjust the Previous/Next buttons
    document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline"; // Adjust index comparison
    document.getElementById("nextBtn").style.display = n < x.length ? "inline" : "none";

    console.log("Current tab: " + n); // Add this line for debugging
    console.log("Total tabs: " + x.length); // Add this line for debugging
    // Highlight the current step in the progress bar
    for (let i = 0; i < progressBarSteps.length; i++) {
        progressBarSteps[i].className = progressBarSteps[i].className.replace(" active", "");
    }
    progressBarSteps[n - 1].className += " active"; // Adjust index by subtracting 1
}

    function nextPrev(n) {
        // Increase or decrease the current tab by 1:
        currentTab += n;
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

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
        var summaryHTML = "<hr>";
        summaryHTML += "<div class='row'>";
        summaryHTML += "<div class='col'>";
        summaryHTML += "<p><strong>Full Name:</strong> " + fullName + "</p>";
        summaryHTML += "<p><strong>Email:</strong> " + email + "</p>";
        summaryHTML += "<p><strong>Issue Type:</strong> " + issueType + "</p>";
        summaryHTML += "<p><strong>Issue Description:</strong> " + issueDescription + "</p>";
        summaryHTML += "</div>";
        summaryHTML += "</div>";

        document.getElementById("summary-section").innerHTML = summaryHTML;
    }
*/
</script>
</body>
</html>