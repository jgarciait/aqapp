//::::Sidebar Scripts::::

document.addEventListener('DOMContentLoaded', function () {
    // JavaScript to handle the sidebar toggle functionality
    const toggleButton = document.getElementById('toggleButton');
    const sidebar = document.getElementById('sidebar');

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
    
    // Function to check the viewport width and expand the sidebar if it's more than 1700px

    // Add an event listener to check and hide the sidebar when the viewport width changes
    window.addEventListener('resize', checkViewportWidth);

    // Check the viewport width initially
    checkViewportWidth();

    // Add the click event listener to the toggle button
    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('expanded');
    });
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

const checkIn = document.getElementById("openModal-1");
    checkIn.addEventListener('click', function () {
        $('#checkIn').modal('show'); // Open the modal
});
*/

//::::Data Table Scripts::::

$(document).ready(function () {
    const table = $('#templateTable').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 100],
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
    addUsers.addEventListener('click', function () {
        $('#addUsers').modal('show'); // Open the modal
    });

    const invite = document.getElementById("openModal-2");
    invite.addEventListener('click', function () {
        $('#invite').modal('show'); // Open the modal
    });
});
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