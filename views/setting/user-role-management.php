<?php
// Start session to maintain user state
session_start();

// Mock database functions
function getRoles() {
    return ['Admin', 'Manager', 'Staff'];
}

function getPermissions($role) {
    // In a real app, this would fetch from database
    $permissions = [
        'Admin' => [
            'create_products' => true,
            'edit_products' => true,
            'delete_products' => true,
            'manage_orders' => true,
            'manage_users' => true,
            'manage_settings' => true
        ]
    ];
    
    return $permissions[$role] ?? [];
}

function savePermissions($role, $permissions) {
    // In a real app, this would save to database
    $_SESSION['permissions'][$role] = $permissions;
    return true;
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'Admin';
    $permissions = [
        'create_products' => isset($_POST['create_products']),
        'edit_products' => isset($_POST['edit_products']),
        'delete_products' => isset($_POST['delete_products']),
        'manage_orders' => isset($_POST['manage_orders']),
        'manage_users' => isset($_POST['manage_users']),
        'manage_settings' => isset($_POST['manage_settings'])
    ];
    
    if (savePermissions($role, $permissions)) {
        $message = 'Permissions saved successfully!';
    }
}

$selectedRole = $_GET['role'] ?? 'Admin';
$currentPermissions = getPermissions($selectedRole);
?>

    <div class="settings-container">
        <h2 class="mb-4">Settings</h2>
        
        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Settings Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link" href="/setting">General</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Notification</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/setting/UserRole">Role & Permission</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Security</a>
            </li>
        </ul>

        <form method="post">
            <!-- Role Selection -->
            <div class="mb-4">
                <label class="form-label">Select Role</label>
                <select name="role" class="form-select">
                    <option value="Admin" <?php echo $selectedRole === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="Manager" <?php echo $selectedRole === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                    <option value="Staff" <?php echo $selectedRole === 'Staff' ? 'selected' : ''; ?>>Staff</option>
                </select>
                <div class="form-text">Choose a role to modify its permissions</div>
            </div>

            <!-- Permissions -->
            <div class="mb-4">
                <h5 class="mb-3">Permissions</h5>

                <div class="permission-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="permission-title">Create Products</h6>
                            <p class="permission-description">Can create new products in the system</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="create_products" 
                                <?php echo ($currentPermissions['create_products'] ?? false) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                </div>

                <div class="permission-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="permission-title">Edit Products</h6>
                            <p class="permission-description">Can modify existing products</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="edit_products"
                                <?php echo ($currentPermissions['edit_products'] ?? false) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                </div>

                <div class="permission-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="permission-title">Delete Products</h6>
                            <p class="permission-description">Can remove products from the system</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="delete_products"
                                <?php echo ($currentPermissions['delete_products'] ?? false) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                </div>

                <div class="permission-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="permission-title">Manage Orders</h6>
                            <p class="permission-description">Can view and process orders</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="manage_orders"
                                <?php echo ($currentPermissions['manage_orders'] ?? false) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                </div>

                <div class="permission-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="permission-title">Manage Users</h6>
                            <p class="permission-description">Can manage user accounts</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="manage_users"
                                <?php echo ($currentPermissions['manage_users'] ?? false) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                </div>

                <div class="permission-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="permission-title">Manage Settings</h6>
                            <p class="permission-description">Can modify system settings</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="manage_settings"
                                <?php echo ($currentPermissions['manage_settings'] ?? false) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-save">Save Change</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add event listener to role select to reload page with new role
        document.querySelector('select[name="role"]').addEventListener('change', function() {
            window.location.href = '?role=' + this.value;
        });
    </script>
</body>
</html>