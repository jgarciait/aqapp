<?php

include_once 'core/config/forms_setup.php';

$edit_form = $_GET['id'];
$formData = getFormData($edit_form, $db);
$form_level_id = $formData['process_level_id'];
$formId = $formData['fId'];
$workflow_id = $formData['wId'];
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
.boximage{
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
}

#progressbar .active {
    color: #000000;
}

#progressbar li {
    list-style-type: none;
    font-size: 12px;
    width: 25%;
    float: left;
    position: relative;
}

/*Icons in the ProgressBar*/
#progressbar #personal:before {
    font-family: FontAwesome;
    content: "\f007";
}

#progressbar #vaccine:before {
    font-family: FontAwesome;
    content: "\f481";
}

#progressbar #health:before {
    font-family: FontAwesome;
    content: "\f481";
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
    position: relative; /* Add this line */
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
    z-index: 0; /* Increase this z-index value */
}


/*Color number of the step and the connector before it*/
#progressbar li.active:before, #progressbar li.active:after {
    background: #007bd2;
}

label {
    font-size: 1rem;
    font-weight: 400;
}

  .scale-container {
    width: 100%; /* Adjust the width as needed */
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

<main class="container-login mt-5" id="chart-container">
    <div class="container-form my-2 mb-5 bg-white shadow rounded" >
 
  <form style="width: 55em;" class="form-content p-3 m-2" id="regForm" action="core/transactions/transacApprovalForm001.php" method="post">
        <input type="hidden" name="workflow_id" value="<?php echo $workflow_id; ?>">
        <input type="hidden" name="form_level_id" value="<?php echo $form_level_id; ?>">
        <input type="hidden" name="formId" value="<?php echo $formId; ?>">
            <div class="text-center">
                <h2>Service Template</h2>
                <p>Request Reference Number: <strong><?php echo $formData['ref_number']; ?></strong></p>
            </div>
            <hr>
            <div class="text-center">
                <ul id="progressbar">
                    <li class="active" id="personal"><strong>Step 1</strong></li>
                    <li id="vaccine"><strong>Step 2</strong></li>
                    <li id="health"><strong>Step 3</strong></li>
                    <li id="confirm"><strong>Approval Evaluation</strong></li>
                </ul>
            </div>
            <!-- One "tab" for each step in the form: -->
            <div class="tab mb-3 fs-title">User Info
              <div class="row">
                  <div class="col-3 mb-3">
                      <label for="firstName">Name:<span></span></label>
                      <input autocomplete="given-name" class="form-control" id="firstName" name="firstName"
                        value="<?php echo $formData['firstName']; ?>"readonly>
                  </div>
                  <div class="col-4 mb-3">
                      <label for="lastName">Last Name:<span class="required-label">*</span></label>
                      <input autocomplete="family-name" class="form-control" id="lastName" name="lastName"
                       value="<?php echo $formData['lastName']; ?>"readonly>
                  </div>
                  <div class="col-2 mb-3">
                      <label for="age">Age:<span class="required-label">*</span></label>
                      <input autocomplete="age" class="form-control" id="age" placeholder="Age" name="age"
                       value="<?php echo $formData['age']; ?>"readonly>
                  </div>
                    <div class="col mb-3">
                      <label for="gender">Gender:<span class="required-label">*</span></label>
                      <input autocomplete="gender" class="form-control" id="gender" name="gender"
                       value="<?php echo $formData['gender']; ?>"readonly>
                  </div>
              </div>
              <div class="mb-3">
              <label class="form-label">Physical Address</label>
                  <input type="text" 
                  class="form-control"
                  name="physical_address"
                  value="<?php echo $formData['physical_address']; ?>"
                  readonly>
              </div>
              <div class="mb-3">
              <label class="form-label">Postal Address</label>
                  <input type="text" 
                  class="form-control"
                  name="postal_address"
                  value="<?php echo $formData['postal_address']; ?>"
                  readonly>
              </div>
              <div class="row">
                <div class="col-4 mb-3">
                  <label class="form-label">Sector</label>
                    <input type="text" 
                    class="form-control"
                    name="sector"
                    value="<?php echo $formData['sector']; ?>"
                    readonly>
                </div>
                <div class="col-4 mb-3">
                  <label class="form-label">Phone</label>
                    <input type="text" 
                    class="form-control"
                    name="phone"
                    value="<?php echo $formData['phone']; ?>"
                    readonly>
                </div>
                <div class="col mb-3">
                  <label class="form-label">Email</label>
                    <input type="text" 
                    class="form-control"
                    name="email"
                    value="<?php echo $formData['email']; ?>"
                    readonly>
                </div>
              </div>

            <div class="form-group row" id="conditions">
<!--
               <label>Template Fields</label>
                <div class="col">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Depresión">
                    <label class="form-check-label" for="conditions">Depresión</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Hipertensión">
                    <label class="form-check-label" for="conditions">Hipertensión</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Diabetes">
                    <label class="form-check-label" for="conditions">Diabetes</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Hipotiroidismo">
                    <label class="form-check-label" for="conditions">Hipotiroidismo</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Artritis">
                    <label class="form-check-label" for="conditions">Artritis</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Hipercolesterolemia">
                    <label class="form-check-label" for="conditions">Hipercolesterolemia</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Asma">
                    <label class="form-check-label" for="conditions">Asma</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Condición Renal">
                    <label class="form-check-label" for="conditions">Condición renal</label>
                  </div>
                </div>
                <div class="col">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="ArritmiaCardiaca">
                    <label class="form-check-label" for="conditions">Arritmia Cardiaca</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions"" name="conditions[]" value="ApneaSueno"">
                    <label class="form-check-label" for="conditions"">Apnea del Sueño</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Anemia">
                    <label class="form-check-label" for="conditions">Anemia</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="COPD">
                    <label class="form-check-label" for="conditions">COPD</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="VIH">
                    <label class="form-check-label" for="conditions">VIH</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="conditions" name="conditions[]" value="Neuropatia">
                    <label class="form-check-label" for="conditions">Neuropatía</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="otherConditions" name="conditions[]" value="Otros">
                    <label class="form-check-label" for="conditions">Otros</label>
                  </div>

                </div>
              </div>

              <div class="form-group mb-3" id="otherConditionGroup" style="display: none;">
                <hr>
                <label for="otherConditions">Otra Condición</label>
                <input class="form-control" type="text" id="otherConditions" name="otherConditions">
              </div>
              <label class="mb-3 mt-4">¿Cómo se siente de salud al día de hoy? <span class="required-label">*</span></label>
              <div class="scale-container">
                  <div class="scale-label">
                      <label class="mt-4" for="bad">Desmejorada</label>
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="1">1</label>
                      <input type="radio" id="1" name="h_feeling" value="1">
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="2">2</label>
                      <input type="radio" id="2" name="h_feeling" value="2">
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="3">3</label>
                      <input type="radio" id="3" name="h_feeling" value="3">
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="4">4</label>
                      <input type="radio" id="4" name="h_feeling" value="4">
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="5">5</label>
                      <input type="radio" id="5" name="h_feeling" value="5">
                  </div>
                  <div class="scale-label">
                      <label class="mt-4" for="excellent">Bien</label>
                  </div>
  -->              </div>
              </div>

              <div class="tab mb-3 fs-title">Step 2:
             <div class="col mb-3">
              <label class="form-label">Service Requested</label>
                    <input type="text" 
                    class="form-control"
                    name="service_request"
                    value="<?php echo $formData['service_request']; ?>"
                    readonly>
            </div>

                <label class="mb-3 mt-4">Field 2</label>
                <div class="scale-container">
                    <div class="scale-label">
                        <label class="mt-4" for="bad"></label>
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="1"></label>
                        <input type="radio" id="" name="" value="">
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="2"></label>
                        <input type="radio" id="" name="" value="">
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="3"></label>
                        <input type="radio" id="3" name="" value="">
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="4"></label>
                        <input type="radio" id="4" name="" value="">
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="5"></label>
                        <input type="radio" id="5" name="" value="">
                    </div>
                </div>
                <div class="mt-4 mb-3">
                  <label>Field 3</label>
                    <div class="mt-4 mb-3 form-check">
                      <input class="form-check-input" type="radio" name="" id="" value="">
                      <label class="form-check-label" for="">
                        A
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="" id="" value="">
                      <label class="form-check-label" for="">
                        B
                      </label>
                    </div>
                </div>
              </div>  
              <div class="tab mb-3 fs-title">Step 3:
              <div class="row">
              <div class="col mb-3">
                <label for="">Field 1:</label>
                <input class="mb-3 form-control text-center" type="number" id="Text" name="" value="120">
                <input class="form-control" type="range" id="" min="70" max="200" step="1">
              </div>
                <div class="col mb-3">
                  <label for="diastolic">Field 2:</label>
                  <input class="mb-3 form-control text-center" type="number" id="diastolicText"  name="diastolic" value="80">
                  <input class="form-control" type="range" id="diastolic" min="40" max="130" step="1">
                </div>
                 <div class="col mb-3">
                  <label for="pulse">Field 3:</label>
                  <input class="mb-3 form-control text-center" type="number" id="pulseText" name="pulse" value="90">
                  <input class="form-control" type="range" id="pulse"  min="60" max="160" step="1">
                </div>
              </div>
              <div class="row">
                <div class="col mb-3">
                  <label for="bloodSugar">Field 4:</label>
                  <input class="mb-3 form-control text-center" type="number" id="bloodSugarText" name="bloodSugar" value="120">
                  <input class="form-control" type="range" id="bloodSugar"  min="50" max="500" step="1">
                </div>
                <div class="col mb-3">
                  <label for="bodyTemp">Field 5:</label>
                  <input class="mb-3 form-control text-center" type="number" id="bodyTempText" name="bodyTemp" value="97">
                  <input class="form-control" type="range" id="bodyTemp"  min="95" max="105" step="0.1">
                </div>
               <div class="col mb-3">
                  <label for="weight">Field 6:</label>
                  <input class="mb-3 form-control text-center" type="number" id="weightText" name="weight" value="120">
                  <input class="form-control" type="range" id="weight"  min="50" max="500" step="1">
                </div>
            </div>
            </div>

  <!-- Approval Section -->
        <div class="tab mb-3">
            <div class="fs-title">Approval Section:</div>
            <!-- Your approval form content goes here -->
        </div>
        <hr>
        <div class="mb-3 p-2">
            <div style="float: right;">
                <button type="button" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="prevBtn" onclick="nextPrev(-1);">Previous</button>
                <button type="button" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="nextBtn" onclick="nextPrev(1);">Next</button>
                <span id="approvalButtons" style="display: none;">
                    <button type="submit" name="action" value="revert" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="revertBtn" onclick="revertForm();">Revert</button>
                    <button type="submit" name="action" value="approve" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="approveBtn" onclick="approveForm();">Approve</button>
                    <button type="submit" name="action" value="reject" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="rejectBtn" onclick="rejectForm();">Reject</button>
                </span>
            </div>
        </div>

        <!-- Circles which indicate the steps of the form: -->
        <div style="display: none; text-align:center;margin-top:40px;">
            <span class="step"></span>
            <span class="step"></span>
            <span class="step"></span>
            <span class="step"></span>
        </div>
    </form>

    <script>
        // Function to show or hide the approval buttons
        function toggleApprovalButtons(show) {
            const approvalButtons = document.getElementById("approvalButtons");
            if (show) {
                approvalButtons.style.display = "inline";
            } else {
                approvalButtons.style.display = "none";
            }
        }

        // Initialize the current tab
        let currentTab = 0;
        showTab(currentTab); // Display the current tab

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
      document.getElementById("nextBtn").style.display = "none";
      toggleApprovalButtons(true); // Show the approval buttons at the final step
  } else {
      document.getElementById("nextBtn").style.display = "inline";
      toggleApprovalButtons(false); // Hide the approval buttons when not at the final step
  }

}

        // Function to handle Next and Previous buttons
        function nextPrev(n) {
            var x = document.getElementsByClassName("tab");
            if (n === 1 && !validateForm()) {
                return false;
            }
            x[currentTab].style.display = "none";
            currentTab += n;
            if (currentTab >= x.length) {
                toggleApprovalButtons(true); // Show the approval buttons at the final step
                return false;
            } else {
                toggleApprovalButtons(false); // Hide the approval buttons when not at the final step
            }
            showTab(currentTab);
        }

        // Function to handle form approval
        function approveForm() {
            // Add logic here to handle form approval
            // For example, show a confirmation dialog and submit the form with an "approved" action
            if (confirm("Do you want to approve the form?")) {
                document.getElementById("action").value = "approved";
                document.getElementById("regForm").submit();
            }
        }

        // Function to handle form rejection
        function rejectForm() {
            // Add logic here to handle form rejection
            // For example, show a confirmation dialog and submit the form with a "rejected" action
            if (confirm("Do you want to reject the form?")) {
                document.getElementById("action").value = "rejected";
                document.getElementById("regForm").submit();
            }
        }

        // Function to handle form reversion
        function revertForm() {
            // Add logic here to handle form reversion
            // For example, show a confirmation dialog and submit the form with a "reverted" action
            if (confirm("Do you want to revert the form?")) {
                document.getElementById("action").value = "reverted";
                document.getElementById("regForm").submit();
            }
        }

        // Function to validate the form fields
        function validateForm() {
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            for (i = 0; i < y.length; i++) {
                if (y[i].value === "" && y[i].required) {
                    y[i].className += " invalid";
                    valid = false;
                }
            }
            return valid;
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }
    </script>
</body>
</html>