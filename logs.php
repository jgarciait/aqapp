<?php
session_start(); // Make sure to start the session

// Check if user is not authenticated
if (!isset($_SESSION['id']) || !isset($_SESSION['first_name'])) {
    header("Location: index.php");
    exit;
}

include "connection.php";
include 'functions.php';

$session_user = $_SESSION['id'];
$user_data = getSysRol($session_user, $db);

if ($user_data['sys_group_name'] === 'admin') {
        $isAdmin = true;
}


if ($isAdmin) {
    // Admin-specific content here
} else {
    // User is not an admin, so end the session and redirect to index.php
    session_destroy();
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/homeStyle.css" />
    
</head>
<body>
    <header class="header">
        <div class="header-content">
            <p style="" class="shine">AQ Platform</p>
        </div>
        <nav class="profile"><!-- Navigation Bar Starts Here -->
                <span class="profile-text"><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></span>
                <img class="display-picture" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Windows_10_Default_Profile_Picture.svg/64px-Windows_10_Default_Profile_Picture.svg.png" alt="Profile Image">
            <ul class="shadow border profile-menu"><!-- Profile Menu -->
                <li><a href="profile.php">Profile</a></li>
                <li><a href="#">Account</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav><!-- Navigation Bar Ends Here -->
    </header>
    <div class="container">
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
     
    <div class="container container-table">
        <main class="container-fluid my-3 p-5 border border-info content-table bg-white shadow rounded table-responsive">
            <div class="container">
            <a class="title-table"><span>Logs</span></a>
            </div>    
                <div class="container-fluid p-3 mr-3 ">        
                    <table style="width: 100%; padding: 3rem;" id="templateTable" class="table table-bordered table-condensed table-hover">
                       <thead>
                            <tr>
                                    <th>#</th>
                                    <th>Nombre y Apellido</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Log In / Log Out</th>
                                    <th>Fecha y Hora</th>          
                                </tr>
                            </thead>
                            <tbody>
        <?php
            mysqli_set_charset($db, "utf8");
            $sql = "SELECT * 
            FROM logs
            INNER JOIN users ON users.id = logs.logs_user_id
            ORDER BY logs_timestamp DESC
            "; 
            $result = mysqli_query($db, $sql); // Execute the query

            $count = 1;
        
            if ($result) {
            
                while ($row = mysqli_fetch_assoc($result)) {
                    // Your table rows here...
                     $timestamp = date('F j, Y h:i A', strtotime($row['logs_timestamp']));
                    ?>
                    <tr>
                        <td data-title='#'><?php echo $count; ?></td>
                        <td data-title='Nombre y Apellido'><?php echo $row['first_name'] . " " . $row['last_name']?></td>
                        <td data-title='Nombre de Usuario'><?php echo $row['user_email']?></td>
                        <td data-title='Log In / Log Out'><?php echo $row['logs_action']?></td>
                        <td data-title='Fecha y Hora'><?php echo $timestamp; ?></td>                  
                    </tr>
                    <?php
                    $count++;
                }
                mysqli_free_result($result); // Free the result set
            } else {
                echo "Error executing query: " . mysqli_error($db);
            }
            
            ?>
            <!-- Display the workflowName as the heading -->
    </tbody>
                        
                    </table>
                </div>
        </main>
    </div>
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
            "lengthMenu": [5, 10, 25, 50, 100, 500, 1000],
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
