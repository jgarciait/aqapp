//::::Sidebar Scripts::::

document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleButton');
    const sidebar = document.getElementById('sidebar');

    if (toggleButton && sidebar) {
        // Function to expand the sidebar
        function expandSidebar() {
            sidebar.classList.add('expanded');
        }

        // Function to check the viewport width and hide the sidebar if it's less than 1700px
        function checkViewportWidth() {
            if (window.innerWidth < 1700) {
                sidebar.classList.remove('expanded');
            } else {
                sidebar.classList.add('expanded');
            }
        }

        // Add an event listener to check and hide the sidebar when the viewport width changes
        window.addEventListener('resize', checkViewportWidth);

        // Check the viewport width initially
        checkViewportWidth();

        // Add the click event listener to the toggle button
        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('expanded');
        });
    }
});



/*
const expandButton = document.getElementById("expandButton");
const expandContent = document.getElementById("expandContent");

expandButton.addEventListener("click", function () {
    if (expandContent.style.display === "none") {
        expandContent.style.display = "block";
    } else {
        expandContent.style.display = "none";
    }
});
*/

/*
const expandButton1 = document.getElementById("expandButton1");
const expandContent1 = document.getElementById("expandContent1");

expandButton1.addEventListener("click", function () {
    if (expandContent1.style.display === "none") {
        expandContent1.style.display = "block";
    } else {
        expandContent1.style.display = "none";
    }
});
*/


//:::::HOME Scripts:::::

function setupFlip(tick) {
    // Function to update the Flip.js clock with the current time
    function updateClock() {
        const currentTime = new Date();
        let hours = currentTime.getHours();
        const minutes = currentTime.getMinutes();
        const seconds = currentTime.getSeconds();
        const amPm = hours >= 12 ? 'PM' : 'AM';

        // Convert hours to 12-hour format
        if (hours > 12) {
            hours -= 12;
        }

        // Update the Flip.js clock with the current time
        const timeString = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')} ${amPm}`;
        tick.value = timeString;

        // Set `aria-label` attribute for screen readers
        tick.root.setAttribute('aria-label', timeString);
    }

    // Initialize the Flip.js clock
    updateClock();

    // Set the interval to update the clock every second
    setInterval(updateClock, 1000);
}

/*
let card = document.querySelector(".card"); //declearing profile card element
let displayPicture = document.querySelector(".display-picture"); //declearing profile picture

displayPicture.addEventListener("click", function() { //on click on profile picture toggle hidden class from css
card.classList.toggle("hidden")})

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})

*/


//::::Data Table Scripts::::

$(document).ready(function () {
    const table = $('#templateTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50, 100],
    });

    const filterName = document.getElementById('filterName');
    const filterService = document.getElementById('filterService');

    if (filterName) {
        $('#filterName').change(function () {
            const selectedName = $(this).val();
            table.column(0).search(selectedName).draw();
        });
    }

    if (filterService) {
        $('#filterService').change(function () {
            const selectedName = $(this).val();
            table.column(1).search(selectedName).draw();
        });
    }

    // Rest of your code...
});


//::::Footer Script::::

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

//user_management.php scripts
document.addEventListener("DOMContentLoaded", function() {
    const addUsers = document.getElementById("openModal-1");

    if (addUsers) {
        addUsers.addEventListener('click', function () {
            $('#addUsers').modal('show'); // Open the modal
        });
    }

    const invite = document.getElementById("openModal-2");

    if (invite) {
        invite.addEventListener('click', function () {
            $('#invite').modal('show'); // Open the modal
        });
    }

    const createModule = document.getElementById("openModal-3");

    if (createModule) {
        createModule.addEventListener('click', function () {
            $('#createModule').modal('show'); // Open the modal
        });
    }

    const createProcess = document.getElementById("openModal-4");

    if (createProcess) {
        createProcess.addEventListener('click', function () {
            $('#createProcess').modal('show'); // Open the modal
        });
    }

    const addUserProcess = document.getElementById("openModal-5");

    if (addUserProcess) {
        addUserProcess.addEventListener('click', function () {
            $('#addUserProcess').modal('show'); // Open the modal
        });
    }

    const checkIn = document.getElementById("openModal-6");

    if (checkIn) {
        checkIn.addEventListener('click', function () {
            $('#checkIn').modal('show'); // Open the modal
        });
    }

    const passwordInput = document.getElementById("user_pass");
    const toggleButton = document.getElementById("togglePassword");

    if (toggleButton && passwordInput) {
        toggleButton.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        });
    }
});

function populateWCreator() {
    var selectedUserId = document.getElementById("userSelect").value;
    var selectedWorkflowId = document.getElementById("workflowSelect").value;
    var wcreatorSelect = document.getElementById("wcreatorSelect");

    // Clear previous options
    wcreatorSelect.innerHTML = '';
    
    // Make an AJAX request to fetch data
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var wcreatorData = JSON.parse(xhr.responseText);
                wcreatorData.forEach(function (item) {
                    var option = document.createElement("option");
                    option.value = item.id;
                    option.text = item.wcreator_name; // Display both id and name
                    wcreatorSelect.appendChild(option);
                });
            } else {
                console.error("Error fetching data. Status:", xhr.status);
            }
        }
    };

    xhr.open("GET", "core/transactions/transacGetWcreator.php?userId=" + selectedUserId + "&workflowId=" + selectedWorkflowId, true);
    xhr.send();
}

