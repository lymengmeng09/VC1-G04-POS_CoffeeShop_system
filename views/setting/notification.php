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
                <a class="nav-link" href="#">General</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/setting/notification">Notification</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="#">Role & Permission</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Security</a>
            </li>
        </ul>
     
 
</body>
</html>



