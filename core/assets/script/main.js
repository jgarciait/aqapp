function openTab(tabName) {
    var i, tabcontent;
    tabcontent = document.getElementsByClassName("tab");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    document.getElementById(tabName).style.display = "block";
}

// Check if the success message element exists
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.querySelector('.alert-success');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 60000); // 60000 milliseconds = 1 minute
    }
});

// AQMessenger Scripts Start
document.addEventListener('DOMContentLoaded', function () {
    const searchBar = document.querySelector('.users .contact-search-1 input'),
          contactsList = document.querySelector('.users .contacts-list');
    
    // Check if all elements exist
    if (searchBar && contactsList) {
        // Setup button click event only if all elements are found
        searchBar.onclick = () => {
            searchBar.classList.toggle('active');
            searchBar.focus();
            searchBar.value = "";
        };
        
searchBar.addEventListener('keyup', async () => {
    let searchTerm = searchBar.value;
    if (searchTerm !== "") {
        searchBar.classList.add('active');
    } else {
        searchBar.classList.remove('active');
    }
    try {
        const response = await fetch('contactsSearch.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `searchTerm=${encodeURIComponent(searchTerm)}`,
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.text();
        contactsList.innerHTML = data;
    } catch (error) {
        console.error('Failed to search contacts:', error);
    }
});

        
// Use async function to handle asynchronous operations more cleanly
async function fetchContacts() {
    try {
        const response = await fetch('contacts.php');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.text();
        contactsList.innerHTML = data;
    } catch (error) {
        console.error('Failed to fetch contacts:', error);
    }
}
// Setup the interval for fetching contacts as all elements are present
const intervalId = setInterval(fetchContacts, 1500); // Adjust time as needed
    } 
});

document.addEventListener('DOMContentLoaded', function () {
    const chatForm = document.querySelector('.typing-area');
    if (!chatForm) {
        return;
    }

    const inputField = chatForm.querySelector('.input-field'),
        sendBtn = chatForm.querySelector('button'),
        chatBoxContent = document.querySelector('.chat-box-content');

    if (inputField && sendBtn && chatBoxContent) {
      
        chatForm.onsubmit = (e) => {
            e.preventDefault(); // Prevent form submission
        }
        
        sendBtn.onclick = () => {
      
    
        // Let's start AJAX
        let xhr = new XMLHttpRequest(); // Creating XML object
        xhr.open("POST", "insert-chat.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    inputField.value = ""; // Clear the input field          
                }
            }
        }
        let formData = new FormData(chatForm); // Creating new formData object
        xhr.send(formData); // Sending the form data to insert-chat.php
        }
        // Setup the interval for fetching contacts as all elements are present
        setInterval(() => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "get-chat.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;
                        chatBoxContent.innerHTML = data;
              
                    }
                } 
            };
            xhr.onerror = () => {
                // Additional error handling for network errors
                console.error('Network error occurred while fetching contacts.');
            };
            let formData = new FormData(chatForm); // Creating new formData object
            xhr.send(formData); // Sending the form data to insert-chat.php
        }, 800);
    }
});

    // We have to send the form data through ajax to php

// AQMessenger Scripts End

// Profile Script
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        var modal = document.getElementById("profileModal");
        
        if (event.target.matches(".profile-image")) {
            if (modal) {
                modal.style.display = "block";
            }
        } else if (modal && event.target == modal) {
            modal.style.display = "none";
        }
    });
});

// Notification Scripts Test

$(document).ready(function () {
    $('#notifications').hide(); // Initially hide the notifications area

    // Check if in-app notifications are enabled
    if (userInAppNotiEnabled === 1) {
        var notificationInterval = setInterval(fetchNotifications, 3000); // Call fetchNotifications at regular intervals
        fetchNotifications(); // Initial call
    }


    function fetchNotifications() {
        $.ajax({
            type: 'POST',
            url: 'fetchNotifications.php',
            data: { key: '123' },
            dataType: 'json', // Expect a JSON response
            cache: false,
            success: function (response) {
                // Check for a specific status or message in the JSON response
                if (response.status === 'disabled' || response.message === 'In-app notifications are disabled.' || !response) {
                    clearInterval(notificationInterval); // Stop the interval
                    $('#notifications').hide();
                    $('#nf-n').hide();
                    return; // Exit the function
                }

                let contentData = response; // Assuming this is already parsed JSON
                // Further processing of contentData as before...
                $('#notifications').html('');
                console.log(contentData);
                if (contentData.length > 1) {
                    // Update notification count
                    $('#nf-n').text(contentData[0].total);
                    $('#nf-n').show(); 
                    // Populate notifications
                function formatDate(timestamp) {
                    const date = new Date(timestamp);
                    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                    return date.toLocaleDateString('en-US', options);
                }
           
                    $('#notifications').append(`
                        <div>
                            <button id="mark-all-seen" style="color: white; font-size: 16px;" class="btn btn-sm"><i class="fa-solid fa-eye"></i> Mark all as seen</button>
                        <hr>
                    </div>`);
                    
                for (let i = 1; i < contentData.length; i++) {
                    const element = contentData[i];
                    let linkHref;

                    // Determine the link based on the action
                    if (element.actions === "Completed") {
                        linkHref = `completedDataTable.php?workflow_id=${element.workflow_id}`;
                    } else if (element.actions === "Rejected") {
                        linkHref = `rejectedDataTable.php?workflow_id=${element.workflow_id}`;
                    } else {
                        // Default to sender or receiver link based on is_receiver flag
                        linkHref = element.is_receiver ? `receiverDataTable.php?workflow_id=${element.workflow_id}` : `senderDataTable.php?workflow_id=${element.workflow_id}`;
                    }
                    
           
                    // Append the notification element with the dynamically determined linkHref
                    $('#notifications').append(`
                        <a style="color:white;" href="${linkHref}" class="notification-link">
                            <div class="notification-item">
                                ${element.ref_number} was ${element.actions} on ${formatDate(element.fl_timestamp)}
                                <button style="color:white; font-size: 14px;" class="btn btn-sm mark-as-seen" data-id="${element.audit_trail_id}" onclick="event.preventDefault(); markNotificationAsSeen(${element.audit_trail_id});"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </a>
                    `);
                }


                    // Attach click handler to toggle visibility only if contentData.length > 0
                    $('#notification-btn').off('click').on('click', function () {
                        $('#notifications').toggle(); // Toggle the visibility of notifications
                    });
                    
                    // Event listener for "Mark all as seen" button
                    $('#mark-all-seen').on('click', function() {
                        markAllNotificationsAsSeen();
                    });

                    // Mark notification as seen
                    $(document).on('click', '.mark-as-seen', function() {
                        const notificationId = $(this).data('id');
                        markNotificationAsSeen(notificationId);
                    });
                    
                } else {
                    $('#notifications').text('No new notifications');
                    $('#nf-n').hide(); 
                    $('#notification-btn').off('click').on('click', function () {
                    $('#notifications').toggle(); // Toggle the visibility of notifications
                    });
                }
            }
        });
    }
});

// Function to mark all notifications as seen
function markAllNotificationsAsSeen() {
    $.ajax({
        type: 'POST',
        url: 'markAllAsSeen.php',
        success: function(response) {
            let responseData = JSON.parse(response);
            if (responseData.success) {
                fetchNotifications(); // Re-fetch notifications to update UI
            } else {
                console.log('Failed to mark all as seen');
            }
        },
        error: function() {
            console.log('Error marking all notifications as seen');
        }
    });
}



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

