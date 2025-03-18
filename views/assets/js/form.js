/**
 * Universal Form Validation Script
 * Works with login, user creation, and password reset forms
 */
document.addEventListener("DOMContentLoaded", function() {
    // Add CSS for validation styling if not already in your stylesheet
    addValidationStyles();
    
    // Initialize login form validation
    initLoginFormValidation();
    
    // Initialize password forms validation (create user and reset password)
    initPasswordFormsValidation();
    
    // Initialize password visibility toggles
    initPasswordToggles();
});

// Function to add validation styles if not already in stylesheet
function addValidationStyles() {
    // Check if styles already exist
    if (!document.getElementById('validation-styles')) {
        const styleSheet = document.createElement('style');
        styleSheet.id = 'validation-styles';
        styleSheet.textContent = `
            .form-control.is-invalid {
                border-color: #dc3545;
                padding-right: calc(1.5em + 0.75rem);
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            }
            
            .invalid-feedback {
                display: none;
                width: 100%;
                margin-top: 0.25rem;
                font-size: 0.875em;
                color: #dc3545;
            }
            
            .is-invalid ~ .invalid-feedback {
                display: block;
            }
        `;
        document.head.appendChild(styleSheet);
    }
}

// Function to initialize login form validation
function initLoginFormValidation() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;
    
    // Get form fields
    const emailField = loginForm.querySelector('input[name="email"]');
    const passwordField = loginForm.querySelector('input[name="password"]');
    
    // Add input event listeners for real-time validation
    if (emailField) {
        emailField.addEventListener('input', function() {
            validateRequiredField(this, 'Email is required.');
        });
    }
    
    if (passwordField) {
        passwordField.addEventListener('input', function() {
            validateRequiredField(this, 'Password is required.');
        });
    }
    
    // Add submit event listener
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        
        // Validate email
        if (emailField) {
            const emailValid = validateRequiredField(emailField, 'Email is required.');
            isValid = isValid && emailValid;
        }
        
        // Validate password
        if (passwordField) {
            const passwordValid = validateRequiredField(passwordField, 'Password is required.');
            isValid = isValid && passwordValid;
        }
        
        // Submit if valid
        if (isValid) {
            this.submit();
        }
    });
}

// Function to initialize password forms validation (create user and reset password)
function initPasswordFormsValidation() {
    // Get all forms with password fields (except login)
    const passwordForms = document.querySelectorAll('form:not(#loginForm)');
    
    passwordForms.forEach(form => {
        // Skip if not a form with password fields
        const passwordField = form.querySelector('input[name="password"]');
        const confirmPasswordField = form.querySelector('input[name="confirm_password"]');
        if (!passwordField || !confirmPasswordField) return;
        
        // Add input event listeners for password field
        passwordField.addEventListener('input', function() {
            validatePasswordField(this);
            
            // Also validate confirm password if it has a value
            if (confirmPasswordField.value) {
                validateConfirmPasswordField(confirmPasswordField, this);
            }
        });
        
        // Add input event listeners for confirm password field
        confirmPasswordField.addEventListener('input', function() {
            validateConfirmPasswordField(this, passwordField);
        });
        
        // Add input event listeners for other required fields
        const requiredFields = form.querySelectorAll('input[required]:not([name="password"]):not([name="confirm_password"]), select[required]');
        requiredFields.forEach(field => {
            field.addEventListener('input', function() {
                let errorMessage = 'This field is required.';
                
                // Custom messages based on field type or name
                if (field.type === 'email') {
                    errorMessage = 'Please provide a valid email address.';
                } else if (field.name === 'name') {
                    errorMessage = 'Name is required.';
                } else if (field.name === 'role_id') {
                    errorMessage = 'Please select a role.';
                }
                
                validateRequiredField(field, errorMessage);
            });
        });
        
        // Add submit event listener
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            
            // Validate password fields
            const passwordValid = validatePasswordField(passwordField);
            const confirmValid = validateConfirmPasswordField(confirmPasswordField, passwordField);
            isValid = isValid && passwordValid && confirmValid;
            
            // Validate other required fields
            requiredFields.forEach(field => {
                let errorMessage = 'This field is required.';
                
                // Custom messages based on field type or name
                if (field.type === 'email') {
                    errorMessage = 'Please provide a valid email address.';
                } else if (field.name === 'name') {
                    errorMessage = 'Name is required.';
                } else if (field.name === 'role_id') {
                    errorMessage = 'Please select a role.';
                }
                
                const fieldValid = validateRequiredField(field, errorMessage);
                isValid = isValid && fieldValid;
            });
            
            // Submit if valid
            if (isValid) {
                this.submit();
            }
        });
    });
}

// Function to initialize password visibility toggles
function initPasswordToggles() {
    const checkboxes = document.querySelectorAll('.show-password-checkbox, input[type="checkbox"][id^="show_password"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Find the closest form
            const form = this.closest('form');
            
            // Find password fields within this form
            const passwordFields = form.querySelectorAll('input[type="password"], input[type="text"][name="password"], input[type="text"][name="confirm_password"]');
            
            // Toggle password visibility
            passwordFields.forEach(field => {
                field.type = this.checked ? 'text' : 'password';
            });
        });
    });
}

// Function to validate a required field
function validateRequiredField(field, errorMessage) {
    // Find the error message element
    const errorElement = field.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
        console.warn('No error element found for field:', field);
        return true;
    }
    
    // Check if empty
    if (!field.value.trim()) {
        field.classList.add('is-invalid');
        errorElement.textContent = errorMessage;
        return false;
    }
    
    // Check email format if it's an email field
    if (field.type === 'email' && !isValidEmail(field.value)) {
        field.classList.add('is-invalid');
        errorElement.textContent = 'Please enter a valid email address.';
        return false;
    }
    
    // Valid field
    field.classList.remove('is-invalid');
    return true;
}

// Function to validate password field
function validatePasswordField(passwordField) {
    // Find the error message element
    const errorElement = passwordField.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
        console.warn('No error element found for password field:', passwordField);
        return true;
    }
    
    // Check if empty
    if (!passwordField.value) {
        passwordField.classList.add('is-invalid');
        errorElement.textContent = 'Password is required.';
        return false;
    }
    
    // Check length (minimum 8 characters)
    if (passwordField.value.length < 8) {
        passwordField.classList.add('is-invalid');
        errorElement.textContent = 'Password must be at least 8 characters long.';
        return false;
    }
    
    // Valid password
    passwordField.classList.remove('is-invalid');
    return true;
}

// Function to validate confirm password field
function validateConfirmPasswordField(confirmPasswordField, passwordField) {
    // Find the error message element
    const errorElement = confirmPasswordField.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
        console.warn('No error element found for confirm password field:', confirmPasswordField);
        return true;
    }
    
    // Check if empty
    if (!confirmPasswordField.value) {
        confirmPasswordField.classList.add('is-invalid');
        errorElement.textContent = 'Please confirm your password.';
        return false;
    }
    
    // Check if matches password
    if (confirmPasswordField.value !== passwordField.value) {
        confirmPasswordField.classList.add('is-invalid');
        errorElement.textContent = 'Passwords do not match.';
        return false;
    }
    
    // Valid confirmation
    confirmPasswordField.classList.remove('is-invalid');
    return true;
}

// Function to validate email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}