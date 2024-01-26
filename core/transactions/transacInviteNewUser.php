<?php
// invite-user.php
session_start();

// Check if the user is not logged in and redirect to the login page
if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
     echo "<script type=\"text/javascript\">
            alert('Para continuar debes iniciar sesión.');
            window.location.href = 'index.php';
        </script>";
        exit();

}
include('connection.php');
include('functions.php');

// Handle form submission to send invitation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['user_email'];
    $userName = $_POST['user_name'];
    $token = generateToken(); // Generate a unique token for the user

    // Create the registration link with the token
    $registrationLink = "https://www.uccpr.app/aqregistration.php?token=" . $token;

    // Send an email to the user with the registration link
    $subject = "Invitation to Register";
    
    // Create an HTML message with the registration link as a hyperlink
    $message = '<html ><body>';
    $message .= '<p>Hola '. htmlentities($userName, ENT_QUOTES, 'UTF-8') .'!</p>';
    $message .= '<p>Usted fue invitado a registrarse en AQPlatform.</p>';
    $message .= '<a href="' . $registrationLink . '">Register</a>';
    $message .= '</body></html>';

    // Additional headers for HTML email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    // Insert the token into the reset_tokens table
    if (insertResetTokenForNewUser($email, $token, $db)) {
        // Token inserted successfully
        if (sendEmail($email, $subject, $message, $headers)) {
            // Email sent successfully
            echo "Invitation sent successfully.";
        } else {
            // Email sending failed
            echo "Error sending invitation.";
        }
        ?>
        <script type="text/javascript">
        alert("Invitación enviada");
        window.location = "home.php";
        </script>
    <?php
    } else {
        // Token insertion failed
        echo "Error sending invitation. Please try again later.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="css/indexStyle.css">  
</head>
<body>
    <header class="header">
        <div class="header-content">
            <p style="" class="shine">AQ Platform</p>
        </div>
        <nav class="profile"><!-- Navigation Bar Starts Here -->
            <a href="#" class="display-picture">
                <span class="profile-text"><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></span>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Windows_10_Default_Profile_Picture.svg/64px-Windows_10_Default_Profile_Picture.svg.png" alt="Profile Image">
            </a>
            <ul class="shadow border profile-menu"><!-- Profile Menu -->
                <li><a href="profile.php">Profile</a></li>
                <li><a href="#">Account</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav><!-- Navigation Bar Ends Here -->
    </header>
    <div>
        <aside class="sidebar" id="sidebar">
            <div class="toggle-button-container">
                <button type="button" class="icon-sm" id="toggleButton"><i class="fa fa-circle-dot"></i></button>
            </div>
            <nav>
                 <ul>
                    <li class="has-subnav">
                        <a href="home.php">
                            <i class="fa fa-home fa-sm"></i>
                            <span class="nav-text">
                                Inicio
                            </span>
                        </a>
                    </li>
                    <li class="has-subnav">
                        <a href="workflowsList.php">
                            <i class="fa fa-gear fa-2x"></i>
                            <span class="nav-text">
                                Módulos
                            </span>
                        </a>
                    </li>
                    <li class="has-subnav">
                        <a href="logs.php">
                            <i class="fa fa-clock-rotate-left fa-2x"></i>
                            <span class="nav-text">
                                Logs
                            </span>
                        </a>
                    </li>
                     <li class="has-subnav">
                        <a href="audit_trail.php">
                            <i class="fa fa-user-secret fa-2x"></i>
                            <span class="nav-text">
                                Audit Trail
                            </span>
                        </a>
                    </li>
                        <li class="has-subnav">
                        <a href="usersAccount.php">
                            <i class="fa fa-users fa-2x"></i>
                            <span class="nav-text">
                                Usuarios
                            </span>
                        </a>
                    </li>
                    <ul class="logout">
                        <li>
                            <a href="logout.php">
                                <i style="color:Tomato;"  class="fa fa-power-off fa-2x"></i>
                                <span  style="font-weight: bold; color:Tomato;" class="nav-text">
                                Salir
                                </span>
                            </a>
                        </li>
                    </ul>
                </ul>
            </nav>
        </aside>
    </div>

        <main class="container-login">
            <form style="width: 26rem;" class="p-5 m-2 bg-white shadow rounded"
                action="invite-user.php"
                method="post"
                enctype="multipart/form-data">
           
        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" class="form-control" name="user_name" required>
        </div>
        <!-- Input field for user's email -->
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" class="form-control" name="user_email" required>
        </div>
        <!-- Submit button to send invitation -->
         <button type="submit" class="btn-menu btn-1 hover-filled-opacity"><span>Enviar Invitación</span></button>
            </form>
        </main>

    <footer id="myFooter" class="footer">
    <p>Document Control Systems Inc.</p>
</footer>
</body>

<script src="https://code.jquery.com/jquery-3.7.0.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js" type="text/javascript"></script>


<!-- Add Bootstrap JS (Optional) -->
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

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

</html>
