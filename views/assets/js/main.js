document.addEventListener("DOMContentLoaded", function () {
    const currentPath = window.location.pathname;
    const sidebarItems = document.querySelectorAll('.sidebar-item a');

    sidebarItems.forEach(item => {
        if (item.getAttribute('href') === currentPath) {
            item.parentElement.classList.add('active');
        }
    });
});
// Perfect Scrollbar Init
if (typeof PerfectScrollbar == 'function') {
    const container = document.querySelector(".sidebar-wrapper");
    const ps = new PerfectScrollbar(container, {
        wheelPropagation: false
    });
}

// Scroll into active sidebar
document.querySelector('.sidebar-item.active').scrollIntoView(false)
// Function to toggle the password visibility
function togglePassword() {
    // Get all password fields and checkboxes
    var passwords = document.querySelectorAll("#password");
    var confirmPasswords = document.querySelectorAll("#confirm_password");
    var showPasswordCheckboxes = document.querySelectorAll("#show_password");

    // Loop through each checkbox to find the one that was clicked
    showPasswordCheckboxes.forEach(function (checkbox, index) {
        if (checkbox.checked) {
            // Show the password fields
            passwords[index].type = "text";
            confirmPasswords[index].type = "text";
        } else {
            // Hide the password fields
            passwords[index].type = "password";
            confirmPasswords[index].type = "password";
        }
    });
}

// Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
    // Get all forms
    var forms = document.querySelectorAll("#userForm");

    // Add submit event listener to each form
    forms.forEach(function (form) {
        form.addEventListener("submit", function (e) {
            // Find the password fields within this specific form
            var password = this.querySelector("#password").value;
            var confirmPassword = this.querySelector("#confirm_password").value;

            // Check if passwords match
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                e.preventDefault(); // Prevent form submission
            }
        });
    });
});