<div class="d-flex justify-content-between align-items-center mb-2">
    <h5>Member management</h5>
</div>
<div class="card">
    <div class="card-body">

        <!-- Search and Buttons Row -->
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="search" class="form-control searchbar" id="searchInput" placeholder="Search" aria-label="Search" oninput="searchTable()">
                </div>
            </div>
            <div class="col-md-6 text-end">
                <!-- Role Filter Dropdown -->
                <div class="btn-group me-2">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Role: <?= htmlspecialchars(ucfirst($_GET['role'] ?? 'all')) ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <li><a class="dropdown-item" href="?role=all&status=<?= $_GET['status'] ?? 'all' ?>">All Roles</a></li>
                        <li><a class="dropdown-item" href="?role=Admin&status=<?= $_GET['status'] ?? 'all' ?>">Admin</a></li>
                        <li><a class="dropdown-item" href="?role=Staff&status=<?= $_GET['status'] ?? 'all' ?>">Staff</a></li>
                        <li><a class="dropdown-item" href="?role=Customer&status=<?= $_GET['status'] ?? 'all' ?>">Customer</a></li>
                    </ul>
                </div>
                <!-- Add User Button (only for admins) -->
                <?php if (AccessControl::hasPermission('create_users')): ?>
                    <a href="/users/create" class="btn btn-primary">
                        + Add User
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <table class="table w-100" id="user">
        <thead>
            <tr>
                <th></th>
                <th>User Name</th>
                <th>Email</th>
                <th>Role</th>
                <?php if (AccessControl::isAdmin()): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
    <?php 
    // Filter out the current user from the $users array
    $filteredUsers = array_filter($users, function($user) {
        return $user['id'] != $_SESSION['user']['id'];
    });
    
    if (!empty($filteredUsers)): 
    ?>
        <?php foreach ($filteredUsers as $user): ?>
            <tr>
                <td></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role_name']) ?></td>
                <?php if (AccessControl::isAdmin()): ?>
                    <td>
                        <?php if (AccessControl::hasPermission('reset_password')): ?>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#resetPassword<?= $user['id'] ?>" style="border: none; background:none; color: green;">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                            <!-- Reset Password Modal -->
                            <div class="modal fade" id="resetPassword<?= $user['id'] ?>" tabindex="-1" aria-labelledby="resetPasswordLabel<?= $user['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="resetPasswordLabel<?= $user['id'] ?>">Change Password for <?= htmlspecialchars($user['name']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/resetpassword?id=<?= $user['id'] ?>" method="POST" id="resetForm<?= $user['id'] ?>" class="needs-validation reset-password-form" novalidate>
                                        <div class="modal-body">
                                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                                            <div class="form-outline mb-3 position-relative">
                                                <label class="form-label" for="password">New Password <span class="star">*</span></label>
                                                <i class="fa fa-eye position-absolute toggle-password" id="togglePassword" style="cursor: pointer; right: 10px; transform: translateY(-50%);"></i>
                                                <input type="password" name="password" id="password" class="form-control password-field"
                                                    placeholder="Create a password" required minlength="8" />
                                                <div class="invalid-feedback">
                                                    Password must be at least 8 characters long.
                                                </div>
                                            </div>
                                            <div class="form-outline mb-3 position-relative">
                                                <label class="form-label" for="confirm_password">Confirm Password <span class="star">*</span></label>
                                                <i class="fa fa-eye position-absolute toggle-password" id="toggleConfirmPassword" style="cursor: pointer; right: 10px; transform: translateY(-50%);"></i>
                                                <input type="password" name="confirm_password" id="confirm_password" class="form-control confirm-password-field"
                                                    placeholder="Confirm your password" required />
                                                <div class="invalid-feedback">
                                                    Passwords do not match.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (AccessControl::hasPermission('edit_users')): ?>
                            <a href="/edit-user?id=<?= htmlspecialchars($user['id']) ?>"><i class="bi bi-pencil-square"></i></a>
                        <?php endif; ?>
                        <?php if (AccessControl::hasPermission('delete_users')): ?>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#user<?= $user['id'] ?>" class="btn-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                            <?php require 'delete.php' ?>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" id="noResultsMessage" style="display: none;">No records found</td>
        </tr>
    <?php endif; ?>
</tbody>
    </table>
</div>