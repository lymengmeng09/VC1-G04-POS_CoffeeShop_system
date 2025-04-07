/**
 * Universal Form Validation Script with Multilingual Support
 */
document.addEventListener("DOMContentLoaded", () => {
  // Add CSS for validation styling if not already in your stylesheet
  addValidationStyles()

  // Initialize login form validation
  initLoginFormValidation()

  // Initialize password forms validation (create user and reset password)
  initPasswordFormsValidation()

  // Initialize password visibility toggles
  initPasswordToggles()
})

// Function to add validation styles if not already in stylesheet
function addValidationStyles() {
  // Check if styles already exist
  if (!document.getElementById("validation-styles")) {
    const styleSheet = document.createElement("style")
    styleSheet.id = "validation-styles"
    styleSheet.textContent = `
            .form-control.is-invalid {
                border-color: #dc3545;
                background-image: none !important; /* Explicitly remove background image */
                padding-right: 0.75rem !important; /* Reset padding to normal */
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
            
            /* Override Bootstrap's default validation icons */
            .was-validated .form-control:invalid,
            .was-validated .form-control.is-invalid {
                background-image: none !important;
                padding-right: 0.75rem !important;
            }
        `
    document.head.appendChild(styleSheet)
  }
}

// Get translations from the global object
function getTranslation(key) {
  // Check if translations object exists
  if (window.validationMessages && window.validationMessages[key]) {
    return window.validationMessages[key]
  }
  // Fallback to default English messages
  // const defaultTranslations = {
  //   'email_required': 'Email is required.',
  //   'password_required': 'Password is required.',
  //   'field_required': 'This field is required.',
  //   'valid_email': 'Please enter a valid email address.',
  //   'name_required': 'Name is required.',
  //   'role_required': 'Please select a role.',
  //   'password_length': 'Password must be at least 8 characters long.',
  //   'confirm_password': 'Please confirm your password.',
  //   'passwords_not_match': 'Passwords do not match.'
  // }
  return defaultTranslations[key] || key
}

// Function to initialize login form validation
function initLoginFormValidation() {
  const loginForm = document.getElementById("loginForm")
  if (!loginForm) return

  // Get form fields
  const emailField = loginForm.querySelector('input[name="email"]')
  const passwordField = loginForm.querySelector('input[name="password"]')

  // Add input event listeners for real-time validation
  if (emailField) {
    emailField.addEventListener("input", function () {
      validateRequiredField(this, getTranslation('email_required'))
    })
  }

  if (passwordField) {
    passwordField.addEventListener("input", function () {
      validateRequiredField(this, getTranslation('password_required'))
      updateToggleIconPosition(this)
    })
  }

  // Add submit event listener
  loginForm.addEventListener("submit", function (e) {
    e.preventDefault()

    let isValid = true

    // Validate email
    if (emailField) {
      const emailValid = validateRequiredField(emailField, getTranslation('email_required'))
      isValid = isValid && emailValid
    }

    // Validate password
    if (passwordField) {
      const passwordValid = validateRequiredField(passwordField, getTranslation('password_required'))
      isValid = isValid && passwordValid
      updateToggleIconPosition(passwordField)
    }

    // Submit if valid
    if (isValid) {
      this.submit()
    }
  })
}

// Function to initialize password forms validation (create user and reset password)
function initPasswordFormsValidation() {
  // Get all forms with password fields (except login)
  const passwordForms = document.querySelectorAll("form:not(#loginForm)")

  passwordForms.forEach((form) => {
    // Skip if not a form with password fields
    const passwordField = form.querySelector('input[name="password"]')
    const confirmPasswordField = form.querySelector('input[name="confirm_password"]')
    if (!passwordField || !confirmPasswordField) return

    // Add input event listeners for password field
    passwordField.addEventListener("input", function () {
      validatePasswordField(this)
      updateToggleIconPosition(this)

      // Also validate confirm password if it has a value
      if (confirmPasswordField.value) {
        validateConfirmPasswordField(confirmPasswordField, this)
        updateToggleIconPosition(confirmPasswordField)
      }
    })

    // Add input event listeners for confirm password field
    confirmPasswordField.addEventListener("input", function () {
      validateConfirmPasswordField(this, passwordField)
      updateToggleIconPosition(this)
    })

    // Add input event listeners for other required fields
    const requiredFields = form.querySelectorAll(
      'input[required]:not([name="password"]):not([name="confirm_password"]), select[required]',
    )
    requiredFields.forEach((field) => {
      field.addEventListener("input", () => {
        let errorMessage = getTranslation('field_required')

        // Custom messages based on field type or name
        if (field.type === "email") {
          errorMessage = getTranslation('valid_email')
        } else if (field.name === "name") {
          errorMessage = getTranslation('name_required')
        } else if (field.name === "role_id") {
          errorMessage = getTranslation('role_required')
        }

        validateRequiredField(field, errorMessage)
      })
    })

    // Add submit event listener
    form.addEventListener("submit", function (e) {
      e.preventDefault()

      let isValid = true

      // Validate password fields
      const passwordValid = validatePasswordField(passwordField)
      const confirmValid = validateConfirmPasswordField(confirmPasswordField, passwordField)
      isValid = isValid && passwordValid && confirmValid

      // Update toggle icon positions
      updateToggleIconPosition(passwordField)
      updateToggleIconPosition(confirmPasswordField)

      // Validate other required fields
      requiredFields.forEach((field) => {
        let errorMessage = getTranslation('field_required')

        // Custom messages based on field type or name
        if (field.type === "email") {
          errorMessage = getTranslation('valid_email')
        } else if (field.name === "name") {
          errorMessage = getTranslation('name_required')
        } else if (field.name === "role_id") {
          errorMessage = getTranslation('role_required')
        }

        const fieldValid = validateRequiredField(field, errorMessage)
        isValid = isValid && fieldValid
      })

      // Submit if valid
      if (isValid) {
        this.submit()
      }
    })
  })
}

// Function to update toggle icon position based on validation state
function updateToggleIconPosition(inputField) {
  // Find the associated toggle icon
  const parent = inputField.parentElement
  if (!parent) return

  const toggleIcon = parent.querySelector(".toggle-password")
  if (!toggleIcon) return

  // Check if the input field has validation error
  if (inputField.classList.contains("is-invalid")) {
    // Move the icon to 40% when there's an error
    toggleIcon.style.top = "58%"
  } else {
    // Remove the inline style to let the original CSS take effect
    toggleIcon.style.removeProperty("top")
  }
}

// Function to initialize password toggles
function initPasswordToggles() {
  // Get all password toggle icons
  const toggleIcons = document.querySelectorAll(".toggle-password")

  toggleIcons.forEach((icon) => {
    // Find the associated password input (comes after the icon in your HTML)
    const passwordInput = icon.nextElementSibling
    if (!passwordInput || !passwordInput.classList.contains("form-control")) return

    icon.addEventListener("click", function () {
      // Toggle password visibility
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
      passwordInput.setAttribute("type", type)

      // Toggle icon
      this.classList.toggle("bi-eye-slash-fill")
    })
  })
}

// Function to validate a required field
function validateRequiredField(field, errorMessage) {
  // Find the error message element
  const errorElement = field.nextElementSibling
  if (!errorElement || !errorElement.classList.contains("invalid-feedback")) {
    console.warn("No error element found for field:", field)
    return true
  }

  // Check if empty
  if (!field.value.trim()) {
    field.classList.add("is-invalid")
    errorElement.textContent = errorMessage
    return false
  }

  // Check email format if it's an email field
  if (field.type === "email" && !isValidEmail(field.value)) {
    field.classList.add("is-invalid")
    errorElement.textContent = getTranslation('valid_email')
    return false
  }

  // Valid field
  field.classList.remove("is-invalid")
  return true
}

// Function to validate password field
function validatePasswordField(passwordField) {
  // Find the error message element
  const errorElement = passwordField.nextElementSibling
  if (!errorElement || !errorElement.classList.contains("invalid-feedback")) {
    console.warn("No error element found for password field:", passwordField)
    return true
  }

  // Check if empty
  if (!passwordField.value) {
    passwordField.classList.add("is-invalid")
    errorElement.textContent = getTranslation('password_required')
    return false
  }

  // Check length (minimum 8 characters)
  if (passwordField.value.length < 8) {
    passwordField.classList.add("is-invalid")
    errorElement.textContent = getTranslation('password_length')
    return false
  }

  // Valid password
  passwordField.classList.remove("is-invalid")
  return true
}

// Function to validate confirm password field
function validateConfirmPasswordField(confirmPasswordField, passwordField) {
  // Find the error message element
  const errorElement = confirmPasswordField.nextElementSibling
  if (!errorElement || !errorElement.classList.contains("invalid-feedback")) {
    console.warn("No error element found for confirm password field:", confirmPasswordField)
    return true
  }

  // Check if empty
  if (!confirmPasswordField.value) {
    confirmPasswordField.classList.add("is-invalid")
    errorElement.textContent = getTranslation('confirm_password')
    return false
  }

  // Check if matches password
  if (confirmPasswordField.value !== passwordField.value) {
    confirmPasswordField.classList.add("is-invalid")
    errorElement.textContent = getTranslation('passwords_not_match')
    return false
  }

  // Valid confirmation
  confirmPasswordField.classList.remove("is-invalid")
  return true
}

// Function to validate email format
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

