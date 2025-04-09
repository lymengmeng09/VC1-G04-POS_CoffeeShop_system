<?php
$user = $_SESSION['user'];
?>

<div class="card mb-4">
    <div class="card-header py-3" style="background: #FFFAEB;">
        <div class="row align-items-center" >
            <div class="col-md-8 mb-md-0">
                <div class="d-flex align-items-center">
                    <img src="<?= htmlspecialchars($user['profile']) ?>" onerror="this.src='/views/assets/images/profile.png'" alt="<?= htmlspecialchars($user['name']) ?>" class="rounded-circle me-3" width="64" height="64">
                    <div class="user">
                        <h2 class="card-title mb-0" style="color: #432115ce; font-weight:600;"><?= htmlspecialchars($user['name']) ?></h2>
                        <p class="card-text mb-0" style="color: #006241;"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-md-end gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-success dropdown-toggle" type="button" id="manageProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear me-1"></i> <?php echo __('manage_profile'); ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="manageProfileDropdown">
                        <li class="edit">
                            <a href="/edit-user?id=<?= htmlspecialchars($user['id']) ?>" class="edit-link">
                                <i class="bi bi-pencil"></i> <?php echo __('edit_profile'); ?>
                            </a>
                        </li>
                        <li>
                            <button class="dropdown-item btn-reset" type="button" data-bs-toggle="modal" data-bs-target="#resetPassword<?= $user['id'] ?>">
                                <i class="bi bi-arrow-clockwise"></i> <?php echo __('change_password'); ?>
                            </button>
                        </li>
                    </ul>
                </div>
                <?php require 'reset-password.php'; ?>
            </div>
        </div>
    </div>
    <div class="card-body mt-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted"><?php echo __('full_name'); ?></label>
                    <div class="fw-medium"><?= htmlspecialchars($user['name']) ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted"><?php echo __('email_address'); ?></label>
                    <div class="fw-medium"><?= htmlspecialchars($user['email']) ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted"><?php echo __('role'); ?></label>
                    <div class="fw-medium"><?= htmlspecialchars($user['role_name']) ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted "><?php echo __('member_since'); ?></label>
                    <div class="fw-medium"><?= htmlspecialchars($user['created_at']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>