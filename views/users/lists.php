<div class="d-flex justify-content-between align-items-center mb-2">
    <h5>Member management</h5>
</div>
<div class="card">
    <div class="card-body">

        <!-- Search and Buttons Row -->
        <div class="row mb-3">
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
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role_name']) ?></td>
                        <?php if (AccessControl::isAdmin()): ?>
                            <td>
                                <?php if (AccessControl::hasPermission('reset_password')): ?>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#resetPassword<?= $user['id'] ?>" class="btn-reset">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
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