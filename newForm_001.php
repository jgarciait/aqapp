<?php

include_once 'core/config/forms_setup.php';

$workflow_id = $_GET['workflow_id'];

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
        <div class="boximage"></div>
  <form style="width: 55em;" class="form-content p-3 m-2" id="regForm" action="transacCheckInMed.php" method="post">
        <input type="hidden" name="workflow_id" value="<?php echo $workflow_id; ?>">
            <div class="text-center">
                <h2>Service Template</h2>
                <p></p>
            </div>
            <hr>
            <div class="text-center">
                <ul id="progressbar">
                    <li class="active" id="personal"><strong>Step 1</strong></li>
                    <li id="vaccine"><strong>Step 2</strong></li>
                    <li id="health"><strong>Step 3</strong></li>
                    <li id="confirm"><strong>Submit</strong></li>
                </ul>
            </div>
            <!-- One "tab" for each step in the form: -->
            <div class="tab mb-3 fs-title">User Info
              <div class="row">
                  <div class="col-3 mb-3">
                      <label for="firstName">Name:<span class="required-label">*</span></label>
                      <input autocomplete="given-name" class="form-control" type="text" id="firstName" placeholder="First Name" name="firstName">
                  </div>
                  <div class="col-4 mb-3">
                      <label for="lastName">Last Name:<span class="required-label">*</span></label>
                      <input autocomplete="family-name" class="form-control" type="text" id="lastName" placeholder="Last Name" name="lastName">
                  </div>
                  <div class="col-2 mb-3">
                      <label for="age">Edad:<span class="required-label">*</span></label>
                      <select autocomplete="bday-year" class="form-select" id="age" name="age">
                          <option value="" disabled selected>---</option>
                          <option value="0-5">0-5</option>
                          <option value="5-12">5-12</option>
                          <option value="13-24">13-24</option>
                          <option value="25-36">25-36</option>
                          <option value="37-48">37-48</option>
                          <option value="49-60">49-60</option>
                          <option value="60-up">60-up</option>
                      </select>
                  </div>
                  <div class="col mb-3">
                      <label for="gender">Género:<span class="required-label">*</span></label>
                      <select autocomplete="sex" class="form-select" id="gender" name="gender">
                          <option value="" disabled selected>---</option>
                          <option value="Female">Female</option>
                          <option value="Male">Male</option>
                      </select>
                  </div>
              </div>
              <div class="mb-3">
                  <label for="physical_address">Dirección:</label>
                  <textarea autocomplete="address-line1"class="mb-3 form-control" id="physical_address" placeholder="Dirección Física" name="physical_address"></textarea>
                  <textarea autocomplete="address-line2" class="form-control" id="postal_address" placeholder="Dirección Postal" name="postal_address"></textarea>
              </div>
              <div class="mb-3">
                  <input class="form-check-input" type="checkbox" id="sameAddress" value="1">
                  <label class="form-check-label" for="sameAddress">Same as Physical Address</label>
              </div>
              <div class="row">
                <div class="col-4 mb-3">
                    <label for="sector">Sector:<span class="required-label">*</span></label>
                    <input autocomplete="address-level2" class="form-control" type="text" id="sector" placeholder="Sector" name="sector">
                </div>
                <div class="col-4 mb-3">
                    <label for="phone">Teléfono:</label>
                    <input autocomplete="tel" class="form-control" type="tel" id="phone" placeholder="Teléfono" name="phone">
                </div>
                <div class="col mb-3">
                    <label for="email">Correo Electrónico:<span class="required-label">*</span></label>
                    <input autocomplete="email" class="form-control" type="email" id="email" placeholder="user@email.com" name="email">
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
                <div class="mt-4 mb-3">
                  <label>Field 1</label>
                    <div class="mt-4 mb-3 form-check">
                      <input class="form-check-input" type="radio" name="" id="" value="si">
                      <label class="form-check-label" for="">
                        Sí
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="" id="" value="no">
                      <label class="form-check-label" for="">
                        No
                      </label>
                    </div>
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

              <div class="tab mb-3"><div class="fs-title">Summary:</div>
                <div id="summary-section">
                    <!-- User's information will be displayed here -->
                </div>
              </div>
              <hr>
              <div class="mb-3 p-2">
                <div style="float: right;">
                  <button type="button" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="prevBtn" onclick="nextPrev(-1)"></button>
                  <button type="button" class="mb-3 btn-menu btn-1 hover-filled-opacity" id="nextBtn" onclick="nextPrev(1); updateSummary();">Next</button>
              </div>
              </div>
              <!-- Circles which indicates the steps of the form: -->
              <div style="display: none; text-align:center;margin-top:40px;">
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
              </div>
            </div>
          </form>
        </div>
      </main>
   <footer id="myFooter" class="footer">
        <p>
            © 2024 All Rights Reserved - Document Control System.
        </p>
    </footer>
</body>

<!-- Summary Tab Start -->
<script>

// Function to handle tab changes
function nextPrev(n) {
    // ...
    // Check if the current tab is the "Summary" tab
    if (currentTab === tabs.length - 1) {
        updateSummary(); // Call the updateSummary function
    }
}
// JavaScript function to update the summary section
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

    var firstName = getValueById("firstName");
    var lastName = getValueById("lastName");
    var age = getValueById("age");
    var gender = getValueById("gender");
    var physical_address = getValueById("physical_address");
    var postal_address = getValueById("postal_address");
    var sector = getValueById("sector");
    var phone = getValueById("phone");
    var email = getValueById("email");

    // Create HTML to display the collected information
    var summaryHTML = "<hr>";
    summaryHTML += "<div class='row'>";
    summaryHTML += "<div class='col'>";
    summaryHTML += "<p><strong>First Name:</strong> " + firstName + "</p>";
    summaryHTML += "<p><strong>Last Name:</strong> " + lastName + "</p>";
    summaryHTML += "<p><strong>Age:</strong> " + age + "</p>";
    summaryHTML += "<p><strong>Gender:</strong> " + gender + "</p>";
    summaryHTML += "<p><strong>Physical Address:</strong> " + physical_address + "</p>";
    summaryHTML += "<p><strong>Postal Address:</strong> " + postal_address + "</p>";
    summaryHTML += "<p><strong>Sector:</strong> " + sector + "</p>";
    summaryHTML += "<p><strong>Phone:</strong> " + phone + "</p>";
    summaryHTML += "<p><strong>Email:</strong> " + email + "</p>";
    summaryHTML += "</div></div>";

    // Update the summary section
    summarySection.innerHTML = summaryHTML;
}

</script>
<!-- Summary Tab End -->
<script>

// Get references to the input elements
const physicalAddressInput = document.getElementById("physical_address");
const postalAddressInput = document.getElementById("postal_address");
const sameAddressCheckbox = document.getElementById("sameAddress");

// Add an event listener to the checkbox
sameAddressCheckbox.addEventListener("change", function () {
  if (sameAddressCheckbox.checked) {
    // If the checkbox is checked, copy the value from physical address to postal address
    postalAddressInput.value = physicalAddressInput.value;
    // Disable the postal address input
    postalAddressInput.disabled = true;
  } else {
    // If the checkbox is unchecked, clear and enable the postal address input
    postalAddressInput.value = "";
    postalAddressInput.disabled = false;
  }
});


</script>
<script>
  // JavaScript to handle the sidebar toggle functionality
  const toggleButton = document.getElementById('toggleButton');
  const sidebar = document.getElementById('sidebar');

  // Function to expand the sidebar
  function expandSidebar() {
    if (window.innerWidth <= 800) {
      sidebar.classList.remove('expanded');
    } else if (window.innerWidth >= 1700) {
      sidebar.classList.add('expanded');
    }
  }

  // Function to check the viewport width and hide the sidebar if it's less than 1700px
  function checkViewportWidth() {
    if (window.innerWidth < 1700) {
      sidebar.classList.remove('expanded');
    }
  }

  // Add an event listener to expand the sidebar when the page loads
  window.addEventListener('load', expandSidebar);

  // Add the click event listener to the toggle button
  toggleButton.addEventListener('click', () => {
    sidebar.classList.toggle('expanded');
  });

  // Add an event listener to check and hide the sidebar when the viewport width changes
  window.addEventListener('resize', () => {
    expandSidebar();
    checkViewportWidth();
  });

  // Check the viewport width initially
  checkViewportWidth();
</script>



<script>
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
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
    document.getElementById("prevBtn").innerHTML = "Previous";
  }

}

// Function to navigate to the next or previous tab
function nextPrev(n) {
  var x = document.getElementsByClassName("tab");
  if (n === 1 && !validateForm()) {
    return false;
  }
  x[currentTab].style.display = "none";
  currentTab += n;
  if (currentTab >= x.length) {
    // Display a confirmation message and submit the form
    if (confirm("Do you want to submit the form?")) {
      document.getElementById("regForm").submit();
    }
    return false;
  }
  showTab(currentTab);
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