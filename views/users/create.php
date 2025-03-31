<?php
// Start the session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize $user with default values if not set
$user = $_SESSION['user'] ?? ['profile' => 'views/assets/images/profile.png'];
?>
<div class="card">
    <div class="card-body">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger">
                <?php echo $errors['general']; ?>
            </div>
        <?php endif; ?>
        <form action="/users/store" method="POST" id="userForm" class="needs-validation" novalidate enctype="multipart/form-data">
            <!-- Other form fields -->
            <div class="mb-3">
                <label for="productImage">Profile Image</label>
                <div class="image-upload-container">
                    <input type="file" class="file-input" id="productImage" name="profile_image" accept="image/*" required>
                    <div class="image-preview-box" id="uploadBox" onclick="triggerFileInput()">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <p>Upload Image</p>
                        </div>
                        <img id="imagePreview" src="#" alt="Profile Image Preview" style="display: none; max-width: 100%; max-height: 100%;">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="star">*</span></label>
                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
                    id="name" name="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    <?php echo $errors['name'] ?? 'Name is required.'; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="star">*</span></label>
                <input type="email" placeholder="Enter your email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                    id="email" name="email" value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    <?php echo $errors['email'] ?? 'Please provide a valid email address.'; ?>
                </div>
            </div>
            <div class="form-outline mb-3 position-relative">
                <label class="form-label" for="password">Password <span class="star">*</span></label>
                <i class="fa fa-eye position-absolute toggle-password" id="togglePassword" style="cursor: pointer; right: 10px; transform: translateY(-50%);"></i>
                <input type="password" name="password" id="password"
                    class="form-control password-field <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                    placeholder="Create a password" required minlength="8" />
                <div class="invalid-feedback">
                    <?php echo $errors['password'] ?? 'Password must be at least 8 characters long.'; ?>
                </div>
            </div>
            <div class="form-outline mb-3 position-relative">
                <label class="form-label" for="confirm_password">Confirm Password <span class="star">*</span></label>
                <i class="fa fa-eye position-absolute toggle-password" id="toggleConfirmPassword" style="cursor: pointer; right: 10px; transform: translateY(-50%);"></i>
                <input type="password" name="confirm_password" id="confirm_password"
                    class="form-control confirm-password-field <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
                    placeholder="Confirm your password" required />
                <div class="invalid-feedback">
                    <?php echo $errors['confirm_password'] ?? 'Passwords do not match.'; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="role_id" class="form-label">Role <span class="star">*</span></label>
                <select name="role_id" class="form-control <?php echo isset($errors['role_id']) ? 'is-invalid' : ''; ?>" required>
                    <option value="">Select a role</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?php echo ($formData['role_id'] ?? '') == $role['id'] ? 'selected' : ''; ?>>
                            <?= $role['role_name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <div class="invalid-feedback">
                    <?php echo $errors['role_id'] ?? 'Please select a role.'; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Submit</button>
        </form>
    </div>
</div>
<style>
    .image-upload-container {
        margin-bottom: 20px;
    }

    .image-preview-box {
        width: 200px;
        height: 200px;
        border: 2px dashed #ccc;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .upload-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #999;
    }

    .upload-placeholder p {
        margin-top: 10px;
        margin-bottom: 0;
        font-size: 14px;
    }

    #imagePreview {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .file-input {
        display: none;
    }
</style>

<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get the file input element
        const fileInput = document.getElementById('productImage');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');

        // Add event listener for file selection
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Set the image source to the loaded data URL
                    imagePreview.src = e.target.result;
                    // Make the image visible
                    imagePreview.style.display = 'block';
                    // Hide the upload placeholder
                    uploadPlaceholder.style.display = 'none';
                };

                // Read the selected file as a data URL
                reader.readAsDataURL(this.files[0]);
            } else {
                // If no file is selected, hide the preview and show the placeholder
                imagePreview.style.display = 'none';
                uploadPlaceholder.style.display = 'flex';
                imagePreview.src = '#';
            }
        });
    });

    // Function to trigger the file input when clicking on the upload box
    function triggerFileInput() {
        document.getElementById('productImage').click();
    }
</script>