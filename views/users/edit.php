<div class="card">
    <div class="card-body">
        <div class="container">
            <!-- Form for updating user details -->
            <form method="POST" action="/update-user?id=<?= $user['id'] ?>" id="userForm" class="needs-validation" novalidate enctype="multipart/form-data">
                <!-- Profile Image Upload Section -->
                <div class="form-group">
                    <label for="profileImage">Profile Image</label>
                    <div class="image-upload-container">
                        <input type="file" class="file-input" id="profileImage" name="profile_image" accept="image/*">
                        <div class="image-preview-box" id="uploadBox" onclick="triggerFileInput()">
                            <img id="imagePreview" src="/<?= htmlspecialchars($user['profile']) ?>" alt="Profile Image Preview" style="max-width: 100%; max-height: 100%;">
                        </div>
                    </div>
                </div>

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <?php if (AccessControl::isAdmin()): ?>
                    <!-- Role Selection Dropdown (visible to admins) -->
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role:</label>
                        <select name="role_id" class="form-control" required>
                            <option value="">Select a role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>" <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['role_name']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php else: ?>
                    <!-- Hidden role_id field (for non-admins) -->
                    <input type="hidden" name="role_id" value="<?= $user['role_id'] ?>">
                <?php endif; ?>
                <!-- Save and Cancel Buttons -->
                <button type="submit" class="btn btn-primary mt-2">Save</button>
                <a href="/list-users" class="btn btn-secondary mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Image Preview -->
<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get the file input element
        const fileInput = document.getElementById('profileImage');
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
        document.getElementById('profileImage').click();
    }
// In your edit view file
$(document).ready(function() {
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Update profile info on the page immediately
                $('.user-name').text($('#name').val());
                $('.user-email').text($('#email').val());
                
                // If profile image was updated
                if ($('#profile_image').val()) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.user-profile-img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL($('#profile_image')[0].files[0]);
                }
                
                // Show success message
                alert('Profile updated successfully!');
            }
        });
    });
});
</script>