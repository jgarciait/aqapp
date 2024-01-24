// Section 1: Password Toggle Functionality
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

// Section 2: Human Validation Checkbox
const humanValidationCheckbox = document.getElementById('iamARobot');
humanValidationCheckbox.addEventListener('change', function () {
    if (humanValidationCheckbox.checked) {
        // Checkbox is checked, enable form submission
    } else {
        // Checkbox is unchecked, you can show a message if required
        // Prevent form submission here or display a message to check the checkbox
    }
});

// Section 3: Modal Handling for Captcha
document.querySelector('.close').addEventListener('click', function () {
    document.getElementById('myModal').style.display = 'none';
    document.getElementById('captcha-question').style.display = 'none';
});

// Section 4: Close Modal When Clicking Outside
window.addEventListener('click', function (event) {
    if (event.target == document.getElementById('myModal')) {
        document.getElementById('myModal').style.display = 'none';
        document.getElementById('captcha-question').style.display = 'none';
    }
});

// Section 5: Robot Checkbox and Modal
document.addEventListener("DOMContentLoaded", function () {
    const soyRobotCheckbox = document.getElementById("humanValidationCheckbox2");
    const robotModal = document.getElementById("robotModal");
    const closeRobotModal = document.getElementById("closeRobotModal");
    const loginForm = document.getElementById("loginForm"); // Assuming you have a login form element

    soyRobotCheckbox.addEventListener("change", function () {
        if (soyRobotCheckbox.checked) {
            robotModal.style.display = "block";
        }
    });

    loginForm.addEventListener("submit", function (event) {
        if (soyRobotCheckbox.checked) {
            event.preventDefault();
            alert("Confirme que no es un Robot.");
        }
    });

    closeRobotModal.addEventListener("click", function () {
        robotModal.style.display = "none";
    });
});

// Section 6: Sidebar Toggle Functionality
const toggleButton = document.getElementById('toggleButton');
const sidebar = document.getElementById('sidebar');

toggleButton.addEventListener('click', () => {
    sidebar.classList.toggle('expanded');
});

// Section 7: DataTable Initialization
$(document).ready(function () {
    const table = $('#templateTable').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Buscar: "
            // Add other language customizations here
        }
    });

    $('#filterName').change(function () {
        const selectedName = $(this).val();
        table.column(0).search(selectedName).draw();
    });

    $('#filterService').change(function () {
        const selectedName = $(this).val();
        table.column(1).search(selectedName).draw();
    });
});

// Section 8: Profile Card Toggle
let card = document.querySelector(".card");
let displayPicture = document.querySelector(".display-picture");

displayPicture.addEventListener("click", function () {
    card.classList.toggle("hidden");
});

// Section 9: Bootstrap Popovers
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
});

// Section 10: Footer Scroll Handling
let lastScrollPosition = 0;
const footer = document.getElementById('myFooter');

function handleScroll() {
    const currentScrollPosition = window.scrollY;

    if (currentScrollPosition > lastScrollPosition) {
        footer.style.transform = 'translateY(100%)';
    } else {
        footer.style.transform = 'translateY(0)';
    }

    lastScrollPosition = currentScrollPosition;
}

window.addEventListener('scroll', handleScroll);

// Section 11: Form Modals
document.getElementById('openModal-1').addEventListener('click', function () {
    $('#forgotPass').modal('show');
});
