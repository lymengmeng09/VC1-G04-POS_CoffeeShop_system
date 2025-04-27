<h5><?php echo __('Member management'); ?></h5>
<div class="card">
    <div class="card-body">

        <!-- Search and Buttons Row -->
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="search" class="form-control searchbar" id="searchInput" placeholder="<?php echo __('search user'); ?>"
                        aria-label="Search" oninput="searchTable()">
                </div>
            </div>
            <div class="col-md-6 text-end">
                <!-- Role Filter Dropdown -->
                <div class="btn-group role-category me-2">
                    <button id="drop1" type="button" class="btn btn-outline-primary dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo __('role'); ?>: <?= htmlspecialchars(ucfirst($_GET['role'] ?? 'all')) ?>
                    </button>
                    <ul class="dropdown-menu menu-role" aria-labelledby="drop1">
                        <li><a class="dropdown-item role-item" href="?role=all&status=<?= $_GET['status'] ?? 'all' ?>"><?php echo __('All role'); ?></a></li>
                        <li><a class="dropdown-item role-item" href="?role=Admin&status=<?= $_GET['status'] ?? 'all' ?>"><?php echo __('admin'); ?></a></li>
                        <li><a class="dropdown-item role-item" href="?role=Staff&status=<?= $_GET['status'] ?? 'all' ?>"><?php echo __('Staff'); ?></a></li>
                        <li><a class="dropdown-item role-item"
                                href="?role=Customer&status=<?= $_GET['status'] ?? 'all' ?>">Customer</a></li>
                    </ul>
                </div>
                <!-- Add User Button (only for admins) -->
                <?php if (AccessControl::hasPermission('create_users')): ?>
                    <a href="/users/create" class="btn btn-primary">
                        + <?php echo __('add_user'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <table class="table w-100" id="user">
        <thead>
            <tr>
                <th></th>
                <th><?php echo __('username'); ?></th>
                <th><?php echo __('email'); ?></th>
                <th><?php echo __('role'); ?></th>
                <?php if (AccessControl::isAdmin()): ?>
                    <th><?php echo __('Action'); ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Filter out the current user from the $users array
            $filteredUsers = array_filter($users, function ($user) {
                return $user['id'] != $_SESSION['user']['id'];
            });


            if (!empty($filteredUsers)):
            ?>
                <?php foreach ($filteredUsers as $user): ?>
                    <tr>
                        <td></td>
                        <td class="py-2 "><img
                                src="/<?= !empty($user['profile']) ? htmlspecialchars($user['profile']) : 'views/assets/images/profile.png' ?>"
                                alt="" class="rounded-circle me-2" onerror="this.src='/views/assets/images/profile.png'" style="width: 40px; height:40px; object-fit: cover;">
                            <span><?= htmlspecialchars($user['name']) ?></span>
                        </td>
                        <td style="color:rgb(106, 106, 106);"><?= htmlspecialchars($user['email']) ?></td>
                        <td style="color:rgb(17, 136, 51); "><?= htmlspecialchars($user['role_name']) ?></td>
                        <?php if (AccessControl::isAdmin()): ?>
                            <td>
                                <div class="btn-group dropend">
                                    <a href="#" class="text-secondary" style="margin-left: 20px;" id="dropdownMenuButton<?= $user['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-start" style="padding-right: 30px;" aria-labelledby="dropdownMenuButton<?= $user['id'] ?>">
                                        <?php if (AccessControl::hasPermission('reset_password')): ?>
                                            <li>
                                                <button type="button" class="dropdown-item btn-reset" data-bs-toggle="modal" data-bs-target="#resetPassword<?= $user['id'] ?>">
                                                    <i class="bi bi-arrow-clockwise"></i> <?php echo __('Change password'); ?>
                                                </button>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (AccessControl::hasPermission('edit_users')): ?>
                                            <li class="edit">
                                                <a href="/edit-user?id=<?= htmlspecialchars($user['id']) ?>" class="edit-link">
                                                    <i class="bi bi-pencil-square"></i> <?php echo __('edit_user'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (AccessControl::hasPermission('delete_users')): ?>
                                            <li>
                                                <button type="button" class="dropdown-item btn-delete" data-bs-toggle="modal" data-bs-target="#user<?= $user['id'] ?>">
                                                    <i class="bi bi-trash"></i> <?php echo __('delete_user'); ?>
                                                </button>

                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <?php require 'reset-password.php'; ?>
                                <?php require 'delete.php' ?>
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