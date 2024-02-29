function openTab(tabName) {
    var i, tabcontent;
    tabcontent = document.getElementsByClassName("tab");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    document.getElementById(tabName).style.display = "block";
}


// Notification Scripts

$(document).ready(function () {
    // Initially hide the notifications area
    $('#notifications').hide();

    // Call fetchNotifications at regular intervals
    fetchNotifications();
    setInterval(fetchNotifications, 3000);

    // Function to fetch notifications
    function fetchNotifications() {
        $.ajax({
            type: 'POST',
            url: 'fetchNotifications.php', // This script should return notifications related to forms
            data: {
                key: '123' // Key to send to the server
            },
            cache: false,
            success: function (data) {
                let contentData = data; // Assuming this is already parsed JSON
                $('#notifications').html('');
                if (contentData.length > 1) {
                    // Update notification count
                    $('#nf-n').text(contentData[0].total);
                    $('#nf-n').show(); 
                    // Populate notifications
                    for (let i = 1; i < contentData.length; i++) {
                        const element = contentData[i];
                        $('#notifications').append(`
                            <a style="color:white;" href="senderDataTable.php?workflow_id=${element.workflow_id}" class="notification-link">
                                <div class="notification-item">
                                    Request ${element.ref_number} was ${element.actions}
                                    <button style="color:white; font-size: 14px;" class="btn btn-sm mark-as-seen" data-id="${element.audit_trail_id}" onclick="event.preventDefault(); markNotificationAsSeen(${element.audit_trail_id});"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </a>
                        `);
                    }

                    // Attach click handler to toggle visibility only if contentData.length > 0
                    $('#notification-btn').off('click').on('click', function () {
                        $('#notifications').toggle(); // Toggle the visibility of notifications
                    });

                    // Mark notification as seen
                    $(document).on('click', '.mark-as-seen', function() {
                        const notificationId = $(this).data('id');
                        markNotificationAsSeen(notificationId);
                    });
                    
                } else {
                    $('#notifications').html('No new notifications.');
                    $('#nf-n').hide(); 
                    $('#notification-btn').off('click').on('click', function () {
                    $('#notifications').toggle(); // Toggle the visibility of notifications
                    });
                }
            }
        });
    }
});



// Example of marking a notification as seen                    
function markNotificationAsSeen(notificationId) {
    // Show loading indicator or temporary text
    $('#nf-n').text('..');

    $.ajax({
        type: 'POST',
        url: 'markAsSeen.php', // This script marks a notification as seen
        data: {
            notification_id: notificationId
        },
        success: function (response) {
            console.log(notificationId);
            let responseData = JSON.parse(response);
            if (responseData.success) {
                // Remove the notification from the DOM
                $(`button[data-id="${notificationId}"]`).parent().remove();
                // Update notification count
                let updatedCount = parseInt($('#nf-n').text(), 10) - 1;
                $('#nf-n').text(updatedCount > 0 ? updatedCount : '...');
            } else {
                // Handle failure (optionally show previous count or an error message)
                $('#nf-n').text('Error'); // Or revert to the previous count
            }
        },
        error: function() {
            // Handle AJAX error
            $('#nf-n').text('Error'); // Indicate error
        },
        complete: function() {
            // This callback function runs regardless of the success or error
            // Update the count properly here if not already updated in success/error
        }
    });
}



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

document.addEventListener('DOMContentLoaded', function () {
    const toggleButton2 = document.getElementById('toggleButton2');
    const sidebar = document.getElementById('sidebar2');

    if (toggleButton2 && sidebar) {
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
        toggleButton2.addEventListener('click', () => {
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

$(document).ready(function() {
    const dataTable = $('#myTable').DataTable();

    // Add event listener for the filter dropdown
    $('#filterDate').on('change', function() {
            const filterValue = this.value; // Get the selected option value
        if (filterValue === 'todo') {
            // Show all rows when "Mostrar todos" is selected
            dataTable.column(2).search('').draw();
        } else if (filterValue === 'hoy') {
            // Get the current date in the desired format
            var currentDate = new Date();
            var currentDateFormat = currentDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

            // Set the default filter value to the current date
            dataTable.column(2).search(currentDateFormat, true, false).draw();   
        } else {
            dataTable.column(2).search(filterValue, true, false).draw();
        }
    });
           // Get the current date in the desired format
            var currentDate = new Date();
            var currentDateFormat = currentDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

            // Set the default filter value to the current date
            dataTable.column(2).search(currentDateFormat, true, false).draw(); 

    // Add event listener for the filter dropdown for first name (if needed).
    $('#filterFirstName').on('change', function() {
            const filterValue = this.value; // Get the selected option value
        if (filterValue === '') {
            // Show all rows when "Mostrar todos" is selected
            dataTable.column(1).search('').draw();
        } else {
            // Filter based on the selected option
            dataTable.column(1).search(filterValue, true, false).draw();
        }
    });

    // Add event listener for the filter dropdown for first name (if needed).
    $('#filterStatus').on('change', function() {
            const filterValue = this.value; // Get the selected option value
        if (filterValue === '') {
            // Show all rows when "Mostrar todos" is selected
            dataTable.column(12).search('').draw();
        } else {
            // Filter based on the selected option
            dataTable.column(12).search(filterValue, true, false).draw();
        }
    });

});

$(document).ready(function() {
    const dataTable = $('#senderTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50, 100],
    });

    // Add event listener for the filter dropdown for first name (if needed).
    $('#filterFormStatus').on('change', function() {
        const filterValue = this.value; // Get the selected option value
        if (filterValue === '') {
            // Show all rows when "Mostrar todos" is selected
            dataTable.column(3).search('').draw();
        } else {
            // Filter based on the selected option
            dataTable.column(3).search(filterValue, true, false).draw();
        }
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

