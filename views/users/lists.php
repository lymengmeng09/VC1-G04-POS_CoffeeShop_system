<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Member management</h2>
    </div>
    <!-- Search and Buttons Row -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <input type="search" class="form-control" id="searchInput" placeholder="Search" aria-label="Search" oninput="searchTable()">
            </div>
        </div>
        <div class="col-md-6 text-end">
            <!-- Role Filter Dropdown -->
            <div class="btn-group me-2">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-prmary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
    <div class="table-responsive">
        <table class="table table-striped" id="user">
            <thead>
                <tr>
                    <th></th>
                    <th>Customers Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <?php if (AccessControl::isAdmin()): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?> <?php foreach ($users as $user): ?>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role_name']) ?></td>
                            <?php if (AccessControl::isAdmin()): ?>
                                <td>
                                    <?php if (AccessControl::hasPermission('edit_users')): ?>
                                        <a href="/edit-user?id=<?= htmlspecialchars($user['id']) ?>" class="btn text-dark btn-warning">Edit</a>
                                    <?php endif; ?>

                                    <?php if (AccessControl::hasPermission('delete_users')): ?>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#user<?= $user['id'] ?>">
                                            delete
                                        </button>
                                        <!-- Modal -->
                                        <?php require 'delete.php' ?>
                                    <?php endif; ?>
                                    <?php if (AccessControl::hasPermission('reset_password')): ?>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#resetPassword<?= $user['id'] ?>">
                                            Reset Password
                                        </button>
                                        <!-- Reset Password Modal -->
                                        <div class="modal fade" id="resetPassword<?= $user['id'] ?>" tabindex="-1" aria-labelledby="resetPasswordLabel<?= $user['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordLabel<?= $user['id'] ?>">Reset Password for <?= htmlspecialchars($user['name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/resetpassword?id=<?= $user['id'] ?>" method="POST" id="resetForm<?= $user['id'] ?>" class="needs-validation reset-password-form" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                    <div class="form-outline mb-3">
                        <label class="form-label" for="password<?= $user['id'] ?>">New Password</label>
                        <input type="password" name="password" id="password<?= $user['id'] ?>" class="form-control password-field"
                            placeholder="Create a password" required minlength="8" />
                        <div class="invalid-feedback">
                            Password must be at least 8 characters long.
                        </div>
                    </div>
                    <div class="form-outline mb-3">
                        <label class="form-label" for="confirm_password<?= $user['id'] ?>">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password<?= $user['id'] ?>" class="form-control confirm-password-field"
                            placeholder="Confirm your password" required />
                        <div class="invalid-feedback">
                            Passwords do not match.
                        </div>
                    </div>
                    <div class="form-check mb-3 d-flex align-items-center gap-2">
                        <input type="checkbox" id="show_password<?= $user['id'] ?>" class="show-password-checkbox">
                        <label class="form-check-label" for="show_password<?= $user['id'] ?>">Show Password</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?><?php else: ?>
                        <tr>
                            <td colspan="6" id="noResultsMessage" style="display: none;">No records found</td>
                        </tr>
                    <?php endif; ?>
            </tbody>
        </table>
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">4</a></li>
            <li class="page-item"><a class="page-link" href="#">5</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>
</div>
