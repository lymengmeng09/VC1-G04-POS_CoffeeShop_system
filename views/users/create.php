<div class="card">
    <div class="card-body">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger">
                <?php echo $errors['general']; ?>
            </div>
        <?php endif; ?>
        
        <form action="/users/store" method="POST" id="userForm" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" placeholder="Enter your name" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                       id="name" name="name" value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    <?php echo $errors['name'] ?? 'Name is required.'; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" placeholder="Enter your email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                       id="email" name="email" value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    <?php echo $errors['email'] ?? 'Please provide a valid email address.'; ?>
                </div>
            </div>
            <div class="form-outline mb-3 position-relative">
                <label class="form-label" for="password">Password</label>
                <i class="fa fa-eye position-absolute toggle-password" id="togglePassword" style="cursor: pointer; right: 10px; transform: translateY(-50%);"></i>
                <input type="password" name="password" id="password" 
                       class="form-control password-field <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                       placeholder="Create a password" required minlength="8" />
                <div class="invalid-feedback">
                    <?php echo $errors['password'] ?? 'Password must be at least 8 characters long.'; ?>
                </div>
            </div>
            <div class="form-outline mb-3 position-relative">
                <label class="form-label" for="confirm_password">Confirm Password</label>
                <i class="fa fa-eye position-absolute toggle-password" id="toggleConfirmPassword" style="cursor: pointer; right: 10px; transform: translateY(-50%);"></i>
                <input type="password" name="confirm_password" id="confirm_password" 
                       class="form-control confirm-password-field <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
                       placeholder="Confirm your password" required />
                <div class="invalid-feedback">
                    <?php echo $errors['confirm_password'] ?? 'Passwords do not match.'; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="role_id" class="form-label">Role:</label>
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

