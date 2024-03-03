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
// Open the Login Form modal
document.getElementById('openLoginModal').addEventListener('click', function () {
    $('#loginModal').modal('show');
});

// Section 1: Checkbox Event Listener
const notARobotCheckbox = document.getElementById('iamNotARobot');
const modalText = document.getElementById('modal-text');

notARobotCheckbox.addEventListener('change', function () {
    if (notARobotCheckbox.checked) {
        // Checkbox is checked, set the modal text
        modalText.textContent = 'Are you a robot?';
        document.getElementById('myModal').style.display = 'block';
    } else {
        // Checkbox is unchecked, clear modal text and hide modal
        modalText.textContent = '';
        document.getElementById('myModal').style.display = 'none';
    }
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
document.getElementById('closeModal').addEventListener('click', function () {
    document.getElementById('myModal').style.display = 'none';
    // Optionally, you can clear the modal text content here if needed.
    modalText.textContent = '';
});

// Section 4: Close Modal When Clicking Outside
window.addEventListener('click', function (event) {
    if (event.target == document.getElementById('myModal')) {
        document.getElementById('myModal').style.display = 'none';
        // Optionally, you can clear the modal text content here if needed.
        modalText.textContent = '';
    }
});

