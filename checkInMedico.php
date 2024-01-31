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
  background-image: url(core/assets/css/src/canovanassaludable.jpg);
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
    content: "\f21e";
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
    background: #b0b9ff;
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
    background: #b0b9ff;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: 0; /* Increase this z-index value */
}


/*Color number of the step and the connector before it*/
#progressbar li.active:before, #progressbar li.active:after {
    background: #545c9b;
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
                <h2>Registro de Pacientes Canóvanas Saludable</h2>
                <p>Este formulario está creado para recopilar información del Programa Canóvanas Saludable</p>
            </div>
            <hr>
            <div class="text-center">
                <ul id="progressbar">
                    <li class="active" id="personal"><strong>Info Paciente</strong></li>
                    <li id="vaccine"><strong>Vacunación</strong></li>
                    <li id="health"><strong>Signos Vitales</strong></li>
                    <li id="confirm"><strong>Finalizar</strong></li>
                </ul>
            </div>
            <!-- One "tab" for each step in the form: -->
            <div class="tab mb-3 fs-title">Información del Paciente:
              <div class="row">
                  <div class="col-3 mb-3">
                      <label for="cim_fname">Nombre:<span class="required-label">*</span></label>
                      <input autocomplete="given-name" class="form-control" type="text" id="cim_fname" placeholder="Nombre" name="cim_fname">
                  </div>
                  <div class="col-4 mb-3">
                      <label for="cim_lname">Apellidos:<span class="required-label">*</span></label>
                      <input autocomplete="family-name" class="form-control" type="text" id="cim_lname" placeholder="Apellidos" name="cim_lname">
                  </div>
                  <div class="col-2 mb-3">
                      <label for="cim_age">Edad:<span class="required-label">*</span></label>
                      <select autocomplete="bday-year" class="form-select" id="cim_age" name="cim_age">
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
                      <label for="cim_gender">Género:<span class="required-label">*</span></label>
                      <select autocomplete="sex" class="form-select" id="cim_gender" name="cim_gender">
                          <option value="" disabled selected>---</option>
                          <option value="Femenino">Femenino</option>
                          <option value="Masculino">Masculino</option>
                          <option value="Prefiero no decirlo">Prefiero no decirlo</option>
                      </select>
                  </div>
              </div>
              <div class="mb-3">
                  <label for="cim_physicalAddress">Dirección:</label>
                  <textarea autocomplete="address-line1"class="mb-3 form-control" id="cim_physicalAddress" placeholder="Dirección Física" name="cim_physicalAddress"></textarea>
                  <textarea autocomplete="address-line2" class="form-control" id="cim_postalAddress" placeholder="Dirección Postal" name="cim_postalAddress"></textarea>
              </div>
              <div class="mb-3">
                  <input class="form-check-input" type="checkbox" id="sameAddress" value="1">
                  <label class="form-check-label" for="sameAddress">Dirección Postal es la misma que la Dirección Física</label>
              </div>
              <div class="row">
                <div class="col-4 mb-3">
                    <label for="cim_sector">Sector:<span class="required-label">*</span></label>
                    <input autocomplete="address-level2" class="form-control" type="text" id="cim_sector" placeholder="Sector" name="cim_sector">
                </div>
                <div class="col-4 mb-3">
                    <label for="cim_phone">Teléfono:</label>
                    <input autocomplete="tel" class="form-control" type="tel" id="cim_phone" placeholder="Teléfono" name="cim_phone">
                </div>
                <div class="col mb-3">
                    <label for="cim_email">Correo Electrónico:<span class="required-label">*</span></label>
                    <input autocomplete="email" class="form-control" type="email" id="cim_email" placeholder="user@email.com" name="cim_email">
                </div>
              </div>
              <div class="form-group row" id="cim_conditions">
                <label>Condiciones de Salud</label>
                <div class="col">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Depresión">
                    <label class="form-check-label" for="cim_conditions">Depresión</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Hipertensión">
                    <label class="form-check-label" for="cim_conditions">Hipertensión</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Diabetes">
                    <label class="form-check-label" for="cim_conditions">Diabetes</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Hipotiroidismo">
                    <label class="form-check-label" for="cim_conditions">Hipotiroidismo</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Artritis">
                    <label class="form-check-label" for="cim_conditions">Artritis</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Hipercolesterolemia">
                    <label class="form-check-label" for="cim_conditions">Hipercolesterolemia</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Asma">
                    <label class="form-check-label" for="cim_conditions">Asma</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Condición Renal">
                    <label class="form-check-label" for="cim_conditions">Condición renal</label>
                  </div>
                </div>
                <div class="col">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="ArritmiaCardiaca">
                    <label class="form-check-label" for="cim_conditions">Arritmia Cardiaca</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions"" name="cim_conditions[]" value="ApneaSueno"">
                    <label class="form-check-label" for="cim_conditions"">Apnea del Sueño</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Anemia">
                    <label class="form-check-label" for="cim_conditions">Anemia</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="COPD">
                    <label class="form-check-label" for="cim_conditions">COPD</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="VIH">
                    <label class="form-check-label" for="cim_conditions">VIH</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cim_conditions" name="cim_conditions[]" value="Neuropatia">
                    <label class="form-check-label" for="cim_conditions">Neuropatía</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="otherConditions" name="cim_conditions[]" value="Otros">
                    <label class="form-check-label" for="cim_conditions">Otros</label>
                  </div>
                </div>
              </div>      
              <div class="form-group mb-3" id="otherConditionGroup" style="display: none;">
                <hr>
                <label for="cim_otherConditions">Otra Condición</label>
                <input class="form-control" type="text" id="cim_otherConditions" name="cim_otherConditions">
              </div>
              <label class="mb-3 mt-4">¿Cómo se siente de salud al día de hoy? <span class="required-label">*</span></label>
              <div class="scale-container">
                  <div class="scale-label">
                      <label class="mt-4" for="bad">Desmejorada</label>
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="1">1</label>
                      <input type="radio" id="1" name="cim_h_feeling" value="1">
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="2">2</label>
                      <input type="radio" id="2" name="cim_h_feeling" value="2">
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="3">3</label>
                      <input type="radio" id="3" name="cim_h_feeling" value="3">
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="4">4</label>
                      <input type="radio" id="4" name="cim_h_feeling" value="4">
                  </div>
                  <div class="scale-label">
                      <label class="mb-2" for="5">5</label>
                      <input type="radio" id="5" name="cim_h_feeling" value="5">
                  </div>
                  <div class="scale-label">
                      <label class="mt-4" for="excellent">Bien</label>
                  </div>
              </div>
              </div>
              <div class="tab mb-3 fs-title">Vacunación:
                <div class="mt-4 mb-3">
                  <label>Covid 19</label>
                    <div class="mt-4 mb-3 form-check">
                      <input class="form-check-input" type="radio" name="cim_covidVacc" id="cim_covidVacc" value="si">
                      <label class="form-check-label" for="cim_covidVacc">
                        Sí
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="cim_covidVacc" id="cim_covidVacc" value="no">
                      <label class="form-check-label" for="cim_covidVacc">
                        No
                      </label>
                    </div>
                </div>
                <label class="mb-3 mt-4">Vacunas:</label>
                <div class="scale-container">
                    <div class="scale-label">
                        <label class="mt-4" for="bad">Covid 19</label>
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="1">1ra Dosis</label>
                        <input type="radio" id="cim_covidVaccDose" name="cim_covidVaccDose" value="1ra Dosis">
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="2">2da Dosis</label>
                        <input type="radio" id="cim_covidVaccDose" name="cim_covidVaccDose" value="2da Dosis">
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="3">3ra Dosis</label>
                        <input type="radio" id="3" name="cim_covidVaccDose" value="3ra Dosis">
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="4">Refuerzo (4)</label>
                        <input type="radio" id="4" name="cim_covidVaccDose" value="Refuerzo (4)">
                    </div>
                    <div class="scale-label">
                        <label class="mb-2" for="5">Refuerzo (5)</label>
                        <input type="radio" id="5" name="cim_covidVaccDose" value="Refuerzo (5)">
                    </div>
                </div>
                <div class="mt-4 mb-3">
                  <label>Influenza</label>
                    <div class="mt-4 mb-3 form-check">
                      <input class="form-check-input" type="radio" name="cim_influenzaVacc" id="cim_influenzaVacc" value="si">
                      <label class="form-check-label" for="cim_influenzaVacc">
                        Sí
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="cim_influenzaVacc" id="cim_influenzaVacc" value="no">
                      <label class="form-check-label" for="cim_influenzaVacc">
                        No
                      </label>
                    </div>
                </div>
                <div class="mt-4 mb-3">
                  <label>Culebrilla</label>
                    <div class="mt-4 mb-3 form-check">
                      <input class="form-check-input" type="radio" name="cim_culebVacc" id="cim_culebVacc" value="si">
                      <label class="form-check-label" for="cim_culebVacc">
                        Sí
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="cim_culebVacc" id="cim_culebVacc" value="no" >
                      <label class="form-check-label" for="cim_culebVacc">
                        No
                      </label>
                    </div>
                </div>
              </div>
              
              <div class="tab mb-3 fs-title">Signos vitales:
              <div class="row">
              <div class="col mb-3">
                <label for="systolic">Sistólica:</label>
                <input class="mb-3 form-control text-center" type="number" id="systolicText" name="cim_systolic" value="120">
                <input class="form-control" type="range" id="cim_systolic" min="70" max="200" step="1">
              </div>
                <div class="col mb-3">
                  <label for="cim_diastolic">Diastólica:</label>
                  <input class="mb-3 form-control text-center" type="number" id="diastolicText"  name="cim_diastolic" value="80">
                  <input class="form-control" type="range" id="cim_diastolic" min="40" max="130" step="1">
                </div>
                 <div class="col mb-3">
                  <label for="cim_pulse">Frecuencia Cardiaca:</label>
                  <input class="mb-3 form-control text-center" type="number" id="pulseText" name="cim_pulse" value="90">
                  <input class="form-control" type="range" id="cim_pulse"  min="60" max="160" step="1">
                </div>
              </div>
              <div class="row">
                <div class="col mb-3">
                  <label for="cim_bloodSugar">Nivel de Azúcar en Sangre:</label>
                  <input class="mb-3 form-control text-center" type="number" id="bloodSugarText" name="cim_bloodSugar" value="120">
                  <input class="form-control" type="range" id="cim_bloodSugar"  min="50" max="500" step="1">
                </div>
                <div class="col mb-3">
                  <label for="bodyTemp">Temperatura Corporal:</label>
                  <input class="mb-3 form-control text-center" type="number" id="bodyTempText" name="cim_bodyTemp" value="97">
                  <input class="form-control" type="range" id="bodyTemp"  min="95" max="105" step="0.1">
                </div>
               <div class="col mb-3">
                  <label for="cim_weight">Peso:</label>
                  <input class="mb-3 form-control text-center" type="number" id="weightText" name="cim_weight" value="120">
                  <input class="form-control" type="range" id="cim_weight"  min="50" max="500" step="1">
                </div>
              </div>
            </div>
              <div class="tab mb-3"><div class="fs-title">Resumen:</div>
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
            © 2024 All Rights Reserved - Ricoh.
        </p>
    </footer>
</body>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>


<!-- Add Bootstrap JS (Optional) -->
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
    var chartContainer = document.getElementById('chart-container');
    var fullscreenButton = document.getElementById('fullscreen-button');

    // Function to toggle fullscreen mode
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            chartContainer.requestFullscreen()
                .then(() => {
                    // Chart container is now in fullscreen
                })
                .catch((err) => {
                    console.error('Error entering fullscreen mode:', err);
                });
        } else {
            document.exitFullscreen()
                .then(() => {
                    // Chart container exited fullscreen
                })
                .catch((err) => {
                    console.error('Error exiting fullscreen mode:', err);
                });
        }
    }

    // Add a click event listener to the fullscreen button
    fullscreenButton.addEventListener('click', toggleFullscreen);
</script>
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

    var cim_fname = getValueById("cim_fname");
    var cim_lname = getValueById("cim_lname");
    var cim_age = getValueById("cim_age");
    var cim_gender = getValueById("cim_gender");
    var cim_physicalAddress = getValueById("cim_physicalAddress");
    var cim_postalAddress = getValueById("cim_postalAddress");
    var cim_sector = getValueById("cim_sector");
    var cim_phone = getValueById("cim_phone");
    var cim_email = getValueById("cim_email");

    // Create HTML to display the collected information
    var summaryHTML = "<hr>";
    summaryHTML += "<div class='row'>";
    summaryHTML += "<div class='col'>";
    summaryHTML += "<p><strong>Nombre:</strong> " + cim_fname + "</p>";
    summaryHTML += "<p><strong>Apellidos:</strong> " + cim_lname + "</p>";
    summaryHTML += "<p><strong>Edad:</strong> " + cim_age + "</p>";
    summaryHTML += "<p><strong>Género:</strong> " + cim_gender + "</p>";
    summaryHTML += "<p><strong>Dirección Física:</strong> " + cim_physicalAddress + "</p>";
    summaryHTML += "<p><strong>Dirección Postal:</strong> " + cim_postalAddress + "</p>";
    summaryHTML += "<p><strong>Sector:</strong> " + cim_sector + "</p>";
    summaryHTML += "<p><strong>Teléfono:</strong> " + cim_phone + "</p>";
    summaryHTML += "<p><strong>Correo Electrónico:</strong> " + cim_email + "</p>";
    summaryHTML += "<p><strong>Condiciones de Salud:</strong> ";

    // Get the checkboxes for health conditions
    var cim_conditions = document.getElementById("cim_conditions").querySelectorAll("input[type=checkbox]:checked");
    var selectedHealthConditions = Array.from(cim_conditions).map((checkbox) => checkbox.value);

    // Add selected health conditions to the summary
    summaryHTML += selectedHealthConditions.join(", ");

    // Check if "Otra Condición" input field has a value and include it in the summary
    var otherConditions = document.getElementById("otherConditions").value.trim();
    if (otherConditions !== "") {
        summaryHTML += "<p><strong>Otra/s Condición/es:</strong> " + otherConditions + "</p>";
    }

    summaryHTML += "</div><div class='col'>";
    
    // Check if a radio button is selected and include it in the summary
    var selectedHealthFeeling = getCheckedRadioValue("cim_h_feeling");
    if (selectedHealthFeeling) {
        summaryHTML += "<p><strong>¿Cómo se siente de salud al día de hoy?: </strong> " + selectedHealthFeeling + "</p>";
    }

    // Collect values from vaccination fields
    var cim_covidVacc = getCheckedRadioValue("cim_covidVacc");
    if (cim_covidVacc) {
        summaryHTML += "<p><strong>Vacunación Covid 19:</strong> " + cim_covidVacc + "</p>";
    }

    // Collect values from radio buttons for Covid 19 doses
    var cim_covidVaccDose = getCheckedRadioValue("cim_covidVaccDose");
    if (cim_covidVaccDose) {
        summaryHTML += "<p><strong>Covid 19 Dosis:</strong> " + cim_covidVaccDose + "</p>";
    }

    // Collect values from radio buttons for Influenza
    var cim_influenzaVacc = getCheckedRadioValue("cim_influenzaVacc");
    if (cim_influenzaVacc) {
        summaryHTML += "<p><strong>Influenza:</strong> " + cim_influenzaVacc + "</p>";
    }

    // Collect values from radio buttons for Culebrilla
    var cim_culebVacc = getCheckedRadioValue("cim_culebVacc");
    if (cim_culebVacc) {
        summaryHTML += "<p><strong>Culebrilla:</strong> " + cim_culebVacc + "</p>";
    }

    var cim_systolic = getValueById("systolicText");
    var cim_diastolic = getValueById("diastolicText");
    var cim_pulse = getValueById("pulseText");
    var cim_bloodSugar = getValueById("bloodSugarText");
    var cim_weight = getValueById("weightText");
    var cim_bodyTemp = getValueById("bodyTempText");
  

    summaryHTML += "<p><strong>Sistólica:</strong> " + cim_systolic + "</p>";
    summaryHTML += "<p><strong>Diastólica:</strong> " + cim_diastolic + "</p>";
    summaryHTML += "<p><strong>Frecuencia Cardiaca:</strong> " + cim_pulse + "</p>";
    summaryHTML += "<p><strong>Nivel de Azúcar en Sangre:</strong> " + cim_bloodSugar + "</p>";
    summaryHTML += "<p><strong>Temperatura Corporal:</strong> " + cim_bodyTemp + "</p>";
    summaryHTML += "<p><strong>Peso:</strong> " + cim_weight + "</p>";

    summaryHTML += "</div></div>";

    // Update the summary section
    summarySection.innerHTML = summaryHTML;
}

</script>
<!-- Summary Tab End -->


<script>
  const systolicText = document.getElementById("systolicText");
  const systolicRange = document.getElementById("cim_systolic");
  const diastolicText = document.getElementById("diastolicText");
  const diastolicRange = document.getElementById("cim_diastolic");
  const pulseText = document.getElementById("pulseText");
  const pulseRange = document.getElementById("cim_pulse");
  const bloodSugarText = document.getElementById("bloodSugarText");
  const bloodSugarRange = document.getElementById("cim_bloodSugar");
  const weightText = document.getElementById("weightText");
  const weightRange = document.getElementById("cim_weight");
  const bodyTempText = document.getElementById("bodyTempText");
  const bodyTempRange = document.getElementById("cim_bodyTemp");

// Systolic
  systolicText.addEventListener("input", () => {
    const textValue = parseFloat(systolicText.value);
    if (!isNaN(textValue) && textValue >= 70 && textValue <= 200) {
      systolicRange.value = textValue;
    }
  });

  systolicRange.addEventListener("input", () => {
    systolicText.value = systolicRange.value;
  });

// Diastolic
  diastolicText.addEventListener("input", () => {
    const textValue = parseFloat(diastolicText.value);
    if (!isNaN(textValue) && textValue >= 40 && textValue <= 130) {
      diastolicRange.value = textValue;
    }
  });

  diastolicRange.addEventListener("input", () => {
    diastolicText.value = diastolicRange.value;
  });

// Pulse
  pulseText.addEventListener("input", () => {
    const textValue = parseFloat(pulseText.value);
    if (!isNaN(textValue) && textValue >= 60 && textValue <= 160) {
      pulseRange.value = textValue;
    }
  });

  pulseRange.addEventListener("input", () => {
    pulseText.value = pulseRange.value;
  });

// Blood Sugar

  bloodSugarText.addEventListener("input", () => {
    const textValue = parseFloat(bloodSugarText.value);
    if (!isNaN(textValue) && textValue >= 50 && textValue <= 500) {
      bloodSugarRange.value = textValue;
    }
  });

  bloodSugarRange.addEventListener("input", () => {
    bloodSugarText.value = bloodSugarRange.value;
  });

// Weight
 
   weightText.addEventListener("input", () => {
    const textValue = parseFloat(weightText.value);
    if (!isNaN(textValue) && textValue >= 40 && textValue <= 130) {
      weightRange.value = textValue;
    }
  });

  weightRange.addEventListener("input", () => {
    weightText.value = weightRange.value;
  });

// Body Temp
  bodyTempText.addEventListener("input", () => {
    const textValue = parseFloat(bodyTempText.value);
    if (!isNaN(textValue) && textValue >= 95 && textValue <= 105) {
      bodyTempRange.value = textValue;
    }
  });

  bodyTempRange.addEventListener("input", () => {
    bodyTempText.value = bodyTempRange.value;
  });
</script>
<script>
  const textarea = document.getElementById("cim_physicalAddress");
  
  textarea.addEventListener("input", function () {
    const maxLength = 135;
    if (textarea.value.length > maxLength) {
      textarea.value = textarea.value.slice(0, maxLength); // Truncate the text to 100 characters
    }
  });
</script>
<script>
  // Get references to the checkbox elements
  const otherCheckbox = document.getElementById("otherConditions");
  const otherConditionGroup = document.getElementById("otherConditionGroup");

  // Add an event listener to the "Other" checkbox
  otherCheckbox.addEventListener("change", function () {
    // Check if the "Other" checkbox is checked
    if (otherCheckbox.checked) {
      // If checked, show the "otherConditionGroup" input
      otherConditionGroup.style.display = "block";
    } else {
      // If unchecked, hide the "otherConditionGroup" input and clear its value
      otherConditionGroup.style.display = "none";
      document.getElementById("cim_otherConditions").value = "";
    }
  });
</script>

<script>

// Get references to the input elements
const physicalAddressInput = document.getElementById("cim_physicalAddress");
const postalAddressInput = document.getElementById("cim_postalAddress");
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
    $(document).ready(function () {
        const table = $('#templateTable').DataTable({
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50, 100],
             "language": {
            "search": "Buscar: "
            // Add other language customizations here
        }
        });

        // Listen for changes in the dropdown
        $('#filterName').change(function () {
            const selectedName = $(this).val();
            table.column(0).search(selectedName).draw();
        });
          // Listen for changes in the dropdown
        $('#filterService').change(function () {
            const selectedName = $(this).val();
            table.column(1).search(selectedName).draw();
        });

    });
</script>
<script>
let card = document.querySelector(".card"); //declearing profile card element
let displayPicture = document.querySelector(".display-picture"); //declearing profile picture

displayPicture.addEventListener("click", function() { //on click on profile picture toggle hidden class from css
card.classList.toggle("hidden")})
</script>
<script>
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})
</script>
<script>
  // Store the current scroll position
  let lastScrollPosition = 0;

  // Get a reference to the footer
  const footer = document.getElementById('myFooter');

  // Function to check scroll direction and hide/show the footer
  function handleScroll() {
    const currentScrollPosition = window.scrollY;

    if (currentScrollPosition > lastScrollPosition) {
      // Scrolling down, hide the footer
      footer.style.transform = 'translateY(100%)';
    } else {
      // Scrolling up, show the footer
      footer.style.transform = 'translateY(0)';
    }

    lastScrollPosition = currentScrollPosition;
  }

  // Add a scroll event listener to the window
  window.addEventListener('scroll', handleScroll);
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
    document.getElementById("nextBtn").innerHTML = "Enviar";
  } else {
    document.getElementById("nextBtn").innerHTML = "Siguiente";
    document.getElementById("prevBtn").innerHTML = "Anterior";
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