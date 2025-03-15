<div class="card">
    <div class="card-body">
            <form action="/users/store" method="POST" id="userForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-outline mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Create a password" required />
                </div>
                <div class="form-outline mb-3">
                    <label class="form-label" for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                        placeholder="Confirm your password" required />
                </div>
                <div class="form-check mb-3 d-flex align-items-center gap-2">
                    <input type="checkbox" id="show_password" onclick="togglePassword()">Show Password
                    <label class="form-check-label" for="show_password"></label>
                </div>
                <div class="mb-3">
                    <label for="role_id" class="form-label">Role:</label>
                    <select name="role_id" class="form-control">
                        <option value="">Select a role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"><?= $role['role_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>
    </div>
</div>

<script>
    // Function to toggle the password visibility
    function togglePassword() {
        var password = document.getElementById("password");
        var confirmPassword = document.getElementById("confirm_password");
        var showPasswordCheckbox = document.getElementById("show_password");

        if (showPasswordCheckbox.checked) {
            password.type = "text"; // Show the password
            confirmPassword.type = "text"; // Show the confirm password
        } else {
            password.type = "password"; // Hide the password
            confirmPassword.type = "password"; // Hide the confirm password
        }
    }

    // Function to validate password match
    document.getElementById("userForm").addEventListener("submit", function(e) {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;

        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            e.preventDefault(); // Prevent form submission
        }
    });
</script>