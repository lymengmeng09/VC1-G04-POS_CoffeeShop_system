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
                <?php echo __($errors['general']); ?>
            </div>
        <?php endif; ?>
        <form action="/users/store" method="POST" id="userForm" class="needs-validation" novalidate enctype="multipart/form-data">
            <!-- Other form fields -->
            <div class="form-group">
                <label for="productImage"><?php echo __('profile_image'); ?></label>
                <div class="image-upload-container">
                    <input type="file" class="file-input" id="productImage" name="profile_image" accept="image/*" required>
                    <div class="image-preview-box" id="uploadBox" onclick="triggerFileInput()">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <p><?php echo __('upload_image'); ?></p>
                        </div>
                        <img id="imagePreview" src="#" alt="<?php echo __('profile_image_preview'); ?>" style="display: none; max-width: 100%; max-height: 100%;">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="form-label"><?php echo __('name'); ?> <span class="star">*</span></label>
                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
                    id="name" name="name" placeholder="<?php echo __('enter_name_placeholder'); ?>" value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    <?php echo isset($errors['name']) ? __($errors['name']) : __('name_required'); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="form-label"><?php echo __('email'); ?> <span class="star">*</span></label>
                <input type="email" placeholder="<?php echo __('enter_email_placeholder'); ?>" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                    id="email" name="email" value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    <?php echo isset($errors['email']) ? __($errors['email']) : __('valid_email'); ?>
                </div>
            </div>
            <div class="form-outline form-group position-relative">
                <label class="form-label" for="password"><?php echo __('password'); ?> <span class="star">*</span></label>
                <i class="bi-eye-fill position-absolute toggle-password" style="cursor: pointer; right: 10px;"></i>
                <input type="password" name="password" id="password"
                    class="form-control password-field <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                    placeholder="<?php echo __('create_password_placeholder'); ?>" required minlength="8" />
                <div class="invalid-feedback">
                    <?php echo isset($errors['password']) ? __($errors['password']) : __('password_length'); ?>
                </div>
            </div>
            <div class="form-outline form-group position-relative">
                <label class="form-label" for="confirm_password"><?php echo __('confirm_password_label'); ?> <span class="star">*</span></label>
                <i class="bi-eye-fill position-absolute toggle-password" style="cursor: pointer; right: 10px;"></i>
                <input type="password" name="confirm_password" id="confirm_password"
                    class="form-control confirm-password-field <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
                    placeholder="<?php echo __('confirm_password_placeholder'); ?>" required />
                <div class="invalid-feedback">
                    <?php echo isset($errors['confirm_password']) ? __($errors['confirm_password']) : __('passwords_not_match'); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="role_id" class="form-label"><?php echo __('role'); ?> <span class="star">*</span></label>
                <select name="role_id" class="form-control <?php echo isset($errors['role_id']) ? 'is-invalid' : ''; ?>" required>
                    <option value=""><?php echo __('select_role'); ?></option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?php echo ($formData['role_id'] ?? '') == $role['id'] ? 'selected' : ''; ?>>
                            <?= $role['role_name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <div class="invalid-feedback">
                    <?php echo isset($errors['role_id']) ? __($errors['role_id']) : __('role_required'); ?>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3"><?php echo __('submit'); ?></button>
        </form>
    </div>
</div>

<!-- Add validation messages for JavaScript -->
<script>
// Create a global validation messages object
window.validationMessages = {
    'email_required': '<?php echo __('email_required'); ?>',
    'password_required': '<?php echo __('password_required'); ?>',
    'field_required': '<?php echo __('field_required'); ?>',
    'valid_email': '<?php echo __('valid_email'); ?>',
    'name_required': '<?php echo __('name_required'); ?>',
    'role_required': '<?php echo __('role_required'); ?>',
    'password_length': '<?php echo __('password_length'); ?>',
    'confirm_password': '<?php echo __('confirm_password'); ?>',
    'passwords_not_match': '<?php echo __('passwords_not_match'); ?>'
};
</script>

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