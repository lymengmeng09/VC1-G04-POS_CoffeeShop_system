<div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Member management</h2>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <div class="input-group" style="max-width: 330px; height:40px;">
                <input type="text" class="form-control" placeholder="Search" aria-label="Search">
            </div>
            <div class="d-flex justify-content-between mb-3">
                <div class="d-flex gap-2 me-2">
                    <button class="btn btn-primary add-user-btn" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add User</button>
                </div>
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Role: <?= htmlspecialchars(ucfirst($_GET['role'] ?? 'all')) ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?role=all&status=<?= $_GET['status'] ?? 'all' ?>">All Roles</a></li>
                        <li><a class="dropdown-item" href="?role=Admin&status=<?= $_GET['status'] ?? 'all' ?>">Admin</a></li>
                        <li><a class="dropdown-item" href="?role=Staff&status=<?= $_GET['status'] ?? 'all' ?>">Staff</a></li>
                        <li><a class="dropdown-item" href="?role=Customer&status=<?= $_GET['status'] ?? 'all' ?>">Customer</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Status: <?= htmlspecialchars(ucfirst($_GET['status'] ?? 'all')) ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?role=<?= $_GET['role'] ?? 'all' ?>&status=all">All Status</a></li>
                        <li><a class="dropdown-item" href="?role=<?= $_GET['role'] ?? 'all' ?>&status=active">Active</a></li>
                        <li><a class="dropdown-item" href="?role=<?= $_GET['role'] ?? 'all' ?>&status=inactive">Inactive</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Customers Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning text-dark">Edit</a>
                                    <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                    
                                </td>


                                <td>...</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No records found</td></tr>
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role">
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                                <option value="Customer">Customer</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>