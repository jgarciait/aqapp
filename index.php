<?php
include_once 'core/config/index_setup.php';
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Demo</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

    <!-- Index (login) app CSS Style -->
    <link rel="stylesheet" type="text/css" href="core/assets/css/indexStyle.css">

    <!-- Embeded Map for GPS Features. Need to be removed. -->
    <script type="text/javascript" src="https://www.bing.com/api/maps/mapcontrol?key=AkdPaHyrL21_U0E9Gdvkl74fuoBSnbl6QyWT6WuLUrT0COJryzTbcprcqceoP9Pc&callback=loadMapScenario" async defer></script>
</head>

<body style="background: #34354138;" class="body-container">
    <header class="header">
        <div class="header-content">
            <p style="" class="shine">AQ Platform</p>
        </div>
<!-- Navigation Bar Starts Here     
        <nav class="profile">

            <a href="#" class="display-picture"><img src="https://i.pravatar.cc/85" alt=""></a>
            <ul class="profile-menu">
                <li><a href="#">Profile</a></li>
                <li><a href="#">Account</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Log Out</a></li>
            </ul>
        </nav> 
Navigation Bar Ends Here -->
    </header>
    <div class="container  p-3">


<!-- 
   <div class="queue-container">
        <div class="ticket shadow-sm"><img src="src/ticket2.svg"></div>
    </div> 
-->
   <main class="container-login">
        <form style="width: 26rem;" class="p-5 bg-white shadow rounded" action="core/transactions/login.php" method="post"
            id="loginForm">
   

    <a href="" class="logo">
        <div style="margin-top: 0%; text-align: center;">
            <img src="core/assets/css/src/logo-dcs.png" 
            alt="" style="width: 10rem; margin: 1rem;" class="brand-img pb-2">
        </div>
    </a>
    <div class="mb-3">
        <label for="user_email">Correo electrónico:</label>
        <input class="form-control" id="user_email" autocomplete="on" type="text" placeholder="example@email.com" name="user_email" required="">
    </div>
    <div class="mb-3">
        <label for="user_pass">Contraseña:</label>
        <div class="input-group">
            <input type="password" autocomplete="on" class="form-control" id="user_pass" name="user_pass" placeholder="********">
            <button type="button" class="btn btn-outline-secondary" id="togglePassword">👁️</button>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col">
            <label style="display: inline-block;">
                <input type="checkbox" id="iamARobot" name="iamARobot" required> No soy Robot
            </label>
        </div>
        <div class="col">
            <label style="display: inline-block;">
                <input type="checkbox" id="iamNotARobot" name="iamNotARobot"> Soy Robot
            </label>
        </div>
    </div>
    <div id="robotModal" class="modal"> 
    <div class="modal-content">
        <div>
            <span class="close" id="closeRobotModal">&times;</span>
        </div>
            <i class="fa-solid fa-robot fa-xl"></i>
            <div class="mb-3 p-3">
                <p>¿Estas seguro que eres un Robot?</p>
            </div>
        </div>
    </div>
    <!-- Add a checkbox to allow the user to remember their email -->
    <div class="mb-3 my-4" style="text-align:center;" >
        <button class="button1" type="submit"  style="width: 80%; text-align:center; color: white; background-color: #308ccb; border: 1px solid #c5c5c5; border-radius: .25rem; margin-top: 2%;">
            Log in
        </button>
    </div>
    <div class="mb-3 my-4">
        <a type="button" class="btn forgot-password-link" id="openModal-1" href="#">
            <small >Olvidé Contraseña</small>
        </a>
    </div>
             <!-- Add these elements inside the form, after the login button 
            <div id="locationSection" style="display: none;">
                <h4>Your Location:</h4>
                <p id="coordinates"></p>
                <p id="address"></p>
            </div>
            <div id="mapContainer" style="height: 400px;"></div>  
            <button type="button" class="btn btn-outline" id="getLocationBtn">Get My Location</button>
            Add this div for the map -->
</form>
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <p id="modal-text"></p>
    </div>
</div>
           <div class="modal fade" id="forgotPass" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <form  class="p-2 m-1 bg-white shadow rounded" action="transacForgotPass.php" method="post">
                                <div class="mb-3">
                                    <label for="user_email">Ingrese su correo electrónico:</label>
                                </div>
                                <div class="mb-3">  
                                    <input class="form-control" placeholder="example@email.com" type="email" name="user_email" required>
                                </div>
                                <div class="mb-3 justify-content-center align-items-center">
                                    <button class="btn-menu btn-1 hover-filled-opacity" type="submit"><span>Enlace de recuperación</span></button>
                                </div>
                            </form>
                            </div>
                        
                        </div>
                    </div>
                </div>

</main>
    </div>
    <footer id="myFooter" class="footer">
    <p><img src="https://documentcontrol.com/wp-content/uploads/2023/04/logo-dcs_clipped_rev_1.png" 
            alt="Document Control System Inc." style="width: 2rem; margin: .2rem;" class="brand-img pb-1">
    © 2023 All Rights Reserved - Document Control Systems Inc.</p>
</footer>
</body>

<script src="https://code.jquery.com/jquery-3.7.0.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js" type="text/javascript"></script>


<!-- Add Bootstrap JS (Optional) -->
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<!-- Add this script block after the previous JavaScript block -->
<script>
    function loadMapScenario() {
        const getLocationBtn = document.getElementById("getLocationBtn");
        const coordinatesElement = document.getElementById("coordinates");
        const addressElement = document.getElementById("address");

        getLocationBtn.addEventListener("click", function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        const coordinates = `Latitude: ${position.coords.latitude}, Longitude: ${position.coords.longitude}`;
                        const { latitude, longitude } = position.coords;

                        // Use Bing Maps REST Services to get the address based on coordinates
                        const bingMapsApiUrl = `https://dev.virtualearth.net/REST/v1/Locations/${latitude},${longitude}?o=json&key=AkdPaHyrL21_U0E9Gdvkl74fuoBSnbl6QyWT6WuLUrT0COJryzTbcprcqceoP9Pc`;

                        fetch(bingMapsApiUrl)
                            .then((response) => response.json())
                            .then((data) => {
                                if (data.resourceSets && data.resourceSets.length > 0) {
                                    const address = data.resourceSets[0].resources[0].address.formattedAddress;
                                    coordinatesElement.textContent = coordinates;
                                    addressElement.textContent = `Address: ${address}`;

                                    // Show the location information section
                                    document.getElementById("locationSection").style.display = "block";

                                    // Initialize and show the map
                                    const map = new Microsoft.Maps.Map('#mapContainer', {
                                        credentials: 'AkdPaHyrL21_U0E9Gdvkl74fuoBSnbl6QyWT6WuLUrT0COJryzTbcprcqceoP9Pc',
                                        center: new Microsoft.Maps.Location(latitude, longitude),
                                        zoom: 15,
                                    });

                                    // Add a pushpin (marker) at the user's location
                                    const pushpin = new Microsoft.Maps.Pushpin(map.getCenter(), {
                                        title: 'Your Location',
                                    });

                                    map.entities.push(pushpin);
                                } else {
                                    alert("Unable to retrieve address information.");
                                }
                            })
                            .catch((error) => {
                                console.error("Error fetching Bing Maps data:", error);
                                alert("Error fetching Bing Maps data. Please try again.");
                            });
                    },
                    function (error) {
                        console.error("Error getting location:", error);
                        alert("Error getting location. Please try again.");
                    }
                );
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        });
    }
</script>


<!-- Login Part -->

<!-- Additional JavaScript code -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById("user_pass");
        const toggleButton = document.getElementById("togglePassword");

        toggleButton.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        });
    });
</script>

<script>
    // Function to trigger translation to English
    function translateToEnglish() {
        // Check if the Google Translate widget is available
        if (typeof google !== 'undefined' && google.translate) {
            // Translate the page to English
            google.translate.translate({ source: 'es', target: 'en' }, function(result) {
                if (result.translation) {
                    // Replace the content of the page with the translated text
                    document.body.innerHTML = result.translation;
                }
            });
        }
    }
</script>
<script>
    <?php
    
    if (isset($_SESSION['modalMessage'])) {
        $modalMessage = $_SESSION['modalMessage'];
        echo "document.getElementById('modal-text').textContent = '$modalMessage';";
        echo "document.getElementById('myModal').style.display = 'block';";
        unset($_SESSION['modalMessage']); // Clear the session variable
    }
    ?>

    // Add event listener to show the captcha question

     const humanValidationCheckbox = document.getElementById('iamARobot');
        humanValidationCheckbox.addEventListener('change', function() {
            if (humanValidationCheckbox.checked) {
                // Checkbox is checked, enable form submission
         
            } else {
                // Checkbox is unchecked, you can show a message if required
                // Prevent form submission here or display a message to check the checkbox
            }

    // Close the modal and hide the captcha question
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('myModal').style.display = 'none';
        document.getElementById('captcha-question').style.display = 'none';
    });
    });
   
   

    // Close the modal and hide the captcha question when clicking outside the modal
    window.addEventListener('click', function(event) {
        if (event.target == document.getElementById('myModal')) {
            document.getElementById('myModal').style.display = 'none';
            document.getElementById('captcha-question').style.display = 'none';
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const soyRobotCheckbox = document.getElementById("humanValidationCheckbox2");
        const robotModal = document.getElementById("robotModal");
        const closeRobotModal = document.getElementById("closeRobotModal");

        // Event listener to show the robot modal when the checkbox is checked
        soyRobotCheckbox.addEventListener("change", function () {
            if (soyRobotCheckbox.checked) {
                robotModal.style.display = "block";
            }
        });

         loginForm.addEventListener("submit", function (event) {
            if (soyRobotCheckbox.checked) {
                // The "Soy Robot" checkbox is checked, prevent form submission
                event.preventDefault();
                  // You can also display a message to the user
                alert("Confirme que no es un Robot.");
         }});

        // Event listener to close the robot modal
        closeRobotModal.addEventListener("click", function () {
            robotModal.style.display = "none";
        });
    });
</script>
<script>
    // JavaScript to handle the sidebar toggle functionality
    const toggleButton = document.getElementById('toggleButton');
    const sidebar = document.getElementById('sidebar');

    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('expanded');
    });
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
// Form Modals
    document.getElementById('openModal-1').addEventListener('click', function () {
        $('#forgotPass').modal('show'); // Open the modal
    });
</script>
</html>
