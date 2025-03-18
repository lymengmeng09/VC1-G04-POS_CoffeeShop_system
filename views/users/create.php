<div class="card">
    <div class="card-body">
        <form action="/users/store" method="POST" id="userForm" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <div class="invalid-feedback">
                    Name is required.
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">
                    Please provide a valid email address.
                </div>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control password-field"
                    placeholder="Create a password" required minlength="8" />
                <div class="invalid-feedback">
                    Password must be at least 8 characters long.
                </div>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label" for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control confirm-password-field"
                    placeholder="Confirm your password" required />
                <div class="invalid-feedback">
                    Passwords do not match.
                </div>
            </div>
            <div class="form-check mb-3 d-flex align-items-center gap-2">
                <input type="checkbox" id="show_password" class="show-password-checkbox">
                <label class="form-check-label" for="show_password">Show Password</label>
            </div>
            <div class="mb-3">
                <label for="role_id" class="form-label">Role:</label>
                <select name="role_id" class="form-control" required>
                    <option value="">Select a role</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>"><?= $role['role_name'] ?></option>
                    <?php endforeach ?>
                </select>
                <div class="invalid-feedback">
                    Please select a role.
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Submit</button>
        </form>
    </div>
</div>