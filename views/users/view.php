<?php
$user = $_SESSION['user'];
?>

<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <img src="<?= htmlspecialchars($user['profile']) ?>" alt="<?= htmlspecialchars($user['name']) ?>" class="rounded-circle me-3" width="64" height="64">
                    <div class="user">
                        <h2 class="card-title mb-0" style="color: #432115ce; font-weight:600;"><?= htmlspecialchars($user['name']) ?></h2>
                        <p class="card-text mb-0" style="color: #006241;"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-md-end gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="manageProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear me-1"></i> Manage Profile
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="manageProfileDropdown">
                        <li>
                            <a href="/edit-user?id=<?= htmlspecialchars($user['id']) ?>" class="drop">
                                <i class="bi bi-pencil mx-3"></i>Edit Profile
                            </a>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#resetPassword<?= $user['id'] ?>">
                                <i class="bi bi-arrow-clockwise me-2"></i> Change Password
                            </button>
                        </li>
                    </ul>
                </div>
                <?php require 'reset-password.php'; ?>
            </div>
        </div>
    </div>
    <hr class="mt-2">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted">Full Name</label>
                    <div class="fw-medium"><?= htmlspecialchars($user['name']) ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted">Email Address</label>
                    <div class="fw-medium"><?= htmlspecialchars($user['email']) ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted">Role</label>
                    <div class="fw-medium"><?= htmlspecialchars($user['role_name']) ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted ">Member Since</label>
                    <div class="fw-medium"><?= htmlspecialchars($user['created_at']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>